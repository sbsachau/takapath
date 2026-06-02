<?php
/**
 * TakaPath — Homepage (front-page.php)
 * WordPress gives this file highest priority when a static front page is set.
 * Sections: announcement bar, hero, trust bar, live rate widget, corridor grid, content band.
 */
declare( strict_types=1 );
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();

$hero_heading     = function_exists( 'get_field' ) ? ( get_field( 'hero_heading' ) ?: 'Best Way to Send Money to Bangladesh' ) : 'Best Way to Send Money to Bangladesh';
$hero_tagline     = function_exists( 'get_field' ) ? ( get_field( 'hero_tagline' ) ?: 'Trusted comparison for Bangladeshis worldwide. Updated rates from real providers -- bank deposit, bKash, and cash pickup.' ) : 'Trusted comparison for Bangladeshis worldwide. Updated rates from real providers -- bank deposit, bKash, and cash pickup.';
$default_currency = function_exists( 'get_field' ) ? ( get_field( 'default_currency' ) ?: 'GBP' ) : 'GBP';
$trust_stats      = function_exists( 'get_field' ) ? ( get_field( 'trust_stats' ) ?: [] ) : [];
$announcement     = function_exists( 'get_field' ) ? ( get_field( 'announcement' ) ?: '' ) : '';

$corridors = get_posts( [
	'post_type'      => 'corridor',
	'posts_per_page' => 8,
	'orderby'        => 'menu_order',
	'order'          => 'ASC',
	'post_status'    => 'publish',
] );

