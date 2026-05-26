# TakaPath — Deployment Checklist

Work through each section in order. Tick items off as you go.

---

## Phase 1 — Hosting & Domain

- [ ] WordPress installed on managed host (Kinsta / Cloudways / SiteGround recommended)
- [ ] SSL certificate active — `https://takapath.com` loads cleanly
- [ ] `takapath.com` DNS pointed at host (A record or CNAME)
- [ ] `www` redirect → non-www (or vice versa) configured at host level
- [ ] WP_DEBUG set to `false` in `wp-config.php` for production
- [ ] Paste `config/wp-config-additions.php` constants into `wp-config.php`

---

## Phase 2 — WordPress Core Setup

- [ ] Run `bash config/setup-wpcli.sh` (creates pages, menus, permalinks)
- [ ] OR follow manual equivalent steps in WP Admin
- [ ] Verify permalink structure: `/%postname%/` (Settings > Permalinks)
- [ ] Verify homepage is set to static page (Settings > Reading)
- [ ] Disable comments sitewide (Settings > Discussion)
- [ ] Set timezone: Asia/Dhaka (Settings > General)

---

## Phase 3 — Theme

- [ ] Upload `theme/takapath-child/` to `/wp-content/themes/`
- [ ] Activate parent theme first (e.g. GeneratePress, Astra, or Blocksy)
- [ ] Activate `takapath-child` theme
- [ ] Verify `style.css` header has correct `Template:` line matching parent theme slug
- [ ] Check homepage renders correctly at `https://takapath.com`

---

## Phase 4 — Plugins

### Required plugins (install in this order)

| Plugin | Source | Notes |
|--------|--------|-------|
| Advanced Custom Fields (ACF) | wordpress.org or ACF Pro | Free version sufficient |
| Rank Math SEO | wordpress.org | Free version sufficient |
| Polylang | wordpress.org | For EN/BN bilingual support |
| WP Rocket (or W3 Total Cache) | woothemes / wordpress.org | Caching — reduces TTFB |
| Really Simple SSL | wordpress.org | SSL redirect |
| UpdraftPlus | wordpress.org | Backups |

### Custom plugin
- [ ] Upload `plugins/takapath-rates/` to `/wp-content/plugins/`
- [ ] Activate `takapath-rates` plugin
- [ ] Verify admin settings page at WP Admin > Settings > TakaPath Rates
- [ ] Test shortcode: add `[takapath_rates from="GBP" amount="500"]` to a test page
- [ ] Confirm rates return correctly from TransferSmartly API

---

## Phase 5 — ACF Field Groups

- [ ] Go to ACF > Tools > Import
- [ ] Import `config/acf-field-groups.php` (or run via WP-CLI: `wp eval-file config/acf-field-groups.php`)
- [ ] Verify field groups appear under ACF > Field Groups:
  - `Corridor Details`
  - `Guide Page Options`

---

## Phase 6 — Rank Math SEO

- [ ] Complete Rank Math Setup Wizard (Organisation type, logo, social profiles)
- [ ] Go to Rank Math > Status & Tools > Import & Export
- [ ] Import `config/rankmath-import.json`
- [ ] Verify homepage title in Rank Math > Titles & Meta > Homepage
- [ ] Verify corridor CPT title template uses `%%cf_from_country%%` tokens
- [ ] Enable XML sitemap (Rank Math > Sitemap)
- [ ] Include: Posts ✓ Pages ✓ Corridors ✓
- [ ] Exclude: Tags, Author archives
- [ ] Apply per-page meta (see `config/rankmath-import.json > page_specific`):
  - [ ] Homepage focus keyword: `best way to send money to Bangladesh`
  - [ ] About: `about TakaPath`
  - [ ] How We Work: `how TakaPath works`
  - [ ] bKash guide: `receive money on bKash from abroad`
  - [ ] Each corridor page: `send money from [country] to Bangladesh`

---

## Phase 7 — Content: Corridors

