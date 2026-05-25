<?php
/**
 * Bridge partial — includes the FAQ template from the takapath-rates plugin if it exists.
 * Keeps theme and plugin loosely coupled: the theme doesn't hard-require the plugin.
 */
declare( strict_types=1 );
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( defined( 'TAKAPATH_RATES_DIR' ) ) {
	$faq_template = TAKAPATH_RATES_DIR . 'templates/corridor-faq.php';
	if ( file_exists( $faq_template ) ) {
		include $faq_template;
	}
}
