<?php
/**
 * TakaPath Child Theme — functions.php
 * Enqueues parent (GeneratePress) styles then child styles.
 * Conditionally loads home-extra.css on the homepage only.
 * Conditionally loads corridor-extra.css on single corridor pages only.
 * Loads ACF field group definitions from /acf/.
 * Adds Bengali language support, SEO helpers, and schema markup.
 * Registers the "TakaPath Homepage" page template.
 */

declare( strict_types=1 );

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// =============================================================================
// ENQUEUE STYLESHEETS
// =============================================================================
add_action( 'wp_enqueue_scripts', function () {

	// 1. Parent theme (GeneratePress)
	wp_enqueue_style(
		'generatepress-style',
		get_template_directory_uri() . '/style.css',
		[],
		wp_get_theme( 'generatepress' )->get( 'Version' )
	);

	// 2. TakaPath child — global tokens + brand styles
	wp_enqueue_style(
		'takapath-child-style',
		get_stylesheet_uri(),
		[ 'generatepress-style' ],
		wp_get_theme()->get( 'Version' )
	);

	// 3. Homepage extra — steps, bilingual block, stats strip, footer CTA
	if ( is_front_page() || is_home() ) {
		wp_enqueue_style(
			'takapath-home-extra',
			get_stylesheet_directory_uri() . '/home-extra.css',
			[ 'takapath-child-style' ],
			wp_get_theme()->get( 'Version' )
		);
	}

	// 4. Corridor page extra — intro, comparison, pills, expert tip, FAQ, related
	if ( is_singular( 'corridor' ) ) {
		wp_enqueue_style(
			'takapath-corridor-extra',
			get_stylesheet_directory_uri() . '/corridor-extra.css',
			[ 'takapath-child-style' ],
			wp_get_theme()->get( 'Version' )
		);
	}
} );


// =============================================================================
// PAGE TEMPLATE — "TakaPath Homepage"
// Registers page-home.php as a selectable template in
// WP Admin → Pages → Page Attributes → Template.
// =============================================================================
add_filter( 'theme_page_templates', function ( array $templates ): array {
	$templates['page-home.php'] = __( 'TakaPath Homepage', 'takapath-child' );
	return $templates;
} );

add_filter( 'template_include', function ( string $template ): string {
	if ( ! is_page() ) {
		return $template;
	}
	$page_template = get_post_meta( get_the_ID(), '_wp_page_template', true );
	if ( 'page-home.php' !== $page_template ) {
		return $template;
	}
	$custom = get_stylesheet_directory() . '/page-home.php';
	return file_exists( $custom ) ? $custom : $template;
} );


// =============================================================================
// ACF FIELD GROUPS — load from version-controlled PHP export
// =============================================================================
add_action( 'acf/include_fields', function () {
	$field_file = get_stylesheet_directory() . '/../../acf/corridor-fields.php';
	if ( file_exists( $field_file ) ) {
		require_once $field_file;
	}
} );


// =============================================================================
// RANK MATH SEO — corridor page overrides
// =============================================================================
add_filter( 'rank_math/title', function ( string $title ): string {
	if ( ! is_singular( 'corridor' ) ) {
		return $title;
	}
	$override = get_field( 'seo_title' );
	return ( $override ) ? (string) $override : $title;
} );

add_filter( 'rank_math/description', function ( string $desc ): string {
	if ( ! is_singular( 'corridor' ) ) {
		return $desc;
	}
	$override = get_field( 'seo_description' );
	return ( $override ) ? (string) $override : $desc;
} );

add_filter( 'rank_math/frontend/canonical', function ( string $canonical ): string {
	if ( ! is_singular( 'corridor' ) ) {
		return $canonical;
	}
	$override = get_field( 'canonical_url' );
	return ( $override ) ? esc_url_raw( (string) $override ) : $canonical;
} );


