<?php
/**
 * TakaPath — ACF Field Group Definitions
 *
 * HOW TO USE:
 *   These field groups are registered via PHP (no DB import needed).
 *   The file is already required from functions.php via:
 *     add_action( 'acf/init', function() { require_once ... } );
 *
 * Field Groups:
 *   1. Corridor Meta      — CPT 'corridor'
 *   2. Homepage Options   — page template 'TakaPath Homepage'
 */

if ( ! function_exists( 'acf_add_local_field_group' ) ) {
	return;
}

// ============================================================
// 1. CORRIDOR META
// ============================================================
acf_add_local_field_group( [
	'key'   => 'group_corridor_meta',
	'title' => 'Corridor Meta',
	'fields' => [

		[
			'key'           => 'field_from_currency',
			'label'         => 'From Currency',
			'name'          => 'from_currency',
			'type'          => 'select',
			'instructions'  => 'ISO 4217 currency code for the source country.',
			'required'      => 1,
			'choices'       => [
				'SAR' => 'SAR — Saudi Riyal',
				'AED' => 'AED — UAE Dirham',
				'GBP' => 'GBP — British Pound',
				'MYR' => 'MYR — Malaysian Ringgit',
				'USD' => 'USD — US Dollar',
				'OMR' => 'OMR — Omani Rial',
				'EUR' => 'EUR — Euro',
				'KWD' => 'KWD — Kuwaiti Dinar',
				'QAR' => 'QAR — Qatari Riyal',
				'SGD' => 'SGD — Singapore Dollar',
				'BHD' => 'BHD — Bahraini Dinar',
				'CAD' => 'CAD — Canadian Dollar',
				'AUD' => 'AUD — Australian Dollar',
			],
			'default_value' => 'GBP',
			'allow_null'    => 0,
			'ui'            => 1,
		],

		[
			'key'         => 'field_flag_emoji',
			'label'       => 'Flag Emoji',
			'name'        => 'flag_emoji',
			'type'        => 'text',
			'instructions'=> 'Single flag emoji, e.g. 🇬🇧',
			'required'    => 1,
			'maxlength'   => 4,
			'placeholder' => '🇬🇧',
		],

		[
			'key'         => 'field_route_label',
			'label'       => 'Route Label',
			'name'        => 'route_label',
			'type'        => 'text',
			'instructions'=> 'Human-readable label, e.g. "UK → Bangladesh"',
			'required'    => 1,
			'placeholder' => 'UK → Bangladesh',
		],

		[
			'key'         => 'field_source_country',
			'label'       => 'Source Country (English)',
			'name'        => 'source_country',
			'type'        => 'text',
			'instructions'=> 'Full country name used in headings and meta, e.g. "United Kingdom"',
			'required'    => 1,
			'placeholder' => 'United Kingdom',
		],

		[
			'key'           => 'field_default_amount',
			'label'         => 'Default Send Amount',
			'name'          => 'default_amount',
			'type'          => 'number',
			'instructions'  => 'Amount pre-loaded into the comparison widget (source currency). Use a typical round number for that corridor.',
			'required'      => 1,
			'default_value' => 1000,
			'min'           => 50,
			'max'           => 50000,
			'step'          => 50,
		],

		[
			'key'         => 'field_intro_paragraph',
			'label'       => 'Intro Paragraph (English)',
			'name'        => 'intro_paragraph',
			'type'        => 'textarea',
			'instructions'=> '1–2 sentence intro shown below the H1. Mention source country, BDT, bKash or Nagad for SEO.',
			'required'    => 0,
			'rows'        => 4,
			'maxlength'   => 400,
		],

		[
			'key'         => 'field_intro_paragraph_bn',
			'label'       => 'Intro Paragraph (Bengali)',
			'name'        => 'intro_paragraph_bn',
			'type'        => 'textarea',
			'instructions'=> 'Bengali translation of the intro paragraph.',
			'required'    => 0,
			'rows'        => 4,
			'maxlength'   => 500,
		],

		[
			'key'         => 'field_seo_title_override',
			'label'       => 'SEO Title Override',
			'name'        => 'seo_title_override',
			'type'        => 'text',
			'instructions'=> 'Leave blank — Rank Math auto-templates handle this. Only fill for hand-crafted titles. Max 60 chars.',
			'required'    => 0,
			'maxlength'   => 60,
			'placeholder' => 'Send Money from UK to Bangladesh – GBP to BDT | TakaPath',
		],

		[
			'key'         => 'field_seo_meta_override',
			'label'       => 'SEO Meta Description Override',
			'name'        => 'seo_meta_override',
			'type'        => 'textarea',
			'instructions'=> 'Leave blank unless you need a custom meta. Max 155 chars.',
			'required'    => 0,
			'rows'        => 3,
			'maxlength'   => 155,
		],

		[
			'key'          => 'field_faq_items',
			'label'        => 'FAQ Items',
			'name'         => 'faq_items',
			'type'         => 'repeater',
			'instructions' => 'Q&A pairs shown in the FAQ section and emitted as FAQPage JSON-LD schema. Aim for 3–5 questions.',
			'required'     => 0,
			'min'          => 0,
			'max'          => 10,
			'layout'       => 'block',
			'button_label' => 'Add FAQ item',
			'sub_fields'   => [
				[
					'key'         => 'field_faq_question',
					'label'       => 'Question',
					'name'        => 'question',
					'type'        => 'text',
					'required'    => 1,
					'placeholder' => 'What is the best way to send money from UK to Bangladesh?',
				],
				[
					'key'         => 'field_faq_answer',
					'label'       => 'Answer',
					'name'        => 'answer',
					'type'        => 'textarea',
					'required'    => 1,
					'rows'        => 4,
					'placeholder' => 'Based on current rates, Wise and Remitly typically offer ...',
				],
			],
		],

		[
			'key'           => 'field_receive_methods',
			'label'         => 'Receive Methods Available',
			'name'          => 'receive_methods',
			'type'          => 'checkbox',
			'instructions'  => 'Tick all delivery methods available for this corridor. Shown as pills on the corridor page.',
			'required'      => 0,
			'choices'       => [
				'bank_deposit'  => 'Bank Deposit',
				'bkash'         => 'bKash',
				'nagad'         => 'Nagad',
				'rocket'        => 'Rocket (DBBL)',
				'cash_pickup'   => 'Cash Pickup',
				'home_delivery' => 'Home Delivery',
			],
			'default_value' => [ 'bank_deposit', 'bkash', 'nagad' ],
			'layout'        => 'horizontal',
			'toggle'        => 1,
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
	'description'           => 'Core meta fields for TakaPath corridor comparison pages.',
] );


// ============================================================
// 2. HOMEPAGE OPTIONS
// ============================================================
acf_add_local_field_group( [
	'key'   => 'group_homepage_options',
	'title' => 'Homepage Options',
	'fields' => [

		[
			'key'           => 'field_hero_volume_note',
			'label'         => 'Hero Volume Note',
			'name'          => 'hero_volume_note',
			'type'          => 'text',
			'instructions'  => 'Badge text above the H1. Update annually with Bangladesh Bank figures.',
			'required'      => 0,
			'default_value' => 'Bangladesh received $33 billion in remittances in 2025',
			'maxlength'     => 120,
		],

		[
			'key'           => 'field_hero_default_currency',
			'label'         => 'Hero Widget Default Currency',
			'name'          => 'hero_default_currency',
			'type'          => 'select',
			'instructions'  => 'Currency pre-loaded in the homepage live rates widget.',
			'required'      => 0,
			'choices'       => [
				'GBP' => 'GBP — British Pound',
				'SAR' => 'SAR — Saudi Riyal',
				'AED' => 'AED — UAE Dirham',
				'USD' => 'USD — US Dollar',
				'MYR' => 'MYR — Malaysian Ringgit',
			],
			'default_value' => 'GBP',
			'allow_null'    => 0,
			'ui'            => 1,
		],

		[
			'key'           => 'field_hero_default_amount',
			'label'         => 'Hero Widget Default Amount',
			'name'          => 'hero_default_amount',
			'type'          => 'number',
			'instructions'  => 'Amount pre-loaded in the homepage widget.',
			'required'      => 0,
			'default_value' => 1000,
			'min'           => 50,
			'max'           => 10000,
			'step'          => 50,
		],

		[
			'key'           => 'field_stats_remittance_annual',
			'label'         => 'Stats — Annual Remittance Figure',
			'name'          => 'stats_remittance_annual',
			'type'          => 'text',
			'instructions'  => 'e.g. "$33B". Displayed in the stats strip.',
			'required'      => 0,
			'default_value' => '$33B',
			'maxlength'     => 10,
		],

		[
			'key'           => 'field_stats_monthly_high',
			'label'         => 'Stats — Monthly Record Figure',
			'name'          => 'stats_monthly_high',
			'type'          => 'text',
			'instructions'  => 'e.g. "$2.97B in May 2025 (+32% YoY)".',
			'required'      => 0,
			'default_value' => '$2.97B in May 2025 (+32% YoY)',
			'maxlength'     => 40,
		],

	],
	'location' => [
		[
			[
				'param'    => 'page_template',
				'operator' => '==',
				'value'    => 'page-home.php',
			],
		],
	],
	'menu_order'            => 0,
	'position'              => 'side',
	'style'                 => 'default',
	'label_placement'       => 'top',
	'instruction_placement' => 'label',
	'active'                => true,
	'description'           => 'Editable homepage copy and widget defaults — no code changes needed.',
] );
