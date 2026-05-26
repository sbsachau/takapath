<?php
/**
 * TakaPath — page-guide.php
 * Template Name: TakaPath Guide Page
 *
 * Used for:
 *  - /bkash-receive-money-abroad/
 *  - /nagad-international-transfer/
 *  - /gbp-bdt-exchange-rate/
 *  - /usd-bdt-exchange-rate/
 *  - /sar-bdt-exchange-rate/
 *
 * Structure:
 *  1. Breadcrumb
 *  2. Guide hero (title + intro + Bengali subtitle)
 *  3. Table of Contents (auto-built from H2 headings in ACF sections)
 *  4. ACF guide sections (repeater: heading + body + optional tip box)
 *  5. Rate widget (contextual — shown on exchange-rate guide pages)
 *  6. FAQ accordion
 *  7. Related corridors
 *  8. CTA strip
 */

get_header();

$post_id     = get_the_ID();
$slug        = get_post_field( 'post_name', $post_id );

// ACF fields (safe fallbacks if ACF inactive)
$guide_intro     = function_exists( 'get_field' ) ? (string) get_field( 'guide_intro',     $post_id ) : '';
$guide_intro_bn  = function_exists( 'get_field' ) ? (string) get_field( 'guide_intro_bn',  $post_id ) : '';
$guide_sections  = function_exists( 'get_field' ) ? (array)  get_field( 'guide_sections',  $post_id ) : [];
$guide_faqs      = function_exists( 'get_field' ) ? (array)  get_field( 'faq_items',        $post_id ) : [];
$related_slugs   = function_exists( 'get_field' ) ? (array)  get_field( 'related_corridors',$post_id ) : [];
$show_widget     = function_exists( 'get_field' ) ? (bool)   get_field( 'show_rate_widget', $post_id ) : false;
$widget_currency = function_exists( 'get_field' ) ? (string) get_field( 'widget_currency',  $post_id ) : 'GBP';

// Determine guide type from slug for contextual default content
$is_bkash_guide  = ( $slug === 'bkash-receive-money-abroad' );
$is_nagad_guide  = ( $slug === 'nagad-international-transfer' );
$is_rate_guide   = in_array( $slug, [ 'gbp-bdt-exchange-rate', 'usd-bdt-exchange-rate', 'sar-bdt-exchange-rate' ], true );

// Fallback intro for bKash guide (shown when ACF not yet populated)
if ( $guide_intro === '' && $is_bkash_guide ) {
    $guide_intro    = 'bKash HomeSend lets you receive money from 136 countries directly into your bKash account. This guide explains how international remittances work on bKash, which providers support it, and how to receive money quickly and safely.';
    $guide_intro_bn = 'bKash HomeSend ১৩৬টি দেশ থেকে সরাসরি আপনার bKash অ্যাকাউন্টে টাকা পাঠানোর সুবিধা দেয়। এই গাইডে আন্তর্জাতিক রেমিট্যান্স কীভাবে bKash-এ কাজ করে তা বিস্তারিত আলোচনা করা হয়েছে।';
}

