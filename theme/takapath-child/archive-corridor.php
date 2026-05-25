<?php
/**
 * TakaPath — Archive template for 'corridor' CPT
 * URL: /send-money-to-bangladesh/
 *
 * Sections:
 *   1. Hero          — archive heading + tagline
 *   2. Trust bar     — three signals
 *   3. Breadcrumb    — Home > Send Money to Bangladesh
 *   4. Filter bar    — client-side JS currency/region filter
 *   5. Corridor grid — CPT cards with data attributes for filtering
 *   6. Empty state   — shown when JS filter returns 0 results
 *   7. Pagination    — standard WP posts_nav
 */

declare( strict_types=1 );

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

// Build all corridor data upfront for filter tags
$all_corridors = [];
if ( have_posts() ) {
	while ( have_posts() ) :
		the_post();
		$all_corridors[] = [
			'id'       => get_the_ID(),
			'flag'     => (string) ( get_field( 'flag_emoji' )    ?: '🌍' ),
			'label'    => (string) ( get_field( 'route_label' )   ?: get_the_title() ),
			'currency' => (string) ( get_field( 'from_currency' ) ?: '' ),
			'region'   => (string) ( get_field( 'region' )        ?: 'other' ),
			'url'      => get_permalink(),
		];
	endwhile;
	rewind_posts();
}

// Collect unique regions for filter tabs
$regions = array_unique( array_column( $all_corridors, 'region' ) );
sort( $regions );
?>

<!-- ══════════════════════════════════════════════ 1. HERO ═══ -->
<section class="takapath-hero" aria-label="All corridors hero">
	<div class="takapath-hero__inner">
		<span class="takapath-hero__flag" aria-hidden="true">🌍 🇧🇩</span>
		<h1><?php esc_html_e( 'Send Money to Bangladesh — Compare Every Corridor', 'takapath-child' ); ?></h1>
		<p class="tagline">
			<?php esc_html_e( 'Pick your country below to compare live exchange rates, fees and transfer speeds. Bank deposit, bKash, Nagad and cash pickup.', 'takapath-child' ); ?>
		</p>
	</div>
</section>

<!-- ══════════════════════════════════════════════ 2. TRUST BAR ═══ -->
<div class="trust-bar" role="list" aria-label="Trust signals">
	<div class="trust-bar__item" role="listitem">
		<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
		<?php esc_html_e( 'Rates updated every hour', 'takapath-child' ); ?>
	</div>
	<div class="trust-bar__item" role="listitem">
		<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
		<?php esc_html_e( 'No affiliate bias — all providers compared equally', 'takapath-child' ); ?>
	</div>
	<div class="trust-bar__item" role="listitem">
		<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
		<?php esc_html_e( '$33 billion sent to Bangladesh in 2025', 'takapath-child' ); ?>
	</div>
</div>

