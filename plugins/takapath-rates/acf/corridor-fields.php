<?php
/**
 * TakaPath — ACF Field Groups
 * Register ACF field groups programmatically so they are version-controlled.
 * This approach is preferred over importing JSON — fields are always in sync with code.
 *
 * Requires: Advanced Custom Fields (free) v6.0+
 *
 * Field groups:
 *   1. Corridor Guide Fields  → attached to CPT: corridor
 *   2. Provider Override Fields → attached to CPT: corridor (repeater sub-fields)
 *   3. Homepage Settings      → attached to page template: front-page
 */

declare( strict_types=1 );

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'acf_add_local_field_group' ) ) {
	return; // ACF not active
}

// ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
// 1. CORRIDOR GUIDE FIELDS
// ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
acf_add_local_field_group( [
	'key'      => 'group_takapath_corridor',
	'title'    => 'Corridor Settings',
	'fields'   => [

		// ── Source country ─────────────────────────────────────────────────
		[
			'key'           => 'field_corridor_from_country',
			'label'         => 'From Country',
			'name'          => 'from_country',
			'type'          => 'text',
			'instructions'  => 'Full country name, e.g. "United Kingdom"',
			'required'      => 1,
			'placeholder'   => 'United Kingdom',
		],

		// ── Source currency ─────────────────────────────────────────────────
		[
			'key'           => 'field_corridor_from_currency',
			'label'         => 'From Currency (ISO 4217)',
			'name'          => 'from_currency',
			'type'          => 'select',
			'instructions'  => 'Select the source currency for this corridor.',
			'required'      => 1,
			'choices'       => [
				'GBP' => '🇬🇧 GBP — British Pound',
				'USD' => '🇺🇸 USD — US Dollar',
				'EUR' => '🇪🇺 EUR — Euro',
				'SAR' => '🇸🇦 SAR — Saudi Riyal',
				'AED' => '🇦🇪 AED — UAE Dirham',
				'MYR' => '🇲🇾 MYR — Malaysian Ringgit',
				'OMR' => '🇴🇲 OMR — Omani Rial',
				'KWD' => '🇰🇼 KWD — Kuwaiti Dinar',
				'QAR' => '🇶🇦 QAR — Qatari Riyal',
				'SGD' => '🇸🇬 SGD — Singapore Dollar',
				'CAD' => '🇨🇦 CAD — Canadian Dollar',
				'AUD' => '🇦🇺 AUD — Australian Dollar',
				'ITL' => '🇮🇹 ITL — Italian (EUR)',
			],
			'default_value' => 'GBP',
			'ui'            => 1, // use Select2 UI
		],

		// ── Flag emoji ────────────────────────────────────────────────────────
		[
			'key'           => 'field_corridor_flag_emoji',
			'label'         => 'Flag Emoji',
			'name'          => 'flag_emoji',
			'type'          => 'text',
			'instructions'  => 'Country flag emoji, e.g. 🇬🇧. Copy-paste from emojipedia.org',
			'required'      => 1,
			'placeholder'   => '🇬🇧',
			'maxlength'     => 8,
		],

		// ── Default send amount for rate widget ───────────────────────────────
		[
			'key'           => 'field_corridor_default_amount',
			'label'         => 'Default Send Amount',
			'name'          => 'default_amount',
			'type'          => 'number',
			'instructions'  => 'The default amount shown in the rates widget for this corridor.',
			'required'      => 1,
			'default_value' => 1000,
			'min'           => 1,
			'max'           => 100000,
			'step'          => 100,
			'prepend'       => '', // will be set to currency symbol dynamically
		],

		// ── Corridor intro ────────────────────────────────────────────────────
		[
			'key'           => 'field_corridor_intro',
			'label'         => 'Corridor Intro Paragraph',
			'name'          => 'corridor_intro',
			'type'          => 'textarea',
			'instructions'  => 'Short paragraph (2-3 sentences) shown under the H1. Mention diaspora size, remittance volume, or key stat.',
			'rows'          => 4,
			'placeholder'   => 'Over 700,000 Bangladeshis live in the UK, sending billions home each year...',
		],

		// ── Hero stat (optional) ──────────────────────────────────────────────
		[
			'key'           => 'field_corridor_hero_stat',
			'label'         => 'Hero Stat (optional)',
			'name'          => 'hero_stat',
			'type'          => 'text',
			'instructions'  => 'A single key stat shown in the hero, e.g. "3.5B sent annually"',
			'placeholder'   => '3.5B sent from UK annually',
		],

		// ── Receive method highlights ─────────────────────────────────────────
		[
			'key'           => 'field_corridor_receive_methods',
			'label'         => 'Available Receive Methods',
			'name'          => 'receive_methods',
			'type'          => 'checkbox',
			'instructions'  => 'Tick all methods available from providers for this corridor.',
			'choices'       => [
				'bank'       => '🏦 Bank deposit',
				'bkash'      => '📱 bKash mobile wallet',
				'nagad'      => '📱 Nagad mobile wallet',
				'rocket'     => '📱 Rocket (DBBL) wallet',
				'cash'       => '🏪 Cash pickup',
				'home'       => '🚪 Home delivery',
			],
			'default_value' => [ 'bank', 'bkash', 'cash' ],
			'layout'        => 'horizontal',
		],

		// ── SEO: custom H1 (overrides post title) ─────────────────────────────
		[
			'key'           => 'field_corridor_seo_h1',
			'label'         => 'SEO H1 (overrides page title)',
			'name'          => 'seo_h1',
			'type'          => 'text',
			'instructions'  => 'Optional. If set, replaces the post title as the visible H1. Leave blank to use post title.',
			'placeholder'   => 'Send Money from UK to Bangladesh -- Best GBP to BDT Rates',
			'maxlength'     => 100,
		],

		// ── FAQ Items (repeater) ──────────────────────────────────────────────
		[
			'key'           => 'field_corridor_faqs',
			'label'         => 'FAQ Items',
			'name'          => 'faqs',
			'type'          => 'repeater',
			'instructions'  => 'Add 4+ FAQs. These are output with FAQ schema markup for Google rich results.',
			'min'           => 0,
			'max'           => 12,
			'layout'        => 'block',
			'button_label'  => 'Add FAQ',
			'sub_fields'    => [
				[
					'key'         => 'field_faq_question',
					'label'       => 'Question',
					'name'        => 'question',
					'type'        => 'text',
					'required'    => 1,
					'placeholder' => 'What is the cheapest way to send money from UK to Bangladesh?',
				],
				[
					'key'         => 'field_faq_answer',
					'label'       => 'Answer',
					'name'        => 'answer',
					'type'        => 'textarea',
					'required'    => 1,
					'rows'        => 3,
					'placeholder' => 'As of today, Wise typically offers the best GBP to BDT exchange rate...',
				],
			],
		],

		// ── Provider overrides (repeater) ─────────────────────────────────────
		[
			'key'           => 'field_corridor_provider_overrides',
			'label'         => 'Provider Highlights (optional manual overrides)',
			'name'          => 'provider_overrides',
			'type'          => 'repeater',
			'instructions'  => 'Optional. Manually highlight specific providers with editorial notes (e.g. "Best for bKash"). These appear as callout cards above the rate table.',
			'min'           => 0,
			'max'           => 5,
			'layout'        => 'table',
			'button_label'  => 'Add Provider Highlight',
			'sub_fields'    => [
				[
					'key'         => 'field_provider_name',
					'label'       => 'Provider Name',
					'name'        => 'provider_name',
					'type'        => 'select',
					'choices'     => [
						'Wise'          => 'Wise',
						'Remitly'       => 'Remitly',
						'Western Union' => 'Western Union',
						'MoneyGram'     => 'MoneyGram',
						'Ria'           => 'Ria Money Transfer',
						'WorldRemit'    => 'WorldRemit',
						'Sendwave'      => 'Sendwave',
						'bKash'         => 'bKash (HomeSend)',
						'ACE Money'     => 'ACE Money Transfer',
						'Other'         => 'Other (specify in label)',
					],
					'ui' => 1,
				],
				[
					'key'         => 'field_provider_badge',
					'label'       => 'Badge Label',
					'name'        => 'badge',
					'type'        => 'text',
					'placeholder' => 'Best for bKash',
					'maxlength'   => 30,
				],
				[
					'key'         => 'field_provider_note',
					'label'       => 'Editorial Note',
					'name'        => 'note',
					'type'        => 'text',
					'placeholder' => 'Wise offers the mid-market rate with a small transparent fee.',
				],
				[
					'key'         => 'field_provider_affiliate_url',
					'label'       => 'Affiliate / Referral URL',
					'name'        => 'affiliate_url',
					'type'        => 'url',
					'placeholder' => 'https://wise.com/invite/...',
				],
			],
		],

		// ── Related corridors ─────────────────────────────────────────────────
		[
			'key'           => 'field_corridor_related',
			'label'         => 'Related Corridors',
			'name'          => 'related_corridors',
			'type'          => 'relationship',
			'instructions'  => 'Select 2-3 related corridor pages to show in the "You might also like" section.',
			'post_type'     => [ 'corridor' ],
			'filters'       => [ 'search' ],
			'min'           => 0,
			'max'           => 3,
			'return_format' => 'post_object',
		],

	],
	'location' => [
		[
			[
				'param'    => 'post_type',
				'operator' => '==',
				'value'    => 'corridor',
			],
		],
	],
	'menu_order'            => 0,
	'position'              => 'normal',
	'style'                 => 'seamless',
	'label_placement'       => 'top',
	'instruction_placement' => 'field',
	'active'                => true,
] );


// ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
// 2. HOMEPAGE SETTINGS FIELDS
// ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
acf_add_local_field_group( [
	'key'    => 'group_takapath_homepage',
	'title'  => 'Homepage Settings',
	'fields' => [

		[
			'key'           => 'field_home_hero_heading',
			'label'         => 'Hero Heading',
			'name'          => 'hero_heading',
			'type'          => 'text',
			'default_value' => 'Best Way to Send Money to Bangladesh -- Compare Rates & Fees',
			'maxlength'     => 120,
		],

		[
			'key'           => 'field_home_hero_tagline',
			'label'         => 'Hero Tagline',
			'name'          => 'hero_tagline',
			'type'          => 'text',
			'default_value' => 'Trusted comparison for Bangladeshis worldwide. Updated rates from real providers -- bank deposit, bKash, and cash pickup.',
			'maxlength'     => 200,
		],

		[
			'key'           => 'field_home_default_currency',
			'label'         => 'Default Rate Widget Currency',
			'name'          => 'default_currency',
			'type'          => 'select',
			'instructions'  => 'The currency pre-selected in the homepage rate comparison widget.',
			'choices'       => [
				'GBP' => 'GBP -- British Pound',
				'USD' => 'USD -- US Dollar',
				'EUR' => 'EUR -- Euro',
				'SAR' => 'SAR -- Saudi Riyal',
				'AED' => 'AED -- UAE Dirham',
				'MYR' => 'MYR -- Malaysian Ringgit',
			],
			'default_value' => 'GBP',
			'ui'            => 1,
		],

		[
			'key'           => 'field_home_trust_stats',
			'label'         => 'Trust Bar Stats',
			'name'          => 'trust_stats',
			'type'          => 'repeater',
			'instructions'  => 'Short stats shown in the trust bar below the hero, e.g. "10+ providers compared".',
			'min'           => 2,
			'max'           => 5,
			'layout'        => 'table',
			'button_label'  => 'Add Stat',
			'sub_fields'    => [
				[
					'key'           => 'field_trust_stat_number',
					'label'         => 'Stat / Number',
					'name'          => 'stat',
					'type'          => 'text',
					'placeholder'   => '10+',
					'default_value' => '10+',
				],
				[
					'key'           => 'field_trust_stat_label',
					'label'         => 'Label',
					'name'          => 'label',
					'type'          => 'text',
					'placeholder'   => 'Providers compared',
					'default_value' => 'Providers compared',
				],
			],
		],

		[
			'key'           => 'field_home_announcement',
			'label'         => 'Announcement Bar (optional)',
			'name'          => 'announcement',
			'type'          => 'text',
			'instructions'  => 'Optional single-line announcement shown at the top of the homepage. Leave blank to hide.',
			'placeholder'   => 'Bangladesh remittances hit a record $33B in 2025',
		],

	],
	'location' => [
		[
			[
				'param'    => 'page_type',
				'operator' => '==',
				'value'    => 'front_page',
			],
		],
	],
	'menu_order'            => 0,
	'position'              => 'normal',
	'style'                 => 'seamless',
	'active'                => true,
] );


// ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
// 3. GLOBAL PROVIDER SETTINGS (Options Page)
// Requires ACF PRO for options pages, but the field group is defined here
// so it's ready when ACF PRO is activated. Falls back gracefully.
// ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
if ( function_exists( 'acf_add_options_page' ) ) {
	acf_add_options_page( [
		'page_title'  => 'TakaPath Global Settings',
		'menu_title'  => 'TakaPath',
		'menu_slug'   => 'takapath-global',
		'capability'  => 'manage_options',
		'icon_url'    => 'dashicons-money-alt',
		'redirect'    => false,
	] );

	acf_add_local_field_group( [
		'key'    => 'group_takapath_global',
		'title'  => 'Global Provider Settings',
		'fields' => [

			[
				'key'           => 'field_global_disclaimer',
				'label'         => 'Global Rates Disclaimer',
				'name'          => 'global_disclaimer',
				'type'          => 'textarea',
				'rows'          => 3,
				'default_value' => 'Rates shown are indicative and updated hourly. Actual rates may vary. Always confirm the final rate on the provider\'s website before sending.',
			],

			[
				'key'           => 'field_global_affiliate_disclosure',
				'label'         => 'Affiliate Disclosure',
				'name'          => 'affiliate_disclosure',
				'type'          => 'textarea',
				'rows'          => 2,
				'default_value' => 'TakaPath may earn a commission when you use links on this site. This does not affect our comparisons -- we always show all available providers.',
			],

			[
				'key'           => 'field_global_provider_count',
				'label'         => 'Number of Providers Compared (for trust signals)',
				'name'          => 'provider_count',
				'type'          => 'number',
				'default_value' => 10,
				'min'           => 1,
				'max'           => 50,
			],

			[
				'key'           => 'field_global_last_verified',
				'label'         => 'Rates Last Manually Verified',
				'name'          => 'last_verified',
				'type'          => 'date_picker',
				'display_format' => 'd F Y',
				'return_format'  => 'Y-m-d',
			],

		],
		'location' => [
			[
				[
					'param'    => 'options_page',
					'operator' => '==',
					'value'    => 'takapath-global',
				],
			],
		],
		'active' => true,
	] );
}
