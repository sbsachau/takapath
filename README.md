# TakaPath — Bangladesh Remittance Comparison Site

TakaPath.com is a Bangladesh-dedicated money transfer comparison website targeting the Bengali-speaking diaspora worldwide.

## Tech Stack
- **CMS:** WordPress (content-first, SEO-first)
- **Rate Engine:** TransferSmartly (MoneyRoutes API) — single source of truth
- **Theme:** Custom child theme based on GeneratePress
- **Page Builder:** Elementor Free (layout only)
- **SEO:** Rank Math SEO
- **Rate Display:** Custom TakaPath Rates Plugin (fetches `/api/compare?toCountry=BD`)
- **Multilingual:** Polylang (English + Bengali)
- **Performance:** WP Rocket + Cloudflare

## Repository Structure
```
takapath/
├── plugins/
│   └── takapath-rates/          # Core custom plugin — fetches & displays rates
├── theme/
│   └── takapath-child/          # GeneratePress child theme
├── config/
│   ├── plugin-stack.md          # Full plugin list with rationale
│   └── wp-config-additions.php  # wp-config.php additions (constants)
└── docs/
    ├── seo-strategy.md
    └── corridor-pages.md
```

## Setup Order
1. Install WordPress on managed host (Kinsta / WP Engine)
2. Install & activate plugin stack (see `config/plugin-stack.md`)
3. Install GeneratePress theme + activate `takapath-child`
4. Upload & activate `takapath-rates` plugin
5. Configure Rank Math with homepage SEO settings (see `docs/seo-strategy.md`)
6. Create corridor pages (see `docs/corridor-pages.md`)
7. Set TransferSmartly API endpoint in WP Admin → TakaPath Settings

## Environment Variables (set via wp-config.php)
```php
define('TAKAPATH_API_URL', 'https://transfersmartly.com/api/compare');
define('TAKAPATH_CACHE_TTL', 3600); // 1 hour cache for rate data
```
