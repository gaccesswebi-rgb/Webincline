<?php
/**
 * Content for the homepage sections. Edit text here, not in the templates.
 * Anything in [square brackets] is a placeholder waiting for real details.
 */

declare(strict_types=1);

$service_groups = [
    'grow'     => 'Grow',
    'build'    => 'Build',
    'automate' => 'Automate',
    'ai-crm'   => 'AI & CRM',
];

$services = [
    [
        'slug'     => 'seo',
        'group'    => 'grow',
        'title'    => 'SEO & AI search',
        'line'     => 'Rank on Google — and get cited by AI assistants.',
        'body'     => 'Technical fixes, keyword strategy, content and links that move you up for the searches your buyers make, plus answer-engine optimisation so ChatGPT, Gemini and Perplexity recommend you.',
        'includes' => ['Technical SEO audit', 'Keyword & competitor research', 'On-page & content', 'Link building', 'Local SEO', 'AEO / GEO'],
        'proof'    => 'Top-10 rankings in 3–4 months',
    ],
    [
        'slug'     => 'paid-ads',
        'group'    => 'grow',
        'title'    => 'Google & Meta Ads',
        'line'     => 'Every rupee and dollar tracked to a lead or a sale.',
        'body'     => 'Search, Shopping, Performance Max, Meta and YouTube campaigns built around your margins, tested every week and reported in leads and revenue — not clicks.',
        'includes' => ['Account & tracking setup', 'Ad copy & creatives', 'Landing page optimisation', 'Weekly optimisation', 'Quality score improvement'],
        'proof'    => '60× return on ad spend',
    ],
    [
        'slug'     => 'social-media',
        'group'    => 'grow',
        'title'    => 'Social media marketing',
        'line'     => 'Content people stop scrolling for.',
        'body'     => 'Strategy, content calendars, reels, design and community management on Instagram, Facebook, LinkedIn and YouTube — built to grow an audience that buys.',
        'includes' => ['Content calendar', 'Reels & creatives', 'Community management', 'Monthly reporting'],
        'proof'    => 'Always-on content, every week',
    ],
    [
        'slug'     => 'influencer-marketing',
        'group'    => 'grow',
        'title'    => 'Influencer marketing',
        'line'     => 'The right voices in front of the right audience.',
        'body'     => 'We shortlist creators whose followers match your buyers, handle outreach and briefs, and track every campaign back to reach, leads and sales.',
        'includes' => ['Creator research', 'Outreach & negotiation', 'Campaign briefs', 'Performance tracking'],
        'proof'    => 'Creators matched to your buyers',
    ],
    [
        'slug'     => 'affiliate-marketing',
        'group'    => 'grow',
        'title'    => 'Affiliate marketing',
        'line'     => 'Partners who only get paid when you sell.',
        'body'     => 'We set up your affiliate programme, recruit partners who reach your audience, and track every referral so commissions follow real sales.',
        'includes' => ['Programme setup', 'Partner recruitment', 'Tracking & attribution', 'Commission structure'],
        'proof'    => 'Pay only for results',
    ],
    [
        'slug'     => 'web-development',
        'group'    => 'build',
        'title'    => 'Website design & development',
        'line'     => 'Websites that turn visits into enquiries.',
        'body'     => 'Fast, mobile-first websites and landing pages designed to convert, with SEO basics built in — and 6 months of free support after launch.',
        'includes' => ['UX & wireframes', 'Custom design', 'Development', 'Speed & SEO basics', '6 months free support'],
        'proof'    => '6 months free support',
    ],
    [
        'slug'     => 'e-commerce',
        'group'    => 'build',
        'title'    => 'E-commerce solutions',
        'line'     => 'Online stores that sell while you sleep.',
        'body'     => 'Shopify, WooCommerce and custom stores with payments, shipping and product feeds ready for Google Shopping and Meta ads from day one.',
        'includes' => ['Store setup', 'Payments & shipping', 'Product feeds', 'Conversion optimisation'],
        'proof'    => 'Ad-ready from launch',
    ],
    [
        'slug'     => 'business-profile-building',
        'group'    => 'build',
        'title'    => 'Business profile building',
        'line'     => 'Show up on Google Maps when locals search.',
        'body'     => 'Google Business Profile setup and optimisation, local listings and a review strategy, so nearby customers find you first — and trust what they see.',
        'includes' => ['Google Business Profile', 'Local citations', 'Review strategy', 'Photos & posts'],
        'proof'    => 'Found in the map pack',
    ],
    [
        'slug'     => 'marketing-automation',
        'group'    => 'automate',
        'title'    => 'Marketing automation',
        'line'     => 'Follow-ups that never forget.',
        'body'     => 'Email, SMS and WhatsApp journeys that nurture every lead automatically, from the first click to the closed deal.',
        'includes' => ['Lead capture & scoring', 'Email & WhatsApp journeys', 'Segmentation', 'Reporting dashboards'],
        'proof'    => 'No lead left waiting',
    ],
    [
        'slug'     => 'business-automation',
        'group'    => 'automate',
        'title'    => 'Business automation',
        'line'     => 'Less manual work. Fewer mistakes.',
        'body'     => 'We connect your forms, CRM, accounts and tools so routine work — data entry, invoices, reminders, reports — runs on its own.',
        'includes' => ['Process mapping', 'Workflow automation', 'App integrations', 'Team training'],
        'proof'    => 'Hours saved every week',
    ],
    [
        'slug'      => 'crm',
        'group'     => 'ai-crm',
        'title'     => 'CRM building & management',
        'line'      => 'One place for every lead, deal and customer.',
        'body'      => 'We design, build and run your CRM — pipelines, automations, dashboards and team training — then manage it for you month after month.',
        'includes'  => ['Setup & data migration', 'Pipelines & automations', 'Dashboards', 'Ongoing management'],
        'platforms' => ['Zoho CRM', 'HubSpot', 'GoHighLevel'],
        'proof'     => 'Zoho · HubSpot · GoHighLevel',
    ],
    [
        'slug'     => 'ai-automation',
        'group'    => 'ai-crm',
        'title'    => 'AI automation & agents',
        'line'     => 'AI that answers, qualifies and books — 24/7.',
        'body'     => 'AI chat and WhatsApp agents that answer questions, qualify leads and book calls straight into your CRM, plus AI workflows that speed up content and reporting.',
        'includes' => ['AI chat & WhatsApp agents', 'Lead qualification', 'CRM integration', 'AI content workflows'],
        'proof'    => 'Replies in seconds, not hours',
    ],
];

