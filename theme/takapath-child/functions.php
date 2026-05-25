<?php
/**
 * TakaPath Child Theme — functions.php
 * Enqueues parent (GeneratePress) styles then child styles.
 * Adds Bengali language support and SEO helpers.
 */

declare( strict_types=1 );

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue parent & child stylesheets.
 */
add_action( 'wp_enqueue_scripts', function () {
	// Parent theme stylesheet
	wp_enqueue_style(
		'generatepress-style',
		get_template_directory_uri() . '/style.css',
		[],
		wp_get_theme( 'generatepress' )->get( 'Version' )
	);

	// Child theme stylesheet
	wp_enqueue_style(
		'takapath-child-style',
		get_stylesheet_uri(),
		[ 'generatepress-style' ],
		wp_get_theme()->get( 'Version' )
	);
} );

/**
 * Add Bengali (Bangla) as a supported language in Polylang.
 * No-op if Polylang is not active.
 */
add_action( 'init', function () {
	if ( ! function_exists( 'pll_register_string' ) ) {
		return;
	}
	// Register translatable strings for the theme.
	pll_register_string( 'site-tagline', 'Compare the best ways to send money to Bangladesh', 'TakaPath' );
	pll_register_string( 'hero-heading', 'Best Way to Send Money to Bangladesh — Compare Rates & Fees', 'TakaPath' );
} );

/**
 * Add hreflang tags for English / Bengali language variants.
 * Polylang handles most of this; this is a manual fallback.
 */
add_action( 'wp_head', function () {
	if ( function_exists( 'pll_the_languages' ) ) {
		return; // Polylang active — let it handle hreflang
	}
	echo '<link rel="alternate" hreflang="en" href="' . esc_url( home_url() ) . '" />' . "\n";
	echo '<link rel="alternate" hreflang="bn" href="' . esc_url( home_url( '/bn/' ) ) . '" />' . "\n";
	echo '<link rel="alternate" hreflang="x-default" href="' . esc_url( home_url() ) . '" />' . "\n";
} );

/**
 * Register custom post type: Corridor Guide
 * Used for dedicated corridor pages (e.g. UK → Bangladesh).
 */
add_action( 'init', function () {
	register_post_type( 'corridor', [
		'labels' => [
			'name'          => __( 'Corridor Guides', 'takapath-child' ),
			'singular_name' => __( 'Corridor Guide', 'takapath-child' ),
			'add_new_item'  => __( 'Add New Corridor', 'takapath-child' ),
		],
		'public'       => true,
		'has_archive'  => true,
		'rewrite'      => [ 'slug' => 'send-money-to-bangladesh' ],
		'show_in_rest' => true,
		'menu_icon'    => 'dashicons-airplane',
		'supports'     => [ 'title', 'editor', 'excerpt', 'thumbnail', 'custom-fields' ],
	] );
} );

/**
 * Add structured data (BreadcrumbList) for corridor pages.
 */
add_action( 'wp_head', function () {
	if ( ! is_singular( 'corridor' ) ) {
		return;
	}

	$schema = [
		'@context'        => 'https://schema.org',
		'@type'           => 'BreadcrumbList',
		'itemListElement' => [
			[
				'@type'    => 'ListItem',
				'position' => 1,
				'name'     => 'Home',
				'item'     => home_url(),
			],
			[
				'@type'    => 'ListItem',
				'position' => 2,
				'name'     => get_the_title(),
				'item'     => get_permalink(),
			],
		],
	];

	echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
} );
