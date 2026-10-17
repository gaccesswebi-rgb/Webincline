<?php
/**
 * The seven main services and their sub-services. Every service page, the
 * services hub, the header menu and the footer read from this list.
 * 'status' is 'asked' (the client asked for it) or 'suggested' (added in the
 * sitemap plan). A page links only once its file exists (see routes.php).
 */

declare(strict_types=1);

$services = [
    'seo' => [
        'title'  => 'SEO',
        'name'   => 'SEO & AI search',
        'theme'  => 'fog',
        'visual' => 'serp',
        'line'   => 'Rank where your buyers search.',
        'body'   => 'Technical fixes, content and links that lift you on Google, plus optimisation for the answers ChatGPT, Gemini and Google’s AI Overviews give.',
        'proof'  => '59 → 3,570 visits a month',
        'proof_by' => 'Under Pressure, USA',
        'subs'   => [
            'technical-seo' => ['title' => 'Technical SEO'],
            'on-page-seo'   => ['title' => 'On-page SEO'],
            'off-page-seo'  => ['title' => 'Off-page SEO'],
            'geo'           => ['title' => 'GEO', 'long' => 'Generative engine optimisation'],
            'aio'           => ['title' => 'AIO', 'long' => 'AI Overviews optimisation'],
            'aeo'           => ['title' => 'AEO', 'long' => 'Answer engine optimisation'],
            'local-seo'     => ['title' => 'Local SEO', 'long' => 'Local SEO & Google Business Profile', 'status' => 'suggested'],
            'seo-audit'     => ['title' => 'Free SEO audit', 'status' => 'suggested'],
        ],
    ],
    'paid-ads' => [
        'title'  => 'Paid ads',
        'name'   => 'Paid ads',
        'theme'  => 'ink',
        'visual' => 'ads',
        'line'   => 'Spend that comes back as sales.',
        'body'   => 'Campaigns built around your margins, with tracking from the first click to the sale and new creatives tested every week.',
        'proof'  => '60× return on ad spend',
        'proof_by' => 'Beauty Basket, India',
        'subs'   => [
            'google-ads'   => ['title' => 'Google Ads'],
            'meta-ads'     => ['title' => 'Meta ads', 'long' => 'Meta ads (Facebook & Instagram)'],
            'amazon-ads'   => ['title' => 'Amazon ads'],
            'chatgpt-ads'  => ['title' => 'ChatGPT ads'],
            'linkedin-ads' => ['title' => 'LinkedIn ads'],
            'tiktok-ads'   => ['title' => 'TikTok ads'],
            'youtube-ads'  => ['title' => 'YouTube ads', 'status' => 'suggested'],
        ],
    ],
    'social-media' => [
        'title'  => 'Social media',
        'name'   => 'Social media',
        'theme'  => 'cloud',
        'visual' => 'social',
        'line'   => 'Feeds people actually stop on.',
        'body'   => 'Content calendars, reels, design and community management, plus creators who bring your product to the audiences that buy it.',
        'proof'  => 'New content every week',
        'proof_by' => '7 brands in our portfolio',
        'subs'   => [
            'instagram-facebook'   => ['title' => 'Instagram & Facebook', 'status' => 'suggested'],
            'linkedin'             => ['title' => 'LinkedIn', 'status' => 'suggested'],
            'youtube'              => ['title' => 'YouTube', 'status' => 'suggested'],
            'influencer-marketing' => ['title' => 'Influencer marketing', 'status' => 'suggested'],
            'content-production'   => ['title' => 'Reels & content', 'status' => 'suggested'],
        ],
    ],
    'web-design' => [
        'title'  => 'Web design',
        'name'   => 'Web design & development',
        'theme'  => 'orange',
        'visual' => 'web',
        'line'   => 'Sites that turn visits into enquiries.',
        'body'   => 'Fast, mobile-first websites, landing pages and online stores on the platform that suits you, built with SEO in place and ready for ads from launch day.',
        'proof'  => '6 months free support',
        'proof_by' => 'on every website',
        'subs'   => [
            'shopify'             => ['title' => 'Shopify'],
            'wordpress'           => ['title' => 'WordPress'],
            'wix'                 => ['title' => 'Wix'],
            'framer'              => ['title' => 'Framer'],
            'vibe-coded-websites' => ['title' => 'Vibe-coded websites'],
            'squarespace'         => ['title' => 'Squarespace'],
            'webflow'             => ['title' => 'Webflow'],
            'landing-pages'       => ['title' => 'Landing pages', 'status' => 'suggested'],
            'website-maintenance' => ['title' => 'Website maintenance', 'status' => 'suggested'],
        ],
    ],
    'crm' => [
        'title'  => 'CRM',
        'name'   => 'CRM',
        'theme'  => 'violet',
        'visual' => 'crm',
        'line'   => 'Every lead in one pipeline.',
        'body'   => 'We set up your CRM around how you sell, with pipelines, automations and dashboards, then run it for you month after month.',
        'proof'  => 'Five CRMs, set up and run',
        'proof_by' => 'migration to monthly care',
        'subs'   => [
            'zoho-crm'           => ['title' => 'Zoho CRM'],
            'gohighlevel'        => ['title' => 'GoHighLevel'],
            'hubspot-crm'        => ['title' => 'HubSpot CRM'],
            'salesforce'         => ['title' => 'Salesforce'],
            'microsoft-dynamics' => ['title' => 'Microsoft Dynamics 365'],
        ],
    ],
    'automation' => [
        'title'  => 'Automation',
        'name'   => 'Automation & AI',
        'theme'  => 'pink',
        'visual' => 'ai',
        'line'   => 'Replies in seconds, day and night.',
        'body'   => 'Chatbots and WhatsApp flows that answer, qualify and book calls, plus automations that move data between your apps so nobody copies it by hand.',
        'proof'  => 'Leads answered 24/7',
        'proof_by' => 'chat, WhatsApp and email',
        'subs'   => [
            'zapier'   => ['title' => 'Zapier'],
            'make'     => ['title' => 'Make'],
            'manychat' => ['title' => 'ManyChat'],
            'n8n'      => ['title' => 'n8n'],
        ],
    ],
    'email-marketing' => [
        'title'  => 'Email marketing',
        'name'   => 'Email marketing',
        'theme'  => 'blue',
        'visual' => 'email',
        'line'   => 'Emails that sell while you sleep.',
        'body'   => 'Welcome, abandoned-cart and win-back flows plus regular campaigns, designed, written and tested on the email platform you already use.',
        'proof'  => 'Flows that run on their own',
        'proof_by' => 'welcome, cart and win-back',
        'subs'   => [
            'klaviyo'        => ['title' => 'Klaviyo'],
            'brevo'          => ['title' => 'Brevo', 'long' => 'Brevo (Sendinblue)'],
            'mailchimp'      => ['title' => 'Mailchimp'],
            'hubspot'        => ['title' => 'HubSpot', 'long' => 'HubSpot email marketing'],
            'zoho-campaigns' => ['title' => 'Zoho Campaigns'],
        ],
    ],
];