// Default trust stats if none set via ACF
$default_trust_stats = [
	[ 'stat' => '10+',    'label' => 'Providers compared' ],
	[ 'stat' => 'BDT',    'label' => 'Bank, bKash & cash pickup' ],
	[ 'stat' => 'Hourly', 'label' => 'Rate updates' ],
	[ 'stat' => 'Free',   'label' => 'No signup needed' ],
];
$stats = ! empty( $trust_stats ) ? $trust_stats : $default_trust_stats;
?>
<main id="primary" class="site-main tp-homepage">

	<?php if ( $announcement ) : ?>
	<div class="tp-announcement" role="note" aria-live="polite">
		<div class="tp-container">
			<span class="tp-announcement__icon" aria-hidden="true">&#128308;</span>
			<?php echo esc_html( $announcement ); ?>
		</div>
	</div>
	<?php endif; ?>

	<!-- ═══════════════════════════════════════════════
	     HERO
	══════════════════════════════════════════════════ -->
	<section class="tp-hero" aria-labelledby="tp-hero-heading">
		<div class="tp-container tp-hero__inner">
			<div class="tp-hero__content">
				<p class="tp-eyebrow">Bangladesh remittance comparison</p>
				<h1 id="tp-hero-heading" class="tp-hero__heading">
					<?php echo esc_html( $hero_heading ); ?>
					<span class="tp-hero__heading-sub">Compare Rates &amp; Fees</span>
				</h1>
				<p class="tp-hero__tagline"><?php echo esc_html( $hero_tagline ); ?></p>
				<div class="tp-hero__cta">
					<a class="tp-btn tp-btn--primary" href="#rates">Compare rates now</a>
					<a class="tp-btn tp-btn--ghost" href="#corridors">Browse corridors</a>
				</div>
			</div>
			<div class="tp-hero__badge" aria-hidden="true">
				<div class="tp-hero__badge-inner">
					<span class="tp-hero__badge-flag">&#127987;&#65039;</span>
					<span class="tp-hero__badge-text">Bangladesh</span>
					<span class="tp-hero__badge-sub">&#2547; Bangladeshi Taka</span>
				</div>
			</div>
		</div>
	</section>

	<!-- ═══════════════════════════════════════════════
	     TRUST BAR
	══════════════════════════════════════════════════ -->
	<section class="tp-trust-bar" aria-label="Why use TakaPath">
		<div class="tp-container tp-trust-bar__inner">
			<?php foreach ( $stats as $item ) : ?>
			<div class="tp-trust-bar__item">
				<strong class="tp-trust-bar__stat"><?php echo esc_html( $item['stat'] ); ?></strong>
				<span class="tp-trust-bar__label"><?php echo esc_html( $item['label'] ); ?></span>
			</div>
			<?php endforeach; ?>
		</div>
	</section>

	<!-- ═══════════════════════════════════════════════
	     LIVE RATES WIDGET
	══════════════════════════════════════════════════ -->
	<section id="rates" class="tp-section tp-section--rates" aria-labelledby="tp-rates-heading">
		<div class="tp-container">
			<header class="tp-section__header">
				<h2 id="tp-rates-heading" class="tp-section__title">Live rate comparison</h2>
				<p class="tp-section__intro">Compare what your recipient actually receives in Bangladesh after exchange rates and fees. Updated hourly.</p>
			</header>
			<div class="tp-rates-widget">
				<?php
				echo do_shortcode(
					sprintf(
						'[takapath_rates from="%s" amount="1000" show="8"]',
						esc_attr( $default_currency )
					)
				);
				?>
			</div>
			<p class="tp-disclaimer">Rates are indicative and updated hourly. Always confirm the final rate on the provider's website before sending.</p>
		</div>
	</section>

	<!-- ═══════════════════════════════════════════════
	     CORRIDOR GRID
	══════════════════════════════════════════════════ -->
	<section id="corridors" class="tp-section tp-section--corridors" aria-labelledby="tp-corridors-heading">
		<div class="tp-container">
			<header class="tp-section__header">
				<h2 id="tp-corridors-heading" class="tp-section__title">Send from your country</h2>
				<p class="tp-section__intro">Choose your source country for a dedicated comparison guide with provider reviews, receive options, and live rates.</p>
			</header>
			<?php if ( ! empty( $corridors ) ) : ?>
			<div class="tp-corridor-grid">
				<?php foreach ( $corridors as $corridor_post ) :
					setup_postdata( $corridor_post );
					$flag     = function_exists( 'get_field' ) ? ( get_field( 'flag_emoji', $corridor_post->ID ) ?: '' ) : '';
					$currency = function_exists( 'get_field' ) ? ( get_field( 'from_currency', $corridor_post->ID ) ?: '' ) : '';
					$country  = function_exists( 'get_field' ) ? ( get_field( 'from_country', $corridor_post->ID ) ?: get_the_title( $corridor_post->ID ) ) : get_the_title( $corridor_post->ID );
				?>
				<a class="tp-corridor-card" href="<?php echo esc_url( get_permalink( $corridor_post->ID ) ); ?>" aria-label="Send money from <?php echo esc_attr( $country ); ?> to Bangladesh">
					<?php if ( $flag ) : ?>
					<span class="tp-corridor-card__flag" aria-hidden="true"><?php echo esc_html( $flag ); ?></span>
					<?php endif; ?>
					<span class="tp-corridor-card__country"><?php echo esc_html( $country ); ?></span>
					<?php if ( $currency ) : ?>
					<span class="tp-corridor-card__currency"><?php echo esc_html( $currency ); ?> &rarr; BDT</span>
					<?php endif; ?>
					<span class="tp-corridor-card__arrow" aria-hidden="true">&rarr;</span>
				</a>
				<?php endforeach;
				wp_reset_postdata(); ?>
			</div>
			<?php else : ?>
			<p class="tp-empty">Corridor pages coming soon.</p>
			<?php endif; ?>
		</div>
	</section>

	<!-- ═══════════════════════════════════════════════
	     CONTENT BAND (Trust / editorial)
	══════════════════════════════════════════════════ -->
	<section class="tp-section tp-content-band" aria-labelledby="tp-about-heading">
		<div class="tp-container">
			<div class="tp-content-band__grid">
				<article class="tp-content-band__item">
					<div class="tp-content-band__icon" aria-hidden="true">&#127988;</div>
					<h2 id="tp-about-heading">Built only for Bangladesh</h2>
					<p>TakaPath focuses entirely on sending money to Bangladesh, so every guide, comparison, and provider note is written for this one destination. No generic results &mdash; just the corridors your family uses.</p>
				</article>
				<article class="tp-content-band__item">
					<div class="tp-content-band__icon" aria-hidden="true">&#128242;</div>
					<h2>Bank, bKash &amp; cash pickup</h2>
					<p>Some families need a direct bank transfer, some need their bKash wallet topped up, and others still prefer cash at a local agent. TakaPath makes the differences clear so you can pick the right method for your family.</p>
				</article>
				<article class="tp-content-band__item">
					<div class="tp-content-band__icon" aria-hidden="true">&#128200;</div>
					<h2>Bangladesh hit $33B in remittances</h2>
					<p>Bangladesh received a record $33 billion in remittances in 2025. Formal channels like bank transfers and bKash HomeSend are now faster and cheaper than ever &mdash; we help you find the best one.</p>
				</article>
			</div>
		</div>
	</section>

</main>
<?php get_footer();