// Numbers come from the case studies on the current website.
$case_studies = [
    [
        'client'  => 'Under Pressure Power Washing',
        'market'  => 'Florida, USA',
        'service' => 'SEO',
        'title'   => 'From page zero to 20 keywords in Google’s top 10',
        'metric'  => ['before' => 59, 'after' => 3570, 'label' => 'Organic visits a month'],
        'extra'   => '87 → 11,688 backlinks',
        'period'  => 'May 2022 – Jul 2023',
        'url'     => '/seo-case-study-under-pressure',
    ],
    [
        'client'  => 'Yalla Renovation',
        'market'  => 'Dubai, UAE',
        'service' => 'SEO',
        'title'   => 'An interior design brand ranking where Dubai searches',
        'metric'  => ['before' => 252, 'after' => 3242, 'label' => 'Organic visits a month'],
        'extra'   => '28 keywords in the top 10 in 3 months',
        'period'  => 'Jan 2022 – Mar 2023',
        'url'     => '/seo-case-study-yalla-renovation',
    ],
    [
        'client'  => 'Beauty Basket',
        'market'  => 'India',
        'service' => 'Paid ads',
        'title'   => 'Google + Meta ads that paid back sixty times over',
        'metric'  => ['before' => 0, 'after' => 60, 'label' => 'Return on ad spend', 'suffix' => '×', 'single' => true],
        'extra'   => '15.24M in sales from 252k ad spend',
        'period'  => 'Nov 2022 – Apr 2023',
        'url'     => '/case-study-beautybasket',
    ],
    [
        'client'  => 'Moebot',
        'market'  => 'Australia',
        'service' => 'SEO',
        'title'   => 'Owning “robot lawn mower Australia” and 27 more',
        'metric'  => ['before' => 459, 'after' => 1999, 'label' => 'Organic visits a month'],
        'extra'   => '28 keywords in the top 10 in 3 months',
        'period'  => 'Jan 2022 – Mar 2023',
        'url'     => '/seo-case-study-moebot',
    ],
    [
        'client'  => 'Indogulf BioAg',
        'market'  => 'USA & Canada',
        'service' => 'Paid ads',
        'title'   => 'Organic fertiliser sales from Google and Facebook ads',
        'metric'  => ['before' => 0, 'after' => 652, 'label' => 'Conversions', 'single' => true],
        'extra'   => '8.6 cost per conversion on 5.6k ad spend',
        'period'  => 'Apr 2023 – Oct 2023',
        'url'     => '/case-study-indogulf',
    ],
    [
        'client'  => 'TV Repair Company',
        'market'  => 'Ontario, Canada',
        'service' => 'SEO',
        'title'   => 'Local SEO across Toronto, Brampton and Mississauga',
        'metric'  => ['before' => 0, 'after' => 762, 'label' => 'Organic visits a month'],
        'extra'   => '10 local keywords in the top 10 in 3 months',
        'period'  => 'Dec 2021 – Aug 2022',
        'url'     => '/seo-case-study-tv-repair',
    ],
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

$industries = ['Overseas education', 'Automobile', 'Construction', 'Real estate', 'E-commerce', 'Scientific products', 'Home improvement', 'Healthcare', 'Travel'];

$process = [
    ['title' => 'Research', 'body' => 'We study your business, audience, competitors and current numbers to find where the fastest wins are.', 'tags' => ['Audit', 'Competitor analysis', 'Audience research']],
    ['title' => 'Create',   'body' => 'Goals, strategy and a channel plan — then the assets: website, profiles, content and campaigns.', 'tags' => ['Strategy', 'Content', 'Website & profiles']],
    ['title' => 'Promote',  'body' => 'We launch on the channels the plan picked, organic and paid, and get your brand in front of buyers.', 'tags' => ['SEO', 'Ads', 'Social']],
    ['title' => 'Analyze',  'body' => 'We track every channel and report what is working — traffic, leads, cost per lead and sales.', 'tags' => ['Tracking', 'Dashboards', 'Reports']],
    ['title' => 'Optimize', 'body' => 'We fine-tune content, design and budgets every week and put more behind what works.', 'tags' => ['Testing', 'Fine-tuning', 'Scaling']],
];

$testimonials = [
    ['video' => 'testimonial-study-abroad', 'name' => '[Client name]', 'company' => 'Study Abroad Education Services'],
    ['video' => 'testimonial-01',           'name' => '[Client name]', 'company' => '[Company]'],
    ['video' => 'testimonial-02',           'name' => '[Client name]', 'company' => '[Company]'],
];

// Replace with real team members (photo in assets/img/team/<slug>.webp).
$team = [
    ['name' => '[Name]', 'role' => 'Founder & strategy lead', 'focus' => 'Strategy'],
    ['name' => '[Name]', 'role' => 'SEO lead',                'focus' => 'SEO'],
    ['name' => '[Name]', 'role' => 'Paid ads lead',           'focus' => 'Ads'],
    ['name' => '[Name]', 'role' => 'Social media lead',       'focus' => 'Social'],
    ['name' => '[Name]', 'role' => 'Web development lead',    'focus' => 'Web'],
    ['name' => '[Name]', 'role' => 'CRM & automation lead',   'focus' => 'CRM'],
];

$faqs = [
    ['q' => 'Which countries do you work with?', 'a' => 'We work with businesses in India, Canada, the USA, the UK, Australia, Europe, Singapore, South Africa and across Africa. Our team is based in Chandigarh and works across time zones on calls, WhatsApp and email.'],
    ['q' => 'How long until I see results?', 'a' => 'Paid ads can bring leads within the first few weeks. SEO takes longer — in our case studies, clients went from no rankings to Google’s top 10 for their target keywords in three to four months.'],
    ['q' => 'Can you build our website and market it too?', 'a' => 'Yes. We design and develop the website, then run SEO, ads and social on top of it, so one team owns the results. Every website comes with 6 months of free support.'],
    ['q' => 'Which CRMs do you work with?', 'a' => 'Zoho CRM, HubSpot and GoHighLevel. We set them up, migrate your data, build pipelines and automations, train your team — and can manage the CRM for you every month.'],
    ['q' => 'Which industries do you know best?', 'a' => 'Overseas education, automobile, construction, real estate, e-commerce, scientific products and home improvement, along with healthcare, travel and local services.'],
    ['q' => 'How do you report results?', 'a' => 'You get regular reports with the numbers that matter — traffic, rankings, leads, cost per lead and sales — plus what we changed and what we will do next.'],
    ['q' => 'What happens after I get in touch?', 'a' => 'We get on a call, look at your website, ads and social profiles, and point out the quickest wins. If we are a good fit, we send a plan and get started.'],
];

$posts = [
    [
        'title'    => 'How a Social Media Agency Can Help Small Businesses Grow in India',
        'category' => 'Social media',
        'date'     => 'May 12, 2026',
        'read'     => '6 min read',
        'excerpt'  => 'Customers now discover brands through Reels, Facebook ads, WhatsApp and influencer recommendations before they buy. Here is how an agency turns that into growth.',
        'path'     => '/post/how-a-social-media-agency-can-help-small-businesses-grow-in-india',
    ],
    [
        'title'    => 'Best Digital Marketing Strategy for Small Businesses in 2026',
        'category' => 'Strategy',
        'date'     => 'Apr 10, 2026',
        'read'     => '4 min read',
        'excerpt'  => 'SEO, local visibility, social media, content and paid ads — how small businesses combine them into a lead engine that keeps working.',
        'path'     => '/post/best-digital-marketing-strategy-for-small-businesses',
    ],
    [
        'title'    => 'How AI and AEO Are Replacing Traditional SEO',
        'category' => 'SEO',
        'date'     => 'Feb 12, 2026',
        'read'     => '5 min read',
        'excerpt'  => 'People now ask AI assistants instead of scrolling results. What answer-engine optimisation means and how to adapt your SEO for AI-powered search.',
        'path'     => '/post/how-ai-and-answer-engine-optimization-are-replacing-traditional-seo',
    ],
];
