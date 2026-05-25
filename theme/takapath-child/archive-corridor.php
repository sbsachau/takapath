<?php
/**
 * TakaPath — Archive template for 'corridor' CPT
 * Lists all corridor guide pages as a responsive card grid.
 * URL: /send-money-to-bangladesh/
 */

declare( strict_types=1 );

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<!-- HERO -->
<section class="takapath-hero" aria-label="All corridors hero">
	<div class="takapath-hero__inner">
		<span class="takapath-hero__flag" aria-hidden="true">🌍 🇧🇩</span>
		<h1><?php esc_html_e( 'Send Money to Bangladesh — Compare Every Corridor', 'takapath-child' ); ?></h1>
		<p class="tagline"><?php esc_html_e( 'Pick your country below to compare live exchange rates, fees and transfer speeds. Bank deposit, bKash, Nagad and cash pickup.', 'takapath-child' ); ?></p>
	</div>
</section>

<!-- TRUST BAR -->
<div class="trust-bar">
	<div class="trust-bar__item">
		<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
		<?php esc_html_e( 'Rates updated every hour', 'takapath-child' ); ?>
	</div>
	<div class="trust-bar__item">
		<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
		<?php esc_html_e( 'No affiliate bias — all providers compared equally', 'takapath-child' ); ?>
	</div>
	<div class="trust-bar__item">
		<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
		<?php esc_html_e( '$33 billion sent to Bangladesh in 2025', 'takapath-child' ); ?>
	</div>
</div>

<div class="takapath-section">

	<h2 class="takapath-section__title"><?php esc_html_e( 'Choose your country', 'takapath-child' ); ?></h2>

	<?php if ( have_posts() ) : ?>

		<div class="corridor-grid" role="list">
			<?php while ( have_posts() ) : the_post(); ?>

				<?php
				$flag     = (string) ( get_field( 'flag_emoji' )    ?: '🌍' );
				$label    = (string) ( get_field( 'route_label' )   ?: get_the_title() );
				$currency = (string) ( get_field( 'from_currency' ) ?: '' );
				?>

				<a
					href="<?php the_permalink(); ?>"
					class="corridor-card"
					role="listitem"
					aria-label="<?php echo esc_attr( sprintf( __( 'Send money from %s', 'takapath-child' ), $label ) ); ?>"
				>
					<span class="corridor-card__flag" aria-hidden="true"><?php echo esc_html( $flag ); ?></span>
					<h3 class="corridor-card__title"><?php echo esc_html( $label ); ?></h3>
					<?php if ( $currency ) : ?>
						<p class="corridor-card__meta"><?php echo esc_html( $currency ); ?> → BDT</p>
					<?php endif; ?>
				</a>

			<?php endwhile; ?>
		</div>

		<?php the_posts_pagination(); ?>

	<?php else : ?>
		<p><?php esc_html_e( 'No corridors found. Check back soon — we are adding more routes.', 'takapath-child' ); ?></p>
	<?php endif; ?>

</div>

<?php get_footer(); ?>
