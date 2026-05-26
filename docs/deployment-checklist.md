# TakaPath — Deployment Checklist

Follow this order when setting up TakaPath on a fresh WordPress install.

---

## 1. WordPress + Hosting
- [ ] WordPress installed at takapath.com
- [ ] SSL certificate active (HTTPS enforced)
- [ ] Permalink structure set to **Post name** (`/%postname%/`) in Settings → Permalinks
- [ ] Timezone set to **Dhaka (UTC+6)** in Settings → General

## 2. Plugins to Install & Activate
| Plugin | Why |
|--------|-----|
| **GeneratePress** (free theme) | Parent theme |
| **ACF Free** or ACF Pro | Field groups for corridor posts |
| **Rank Math SEO** (free) | Sitemap, meta templates, schema |
| **WP Rocket** (or host cache) | Page caching + minification |
| **Polylang** (optional, later) | Bengali language support |

## 3. Theme
- [ ] Upload `theme/takapath-child/` to `/wp-content/themes/takapath-child/`
- [ ] Activate TakaPath Child theme in Appearance → Themes
- [ ] Flush permalinks: Settings → Permalinks → Save

## 4. Plugin — takapath-rates
- [ ] Upload `plugins/takapath-rates/` to `/wp-content/plugins/takapath-rates/`
- [ ] Activate in Plugins → Installed Plugins
- [ ] Go to **Settings → TakaPath** and verify API URL is set to `https://transfersmartly.com/api/compare`
- [ ] Click **Clear Rate Cache** to trigger a first fetch
- [ ] Verify shortcode works: add `[takapath_rates from="GBP" amount="500"]` to any post and preview

## 5. ACF Field Groups
- [ ] ACF fields load automatically via PHP (no import needed) once the theme is active
- [ ] Verify: go to ACF → Field Groups — you should see **Corridor Meta** and **Homepage Options**
- [ ] If not visible, check `config/acf-field-groups.php` path in `functions.php`

## 6. Corridor Posts
- [ ] Run the seeder: `wp eval-file scripts/seed-corridors.php` from the WordPress root
- [ ] Verify 8 corridor posts created in WP Admin → Corridor Guides
- [ ] Check each post has ACF fields populated (from_currency, flag_emoji, FAQs etc.)
- [ ] Visit `/send-money-to-bangladesh/uk-to-bangladesh/` — confirm template renders
- [ ] Flush permalinks again after seeding: Settings → Permalinks → Save

## 7. Homepage
- [ ] Create a new Page, set template to **TakaPath Homepage**
- [ ] Set as Static Homepage in Settings → Reading → Your homepage displays → A static page
- [ ] Verify the corridor grid shows the 8 seeded posts (not static fallback)
- [ ] Verify `[takapath_rates]` widget loads and shows provider rates

## 8. Rank Math SEO
- [ ] Run Rank Math setup wizard
- [ ] Set corridor post type title template: `Send Money from %custom_field(source_country)% to Bangladesh – %custom_field(from_currency)% to BDT | TakaPath`
- [ ] Set corridor meta description template to mention bKash, BDT, and the source country
- [ ] Submit sitemap to Google Search Console: `takapath.com/sitemap_index.xml`
- [ ] Submit sitemap to Bing Webmaster Tools

## 9. Google Search Console
- [ ] Add takapath.com as a property
- [ ] Verify via HTML tag or DNS
- [ ] Request indexing for homepage and each corridor page

## 10. Post-launch
- [ ] Verify canonical tags on all corridor pages point to takapath.com (not .replit.app)
- [ ] Check hreflang tags are present in page source
- [ ] Test FAQPage rich results via [Rich Results Test](https://search.google.com/test/rich-results)
- [ ] Set up uptime monitor (UptimeRobot free tier)
- [ ] Schedule annual stats update reminder (Bangladesh Bank figures, May each year)
