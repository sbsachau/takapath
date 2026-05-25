<?php
/**
 * Shortcode — renders the rate comparison widget.
 *
 * Consumes the normalised array from API_Client::get_rates() so it never
 * needs to know anything about the upstream TransferSmartly API shape.
 *
 * Usage:
 *   [takapath_rates]                                   → GBP → BDT, £1,000
 *   [takapath_rates from="USD" amount="500"]            → USD → BDT, $500
 *   [takapath_rates from="SAR" show="3" selector="yes"] → top 3 + currency switcher
 */

declare( strict_types=1 );

namespace TakaPath\Rates;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Shortcode {

	/** Supported source currencies with display labels and symbols. */
	private const CURRENCIES = [
		'GBP' => [ 'label' => 'British Pounds',       'symbol' => '£',  'flag' => '🇬🇧' ],
		'USD' => [ 'label' => 'US Dollars',            'symbol' => '$',  'flag' => '🇺🇸' ],
		'EUR' => [ 'label' => 'Euros',                 'symbol' => '€',  'flag' => '🇪🇺' ],
		'SAR' => [ 'label' => 'Saudi Riyals',          'symbol' => 'ر.س', 'flag' => '🇸🇦' ],
		'AED' => [ 'label' => 'UAE Dirhams',           'symbol' => 'د.إ', 'flag' => '🇦🇪' ],
		'MYR' => [ 'label' => 'Malaysian Ringgit',     'symbol' => 'RM', 'flag' => '🇲🇾' ],
		'OMR' => [ 'label' => 'Omani Rials',           'symbol' => 'ر.ع', 'flag' => '🇴🇲' ],
		'KWD' => [ 'label' => 'Kuwaiti Dinars',        'symbol' => 'KD', 'flag' => '🇰🇼' ],
		'QAR' => [ 'label' => 'Qatari Riyals',        'symbol' => 'ر.ق', 'flag' => '🇶🇦' ],
		'SGD' => [ 'label' => 'Singapore Dollars',     'symbol' => 'S$', 'flag' => '🇸🇬' ],
		'CAD' => [ 'label' => 'Canadian Dollars',      'symbol' => 'C$', 'flag' => '🇨🇦' ],
		'AUD' => [ 'label' => 'Australian Dollars',    'symbol' => 'A$', 'flag' => '🇦🇺' ],
		'ITL' => [ 'label' => 'Italian / EU (EUR)',    'symbol' => '€',  'flag' => '🇮🇹' ],
	];

	public static function register(): void {
		add_shortcode( 'takapath_rates', [ self::class, 'render' ] );
		add_action( 'wp_enqueue_scripts', [ self::class, 'enqueue_assets' ] );
	}

	public static function enqueue_assets(): void {
		wp_enqueue_style(
			'takapath-rates',
			TAKAPATH_RATES_URL . 'assets/takapath-rates.css',
			[],
			TAKAPATH_RATES_VERSION
		);
	}

	/**
	 * Render the comparison widget.
	 *
	 * @param array|string $atts  Shortcode attributes.
	 * @return string             HTML output (never echoed directly).
	 */
	public static function render( $atts = [] ): string {
		$atts = shortcode_atts(
			[
				'from'     => 'GBP',  // Source currency ISO 4217.
				'amount'   => '1000', // Send amount.
				'show'     => '10',   // Max providers to display (1–20).
				'selector' => 'no',   // Show currency selector UI: yes|no.
			],
			$atts,
			'takapath_rates'
		);

		$from     = strtoupper( sanitize_text_field( $atts['from'] ) );
		$amount   = max( 1, (int) $atts['amount'] );
		$show     = max( 1, min( 20, (int) $atts['show'] ) );
		$selector = 'yes' === strtolower( $atts['selector'] );

		// Fallback to GBP if an unsupported currency is requested.
		if ( ! isset( self::CURRENCIES[ $from ] ) ) {
			$from = 'GBP';
		}

		$result = API_Client::get_rates( $from, $amount );

		// Hard failure: no live data, no stale cache.
		if ( ! $result['success'] && empty( $result['data'] ) ) {
			return self::render_error(
				__( 'Rate data is temporarily unavailable. Please try again shortly.', 'takapath-rates' )
			);
		}

		$rows    = array_slice( $result['data'], 0, $show );
		$is_stale = 'stale' === $result['source'];
		$currency = self::CURRENCIES[ $from ];

		ob_start();
		?>
		<div class="takapath-widget"
			 role="region"
			 aria-label="<?php printf(
					/* translators: %s: source currency label e.g. "British Pounds" */
					esc_attr__( 'Money transfer rates from %s to Bangladesh', 'takapath-rates' ),
					esc_attr( $currency['label'] )
				); ?>"
			 data-from="<?php echo esc_attr( $from ); ?>"
			 data-amount="<?php echo esc_attr( (string) $amount ); ?>">

