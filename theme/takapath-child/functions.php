<?php
/**
 * TakaPath Child Theme — functions.php
 *
 * Responsibilities:
 *   - Enqueue parent (GeneratePress) + child styles, conditionally per page type
 *   - Register "TakaPath Homepage" page template
 *   - Load ACF field groups from version-controlled PHP export
 *   - Rank Math SEO filter hooks (title / meta / canonical overrides per corridor)
 *   - Polylang translatable string registration
 *   - Hreflang tags (manual fallback when Polylang is inactive)
 *   - Custom Post Type: corridor
 *   - Structured data: BreadcrumbList + FAQPage (corridor pages), WebSite (homepage)
 *   - Helper: takapath_corridor_shortcode()
 */

declare( strict_types=1 );

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'TAKAPATH_VERSION', wp_get_theme()->get( 'Version' ) );

// =============================================================================
// ENQUEUE STYLESHEETS
// =============================================================================
add_action( 'wp_enqueue_scripts', function () {

	$v = TAKAPATH_VERSION;

	// 1. Parent theme (GeneratePress)
	wp_enqueue_style(
		'generatepress-style',
		get_template_directory_uri() . '/style.css',
		[],
		wp_get_theme( 'generatepress' )->get( 'Version' )
	);

	// 2. TakaPath child — global design tokens + brand styles
	wp_enqueue_style(
		'takapath-child',
		get_stylesheet_uri(),
		[ 'generatepress-style' ],
		$v
	);

	// 3. Header + footer styles — always loaded (sitewide chrome)
	wp_enqueue_style(
		'takapath-header-footer',
		get_stylesheet_directory_uri() . '/header-footer.css',
		[ 'takapath-child' ],
		$v
	);

	// 4. Homepage extra — steps, bilingual block, stats strip, footer CTA
	if ( is_front_page() || is_home() ) {
		wp_enqueue_style(
			'takapath-home-extra',
			get_stylesheet_directory_uri() . '/home-extra.css',
			[ 'takapath-header-footer' ],
			$v
		);
	}

	// 5. Corridor page extra — intro, comparison table, receive-method pills, FAQ
	if ( is_singular( 'corridor' ) ) {
		wp_enqueue_style(
			'takapath-corridor-extra',
			get_stylesheet_directory_uri() . '/corridor-extra.css',
			[ 'takapath-header-footer' ],
			$v
		);
	}

	// 6. Archive (corridor listing) extra — breadcrumb, filter bar, pagination
	if ( is_post_type_archive( 'corridor' ) ) {
		wp_enqueue_style(
			'takapath-archive-extra',
			get_stylesheet_directory_uri() . '/archive-extra.css',
			[ 'takapath-header-footer' ],
			$v
		);
	}

	// 7. Guide pages (bKash, Nagad, exchange-rate guides)
	if ( is_page() ) {
		$slug = get_post_field( 'post_name', get_the_ID() );
		$guide_slugs = [
			'bkash-receive-money-abroad',
			'nagad-international-transfer',
			'gbp-bdt-exchange-rate',
			'usd-bdt-exchange-rate',
			'sar-bdt-exchange-rate',
		];
		if ( in_array( $slug, $guide_slugs, true ) ) {
			wp_enqueue_style(
				'takapath-guide',
				get_stylesheet_directory_uri() . '/guide.css',
				[ 'takapath-header-footer' ],
				$v
			);
		}
	}

} );


// =============================================================================
// PAGE TEMPLATES
// =============================================================================
add_filter( 'theme_page_templates', function ( array $templates ): array {
	$templates['page-home.php']  = __( 'TakaPath Homepage', 'takapath' );
	$templates['page-guide.php'] = __( 'TakaPath Guide Page', 'takapath' );
	return $templates;
} );

add_filter( 'template_include', function ( string $template ): string {
	if ( ! is_page() ) {
		return $template;
	}
	$page_template = (string) get_post_meta( get_the_ID(), '_wp_page_template', true );
	$candidates    = [ 'page-home.php', 'page-guide.php' ];
	if ( ! in_array( $page_template, $candidates, true ) ) {
		return $template;
	}
	$custom = get_stylesheet_directory() . '/' . $page_template;
	return file_exists( $custom ) ? $custom : $template;
} );


