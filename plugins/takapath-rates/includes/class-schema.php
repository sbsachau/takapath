<?php
/**
 * Schema output for TakaPath Rates plugin.
 * Outputs FinancialService + BreadcrumbList structured data on corridor pages,
 * and WebSite schema on the homepage.
 */

declare( strict_types=1 );

namespace TakaPath\Rates;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Schema {

	public static function init(): void {
		add_action( 'wp_head', [ self::class, 'output_schema' ], 1 );
	}

	public static function output_schema(): void {
		if ( is_singular( 'corridor' ) ) {
			self::output_corridor_schema();
		}
		if ( is_front_page() ) {
			self::output_site_schema();
		}
	}

	private static function output_corridor_schema(): void {
		$post          = get_queried_object();
		$from_country  = function_exists( 'get_field' ) ? ( get_field( 'from_country', $post->ID ) ?: '' ) : '';
		$from_currency = function_exists( 'get_field' ) ? ( get_field( 'from_currency', $post->ID ) ?: '' ) : '';

		$schema = [
			'@context'    => 'https://schema.org',
			'@type'       => 'WebPage',
			'name'        => get_the_title( $post->ID ),
			'url'         => get_permalink( $post->ID ),
			'description' => get_the_excerpt( $post->ID ),
			'about'       => [
				'@type'              => 'FinancialService',
				'name'               => 'Money Transfer Comparison: ' . $from_country . ' to Bangladesh',
				'areaServed'         => [ $from_country, 'Bangladesh' ],
				'currenciesAccepted' => $from_currency . ', BDT',
			],
			'breadcrumb'  => [
				'@type'           => 'BreadcrumbList',
				'itemListElement' => [
					[
						'@type'    => 'ListItem',
						'position' => 1,
						'name'     => 'Home',
						'item'     => home_url( '/' ),
					],
					[
						'@type'    => 'ListItem',
						'position' => 2,
						'name'     => get_the_title( $post->ID ),
						'item'     => get_permalink( $post->ID ),
					],
				],
			],
		];

		echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
	}

	private static function output_site_schema(): void {
		$schema = [
			'@context'        => 'https://schema.org',
			'@type'           => 'WebSite',
			'name'            => 'TakaPath',
			'url'             => home_url( '/' ),
			'description'     => 'Compare the best ways to send money to Bangladesh. Find live exchange rates, fees, and transfer speeds from Wise, Remitly, Western Union and more.',
			'potentialAction' => [
				'@type'       => 'SearchAction',
				'target'      => home_url( '/?s={search_term_string}' ),
				'query-input' => 'required name=search_term_string',
			],
		];

		echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
	}
}
