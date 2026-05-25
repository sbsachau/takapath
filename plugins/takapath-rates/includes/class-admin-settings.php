<?php
/**
 * Admin_Settings — adds a TakaPath Settings page under WP Admin → Settings.
 * Allows site admins to set the TransferSmartly API URL and cache TTL
 * without touching wp-config.php.
 */

declare( strict_types=1 );

namespace TakaPath\Rates;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Admin_Settings {

	public static function init(): void {
		add_action( 'admin_menu', [ self::class, 'add_page' ] );
		add_action( 'admin_init', [ self::class, 'register_settings' ] );
		add_action( 'admin_post_takapath_bust_cache', [ self::class, 'bust_all_caches' ] );
	}

	public static function add_page(): void {
		add_options_page(
			__( 'TakaPath Settings', 'takapath-rates' ),
			__( 'TakaPath', 'takapath-rates' ),
			'manage_options',
			'takapath-settings',
			[ self::class, 'render_page' ]
		);
	}

	public static function register_settings(): void {
		register_setting( 'takapath_settings', 'takapath_api_url', [
			'type'              => 'string',
			'sanitize_callback' => 'esc_url_raw',
			'default'           => 'https://transfersmartly.com/api/compare',
		] );

		register_setting( 'takapath_settings', 'takapath_cache_ttl', [
			'type'              => 'integer',
			'sanitize_callback' => 'absint',
			'default'           => 3600,
		] );

		add_settings_section(
			'takapath_api_section',
			__( 'Rate Engine Connection', 'takapath-rates' ),
			[ self::class, 'section_description' ],
			'takapath-settings'
		);

		add_settings_field(
			'takapath_api_url',
			__( 'TransferSmartly API URL', 'takapath-rates' ),
			[ self::class, 'field_api_url' ],
			'takapath-settings',
			'takapath_api_section'
		);

		add_settings_field(
			'takapath_cache_ttl',
			__( 'Cache Duration (seconds)', 'takapath-rates' ),
			[ self::class, 'field_cache_ttl' ],
			'takapath-settings',
			'takapath_api_section'
		);
	}

	public static function section_description(): void {
		echo '<p>' . esc_html__(
			'Configure the connection to TransferSmartly, which powers the live rate comparisons on TakaPath.',
			'takapath-rates'
		) . '</p>';
	}

	public static function field_api_url(): void {
		$val = esc_url( get_option( 'takapath_api_url', 'https://transfersmartly.com/api/compare' ) );
		echo "<input type='url' name='takapath_api_url' value='{$val}' class='regular-text' />";
		echo '<p class="description">' . esc_html__( 'The /api/compare endpoint of TransferSmartly. Leave default unless you change the API route.', 'takapath-rates' ) . '</p>';
	}

	public static function field_cache_ttl(): void {
		$val = absint( get_option( 'takapath_cache_ttl', 3600 ) );
		echo "<input type='number' name='takapath_cache_ttl' value='{$val}' min='300' max='86400' step='300' />";
		echo '<p class="description">' . esc_html__( 'How long to cache rate data (in seconds). 3600 = 1 hour. Minimum 300 (5 min).', 'takapath-rates' ) . '</p>';
	}

	public static function render_page(): void {
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'TakaPath Settings', 'takapath-rates' ); ?></h1>
			<form method="post" action="options.php">
				<?php
				settings_fields( 'takapath_settings' );
				do_settings_sections( 'takapath-settings' );
				submit_button();
				?>
			</form>
			<hr />
			<h2><?php esc_html_e( 'Cache Management', 'takapath-rates' ); ?></h2>
			<p><?php esc_html_e( 'Manually clear all cached rate data (forces a fresh API fetch on next page load).', 'takapath-rates' ); ?></p>
			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
				<?php wp_nonce_field( 'takapath_bust_cache' ); ?>
				<input type="hidden" name="action" value="takapath_bust_cache" />
				<?php submit_button( __( 'Clear Rate Cache', 'takapath-rates' ), 'secondary' ); ?>
			</form>
		</div>
		<?php
	}

	public static function bust_all_caches(): void {
		check_admin_referer( 'takapath_bust_cache' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Insufficient permissions.', 'takapath-rates' ) );
		}

		$currencies = [ 'gbp', 'usd', 'eur', 'sar', 'aed', 'myr', 'omr', 'kwd', 'qar', 'sgd', 'cad', 'aud' ];
		foreach ( $currencies as $c ) {
			delete_transient( 'takapath_rates_' . $c );
		}

		wp_redirect( add_query_arg( [ 'page' => 'takapath-settings', 'cache-cleared' => '1' ], admin_url( 'options-general.php' ) ) );
		exit;
	}
}
