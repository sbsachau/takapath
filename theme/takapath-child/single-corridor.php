<?php
/**
 * TakaPath — Single Corridor Guide template
 * Used for posts of CPT 'corridor'.
 *
 * Layout:
 *   1. Hero       — flag + route label + heading + tagline + volume note
 *   2. Trust bar  — three trust signals
 *   3. Intro text — ACF wysiwyg (optional)
 *   4. Rate comparison widget — [takapath_rates] shortcode
 *   5. Receive methods pills
 *   6. Expert tip callout
 *   7. Section divider
 *   8. FAQ accordion (ACF repeater)
 *   9. Related corridors grid
 *  10. Standard WP editor content (optional editorial body)
 *  11. Footer
 */

declare( strict_types=1 );

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();

	// ── ACF field values ──────────────────────────────────────────────────────
	$from_country    = (string) ( get_field( 'from_country' )    ?: '' );
	$from_currency   = (string) ( get_field( 'from_currency' )   ?: 'GBP' );
	$flag_emoji      = (string) ( get_field( 'flag_emoji' )      ?: '' );
	$default_amount  = (int)    ( get_field( 'default_amount' )  ?: 1000 );
	$route_label     = (string) ( get_field( 'route_label' )     ?: ( $from_country . ' → Bangladesh' ) );
	$volume_note     = (string) ( get_field( 'volume_note' )     ?: '' );
	$receive_methods = (array)  ( get_field( 'receive_methods' ) ?: [] );
	$intro_text      = (string) ( get_field( 'intro_text' )      ?: '' );
	$expert_tip      = (string) ( get_field( 'expert_tip' )      ?: '' );
	$faq_items       = (array)  ( get_field( 'faq_items' )       ?: [] );
	$related_ids     = (array)  ( get_field( 'related_corridors' ) ?: [] );

	// Receive method labels
	$method_labels = [
		'bank_deposit'  => '🏦 Bank deposit',
		'bkash'         => '📱 bKash',
		'nagad'         => '📱 Nagad',
		'rocket'        => '📱 Rocket',
		'cash_pickup'   => '💵 Cash pickup',
		'home_delivery' => '🏠 Home delivery',
	];

?>

<!-- ═══════════════════════════════════════════════════════ 1. HERO ═══ -->
<section class="takapath-hero" aria-label="Corridor hero">
	<div class="takapath-hero__inner">

		<?php if ( $flag_emoji ) : ?>
			<span class="takapath-hero__flag" aria-hidden="true"><?php echo esc_html( $flag_emoji ); ?> 🇧🇩</span>
		<?php endif; ?>

		<?php if ( $volume_note ) : ?>
			<span class="volume-note"><?php echo esc_html( $volume_note ); ?></span>
		<?php endif; ?>

		<h1><?php the_title(); ?></h1>

		<p class="tagline">
			<?php
			printf(
				/* translators: 1: route label e.g. "UK → Bangladesh" */
				esc_html__( 'Compare live exchange rates, fees and transfer speeds for %s. Find the best provider in seconds — bank deposit, bKash or cash pickup.', 'takapath-child' ),
				esc_html( $route_label )
			);
			?>
		</p>

		<a href="#comparison" class="btn btn-accent">
			<?php esc_html_e( 'Compare rates now', 'takapath-child' ); ?>
			<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M12 5v14M5 12l7 7 7-7"/></svg>
		</a>

	</div>
</section>

<!-- ══════════════════════════════════════════════════ 2. TRUST BAR ═══ -->
<div class="trust-bar" role="list" aria-label="Trust signals">
	<div class="trust-bar__item" role="listitem">
		<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
		<?php esc_html_e( 'Rates updated every hour', 'takapath-child' ); ?>
	</div>
	<div class="trust-bar__item" role="listitem">
		<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
		<?php esc_html_e( 'No hidden fees — we show the real cost', 'takapath-child' ); ?>
	</div>
	<div class="trust-bar__item" role="listitem">
		<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
		<?php esc_html_e( 'Trusted by Bangladeshis worldwide', 'takapath-child' ); ?>
	</div>
</div>

<div class="takapath-section">

	<!-- ══════════════════════════════════════════ 3. INTRO TEXT ═══ -->
	<?php if ( $intro_text ) : ?>
		<div class="corridor-intro">
			<?php echo wp_kses_post( $intro_text ); ?>
		</div>
	<?php endif; ?>

	<!-- ════════════════════════════════════ 4. COMPARISON WIDGET ═══ -->
	<div id="comparison" class="corridor-comparison" aria-label="Rate comparison table">
		<h2 class="takapath-section__title">
			<?php
			printf(
				/* translators: 1: source currency e.g. GBP, 2: amount e.g. 1000 */
				esc_html__( 'Best exchange rates: %1$s %2$s → BDT today', 'takapath-child' ),
				esc_html( $from_currency ),
				number_format( $default_amount )
			);
			?>
		</h2>
		<?php
		echo do_shortcode(
			takapath_corridor_shortcode( get_the_ID() )
		);
		?>
	</div>

	<!-- ════════════════════════════════ 5. RECEIVE METHODS PILLS ═══ -->
	<?php if ( ! empty( $receive_methods ) ) : ?>
		<div class="receive-methods" aria-label="Available receive methods">
			<span class="receive-methods__pill" style="font-weight:600; color: var(--color-text);"><?php esc_html_e( 'Receive via:', 'takapath-child' ); ?></span>
			<?php foreach ( $receive_methods as $method ) : ?>
				<span class="receive-methods__pill receive-methods__pill--green">
					<?php echo esc_html( $method_labels[ $method ] ?? $method ); ?>
				</span>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>

	<!-- ═══════════════════════════════════════ 6. EXPERT TIP ═══ -->
	<?php if ( $expert_tip ) : ?>
		<div class="expert-tip" role="note">
			<span class="expert-tip__icon" aria-hidden="true">💡</span>
			<p><?php echo esc_html( $expert_tip ); ?></p>
		</div>
	<?php endif; ?>

