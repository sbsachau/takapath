<?php
/**
 * API Client — fetches rate data from TransferSmartly.
 *
 * Results are cached as WordPress transients (default: 3600s).
 * In local development (WP_ENVIRONMENT_TYPE === 'local' or TAKAPATH_USE_MOCK_DATA === true),
 * a static mock dataset is returned so LocalWP works without hitting the live API.
 */

declare( strict_types=1 );

namespace TakaPath\Rates;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class API_Client {

	/** Transient key prefix. */
	private const CACHE_PREFIX = 'takapath_rates_';

	/** Default cache lifetime in seconds. */
	private const DEFAULT_TTL = 3600;

	/**
	 * Fetch (or return cached) rates for a given source currency + amount.
	 *
	 * Returns:
	 *   [
	 *     'success' => bool,
	 *     'source'  => 'live' | 'cache' | 'stale' | 'mock',
	 *     'data'    => [ ...normalised rows ],
	 *   ]
	 *
	 * @param string $from   Source currency ISO 4217.
	 * @param int    $amount Amount to send.
	 * @return array
	 */
	public static function get_rates( string $from, int $amount ): array {

		// ── Local-dev mock bypass ─────────────────────────────────────────────
		if ( self::is_local_dev() ) {
			return [
				'success' => true,
				'source'  => 'mock',
				'data'    => self::mock_rates( $from, $amount ),
			];
		}

		// ── Transient cache ───────────────────────────────────────────────────
		$cache_key = self::CACHE_PREFIX . strtolower( $from ) . '_' . $amount;
		$cached    = get_transient( $cache_key );

		if ( false !== $cached && is_array( $cached ) ) {
			return [
				'success' => true,
				'source'  => 'cache',
				'data'    => $cached,
			];
		}

		// ── Live API call ─────────────────────────────────────────────────────
		$api_base = defined( 'TAKAPATH_API_BASE' ) ? TAKAPATH_API_BASE : 'https://transfersmartly.com';
		$ttl      = defined( 'TAKAPATH_CACHE_TTL' ) ? (int) TAKAPATH_CACHE_TTL : self::DEFAULT_TTL;

		$url = add_query_arg(
			[
				'fromCurrency' => rawurlencode( $from ),
				'toCurrency'   => 'BDT',
				'toCountry'    => 'BD',
				'sendAmount'   => $amount,
			],
			trailingslashit( $api_base ) . 'api/compare'
		);

		$response = wp_remote_get( $url, [
			'timeout'    => 8,
			'user-agent' => 'TakaPath-Plugin/1.0 (+https://takapath.com)',
			'headers'    => [ 'Accept' => 'application/json' ],
		] );

		// ── HTTP error — try stale transient then hard fail ───────────────────
		if ( is_wp_error( $response ) ) {
			$stale = get_option( self::CACHE_PREFIX . 'stale_' . strtolower( $from ) . '_' . $amount );
			if ( $stale ) {
				return [ 'success' => false, 'source' => 'stale', 'data' => $stale ];
			}
			return [ 'success' => false, 'source' => 'error', 'data' => [] ];
		}

		$code = wp_remote_retrieve_response_code( $response );
		if ( 200 !== (int) $code ) {
			$stale = get_option( self::CACHE_PREFIX . 'stale_' . strtolower( $from ) . '_' . $amount );
			if ( $stale ) {
				return [ 'success' => false, 'source' => 'stale', 'data' => $stale ];
			}
			return [ 'success' => false, 'source' => 'error', 'data' => [] ];
		}

		$body = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( ! is_array( $body ) ) {
			return [ 'success' => false, 'source' => 'error', 'data' => [] ];
		}

		$normalised = self::normalise( $body, $from, $amount );

		// Store fresh + stale fallback
		set_transient( $cache_key, $normalised, $ttl );
		update_option( self::CACHE_PREFIX . 'stale_' . strtolower( $from ) . '_' . $amount, $normalised, false );

		return [ 'success' => true, 'source' => 'live', 'data' => $normalised ];
	}

	// ── Helpers ───────────────────────────────────────────────────────────────

	/**
	 * Detect local dev environment.
	 * True when WP_ENVIRONMENT_TYPE is 'local' OR TAKAPATH_USE_MOCK_DATA constant is true.
	 */
	private static function is_local_dev(): bool {
		if ( defined( 'TAKAPATH_USE_MOCK_DATA' ) && TAKAPATH_USE_MOCK_DATA ) {
			return true;
		}
		if ( function_exists( 'wp_get_environment_type' ) ) {
			return 'local' === wp_get_environment_type();
		}
		return false;
	}

