<?php
/**
 * TakaPath — Homepage Template
 * Template Name: TakaPath Homepage
 *
 * Sections:
 *   1. Hero           — headline, tagline, quick-send form anchor
 *   2. Trust bar      — four trust signals
 *   3. Live widget    — [takapath_rates] comparison shortcode (GBP default)
 *   4. Corridor grid  — 8 priority corridors as clickable cards
 *   5. How it works   — 3-step explainer
 *   6. Bengali intro  — dual-language trust paragraph (EN + BN)
 *   7. Stats strip    — Bangladesh remittance numbers
 *   8. Footer CTA     — prompt to explore all corridors
 */

declare( strict_types=1 );

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

// Priority corridors — static fallback before CPT posts are created
$corridors = [
	[ 'flag' => '🇸🇦', 'label' => 'Saudi Arabia → Bangladesh', 'currency' => 'SAR', 'slug' => 'saudi-arabia-to-bangladesh' ],
	[ 'flag' => '🇦🇪', 'label' => 'UAE → Bangladesh',          'currency' => 'AED', 'slug' => 'uae-to-bangladesh' ],
	[ 'flag' => '🇬🇧', 'label' => 'UK → Bangladesh',           'currency' => 'GBP', 'slug' => 'uk-to-bangladesh' ],
	[ 'flag' => '🇲🇾', 'label' => 'Malaysia → Bangladesh',     'currency' => 'MYR', 'slug' => 'malaysia-to-bangladesh' ],
	[ 'flag' => '🇺🇸', 'label' => 'USA → Bangladesh',          'currency' => 'USD', 'slug' => 'usa-to-bangladesh' ],
	[ 'flag' => '🇴🇲', 'label' => 'Oman → Bangladesh',         'currency' => 'OMR', 'slug' => 'oman-to-bangladesh' ],
	[ 'flag' => '🇮🇹', 'label' => 'Italy → Bangladesh',        'currency' => 'EUR', 'slug' => 'italy-to-bangladesh' ],
	[ 'flag' => '🇰🇼', 'label' => 'Kuwait → Bangladesh',       'currency' => 'KWD', 'slug' => 'kuwait-to-bangladesh' ],
];
?>

<!-- ══════════════════════════════════════════════ 1. HERO ═══ -->
<section class="takapath-hero tp-home-hero" aria-label="Homepage hero">
	<div class="takapath-hero__inner">

		<span class="takapath-hero__flag" aria-hidden="true">🇧🇩</span>

		<span class="volume-note">
			<?php esc_html_e( '📈 Bangladesh received $33 billion in remittances in 2025', 'takapath-child' ); ?>
		</span>

		<h1><?php esc_html_e( 'Best Way to Send Money to Bangladesh — Compare Rates & Fees', 'takapath-child' ); ?></h1>

		<p class="tagline">
			<?php esc_html_e( 'Trusted comparison for Bangladeshis worldwide. Updated rates from real providers — bank deposit, bKash, Nagad and cash pickup.', 'takapath-child' ); ?>
		</p>

		<div class="tp-hero-actions">
			<a href="#live-rates" class="btn btn-accent">
				<?php esc_html_e( 'Compare rates now', 'takapath-child' ); ?>
				<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M12 5v14M5 12l7 7 7-7"/></svg>
			</a>
			<a href="<?php echo esc_url( home_url( '/send-money-to-bangladesh/' ) ); ?>" class="btn btn-outline" style="color:#fff;border-color:rgba(255,255,255,0.5);">
				<?php esc_html_e( 'All corridors', 'takapath-child' ); ?>
			</a>
		</div>

	</div>
</section>

<!-- ══════════════════════════════════════════════ 2. TRUST BAR ═══ -->
<div class="trust-bar">
	<div class="trust-bar__item">
		<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
		<?php esc_html_e( 'Rates updated every hour', 'takapath-child' ); ?>
	</div>
	<div class="trust-bar__item">
		<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
		<?php esc_html_e( 'No hidden fees', 'takapath-child' ); ?>
	</div>
	<div class="trust-bar__item">
		<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
		<?php esc_html_e( 'Independent — not owned by any provider', 'takapath-child' ); ?>
	</div>
	<div class="trust-bar__item">
		<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
		<?php esc_html_e( 'bKash, Nagad & bank deposit covered', 'takapath-child' ); ?>
	</div>
</div>

