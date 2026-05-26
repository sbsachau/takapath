<?php
/**
 * TakaPath — Corridor Post Seeder
 *
 * Creates all 8 priority corridor posts with ACF meta,
 * FAQ items, receive methods, intro paragraphs, and SEO fields.
 *
 * HOW TO RUN (two options):
 *
 * Option A — WP-CLI (recommended, run from WordPress root):
 *   wp eval-file path/to/seed-corridors.php
 *
 * Option B — WP Admin one-time importer page:
 *   Add to functions.php temporarily:
 *     add_action('admin_init', function() {
 *         if ( isset($_GET['takapath_seed']) && current_user_can('manage_options') ) {
 *             require_once get_stylesheet_directory() . '/../../scripts/seed-corridors.php';
 *         }
 *     });
 *   Then visit: /wp-admin/?takapath_seed=1
 *   Remove the add_action after use.
 *
 * SAFE TO RE-RUN: checks for existing slug before inserting.
 * DELETE to reset: wp post delete $(wp post list --post_type=corridor --format=ids) --force
 */

if ( ! defined( 'ABSPATH' ) ) {
    // Allow direct WP-CLI invocation via wp eval-file
    $wp_load = dirname( __FILE__, 3 ) . '/wp-load.php';
    if ( file_exists( $wp_load ) ) {
        require_once $wp_load;
    } else {
        die( "wp-load.php not found. Run via WP-CLI from the WordPress root.\n" );
    }
}

if ( ! function_exists( 'update_field' ) ) {
    die( "ACF is not active. Activate ACF Free or ACF Pro first.\n" );
}

