<?php
/**
 * TakaPath — footer.php
 *
 * Outputs:
 *  - Closing </main>
 *  - Site footer: brand column, corridor links, trust/legal column
 *  - Structured data: Organization schema
 *  - wp_footer() hook
 *  - Closing </body></html>
 *
 * Called via get_footer() in every page template.
 */
?>

</main><!-- /#main-content -->

<!-- ============================================================
     SITE FOOTER
============================================================ -->
<footer class="site-footer" role="contentinfo">
  <div class="site-footer__inner">

    <!-- Column 1: Brand -->
    <div class="footer-col footer-col--brand">
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="footer-logo" aria-label="<?php esc_attr_e( 'TakaPath — Home', 'takapath' ); ?>">
        <svg viewBox="0 0 140 36" fill="none" xmlns="http://www.w3.org/2000/svg" width="120" height="31" aria-hidden="true" focusable="false">
          <rect x="2" y="4" width="3" height="28" rx="1.5" fill="currentColor"/>
          <rect x="2" y="4" width="20" height="3" rx="1.5" fill="currentColor"/>
          <rect x="2" y="12" width="14" height="2.5" rx="1.25" fill="currentColor"/>
          <path d="M28 18 L40 10 L40 15 L52 15 L52 21 L40 21 L40 26 Z" fill="var(--color-primary, #01696f)"/>
          <text x="60" y="25" font-family="'Instrument Serif', Georgia, serif" font-size="20" fill="currentColor">Taka</text>
          <text x="98" y="25" font-family="'Instrument Serif', Georgia, serif" font-size="20" fill="var(--color-primary, #01696f)">Path</text>
        </svg>
      </a>
      <p class="footer-tagline">
        <?php esc_html_e( 'Trusted comparison for Bangladeshis worldwide. Updated rates — bank, bKash &amp; cash.', 'takapath' ); ?>
      </p>
      <p class="footer-tagline footer-tagline--bn" lang="bn">
        বিশব্বের যেকোনো প্রান্ত থেকে বাংলাদেশে টাকা পাঠানোর সেরা পথ।
      </p>
      <!-- Trust badge -->
      <p class="footer-trust">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
        <?php esc_html_e( 'Independent &amp; unbiased comparison', 'takapath' ); ?>
      </p>
    </div><!-- /.footer-col--brand -->

    <!-- Column 2: Send Money corridors -->
    <div class="footer-col">
      <h3 class="footer-heading"><?php esc_html_e( 'Send Money To Bangladesh', 'takapath' ); ?></h3>
      <ul class="footer-links" role="list">
        <?php
        $corridors = [
            [ 'label' => __( 'From UK (GBP)',           'takapath' ), 'slug' => 'uk-to-bangladesh' ],
            [ 'label' => __( 'From Saudi Arabia (SAR)', 'takapath' ), 'slug' => 'saudi-arabia-to-bangladesh' ],
            [ 'label' => __( 'From UAE (AED)',           'takapath' ), 'slug' => 'uae-to-bangladesh' ],
            [ 'label' => __( 'From Malaysia (MYR)',      'takapath' ), 'slug' => 'malaysia-to-bangladesh' ],
            [ 'label' => __( 'From USA (USD)',           'takapath' ), 'slug' => 'usa-to-bangladesh' ],
            [ 'label' => __( 'From Oman (OMR)',          'takapath' ), 'slug' => 'oman-to-bangladesh' ],
            [ 'label' => __( 'From Italy (EUR)',         'takapath' ), 'slug' => 'italy-to-bangladesh' ],
            [ 'label' => __( 'From Kuwait (KWD)',        'takapath' ), 'slug' => 'kuwait-to-bangladesh' ],
        ];
        foreach ( $corridors as $c ) :
            $url = home_url( '/send-money-to-bangladesh/' . $c['slug'] . '/' );
        ?>
        <li>
          <a href="<?php echo esc_url( $url ); ?>" class="footer-link">
            <?php echo esc_html( $c['label'] ); ?>
          </a>
        </li>
        <?php endforeach; ?>
      </ul>
    </div><!-- /.footer-col -->

    <!-- Column 3: Guides -->
    <div class="footer-col">
      <h3 class="footer-heading"><?php esc_html_e( 'Guides', 'takapath' ); ?></h3>
      <ul class="footer-links" role="list">
        <li><a href="<?php echo esc_url( home_url( '/bkash-receive-money-abroad/' ) ); ?>" class="footer-link"><?php esc_html_e( 'How to receive money on bKash', 'takapath' ); ?></a></li>
        <li><a href="<?php echo esc_url( home_url( '/nagad-international-transfer/' ) ); ?>" class="footer-link"><?php esc_html_e( 'Receiving money on Nagad', 'takapath' ); ?></a></li>
        <li><a href="<?php echo esc_url( home_url( '/gbp-bdt-exchange-rate/' ) ); ?>" class="footer-link"><?php esc_html_e( 'GBP to BDT exchange rate', 'takapath' ); ?></a></li>
        <li><a href="<?php echo esc_url( home_url( '/usd-bdt-exchange-rate/' ) ); ?>" class="footer-link"><?php esc_html_e( 'USD to BDT exchange rate', 'takapath' ); ?></a></li>
        <li><a href="<?php echo esc_url( home_url( '/sar-bdt-exchange-rate/' ) ); ?>" class="footer-link"><?php esc_html_e( 'SAR to BDT exchange rate', 'takapath' ); ?></a></li>
      </ul>
    </div><!-- /.footer-col -->

    <!-- Column 4: Legal / Trust -->
    <div class="footer-col footer-col--legal">
      <h3 class="footer-heading"><?php esc_html_e( 'TakaPath', 'takapath' ); ?></h3>
      <ul class="footer-links" role="list">
        <li><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>" class="footer-link"><?php esc_html_e( 'About us', 'takapath' ); ?></a></li>
        <li><a href="<?php echo esc_url( home_url( '/how-we-work/' ) ); ?>" class="footer-link"><?php esc_html_e( 'How we work', 'takapath' ); ?></a></li>
        <li><a href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>" class="footer-link"><?php esc_html_e( 'Privacy policy', 'takapath' ); ?></a></li>
        <li><a href="<?php echo esc_url( home_url( '/disclaimer/' ) ); ?>" class="footer-link"><?php esc_html_e( 'Disclaimer', 'takapath' ); ?></a></li>
        <li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="footer-link"><?php esc_html_e( 'Contact', 'takapath' ); ?></a></li>
      </ul>
    </div><!-- /.footer-col--legal -->

  </div><!-- /.site-footer__inner -->

  <!-- Bottom bar -->
  <div class="footer-bottom">
    <div class="footer-bottom__inner">
      <p class="footer-copyright">
        &copy; <?php echo esc_html( date( 'Y' ) ); ?> TakaPath.
        <?php esc_html_e( 'For informational purposes only. Not financial advice.', 'takapath' ); ?>
      </p>
      <p class="footer-disclaimer">
        <?php esc_html_e( 'Rates shown are indicative and may differ from actual transfer rates. Always verify before sending.', 'takapath' ); ?>
      </p>
    </div>
  </div><!-- /.footer-bottom -->

