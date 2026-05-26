<?php
/**
 * TakaPath Child Theme — functions.php
 * Steps 1–8: enqueues, CPT, templates, schema, i18n, hreflang
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/* ══════════════════════════════════════════
   1. ENQUEUE STYLES
══════════════════════════════════════════ */
add_action( 'wp_enqueue_scripts', 'takapath_enqueue_styles' );
function takapath_enqueue_styles() {
    $v = wp_get_theme()->get('Version');

    // Parent theme
    wp_enqueue_style(
        'parent-style',
        get_template_directory_uri() . '/style.css',
        [], $v
    );

    // Child theme base (design tokens)
    wp_enqueue_style(
        'takapath-style',
        get_stylesheet_directory_uri() . '/style.css',
        ['parent-style'], $v
    );

    // Header / footer — sitewide
    wp_enqueue_style(
        'takapath-header-footer',
        get_stylesheet_directory_uri() . '/header-footer.css',
        ['takapath-style'], $v
    );

    // Homepage
    if ( is_front_page() || is_page_template('page-home.php') ) {
        wp_enqueue_style(
            'takapath-home',
            get_stylesheet_directory_uri() . '/home-extra.css',
            ['takapath-header-footer'], $v
        );
    }

    // Corridor archive + single
    if ( is_post_type_archive('corridor') || is_singular('corridor') ) {
        wp_enqueue_style(
            'takapath-corridor',
            get_stylesheet_directory_uri() . '/corridor-extra.css',
            ['takapath-header-footer'], $v
        );
        wp_enqueue_style(
            'takapath-archive',
            get_stylesheet_directory_uri() . '/archive-extra.css',
            ['takapath-header-footer'], $v
        );
    }

    // Guide pages
    $guide_slugs = [
        'bkash-receive-money-abroad',
        'nagad-international-transfer',
        'best-exchange-rate-bangladesh',
        'gbp-to-bdt-rate-today',
        'usd-to-bdt-rate-today',
    ];
    if ( is_page_template('page-guide.php') || ( is_page() && in_array( get_post_field('post_name'), $guide_slugs ) ) ) {
        wp_enqueue_style(
            'takapath-guide',
            get_stylesheet_directory_uri() . '/guide.css',
            ['takapath-header-footer'], $v
        );
    }

    // Trust pages (About, How We Work)
    if ( is_page_template('page-about.php') || is_page_template('page-how-we-work.php') ) {
        wp_enqueue_style(
            'takapath-trust',
            get_stylesheet_directory_uri() . '/trust.css',
            ['takapath-header-footer'], $v
        );
    }
}

/* ══════════════════════════════════════════
   2. CORRIDOR CUSTOM POST TYPE
══════════════════════════════════════════ */
add_action( 'init', 'takapath_register_corridor_cpt' );
function takapath_register_corridor_cpt() {
    register_post_type( 'corridor', [
        'labels' => [
            'name'               => __('Corridors','takapath'),
            'singular_name'      => __('Corridor','takapath'),
            'add_new_item'       => __('Add New Corridor','takapath'),
            'edit_item'          => __('Edit Corridor','takapath'),
        ],
        'public'             => true,
        'show_in_rest'       => true,
        'supports'           => ['title','editor','thumbnail','custom-fields'],
        'rewrite'            => ['slug' => 'send-money-to-bangladesh'],
        'menu_icon'          => 'dashicons-money-alt',
        'has_archive'        => true,
    ]);
}

/* ══════════════════════════════════════════
   3. PAGE TEMPLATES
══════════════════════════════════════════ */
add_filter( 'theme_page_templates', 'takapath_add_page_templates' );
function takapath_add_page_templates( $templates ) {
    $templates['page-home.php']         = __('TakaPath Home Page','takapath');
    $templates['page-guide.php']        = __('TakaPath Guide Page','takapath');
    $templates['page-about.php']        = __('TakaPath About Page','takapath');
    $templates['page-how-we-work.php']  = __('TakaPath How We Work','takapath');
    return $templates;
}

add_filter( 'template_include', 'takapath_load_page_template' );
function takapath_load_page_template( $template ) {
    if ( is_page() ) {
        $custom = get_page_template_slug();
        $file   = get_stylesheet_directory() . '/' . $custom;
        if ( $custom && file_exists( $file ) ) {
            return $file;
        }
    }
    return $template;
}

