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

// =============================================================================
// ENQUEUE STYLESHEETS
// =============================================================================
add_action( 'wp_enqueue_scripts', function () {

	$child_version = wp_get_theme()->get( 'Version' );

	// 1. Parent theme (GeneratePress)
	wp_enqueue_style(
		'generatepress-style',
		get_template_directory_uri() . '/style.css',
		[],
		wp_get_theme( 'generatepress' )->get( 'Version' )
	);

	// 2. TakaPath child — global design tokens + brand styles
	wp_enqueue_style(
		'takapath-child-style',
		get_stylesheet_uri(),
		[ 'generatepress-style' ],
		$child_version
	);

	// 3. Homepage extra — steps, bilingual block, stats strip, footer CTA
	if ( is_front_page() || is_home() ) {
		wp_enqueue_style(
			'takapath-home-extra',
			get_stylesheet_directory_uri() . '/home-extra.css',
			[ 'takapath-child-style' ],
			$child_version
		);
	}

	// 4. Corridor page extra — intro, comparison table, receive-method pills, FAQ
	if ( is_singular( 'corridor' ) ) {
		wp_enqueue_style(
			'takapath-corridor-extra',
			get_stylesheet_directory_uri() . '/corridor-extra.css',
			[ 'takapath-child-style' ],
			$child_version
		);
	}

	// 5. Archive (corridor listing) extra — breadcrumb, filter bar, pagination
	if ( is_post_type_archive( 'corridor' ) ) {
		wp_enqueue_style(
			'takapath-archive-extra',
			get_stylesheet_directory_uri() . '/archive-extra.css',
			[ 'takapath-child-style' ],
			$child_version
		);
	}
} );


// =============================================================================
// PAGE TEMPLATE — "TakaPath Homepage"
// =============================================================================
add_filter( 'theme_page_templates', function ( array $templates ): array {
	$templates['page-home.php'] = __( 'TakaPath Homepage', 'takapath-child' );
	return $templates;
} );

add_filter( 'template_include', function ( string $template ): string {
	if ( ! is_page() ) {
		return $template;
	}
	$page_template = (string) get_post_meta( get_the_ID(), '_wp_page_template', true );
	if ( 'page-home.php' !== $page_template ) {
		return $template;
	}
	$custom = get_stylesheet_directory() . '/page-home.php';
	return file_exists( $custom ) ? $custom : $template;
} );


// =============================================================================
// ACF FIELD GROUPS — load from version-controlled PHP export
// =============================================================================
// acf/include_fields fires after ACF is fully loaded, before fields are used.
// Path: theme/takapath-child/ → repo root → config/acf-field-groups.php
add_action( 'acf/include_fields', function () {
	$field_file = get_stylesheet_directory() . '/../../config/acf-field-groups.php';
	if ( file_exists( $field_file ) ) {
		require_once $field_file;
	}
} );


// =============================================================================
// RANK MATH SEO — per-corridor title / meta / canonical overrides
// =============================================================================
// Field names match config/acf-field-groups.php exactly.
add_filter( 'rank_math/title', function ( string $title ): string {
	if ( ! is_singular( 'corridor' ) ) {
		return $title;
	}
	$override = (string) get_field( 'seo_title_override' );
	return $override !== '' ? $override : $title;
} );

add_filter( 'rank_math/description', function ( string $desc ): string {
	if ( ! is_singular( 'corridor' ) ) {
		return $desc;
	}
	$override = (string) get_field( 'seo_meta_override' );
	return $override !== '' ? $override : $desc;
} );

// No canonical_url ACF field — Rank Math handles canonical automatically.
// Add a filter here only if a per-corridor override field is added later.


// =============================================================================
// POLYLANG — register translatable strings
// =============================================================================
add_action( 'init', function () {
	if ( ! function_exists( 'pll_register_string' ) ) {
		return;
	}
	$strings = [
		'site-tagline' => 'Compare the best ways to send money to Bangladesh',
		'hero-heading' => 'Best Way to Send Money to Bangladesh — Compare Rates & Fees',
		'trust-rates'  => 'Rates updated every hour',
		'trust-fees'   => 'No hidden fees',
		'trust-independent' => 'Independent — not owned by any provider',
		'trust-methods'     => 'bKash, Nagad & bank deposit covered',
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
		return; // Polylang handles hreflang
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
			'name'               => __( 'Corridor Guides', 'takapath-child' ),
			'singular_name'      => __( 'Corridor Guide', 'takapath-child' ),
			'add_new_item'       => __( 'Add New Corridor', 'takapath-child' ),
			'edit_item'          => __( 'Edit Corridor', 'takapath-child' ),
			'view_item'          => __( 'View Corridor', 'takapath-child' ),
			'all_items'          => __( 'All Corridors', 'takapath-child' ),
			'search_items'       => __( 'Search Corridors', 'takapath-child' ),
			'not_found'          => __( 'No corridors found.', 'takapath-child' ),
			'not_found_in_trash' => __( 'No corridors found in Trash.', 'takapath-child' ),
		],
		'public'       => true,
		'has_archive'  => true,
		'rewrite'      => [ 'slug' => 'send-money-to-bangladesh', 'with_front' => false ],
		'show_in_rest' => true,
		'menu_icon'    => 'dashicons-airplane',
		'menu_position'=> 5,
		'supports'     => [ 'title', 'editor', 'excerpt', 'thumbnail', 'custom-fields', 'page-attributes' ],
		// page-attributes enables the Order field in WP Admin → used by homepage corridor grid
	] );
} );


// =============================================================================
// STRUCTURED DATA — BreadcrumbList + FAQPage (corridor pages)
// =============================================================================
// Emitted in <head> via wp_head. Single corridor pages only.
// WebSite schema for the homepage is emitted directly in page-home.php.
add_action( 'wp_head', function () {
	if ( ! is_singular( 'corridor' ) ) {
		return;
	}

	// BreadcrumbList
	$breadcrumb = [
		'@context'        => 'https://schema.org',
		'@type'           => 'BreadcrumbList',
		'itemListElement' => [
			[ '@type' => 'ListItem', 'position' => 1, 'name' => __( 'Home', 'takapath-child' ), 'item' => home_url( '/' ) ],
			[ '@type' => 'ListItem', 'position' => 2, 'name' => __( 'Send Money to Bangladesh', 'takapath-child' ), 'item' => home_url( '/send-money-to-bangladesh/' ) ],
			[ '@type' => 'ListItem', 'position' => 3, 'name' => get_the_title(), 'item' => get_permalink() ],
		],
	];
	echo '<script type="application/ld+json">' . wp_json_encode( $breadcrumb, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";

	// FAQPage — only when faq_items ACF field has entries
	$faq_items = function_exists( 'get_field' ) ? get_field( 'faq_items' ) : null;
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
			$faq_schema = [
				'@context'   => 'https://schema.org',
				'@type'      => 'FAQPage',
				'mainEntity' => $entities,
			];
			echo '<script type="application/ld+json">' . wp_json_encode( $faq_schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
		}
	}
}, 5 ); // Priority 5 — fires before default wp_head output at 10


// =============================================================================
// HELPER — build the [takapath_rates] shortcode string for a corridor post
// =============================================================================
/**
 * Returns a ready-to-use shortcode string for the given corridor post.
 *
 * Usage in templates:
 *   echo do_shortcode( takapath_corridor_shortcode() );
 *
 * @param int $post_id  Optional. Defaults to current post in the loop.
 * @return string       e.g. [takapath_rates from="GBP" amount="500" selector="yes"]
 */
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
