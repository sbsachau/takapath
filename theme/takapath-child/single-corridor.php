<?php
/**
 * Single template for the Corridor Guide CPT.
 * WordPress resolves this automatically via the single-{post_type}.php hierarchy.
 */
declare( strict_types=1 );
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
while ( have_posts() ) : the_post();
	$from_country    = function_exists( 'get_field' ) ? ( get_field( 'from_country' ) ?: '' ) : '';
	$from_currency   = function_exists( 'get_field' ) ? ( get_field( 'from_currency' ) ?: 'GBP' ) : 'GBP';
	$flag_emoji      = function_exists( 'get_field' ) ? ( get_field( 'flag_emoji' ) ?: '' ) : '';
	$default_amount  = function_exists( 'get_field' ) ? ( (int) get_field( 'default_amount' ) ?: 1000 ) : 1000;
	$corridor_intro  = function_exists( 'get_field' ) ? ( get_field( 'corridor_intro' ) ?: '' ) : '';
	$hero_stat       = function_exists( 'get_field' ) ? ( get_field( 'hero_stat' ) ?: '' ) : '';
	$receive_methods = function_exists( 'get_field' ) ? ( get_field( 'receive_methods' ) ?: [] ) : [];
	$seo_h1          = function_exists( 'get_field' ) ? ( get_field( 'seo_h1' ) ?: '' ) : '';
	$provider_cards  = function_exists( 'get_field' ) ? ( get_field( 'provider_overrides' ) ?: [] ) : [];
	$related         = function_exists( 'get_field' ) ? ( get_field( 'related_corridors' ) ?: [] ) : [];
	$headline        = $seo_h1 ?: get_the_title();
	?>
	<main id="primary" class="site-main">

		<!-- HERO -->
		<section class="takapath-hero takapath-hero--corridor">
			<div class="takapath-section">
				<p class="takapath-eyebrow"><?php echo esc_html( trim( $flag_emoji . ' ' . $from_country . ' to Bangladesh' ) ); ?></p>
				<h1><?php echo esc_html( $headline ); ?></h1>
				<?php if ( $corridor_intro ) : ?>
					<p class="tagline"><?php echo esc_html( $corridor_intro ); ?></p>
				<?php endif; ?>
				<div class="takapath-hero__meta">
					<?php if ( $hero_stat ) : ?>
						<span class="takapath-pill"><?php echo esc_html( $hero_stat ); ?></span>
					<?php endif; ?>
					<span class="takapath-pill"><?php echo esc_html( $from_currency ); ?> → BDT</span>
					<?php foreach ( (array) $receive_methods as $method ) : ?>
						<span class="takapath-pill takapath-pill--soft"><?php echo esc_html( ucfirst( $method ) ); ?></span>
					<?php endforeach; ?>
				</div>
			</div>
		</section>

		<!-- EDITORIAL PROVIDER HIGHLIGHTS (ACF repeater) -->
		<?php if ( ! empty( $provider_cards ) ) : ?>
			<section class="takapath-section">
				<h2 class="takapath-section__title">Top picks for this corridor</h2>
				<div class="takapath-highlights">
					<?php foreach ( $provider_cards as $card ) : ?>
						<article class="takapath-highlight-card">
							<?php if ( ! empty( $card['badge'] ) ) : ?>
								<span class="takapath-badge"><?php echo esc_html( $card['badge'] ); ?></span>
							<?php endif; ?>
							<h3 class="takapath-highlight-card__title"><?php echo esc_html( $card['provider_name'] ?? '' ); ?></h3>
							<?php if ( ! empty( $card['note'] ) ) : ?>
								<p><?php echo esc_html( $card['note'] ); ?></p>
							<?php endif; ?>
							<?php if ( ! empty( $card['affiliate_url'] ) ) : ?>
								<a class="takapath-btn-inline" href="<?php echo esc_url( $card['affiliate_url'] ); ?>" target="_blank" rel="noopener noreferrer sponsored">Visit provider</a>
							<?php endif; ?>
						</article>
					<?php endforeach; ?>
				</div>
			</section>
		<?php endif; ?>

		<!-- LIVE RATES SHORTCODE -->
		<section class="takapath-section">
			<h2 class="takapath-section__title">Live rates today</h2>
			<?php echo do_shortcode( sprintf( '[takapath_rates from="%s" amount="%d"]', esc_attr( $from_currency ), (int) $default_amount ) ); ?>
		</section>

		<!-- HOW IT WORKS -->
		<section class="takapath-section takapath-how">
			<h2 class="takapath-section__title">How it works</h2>
			<div class="takapath-steps">
				<article class="takapath-step">
					<span class="takapath-step__num">1</span>
					<h3>Compare providers</h3>
					<p>See the latest exchange rates, fees, and transfer speed for this corridor in one place.</p>
				</article>
				<article class="takapath-step">
					<span class="takapath-step__num">2</span>
					<h3>Choose how money arrives</h3>
					<p>Bank account, bKash wallet, or cash pickup — pick the method your recipient needs.</p>
				</article>
				<article class="takapath-step">
					<span class="takapath-step__num">3</span>
					<h3>Complete safely</h3>
					<p>Click through to the provider, verify the final rate, and complete the transfer.</p>
				</article>
			</div>
		</section>

		<!-- CORRIDOR EDITORIAL GUIDE -->
		<section class="takapath-section">
			<h2 class="takapath-section__title">Corridor guide</h2>
			<div class="takapath-prose"><?php the_content(); ?></div>
		</section>

		<!-- FAQ BRIDGE PARTIAL -->
		<?php include get_stylesheet_directory() . '/template-parts/corridor-faq-bridge.php'; ?>

		<!-- RELATED CORRIDORS -->
		<?php if ( ! empty( $related ) ) : ?>
			<section class="takapath-section">
				<h2 class="takapath-section__title">Related corridors</h2>
				<div class="corridor-grid">
					<?php foreach ( $related as $post ) :
						setup_postdata( $post );
						$rel_flag = function_exists( 'get_field' ) ? ( get_field( 'flag_emoji', $post->ID ) ?: '🌍' ) : '🌍';
						$rel_cur  = function_exists( 'get_field' ) ? ( get_field( 'from_currency', $post->ID ) ?: '' ) : '';
					?>
						<a class="corridor-card" href="<?php the_permalink(); ?>">
							<span class="corridor-card__flag"><?php echo esc_html( $rel_flag ); ?></span>
							<span class="corridor-card__title"><?php the_title(); ?></span>
							<span class="corridor-card__meta"><?php echo esc_html( $rel_cur ); ?> → BDT</span>
						</a>
					<?php endforeach;
					wp_reset_postdata(); ?>
				</div>
			</section>
		<?php endif; ?>

	</main>
<?php endwhile;
get_footer();
