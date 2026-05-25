<?php
/**
 * ACF Field Group: Corridor Guide
 *
 * Version-controlled definition of all custom fields used on Corridor
 * Guide posts (CPT: corridor). Loaded via acf/include_fields hook so it
 * auto-registers on every environment — no manual field setup in WP Admin.
 *
 * Edit this file to add/change fields, then deploy. ACF Pro 6+ is required.
 *
 * Field groups:
 *   takapath_corridor_core      — core corridor data
 *   takapath_corridor_seo       — SEO overrides (used by Rank Math filters)
 *   takapath_corridor_content   — editorial / FAQ fields
 */

declare( strict_types=1 );

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'acf_add_local_field_group' ) ) {
	return; // ACF Pro not active — bail silently.
}

// =============================================================================
// GROUP 1 — CORE CORRIDOR DATA
// =============================================================================
acf_add_local_field_group( [
	'key'      => 'group_takapath_corridor_core',
	'title'    => 'Corridor — Core Data',
	'fields'   => [

		// --- Source country ---
		[
			'key'           => 'field_corridor_from_country',
			'label'         => 'Source country',
			'name'          => 'from_country',
			'type'          => 'text',
			'instructions'  => 'Full country name, e.g. United Kingdom',
			'required'      => 1,
			'placeholder'   => 'United Kingdom',
		],

		// --- Source currency (ISO 4217) ---
		[
			'key'           => 'field_corridor_from_currency',
			'label'         => 'Source currency (ISO 4217)',
			'name'          => 'from_currency',
			'type'          => 'select',
			'instructions'  => 'Currency used in the shortcode [takapath_rates from="..."]',
			'required'      => 1,
			'choices'       => [
				'GBP' => '🇬🇧 GBP — British Pounds',
				'USD' => '🇺🇸 USD — US Dollars',
				'EUR' => '🇪🇺 EUR — Euros',
				'SAR' => '🇸🇦 SAR — Saudi Riyals',
				'AED' => '🇦🇪 AED — UAE Dirhams',
				'MYR' => '🇲🇾 MYR — Malaysian Ringgit',
				'OMR' => '🇴🇲 OMR — Omani Rials',
				'KWD' => '🇰🇼 KWD — Kuwaiti Dinars',
				'QAR' => '🇶🇦 QAR — Qatari Riyals',
				'SGD' => '🇸🇬 SGD — Singapore Dollars',
				'CAD' => '🇨🇦 CAD — Canadian Dollars',
				'AUD' => '🇦🇺 AUD — Australian Dollars',
				'ITL' => '🇮🇹 ITL — Italy (EUR)',
			],
			'default_value' => 'GBP',
			'return_format' => 'value',
		],

		// --- Flag emoji ---
		[
			'key'           => 'field_corridor_flag_emoji',
			'label'         => 'Flag emoji',
			'name'          => 'flag_emoji',
			'type'          => 'text',
			'instructions'  => 'Source country flag emoji, e.g. 🇬🇧 — used in page headings and cards',
			'required'      => 0,
			'maxlength'     => 10,
		],

		// --- Default send amount ---
		[
			'key'           => 'field_corridor_default_amount',
			'label'         => 'Default send amount',
			'name'          => 'default_amount',
			'type'          => 'number',
			'instructions'  => 'Default amount passed to the comparison widget. Typical: 500 for USD, 1000 for GBP.',
			'required'      => 1,
			'default_value' => 1000,
			'min'           => 1,
			'max'           => 100000,
			'step'          => 1,
		],

		// --- Transfer route label (displayed on cards + hero) ---
		[
			'key'           => 'field_corridor_route_label',
			'label'         => 'Route label',
			'name'          => 'route_label',
			'type'          => 'text',
			'instructions'  => 'Short label for cards, e.g. "UK → Bangladesh" or "Saudi Arabia → Bangladesh"',
			'required'      => 0,
			'placeholder'   => 'UK → Bangladesh',
		],

		// --- Remittance volume note (editorial, shown in hero) ---
		[
			'key'           => 'field_corridor_volume_note',
			'label'         => 'Remittance volume note',
			'name'          => 'volume_note',
			'type'          => 'text',
			'instructions'  => 'Optional stat shown below hero heading, e.g. "Over $2.1B sent from the USA to Bangladesh in 2025"',
			'required'      => 0,
		],

		// --- Receive methods available on this corridor ---
		[
			'key'           => 'field_corridor_receive_methods',
			'label'         => 'Receive methods',
			'name'          => 'receive_methods',
			'type'          => 'checkbox',
			'instructions'  => 'Tick all receive methods available for this corridor — shown as pills on the page.',
			'required'      => 0,
			'choices'       => [
				'bank_deposit'  => 'Bank deposit',
				'bkash'         => 'bKash mobile wallet',
				'nagad'         => 'Nagad mobile wallet',
				'rocket'        => 'Rocket / DBBL',
				'cash_pickup'   => 'Cash pickup',
				'home_delivery' => 'Home delivery',
			],
			'layout'        => 'horizontal',
			'return_format' => 'value',
		],

		// --- Related corridors (links to other corridor posts) ---
		[
			'key'           => 'field_corridor_related',
			'label'         => 'Related corridors',
			'name'          => 'related_corridors',
			'type'          => 'relationship',
			'instructions'  => 'Link to up to 4 other corridor guides shown as "Also compare" cards.',
			'required'      => 0,
			'post_type'     => [ 'corridor' ],
			'min'           => 0,
			'max'           => 4,
			'return_format' => 'id',
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
	'style'                 => 'default',
	'label_placement'       => 'top',
	'instruction_placement' => 'label',
	'active'                => true,
] );


// =============================================================================
// GROUP 2 — SEO OVERRIDES
// Rank Math reads these via filters in functions.php to override auto-generated
// meta title, description, and canonical URL per corridor page.
// =============================================================================
acf_add_local_field_group( [
	'key'    => 'group_takapath_corridor_seo',
	'title'  => 'Corridor — SEO Overrides',
	'fields' => [

		[
			'key'          => 'field_corridor_seo_title',
			'label'        => 'SEO title override',
			'name'         => 'seo_title',
			'type'         => 'text',
			'instructions' => 'Overrides the Rank Math title. Leave blank to use the auto-pattern: "Send Money from {country} to Bangladesh – Compare {currency} to BDT Rates | TakaPath"',
			'required'     => 0,
			'maxlength'    => 65,
			'placeholder'  => 'Send Money from UK to Bangladesh – Compare GBP to BDT Rates | TakaPath',
		],

		[
			'key'          => 'field_corridor_seo_description',
			'label'        => 'Meta description override',
			'name'         => 'seo_description',
			'type'         => 'textarea',
			'instructions' => 'Overrides the Rank Math meta description. Leave blank for auto-generated. Max 155 chars.',
			'required'     => 0,
			'maxlength'    => 155,
			'rows'         => 3,
		],

		[
			'key'          => 'field_corridor_canonical',
			'label'        => 'Canonical URL override',
			'name'         => 'canonical_url',
			'type'         => 'url',
			'instructions' => 'Only set this if you need to point to a non-default canonical. Leave blank in almost all cases.',
			'required'     => 0,
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
	'menu_order'      => 10,
	'position'        => 'normal',
	'style'           => 'default',
	'active'          => true,
] );


// =============================================================================
// GROUP 3 — EDITORIAL / FAQ
// =============================================================================
acf_add_local_field_group( [
	'key'    => 'group_takapath_corridor_content',
	'title'  => 'Corridor — Editorial & FAQ',
	'fields' => [

		// --- Intro paragraph (shown above comparison table) ---
		[
			'key'          => 'field_corridor_intro',
			'label'        => 'Intro paragraph',
			'name'         => 'intro_text',
			'type'         => 'wysiwyg',
			'instructions' => '1-2 sentences shown above the comparison table. Plain, trust-building copy.',
			'required'     => 0,
			'tabs'         => 'all',
			'toolbar'      => 'basic',
			'media_upload' => 0,
			'delay'        => 0,
		],

		// --- FAQ repeater ---
		[
			'key'          => 'field_corridor_faq',
			'label'        => 'FAQ items',
			'name'         => 'faq_items',
			'type'         => 'repeater',
			'instructions' => 'Add 3-8 frequently asked questions. Used in the FAQ accordion and FAQ schema.',
			'required'     => 0,
			'min'          => 0,
			'max'          => 12,
			'layout'       => 'block',
			'button_label' => 'Add FAQ item',
			'sub_fields'   => [
				[
					'key'          => 'field_corridor_faq_q',
					'label'        => 'Question',
					'name'         => 'question',
					'type'         => 'text',
					'required'     => 1,
					'placeholder'  => 'What is the best way to send money from UK to Bangladesh?',
				],
				[
					'key'          => 'field_corridor_faq_a',
					'label'        => 'Answer',
					'name'         => 'answer',
					'type'         => 'textarea',
					'required'     => 1,
					'rows'         => 4,
				],
			],
		],

		// --- Expert tip (pull-quote style callout) ---
		[
			'key'          => 'field_corridor_tip',
			'label'        => 'Expert tip',
			'name'         => 'expert_tip',
			'type'         => 'text',
			'instructions' => 'Short tip shown as a highlighted callout, e.g. "Sending on weekdays often gives a better rate than weekends."',
			'required'     => 0,
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
	'menu_order'      => 20,
	'position'        => 'normal',
	'style'           => 'default',
	'active'          => true,
] );
