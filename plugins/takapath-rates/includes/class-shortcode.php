<?php
/**
 * Shortcode — renders the rate comparison table.
 *
 * Usage:
 *   [takapath_rates]                         → GBP → BDT, amount 1000
 *   [takapath_rates from="USD" amount="500"]  → USD → BDT, amount 500
 *   [takapath_rates from="SAR" show="3"]      → SAR → BDT, top 3 providers
 */

declare( strict_types=1 );

namespace TakaPath\Rates;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Shortcode {

	public static function register(): void {
		add_shortcode( 'takapath_rates', [ self::class, 'render' ] );
		add_action( 'wp_enqueue_scripts', [ self::class, 'enqueue_styles' ] );
	}

	public static function enqueue_styles(): void {
		wp_enqueue_style(
			'takapath-rates',
			TAKAPATH_RATES_URL . 'assets/takapath-rates.css',
			[],
			TAKAPATH_RATES_VERSION
		);
	}

	/**
	 * Render the comparison table.
	 *
	 * @param array $atts Shortcode attributes.
	 * @return string HTML output.
	 */
	public static function render( array $atts = [] ): string {
		$atts = shortcode_atts(
			[
				'from'   => 'GBP',
				'amount' => '1000',
				'show'   => '10', // max providers to display
			],
			$atts,
			'takapath_rates'
		);

		$from   = strtoupper( sanitize_text_field( $atts['from'] ) );
		$amount = max( 1.0, (float) $atts['amount'] );
		$show   = max( 1, min( 20, (int) $atts['show'] ) );

		$data = API_Client::get_rates( $from, $amount );

		if ( is_wp_error( $data ) ) {
			return '<p class="takapath-error">' .
				esc_html__( 'Rate data is temporarily unavailable. Please try again shortly.', 'takapath-rates' ) .
				'</p>';
		}

		$providers = $data['providers'] ?? $data['results'] ?? [];
		if ( empty( $providers ) ) {
			return '<p class="takapath-error">' .
				esc_html__( 'No provider data available for this corridor.', 'takapath-rates' ) .
				'</p>';
		}

		// Sort by recipient amount descending (best rate first)
		usort( $providers, static function ( $a, $b ) {
			return ( $b['recipientAmount'] ?? $b['amount'] ?? 0 ) <=> ( $a['recipientAmount'] ?? $a['amount'] ?? 0 );
		} );

		$providers = array_slice( $providers, 0, $show );

		$currency_labels = [
			'GBP' => 'British Pounds',
			'USD' => 'US Dollars',
			'EUR' => 'Euros',
			'SAR' => 'Saudi Riyals',
			'AED' => 'UAE Dirhams',
			'MYR' => 'Malaysian Ringgit',
			'OMR' => 'Omani Rials',
			'KWD' => 'Kuwaiti Dinars',
			'CAD' => 'Canadian Dollars',
			'AUD' => 'Australian Dollars',
		];
		$from_label = $currency_labels[ $from ] ?? $from;

		ob_start();
		?>
		<div class="takapath-widget" role="region" aria-label="<?php printf( esc_attr__( 'Money transfer rates from %s to Bangladesh', 'takapath-rates' ), esc_attr( $from_label ) ); ?>">
			<div class="takapath-widget__header">
				<span class="takapath-widget__label">
					<?php
					printf(
						/* translators: 1: formatted amount + currency label, e.g. "£1,000 British Pounds" */
						esc_html__( 'Sending %s to Bangladesh', 'takapath-rates' ),
						esc_html( number_format( $amount ) . ' ' . $from_label )
					);
					?>
				</span>
				<span class="takapath-widget__updated">
					<?php esc_html_e( 'Rates updated hourly', 'takapath-rates' ); ?>
				</span>
			</div>

			<table class="takapath-table">
				<thead>
					<tr>
						<th scope="col"><?php esc_html_e( 'Provider', 'takapath-rates' ); ?></th>
						<th scope="col"><?php esc_html_e( 'You Send', 'takapath-rates' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Recipient Gets (BDT)', 'takapath-rates' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Fee', 'takapath-rates' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Speed', 'takapath-rates' ); ?></th>
						<th scope="col"><span class="sr-only"><?php esc_html_e( 'Action', 'takapath-rates' ); ?></span></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ( $providers as $index => $provider ) : ?>
						<?php
						$name       = esc_html( $provider['provider'] ?? $provider['name'] ?? 'Unknown' );
						$recv       = number_format( (float) ( $provider['recipientAmount'] ?? $provider['amount'] ?? 0 ), 2 );
						$fee        = $provider['fee'] ?? $provider['totalFee'] ?? 0;
						$fee_fmt    = $fee > 0 ? esc_html( $from . ' ' . number_format( (float) $fee, 2 ) ) : esc_html__( 'Free', 'takapath-rates' );
						$speed      = esc_html( $provider['deliveryTime'] ?? $provider['speed'] ?? '—' );
						$link       = esc_url( $provider['link'] ?? $provider['url'] ?? '#' );
						$is_best    = 0 === $index;
						?>
						<tr class="<?php echo $is_best ? 'takapath-table__row--best' : ''; ?>">
							<td class="takapath-table__provider">
								<?php if ( $is_best ) : ?>
									<span class="takapath-badge"><?php esc_html_e( 'Best Rate', 'takapath-rates' ); ?></span>
								<?php endif; ?>
								<?php echo $name; ?>
							</td>
							<td><?php echo esc_html( $from . ' ' . number_format( $amount, 2 ) ); ?></td>
							<td class="takapath-table__amount">৳ <?php echo esc_html( $recv ); ?></td>
							<td><?php echo $fee_fmt; ?></td>
							<td><?php echo $speed; ?></td>
							<td>
								<a href="<?php echo $link; ?>" class="takapath-btn" target="_blank" rel="noopener noreferrer sponsored">
									<?php esc_html_e( 'Send Now', 'takapath-rates' ); ?>
								</a>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>

			<p class="takapath-widget__disclaimer">
				<?php esc_html_e( 'Rates are indicative and may vary. Always confirm on the provider\'s website before sending.', 'takapath-rates' ); ?>
			</p>
		</div>
		<?php
		return ob_get_clean();
	}
}
