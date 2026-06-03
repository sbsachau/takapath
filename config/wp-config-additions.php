<?php
/**
 * TakaPath — add these constants to your wp-config.php (LocalWP or production).
 *
 * FOR LOCAL DEVELOPMENT (LocalWP)
 * ─────────────────────────────────
 * Add lines marked [LOCAL] to your LocalWP site's wp-config.php:
 *   C:\Users\<you>\Local Sites\takapath\app\public\wp-config.php
 *
 * FOR PRODUCTION
 * ────────────────
 * Add lines marked [PROD] only. Never set WP_ENVIRONMENT_TYPE=local on production.
 */

// [LOCAL] Tells the plugin to use mock data instead of calling the live API.
// Remove or set to false on production.
define( 'WP_ENVIRONMENT_TYPE', 'local' );           // [LOCAL] triggers mock data automatically
// define( 'TAKAPATH_USE_MOCK_DATA', true );         // [LOCAL] alternative explicit flag

// [PROD] Live API endpoint (TransferSmartly). Set on production only.
// define( 'TAKAPATH_API_BASE', 'https://transfersmartly.com' );  // default, can be overridden

// [BOTH] Cache TTL in seconds for live API responses.
define( 'TAKAPATH_CACHE_TTL', 3600 );  // 1 hour

// [PROD] Set to 'production' on live server to disable all mock data.
// define( 'WP_ENVIRONMENT_TYPE', 'production' );
