<?php
/**
 * Homepage template for TakaPath.
 * WordPress gives front-page.php highest priority when a static front page is set.
 */
declare( strict_types=1 );
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();

$hero_heading     = function_exists( 'get_field' ) ? ( get_field( 'hero_heading' ) ?: 'Best Way to Send Money to Bangladesh — Compare Rates & Fees' ) : 'Best Way to Send Money to Bangladesh — Compare Rates & Fees';
$hero_tagline     = function_exists( 'get_field' ) ? ( get_field( 'hero_tagline' ) ?: 'Trusted comparison for Bangladeshis worldwide. Updated rates from real providers — bank deposit, bKash, and cash pickup.' ) : 'Trusted comparison for Bangladeshis worldwide. Updated rates from real providers — bank deposit, bKash, and cash pickup.';
$default_currency = function_exists( 'get_field' ) ? ( get_field( 'default_currency' ) ?: 'GBP' ) : 'GBP';
$trust_stats      = function_exists( 'get_field' ) ? ( get_field( 'trust_stats' ) ?: [] ) : [];
$announcement     = function_exists( 'get_field' ) ? ( get_field( 'announcement' ) ?: '' ) : '';

$corridors = get_posts( [
	'post_type'      => 'corridor',
	'posts_per_page' => 8,
	'orderby'        => 'menu_order',
	'order'          => 'ASC',
] );
?>
<main id="primary" class="site-main">

	<!-- ANNOUNCEMENT BAR -->
	<?php if ( $announcement ) : ?>
		<div class="takapath-announcement" role="banner">
			<div class="takapath-section"><p><?php echo esc_html( $announcement ); ?></p></div>
		</div>
	<?php endif; ?>

	<!-- HERO -->
	<section class="takapath-hero">
		<div class="takapath-section">
			<p class="takapath-eyebrow">Bangladesh remittance comparison</p>
			<h1><?php echo esc_html( $hero_heading ); ?></h1>
			<p class="tagline"><?php echo esc_html( $hero_tagline ); ?></p>
			<div class="takapath-hero__cta">
				<a class="takapath-btn-primary" href="#rates">Compare rates now</a>
				<a class="takapath-btn-secondary" href="#corridors">Browse corridors</a>
			</div>
		</div>
	</section>

	<!-- TRUST BAR -->
	<section class="trust-bar" aria-label="Trust signals">
		<?php if ( ! empty( $trust_stats ) ) : ?>
			<?php foreach ( $trust_stats as $item ) : ?>
				<div class="trust-bar__item">
					<strong><?php echo esc_html( $item['stat'] ?? '' ); ?></strong>
					<span><?php echo esc_html( $item['label'] ?? '' ); ?></span>
				</div>
			<?php endforeach; ?>
		<?php else : ?>
			<div class="trust-bar__item"><strong>10+</strong> <span>Providers compared</span></div>
			<div class="trust-bar__item"><strong>BDT</strong> <span>Bank, bKash &amp; cash pickup</span></div>
			<div class="trust-bar__item"><strong>Hourly</strong> <span>Rate updates</span></div>
			<div class="trust-bar__item"><strong>Free</strong> <span>No signup needed</span></div>
		<?php endif; ?>
	</section>

	<!-- LIVE COMPARISON WIDGET -->
	<section id="rates" class="takapath-section">
		<h2 class="takapath-section__title">Live comparison</h2>
		<p class="takapath-section__intro">Compare what your recipient actually gets in Bangladesh after exchange rates and fees.</p>
		<?php echo do_shortcode( sprintf( '[takapath_rates from="%s" amount="1000" show="8"]', esc_attr( $default_currency ) ) ); ?>
	</section>

	<!-- POPULAR CORRIDORS GRID -->
	<section id="corridors" class="takapath-section">
		<h2 class="takapath-section__title">Popular corridors</h2>
		<p class="takapath-section__intro">Choose your source country for a detailed guide with provider reviews, receive options, and live rates.</p>
		<div class="corridor-grid">
			<?php foreach ( $corridors as $post ) :
				setup_postdata( $post );
				$flag     = function_exists( 'get_field' ) ? ( get_field( 'flag_emoji', $post->ID ) ?: '🌍' ) : '🌍';
				$currency = function_exists( 'get_field' ) ? ( get_field( 'from_currency', $post->ID ) ?: '' ) : '';
			?>
				<a class="corridor-card" href="<?php the_permalink(); ?>">
					<span class="corridor-card__flag" aria-hidden="true"><?php echo esc_html( $flag ); ?></span>
					<span class="corridor-card__title"><?php the_title(); ?></span>
					<span class="corridor-card__meta"><?php echo esc_html( $currency ); ?> → BDT</span>
				</a>
			<?php endforeach;
			wp_reset_postdata(); ?>
		</div>
	</section>

	<!-- EDITORIAL CONTENT BAND -->
	<section class="takapath-section takapath-content-band">
		<div class="takapath-content-band__grid">
			<article>
				<h2>Built for Bangladeshis abroad</h2>
				<p>TakaPath focuses only on sending money to Bangladesh, so every guide, comparison, and provider note is written for this one destination. No generic results — just the corridors your family uses.</p>
			</article>
			<article>
				<h2>Bank, bKash, and cash pickup</h2>
				<p>Some families need a bank transfer, some need their bKash wallet topped up, and some still prefer cash collection at a local agent. TakaPath makes those differences clear so you can choose the right method.</p>
			</article>
		</div>
	</section>

</main>
<?php get_footer();
