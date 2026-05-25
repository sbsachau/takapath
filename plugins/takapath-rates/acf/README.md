# ACF Field Groups — TakaPath

This directory contains ACF field group definitions registered **programmatically** via PHP.
This means fields are version-controlled and automatically available on any WordPress install
that has ACF active — no JSON import step needed.

## How It Works

The file `corridor-fields.php` is loaded by the main plugin bootstrap (`takapath-rates.php`)
on the `acf/init` hook. ACF reads the field group definitions and registers them as if they
were created in the WP Admin UI.

## Field Groups

### 1. `group_takapath_corridor` — Corridor Settings
Attached to: **Custom Post Type `corridor`**

| Field | Key | Type | Purpose |
|-------|-----|------|---------|
| From Country | `from_country` | Text | Source country name |
| From Currency | `from_currency` | Select | ISO 4217 code (GBP, USD, SAR…) |
| Flag Emoji | `flag_emoji` | Text | Country flag emoji |
| Default Send Amount | `default_amount` | Number | Pre-fills the rate widget |
| Corridor Intro | `corridor_intro` | Textarea | SEO-rich intro paragraph |
| Hero Stat | `hero_stat` | Text | Optional stat callout |
| Receive Methods | `receive_methods` | Checkbox | Bank / bKash / Nagad / Cash |
| SEO H1 | `seo_h1` | Text | Overrides post title as visible H1 |
| FAQ Items | `faqs` | Repeater | Question + Answer pairs → FAQ schema |
| Provider Highlights | `provider_overrides` | Repeater | Manual editorial callouts |
| Related Corridors | `related_corridors` | Relationship | CPT relationship links |

### 2. `group_takapath_homepage` — Homepage Settings
Attached to: **Front page** (page_type == front_page)

| Field | Key | Type | Purpose |
|-------|-----|------|---------|
| Hero Heading | `hero_heading` | Text | Editable H1 for homepage |
| Hero Tagline | `hero_tagline` | Text | Subheading below H1 |
| Default Widget Currency | `default_currency` | Select | Pre-selects currency in rates widget |
| Trust Bar Stats | `trust_stats` | Repeater | Stat + Label pairs in trust bar |
| Announcement Bar | `announcement` | Text | Optional top-of-page banner |

### 3. `group_takapath_global` — Global Settings *(ACF PRO only)*
Attached to: **Options page** (TakaPath → Global Settings in WP Admin menu)

| Field | Key | Type | Purpose |
|-------|-----|------|---------|
| Global Disclaimer | `global_disclaimer` | Textarea | Shown below every rate table |
| Affiliate Disclosure | `affiliate_disclosure` | Textarea | Legal disclosure text |
| Provider Count | `provider_count` | Number | For trust signals ("10+ providers") |
| Last Verified | `last_verified` | Date | Manually set last audit date |

## Usage in Templates

### Corridor page
```php
$currency = get_field('from_currency');     // e.g. 'GBP'
$amount   = get_field('default_amount');    // e.g. 1000
$faqs     = get_field('faqs');              // array of ['question'=>..., 'answer'=>...]
$h1       = get_field('seo_h1') ?: get_the_title();

// Render rate widget with corridor-specific defaults:
echo do_shortcode( "[takapath_rates from='{$currency}' amount='{$amount}']" );
```

### Homepage
```php
$heading  = get_field('hero_heading');
$tagline  = get_field('hero_tagline');
$currency = get_field('default_currency');
$stats    = get_field('trust_stats');  // array of ['stat'=>..., 'label'=>...]
```

### Global settings (ACF PRO)
```php
$disclaimer = get_field('global_disclaimer', 'option');
$disclosure = get_field('affiliate_disclosure', 'option');
```

## Adding New Fields

1. Add the field definition to the relevant `acf_add_local_field_group()` array in `corridor-fields.php`
2. Give it a unique `key` prefixed with `field_` and a `name` in snake_case
3. Commit and push — the field appears in WP Admin automatically

**Never create fields in WP Admin UI without also adding them here** — fields created only
in the UI will be lost when the database is reset or the site is migrated.
