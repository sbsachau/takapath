# TakaPath — SEO Strategy

## Homepage SEO

```
Title:    TakaPath – Compare Best Ways to Send Money to Bangladesh
Meta:     Compare exchange rates, fees and transfer speeds from Wise, Remitly,
          Western Union and more. Find the best way to send money to Bangladesh
          today — bank, bKash or cash pickup.
H1:       Best Way to Send Money to Bangladesh — Compare Rates & Fees
Tagline:  Trusted comparison for Bangladeshis worldwide. Updated rates from
          real providers — bank deposit, bKash, and cash pickup.
```

## Rank Math Configuration (post-install)

1. **Homepage** → set Custom Title + Meta via Rank Math → Edit Snippet
2. **Sitemap** → enable XML sitemap, include `corridor` CPT
3. **Schema** → set site type to "Website" + "FinancialService"
4. **Redirections** → enable redirect manager from day 1
5. **Local SEO** → not needed (comparison site, not local business)

## Corridor Page Pattern

```
URL slug:   /send-money-to-bangladesh/from-uk-gbp/
Title:      Send Money from UK to Bangladesh – Compare GBP to BDT Rates | TakaPath
Meta:       Best GBP to BDT exchange rates today. Compare Wise, Western Union,
            Remitly & more for sending money from UK to Bangladesh.
H1:         Send Money from UK to Bangladesh — Best GBP to BDT Rates
```

## Priority Corridors (source: Bangladesh Bank data)

| # | From Country | Currency | Slug |
|---|-------------|----------|------|
| 1 | Saudi Arabia | SAR | /send-money-to-bangladesh/from-saudi-arabia/ |
| 2 | UAE | AED | /send-money-to-bangladesh/from-uae/ |
| 3 | UK | GBP | /send-money-to-bangladesh/from-uk-gbp/ |
| 4 | Malaysia | MYR | /send-money-to-bangladesh/from-malaysia/ |
| 5 | USA | USD | /send-money-to-bangladesh/from-usa/ |
| 6 | Oman | OMR | /send-money-to-bangladesh/from-oman/ |
| 7 | Italy | EUR | /send-money-to-bangladesh/from-italy/ |
| 8 | Kuwait | KWD | /send-money-to-bangladesh/from-kuwait/ |

## Content Strategy

### Guide Articles (blog)
- "Best apps to send money to Bangladesh from UK in 2025"
- "Wise vs Western Union vs Remitly: Bangladesh remittance comparison"
- "How to send money to bKash from abroad"
- "Bangladesh remittance fees explained"
- "Cheapest way to send money to Bangladesh from Saudi Arabia"

### Bengali-language content
- Duplicate priority corridor pages in Bengali (Polylang)
- Bengali blog content targeting diaspora searching in native language
- hreflang tags: en / bn / x-default

## Canonical Strategy (Important)

TakaPath.com and TransferSmartly.com are two separate sites.
Any rates widget embedded from TransferSmartly must use:
```html
<link rel="canonical" href="https://takapath.com/[current-page]/" />
```
Do NOT iframe TransferSmartly pages — use the API data instead to avoid
Google indexing the same page under two domains.
