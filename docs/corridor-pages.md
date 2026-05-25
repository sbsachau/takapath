# TakaPath — Corridor Pages Setup

## Custom Post Type: `corridor`

Registered in `theme/takapath-child/functions.php`.
Archive URL: `/send-money-to-bangladesh/`

## Creating a Corridor Page

1. WP Admin → Corridor Guides → Add New
2. Set **title**: e.g. `Send Money from UK to Bangladesh`
3. Set **slug**: `from-uk-gbp`
4. Set **ACF fields** (install ACF first):

| Field Name | Type | Example |
|------------|------|---------|
| `from_country` | Text | United Kingdom |
| `from_currency` | Text (ISO 4217) | GBP |
| `from_flag_emoji` | Text | 🇬🇧 |
| `corridor_headline` | Text | Best GBP to BDT Exchange Rates |
| `corridor_intro` | Textarea | Bangladeshis in the UK send over $3.5B per year... |
| `featured_providers` | Repeater | Wise, Remitly, Western Union |

5. Add the rate comparison shortcode in the page body:
```
[takapath_rates from="GBP" amount="1000"]
```

6. Set Rank Math SEO title + meta for this page.

## Priority Build Order

```
Week 1: UK (GBP) + USA (USD)   ← highest English-language search volume
Week 2: Saudi Arabia (SAR) + UAE (AED)  ← highest remittance volume
Week 3: Malaysia (MYR) + Oman (OMR)
Week 4: Kuwait (KWD) + Italy (EUR)
```

## Page Template Structure

```
┌─────────────────────────────────────────┐
│  HERO                                   │
│  H1: Send Money from [Country] to BD   │
│  Tagline + trust signals               │
├─────────────────────────────────────────┤
│  RATES TABLE                            │
│  [takapath_rates from="GBP"]            │
├─────────────────────────────────────────┤
│  HOW IT WORKS  (3-step visual)          │
├─────────────────────────────────────────┤
│  CORRIDOR GUIDE  (editorial content)    │
│  Provider deep-dives, tips, bKash info  │
├─────────────────────────────────────────┤
│  FAQ  (schema markup via Rank Math)     │
├─────────────────────────────────────────┤
│  RELATED CORRIDORS                      │
└─────────────────────────────────────────┘
```

## SEO Checklist per Corridor Page

- [ ] Unique H1 mentioning from-country, currency, and "Bangladesh"
- [ ] Rank Math title < 60 chars, includes "| TakaPath"
- [ ] Meta description < 155 chars, includes BDT/bKash/bank
- [ ] Shortcode `[takapath_rates from="XXX"]` placed above the fold
- [ ] At least 500 words of original editorial content
- [ ] Internal links to: homepage, 2+ other corridor pages
- [ ] FAQ section with schema (at least 4 Q&As)
- [ ] Image with alt text: `Send money from [Country] to Bangladesh`
- [ ] hreflang set for EN + BN variants
