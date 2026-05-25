<?php
/**
 * API Client for TakaPath Rates plugin.
 *
 * Single responsibility: fetch comparison results from the TransferSmartly
 * /api/compare endpoint, cache them as WordPress transients, and return a
 * normalised array that the rest of the plugin can consume without knowing
 * anything about the upstream API shape.
 *
 * Source of truth: TransferSmartly (transfersmartly.com)
 * TakaPath never stores or manages rates — it only reads and caches them.
 */

declare( strict_types=1 );

namespace TakaPath\Rates;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class API_Client {

	/**
	 * How long to keep a cached result (seconds).
	 * Overridable via TAKAPATH_CACHE_TTL constant in wp-config.php.
	 */
	private const DEFAULT_TTL = 3600; // 1 hour

	/**
	 * Transient key prefix — kept short to stay under the 172-char WP limit.
	 */
	private const CACHE_PREFIX = 'tkp_rates_';

	/**
	 * Maximum age of stale cache we are willing to serve as a fallback
	 * when the upstream API is unreachable (seconds).
	 */
	private const STALE_TTL = 86400; // 24 hours

	/**
	 * Fetch comparison results for a given currency pair.
	 *
	 * @param string $from_currency  ISO 4217 source currency, e.g. "GBP".
	 * @param int    $amount         Send amount in the source currency.
	 * @param bool   $force_refresh  Bypass cache and fetch fresh data.
	 *
	 * @return array{
	 *   success: bool,
	 *   source:  'api'|'cache'|'stale'|'error',
	 *   data:    array<int, array{
	 *     provider:      string,
	 *     provider_logo: string,
	 *     exchange_rate: float,
	 *     fee:           float,
	 *     recipient_gets:float,
	 *     transfer_speed:string,
	 *     receive_methods:string[],
	 *     transfer_url:  string,
	 *   }>,
	 *   error:   string,
	 *   fetched_at: int,
	 * }
	 */
	public static function get_rates(
		string $from_currency,
		int $amount = 1000,
		bool $force_refresh = false
	): array {
		$from_currency = strtoupper( trim( $from_currency ) );
		$cache_key     = self::CACHE_PREFIX . $from_currency . '_' . $amount;
		$stale_key     = $cache_key . '_stale';

		// --- Serve from live cache unless force-refresh requested ---
		if ( ! $force_refresh ) {
			$cached = get_transient( $cache_key );
			if ( false !== $cached && is_array( $cached ) ) {
				$cached['source'] = 'cache';
				return $cached;
			}
		}

		// --- Fetch from TransferSmartly ---
		$result = self::fetch_from_api( $from_currency, $amount );

		if ( $result['success'] ) {
			// Store live cache.
			set_transient( $cache_key, $result, self::get_ttl() );
			// Also keep a stale copy with a much longer TTL as fallback.
			set_transient( $stale_key, $result, self::STALE_TTL );
			return $result;
		}

		// --- API failed: try stale cache before giving up ---
		$stale = get_transient( $stale_key );
		if ( false !== $stale && is_array( $stale ) ) {
			$stale['source'] = 'stale';
			return $stale;
		}

		// Nothing available — return the error result.
		return $result;
	}

	/**
	 * Clear all cached rate transients for a specific currency,
	 * or all currencies if none is specified.
	 *
	 * @param string|null $from_currency  Specific currency, or null for all.
	 */
	public static function clear_cache( ?string $from_currency = null ): void {
		global $wpdb;

		if ( $from_currency ) {
			$key = self::CACHE_PREFIX . strtoupper( $from_currency );
			// Delete all transients whose option_name starts with _transient_{key}.
			$wpdb->query(
				$wpdb->prepare(
					"DELETE FROM {$wpdb->options} WHERE option_name LIKE %s OR option_name LIKE %s",
					'_transient_' . $wpdb->esc_like( $key ) . '%',
					'_transient_timeout_' . $wpdb->esc_like( $key ) . '%'
				)
			);
		} else {
			$prefix = self::CACHE_PREFIX;
			$wpdb->query(
				$wpdb->prepare(
					"DELETE FROM {$wpdb->options} WHERE option_name LIKE %s OR option_name LIKE %s",
					'_transient_' . $wpdb->esc_like( $prefix ) . '%',
					'_transient_timeout_' . $wpdb->esc_like( $prefix ) . '%'
				)
			);
		}
	}

	// -------------------------------------------------------------------------
	// Private helpers
	// -------------------------------------------------------------------------