<div class="takapath-section">

	<!-- ══════════════════════════════════════════════ 3. BREADCRUMB ═══ -->
	<nav class="archive-breadcrumb" aria-label="Breadcrumb">
		<ol class="breadcrumb-list">
			<li>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
					<?php esc_html_e( 'Home', 'takapath-child' ); ?>
				</a>
			</li>
			<li aria-hidden="true" class="breadcrumb-sep">
				<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M9 18l6-6-6-6"/></svg>
			</li>
			<li aria-current="page">
				<?php esc_html_e( 'Send Money to Bangladesh', 'takapath-child' ); ?>
			</li>
		</ol>
	</nav>

	<!-- ══════════════════════════════════════════════ 4. FILTER BAR ═══ -->
	<?php if ( count( $all_corridors ) > 4 ) : ?>
	<div class="archive-filter" role="group" aria-label="Filter corridors by region">
		<button class="filter-btn is-active" data-filter="all">
			<?php esc_html_e( 'All countries', 'takapath-child' ); ?>
			<span class="filter-btn__count"><?php echo count( $all_corridors ); ?></span>
		</button>
		<?php
		$region_labels = [
			'middle_east' => __( 'Middle East', 'takapath-child' ),
			'europe'      => __( 'Europe',      'takapath-child' ),
			'asia'        => __( 'Asia',        'takapath-child' ),
			'americas'    => __( 'Americas',    'takapath-child' ),
			'other'       => __( 'Other',       'takapath-child' ),
		];
		foreach ( $regions as $region ) :
			if ( ! $region ) continue;
			$count = count( array_filter( $all_corridors, fn( $c ) => $c['region'] === $region ) );
		?>
		<button class="filter-btn" data-filter="<?php echo esc_attr( $region ); ?>">
			<?php echo esc_html( $region_labels[ $region ] ?? ucfirst( str_replace( '_', ' ', $region ) ) ); ?>
			<span class="filter-btn__count"><?php echo (int) $count; ?></span>
		</button>
		<?php endforeach; ?>
	</div>
	<?php endif; ?>

	<h2 class="takapath-section__title"><?php esc_html_e( 'Choose your country', 'takapath-child' ); ?></h2>

	<!-- ══════════════════════════════════════════════ 5. CORRIDOR GRID ═══ -->
	<?php if ( ! empty( $all_corridors ) ) : ?>

		<div class="corridor-grid" role="list" id="corridor-grid">
			<?php foreach ( $all_corridors as $c ) : ?>
			<a
				href="<?php echo esc_url( $c['url'] ); ?>"
				class="corridor-card"
				role="listitem"
				data-region="<?php echo esc_attr( $c['region'] ); ?>"
				data-currency="<?php echo esc_attr( $c['currency'] ); ?>"
				aria-label="<?php echo esc_attr( sprintf( __( 'Send money from %s', 'takapath-child' ), $c['label'] ) ); ?>"
			>
				<span class="corridor-card__flag" aria-hidden="true"><?php echo esc_html( $c['flag'] ); ?></span>
				<h3 class="corridor-card__title"><?php echo esc_html( $c['label'] ); ?></h3>
				<?php if ( $c['currency'] ) : ?>
					<p class="corridor-card__meta"><?php echo esc_html( $c['currency'] ); ?> → BDT</p>
				<?php endif; ?>
			</a>
			<?php endforeach; ?>
		</div>

		<!-- ══════════════════════════════════════════════ 6. EMPTY STATE (JS-driven) ═══ -->
		<div class="archive-empty" id="archive-empty" hidden aria-live="polite">
			<svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
			<h3><?php esc_html_e( 'No corridors match that filter', 'takapath-child' ); ?></h3>
			<p><?php esc_html_e( 'Try "All countries" or check back soon — we are adding more routes.', 'takapath-child' ); ?></p>
			<button class="btn btn-outline" id="reset-filter">
				<?php esc_html_e( 'Show all countries', 'takapath-child' ); ?>
			</button>
		</div>

		<!-- ══════════════════════════════════════════════ 7. PAGINATION ═══ -->
		<div class="archive-pagination">
			<?php the_posts_pagination( [
				'mid_size'           => 2,
				'prev_text'          => __( '&larr; Previous', 'takapath-child' ),
				'next_text'          => __( 'Next &rarr;',     'takapath-child' ),
				'screen_reader_text' => __( 'Corridors navigation', 'takapath-child' ),
			] ); ?>
		</div>

	<?php else : ?>

		<!-- Static empty state (no CPT posts at all) -->
		<div class="archive-empty">
			<svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
			<h3><?php esc_html_e( 'Corridor guides coming soon', 'takapath-child' ); ?></h3>
			<p><?php esc_html_e( 'We are building out corridor-specific comparison pages. In the meantime, use the homepage to compare rates.', 'takapath-child' ); ?></p>
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-primary">
				<?php esc_html_e( 'Compare rates now', 'takapath-child' ); ?>
			</a>
		</div>

	<?php endif; ?>

</div><!-- /.takapath-section -->

<!-- FILTER JS — vanilla, no dependencies -->
<script>
(function () {
	var grid   = document.getElementById('corridor-grid');
	var empty  = document.getElementById('archive-empty');
	var reset  = document.getElementById('reset-filter');
	var btns   = document.querySelectorAll('.filter-btn');
	if (!grid) return;

	function applyFilter(filter) {
		var cards   = grid.querySelectorAll('.corridor-card');
		var visible = 0;

		cards.forEach(function (card) {
			var match = (filter === 'all') || (card.dataset.region === filter);
			card.style.display = match ? '' : 'none';
			if (match) visible++;
		});

		if (empty) {
			empty.hidden = visible > 0;
		}
		btns.forEach(function (b) {
			b.classList.toggle('is-active', b.dataset.filter === filter);
			b.setAttribute('aria-pressed', String(b.dataset.filter === filter));
		});
	}

	btns.forEach(function (btn) {
		btn.addEventListener('click', function () {
			applyFilter(this.dataset.filter);
		});
	});

	if (reset) {
		reset.addEventListener('click', function () {
			applyFilter('all');
		});
	}
})();
</script>

<?php get_footer(); ?>