<!-- ══════════════════════════════════════════════ 3. LIVE RATES WIDGET ═══ -->
<div id="live-rates" class="takapath-section">
	<h2 class="takapath-section__title">
		<?php esc_html_e( 'Compare live GBP → BDT rates', 'takapath-child' ); ?>
	</h2>
	<p style="color:var(--color-text-muted);margin-bottom:var(--space-6);font-size:var(--text-sm);">
		<?php esc_html_e( 'Showing rates for £1,000 from UK. Use the currency selector to change source country.', 'takapath-child' ); ?>
	</p>
	<?php echo do_shortcode( '[takapath_rates from="GBP" amount="1000" selector="yes"]' ); ?>
</div>

<hr class="section-divider" aria-hidden="true">

<!-- ══════════════════════════════════════════════ 4. CORRIDOR GRID ═══ -->
<div class="takapath-section">
	<h2 class="takapath-section__title">
		<?php esc_html_e( 'Send money from your country', 'takapath-child' ); ?>
	</h2>
	<p style="color:var(--color-text-muted);margin-bottom:var(--space-6);font-size:var(--text-sm);">
		<?php esc_html_e( 'Select your country for corridor-specific rates, fee breakdowns, and the fastest transfer options.', 'takapath-child' ); ?>
	</p>

	<div class="corridor-grid" role="list">
		<?php
		$cpt_query = new WP_Query( [
			'post_type'      => 'corridor',
			'posts_per_page' => 8,
			'orderby'        => 'menu_order',
			'order'          => 'ASC',
			'post_status'    => 'publish',
		] );

		if ( $cpt_query->have_posts() ) :
			while ( $cpt_query->have_posts() ) : $cpt_query->the_post();
				$flag     = (string) ( get_field( 'flag_emoji' )    ?: '🌍' );
				$label    = (string) ( get_field( 'route_label' )   ?: get_the_title() );
				$currency = (string) ( get_field( 'from_currency' ) ?: '' );
			?>
			<a href="<?php the_permalink(); ?>" class="corridor-card" role="listitem"
			   aria-label="<?php echo esc_attr( sprintf( __( 'Send money from %s', 'takapath-child' ), $label ) ); ?>">
				<span class="corridor-card__flag" aria-hidden="true"><?php echo esc_html( $flag ); ?></span>
				<h3 class="corridor-card__title"><?php echo esc_html( $label ); ?></h3>
				<?php if ( $currency ) : ?>
					<p class="corridor-card__meta"><?php echo esc_html( $currency ); ?> → BDT</p>
				<?php endif; ?>
			</a>
			<?php
			endwhile;
			wp_reset_postdata();
		else :
			foreach ( $corridors as $c ) :
				$url = home_url( '/send-money-to-bangladesh/' . esc_attr( $c['slug'] ) . '/' );
			?>
			<a href="<?php echo esc_url( $url ); ?>" class="corridor-card" role="listitem"
			   aria-label="<?php echo esc_attr( sprintf( __( 'Send money from %s', 'takapath-child' ), $c['label'] ) ); ?>">
				<span class="corridor-card__flag" aria-hidden="true"><?php echo esc_html( $c['flag'] ); ?></span>
				<h3 class="corridor-card__title"><?php echo esc_html( $c['label'] ); ?></h3>
				<p class="corridor-card__meta"><?php echo esc_html( $c['currency'] ); ?> → BDT</p>
			</a>
			<?php
			endforeach;
			endif;
		?>
	</div>

	<p style="text-align:center;margin-top:var(--space-8);">
		<a href="<?php echo esc_url( home_url( '/send-money-to-bangladesh/' ) ); ?>" class="btn btn-outline">
			<?php esc_html_e( 'View all corridors', 'takapath-child' ); ?>
			<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
		</a>
	</p>
</div>

<hr class="section-divider" aria-hidden="true">

