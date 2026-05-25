<?php
/**
 * Template partial: FAQ section for corridor pages.
 *
 * Renders ACF FAQ repeater as an accessible accordion.
 * The FAQPage JSON-LD is handled separately by class-schema.php.
 *
 * Usage (in corridor page template or Elementor shortcode area):
 *   get_template_part() approach, or include directly:
 *   include TAKAPATH_RATES_DIR . 'templates/corridor-faq.php';
 *
 * Requires: get_field('faqs') to return a non-empty array.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'get_field' ) ) {
	return;
}

$faqs = get_field( 'faqs' );
if ( empty( $faqs ) || ! is_array( $faqs ) ) {
	return;
}
?>
<section class="takapath-faq" aria-labelledby="faq-heading">
	<div class="takapath-section">
		<h2 id="faq-heading" class="takapath-section__title">Frequently Asked Questions</h2>

		<dl class="takapath-faq__list">
			<?php foreach ( $faqs as $index => $faq ) : ?>
				<?php if ( empty( $faq['question'] ) || empty( $faq['answer'] ) ) continue; ?>
				<div class="takapath-faq__item" id="faq-<?php echo esc_attr( $index + 1 ); ?>">
					<dt>
						<button
							class="takapath-faq__question"
							aria-expanded="false"
							aria-controls="faq-answer-<?php echo esc_attr( $index + 1 ); ?>"
						>
							<?php echo esc_html( $faq['question'] ); ?>
							<span class="takapath-faq__icon" aria-hidden="true">
								<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
									<polyline points="6 9 12 15 18 9"/>
								</svg>
							</span>
						</button>
					</dt>
					<dd
						id="faq-answer-<?php echo esc_attr( $index + 1 ); ?>"
						class="takapath-faq__answer"
						hidden
					>
						<p><?php echo wp_kses_post( $faq['answer'] ); ?></p>
					</dd>
				</div>
			<?php endforeach; ?>
		</dl>
	</div>
</section>

<script>
(function () {
	document.querySelectorAll('.takapath-faq__question').forEach(function (btn) {
		btn.addEventListener('click', function () {
			var expanded = this.getAttribute('aria-expanded') === 'true';
			var answerId = this.getAttribute('aria-controls');
			var answer   = document.getElementById(answerId);
			this.setAttribute('aria-expanded', String(!expanded));
			if (expanded) {
				answer.hidden = true;
			} else {
				answer.hidden = false;
			}
		});
	});
})();
</script>

<style>
.takapath-faq { background: var(--color-surface-offset, #f3f0ec); }
.takapath-faq__list { list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 0; }
.takapath-faq__item { border-bottom: 1px solid var(--color-border, #dcd9d5); }
.takapath-faq__item:first-child { border-top: 1px solid var(--color-border, #dcd9d5); }
.takapath-faq__question {
	width: 100%; text-align: left; padding: 1rem 0;
	background: none; border: none; cursor: pointer;
	font-family: var(--font-body, sans-serif);
	font-size: var(--text-base, 1rem);
	font-weight: 600;
	color: var(--color-text, #28251d);
	display: flex; justify-content: space-between; align-items: center; gap: 1rem;
	transition: color 160ms ease;
}
.takapath-faq__question:hover { color: var(--color-primary, #01696f); }
.takapath-faq__question[aria-expanded="true"] .takapath-faq__icon { transform: rotate(180deg); }
.takapath-faq__icon { flex-shrink: 0; color: var(--color-text-muted, #7a7974); transition: transform 200ms ease; }
.takapath-faq__answer { padding: 0 0 1rem; }
.takapath-faq__answer p { color: var(--color-text-muted, #7a7974); font-size: var(--text-base, 1rem); line-height: 1.7; max-width: 70ch; }
</style>