			<!-- WIDGET HEADER -->
			<div class="takapath-widget__header">
				<div class="takapath-widget__meta">
					<span class="takapath-widget__flag" aria-hidden="true"><?php echo esc_html( $currency['flag'] ); ?></span>
					<span class="takapath-widget__label">
						<?php
						printf(
							/* translators: 1: amount+symbol e.g. "£1,000", 2: currency label e.g. "British Pounds" */
							esc_html__( 'Sending %1$s%2$s to Bangladesh', 'takapath-rates' ),
							esc_html( $currency['symbol'] ),
							esc_html( number_format( $amount ) )
						);
						?>
					</span>
				</div>
				<span class="takapath-widget__updated">
					<?php esc_html_e( 'Rates updated hourly', 'takapath-rates' ); ?>
				</span>
			</div>

			<?php if ( $is_stale ) : ?>
				<!-- STALE DATA NOTICE -->
				<div class="takapath-widget__stale" role="alert">
					<?php esc_html_e( 'Live rates are temporarily unavailable. Showing last known rates — confirm on the provider\'s site before sending.', 'takapath-rates' ); ?>
				</div>
			<?php endif; ?>

			<?php if ( $selector ) : ?>
				<!-- CURRENCY SELECTOR -->
				<form class="takapath-selector" method="get" aria-label="<?php esc_attr_e( 'Change source currency', 'takapath-rates' ); ?>">
					<label for="takapath-from" class="takapath-selector__label"><?php esc_html_e( 'I\'m sending from:', 'takapath-rates' ); ?></label>
					<select id="takapath-from" name="from" class="takapath-selector__select" onchange="this.form.submit()">
						<?php foreach ( self::CURRENCIES as $code => $meta ) : ?>
							<option value="<?php echo esc_attr( $code ); ?>" <?php selected( $code, $from ); ?>>
								<?php echo esc_html( $meta['flag'] . ' ' . $code . ' — ' . $meta['label'] ); ?>
							</option>
						<?php endforeach; ?>
					</select>
					<noscript><button type="submit"><?php esc_html_e( 'Update', 'takapath-rates' ); ?></button></noscript>
				</form>
			<?php endif; ?>