// =============================================================================
// POLYLANG — register translatable strings
// =============================================================================
add_action( 'init', function () {
	if ( ! function_exists( 'pll_register_string' ) ) {
		return;
	}
	pll_register_string( 'site-tagline',  'Compare the best ways to send money to Bangladesh', 'TakaPath' );
	pll_register_string( 'hero-heading',  'Best Way to Send Money to Bangladesh — Compare Rates & Fees', 'TakaPath' );
} );


// =============================================================================
// HREFLANG — manual fallback when Polylang is not active
// =============================================================================
add_action( 'wp_head', function () {
	if ( function_exists( 'pll_the_languages' ) ) {
		return;
	}
	echo '<link rel="alternate" hreflang="en" href="' . esc_url( home_url() ) . '" />' . "\n";
	echo '<link rel="alternate" hreflang="bn" href="' . esc_url( home_url( '/bn/' ) ) . '" />' . "\n";
	echo '<link rel="alternate" hreflang="x-default" href="' . esc_url( home_url() ) . '" />' . "\n";
} );


// =============================================================================
// CUSTOM POST TYPE — Corridor Guide
// =============================================================================
add_action( 'init', function () {
	register_post_type( 'corridor', [
		'labels' => [
			'name'          => __( 'Corridor Guides', 'takapath-child' ),
			'singular_name' => __( 'Corridor Guide', 'takapath-child' ),
			'add_new_item'  => __( 'Add New Corridor', 'takapath-child' ),
			'edit_item'     => __( 'Edit Corridor', 'takapath-child' ),
			'view_item'     => __( 'View Corridor', 'takapath-child' ),
			'all_items'     => __( 'All Corridors', 'takapath-child' ),
		],
		'public'        => true,
		'has_archive'   => true,
		'rewrite'       => [ 'slug' => 'send-money-to-bangladesh' ],
		'show_in_rest'  => true,
		'menu_icon'     => 'dashicons-airplane',
		'supports'      => [ 'title', 'editor', 'excerpt', 'thumbnail', 'custom-fields' ],
	] );
} );


// =============================================================================
// STRUCTURED DATA — BreadcrumbList + FAQPage schema for corridor pages
// =============================================================================
add_action( 'wp_head', function () {
	if ( ! is_singular( 'corridor' ) ) {
		return;
	}

	$breadcrumb = [
		'@context'        => 'https://schema.org',
		'@type'           => 'BreadcrumbList',
		'itemListElement' => [
			[ '@type' => 'ListItem', 'position' => 1, 'name' => 'Home',           'item' => home_url() ],
			[ '@type' => 'ListItem', 'position' => 2, 'name' => 'Send Money to Bangladesh', 'item' => home_url( '/send-money-to-bangladesh/' ) ],
			[ '@type' => 'ListItem', 'position' => 3, 'name' => get_the_title(), 'item' => get_permalink() ],
		],
	];
	echo '<script type="application/ld+json">' . wp_json_encode( $breadcrumb, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";

	$faq_items = get_field( 'faq_items' );
	if ( ! empty( $faq_items ) && is_array( $faq_items ) ) {
		$entities = [];
		foreach ( $faq_items as $item ) {
			if ( empty( $item['question'] ) || empty( $item['answer'] ) ) {
				continue;
			}
			$entities[] = [
				'@type'          => 'Question',
				'name'           => wp_strip_all_tags( $item['question'] ),
				'acceptedAnswer' => [
					'@type' => 'Answer',
					'text'  => wp_strip_all_tags( $item['answer'] ),
				],
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
} );


// =============================================================================
// HELPER — get corridor shortcode string for use in templates
// =============================================================================
function takapath_corridor_shortcode( int $post_id = 0 ): string {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}
	$currency = get_field( 'from_currency', $post_id ) ?: 'GBP';
	$amount   = (int) ( get_field( 'default_amount', $post_id ) ?: 1000 );
	return sprintf( '[takapath_rates from="%s" amount="%d" selector="yes"]', esc_attr( $currency ), $amount );
}