// =============================================================================
// ACF FIELD GROUPS — load from version-controlled PHP export
// =============================================================================
add_action( 'acf/include_fields', function () {
	if ( ! function_exists( 'get_field' ) ) {
		return;
	}
	$field_file = get_stylesheet_directory() . '/../../config/acf-field-groups.php';
	if ( file_exists( $field_file ) ) {
		require_once $field_file;
	}
} );


// =============================================================================
// RANK MATH SEO — per-corridor title / meta overrides
// =============================================================================
add_filter( 'rank_math/title', function ( string $title ): string {
	if ( ! is_singular( 'corridor' ) || ! function_exists( 'get_field' ) ) {
		return $title;
	}
	$override = (string) get_field( 'seo_title_override' );
	return $override !== '' ? $override : $title;
} );

add_filter( 'rank_math/description', function ( string $desc ): string {
	if ( ! is_singular( 'corridor' ) || ! function_exists( 'get_field' ) ) {
		return $desc;
	}
	$override = (string) get_field( 'seo_meta_override' );
	return $override !== '' ? $override : $desc;
} );


// =============================================================================
// POLYLANG — register translatable strings
// =============================================================================
add_action( 'init', function () {
	if ( ! function_exists( 'pll_register_string' ) ) {
		return;
	}
	$strings = [
		'site-tagline'       => 'Compare the best ways to send money to Bangladesh',
		'hero-heading'       => 'Best Way to Send Money to Bangladesh — Compare Rates & Fees',
		'trust-rates'        => 'Rates updated every hour',
		'trust-fees'         => 'No hidden fees',
		'trust-independent'  => 'Independent — not owned by any provider',
		'trust-methods'      => 'bKash, Nagad & bank deposit covered',
	];
	foreach ( $strings as $name => $value ) {
		pll_register_string( $name, $value, 'TakaPath' );
	}
} );


// =============================================================================
// HREFLANG — manual fallback when Polylang is inactive
// =============================================================================
add_action( 'wp_head', function () {
	if ( function_exists( 'pll_the_languages' ) ) {
		return;
	}
	$base = esc_url( home_url() );
	echo '<link rel="alternate" hreflang="en" href="' . $base . '" />' . "\n";
	echo '<link rel="alternate" hreflang="bn" href="' . esc_url( home_url( '/bn/' ) ) . '" />' . "\n";
	echo '<link rel="alternate" hreflang="x-default" href="' . $base . '" />' . "\n";
} );


// =============================================================================
// CUSTOM POST TYPE — Corridor Guide
// =============================================================================
add_action( 'init', function () {
	register_post_type( 'corridor', [
		'labels' => [
			'name'               => __( 'Corridor Guides', 'takapath' ),
			'singular_name'      => __( 'Corridor Guide', 'takapath' ),
			'add_new_item'       => __( 'Add New Corridor', 'takapath' ),
			'edit_item'          => __( 'Edit Corridor', 'takapath' ),
			'view_item'          => __( 'View Corridor', 'takapath' ),
			'all_items'          => __( 'All Corridors', 'takapath' ),
			'search_items'       => __( 'Search Corridors', 'takapath' ),
			'not_found'          => __( 'No corridors found.', 'takapath' ),
			'not_found_in_trash' => __( 'No corridors found in Trash.', 'takapath' ),
		],
		'public'        => true,
		'has_archive'   => true,
		'rewrite'       => [ 'slug' => 'send-money-to-bangladesh', 'with_front' => false ],
		'show_in_rest'  => true,
		'menu_icon'     => 'dashicons-airplane',
		'menu_position' => 5,
		'supports'      => [ 'title', 'editor', 'excerpt', 'thumbnail', 'custom-fields', 'page-attributes' ],
	] );
} );


