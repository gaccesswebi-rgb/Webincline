<?php
/**
 * Content for the homepage sections. Edit text here, not in the templates.
 * Anything in [square brackets] is a placeholder waiting for real details.
 * Every result figure comes from the case studies on the current website.
 */

declare(strict_types=1);

/* Six channels that together cover all twelve services. */
$channels = [
    [
        'slug'     => 'seo',
        'theme'    => 'fog',
        'title'    => 'SEO & AI search',
        'short'    => 'SEO',
        'line'     => 'Rank where your buyers search.',
        'body'     => 'Technical fixes, keyword strategy, content and links that lift you on Google — plus answer-engine optimisation so ChatGPT, Gemini and Perplexity name you too.',
        'includes' => ['Technical SEO audit', 'Keyword & content plan', 'Link building', 'Local SEO & Google Business Profile', 'AEO / GEO for AI answers'],
        'proof'    => 'Top-10 rankings in 3–4 months',
        'visual'   => 'serp',
    ],
    [
        'slug'     => 'paid-ads',
        'theme'    => 'ink',
        'title'    => 'Paid ads',
        'short'    => 'Ads',
        'line'     => 'Spend that comes back as sales.',
        'body'     => 'Google, Meta and YouTube campaigns built around your margins, with tracking from click to sale and new creatives tested every week.',
        'includes' => ['Tracking & account setup', 'Ad copy & creatives', 'Landing page optimisation', 'Weekly optimisation', 'Quality score work'],
        'proof'    => '60× ROAS for Beauty Basket',
        'visual'   => 'ads',
    ],
    [
        'slug'     => 'social-media',
        'theme'    => 'cloud',
        'title'    => 'Social & influencers',
        'short'    => 'Social',
        'line'     => 'Feeds people actually stop on.',
        'body'     => 'Content calendars, reels, design and community management — plus creators and affiliate partners who bring your product to the audiences that buy it.',
        'includes' => ['Content calendar & reels', 'Community management', 'Influencer campaigns', 'Affiliate programmes'],
        'proof'    => 'New content every week',
        'visual'   => 'social',
    ],
    [
        'slug'     => 'web-development',
        'theme'    => 'orange',
        'title'    => 'Websites & stores',
        'short'    => 'Web',
        'line'     => 'Sites that turn visits into enquiries.',
        'body'     => 'Fast, mobile-first websites, landing pages and online stores, built with SEO in place and ready for ads from launch day.',
        'includes' => ['UX & custom design', 'Development', 'E-commerce (Shopify, WooCommerce)', 'Speed & SEO basics', '6 months free support'],
        'proof'    => '6 months free support',
        'visual'   => 'web',
    ],
    [
        'slug'     => 'crm',
        'theme'    => 'violet',
        'title'    => 'CRM',
        'short'    => 'CRM',
        'line'     => 'Every lead in one pipeline.',
        'body'     => 'We set up Zoho, HubSpot or GoHighLevel around how you sell — pipelines, automations and dashboards — then run it for you month after month.',
        'includes' => ['Setup & data migration', 'Pipelines & automations', 'Dashboards & reports', 'Team training', 'Ongoing management'],
        'proof'    => 'Zoho · HubSpot · GoHighLevel',
        'visual'   => 'crm',
    ],
    [
        'slug'     => 'ai-automation',
        'theme'    => 'pink',
        'title'    => 'Automation & AI',
        'short'    => 'AI',
        'line'     => 'Replies in seconds, day and night.',
        'body'     => 'AI chat and WhatsApp agents that answer, qualify and book calls, plus marketing and business automations that remove the manual work in between.',
        'includes' => ['AI chat & WhatsApp agents', 'Email & WhatsApp journeys', 'Business process automation', 'App integrations'],
        'proof'    => 'Leads answered 24/7',
        'visual'   => 'ai',
    ],
];