- [ ] Run corridor seeder (if available) or create corridors manually in WP Admin
- [ ] All 8 corridor posts published:
  - [ ] Saudi Arabia → Bangladesh (`saudi-arabia-to-bangladesh`)
  - [ ] UAE → Bangladesh (`uae-to-bangladesh`)
  - [ ] UK → Bangladesh (`uk-to-bangladesh`)
  - [ ] Malaysia → Bangladesh (`malaysia-to-bangladesh`)
  - [ ] USA → Bangladesh (`usa-to-bangladesh`)
  - [ ] Oman → Bangladesh (`oman-to-bangladesh`)
  - [ ] Italy → Bangladesh (`italy-to-bangladesh`)
  - [ ] Kuwait → Bangladesh (`kuwait-to-bangladesh`)
- [ ] Each corridor has ACF fields populated: from_country, from_currency, from_flag
- [ ] Each corridor page displays the rate comparison table correctly

---

## Phase 8 — Content: Pages

- [ ] Homepage: template `TakaPath Home Page` assigned, renders correctly
- [ ] About: template `TakaPath About Page`, slug `about`
- [ ] How We Work: template `TakaPath How We Work`, slug `how-we-work`
- [ ] bKash Guide: template `TakaPath Guide Page`, slug `bkash-receive-money-abroad`
- [ ] Navigation menu includes: Home, All Corridors, bKash Guide, About, How We Work

---

## Phase 9 — SEO & Search Console

- [ ] Robots.txt accessible at `https://takapath.com/robots.txt`
- [ ] Sitemap accessible at `https://takapath.com/sitemap_index.xml`
- [ ] All corridor URLs in sitemap
- [ ] Google Search Console: add property for `takapath.com`
- [ ] Submit sitemap URL in Search Console
- [ ] Request indexing for homepage, about, how-we-work manually
- [ ] Verify no noindex directives on key pages (Search Console > URL Inspection)
- [ ] Structured data test (schema.org validator) for:
  - [ ] Organisation schema on homepage
  - [ ] HowTo schema on bKash guide
  - [ ] BreadcrumbList on all pages

---

## Phase 10 — Performance & Security

- [ ] Google PageSpeed Insights score ≥ 85 on mobile for homepage
- [ ] LCP < 2.5s on mobile
- [ ] No layout shift (CLS < 0.1) on homepage rate table load
- [ ] WP Rocket / caching plugin configured with HTML, CSS, JS minification
- [ ] Images converted to WebP
- [ ] Set security headers (Rank Math or security plugin):
  - [ ] `X-Content-Type-Options: nosniff`
  - [ ] `X-Frame-Options: SAMEORIGIN`
  - [ ] `Referrer-Policy: strict-origin-when-cross-origin`

---

## Phase 11 — Post-Launch

- [ ] Verify TransferSmartly API rate data is live on all corridor pages
- [ ] Test `[takapath_rates]` shortcode at all 13 source currencies
- [ ] WP Cron running: verify transients populated at `wp_options` table (look for `takapath_rates_*`)
- [ ] Set up uptime monitor (UptimeRobot free tier)
- [ ] Schedule first UpdraftPlus backup
- [ ] Share homepage URL with 3 real users from Bangladeshi diaspora and gather feedback

---

## Steps Completed in This Repo

| Step | Description | Status |
|------|-------------|--------|
| 1 | Plugin stack (takapath-rates) | ✅ |
| 2 | functions.php (enqueues, CPT, schema, templates) | ✅ |
| 3 | ACF field groups config | ✅ |
| 4 | Corridor seeder (8 posts) | ✅ |
| 5 | header.php + footer.php + header-footer.css | ✅ |
| 6 | front-page.php + home-extra.css | ✅ |
| 7 | page-guide.php + guide.css (bKash guide) | ✅ |
| 8 | page-about.php + page-how-we-work.php + trust.css | ✅ |
| 9 | Rank Math config, WP-CLI script, sitemap seed, deployment checklist | ✅ |