</div>

<!-- ═══════════════════════════════════════ SECTION DIVIDER ═══ -->
<hr class="section-divider" aria-hidden="true">

<div class="takapath-section">

	<!-- ═══════════════════════════════════════ 7. FAQ ACCORDION ═══ -->
	<?php if ( ! empty( $faq_items ) ) : ?>
		<section aria-label="Frequently asked questions">
			<h2 class="takapath-section__title"><?php esc_html_e( 'Frequently Asked Questions', 'takapath-child' ); ?></h2>
			<ul class="faq-list" role="list">
				<?php foreach ( $faq_items as $index => $item ) :
					$trigger_id = 'faq-trigger-' . (int) $index;
					$body_id    = 'faq-body-' . (int) $index;
				?>
				<li class="faq-item">
					<button
						class="faq-item__trigger"
						id="<?php echo esc_attr( $trigger_id ); ?>"
						aria-expanded="false"
						aria-controls="<?php echo esc_attr( $body_id ); ?>"
					>
						<span><?php echo esc_html( $item['question'] ); ?></span>
						<svg class="faq-item__chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M6 9l6 6 6-6"/></svg>
					</button>
					<div
						class="faq-item__body"
						id="<?php echo esc_attr( $body_id ); ?>"
						role="region"
						aria-labelledby="<?php echo esc_attr( $trigger_id ); ?>"
					>
						<p><?php echo wp_kses_post( $item['answer'] ); ?></p>
					</div>
				</li>
				<?php endforeach; ?>
			</ul>
		</section>
	<?php endif; ?>

	<!-- ════════════════════════════════ 8. RELATED CORRIDORS ═══ -->
	<?php if ( ! empty( $related_ids ) ) : ?>
		<section aria-label="Also compare" style="margin-top: var(--space-12);">
			<h2 class="takapath-section__title"><?php esc_html_e( 'Also compare', 'takapath-child' ); ?></h2>
			<div class="related-corridors">
				<?php foreach ( $related_ids as $related_id ) :
					$r_flag     = (string) ( get_field( 'flag_emoji', $related_id )   ?: '🌍' );
					$r_label    = (string) ( get_field( 'route_label', $related_id )  ?: get_the_title( $related_id ) );
					$r_currency = (string) ( get_field( 'from_currency', $related_id ) ?: '' );
				?>
				<a
					href="<?php echo esc_url( get_permalink( $related_id ) ); ?>"
					class="related-corridor-card"
					aria-label="<?php echo esc_attr( sprintf( __( 'Compare %s', 'takapath-child' ), $r_label ) ); ?>"
				>
					<span class="related-corridor-card__flag" aria-hidden="true"><?php echo esc_html( $r_flag ); ?></span>
					<span>
						<?php echo esc_html( $r_label ); ?>
						<?php if ( $r_currency ) : ?>
							<span style="display:block; font-size:var(--text-xs); color:var(--color-text-muted); font-weight:400;"><?php echo esc_html( $r_currency ); ?> → BDT</span>
						<?php endif; ?>
					</span>
				</a>
			<?php endforeach; ?>
			</div>
		</section>
	<?php endif; ?>

	<!-- ════════════════════════════════ 9. WP EDITOR CONTENT ═══ -->
	<?php if ( get_the_content() ) : ?>
		<div class="entry-content wp-content" style="margin-top: var(--space-12);">
			<?php the_content(); ?>
		</div>
	<?php endif; ?>

</div><!-- /.takapath-section -->

<?php endwhile; ?>

<!-- ══════════════════════════════ FAQ ACCORDION — INLINE JS ═══ -->
<script>
(function () {
	document.querySelectorAll('.faq-item__trigger').forEach(function (btn) {
		btn.addEventListener('click', function () {
			var expanded = this.getAttribute('aria-expanded') === 'true';
			var bodyId   = this.getAttribute('aria-controls');
			var body     = document.getElementById(bodyId);

			// Close all open items first
			document.querySelectorAll('.faq-item__trigger[aria-expanded="true"]').forEach(function (openBtn) {
				openBtn.setAttribute('aria-expanded', 'false');
				var openBodyId = openBtn.getAttribute('aria-controls');
				var openBody   = document.getElementById(openBodyId);
				if (openBody) openBody.classList.remove('is-open');
			});

			// Toggle current
			if (!expanded) {
				this.setAttribute('aria-expanded', 'true');
				if (body) body.classList.add('is-open');
			}
		});
	});
})();
</script>

<?php get_footer(); ?>