/* ══════════════════════════════════════════
   4. SCHEMA — ORGANISATION
══════════════════════════════════════════ */
add_action( 'wp_head', 'takapath_organisation_schema' );
function takapath_organisation_schema() {
    $schema = [
        '@context'    => 'https://schema.org',
        '@type'       => 'Organization',
        'name'        => 'TakaPath',
        'url'         => 'https://takapath.com',
        'logo'        => 'https://takapath.com/wp-content/uploads/takapath-logo.png',
        'description' => 'Independent comparison website for Bangladesh remittances — exchange rates, fees and transfer speed from all major providers.',
        'sameAs'      => [
            'https://twitter.com/takapath',
        ],
        'contactPoint' => [
            [
                '@type'       => 'ContactPoint',
                'email'       => 'hello@takapath.com',
                'contactType' => 'customer support',
            ],
        ],
    ];
    echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
}

/* ══════════════════════════════════════════
   5. SCHEMA — HOWTO (guide pages)
══════════════════════════════════════════ */
add_action( 'wp_head', 'takapath_howto_schema' );
function takapath_howto_schema() {
    if ( ! is_page('bkash-receive-money-abroad') ) return;
    $schema = [
        '@context' => 'https://schema.org',
        '@type'    => 'HowTo',
        'name'     => 'How to Receive Money on bKash from Abroad',
        'step'     => [
            [ '@type' => 'HowToStep', 'position' => 1, 'name' => 'Share your bKash number', 'text' => 'Give your sender your 11-digit bKash-registered mobile number.' ],
            [ '@type' => 'HowToStep', 'position' => 2, 'name' => 'Sender selects bKash as payout', 'text' => 'The sender chooses bKash as the delivery method when making the transfer.' ],
            [ '@type' => 'HowToStep', 'position' => 3, 'name' => 'Receive SMS confirmation', 'text' => 'You receive an SMS from bKash when the money arrives — usually within minutes.' ],
            [ '@type' => 'HowToStep', 'position' => 4, 'name' => 'Use or cash out', 'text' => 'Spend via bKash Pay or withdraw at any bKash agent or ATM.' ],
        ],
    ];
    echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
}

/* ══════════════════════════════════════════
   6. HREFLANG (EN / BN)
══════════════════════════════════════════ */
add_action( 'wp_head', 'takapath_hreflang_tags' );
function takapath_hreflang_tags() {
    $en_url = get_permalink();
    echo '<link rel="alternate" hreflang="en" href="' . esc_url( $en_url ) . '" />' . "\n";
    // Bengali alternate — when Polylang is active it provides pll_e() and the URL switches automatically
    if ( function_exists('pll_get_post') ) {
        $bn_id = pll_get_post( get_the_ID(), 'bn' );
        if ( $bn_id ) {
            echo '<link rel="alternate" hreflang="bn" href="' . esc_url( get_permalink( $bn_id ) ) . '" />' . "\n";
        }
    }
    echo '<link rel="alternate" hreflang="x-default" href="' . esc_url( $en_url ) . '" />' . "\n";
}

/* ══════════════════════════════════════════
   7. POLYLANG STRING REGISTRATION
══════════════════════════════════════════ */
add_action( 'init', 'takapath_register_polylang_strings' );
function takapath_register_polylang_strings() {
    if ( ! function_exists('pll_register_string') ) return;
    $strings = [
        'site_tagline'      => 'Compare best ways to send money to Bangladesh',
        'hero_h1'           => 'Best Way to Send Money to Bangladesh',
        'compare_cta'       => 'Compare Rates Now',
        'bkash_label'       => 'bKash',
        'bank_label'        => 'Bank deposit',
        'cash_label'        => 'Cash pickup',
        'rate_updated'      => 'Rates updated',
    ];
    foreach ( $strings as $name => $value ) {
        pll_register_string( $name, $value, 'TakaPath' );
    }
}

/* ══════════════════════════════════════════
   8. I18N — LOAD TEXTDOMAIN
══════════════════════════════════════════ */
add_action( 'after_setup_theme', 'takapath_load_textdomain' );
function takapath_load_textdomain() {
    load_child_theme_textdomain( 'takapath', get_stylesheet_directory() . '/languages' );
}

/* ══════════════════════════════════════════
   9. THUMBNAIL SUPPORT
══════════════════════════════════════════ */
add_action( 'after_setup_theme', 'takapath_theme_support' );
function takapath_theme_support() {
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'title-tag' );
    add_image_size( 'corridor-thumb', 800, 400, true );
}
