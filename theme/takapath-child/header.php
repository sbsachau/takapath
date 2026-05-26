<?php
/**
 * TakaPath — header.php
 *
 * Outputs <html>, <head>, skip link, site header with:
 *  - SVG logo (inline, accessible)
 *  - Primary nav with active-state highlighting
 *  - Bengali / English language toggle (Polylang-aware, graceful fallback)
 *  - Mobile hamburger menu (CSS-only, no JS required)
 *  - Dark-mode toggle (JS picks up from style.css tokens)
 *
 * Called via get_header() in every page template.
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> data-theme="light">
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- Skip to main content (accessibility) -->
<a class="skip-link" href="#main-content">
    <?php esc_html_e( 'Skip to main content', 'takapath' ); ?>
</a>

<!-- ============================================================
     SITE HEADER
============================================================ -->
<header class="site-header" role="banner">
  <div class="site-header__inner">

    <!-- Logo -->
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-logo" aria-label="<?php esc_attr_e( 'TakaPath — Home', 'takapath' ); ?>">
      <svg class="site-logo__svg" viewBox="0 0 140 36" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
        <!-- T mark: stylised Bengali Taka glyph merged with path arrow -->
        <rect x="2" y="4" width="3" height="28" rx="1.5" fill="currentColor"/>
        <rect x="2" y="4" width="20" height="3" rx="1.5" fill="currentColor"/>
        <rect x="2" y="12" width="14" height="2.5" rx="1.25" fill="currentColor"/>
        <!-- Arrow path pointing right -->
        <path d="M28 18 L40 10 L40 15 L52 15 L52 21 L40 21 L40 26 Z" fill="var(--color-primary, #01696f)"/>
        <!-- Wordmark -->
        <text x="60" y="25" font-family="'Instrument Serif', Georgia, serif" font-size="20" font-weight="400" fill="currentColor" letter-spacing="-0.3">Taka</text>
        <text x="98" y="25" font-family="'Instrument Serif', Georgia, serif" font-size="20" font-weight="400" fill="var(--color-primary, #01696f)" letter-spacing="-0.3">Path</text>
      </svg>
    </a>

    <!-- Primary nav (desktop) -->
    <nav class="site-nav" role="navigation" aria-label="<?php esc_attr_e( 'Primary navigation', 'takapath' ); ?>">
      <ul class="site-nav__list" role="list">

        <?php
        // Build nav items. Use Polylang-aware URLs when available.
        $nav_items = [
            [
                'label' => __( 'Compare Rates', 'takapath' ),
                'url'   => home_url( '/' ),
                'id'    => 'home',
            ],
            [
                'label' => __( 'Corridors', 'takapath' ),
                'url'   => get_post_type_archive_link( 'corridor' ) ?: home_url( '/send-money-to-bangladesh/' ),
                'id'    => 'corridors',
            ],
            [
                'label' => __( 'bKash Guide', 'takapath' ),
                'url'   => home_url( '/bkash-receive-money-abroad/' ),
                'id'    => 'bkash',
            ],
            [
                'label' => __( 'About', 'takapath' ),
                'url'   => home_url( '/about/' ),
                'id'    => 'about',
            ],
        ];

        $current_url = ( is_ssl() ? 'https' : 'http' ) . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

        foreach ( $nav_items as $item ) :
            $is_active = ( rtrim( $current_url, '/' ) === rtrim( $item['url'], '/' ) )
                      || ( $item['id'] === 'corridors' && is_singular( 'corridor' ) )
                      || ( $item['id'] === 'corridors' && is_post_type_archive( 'corridor' ) );
        ?>
        <li class="site-nav__item">
          <a href="<?php echo esc_url( $item['url'] ); ?>"
             class="site-nav__link<?php echo $is_active ? ' site-nav__link--active' : ''; ?>"
             <?php echo $is_active ? 'aria-current="page"' : ''; ?>>
            <?php echo esc_html( $item['label'] ); ?>
          </a>
        </li>
        <?php endforeach; ?>

      </ul>
    </nav>

    <!-- Header actions -->
    <div class="site-header__actions">

      <?php
      // Language toggle — Polylang-aware
      if ( function_exists( 'pll_the_languages' ) ) :
          $langs = pll_the_languages( [ 'raw' => 1 ] );
          if ( ! empty( $langs ) ) :
              $current_lang  = pll_current_language();
              $other_lang    = ( $current_lang === 'en' ) ? 'bn' : 'en';
              $other_label   = ( $other_lang === 'bn' ) ? 'বাংলা' : 'EN';
              $other_url     = isset( $langs[ $other_lang ]['url'] ) ? $langs[ $other_lang ]['url'] : '';
              if ( $other_url ) :
      ?>
      <a href="<?php echo esc_url( $other_url ); ?>"
         class="lang-toggle"
         aria-label="<?php echo esc_attr( $other_lang === 'bn' ? 'Switch to Bengali' : 'Switch to English' ); ?>"
         hreflang="<?php echo esc_attr( $other_lang ); ?>">
        <?php echo esc_html( $other_label ); ?>
      </a>
      <?php endif; endif; endif; ?>

      <!-- Dark mode toggle -->
      <button class="theme-toggle"
              data-theme-toggle
              aria-label="<?php esc_attr_e( 'Switch to dark mode', 'takapath' ); ?>"
              title="<?php esc_attr_e( 'Toggle dark / light mode', 'takapath' ); ?>">
        <!-- Moon icon (shown in light mode) -->
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
          <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
        </svg>
      </button>

      <!-- Mobile hamburger toggle -->
      <button class="nav-toggle"
              aria-controls="mobile-nav"
              aria-expanded="false"
              aria-label="<?php esc_attr_e( 'Open navigation menu', 'takapath' ); ?>">
        <span class="nav-toggle__bar"></span>
        <span class="nav-toggle__bar"></span>
        <span class="nav-toggle__bar"></span>
      </button>

    </div><!-- /.site-header__actions -->
  </div><!-- /.site-header__inner -->

  <!-- Mobile nav drawer -->
  <nav class="mobile-nav" id="mobile-nav" aria-label="<?php esc_attr_e( 'Mobile navigation', 'takapath' ); ?>" hidden>
    <ul class="mobile-nav__list" role="list">
      <?php foreach ( $nav_items as $item ) : ?>
      <li>
        <a href="<?php echo esc_url( $item['url'] ); ?>"
           class="mobile-nav__link">
          <?php echo esc_html( $item['label'] ); ?>
        </a>
      </li>
      <?php endforeach; ?>
    </ul>
  </nav>

</header><!-- /.site-header -->

<!-- Main content landmark -->
<main id="main-content" tabindex="-1">