/* Clients on the radar: ring = how far the campaign reached. */
$radar = [
    ['name' => 'VIEC',             'metric' => '50× leads',             'ring' => 'local',    'angle' => 160],
    ['name' => 'Beauty Basket',    'metric' => '60× ROAS',              'ring' => 'national', 'angle' => 128],
    ['name' => 'RSK Fragrance',    'metric' => '52 top-10 keywords',    'ring' => 'national', 'angle' => 295],
    ['name' => 'Yalla Renovation', 'metric' => '28 top-10 keywords',    'ring' => 'global',   'angle' => 72],
    ['name' => 'Moebot',           'metric' => '4× organic traffic',    'ring' => 'global',   'angle' => 185],
    ['name' => 'Under Pressure',   'metric' => '59 → 3,570 visits/mo',  'ring' => 'global',   'angle' => 255],
    ['name' => 'Indogulf BioAg',   'metric' => '652 conversions',       'ring' => 'global',   'angle' => 330],
    ['name' => 'TV Repair Co.',    'metric' => '0 → 762 visits/mo',     'ring' => 'regional', 'angle' => 40],
    ['name' => 'Robot My Life',    'metric' => '20× organic traffic',   'ring' => 'regional', 'angle' => 225],
    ['name' => 'Juvly Aesthetics', 'metric' => '75 top-10 keywords',    'ring' => 'regional', 'angle' => 105],
];

$clients = [
    'honda'                            => 'Honda',
    'piaggio'                          => 'Piaggio',
    'energizer'                        => 'Energizer',
    'indogulf-bioag'                   => 'Indogulf BioAg',
    'yalla-renovation'                 => 'Yalla Renovation',
    'rsk-fragrance'                    => 'RSK Fragrance',
    'viec'                             => 'VIEC',
    'study-abroad-education-services'  => 'Study Abroad Education Services',
    'arcadia-vacations'                => 'Arcadia Vacations',
    'punjab-timbers'                   => 'Punjab Timbers',
    'richard-miller-custom-homes'      => 'Richard Miller Custom Homes',
    'motherhood-chaitanya-hospital'    => 'Motherhood Chaitanya Hospital',
    'seerat-migration'                 => 'Seerat Migration',
    'global-strides'                   => 'Global Strides',
    'balaji-chem-solutions'            => 'Balaji Chem Solutions',
    'college-of-commerce'              => 'College of Commerce',
    'enliven'                          => 'Enliven',
    'grow-mate'                        => 'Grow-Mate',
    'swadeshi-dukan'                   => 'Swadeshi Dukan',
    'raju-fixer'                       => 'Raju Fixer',
    'toa-toi'                          => 'Toa Toi',
];

/* Where buyers are found — clusters in the crowd panel. */
$crowd_clusters = [
    ['key' => 'search', 'label' => 'Search',    'note' => 'typing what you sell into Google'],
    ['key' => 'social', 'label' => 'Social',    'note' => 'following brands like yours'],
    ['key' => 'ads',    'label' => 'Lookalikes', 'note' => 'who resemble your best customers'],
    ['key' => 'ai',     'label' => 'AI search', 'note' => 'asking ChatGPT for a recommendation'],
];

