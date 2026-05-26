#!/usr/bin/env bash
# =============================================================
# TakaPath — WP-CLI Setup Script
# Run this ONCE after WordPress is installed on the server.
# Usage: bash config/setup-wpcli.sh
# Requires: wp-cli installed, correct wp-config.php present
# =============================================================

set -e

WP="wp --allow-root"

echo "==> [1/10] Setting site title & tagline"
$WP option update blogname "TakaPath"
$WP option update blogdescription "Compare Best Ways to Send Money to Bangladesh"
$WP option update blogpublic 1

echo "==> [2/10] Setting permalink structure"
$WP option update permalink_structure "/%postname%/"
$WP rewrite flush --hard

echo "==> [3/10] Setting timezone"
$WP option update timezone_string "Asia/Dhaka"
echo "Note: Change to your own timezone if outside Bangladesh."

echo "==> [4/10] Disabling comments sitewide"
$WP option update default_comment_status closed
$WP option update default_ping_status closed

echo "==> [5/10] Creating core pages"
# Homepage
HP_ID=$($WP post create --post_type=page --post_title='Home' --post_status=publish --post_name='home' --page_template='page-home.php' --porcelain)
echo "    Homepage ID: $HP_ID"
$WP option update page_on_front $HP_ID
$WP option update show_on_front page

# About
ABOUT_ID=$($WP post create --post_type=page --post_title='About TakaPath' --post_status=publish --post_name='about' --page_template='page-about.php' --porcelain)
echo "    About ID: $ABOUT_ID"

# How We Work
HWW_ID=$($WP post create --post_type=page --post_title='How TakaPath Works' --post_status=publish --post_name='how-we-work' --page_template='page-how-we-work.php' --porcelain)
echo "    How We Work ID: $HWW_ID"

# bKash guide
BKASH_ID=$($WP post create --post_type=page --post_title='How to Receive Money on bKash from Abroad' --post_status=publish --post_name='bkash-receive-money-abroad' --page_template='page-guide.php' --porcelain)
echo "    bKash Guide ID: $BKASH_ID"

echo "==> [6/10] Creating primary navigation menu"
MENU_ID=$($WP menu create "Primary Menu" --porcelain)
$WP menu location assign $MENU_ID primary
$WP menu item add-post $MENU_ID $HP_ID --title="Home"
$WP menu item add-post $MENU_ID $ABOUT_ID --title="About"
$WP menu item add-post $MENU_ID $HWW_ID --title="How We Work"
$WP menu item add-custom $MENU_ID --title="All Corridors" --url="/send-money-to-bangladesh/"
$WP menu item add-post $MENU_ID $BKASH_ID --title="bKash Guide"
echo "    Menu ID: $MENU_ID"

echo "==> [7/10] Activating plugins (requires plugins installed in /wp-content/plugins/)"
PLUGINS=(
  "takapath-rates"          # custom — must be installed manually
  "advanced-custom-fields"  # or acf-pro
  "rank-math"               # rank-math-seo
  "polylang"                # polylang
  "wp-rocket"               # or w3-total-cache
  "really-simple-ssl"       # SSL redirect
)
for PLUGIN in "${PLUGINS[@]}"; do
  if $WP plugin is-installed $PLUGIN 2>/dev/null; then
    $WP plugin activate $PLUGIN
    echo "    Activated: $PLUGIN"
  else
    echo "    SKIP (not installed): $PLUGIN"
  fi
done

echo "==> [8/10] Activating child theme"
$WP theme activate takapath-child 2>/dev/null || echo "    Theme takapath-child not found — upload theme directory first."

echo "==> [9/10] Flushing rewrite rules"
$WP rewrite flush --hard

echo "==> [10/10] Pinging Google & Bing sitemaps"
SITEURL=$($WP option get siteurl)
curl -s "https://www.google.com/ping?sitemap=${SITEURL}/sitemap_index.xml" -o /dev/null && echo "    Google pinged."
curl -s "https://www.bing.com/ping?sitemap=${SITEURL}/sitemap_index.xml" -o /dev/null && echo "    Bing pinged."

echo ""
echo "=== TakaPath WP-CLI setup complete! ==="
echo ""
echo "Next steps:"
echo "  1. Upload takapath-rates plugin to /wp-content/plugins/"
echo "  2. Install ACF, Rank Math, Polylang, WP Rocket via WP Admin"
echo "  3. Import config/rankmath-import.json via Rank Math > Status & Tools > Import"
echo "  4. Apply per-page Rank Math meta (see config/rankmath-import.json > page_specific)"
echo "  5. Run config/acf-field-groups.php via WP-CLI or paste into WP Admin > ACF > Tools > Import"
echo "  6. Run corridor seeder: wp eval-file config/corridor-seeder.php"
echo "  7. Verify sitemap at ${SITEURL}/sitemap_index.xml"
echo "  8. Submit sitemap in Google Search Console"
