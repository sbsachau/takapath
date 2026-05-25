# TakaPath — WordPress Plugin Stack

All plugins are free unless marked **[Premium]**. Priority = installation order.

## Core (Required)

| Priority | Plugin | Version | Purpose | Notes |
|----------|--------|---------|---------|-------|
| 1 | **GeneratePress** (theme) | Latest | Fast, lightweight base theme | Use free tier + child theme |
| 2 | **Rank Math SEO** | Free | On-page SEO, schema, sitemaps | Configure immediately after install |
| 3 | **Elementor** | Free | Page layout builder | Layout only — no bloated widgets |
| 4 | **TakaPath Rates** | Custom | Fetch & display rates from TransferSmartly API | See `plugins/takapath-rates/` |
| 5 | **Advanced Custom Fields (ACF)** | Free | Provider metadata, corridor page fields | |
| 6 | **Polylang** | Free | English + Bengali bilingual support | EN default, BN as secondary |
| 7 | **WP Rocket** | [Premium] | Page caching, minification, CDN | Can substitute W3 Total Cache (free) |
| 8 | **Cloudflare** | Free plugin | Cloudflare CDN integration | Requires Cloudflare account |
| 9 | **UpdraftPlus** | Free | Automated backups to Google Drive / S3 | |
| 10 | **WP Mail SMTP** | Free | Reliable transactional email via SMTP | |

## SEO Enhancement

| Plugin | Purpose | Notes |
|--------|---------|-------|
| **Redirection** | 301 redirect management | Essential as content grows |
| **Schema Pro** | Rich snippets for comparison tables | Optional — Rank Math covers basics |
| **Broken Link Checker** | Monitor dead links | Run periodically, not always-on |

## Security

| Plugin | Purpose |
|--------|--------|
| **Wordfence Security** | Free firewall + malware scan |
| **Limit Login Attempts Reloaded** | Brute force protection |
| **WPS Hide Login** | Change /wp-admin URL |

## Analytics

| Plugin | Purpose |
|--------|--------|
| **MonsterInsights Lite** OR direct GA4 code | Google Analytics 4 integration |
| **HotJar** (script embed) | Heatmaps + session recordings |

## What NOT to Install
- ❌ WooCommerce (not an e-commerce site)
- ❌ Jetpack (too heavy)
- ❌ WPML (Polylang is sufficient)
- ❌ Yoast SEO (duplicate with Rank Math — choose one)
- ❌ Contact Form 7 + WPForms together (pick one)
- ❌ Any page builder other than Elementor

## Plugin Count Target
Keep active plugins under 20 for performance. Current stack: ~14 active plugins.