// =============================================================================
// CORRIDOR DATA
// Each entry maps directly to ACF fields in config/acf-field-groups.php
// =============================================================================
$corridors = [

    // 1. UK → Bangladesh (GBP) — build first, strongest English-language SEO
    [
        'title'          => 'Send Money from UK to Bangladesh',
        'slug'           => 'uk-to-bangladesh',
        'menu_order'     => 3,
        'from_currency'  => 'GBP',
        'flag_emoji'     => '🇬🇧',
        'route_label'    => 'UK → Bangladesh',
        'source_country' => 'United Kingdom',
        'default_amount' => 500,
        'receive_methods'=> ['bank_deposit', 'bkash', 'nagad', 'rocket', 'cash_pickup'],
        'intro_en'       => 'Compare the best GBP to BDT exchange rates and fees from Wise, Remitly, Western Union, and bKash HomeSend. Find the fastest, cheapest way to send money from the UK to Bangladesh — direct to a bank account, bKash, or Nagad wallet.',
        'intro_bn'       => 'যুক্তরাজ্য থেকে বাংলাদেশে টাকা পাঠাতে Wise, Remitly, Western Union এবং bKash HomeSend-এর সর্বোত্তম হার তুলনা করুন। ব্যাংক, bKash অথবা Nagad — যেকোনো মাধ্যমে সবচেয়ে সস্তায় পাঠান।',
        'faqs'           => [
            [
                'question' => 'What is the cheapest way to send money from UK to Bangladesh?',
                'answer'   => 'Based on current rates, Wise and Remitly consistently offer the lowest fees and best GBP to BDT exchange rates for UK to Bangladesh transfers. Wise typically charges around 0.5–1.0% in fees with mid-market exchange rates, while Remitly offers competitive rates for bank deposits and bKash delivery.',
            ],
            [
                'question' => 'How long does a UK to Bangladesh transfer take?',
                'answer'   => 'Most digital providers (Wise, Remitly) deliver within minutes to 24 hours for bKash and Nagad transfers. Bank deposits typically take 1–3 business days. Western Union and MoneyGram cash pickup is usually available within minutes once the transfer is sent.',
            ],
            [
                'question' => 'Can I send money from the UK directly to a bKash account in Bangladesh?',
                'answer'   => 'Yes. bKash HomeSend accepts international transfers from 136 countries including the UK. Remitly, Western Union, and several other providers also support direct delivery to bKash and Nagad mobile wallets.',
            ],
            [
                'question' => 'What is the GBP to BDT exchange rate today?',
                'answer'   => 'The mid-market GBP to BDT rate changes daily. TakaPath displays live rates from major providers updated every hour. The rate you receive will be slightly lower than the mid-market rate as providers apply a small margin.',
            ],
            [
                'question' => 'Is there a limit on how much I can send from the UK to Bangladesh?',
                'answer'   => 'Limits vary by provider. Wise allows up to £1,000,000 per transfer for verified accounts. Remitly limits vary by tier but are typically £20,000–£30,000 per transaction. Western Union limits depend on the payment method used.',
            ],
        ],
    ],

    // 2. Saudi Arabia → Bangladesh (SAR) — highest remittance volume corridor
    [
        'title'          => 'Send Money from Saudi Arabia to Bangladesh',
        'slug'           => 'saudi-arabia-to-bangladesh',
        'menu_order'     => 1,
        'from_currency'  => 'SAR',
        'flag_emoji'     => '🇸🇦',
        'route_label'    => 'Saudi Arabia → Bangladesh',
        'source_country' => 'Saudi Arabia',
        'default_amount' => 1000,
        'receive_methods'=> ['bank_deposit', 'bkash', 'nagad', 'cash_pickup'],
        'intro_en'       => 'Saudi Arabia is the single largest source of remittances to Bangladesh. Compare SAR to BDT exchange rates from Al Rajhi Bank, Western Union, Remitly, Wise, and bKash HomeSend to find the best rate for your transfer.',
        'intro_bn'       => 'সৌদি আরব থেকে বাংলাদেশে সবচেয়ে বেশি রেমিট্যান্স আসে। Al Rajhi, Western Union, Remitly এবং bKash HomeSend-এর SAR থেকে BDT হার তুলনা করুন এবং সাশ্রয়ী প্রদায়ক বেছে নিন।',
        'faqs'           => [
            [
                'question' => 'What is the best way to send money from Saudi Arabia to Bangladesh?',
                'answer'   => 'Al Rajhi Bank, Western Union, and Remitly are among the most popular options for Saudi Arabia to Bangladesh transfers. Al Rajhi Bank is widely used by Bangladeshi workers in Saudi Arabia due to its physical branches. Online providers like Remitly and Wise often offer better exchange rates.',
            ],
            [
                'question' => 'How long does a Saudi Arabia to Bangladesh transfer take?',
                'answer'   => 'Transfers via Al Rajhi Bank typically arrive within 1–2 business days. Online providers like Remitly deliver to bKash and bank accounts within minutes to 24 hours. Western Union cash pickup is usually instant.',
            ],
            [
                'question' => 'Can I send money from Saudi Arabia to bKash in Bangladesh?',
                'answer'   => 'Yes. bKash HomeSend supports transfers from Saudi Arabia through multiple partner providers. Western Union and Remitly also support direct delivery to bKash mobile wallets in Bangladesh.',
            ],
            [
                'question' => 'What is the SAR to BDT rate today?',
                'answer'   => 'The SAR to BDT mid-market rate fluctuates daily. TakaPath updates rates every hour directly from provider APIs. Always compare before sending — the spread between best and worst provider can be 2–3% on this corridor.',
            ],
        ],
    ],

    // 3. UAE → Bangladesh (AED)
    [
        'title'          => 'Send Money from UAE to Bangladesh',
        'slug'           => 'uae-to-bangladesh',
        'menu_order'     => 2,
        'from_currency'  => 'AED',
        'flag_emoji'     => '🇦🇪',
        'route_label'    => 'UAE → Bangladesh',
        'source_country' => 'United Arab Emirates',
        'default_amount' => 1000,
        'receive_methods'=> ['bank_deposit', 'bkash', 'nagad', 'cash_pickup'],
        'intro_en'       => 'The UAE is the second-largest remittance source to Bangladesh. Compare AED to BDT rates from Exchange houses, Remitly, Wise, and Western Union to find the best deal for your bank deposit, bKash, or Nagad transfer.',
        'intro_bn'       => 'ইমারাত থেকে বাংলাদেশে AED থেকে BDT হার তুলনা করুন। Remitly, Wise, Western Union এবং UAE Exchange-এর মধ্যে সর্বোত্তম হারটি বেছে নিন।',
        'faqs'           => [
            [
                'question' => 'What is the best exchange house for UAE to Bangladesh transfers?',
                'answer'   => 'UAE Exchange, Al Ansari Exchange, and Lulu Exchange are popular physical exchange houses. For online transfers, Wise and Remitly typically offer better AED to BDT rates with lower fees than traditional exchange houses.',
            ],
            [
                'question' => 'How long does a UAE to Bangladesh transfer take?',
                'answer'   => 'Online providers (Wise, Remitly) deliver to bKash and bank accounts within minutes to a few hours. Exchange house transfers are typically same-day or next-day. Western Union cash pickup is instant at participating agents.',
            ],
            [
                'question' => 'Can I send money from UAE to Nagad in Bangladesh?',
                'answer'   => 'Yes. Several providers support direct delivery to Nagad mobile wallets in Bangladesh from the UAE, including Remitly and Western Union. bKash HomeSend is also available from UAE.',
            ],
        ],
    ],

    // 4. Malaysia → Bangladesh (MYR)
    [
        'title'          => 'Send Money from Malaysia to Bangladesh',
        'slug'           => 'malaysia-to-bangladesh',
        'menu_order'     => 4,
        'from_currency'  => 'MYR',
        'flag_emoji'     => '🇲🇾',
        'route_label'    => 'Malaysia → Bangladesh',
        'source_country' => 'Malaysia',
        'default_amount' => 1000,
        'receive_methods'=> ['bank_deposit', 'bkash', 'nagad', 'cash_pickup'],
        'intro_en'       => 'Malaysia is home to a large Bangladeshi migrant workforce. Compare MYR to BDT exchange rates and fees from Wise, Western Union, Remitly, and local Malaysian remittance services to send money home affordably.',
        'intro_bn'       => 'মালয়েশিয়া থেকে MYR থেকে BDT হার তুলনা করুন। ব্যাংক ডিপোজিট, bKash অথবা Nagad — স্বল্প খরচে বাড়িতে টাকা পাঠান।',
        'faqs'           => [
            [
                'question' => 'What is the cheapest way to send money from Malaysia to Bangladesh?',
                'answer'   => 'Wise and Remitly typically offer the best MYR to BDT rates online. Local Malaysian remittance services like Merchantrade and KFH also serve this corridor and are widely used by migrant workers.',
            ],
            [
                'question' => 'How long does a Malaysia to Bangladesh transfer take?',
                'answer'   => 'Digital transfers via Wise or Remitly arrive within minutes to 24 hours. Bank-to-bank transfers may take 1–3 business days. Cash pickup via Western Union is typically instant.',
            ],
            [
                'question' => 'Is Wise available for Malaysia to Bangladesh transfers?',
                'answer'   => 'Yes, Wise supports MYR to BDT transfers from Malaysia. It offers the mid-market exchange rate with a small transparent fee, making it one of the cheapest options for this corridor.',
            ],
        ],
    ],

    // 5. USA → Bangladesh (USD)
    [
        'title'          => 'Send Money from USA to Bangladesh',
        'slug'           => 'usa-to-bangladesh',
        'menu_order'     => 5,
        'from_currency'  => 'USD',
        'flag_emoji'     => '🇺🇸',
        'route_label'    => 'USA → Bangladesh',
        'source_country' => 'United States',
        'default_amount' => 500,
        'receive_methods'=> ['bank_deposit', 'bkash', 'nagad', 'rocket', 'cash_pickup'],
        'intro_en'       => 'Compare the best USD to BDT exchange rates from Remitly, Wise, Western Union, and Xoom for sending money from the USA to Bangladesh. Get live rates for bank deposit, bKash, Nagad, and cash pickup options.',
        'intro_bn'       => 'আমেরিকা থেকে USD থেকে BDT হার তুলনা করুন। Remitly, Wise, Xoom এবং Western Union থেকে সর্বোত্তম হার বেছে নিন।',
        'faqs'           => [
            [
                'question' => 'What is the best app to send money from USA to Bangladesh?',
                'answer'   => 'Remitly and Wise are consistently rated among the best for USA to Bangladesh transfers. Remitly offers competitive rates and fast delivery to bKash. Wise provides mid-market rates with transparent fees. Xoom (by PayPal) is another popular option with wide US coverage.',
            ],
            [
                'question' => 'How long does a USA to Bangladesh transfer take?',
                'answer'   => 'Remitly Express delivers to bKash within minutes. Standard bank deposit transfers take 3–5 business days. Wise transfers typically arrive within 1–2 business days. Western Union cash pickup is instant at local agents.',
            ],
            [
                'question' => 'What is the USD to BDT rate today?',
                'answer'   => 'The USD to BDT mid-market rate is updated daily. TakaPath shows live provider rates updated every hour. Provider rates are typically 1–3% below the mid-market rate depending on the provider and transfer method.',
            ],
            [
                'question' => 'Can I send money from the USA directly to a bKash account?',
                'answer'   => 'Yes. Remitly, Western Union, and bKash HomeSend all support direct delivery to bKash accounts in Bangladesh from the United States.',
            ],
        ],
    ],

    // 6. Oman → Bangladesh (OMR)
    [
        'title'          => 'Send Money from Oman to Bangladesh',
        'slug'           => 'oman-to-bangladesh',
        'menu_order'     => 6,
        'from_currency'  => 'OMR',
        'flag_emoji'     => '🇴🇲',
        'route_label'    => 'Oman → Bangladesh',
        'source_country' => 'Oman',
        'default_amount' => 200,
        'receive_methods'=> ['bank_deposit', 'bkash', 'nagad', 'cash_pickup'],
        'intro_en'       => 'Compare OMR to BDT exchange rates from Lulu Exchange, Western Union, Remitly, and other providers for sending money from Oman to Bangladesh. Find the best rate for bank deposit, bKash, and cash pickup.',
        'intro_bn'       => 'ওমান থেকে OMR থেকে BDT হার তুলনা করুন। Lulu Exchange, Western Union এবং Remitly-এর মধ্যে সর্বোত্তম হার বেছে নিন।',
        'faqs'           => [
            [
                'question' => 'What is the best way to send money from Oman to Bangladesh?',
                'answer'   => 'Lulu Exchange and Muscat Exchange are widely used physical options in Oman. Online providers like Remitly often offer better OMR to BDT rates. Western Union has a strong agent network in Oman for cash sends.',
            ],
            [
                'question' => 'How long does a transfer from Oman to Bangladesh take?',
                'answer'   => 'Most providers deliver within 1–3 business days for bank transfers. bKash and Nagad deliveries via Remitly or Western Union are typically within minutes to 24 hours.',
            ],
        ],
    ],

    // 7. Italy → Bangladesh (EUR)
    [
        'title'          => 'Send Money from Italy to Bangladesh',
        'slug'           => 'italy-to-bangladesh',
        'menu_order'     => 7,
        'from_currency'  => 'EUR',
        'flag_emoji'     => '🇮🇹',
        'route_label'    => 'Italy → Bangladesh',
        'source_country' => 'Italy',
        'default_amount' => 500,
        'receive_methods'=> ['bank_deposit', 'bkash', 'nagad', 'cash_pickup'],
        'intro_en'       => 'Italy has one of Europe\'s largest Bangladeshi communities. Compare EUR to BDT exchange rates from Wise, Remitly, Western Union, and MoneyGram to find the best rate for sending money from Italy to Bangladesh.',
        'intro_bn'       => 'ইতালি থেকে EUR থেকে BDT হার তুলনা করুন। Wise, Remitly এবং Western Union-এর মধ্যে সবসেরা বিকল্প বেছে নিন।',
        'faqs'           => [
            [
                'question' => 'What is the cheapest way to send money from Italy to Bangladesh?',
                'answer'   => 'Wise consistently offers the best EUR to BDT rates from Italy with low, transparent fees. Remitly is another strong option for fast delivery to bKash and bank accounts. Western Union has wide agent coverage in Italy for cash sends.',
            ],
            [
                'question' => 'How long does a transfer from Italy to Bangladesh take?',
                'answer'   => 'Wise and Remitly deliver to bKash and bank accounts within minutes to 24 hours. Western Union cash pickup is instant. Bank-to-bank SWIFT transfers may take 3–5 business days.',
            ],
        ],
    ],

    // 8. Kuwait → Bangladesh (KWD)
    [
        'title'          => 'Send Money from Kuwait to Bangladesh',
        'slug'           => 'kuwait-to-bangladesh',
        'menu_order'     => 8,
        'from_currency'  => 'KWD',
        'flag_emoji'     => '🇰🇼',
        'route_label'    => 'Kuwait → Bangladesh',
        'source_country' => 'Kuwait',
        'default_amount' => 100,
        'receive_methods'=> ['bank_deposit', 'bkash', 'nagad', 'cash_pickup'],
        'intro_en'       => 'Compare KWD to BDT exchange rates from Remitly, Western Union, and Kuwait-based exchange houses for sending money from Kuwait to Bangladesh. KWD has a high exchange value — even small amounts convert to significant BDT.',
        'intro_bn'       => 'কুয়েত থেকে KWD থেকে BDT হার তুলনা করুন। কুয়েতি দিনারের মান বেশি হওয়ায় অল্প পরিমাণ পাঠালেও পরিবার ভালো সাহায্য হয়।',
        'faqs'           => [
            [
                'question' => 'What is the best way to send money from Kuwait to Bangladesh?',
                'answer'   => 'Kuwait Finance House (KFH), Western Union, and Remitly are popular for Kuwait to Bangladesh transfers. Online providers like Remitly typically offer better KWD to BDT rates than traditional exchange houses.',
            ],
            [
                'question' => 'What is the KWD to BDT rate today?',
                'answer'   => 'KWD is one of the world\'s highest-value currencies. The KWD to BDT rate is updated hourly on TakaPath. As a guide, 1 KWD typically converts to approximately 250–280 BDT depending on the provider.',
            ],
        ],
    ],

];

