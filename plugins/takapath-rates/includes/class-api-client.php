<?php
/**
 * API_Client — fetches rate data from the TransferSmartly compare endpoint.
 * Results are cached in WordPress transients.
 */

declare( strict_types=1 );

namespace TakaPath\Rates;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class API_Client {

	/**
	 * Cache key prefix for WordPress transients.
	 */
	private const CACHE_PREFIX = 'takapath_rates_';

	/**
	 * Destination country — always Bangladesh for TakaPath.
	 */
	private const TO_COUNTRY = 'BD';

	/**
	 * Fetch comparison rates for a given source currency.
	 *
	 * @param string $from_currency  ISO 4217 currency code (e.g. 'GBP', 'USD').
	 * @param float  $amount         Amount to compare (default: 1000).
	 * @param bool   $force_refresh  Bypass transient cache.
	 * @return array|WP_Error Decoded API response or WP_Error on failure.
	 */
	public static function get_rates(
		string $from_currency,
		float $amount = 1000.0,
		bool $force_refresh = false
	): array|\WP_Error {

		$cache_key = self::CACHE_PREFIX . strtolower( $from_currency );

		if ( ! $force_refresh ) {
			$cached = get_transient( $cache_key );
			if ( false !== $cached ) {
				return $cached;
			}
		}

		$api_url = defined( 'TAKAPATH_API_URL' )
			? TAKAPATH_API_URL
			: get_option( 'takapath_api_url', 'https://transfersmartly.com/api/compare' );

		$endpoint = add_query_arg(
			[
				'fromCurrency' => strtoupper( $from_currency ),
				'toCurrency'   => 'BDT',
				'toCountry'    => self::TO_COUNTRY,
				'amount'       => $amount,
			],
			$api_url
		);

		$response = wp_remote_get(
			$endpoint,
			[
				'timeout'    => 10,
				'user-agent' => 'TakaPath/1.0 (+https://takapath.com)',
				'headers'    => [
					'Accept' => 'application/json',
				],
			]
		);

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$http_code = wp_remote_retrieve_response_code( $response );
		if ( 200 !== $http_code ) {
			return new \WP_Error(
				'takapath_api_error',
				sprintf( 'TransferSmartly API returned HTTP %d.', $http_code )
			);
		}

		$body = wp_remote_retrieve_body( $response );
		$data = json_decode( $body, associative: true );

		if ( JSON_ERROR_NONE !== json_last_error() || ! is_array( $data ) ) {
			return new \WP_Error( 'takapath_parse_error', 'Failed to parse API response.' );
		}

		$ttl = defined( 'TAKAPATH_CACHE_TTL' ) ? TAKAPATH_CACHE_TTL : 3600;
		set_transient( $cache_key, $data, $ttl );

		return $data;
	}

	/**
	 * Bust the transient cache for a specific currency.
	 *
	 * @param string $from_currency
	 */
	public static function bust_cache( string $from_currency ): void {
		delete_transient( self::CACHE_PREFIX . strtolower( $from_currency ) );
	}
}