// =============================================================================
// STRUCTURED DATA — BreadcrumbList + FAQPage (corridor pages)
// =============================================================================
add_action( 'wp_head', function () {
	if ( ! is_singular( 'corridor' ) ) {
		return;
	}

	$breadcrumb = [
		'@context'        => 'https://schema.org',
		'@type'           => 'BreadcrumbList',
		'itemListElement' => [
			[ '@type' => 'ListItem', 'position' => 1, 'name' => __( 'Home', 'takapath' ), 'item' => home_url( '/' ) ],
			[ '@type' => 'ListItem', 'position' => 2, 'name' => __( 'Send Money to Bangladesh', 'takapath' ), 'item' => home_url( '/send-money-to-bangladesh/' ) ],
			[ '@type' => 'ListItem', 'position' => 3, 'name' => get_the_title(), 'item' => get_permalink() ],
		],
	];
	echo '<script type="application/ld+json">' . wp_json_encode( $breadcrumb, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";

	if ( ! function_exists( 'get_field' ) ) {
		return;
	}
	$faq_items = get_field( 'faq_items' );
	if ( ! empty( $faq_items ) && is_array( $faq_items ) ) {
		$entities = [];
		foreach ( $faq_items as $item ) {
			$q = isset( $item['question'] ) ? trim( wp_strip_all_tags( (string) $item['question'] ) ) : '';
			$a = isset( $item['answer'] )   ? trim( wp_strip_all_tags( (string) $item['answer']   ) ) : '';
			if ( $q === '' || $a === '' ) {
				continue;
			}
			$entities[] = [
				'@type'          => 'Question',
				'name'           => $q,
				'acceptedAnswer' => [ '@type' => 'Answer', 'text' => $a ],
			];
		}
		if ( ! empty( $entities ) ) {
			$schema = [
				'@context'   => 'https://schema.org',
				'@type'      => 'FAQPage',
				'mainEntity' => $entities,
			];
			echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
		}
	}
}, 5 );


// =============================================================================
// STRUCTURED DATA — HowTo / Article schema for guide pages
// =============================================================================
add_action( 'wp_head', function () {
	if ( ! is_page() ) {
		return;
	}
	$slug = get_post_field( 'post_name', get_the_ID() );
	if ( $slug !== 'bkash-receive-money-abroad' ) {
		return;
	}
	$schema = [
		'@context'    => 'https://schema.org',
		'@type'       => 'HowTo',
		'name'        => 'How to Receive Money on bKash from Abroad',
		'description' => 'Step-by-step guide to receiving international remittances directly into your bKash account in Bangladesh.',
		'step'        => [
			[ '@type' => 'HowToStep', 'position' => '1', 'name' => 'Choose a supported provider', 'text' => 'Select a money transfer provider that supports bKash HomeSend, such as Remitly, Western Union, or MoneyGram.' ],
			[ '@type' => 'HowToStep', 'position' => '2', 'name' => 'Enter your bKash number', 'text' => 'When prompted for a delivery method, choose bKash and enter the recipient\'s 11-digit bKash mobile number registered in Bangladesh.' ],
			[ '@type' => 'HowToStep', 'position' => '3', 'name' => 'Confirm and send', 'text' => 'Review the exchange rate and fees, then confirm the transfer. The recipient will receive a bKash notification within minutes.' ],
			[ '@type' => 'HowToStep', 'position' => '4', 'name' => 'Recipient withdraws or uses funds', 'text' => 'The recipient can use the funds directly from bKash for payments, or withdraw cash at a bKash agent point.' ],
		],
	];
	echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
}, 5 );


// =============================================================================
// HELPER — build the [takapath_rates] shortcode string for a corridor post
// =============================================================================
function takapath_corridor_shortcode( int $post_id = 0 ): string {
	if ( $post_id === 0 ) {
		$post_id = (int) get_the_ID();
	}
	$currency = function_exists( 'get_field' ) ? (string) ( get_field( 'from_currency', $post_id ) ?: 'GBP' ) : 'GBP';
	$amount   = function_exists( 'get_field' ) ? (int)    ( get_field( 'default_amount', $post_id ) ?: 1000 ) : 1000;
	return sprintf(
		'[takapath_rates from="%s" amount="%d" selector="yes"]',
		esc_attr( $currency ),
		$amount
	);
}