$case_studies = [
    [
        'client'  => 'Under Pressure Power Washing',
        'short'   => 'Under Pressure',
        'img'     => 'case-under-pressure',
        'market'  => 'Florida, USA',
        'service' => ['SEO', 'Content'],
        'year'    => '2022 – 2023',
        'title'   => 'From invisible to 20 keywords on page one',
        'summary' => 'Local SEO for a Florida pressure-washing company: 22 target keywords, service pages and steady link building over twelve months.',
        'metric'  => ['before' => 59, 'after' => 3570, 'label' => 'Organic visits a month', 'from' => 'May 2022', 'to' => 'Jul 2023'],
        'url'     => '/seo-case-study-under-pressure',
    ],
    [
        'client'  => 'Yalla Renovation',
        'short'   => 'Yalla Renovation',
        'img'     => 'case-yalla',
        'market'  => 'Dubai, UAE',
        'service' => ['SEO'],
        'year'    => '2022 – 2023',
        'title'   => 'Ranking a Dubai interior brand for the searches that hire',
        'summary' => 'Forty renovation and interior keywords taken from unranked to the top 10, with 28 on page one inside three months.',
        'metric'  => ['before' => 252, 'after' => 3242, 'label' => 'Organic visits a month', 'from' => 'Jan 2022', 'to' => 'Mar 2023'],
        'url'     => '/seo-case-study-yalla-renovation',
    ],
    [
        'client'  => 'Robot My Life',
        'short'   => 'Robot My Life',
        'img'     => 'case-robot-my-life',
        'market'  => 'E-commerce',
        'service' => ['SEO'],
        'year'    => '2022 – 2023',
        'title'   => 'Twenty times the traffic for a robotics store',
        'summary' => 'Product and category SEO for robot window cleaners and vacuums — 27 keywords in the top 10 within three months.',
        'metric'  => ['before' => 131, 'after' => 2927, 'label' => 'Organic visits a month', 'from' => 'Apr 2022', 'to' => 'Mar 2023'],
        'url'     => '/seo-case-study-robot-my-life',
    ],
    [
        'client'  => 'Moebot',
        'short'   => 'Moebot',
        'img'     => 'case-moebot',
        'market'  => 'Australia',
        'service' => ['SEO'],
        'year'    => '2022 – 2023',
        'title'   => 'Owning “robot lawn mower Australia”',
        'summary' => 'Twenty-eight lawn-robot keywords moved into Google’s top 10 in three months, backed by 371 referring domains.',
        'metric'  => ['before' => 459, 'after' => 1999, 'label' => 'Organic visits a month', 'from' => 'Jan 2022', 'to' => 'Mar 2023'],
        'url'     => '/seo-case-study-moebot',
    ],
    [
        'client'  => 'TV Repair Company',
        'short'   => 'TV Repair Co.',
        'img'     => 'case-tv-repair',
        'market'  => 'Ontario, Canada',
        'service' => ['Local SEO'],
        'year'    => '2021 – 2022',
        'title'   => 'Local SEO across Toronto, Brampton and Mississauga',
        'summary' => 'Ten city-level repair keywords taken to the top 10, turning a site with no search traffic into a steady source of calls.',
        'metric'  => ['before' => 0, 'after' => 762, 'label' => 'Organic visits a month', 'from' => 'Dec 2021', 'to' => 'Aug 2022'],
        'url'     => '/seo-case-study-tv-repair',
    ],
    [
        'client'  => 'RSK Fragrance House',
        'short'   => 'RSK Fragrance',
        'img'     => 'case-rsk',
        'market'  => 'India',
        'service' => ['SEO'],
        'year'    => '2021 – 2022',
        'title'   => 'Fifty-two perfume keywords on page one in four months',
        'summary' => 'Product SEO for an Indian fragrance house, from 3 referring domains to 2,810 and from no rankings to 52 top-10 keywords.',
        'metric'  => ['before' => 17, 'after' => 548, 'label' => 'Monthly page views', 'from' => 'Dec 2021', 'to' => 'Mar 2022'],
        'url'     => '/seo-case-study-rsk-fragrance',
    ],
];

$process = [
    ['title' => 'Research', 'when' => 'Start',    'body' => 'We study your business, audience, competitors and current numbers to find the fastest wins.'],
    ['title' => 'Create',   'when' => 'Build',    'body' => 'Strategy and channel plan first, then the assets: website, profiles, content and campaigns.'],
    ['title' => 'Promote',  'when' => 'Launch',   'body' => 'We go live on the channels the plan picked, organic and paid.'],
    ['title' => 'Analyze',  'when' => 'Every week', 'body' => 'We track every channel and report traffic, leads, cost per lead and sales.'],
    ['title' => 'Optimize', 'when' => 'Ongoing',  'body' => 'We fine-tune content, design and budgets and put more behind what works.'],
];

/* The funnel next to the process: which channels work at each stage. */
$funnel = [
    ['stage' => 'Reach',     'tools' => 'SEO · Ads · Social'],
    ['stage' => 'Clicks',    'tools' => 'Websites · Landing pages'],
    ['stage' => 'Leads',     'tools' => 'CRM · AI agents'],
    ['stage' => 'Customers', 'tools' => 'Automation · Follow-ups'],
];