			<!-- COMPARISON TABLE -->
			<div class="takapath-table-wrap">
				<table class="takapath-table">
					<thead>
						<tr>
							<th scope="col"><?php esc_html_e( 'Provider', 'takapath-rates' ); ?></th>
							<th scope="col"><?php esc_html_e( 'Exchange rate', 'takapath-rates' ); ?></th>
							<th scope="col"><?php esc_html_e( 'Fee', 'takapath-rates' ); ?></th>
							<th scope="col"><?php esc_html_e( 'Recipient gets', 'takapath-rates' ); ?></th>
							<th scope="col"><?php esc_html_e( 'Speed', 'takapath-rates' ); ?></th>
							<th scope="col"><span class="sr-only"><?php esc_html_e( 'Transfer link', 'takapath-rates' ); ?></span></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ( $rows as $i => $row ) :
							$is_best = 0 === $i;
							$fee_fmt = $row['fee'] > 0
								? esc_html( $currency['symbol'] . number_format( $row['fee'], 2 ) )
								: '<span class="takapath-free">' . esc_html__( 'Free', 'takapath-rates' ) . '</span>';
							$recv_fmt = '৳ ' . number_format( $row['recipient_gets'], 2 );
						?>
							<tr class="takapath-table__row<?php echo $is_best ? ' takapath-table__row--best' : ''; ?>">
								<td class="takapath-table__provider">
									<?php if ( $is_best ) : ?>
										<span class="takapath-badge takapath-badge--best"><?php esc_html_e( 'Best rate', 'takapath-rates' ); ?></span>
									<?php endif; ?>
									<?php if ( $row['provider_logo'] ) : ?>
										<img src="<?php echo esc_url( $row['provider_logo'] ); ?>"
											 alt="<?php echo esc_attr( $row['provider'] ); ?>"
											 width="80" height="24"
											 loading="lazy"
											 class="takapath-table__logo">
									<?php else : ?>
										<span class="takapath-table__name"><?php echo esc_html( $row['provider'] ); ?></span>
									<?php endif; ?>
								</td>
								<td class="takapath-table__rate" data-label="<?php esc_attr_e( 'Exchange rate', 'takapath-rates' ); ?>">
									1 <?php echo esc_html( $from ); ?> = <?php echo esc_html( number_format( $row['exchange_rate'], 4 ) ); ?> BDT
								</td>
								<td data-label="<?php esc_attr_e( 'Fee', 'takapath-rates' ); ?>">
									<?php echo $fee_fmt; // phpcs:ignore WordPress.Security.EscapeOutput ?>
								</td>
								<td class="takapath-table__amount" data-label="<?php esc_attr_e( 'Recipient gets', 'takapath-rates' ); ?>">
									<?php echo esc_html( $recv_fmt ); ?>
								</td>
								<td data-label="<?php esc_attr_e( 'Speed', 'takapath-rates' ); ?>">
									<?php echo esc_html( $row['transfer_speed'] ?: '—' ); ?>
								</td>
								<td class="takapath-table__action">
									<?php if ( $row['transfer_url'] ) : ?>
										<a href="<?php echo esc_url( $row['transfer_url'] ); ?>"
											class="takapath-btn"
											target="_blank"
											rel="noopener noreferrer sponsored"
											aria-label="<?php printf( esc_attr__( 'Send money via %s (opens in new tab)', 'takapath-rates' ), esc_attr( $row['provider'] ) ); ?>">
											<?php esc_html_e( 'Send now', 'takapath-rates' ); ?>
										</a>
									<?php endif; ?>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>

			<!-- RECEIVE METHODS CHIPS (from first provider as reference) -->
			<?php
			$all_methods = [];
			foreach ( $rows as $row ) {
				foreach ( $row['receive_methods'] as $m ) {
					$all_methods[ $m ] = true;
				}
			}
			if ( ! empty( $all_methods ) ) :
			?>
				<div class="takapath-widget__methods" aria-label="<?php esc_attr_e( 'Available receive methods', 'takapath-rates' ); ?>">
					<span class="takapath-widget__methods-label"><?php esc_html_e( 'Receive options:', 'takapath-rates' ); ?></span>
					<?php foreach ( array_keys( $all_methods ) as $method ) : ?>
						<span class="takapath-pill takapath-pill--soft"><?php echo esc_html( ucfirst( $method ) ); ?></span>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<!-- DISCLAIMER -->
			<p class="takapath-widget__disclaimer">
				<?php esc_html_e( 'Rates are indicative and may differ slightly from the final rate. Always confirm on the provider\'s website before sending.', 'takapath-rates' ); ?>
			</p>

		</div>
		<?php
		return ob_get_clean();
	}

	// -------------------------------------------------------------------------
	// Private helpers
	// -------------------------------------------------------------------------

	private static function render_error( string $message ): string {
		return '<div class="takapath-widget takapath-widget--error" role="alert">' .
			'<p class="takapath-error">' . esc_html( $message ) . '</p>' .
			'</div>';
	}
}