/* Industries we know, with clients from our case studies and portfolio. */
$industries = [
    ['name' => 'Education & study abroad', 'clients' => 'VIEC Chandigarh · 4S Study Abroad', 'icon' => 'users'],
    ['name' => 'Clinics & healthcare',     'clients' => 'Juvly Aesthetics · Motherhood Chaitanya Hospital', 'icon' => 'heart'],
    ['name' => 'Home services',            'clients' => 'Under Pressure · Yalla Renovation · TV Repair Company', 'icon' => 'pin'],
    ['name' => 'E-commerce',               'clients' => 'Beauty Basket · RSK Fragrance House · Robot My Life', 'icon' => 'chart'],
    ['name' => 'Real estate & construction', 'clients' => 'Richard Miller Custom Homes · Punjab Timbers', 'icon' => 'target'],
    ['name' => 'Automobile',               'clients' => 'Sajjag Automobiles (Piaggio)', 'icon' => 'arrow-up'],
    ['name' => 'Agriculture & science',    'clients' => 'Indogulf BioAg · Balaji Chem Solutions', 'icon' => 'star'],
    ['name' => 'Travel',                   'clients' => 'Arcadia Vacations', 'icon' => 'send'],
];

/* Markets: IANA time zone for the live clock, and clients from our case studies. */
$markets = [
    ['code' => 'IN', 'name' => 'India',          'slug' => 'india',        'tz' => 'Asia/Kolkata',        'clients' => 'RSK Fragrance House · Beauty Basket · VIEC'],
    ['code' => 'CA', 'name' => 'Canada',         'slug' => 'canada',       'tz' => 'America/Toronto',     'clients' => 'TV Repair Company · Indogulf BioAg'],
    ['code' => 'US', 'name' => 'USA',            'slug' => 'usa',          'tz' => 'America/New_York',    'clients' => 'Under Pressure · Juvly Aesthetics'],
    ['code' => 'UK', 'name' => 'United Kingdom', 'slug' => 'uk',           'tz' => 'Europe/London',       'clients' => ''],
    ['code' => 'AE', 'name' => 'UAE',            'slug' => 'uae',          'tz' => 'Asia/Dubai',          'clients' => 'Yalla Renovation · 4S Study Abroad'],
    ['code' => 'AU', 'name' => 'Australia',      'slug' => 'australia',    'tz' => 'Australia/Sydney',    'clients' => 'Robot My Life · MoeBot'],
    ['code' => 'SG', 'name' => 'Singapore',      'slug' => 'singapore',    'tz' => 'Asia/Singapore',      'clients' => ''],
    ['code' => 'ZA', 'name' => 'South Africa',   'slug' => 'south-africa', 'tz' => 'Africa/Johannesburg', 'clients' => ''],
    ['code' => 'AF', 'name' => 'Africa',         'slug' => 'africa',       'tz' => 'Africa/Nairobi',      'clients' => ''],
    ['code' => 'EU', 'name' => 'Europe',         'slug' => 'europe',       'tz' => 'Europe/Berlin',       'clients' => ''],
];

/** URL of a main service or sub-service page. */
function service_path(string $pillar, ?string $sub = null): string
{
    return '/services/' . $pillar . ($sub ? '/' . $sub : '');
}
