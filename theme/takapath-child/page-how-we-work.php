<?php
/**
 * Template Name: TakaPath How We Work
 * Template Post Type: page
 *
 * Methodology transparency page — rate sourcing, ranking algorithm,
 * update frequency. Critical for Google E-E-A-T and user trust.
 * Slug: how-we-work
 */

get_header();
?>

<main id="main-content" class="tp-how-we-work">

  <!-- ══════════════════════════════════════════
       HERO
  ══════════════════════════════════════════ -->
  <section class="hww-hero">
    <div class="tp-container">
      <nav class="tp-breadcrumb" aria-label="Breadcrumb">
        <ol itemscope itemtype="https://schema.org/BreadcrumbList">
          <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
            <a href="<?php echo esc_url( home_url('/') ); ?>" itemprop="item"><span itemprop="name"><?php esc_html_e('Home','takapath'); ?></span></a>
            <meta itemprop="position" content="1" />
          </li>
          <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
            <span itemprop="name"><?php esc_html_e('How We Work','takapath'); ?></span>
            <meta itemprop="position" content="2" />
          </li>
        </ol>
      </nav>

      <div class="hww-hero__inner">
        <span class="about-badge"><?php esc_html_e('Methodology','takapath'); ?></span>
        <h1><?php esc_html_e('How TakaPath works','takapath'); ?></h1>
        <p class="hww-hero__lead"><?php esc_html_e('Full transparency on how we collect rate data, rank providers, and keep our comparisons honest.','takapath'); ?></p>
      </div>
    </div>
  </section>

  <!-- ══════════════════════════════════════════
       PROCESS STEPS
  ══════════════════════════════════════════ -->
  <section class="hww-section">
    <div class="tp-container">
      <h2 class="hww-section__title"><?php esc_html_e('How our comparison works','takapath'); ?></h2>

      <ol class="hww-steps">

        <li class="hww-step">
          <div class="hww-step__number">01</div>
          <div class="hww-step__content">
            <h3><?php esc_html_e('We collect rate data from providers','takapath'); ?></h3>
            <p><?php esc_html_e('Our system pulls live exchange rate data directly from provider APIs and verified public rate pages. Data is collected continuously and cached for up to 6 hours. For providers without a public API, our data team manually verifies rates at least once per day.','takapath'); ?></p>
            <div class="hww-step__detail">
              <strong><?php esc_html_e('Providers covered:','takapath'); ?></strong>
              <span><?php esc_html_e('Wise, Remitly, Western Union, MoneyGram, Ria, Al Rajhi Bank, bKash International, Paysend, WorldRemit, TransferGo, and more.','takapath'); ?></span>
            </div>
          </div>
        </li>

        <li class="hww-step">
          <div class="hww-step__number">02</div>
          <div class="hww-step__content">
            <h3><?php esc_html_e('We calculate the true cost of your transfer','takapath'); ?></h3>
            <p><?php esc_html_e('Exchange rate alone doesn\'t tell the full story. We calculate the total amount your recipient receives after all fees — the upfront transfer fee, any exchange rate margin, and recipient fees where applicable. This "recipient receives" figure is what we rank by.','takapath'); ?></p>
            <div class="hww-cost-formula">
              <div class="formula-row">
                <span class="formula-label"><?php esc_html_e('You send','takapath'); ?></span>
                <span class="formula-value">£500</span>
              </div>
              <div class="formula-row formula-row--minus">
                <span class="formula-label"><?php esc_html_e('Transfer fee','takapath'); ?></span>
                <span class="formula-value">− £3.50</span>
              </div>
              <div class="formula-row formula-row--times">
                <span class="formula-label"><?php esc_html_e('Exchange rate applied','takapath'); ?></span>
                <span class="formula-value">× 141.20</span>
              </div>
              <div class="formula-row formula-row--result">
                <span class="formula-label"><?php esc_html_e('Recipient gets','takapath'); ?></span>
                <span class="formula-value">৳70,107</span>
              </div>
            </div>
          </div>
        </li>

        <li class="hww-step">
          <div class="hww-step__number">03</div>
          <div class="hww-step__content">
            <h3><?php esc_html_e('We rank by best recipient amount','takapath'); ?></h3>
            <p><?php esc_html_e('Providers are ranked by who delivers the most BDT to your recipient for your specific send amount. No paid placement. No sponsored rankings. The best deal is always at the top.','takapath'); ?></p>
            <div class="hww-step__detail hww-step__detail--green">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
              <span><?php esc_html_e('No provider pays for a higher ranking on TakaPath.','takapath'); ?></span>
            </div>
          </div>
        </li>

        <li class="hww-step">
          <div class="hww-step__number">04</div>
          <div class="hww-step__content">
            <h3><?php esc_html_e('We show payout method details','takapath'); ?></h3>
            <p><?php esc_html_e('Not all providers support all payout methods in Bangladesh. We show clearly which providers support bank deposit, bKash, Nagad, and cash pickup — and flag any payout-method-specific rate differences.','takapath'); ?></p>
            <ul class="hww-payout-list">
              <li><span class="payout-tag payout-tag--bank"><?php esc_html_e('Bank deposit','takapath'); ?></span> <?php esc_html_e('All major Bangladeshi banks (Sonali, Dutch-Bangla, BRAC, Islami, etc.)','takapath'); ?></li>
              <li><span class="payout-tag payout-tag--bkash"><?php esc_html_e('bKash','takapath'); ?></span> <?php esc_html_e('Instant delivery via bKash HomeSend, 136 source countries','takapath'); ?></li>
              <li><span class="payout-tag payout-tag--nagad"><?php esc_html_e('Nagad','takapath'); ?></span> <?php esc_html_e('Growing coverage, check per-provider availability','takapath'); ?></li>
              <li><span class="payout-tag payout-tag--cash"><?php esc_html_e('Cash pickup','takapath'); ?></span> <?php esc_html_e('Available via Western Union, MoneyGram, Ria at partner locations','takapath'); ?></li>
            </ul>
          </div>
        </li>

        <li class="hww-step">
          <div class="hww-step__number">05</div>
          <div class="hww-step__content">
            <h3><?php esc_html_e('We link you directly to the provider','takapath'); ?></h3>
            <p><?php esc_html_e('We are a comparison service, not a money transfer operator. When you click "Send with [Provider]", you go directly to that provider\'s website or app to complete the transfer. We never handle your money.','takapath'); ?></p>
            <p><?php esc_html_e('TakaPath may earn a small referral commission if you complete a transfer via our links. This never affects our rankings or data.','takapath'); ?></p>
          </div>
        </li>

      </ol>
    </div>
  </section>

  <!-- ══════════════════════════════════════════
       DATA FRESHNESS
  ══════════════════════════════════════════ -->
  <section class="hww-section hww-section--alt">
    <div class="tp-container tp-container--narrow">
      <h2><?php esc_html_e('Data freshness & accuracy','takapath'); ?></h2>

      <div class="hww-freshness-grid">
        <div class="freshness-card">
          <div class="freshness-card__icon">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
          </div>
          <h3><?php esc_html_e('Updated every 6 hours','takapath'); ?></h3>
          <p><?php esc_html_e('Our automated system refreshes rate data every 6 hours. Rates are timestamped on every comparison table so you can see exactly how fresh the data is.','takapath'); ?></p>
        </div>
        <div class="freshness-card">
          <div class="freshness-card__icon">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
          </div>
          <h3><?php esc_html_e('Mid-market rate reference','takapath'); ?></h3>
          <p><?php esc_html_e('We show the mid-market GBP/BDT (or USD/BDT etc.) rate alongside each provider so you can see the markup each provider adds. The mid-market rate comes from the European Central Bank via the Frankfurter API.','takapath'); ?></p>
        </div>
        <div class="freshness-card">
          <div class="freshness-card__icon">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
          </div>
          <h3><?php esc_html_e('Standard send amounts','takapath'); ?></h3>
          <p><?php esc_html_e('All comparisons are calculated for a standard retail transfer amount (e.g. £500, $500, SAR 2,000). Rates can differ at other amounts — always check the final rate on the provider\'s site before sending.','takapath'); ?></p>
        </div>
      </div>
    </div>
  </section>

  <!-- ══════════════════════════════════════════
       PROVIDER INCLUSION CRITERIA
  ══════════════════════════════════════════ -->
  <section class="hww-section">
    <div class="tp-container tp-container--narrow">
      <h2><?php esc_html_e('Provider inclusion criteria','takapath'); ?></h2>
      <p><?php esc_html_e('We include a provider in our comparison if it meets all of the following criteria:','takapath'); ?></p>
      <ul class="hww-criteria-list">
        <li><?php esc_html_e('Regulated or licensed in at least one country where it operates','takapath'); ?></li>
        <li><?php esc_html_e('Supports transfers to Bangladesh (BDT) with bank deposit, bKash, or cash pickup','takapath'); ?></li>
        <li><?php esc_html_e('Publicly advertises a retail exchange rate we can verify','takapath'); ?></li>
        <li><?php esc_html_e('Has a functional customer support channel (web, phone, or chat)','takapath'); ?></li>
      </ul>
      <p><?php echo sprintf(
        esc_html__( 'If your provider is missing from our comparison, %s and we will review inclusion.', 'takapath' ),
        '<a href="mailto:hello@takapath.com">'.esc_html__('let us know','takapath').'</a>'
      ); ?></p>
    </div>
  </section>

  <!-- ══════════════════════════════════════════
       CTA
  ══════════════════════════════════════════ -->
  <section class="hww-cta">
    <div class="tp-container">
      <h2><?php esc_html_e('Ready to compare?','takapath'); ?></h2>
      <p><?php esc_html_e('See today\'s best rates for your corridor — in seconds.','takapath'); ?></p>
      <a href="<?php echo esc_url( home_url('/') ); ?>" class="tp-btn tp-btn--primary tp-btn--lg">
        <?php esc_html_e('Compare rates now','takapath'); ?>
      </a>
    </div>
  </section>

</main>

<?php get_footer(); ?>