// =============================================================================
// INSERT POSTS
// =============================================================================
$inserted = 0;
$skipped  = 0;

foreach ( $corridors as $c ) {

    // Skip if a post with this slug already exists
    $existing = get_page_by_path( $c['slug'], OBJECT, 'corridor' );
    if ( $existing ) {
        echo "SKIP  [{$c['slug']}] already exists (ID {$existing->ID})\n";
        $skipped++;
        continue;
    }

    $post_id = wp_insert_post( [
        'post_title'   => $c['title'],
        'post_name'    => $c['slug'],
        'post_type'    => 'corridor',
        'post_status'  => 'publish',
        'post_content' => '',
        'menu_order'   => $c['menu_order'],
    ], true );

    if ( is_wp_error( $post_id ) ) {
        echo "ERROR [{$c['slug']}]: " . $post_id->get_error_message() . "\n";
        continue;
    }

    // Core ACF fields
    update_field( 'from_currency',       $c['from_currency'],  $post_id );
    update_field( 'flag_emoji',          $c['flag_emoji'],     $post_id );
    update_field( 'route_label',         $c['route_label'],    $post_id );
    update_field( 'source_country',      $c['source_country'], $post_id );
    update_field( 'default_amount',      $c['default_amount'], $post_id );
    update_field( 'intro_paragraph',     $c['intro_en'],       $post_id );
    update_field( 'intro_paragraph_bn',  $c['intro_bn'],       $post_id );
    update_field( 'receive_methods',     $c['receive_methods'],$post_id );

    // FAQ repeater
    update_field( 'faq_items', $c['faqs'], $post_id );

    echo "OK    [{$c['slug']}] created as post ID {$post_id}\n";
    $inserted++;
}

echo "\nDone. Inserted: {$inserted} | Skipped: {$skipped}\n";
