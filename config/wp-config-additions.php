<?php
/**
 * TakaPath — wp-config.php additions
 * Paste these constants BEFORE the line: "That's all, stop editing!"
 * in your wp-config.php file on the server.
 *
 * SECURITY: Never commit real API keys or credentials to this file.
 * Use environment variables or a secrets manager in production.
 */

// ─── TakaPath Rate Engine ────────────────────────────────────────────────────
// Base URL of the TransferSmartly API (no trailing slash)
define( 'TAKAPATH_API_URL', getenv('TAKAPATH_API_URL') ?: 'https://transfersmartly.com/api/compare' );

// Cache lifetime in seconds for fetched rate data (default: 1 hour)
define( 'TAKAPATH_CACHE_TTL', (int) ( getenv('TAKAPATH_CACHE_TTL') ?: 3600 ) );

// Optional API key if TransferSmartly adds authentication later
// define( 'TAKAPATH_API_KEY', getenv('TAKAPATH_API_KEY') ?: '' );

// ─── Performance ─────────────────────────────────────────────────────────────
// Disable WordPress post revisions (keeps DB clean)
define( 'WP_POST_REVISIONS', 5 );

// Empty trash every 7 days
define( 'EMPTY_TRASH_DAYS', 7 );

// Disable file editing via WP Admin (security best practice)
define( 'DISALLOW_FILE_EDIT', true );

// Auto-update minor WordPress core versions only
define( 'WP_AUTO_UPDATE_CORE', 'minor' );

// ─── Debug (disable on production) ──────────────────────────────────────────
// Uncomment below lines only on local/staging environments:
// define( 'WP_DEBUG', true );
// define( 'WP_DEBUG_LOG', true );
// define( 'WP_DEBUG_DISPLAY', false );
// @ini_set( 'display_errors', 0 );