?>
<div class="guide-wrap">

  <!-- Breadcrumb -->
  <nav class="guide-breadcrumb" aria-label="<?php esc_attr_e( 'Breadcrumb', 'takapath' ); ?>">
    <div class="guide-breadcrumb__inner">
      <ol class="breadcrumb-list" role="list">
        <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'takapath' ); ?></a></li>
        <li aria-hidden="true" class="breadcrumb-sep">›</li>
        <li><a href="<?php echo esc_url( home_url( '/guides/' ) ); ?>"><?php esc_html_e( 'Guides', 'takapath' ); ?></a></li>
        <li aria-hidden="true" class="breadcrumb-sep">›</li>
        <li aria-current="page"><?php the_title(); ?></li>
      </ol>
    </div>
  </nav>

  <!-- Guide hero -->
  <header class="guide-hero">
    <div class="guide-hero__inner">

      <?php if ( $is_bkash_guide ) : ?>
      <div class="guide-badge">
        <span class="guide-badge__icon" aria-hidden="true">📱</span>
        <?php esc_html_e( 'bKash Guide', 'takapath' ); ?>
      </div>
      <?php elseif ( $is_nagad_guide ) : ?>
      <div class="guide-badge">
        <span class="guide-badge__icon" aria-hidden="true">💳</span>
        <?php esc_html_e( 'Nagad Guide', 'takapath' ); ?>
      </div>
      <?php elseif ( $is_rate_guide ) : ?>
      <div class="guide-badge">
        <span class="guide-badge__icon" aria-hidden="true">💱</span>
        <?php esc_html_e( 'Exchange Rate Guide', 'takapath' ); ?>
      </div>
      <?php endif; ?>

      <h1 class="guide-hero__title"><?php the_title(); ?></h1>

      <?php if ( $guide_intro !== '' ) : ?>
      <p class="guide-hero__intro"><?php echo wp_kses_post( $guide_intro ); ?></p>
      <?php endif; ?>

      <?php if ( $guide_intro_bn !== '' ) : ?>
      <p class="guide-hero__intro guide-hero__intro--bn" lang="bn"><?php echo wp_kses_post( $guide_intro_bn ); ?></p>
      <?php endif; ?>

      <!-- Last updated -->
      <p class="guide-meta">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        <?php
        printf(
            /* translators: %s = date */
            esc_html__( 'Last updated: %s', 'takapath' ),
            esc_html( get_the_modified_date( 'F Y' ) )
        );
        ?>
      </p>
    </div>
  </header>

  <!-- Main guide body -->
  <div class="guide-body">
    <div class="guide-body__inner">

      <!-- Table of Contents (shown only when sections exist) -->
      <?php
      // Build default sections for bKash guide when ACF not yet populated
      $default_bkash_sections = [
        [
          'heading' => 'What is bKash HomeSend?',
          'body'    => '<p>bKash HomeSend is bKash\'s international remittance receiving service, powered by a global network of money transfer operators. It allows Bangladeshis abroad to send money directly to a bKash mobile wallet in Bangladesh — no bank account required on the receiving end.</p><p>bKash processes over ৳15,000 crore in inbound remittances per year through HomeSend. The recipient receives a push notification the moment the funds arrive, usually within minutes of the sender completing the transfer.</p>',
          'tip'     => 'The recipient does not need to register separately for HomeSend. Any active bKash account can receive international transfers.',
        ],
        [
          'heading' => 'Which providers support bKash HomeSend?',
          'body'    => '<p>The following international money transfer operators have direct integration with bKash HomeSend:</p><ul><li><strong>Remitly</strong> — available in 20+ countries including UK, USA, Canada, Australia</li><li><strong>Western Union</strong> — global coverage, available in Saudi Arabia, UAE, Oman, Kuwait, Malaysia</li><li><strong>MoneyGram</strong> — wide agent network across Europe and the Middle East</li><li><strong>Ria Money Transfer</strong> — competitive for Italy and Spain corridors</li><li><strong>Transfast</strong> — strong USA to bKash rates</li><li><strong>Al Rajhi Bank</strong> — Saudi Arabia specific</li></ul><p>Coverage is expanding. TakaPath rates table shows live availability per provider for each source country.</p>',
          'tip'     => '',
        ],
        [
          'heading' => 'Step-by-step: How to receive money on bKash',
          'body'    => '<ol><li><strong>Share your bKash number</strong> — give the sender your 11-digit bKash mobile number (the same number your bKash account is registered to). No account number or IBAN needed.</li><li><strong>Sender initiates transfer</strong> — the sender selects bKash as the delivery method with a supported provider (e.g., Remitly). They enter your mobile number and the amount.</li><li><strong>You receive a notification</strong> — once the transfer is processed, bKash sends an SMS and in-app notification. Funds are credited instantly to your bKash balance.</li><li><strong>Use or withdraw</strong> — you can use the funds directly for bKash payments, send to bank account, or withdraw cash at any bKash agent point across Bangladesh.</li></ol>',
          'tip'     => 'There is a daily receiving limit on bKash HomeSend. As of 2026, the limit is ৳2,00,000 per day. For larger amounts, a bank transfer may be more appropriate.',
        ],
        [
          'heading' => 'bKash receiving limits and fees',
          'body'    => '<p>bKash does not charge the recipient for receiving international remittances via HomeSend. The sender pays the fee to the transfer provider.</p><table class="guide-table"><thead><tr><th>Limit type</th><th>Amount</th></tr></thead><tbody><tr><td>Daily receiving limit</td><td>৳2,00,000</td></tr><tr><td>Monthly receiving limit</td><td>৳5,00,000</td></tr><tr><td>Per transaction limit</td><td>৳1,00,000</td></tr><tr><td>Recipient fee</td><td>Free (৳0)</td></tr></tbody></table><p>Limits may change. Check the <a href="https://www.bkash.com" target="_blank" rel="noopener noreferrer">bKash official website</a> for the latest information.</p>',
          'tip'     => '',
        ],
        [
          'heading' => 'Compare providers sending to bKash',
          'body'    => '<p>Exchange rates and fees vary significantly between providers for bKash delivery. Use the TakaPath comparison tool to see live rates for your specific source country and amount.</p>[takapath_rates from="GBP" amount="500" selector="yes"]',
          'tip'     => 'Always compare at least 3 providers before sending. The difference in rate alone can be worth ৳500–৳1,500 on a £500 transfer.',
        ],
      ];

      $sections_to_render = ! empty( $guide_sections ) ? $guide_sections : ( $is_bkash_guide ? $default_bkash_sections : [] );
      ?>

      <?php if ( ! empty( $sections_to_render ) ) : ?>
      <nav class="guide-toc" aria-label="<?php esc_attr_e( 'Table of contents', 'takapath' ); ?>">
        <p class="guide-toc__label"><?php esc_html_e( 'In this guide', 'takapath' ); ?></p>
        <ol class="guide-toc__list" role="list">
          <?php foreach ( $sections_to_render as $i => $section ) : ?>
          <?php
            $heading_text = ! empty( $section['heading'] ) ? $section['heading'] : '';
            $heading_id   = 'section-' . ( $i + 1 );
          ?>
          <li><a href="#<?php echo esc_attr( $heading_id ); ?>"><?php echo esc_html( $heading_text ); ?></a></li>
          <?php endforeach; ?>
        </ol>
      </nav>
      <?php endif; ?>

      <!-- Guide sections -->
      <?php if ( ! empty( $sections_to_render ) ) : ?>
      <div class="guide-sections">
        <?php foreach ( $sections_to_render as $i => $section ) :
          $heading_text = ! empty( $section['heading'] ) ? $section['heading'] : '';
          $body_html    = ! empty( $section['body'] )    ? $section['body']    : '';
          $tip_text     = ! empty( $section['tip'] )     ? $section['tip']     : '';
          $section_id   = 'section-' . ( $i + 1 );
        ?>
        <section class="guide-section" id="<?php echo esc_attr( $section_id ); ?>">

          <?php if ( $heading_text !== '' ) : ?>
          <h2 class="guide-section__heading"><?php echo esc_html( $heading_text ); ?></h2>
          <?php endif; ?>

          <?php if ( $body_html !== '' ) : ?>
          <div class="guide-section__body">
            <?php echo do_shortcode( wp_kses_post( $body_html ) ); ?>
          </div>
          <?php endif; ?>

          <?php if ( $tip_text !== '' ) : ?>
          <div class="guide-tip" role="note">
            <span class="guide-tip__icon" aria-hidden="true">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            </span>
            <p><?php echo wp_kses_post( $tip_text ); ?></p>
          </div>
          <?php endif; ?>

        </section>
        <?php endforeach; ?>
      </div><!-- /.guide-sections -->
      <?php endif; ?>

      <!-- Rate widget (exchange-rate guide pages) -->
      <?php if ( $show_widget && $widget_currency !== '' ) : ?>
      <div class="guide-widget-section">
        <h2 class="guide-section__heading">
          <?php printf( esc_html__( 'Live %s to BDT Rates', 'takapath' ), esc_html( $widget_currency ) ); ?>
        </h2>
        <?php echo do_shortcode( '[takapath_rates from="' . esc_attr( $widget_currency ) . '" amount="500" selector="yes"]' ); ?>
      </div>
      <?php endif; ?>

      <!-- FAQ accordion -->
      <?php if ( ! empty( $guide_faqs ) ) : ?>
      <section class="guide-faq" aria-labelledby="guide-faq-heading">
        <h2 id="guide-faq-heading" class="guide-section__heading">
          <?php esc_html_e( 'Frequently Asked Questions', 'takapath' ); ?>
        </h2>
        <dl class="faq-accordion">
          <?php foreach ( $guide_faqs as $faq ) :
            $q = ! empty( $faq['question'] ) ? $faq['question'] : '';
            $a = ! empty( $faq['answer'] )   ? $faq['answer']   : '';
            if ( $q === '' ) continue;
          ?>
          <div class="faq-item">
            <dt>
              <button class="faq-toggle"
                      aria-expanded="false"
                      aria-controls="faq-<?php echo esc_attr( sanitize_title( $q ) ); ?>">
                <?php echo esc_html( $q ); ?>
                <svg class="faq-chevron" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polyline points="6 9 12 15 18 9"/></svg>
              </button>
            </dt>
            <dd id="faq-<?php echo esc_attr( sanitize_title( $q ) ); ?>" class="faq-answer" hidden>
              <?php echo wp_kses_post( $a ); ?>
            </dd>
          </div>
          <?php endforeach; ?>
        </dl>
      </section>
      <?php endif; ?>

    </div><!-- /.guide-body__inner -->

    <!-- Sidebar -->
    <aside class="guide-sidebar" aria-label="<?php esc_attr_e( 'Related tools', 'takapath' ); ?>">

      <!-- Quick compare widget -->
      <div class="sidebar-widget">
        <h3 class="sidebar-widget__title"><?php esc_html_e( 'Quick Rate Compare', 'takapath' ); ?></h3>
        <?php echo do_shortcode( '[takapath_rates from="GBP" amount="500"]' ); ?>
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="sidebar-widget__link">
          <?php esc_html_e( 'See full comparison →', 'takapath' ); ?>
        </a>
      </div>

      <!-- Related corridors -->
      <div class="sidebar-widget">
        <h3 class="sidebar-widget__title"><?php esc_html_e( 'Send Money From', 'takapath' ); ?></h3>
        <ul class="sidebar-corridor-list" role="list">
          <?php
          $corridor_links = [
            [ 'label' => '🇬🇧 UK → Bangladesh', 'slug' => 'uk-to-bangladesh' ],
            [ 'label' => '🇸🇦 Saudi Arabia → Bangladesh', 'slug' => 'saudi-arabia-to-bangladesh' ],
            [ 'label' => '🇦🇪 UAE → Bangladesh', 'slug' => 'uae-to-bangladesh' ],
            [ 'label' => '🇺🇸 USA → Bangladesh', 'slug' => 'usa-to-bangladesh' ],
            [ 'label' => '🇲🇾 Malaysia → Bangladesh', 'slug' => 'malaysia-to-bangladesh' ],
          ];
          foreach ( $corridor_links as $cl ) :
          ?>
          <li>
            <a href="<?php echo esc_url( home_url( '/send-money-to-bangladesh/' . $cl['slug'] . '/' ) ); ?>" class="sidebar-corridor-link">
              <?php echo esc_html( $cl['label'] ); ?>
            </a>
          </li>
          <?php endforeach; ?>
        </ul>
      </div>

    </aside>

  </div><!-- /.guide-body -->

  <!-- Bottom CTA strip -->
  <section class="guide-cta" aria-labelledby="guide-cta-heading">
    <div class="guide-cta__inner">
      <h2 id="guide-cta-heading"><?php esc_html_e( 'Ready to send money to Bangladesh?', 'takapath' ); ?></h2>
      <p><?php esc_html_e( 'Compare live rates from all major providers — bank deposit, bKash, Nagad, and cash pickup.', 'takapath' ); ?></p>
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn--primary">
        <?php esc_html_e( 'Compare Rates Now', 'takapath' ); ?>
      </a>
    </div>
  </section>

</div><!-- /.guide-wrap -->

<!-- FAQ accordion JS -->
<script>
(function(){
  document.querySelectorAll('.faq-toggle').forEach(function(btn){
    btn.addEventListener('click', function(){
      var expanded = btn.getAttribute('aria-expanded') === 'true';
      var answerId = btn.getAttribute('aria-controls');
      var answer   = document.getElementById(answerId);
      btn.setAttribute('aria-expanded', String(!expanded));
      btn.classList.toggle('faq-toggle--open', !expanded);
      if (answer) {
        if (expanded) { answer.setAttribute('hidden', ''); }
        else           { answer.removeAttribute('hidden'); }
      }
    });
  });
})();
</script>

<?php get_footer(); ?>
