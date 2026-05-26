<?php
/**
 * Template Name: TakaPath About Page
 * Template Post Type: page
 *
 * E-E-A-T trust page: who we are, our methodology, editorial policy.
 * Slug: about
 */

get_header();
?>

<main id="main-content" class="tp-about">

  <!-- ══════════════════════════════════════════
       HERO
  ══════════════════════════════════════════ -->
  <section class="about-hero">
    <div class="tp-container">
      <nav class="tp-breadcrumb" aria-label="Breadcrumb">
        <ol itemscope itemtype="https://schema.org/BreadcrumbList">
          <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
            <a href="<?php echo esc_url( home_url('/') ); ?>" itemprop="item"><span itemprop="name"><?php esc_html_e('Home','takapath'); ?></span></a>
            <meta itemprop="position" content="1" />
          </li>
          <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
            <span itemprop="name"><?php esc_html_e('About TakaPath','takapath'); ?></span>
            <meta itemprop="position" content="2" />
          </li>
        </ol>
      </nav>

      <div class="about-hero__inner">
        <div class="about-hero__text">
          <span class="about-badge"><?php esc_html_e('About Us','takapath'); ?></span>
          <h1><?php esc_html_e('We help Bangladeshis worldwide send money home for less','takapath'); ?></h1>
          <p class="about-hero__lead"><?php esc_html_e('TakaPath is an independent comparison service for Bangladesh remittances. We track exchange rates and fees from dozens of providers — so you can see at a glance where your money goes furthest.','takapath'); ?></p>
          <p class="about-hero__lead-bn">আমরা বিশ্বজুড়ে বাংলাদেশিদের জন্য সেরা রেমিট্যান্স রেট খুঁজে বের করি।</p>
        </div>
        <div class="about-hero__stat-grid">
          <div class="stat-card">
            <span class="stat-card__number">$33B+</span>
            <span class="stat-card__label"><?php esc_html_e('Bangladesh remittances in 2025','takapath'); ?></span>
          </div>
          <div class="stat-card">
            <span class="stat-card__number">30+</span>
            <span class="stat-card__label"><?php esc_html_e('Providers tracked','takapath'); ?></span>
          </div>
          <div class="stat-card">
            <span class="stat-card__number">13</span>
            <span class="stat-card__label"><?php esc_html_e('Source currencies covered','takapath'); ?></span>
          </div>
          <div class="stat-card">
            <span class="stat-card__number">100%</span>
            <span class="stat-card__label"><?php esc_html_e('Independent — no provider pays for placement','takapath'); ?></span>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ══════════════════════════════════════════
       MISSION
  ══════════════════════════════════════════ -->
  <section class="about-section about-section--alt">
    <div class="tp-container tp-container--narrow">
      <h2><?php esc_html_e('Our mission','takapath'); ?></h2>
      <p><?php esc_html_e('Bangladesh received a record $33 billion in remittances in 2025. The people sending that money — garment workers in Malaysia, nurses in the UK, engineers in the UAE — often pay more than they should in fees and poor exchange rates. A 1% improvement in rate across the entire corridor would put hundreds of millions of dollars back into Bangladeshi families.','takapath'); ?></p>
      <p><?php esc_html_e('TakaPath exists to close that information gap. We make it easy to see which provider offers the best rate right now, for your country, your amount, and your recipient\'s preferred payout method — bank deposit, bKash, Nagad, or cash pickup.','takapath'); ?></p>
      <blockquote class="about-quote">
        <p><?php esc_html_e('"Every extra taka that reaches a family in Bangladesh is a taka that wasn\'t lost to an unnecessary fee."','takapath'); ?></p>
      </blockquote>
    </div>
  </section>

  <!-- ══════════════════════════════════════════
       HOW WE MAKE MONEY (transparency)
  ══════════════════════════════════════════ -->
  <section class="about-section">
    <div class="tp-container tp-container--narrow">
      <h2><?php esc_html_e('How TakaPath makes money','takapath'); ?></h2>
      <p><?php esc_html_e('TakaPath earns a small referral fee when you click through to a provider and complete a transfer. This never affects our rankings — providers are always sorted by the best rate for you, not by who pays us the most.','takapath'); ?></p>
      <div class="about-transparency-box">
        <div class="transparency-item">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
          <span><?php esc_html_e('Providers are ranked by best rate — always','takapath'); ?></span>
        </div>
        <div class="transparency-item">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
          <span><?php esc_html_e('No provider pays to appear at the top of results','takapath'); ?></span>
        </div>
        <div class="transparency-item">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
          <span><?php esc_html_e('Rate data is fetched directly from provider APIs or verified manually','takapath'); ?></span>
        </div>
        <div class="transparency-item">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
          <span><?php esc_html_e('We are not a money transfer operator — we do not hold or move your money','takapath'); ?></span>
        </div>
      </div>
    </div>
  </section>

  <!-- ══════════════════════════════════════════
       EDITORIAL POLICY
  ══════════════════════════════════════════ -->
  <section class="about-section about-section--alt">
    <div class="tp-container tp-container--narrow">
      <h2><?php esc_html_e('Editorial policy','takapath'); ?></h2>
      <p><?php esc_html_e('Our content is written and reviewed by people who have personally sent money to Bangladesh or helped others do so. All rate data shown on TakaPath reflects rates available to a typical retail customer sending a standard amount — not the best-possible rates available only at high volumes.','takapath'); ?></p>

      <h3><?php esc_html_e('Rate accuracy','takapath'); ?></h3>
      <p><?php esc_html_e('Rates are updated at least every 6 hours via automated data collection from provider sources. They are indicative — the rate you receive at the time of transfer may differ slightly. Always confirm the final rate before completing any transfer.','takapath'); ?></p>

      <h3><?php esc_html_e('Provider coverage','takapath'); ?></h3>
      <p><?php esc_html_e('We include all major providers operating the Bangladesh remittance corridor. A provider\'s absence from our comparison does not imply endorsement or rejection — it may simply reflect that rate data is not yet available for that provider.','takapath'); ?></p>

      <h3><?php esc_html_e('Corrections','takapath'); ?></h3>
      <p><?php echo sprintf(
        /* translators: %s: contact email link */
        esc_html__( 'If you spot an error in our data or content, please %s. We take accuracy seriously and will review and correct within 48 hours.', 'takapath' ),
        '<a href="mailto:hello@takapath.com">'.esc_html__('contact us','takapath').'</a>'
      ); ?></p>
    </div>
  </section>

  <!-- ══════════════════════════════════════════
       CONTACT
  ══════════════════════════════════════════ -->
  <section class="about-section">
    <div class="tp-container tp-container--narrow">
      <h2><?php esc_html_e('Get in touch','takapath'); ?></h2>
      <p><?php esc_html_e('We\'re a small independent team focused on one corridor. We love hearing from the Bangladeshi diaspora — whether that\'s a provider tip, a data correction, or a partnership enquiry.','takapath'); ?></p>
      <div class="about-contact-grid">
        <a href="mailto:hello@takapath.com" class="about-contact-card">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
          <span><?php esc_html_e('hello@takapath.com','takapath'); ?></span>
        </a>
        <a href="https://twitter.com/takapath" class="about-contact-card" target="_blank" rel="noopener noreferrer">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.744l7.737-8.835L1.254 2.25H8.08l4.259 5.63 5.905-5.63Zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
          <span>@TakaPath</span>
        </a>
      </div>
    </div>
  </section>

</main>

<?php get_footer(); ?>
