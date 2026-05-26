# TakaPath — ACF Field Guide

Quick reference for whoever is managing content in WP Admin.

---

## How to activate the field groups

Field groups are registered **via PHP** — no database import needed. They load automatically once ACF Free or ACF Pro is installed and active.

To edit a field (add a currency, rename a label): edit `config/acf-field-groups.php` in the repo and redeploy.

---

## Field Group 1 — Corridor Meta

Appears on every post in the **Corridor** custom post type.

| Field | Type | Required | Notes |
|-------|------|----------|-------|
| `from_currency` | Select | ✅ | ISO 4217 code. Drives the shortcode `from=""` parameter. |
| `flag_emoji` | Text | ✅ | Single emoji e.g. 🇬🇧. Shown in corridor cards and hero. |
| `route_label` | Text | ✅ | Human label e.g. "UK → Bangladesh". Used in H1 and cards. |
| `source_country` | Text | ✅ | Full country name e.g. "United Kingdom". Used in meta. |
| `default_amount` | Number | ✅ | Pre-loaded send amount (source currency). Typical round number. |
| `intro_paragraph` | Textarea | — | 1–2 sentence English intro below H1. Mention bKash/Nagad for SEO. |
| `intro_paragraph_bn` | Textarea | — | Bengali translation of the intro. Used in bilingual trust block. |
| `seo_title_override` | Text | — | Leave blank — Rank Math handles titles. Only for hand-crafted titles. |
| `seo_meta_override` | Textarea | — | Leave blank unless you need a custom meta description. |
| `faq_items` | Repeater | — | Q&A pairs. Aim for 3–5. Emitted as FAQPage JSON-LD schema. |
| `receive_methods` | Checkbox | — | Tick all delivery methods available. Shown as pills on corridor page. |

### Suggested default amounts by corridor

| Corridor | `default_amount` |
|----------|------------------|
| SAR (Saudi Arabia) | 1000 |
| AED (UAE) | 1000 |
| GBP (UK) | 500 |
| MYR (Malaysia) | 1000 |
| USD (USA) | 500 |
| OMR (Oman) | 200 |
| EUR (Italy) | 500 |
| KWD (Kuwait) | 100 |

---

## Field Group 2 — Homepage Options

Appears in the **sidebar** when editing the page using the **TakaPath Homepage** template.

| Field | Type | Default | Notes |
|-------|------|---------|-------|
| `hero_volume_note` | Text | `Bangladesh received $33 billion...` | Badge above H1. Update annually. |
| `hero_default_currency` | Select | `GBP` | Currency pre-loaded in homepage widget. |
| `hero_default_amount` | Number | `1000` | Amount pre-loaded in homepage widget. |
| `stats_remittance_annual` | Text | `$33B` | Shown in stats strip. Update annually. |
| `stats_monthly_high` | Text | `$2.97B in May 2025 (+32% YoY)` | Shown in stats strip. Update as records are broken. |

---

## FAQ best practices

Each FAQ item is emitted in FAQPage JSON-LD schema. Google can show these as rich results in search.

**Good questions for corridor pages:**
- "What is the best way to send money from [country] to Bangladesh?"
- "How long does a [country] to Bangladesh transfer take?"
- "Does [provider] support bKash for Bangladesh transfers?"
- "What are the fees for sending [currency] to BDT?"
- "Is Wise available for Bangladesh transfers from [country]?"

Keep answers factual and under 300 words each. Do not copy text from providers' own sites.