/* Results dashboard tiles. 'bars' are drawn to scale (before vs after). */
$dashboard = [
    'seo' => [
        ['value' => '3,570', 'label' => 'Organic visits a month', 'client' => 'Under Pressure', 'bars' => [59, 3570]],
        ['value' => '20×',   'label' => 'Organic traffic growth', 'client' => 'Robot My Life',  'bars' => [131, 2927]],
        ['value' => '75',    'label' => 'Keywords in the top 10', 'client' => 'Juvly Aesthetics', 'bars' => [0, 75]],
        ['value' => '2,810', 'label' => 'Referring domains',      'client' => 'RSK Fragrance',  'bars' => [3, 2810]],
    ],
    'ads' => [
        ['value' => '60×',    'label' => 'Return on ad spend',  'client' => 'Beauty Basket',  'bars' => [252, 15240]],
        ['value' => '15.24M', 'label' => 'Sales from 252k spend', 'client' => 'Beauty Basket', 'bars' => [252, 15240]],
        ['value' => '652',    'label' => 'Conversions',          'client' => 'Indogulf BioAg', 'bars' => [0, 652]],
        ['value' => '50×',    'label' => 'More leads a month',   'client' => 'VIEC',           'bars' => [1, 50]],
    ],
];

$wins = [
    'Moebot · 28 keywords in Google’s top 10 · Mar 2023',
    'Under Pressure · 3,570 organic visits a month · Jul 2023',
    'Beauty Basket · 15.24M in sales from Google + Meta · Apr 2023',
    'Indogulf BioAg · 652 conversions · Oct 2023',
    'Yalla Renovation · 3,242 organic visits a month · Mar 2023',
    'RSK Fragrance · 52 keywords in the top 10 · Mar 2022',
    'VIEC · 50× more leads from Meta · Apr 2023',
    'Juvly Aesthetics · 75 keywords in the top 10 · Apr 2017',
];

$testimonials = [
    ['video' => 'testimonial-study-abroad', 'name' => '[Client name]', 'company' => 'Study Abroad Education Services'],
    ['video' => 'testimonial-01',           'name' => '[Client name]', 'company' => '[Company]'],
    ['video' => 'testimonial-02',           'name' => '[Client name]', 'company' => '[Company]'],
];

// Replace with real team members (photo in assets/img/team/<n>.webp).
$team = [
    ['name' => '[Name]', 'role' => 'Founder & strategy lead', 'focus' => 'Strategy'],
    ['name' => '[Name]', 'role' => 'SEO lead',                'focus' => 'SEO'],
    ['name' => '[Name]', 'role' => 'Paid ads lead',           'focus' => 'Ads'],
    ['name' => '[Name]', 'role' => 'Social media lead',       'focus' => 'Social'],
    ['name' => '[Name]', 'role' => 'Web development lead',    'focus' => 'Web'],
    ['name' => '[Name]', 'role' => 'CRM & automation lead',   'focus' => 'CRM'],
];

$faqs = [
    ['q' => 'Which countries do you work with?', 'tag' => 'General', 'a' => 'India, Canada, the USA, the UK, Australia, Europe, Singapore, South Africa and the rest of Africa. Our team is in Chandigarh and works across time zones on calls, WhatsApp and email.'],
    ['q' => 'How long until I see results?', 'tag' => 'General', 'a' => 'Paid ads can bring leads within the first few weeks. SEO takes longer — in our case studies, clients went from no rankings to Google’s top 10 in three to four months.'],
    ['q' => 'Can you build our website and market it too?', 'tag' => 'Services', 'a' => 'Yes. We design and build the site, then run SEO, ads and social on top of it, so one team owns the results. Every website includes 6 months of free support.'],
    ['q' => 'Which CRMs do you work with?', 'tag' => 'Services', 'a' => 'Zoho CRM, HubSpot and GoHighLevel. We set them up, migrate your data, build pipelines and automations, train your team, and can manage the CRM for you every month.'],
    ['q' => 'Which industries do you know best?', 'tag' => 'General', 'a' => 'Overseas education, automobile, construction, real estate, e-commerce, scientific products and home improvement, along with healthcare, travel and local services.'],
    ['q' => 'How do you report results?', 'tag' => 'Process', 'a' => 'Regular reports with the numbers that matter — traffic, rankings, leads, cost per lead and sales — plus what we changed and what comes next.'],
    ['q' => 'What happens on the first call?', 'tag' => 'Process', 'a' => 'We look at your website, ads and social profiles together and point out the quickest wins. If we are a fit, we send a plan and get started.'],
];