<!-- ══════════════════════════════════════════════ 5. HOW IT WORKS ═══ -->
<div class="takapath-section">
	<h2 class="takapath-section__title" style="text-align:center;max-width:100%;">
		<?php esc_html_e( 'How TakaPath works', 'takapath-child' ); ?>
	</h2>

	<div class="tp-steps">
		<div class="tp-step">
			<div class="tp-step__number" aria-hidden="true">1</div>
			<div class="tp-step__body">
				<h3><?php esc_html_e( 'Choose your country', 'takapath-child' ); ?></h3>
				<p><?php esc_html_e( 'Select where you are sending from. We support Saudi Arabia, UAE, UK, Malaysia, USA and more.', 'takapath-child' ); ?></p>
			</div>
		</div>
		<div class="tp-step">
			<div class="tp-step__number" aria-hidden="true">2</div>
			<div class="tp-step__body">
				<h3><?php esc_html_e( 'Compare providers', 'takapath-child' ); ?></h3>
				<p><?php esc_html_e( 'See live rates and fees from Wise, Remitly, Western Union, bKash HomeSend and others side by side.', 'takapath-child' ); ?></p>
			</div>
		</div>
		<div class="tp-step">
			<div class="tp-step__number" aria-hidden="true">3</div>
			<div class="tp-step__body">
				<h3><?php esc_html_e( 'Send with confidence', 'takapath-child' ); ?></h3>
				<p><?php esc_html_e( 'Click through to the best provider. We never hold your money — TakaPath is a free comparison service.', 'takapath-child' ); ?></p>
			</div>
		</div>
	</div>
</div>

<hr class="section-divider" aria-hidden="true">

<!-- ══════════════════════════════════════════════ 6. DUAL-LANGUAGE INTRO ═══ -->
<div class="takapath-section">
	<div class="tp-bilingual">
		<div class="tp-bilingual__en">
			<h2><?php esc_html_e( 'Built for Bangladeshis worldwide', 'takapath-child' ); ?></h2>
			<p><?php esc_html_e( 'Whether you are sending from Riyadh, London, New York or Kuala Lumpur, TakaPath gives you an honest, up-to-date comparison so your family in Bangladesh gets the most taka possible.', 'takapath-child' ); ?></p>
			<p style="margin-top:var(--space-4);"><?php esc_html_e( 'We compare bank deposit, bKash, Nagad and cash pickup options — all in one place, with no signup required.', 'takapath-child' ); ?></p>
		</div>
		<div class="tp-bilingual__bn" lang="bn">
			<h2>বিশ্বজুড়ে বাংলাদেশিদের জন্য তৈরি</h2>
			<p>সৌদি আরব, যুক্তরাজ্য, মালয়েশিয়া বা আমেরিকা যেখান থেকেই পাঠান, TakaPath আপনাকে সর্বোত্তম হার খুঁজে পেতে সাহায্য করে।</p>
			<p style="margin-top:var(--space-4);">ব্যাংক ডিপোজিট, bKash, Nagad এবং ক্যাশ পিকআপ — সব একজায়গা। রেজিস্ট্রেশন ছাড়াই তুলনা করুন।</p>
		</div>
	</div>
</div>

<hr class="section-divider" aria-hidden="true">

<!-- ══════════════════════════════════════════════ 7. STATS STRIP ═══ -->
<div class="tp-stats-strip" aria-label="Bangladesh remittance statistics">
	<div class="tp-stats-strip__inner">
		<div class="tp-stat">
			<span class="tp-stat__number">$33B</span>
			<span class="tp-stat__label"><?php esc_html_e( 'sent to Bangladesh in 2025', 'takapath-child' ); ?></span>
		</div>
		<div class="tp-stat">
			<span class="tp-stat__number">$2.97B</span>
			<span class="tp-stat__label"><?php esc_html_e( 'in May 2025 alone (+32% YoY)', 'takapath-child' ); ?></span>
		</div>
		<div class="tp-stat">
			<span class="tp-stat__number">136</span>
			<span class="tp-stat__label"><?php esc_html_e( 'countries via bKash HomeSend', 'takapath-child' ); ?></span>
		</div>
		<div class="tp-stat">
			<span class="tp-stat__number">8+</span>
			<span class="tp-stat__label"><?php esc_html_e( 'corridors compared on TakaPath', 'takapath-child' ); ?></span>
		</div>
	</div>
</div>

<!-- ══════════════════════════════════════════════ 8. FOOTER CTA ═══ -->
<div class="takapath-section tp-footer-cta">
	<div class="tp-footer-cta__inner">
		<h2><?php esc_html_e( 'Ready to find the best rate?', 'takapath-child' ); ?></h2>
		<p><?php esc_html_e( 'Compare every major provider in seconds. Free, independent, and updated every hour.', 'takapath-child' ); ?></p>
		<a href="#live-rates" class="btn btn-primary" style="font-size:var(--text-base);padding:var(--space-4) var(--space-8);">
			<?php esc_html_e( 'Compare rates now', 'takapath-child' ); ?>
		</a>
	</div>
</div>

<?php get_footer(); ?>