	/**
	 * Make the actual HTTP request to TransferSmartly.
	 */
	private static function fetch_from_api( string $from_currency, int $amount ): array {
		$base_url = defined( 'TAKAPATH_API_BASE_URL' )
			? rtrim( TAKAPATH_API_BASE_URL, '/' )
			: 'https://transfersmartly.com';

		$url = add_query_arg(
			[
				'fromCurrency' => $from_currency,
				'toCurrency'   => 'BDT',
				'toCountry'    => 'BD',
				'amount'       => $amount,
			],
			$base_url . '/api/compare'
		);

		$response = wp_remote_get( $url, [
			'timeout'    => 10,
			'user-agent' => 'TakaPath/' . TAKAPATH_RATES_VERSION . ' (+https://takapath.com)',
			'headers'    => [ 'Accept' => 'application/json' ],
		] );

		// Network-level failure.
		if ( is_wp_error( $response ) ) {
			return self::error_result(
				'Network error: ' . $response->get_error_message()
			);
		}

		$status = wp_remote_retrieve_response_code( $response );
		if ( 200 !== (int) $status ) {
			return self::error_result(
				sprintf( 'TransferSmartly returned HTTP %d', $status )
			);
		}

		$body = wp_remote_retrieve_body( $response );
		$json = json_decode( $body, true );

		if ( JSON_ERROR_NONE !== json_last_error() || ! is_array( $json ) ) {
			return self::error_result( 'Invalid JSON response from TransferSmartly' );
		}

		$normalised = self::normalise( $json );

		if ( empty( $normalised ) ) {
			return self::error_result( 'No provider results in API response' );
		}

		return [
			'success'    => true,
			'source'     => 'api',
			'data'       => $normalised,
			'error'      => '',
			'fetched_at' => time(),
		];
	}

	/**
	 * Normalise the TransferSmartly response into a flat, typed array.
	 * Insulates the rest of the plugin from upstream API shape changes.
	 *
	 * TransferSmartly /api/compare returns an array of provider objects.
	 * Each object shape (as of the MoneyRoutes codebase):
	 *   { provider, exchangeRate, fee, recipientGets, transferTime,
	 *     receiveMethods, transferUrl, logoUrl }
	 *
	 * @param array $raw  Decoded JSON from the API.
	 * @return array      Normalised provider rows.
	 */
	private static function normalise( array $raw ): array {
		// The API may return { results: [...] } or a bare array.
		$rows = isset( $raw['results'] ) && is_array( $raw['results'] )
			? $raw['results']
			: $raw;

		$out = [];

		foreach ( $rows as $row ) {
			if ( ! is_array( $row ) ) {
				continue;
			}

			$out[] = [
				'provider'       => sanitize_text_field( $row['provider'] ?? $row['name'] ?? '' ),
				'provider_logo'  => esc_url_raw( $row['logoUrl'] ?? $row['logo'] ?? '' ),
				'exchange_rate'  => (float) ( $row['exchangeRate'] ?? $row['exchange_rate'] ?? 0 ),
				'fee'            => (float) ( $row['fee'] ?? 0 ),
				'recipient_gets' => (float) ( $row['recipientGets'] ?? $row['recipient_gets'] ?? 0 ),
				'transfer_speed' => sanitize_text_field( $row['transferTime'] ?? $row['transfer_speed'] ?? '' ),
				'receive_methods'=> array_map(
					'sanitize_text_field',
					(array) ( $row['receiveMethods'] ?? $row['receive_methods'] ?? [] )
				),
				'transfer_url'   => esc_url_raw( $row['transferUrl'] ?? $row['transfer_url'] ?? '' ),
			];
		}

		// Sort by recipient_gets descending (best rate first).
		usort( $out, static fn( $a, $b ) => $b['recipient_gets'] <=> $a['recipient_gets'] );

		return $out;
	}

	/**
	 * Build a standardised error result array.
	 */
	private static function error_result( string $message ): array {
		// Log to WP debug log if WP_DEBUG_LOG is on.
		if ( defined( 'WP_DEBUG_LOG' ) && WP_DEBUG_LOG ) {
			// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
			error_log( '[TakaPath] API_Client error: ' . $message );
		}

		return [
			'success'    => false,
			'source'     => 'error',
			'data'       => [],
			'error'      => $message,
			'fetched_at' => 0,
		];
	}

	/**
	 * Get the configured cache TTL.
	 */
	private static function get_ttl(): int {
		return defined( 'TAKAPATH_CACHE_TTL' ) ? (int) TAKAPATH_CACHE_TTL : self::DEFAULT_TTL;
	}
}