$posts = [
    [
        'title'    => 'How a Social Media Agency Can Help Small Businesses Grow in India',
        'category' => 'Social media',
        'date'     => 'May 12, 2026',
        'read'     => '6 min read',
        'excerpt'  => 'Customers now find brands through Reels, Facebook ads, WhatsApp and creators before they buy. Here is how an agency turns that into growth.',
        'path'     => '/post/how-a-social-media-agency-can-help-small-businesses-grow-in-india',
        'img'      => 'post-social',
    ],
    [
        'title'    => 'Best Digital Marketing Strategy for Small Businesses in 2026',
        'category' => 'Strategy',
        'date'     => 'Apr 10, 2026',
        'read'     => '4 min read',
        'excerpt'  => 'SEO, local visibility, social, content and paid ads — combined into a lead engine that keeps working.',
        'path'     => '/post/best-digital-marketing-strategy-for-small-businesses',
        'img'      => 'post-strategy',
    ],
    [
        'title'    => 'How AI and AEO Are Replacing Traditional SEO',
        'category' => 'SEO',
        'date'     => 'Feb 12, 2026',
        'read'     => '5 min read',
        'excerpt'  => 'People now ask AI assistants instead of scrolling results. What answer-engine optimisation means for your SEO.',
        'path'     => '/post/how-ai-and-answer-engine-optimization-are-replacing-traditional-seo',
        'img'      => 'post-aeo',
    ],
];

/* Content feed: formats we make. Photos are placeholders until real client work is added. */
$feed = [
    ['img' => 'feed-1',  'format' => 'Reel',        'niche' => 'Café'],
    ['img' => 'feed-5',  'format' => 'Carousel',    'niche' => 'Beauty'],
    ['img' => 'feed-9',  'format' => 'Story',       'niche' => 'Bakery'],
    ['img' => 'feed-12', 'format' => 'Reel',        'niche' => 'Fashion'],
    ['img' => 'feed-6',  'format' => 'Product shot', 'niche' => 'Skincare'],
    ['img' => 'feed-3',  'format' => 'UGC',         'niche' => 'Food'],
    ['img' => 'feed-8',  'format' => 'Carousel',    'niche' => 'Cosmetics'],
    ['img' => 'feed-2',  'format' => 'Flat lay',    'niche' => 'Coffee'],
    ['img' => 'feed-11', 'format' => 'Story',       'niche' => 'Home decor'],
    ['img' => 'feed-10', 'format' => 'Reel',        'niche' => 'Breakfast'],
    ['img' => 'feed-7',  'format' => 'Ad creative', 'niche' => 'Skincare'],
    ['img' => 'case-rsk', 'format' => 'Product shot', 'niche' => 'Fragrance'],
];

/* Example funnel shown next to the process steps (illustrative numbers). */
$funnel_demo = [
    ['stage' => 'Reach',     'value' => 120000, 'note' => 'SEO · Ads · Social', 'rate' => '4% become clicks'],
    ['stage' => 'Clicks',    'value' => 4800,   'note' => 'Website · Landing pages', 'rate' => '8% become leads'],
    ['stage' => 'Leads',     'value' => 384,    'note' => 'CRM · AI agents', 'rate' => '15% become customers'],
    ['stage' => 'Customers', 'value' => 58,     'note' => 'Automation · Follow-ups', 'rate' => 'and they come back'],
];

$hero_chips = ['New lead', 'Call booked', '#1 on Google', 'New follower', 'WhatsApp enquiry', 'Form filled', 'Top-10 keyword', 'Repeat order', 'Reel shared', '5-star review'];
