<?php
/**
 * Schema — outputs structured data (JSON-LD) for corridor pages.
 *
 * Outputs:
 *   - FAQPage schema (from ACF FAQ repeater)
 *   - FinancialProduct schema for the comparison widget
 *   - BreadcrumbList schema (also in functions.php, consolidated here)
 */

declare( strict_types=1 );

namespace TakaPath\Rates;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Schema {

	public static function init(): void {
		add_action( 'wp_head', [ self::class, 'output_corridor_schema' ], 5 );
	}

	/**
	 * Output JSON-LD schema for corridor CPT pages.
	 */
	public static function output_corridor_schema(): void {
		if ( ! is_singular( 'corridor' ) || ! function_exists( 'get_field' ) ) {
			return;
		}

		$schemas = [];

		// ── FAQ schema ────────────────────────────────────────────────────────
		$faqs = get_field( 'faqs' );
		if ( ! empty( $faqs ) && is_array( $faqs ) ) {
			$faq_entities = [];
			foreach ( $faqs as $faq ) {
				if ( ! empty( $faq['question'] ) && ! empty( $faq['answer'] ) ) {
					$faq_entities[] = [
						'@type'          => 'Question',
						'name'           => wp_strip_all_tags( $faq['question'] ),
						'acceptedAnswer' => [
							'@type' => 'Answer',
							'text'  => wp_strip_all_tags( $faq['answer'] ),
						],
					];
				}
			}
			if ( ! empty( $faq_entities ) ) {
				$schemas[] = [
					'@context'   => 'https://schema.org',
					'@type'      => 'FAQPage',
					'mainEntity' => $faq_entities,
				];
			}
		}

		// ── BreadcrumbList ────────────────────────────────────────────────────
		$schemas[] = [
			'@context'        => 'https://schema.org',
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
					'name'     => 'Send Money to Bangladesh',
					'item'     => home_url( '/send-money-to-bangladesh/' ),
				],
				[
					'@type'    => 'ListItem',
					'position' => 3,
					'name'     => get_the_title(),
					'item'     => get_permalink(),
				],
			],
		];

		// ── FinancialService (site-level, once per corridor page) ─────────────
		$from_country = get_field( 'from_country' ) ?: 'international';
		$schemas[]    = [
			'@context'    => 'https://schema.org',
			'@type'       => 'FinancialService',
			'name'        => 'TakaPath — Money Transfer Comparison to Bangladesh',
			'url'         => get_permalink(),
			'description' => sprintf(
				'Compare money transfer rates from %s to Bangladesh. Find the best exchange rate and lowest fees for sending money to bank accounts, bKash, and cash pickup.',
				$from_country
			),
			'areaServed'  => [ $from_country, 'Bangladesh' ],
			'currenciesAccepted' => get_field( 'from_currency' ) ?: 'GBP',
		];

		// Output all schemas
		foreach ( $schemas as $schema ) {
			echo '<script type="application/ld+json">' .
				wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ) .
				'</script>' . "\n";
		}
	}
}
