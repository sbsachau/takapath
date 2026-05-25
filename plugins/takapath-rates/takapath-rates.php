<?php
/**
 * Plugin Name:       TakaPath Rates
 * Plugin URI:        https://takapath.com
 * Description:       Fetches live money transfer rates from the TransferSmartly API
 *                    and displays them via a shortcode or Elementor HTML widget.
 *                    Target corridor: any → Bangladesh (BDT).
 * Version:           1.0.0
 * Author:            TakaPath
 * Author URI:        https://takapath.com
 * License:           GPL-2.0-or-later
 * Text Domain:       takapath-rates
 * Domain Path:       /languages
 * Requires at least: 6.0
 * Requires PHP:      8.0
 */

declare( strict_types=1 );

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'TAKAPATH_RATES_VERSION', '1.0.0' );
define( 'TAKAPATH_RATES_DIR', plugin_dir_path( __FILE__ ) );
define( 'TAKAPATH_RATES_URL', plugin_dir_url( __FILE__ ) );

/**
 * Register ACF local field groups for the Corridor CPT.
 * Must fire on acf/init so ACF is fully loaded before we register fields.
 */
add_action( 'acf/init', static function () {
	$acf_file = TAKAPATH_RATES_DIR . 'acf/corridor-fields.php';
	if ( file_exists( $acf_file ) ) {
		require_once $acf_file;
	}
} );

require_once TAKAPATH_RATES_DIR . 'includes/class-api-client.php';
require_once TAKAPATH_RATES_DIR . 'includes/class-shortcode.php';
require_once TAKAPATH_RATES_DIR . 'includes/class-admin-settings.php';
require_once TAKAPATH_RATES_DIR . 'includes/class-schema.php';

add_action( 'plugins_loaded', function () {
	TakaPath\Rates\Shortcode::register();

	if ( is_admin() ) {
		TakaPath\Rates\Admin_Settings::init();
	}

	TakaPath\Rates\Schema::init();
} );

/**
 * Schedule hourly cache pre-warming for all source currencies.
 */
register_activation_hook( __FILE__, function () {
	if ( ! wp_next_scheduled( 'takapath_refresh_rates_cache' ) ) {
		wp_schedule_event( time(), 'hourly', 'takapath_refresh_rates_cache' );
	}
} );

register_deactivation_hook( __FILE__, function () {
	wp_clear_scheduled_hook( 'takapath_refresh_rates_cache' );
} );

add_action( 'takapath_refresh_rates_cache', function () {
	// Top source countries for Bangladesh remittances.
	$currencies = [ 'GBP', 'USD', 'EUR', 'SAR', 'AED', 'MYR', 'OMR', 'KWD', 'QAR', 'SGD', 'ITL', 'CAD', 'AUD' ];
	foreach ( $currencies as $from ) {
		TakaPath\Rates\API_Client::get_rates( $from, force_refresh: true );
	}
} );