	/**
	 * Normalise the TransferSmartly /api/compare response array into a
	 * flat list of rows the shortcode can iterate.
	 *
	 * @param array  $body   Decoded JSON response.
	 * @param string $from   Source currency.
	 * @param int    $amount Send amount.
	 * @return array
	 */
	private static function normalise( array $body, string $from, int $amount ): array {
		$rows   = $body['results'] ?? $body;
		$output = [];

		if ( ! is_array( $rows ) ) {
			return [];
		}

		foreach ( $rows as $row ) {
			if ( ! is_array( $row ) ) {
				continue;
			}
			$rate          = (float) ( $row['exchangeRate'] ?? $row['exchange_rate'] ?? 0 );
			$fee           = (float) ( $row['fee'] ?? $row['totalFee'] ?? 0 );
			$recv          = (float) ( $row['recipientAmount'] ?? $row['recipient_gets'] ?? ( ( $amount - $fee ) * $rate ) );
			$output[]      = [
				'provider'        => (string) ( $row['provider'] ?? $row['providerName'] ?? '' ),
				'provider_logo'   => (string) ( $row['providerLogo'] ?? $row['provider_logo'] ?? '' ),
				'exchange_rate'   => $rate,
				'fee'             => $fee,
				'recipient_gets'  => $recv,
				'transfer_speed'  => (string) ( $row['transferSpeed'] ?? $row['transfer_speed'] ?? '' ),
				'receive_methods' => (array)  ( $row['receiveMethods'] ?? $row['receive_methods'] ?? [] ),
				'transfer_url'    => (string) ( $row['transferUrl'] ?? $row['transfer_url'] ?? '' ),
			];
		}

		// Sort best rate first (highest recipient_gets).
		usort( $output, fn( $a, $b ) => $b['recipient_gets'] <=> $a['recipient_gets'] );

		return $output;
	}

	/**
	 * Static mock data for local development.
	 * Reflects realistic GBP→BDT rates as of mid-2026.
	 * Other currencies scale proportionally.
	 *
	 * @param string $from   Source currency.
	 * @param int    $amount Send amount.
	 * @return array
	 */
	private static function mock_rates( string $from, int $amount ): array {
		// Mid-market approximate rates to BDT (mid-2026 ballpark)
		$mid_rates = [
			'GBP' => 155.20,
			'USD' => 122.80,
			'EUR' => 132.40,
			'SAR' => 32.70,
			'AED' => 33.45,
			'MYR' => 26.10,
			'OMR' => 319.20,
			'KWD' => 399.80,
			'QAR' => 33.70,
			'SGD' => 91.20,
			'CAD' => 90.50,
			'AUD' => 79.60,
			'ITL' => 132.40,
		];

		$mid = $mid_rates[ $from ] ?? $mid_rates['GBP'];

		// Each provider applies a markup % and a fixed fee.
		$providers = [
			[
				'provider'       => 'Wise',
				'markup'         => 0.0065,
				'fee'            => 3.69,
				'speed'          => 'Within hours',
				'methods'        => [ 'bank_deposit', 'bkash' ],
				'url'            => 'https://wise.com',
			],
			[
				'provider'       => 'Remitly',
				'markup'         => 0.010,
				'fee'            => 0.00,
				'speed'          => '3–5 minutes',
				'methods'        => [ 'bank_deposit', 'bkash', 'cash_pickup' ],
				'url'            => 'https://remitly.com',
			],
			[
				'provider'       => 'Western Union',
				'markup'         => 0.035,
				'fee'            => 4.90,
				'speed'          => 'Minutes',
				'methods'        => [ 'bank_deposit', 'cash_pickup', 'home_delivery' ],
				'url'            => 'https://westernunion.com',
			],
			[
				'provider'       => 'Xoom (PayPal)',
				'markup'         => 0.020,
				'fee'            => 4.99,
				'speed'          => 'Minutes',
				'methods'        => [ 'bank_deposit', 'bkash' ],
				'url'            => 'https://xoom.com',
			],
			[
				'provider'       => 'Small World',
				'markup'         => 0.025,
				'fee'            => 2.99,
				'speed'          => '1–2 business days',
				'methods'        => [ 'bank_deposit', 'cash_pickup' ],
				'url'            => 'https://smallworldfs.com',
			],
			[
				'provider'       => 'MoneyGram',
				'markup'         => 0.030,
				'fee'            => 5.99,
				'speed'          => 'Minutes',
				'methods'        => [ 'bank_deposit', 'cash_pickup' ],
				'url'            => 'https://moneygram.com',
			],
		];

		$output = [];
		foreach ( $providers as $p ) {
			$rate  = $mid * ( 1 - $p['markup'] );
			$recv  = ( $amount - $p['fee'] ) * $rate;
			$output[] = [
				'provider'       => $p['provider'],
				'provider_logo'  => '',
				'exchange_rate'  => round( $rate, 4 ),
				'fee'            => $p['fee'],
				'recipient_gets' => round( max( 0, $recv ), 2 ),
				'transfer_speed' => $p['speed'],
				'receive_methods'=> $p['methods'],
				'transfer_url'   => $p['url'],
			];
		}

		// Best rate first.
		usort( $output, fn( $a, $b ) => $b['recipient_gets'] <=> $a['recipient_gets'] );

		return $output;
	}

	/**
	 * Clear all cached rates (called from Admin settings page).
	 */
	public static function clear_cache(): void {
		global $wpdb;
		$wpdb->query(
			$wpdb->prepare(
				"DELETE FROM {$wpdb->options} WHERE option_name LIKE %s OR option_name LIKE %s",
				'_transient_' . self::CACHE_PREFIX . '%',
				'_transient_timeout_' . self::CACHE_PREFIX . '%'
			)
		);
	}
}