</footer><!-- /.site-footer -->

<!-- Organization schema -->
<script type="application/ld+json">
<?php echo wp_json_encode( [
    '@context'  => 'https://schema.org',
    '@type'     => 'Organization',
    'name'      => 'TakaPath',
    'url'       => home_url( '/' ),
    'logo'      => home_url( '/wp-content/themes/takapath-child/assets/logo.svg' ),
    'description' => 'Independent comparison tool for money transfers to Bangladesh. Compare rates from Wise, Remitly, Western Union, bKash HomeSend and more.',
    'sameAs'    => [],
    'areaServed' => [
        [ '@type' => 'Country', 'name' => 'Bangladesh' ],
    ],
    'serviceType' => 'Financial comparison service',
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ); ?>
</script>

<!-- Dark mode + mobile nav JS -->
<script>
(function(){
  // Dark mode toggle
  var t = document.querySelector('[data-theme-toggle]');
  var r = document.documentElement;
  var d = matchMedia('(prefers-color-scheme:dark)').matches ? 'dark' : 'light';
  r.setAttribute('data-theme', d);
  if (t) {
    // Set correct initial icon
    t.innerHTML = d === 'dark'
      ? '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="5"/><path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/></svg>'
      : '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>';
    t.addEventListener('click', function(){
      d = d === 'dark' ? 'light' : 'dark';
      r.setAttribute('data-theme', d);
      t.setAttribute('aria-label', 'Switch to ' + (d === 'dark' ? 'light' : 'dark') + ' mode');
      t.innerHTML = d === 'dark'
        ? '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="5"/><path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/></svg>'
        : '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>';
    });
  }

  // Mobile hamburger toggle
  var btn  = document.querySelector('.nav-toggle');
  var menu = document.getElementById('mobile-nav');
  if (btn && menu) {
    btn.addEventListener('click', function(){
      var open = btn.getAttribute('aria-expanded') === 'true';
      btn.setAttribute('aria-expanded', String(!open));
      btn.setAttribute('aria-label', open ? '<?php echo esc_js( __( 'Open navigation menu', 'takapath' ) ); ?>' : '<?php echo esc_js( __( 'Close navigation menu', 'takapath' ) ); ?>');
      if (open) {
        menu.setAttribute('hidden', '');
        btn.classList.remove('nav-toggle--open');
      } else {
        menu.removeAttribute('hidden');
        btn.classList.add('nav-toggle--open');
      }
    });
    // Close on outside click
    document.addEventListener('click', function(e){
      if (!menu.contains(e.target) && !btn.contains(e.target)) {
        menu.setAttribute('hidden', '');
        btn.setAttribute('aria-expanded', 'false');
        btn.classList.remove('nav-toggle--open');
      }
    });
    // Close on Escape
    document.addEventListener('keydown', function(e){
      if (e.key === 'Escape' && btn.getAttribute('aria-expanded') === 'true') {
        menu.setAttribute('hidden', '');
        btn.setAttribute('aria-expanded', 'false');
        btn.classList.remove('nav-toggle--open');
        btn.focus();
      }
    });
  }
})();
</script>

<?php wp_footer(); ?>
</body>
</html>
