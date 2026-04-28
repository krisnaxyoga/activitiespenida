<?php
/**
 * Template Name: Luxury Nusa Penida Tour Landing
 * Description: High-converting landing page for premium private Nusa Penida tours.
 */

if (!defined('ABSPATH')) exit;

if (!function_exists('lt_tabler_icon')) {
    /**
     * Render an inline Tabler-style stroke icon (SVG path data).
     */
    function lt_tabler_icon($name, $class = 'w-6 h-6') {
        $paths = [
            // Includes / checks
            'check'        => '<path d="M5 12l5 5L20 7"/>',
            'circle-check' => '<circle cx="12" cy="12" r="9"/><path d="M9 12l2 2 4-4"/>',
            // Hero / package badges
            'sparkles'     => '<path d="M12 3v4"/><path d="M12 17v4"/><path d="M3 12h4"/><path d="M17 12h4"/><path d="M5.6 5.6l2.8 2.8"/><path d="M15.6 15.6l2.8 2.8"/><path d="M5.6 18.4l2.8-2.8"/><path d="M15.6 8.4l2.8-2.8"/>',
            'flame'        => '<path d="M12 12c-2 -2.96 0 -7 1 -8c0 3.038 1.773 4.741 3 6c1.226 1.26 2 3.24 2 5a6 6 0 1 1 -12 0c0 -1.532 1.056 -3.94 2 -5c1.786 2 3 4.04 4 5z"/>',
            'gem'          => '<path d="M6 4h12l3 5l-9 11l-9 -11z"/><path d="M9 9l3 11l3 -11"/><path d="M3 9h18"/>',
            'crown'        => '<path d="M3 17h18l-2 -10l-5 4l-3 -7l-3 7l-5 -4z"/>',
            'compass'      => '<circle cx="12" cy="12" r="9"/><path d="M14.5 9.5l-2 5l-5 2l2 -5z"/>',
            'map-pin'      => '<path d="M9 11a3 3 0 1 0 6 0a3 3 0 0 0 -6 0"/><path d="M17.657 16.657l-4.244 4.243a2 2 0 0 1 -2.828 0l-4.244 -4.243a8 8 0 1 1 11.314 0z"/>',
            // CTA & contact
            'whatsapp'     => '<path d="M3 21l1.65 -3.8a9 9 0 1 1 3.4 2.9l-5.05 .9"/><path d="M9 10a.5 .5 0 0 0 1 0v-1a.5 .5 0 0 0 -1 0v1a5 5 0 0 0 5 5h1a.5 .5 0 0 0 0 -1h-1a.5 .5 0 0 0 0 1"/>',
            'arrow-right'  => '<path d="M5 12h14"/><path d="M13 6l6 6l-6 6"/>',
            'phone'        => '<path d="M5 4h4l2 5l-2.5 1.5a11 11 0 0 0 5 5l1.5 -2.5l5 2v4a2 2 0 0 1 -2 2a16 16 0 0 1 -15 -15a2 2 0 0 1 2 -2"/>',
            'mail'         => '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 7l9 6l9 -6"/>',
            'star'         => '<path d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z"/>',
            'world'        => '<circle cx="12" cy="12" r="9"/><path d="M3.6 9h16.8"/><path d="M3.6 15h16.8"/><path d="M11.5 3a17 17 0 0 0 0 18"/><path d="M12.5 3a17 17 0 0 1 0 18"/>',
            'camera'       => '<path d="M5 7h2l2 -3h6l2 3h2a2 2 0 0 1 2 2v9a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-9a2 2 0 0 1 2 -2"/><circle cx="12" cy="13" r="3"/>',
            'video'        => '<rect x="3" y="6" width="13" height="12" rx="2"/><path d="M16 10l5 -3v10l-5 -3"/>',
            'message'      => '<path d="M3 20l1.3 -3.9a9 8 0 1 1 3.4 2.9l-4.7 1"/><path d="M12 12v.01"/><path d="M8 12v.01"/><path d="M16 12v.01"/>',
            'calendar'     => '<rect x="4" y="5" width="16" height="16" rx="2"/><path d="M16 3v4"/><path d="M8 3v4"/><path d="M4 11h16"/>',
            'building'     => '<rect x="3" y="3" width="7" height="18" rx="1"/><rect x="14" y="9" width="7" height="12" rx="1"/><path d="M5 7h2"/><path d="M5 11h2"/><path d="M5 15h2"/><path d="M16 13h2"/><path d="M16 17h2"/>',
            'sail'         => '<path d="M3 18l1 1.5a4 4 0 0 0 6 0a4 4 0 0 1 6 0a4 4 0 0 0 5 0l1 -1.5"/><path d="M5 16l4 -10l8 10z"/>',
            'sunrise'      => '<path d="M3 17h18"/><path d="M21 21h-18"/><path d="M8 13a4 4 0 1 1 8 0"/><path d="M12 4v3"/><path d="M5.6 9.6l1.4 1.4"/><path d="M18.4 9.6l-1.4 1.4"/>',
            'plant'        => '<path d="M12 22v-7"/><path d="M9 8a6 6 0 0 1 6 0v6a6 6 0 0 1 -6 0z"/><path d="M5 12c0 4 3 7 7 7"/>',
            'heart'        => '<path d="M12 20l-7 -7a4.5 4.5 0 0 1 6.4 -6.3l.6 .6l.6 -.6a4.5 4.5 0 0 1 6.4 6.3l-7 7"/>',
            'shield-check' => '<path d="M12 3l8 3v6c0 5 -3.5 8.5 -8 9c-4.5 -.5 -8 -4 -8 -9v-6z"/><path d="M9 12l2 2l4 -4"/>',
        ];
        $d = $paths[$name] ?? '';
        return '<svg class="' . esc_attr($class) . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $d . '</svg>';
    }
}

$wa_number   = '6281337567256';
$wa_message  = rawurlencode('Hello, I would like to book a private Nusa Penida tour with Activities Penida Tour.');
$wa_link     = 'https://wa.me/' . $wa_number . '?text=' . $wa_message;
$site_name   = 'Activities Penida Tour';
$site_url    = 'https://activitiespenidatour.com';

$reviews = [
    [
        'name'    => 'Sophie Laurent',
        'country' => 'France',
        'date'    => '2026-03-18',
        'title'   => 'A truly luxurious island escape',
        'text'    => 'My husband and I booked the Luxury Bundle Package for our honeymoon and it exceeded every expectation. The private driver was punctual, the snorkeling boat was spotless, and the guide knew the best photo spots without the crowds. Worth every rupiah.',
    ],
    [
        'name'    => 'James O\'Connor',
        'country' => 'Australia',
        'date'    => '2026-02-09',
        'title'   => 'Best private tour in Bali',
        'text'    => 'We had a private West Tour and felt completely taken care of from hotel pickup to drop-off. Kelingking Beach at sunrise with no crowds — unforgettable. Professional, safe, and zero stress. Highly recommended for couples.',
    ],
    [
        'name'    => 'Mei Tanaka',
        'country' => 'Japan',
        'date'    => '2026-01-22',
        'title'   => 'Manta rays and crystal-clear water',
        'text'    => 'The private snorkeling package was magical. Our guide spotted three manta rays at manta bay and the boat crew was so attentive. They even prepared towels and fresh water. Five stars without hesitation.',
    ],
    [
        'name'    => 'Daniel Becker',
        'country' => 'Germany',
        'date'    => '2025-12-05',
        'title'   => 'Premium service, fair pricing',
        'text'    => 'Booked the One Day Tour from Seminyak. Everything was seamless — fast boat, AC car, English guide, lunch. Very different from the cheap group tours my friends took. You really feel the difference.',
    ],
    [
        'name'    => 'Isabella Rossi',
        'country' => 'Italy',
        'date'    => '2025-11-14',
        'title'   => 'Perfect for honeymooners',
        'text'    => 'Diamond Beach and Atuh Beach were stunning, and our guide gave us privacy for couple photos. The luxury accommodation included in the bundle was beautifully located. We will absolutely return.',
    ],
];

$schema = [
    '@context' => 'https://schema.org',
    '@type'    => 'TravelAgency',
    'name'     => $site_name,
    'url'      => $site_url,
    'description' => 'Premium private tours and luxury snorkeling experiences in Nusa Penida.',
    'areaServed'  => 'Nusa Penida, Bali, Indonesia',
    'aggregateRating' => [
        '@type'       => 'AggregateRating',
        'ratingValue' => '5',
        'bestRating'  => '5',
        'worstRating' => '1',
        'reviewCount' => '400',
    ],
    'review' => array_map(function ($r) {
        return [
            '@type'         => 'Review',
            'author'        => ['@type' => 'Person', 'name' => $r['name']],
            'datePublished' => $r['date'],
            'name'          => $r['title'],
            'reviewBody'    => $r['text'],
            'reviewRating'  => [
                '@type'       => 'Rating',
                'ratingValue' => '5',
                'bestRating'  => '5',
                'worstRating' => '1',
            ],
        ];
    }, $reviews),
];

get_template_part('template-parts/header');
?>

<script type="application/ld+json"><?php echo wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?></script>

<main class="bg-white text-slate-800">

    <!-- HERO -->
    <section class="relative overflow-hidden">
        <div class="absolute inset-0">
            <img src="<?php echo esc_url(home_url('/wp-content/uploads/2025/10/klingking-beach-activities-penidatour-ikomangartawann.webp')); ?>"
                 alt="Kelingking Beach, Nusa Penida"
                 class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-r from-slate-900/80 via-slate-900/55 to-slate-900/30"></div>
        </div>
        <div class="relative max-w-6xl mx-auto px-6 py-24 md:py-36 text-white">
            <span class="inline-block uppercase tracking-[0.25em] text-xs font-semibold text-amber-300 mb-5">activitiespenidatour.com</span>
            <h1 class="text-4xl md:text-6xl font-serif font-semibold leading-tight max-w-3xl">
                Discover the Luxury Side of Nusa Penida
            </h1>
            <p class="mt-6 text-lg md:text-xl text-slate-100/90 max-w-2xl leading-relaxed">
                Private island tours, exclusive snorkeling adventures, and premium getaway packages crafted for travelers who want comfort, privacy, and unforgettable experiences in paradise.
            </p>
            <div class="mt-9 flex flex-wrap gap-4">
                <a href="#packages" class="inline-flex items-center gap-2 bg-amber-400 hover:bg-amber-300 text-slate-900 font-semibold px-7 py-3.5 rounded-full transition-colors shadow-lg">
                    <?php echo lt_tabler_icon('sparkles', 'w-5 h-5'); ?>
                    Explore Packages
                </a>
                <a href="<?php echo esc_url($wa_link); ?>" target="_blank" rel="noopener"
                   class="inline-flex items-center gap-2 bg-white/10 backdrop-blur border border-white/40 hover:bg-white/20 text-white font-semibold px-7 py-3.5 rounded-full transition-colors">
                    <?php echo lt_tabler_icon('arrow-right', 'w-5 h-5'); ?>
                    Book Your Private Tour
                </a>
            </div>
            <div class="mt-10 flex items-center gap-3 text-sm text-slate-100/80">
                <div class="flex text-amber-300 fill-current" aria-label="5 out of 5 stars">
                    <?php for ($i = 0; $i < 5; $i++) echo lt_tabler_icon('star', 'w-5 h-5'); ?>
                </div>
                <span><strong class="text-white">5.0</strong> from 400+ international travelers · private transport · pro local guides</span>
            </div>
        </div>
    </section>

    <!-- WHY CHOOSE US -->
    <section class="py-20 md:py-24">
        <div class="max-w-4xl mx-auto px-6 text-center">
            <span class="text-amber-600 uppercase text-xs tracking-[0.25em] font-semibold">Why Choose Us</span>
            <h2 class="mt-3 text-3xl md:text-4xl font-serif font-semibold text-slate-900">
                Experience Nusa Penida Without the Stress
            </h2>
            <p class="mt-6 text-lg text-slate-600 leading-relaxed">
                At Activities Penida Tour, we specialize in premium private experiences across Nusa Penida. From breathtaking island tours to luxury snorkeling trips and curated stay packages, every detail is designed to make your Bali holiday smooth, exclusive, and memorable.
            </p>
            <p class="mt-4 text-lg text-slate-600 leading-relaxed">
                <strong class="text-slate-900">No crowded buses. No rushed schedules.</strong> Just personalized adventures tailored to your travel style.
            </p>
            <div class="mt-10 grid grid-cols-1 md:grid-cols-3 gap-5 text-left">
                <div class="bg-slate-50 border border-slate-100 rounded-2xl p-6">
                    <div class="w-11 h-11 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center mb-4"><?php echo lt_tabler_icon('shield-check', 'w-6 h-6'); ?></div>
                    <h3 class="font-semibold text-slate-900">Private Transport</h3>
                    <p class="text-sm text-slate-600 mt-2">Air-conditioned car with professional driver, only for your group.</p>
                </div>
                <div class="bg-slate-50 border border-slate-100 rounded-2xl p-6">
                    <div class="w-11 h-11 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center mb-4"><?php echo lt_tabler_icon('compass', 'w-6 h-6'); ?></div>
                    <h3 class="font-semibold text-slate-900">Expert Local Guides</h3>
                    <p class="text-sm text-slate-600 mt-2">English-speaking guides who know every hidden viewpoint and beach.</p>
                </div>
                <div class="bg-slate-50 border border-slate-100 rounded-2xl p-6">
                    <div class="w-11 h-11 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center mb-4"><?php echo lt_tabler_icon('sparkles', 'w-6 h-6'); ?></div>
                    <h3 class="font-semibold text-slate-900">Premium Hospitality</h3>
                    <p class="text-sm text-slate-600 mt-2">Hotel pickup, fast boat assistance, and curated luxury comfort.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- PACKAGES -->
    <section id="packages" class="py-20 md:py-24 bg-slate-50">
        <div class="max-w-6xl mx-auto px-6">
            <div class="text-center mb-14">
                <span class="text-amber-600 uppercase text-xs tracking-[0.25em] font-semibold">Our Packages</span>
                <h2 class="mt-3 text-3xl md:text-4xl font-serif font-semibold text-slate-900">Choose Your Private Experience</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-7">

                <!-- 1. WEST TOUR -->
                <article class="bg-white rounded-2xl border border-slate-200 overflow-hidden flex flex-col shadow-sm hover:shadow-lg transition-shadow">
                    <div class="p-7 flex-1 flex flex-col">
                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wider text-amber-600"><?php echo lt_tabler_icon('flame', 'w-4 h-4'); ?> Most Popular</span>
                        <h3 class="mt-2 text-2xl font-serif font-semibold text-slate-900">Private Nusa Penida West Tour</h3>
                        <p class="mt-3 text-slate-600">Explore the most iconic spots in western Nusa Penida with a private guide and comfortable transportation.</p>

                        <h4 class="mt-5 inline-flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wider text-slate-500"><?php echo lt_tabler_icon('map-pin', 'w-4 h-4'); ?> Destinations</h4>
                        <ul class="mt-2 grid grid-cols-2 gap-y-1.5 text-sm text-slate-700">
                            <li class="flex items-start gap-1.5"><?php echo lt_tabler_icon('map-pin', 'w-4 h-4 mt-0.5 text-amber-500'); ?>Kelingking Beach</li>
                            <li class="flex items-start gap-1.5"><?php echo lt_tabler_icon('map-pin', 'w-4 h-4 mt-0.5 text-amber-500'); ?>Broken Beach</li>
                            <li class="flex items-start gap-1.5"><?php echo lt_tabler_icon('map-pin', 'w-4 h-4 mt-0.5 text-amber-500'); ?>Angel's Billabong</li>
                            <li class="flex items-start gap-1.5"><?php echo lt_tabler_icon('map-pin', 'w-4 h-4 mt-0.5 text-amber-500'); ?>Crystal Bay</li>
                        </ul>

                        <h4 class="mt-5 inline-flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wider text-slate-500"><?php echo lt_tabler_icon('circle-check', 'w-4 h-4'); ?> Includes</h4>
                        <ul class="mt-2 text-sm text-slate-700 space-y-1.5">
                            <li class="flex items-start gap-1.5"><?php echo lt_tabler_icon('check', 'w-4 h-4 mt-0.5 text-emerald-600'); ?>Private car &amp; driver</li>
                            <li class="flex items-start gap-1.5"><?php echo lt_tabler_icon('check', 'w-4 h-4 mt-0.5 text-emerald-600'); ?>English-speaking guide</li>
                            <li class="flex items-start gap-1.5"><?php echo lt_tabler_icon('check', 'w-4 h-4 mt-0.5 text-emerald-600'); ?>Hotel pickup &amp; drop-off</li>
                            <li class="flex items-start gap-1.5"><?php echo lt_tabler_icon('check', 'w-4 h-4 mt-0.5 text-emerald-600'); ?>Fast boat assistance</li>
                            <li class="flex items-start gap-1.5"><?php echo lt_tabler_icon('check', 'w-4 h-4 mt-0.5 text-emerald-600'); ?>Mineral water</li>
                        </ul>

                        <div class="mt-7 pt-5 border-t border-slate-100 flex items-end justify-between gap-4">
                            <div>
                                <div class="text-xs text-slate-500">Starting From</div>
                                <div class="text-2xl font-bold text-slate-900">IDR 850,000<span class="text-sm font-normal text-slate-500"> / person</span></div>
                            </div>
                            <a href="<?php echo esc_url($wa_link); ?>" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 bg-slate-900 hover:bg-slate-800 text-white font-semibold px-5 py-2.5 rounded-full text-sm">Book West Tour <?php echo lt_tabler_icon('arrow-right', 'w-4 h-4'); ?></a>
                        </div>
                    </div>
                </article>

                <!-- 2. EAST TOUR -->
                <article class="bg-white rounded-2xl border border-slate-200 overflow-hidden flex flex-col shadow-sm hover:shadow-lg transition-shadow">
                    <div class="p-7 flex-1 flex flex-col">
                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wider text-amber-600"><?php echo lt_tabler_icon('gem', 'w-4 h-4'); ?> Hidden Gems</span>
                        <h3 class="mt-2 text-2xl font-serif font-semibold text-slate-900">Private Nusa Penida East Tour</h3>
                        <p class="mt-3 text-slate-600">Discover the dramatic cliffs and hidden beaches of eastern Nusa Penida with a personalized private experience.</p>

                        <h4 class="mt-5 inline-flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wider text-slate-500"><?php echo lt_tabler_icon('map-pin', 'w-4 h-4'); ?> Destinations</h4>
                        <ul class="mt-2 grid grid-cols-2 gap-y-1.5 text-sm text-slate-700">
                            <li class="flex items-start gap-1.5"><?php echo lt_tabler_icon('map-pin', 'w-4 h-4 mt-0.5 text-amber-500'); ?>Diamond Beach</li>
                            <li class="flex items-start gap-1.5"><?php echo lt_tabler_icon('map-pin', 'w-4 h-4 mt-0.5 text-amber-500'); ?>Atuh Beach</li>
                            <li class="flex items-start gap-1.5"><?php echo lt_tabler_icon('map-pin', 'w-4 h-4 mt-0.5 text-amber-500'); ?>Tree House</li>
                            <li class="flex items-start gap-1.5"><?php echo lt_tabler_icon('map-pin', 'w-4 h-4 mt-0.5 text-amber-500'); ?>Thousand Island Viewpoint</li>
                        </ul>

                        <h4 class="mt-5 inline-flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wider text-slate-500"><?php echo lt_tabler_icon('circle-check', 'w-4 h-4'); ?> Includes</h4>
                        <ul class="mt-2 text-sm text-slate-700 space-y-1.5">
                            <li class="flex items-start gap-1.5"><?php echo lt_tabler_icon('check', 'w-4 h-4 mt-0.5 text-emerald-600'); ?>Private transportation</li>
                            <li class="flex items-start gap-1.5"><?php echo lt_tabler_icon('check', 'w-4 h-4 mt-0.5 text-emerald-600'); ?>Local guide</li>
                            <li class="flex items-start gap-1.5"><?php echo lt_tabler_icon('check', 'w-4 h-4 mt-0.5 text-emerald-600'); ?>Flexible itinerary</li>
                            <li class="flex items-start gap-1.5"><?php echo lt_tabler_icon('check', 'w-4 h-4 mt-0.5 text-emerald-600'); ?>Fast boat assistance</li>
                        </ul>

                        <div class="mt-7 pt-5 border-t border-slate-100 flex items-end justify-between gap-4">
                            <div>
                                <div class="text-xs text-slate-500">Starting From</div>
                                <div class="text-2xl font-bold text-slate-900">IDR 900,000<span class="text-sm font-normal text-slate-500"> / person</span></div>
                            </div>
                            <a href="<?php echo esc_url($wa_link); ?>" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 bg-slate-900 hover:bg-slate-800 text-white font-semibold px-5 py-2.5 rounded-full text-sm">Book East Tour <?php echo lt_tabler_icon('arrow-right', 'w-4 h-4'); ?></a>
                        </div>
                    </div>
                </article>

                <!-- 3. ONE DAY TOUR -->
                <article class="bg-white rounded-2xl border border-slate-200 overflow-hidden flex flex-col shadow-sm hover:shadow-lg transition-shadow">
                    <div class="p-7 flex-1 flex flex-col">
                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wider text-amber-600"><?php echo lt_tabler_icon('sunrise', 'w-4 h-4'); ?> From Bali</span>
                        <h3 class="mt-2 text-2xl font-serif font-semibold text-slate-900">One Day Nusa Penida Tour</h3>
                        <p class="mt-3 text-slate-600">Perfect for travelers staying in Bali who want to experience the highlights of Nusa Penida in one unforgettable day.</p>

                        <h4 class="mt-5 inline-flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wider text-slate-500"><?php echo lt_tabler_icon('circle-check', 'w-4 h-4'); ?> Includes</h4>
                        <ul class="mt-2 text-sm text-slate-700 space-y-1.5">
                            <li class="flex items-start gap-1.5"><?php echo lt_tabler_icon('check', 'w-4 h-4 mt-0.5 text-emerald-600'); ?>Return fast boat tickets</li>
                            <li class="flex items-start gap-1.5"><?php echo lt_tabler_icon('check', 'w-4 h-4 mt-0.5 text-emerald-600'); ?>Hotel transfer in Bali</li>
                            <li class="flex items-start gap-1.5"><?php echo lt_tabler_icon('check', 'w-4 h-4 mt-0.5 text-emerald-600'); ?>Private island tour</li>
                            <li class="flex items-start gap-1.5"><?php echo lt_tabler_icon('check', 'w-4 h-4 mt-0.5 text-emerald-600'); ?>Lunch</li>
                            <li class="flex items-start gap-1.5"><?php echo lt_tabler_icon('check', 'w-4 h-4 mt-0.5 text-emerald-600'); ?>Tour guide</li>
                            <li class="flex items-start gap-1.5"><?php echo lt_tabler_icon('check', 'w-4 h-4 mt-0.5 text-emerald-600'); ?>Comfortable transportation</li>
                        </ul>

                        <div class="mt-7 pt-5 border-t border-slate-100 flex items-end justify-between gap-4">
                            <div>
                                <div class="text-xs text-slate-500">Starting From</div>
                                <div class="text-2xl font-bold text-slate-900">IDR 1,500,000<span class="text-sm font-normal text-slate-500"> / person</span></div>
                            </div>
                            <a href="<?php echo esc_url($wa_link); ?>" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 bg-slate-900 hover:bg-slate-800 text-white font-semibold px-5 py-2.5 rounded-full text-sm">Reserve <?php echo lt_tabler_icon('arrow-right', 'w-4 h-4'); ?></a>
                        </div>
                    </div>
                </article>

                <!-- 4. PRIVATE SNORKELING -->
                <article class="bg-white rounded-2xl border border-slate-200 overflow-hidden flex flex-col shadow-sm hover:shadow-lg transition-shadow">
                    <div class="p-7 flex-1 flex flex-col">
                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wider text-amber-600"><?php echo lt_tabler_icon('heart', 'w-4 h-4'); ?> For Couples</span>
                        <h3 class="mt-2 text-2xl font-serif font-semibold text-slate-900">Private Snorkeling Package</h3>
                        <p class="mt-3 text-slate-600">Experience the crystal-clear waters of Nusa Penida with a premium private snorkeling adventure. Swim alongside manta rays and explore Bali's most stunning underwater destinations with experienced local crews.</p>

                        <h4 class="mt-5 inline-flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wider text-slate-500"><?php echo lt_tabler_icon('sail', 'w-4 h-4'); ?> Snorkeling Spots</h4>
                        <ul class="mt-2 grid grid-cols-2 gap-y-1.5 text-sm text-slate-700">
                            <li class="flex items-start gap-1.5"><?php echo lt_tabler_icon('map-pin', 'w-4 h-4 mt-0.5 text-amber-500'); ?>Manta Bay</li>
                            <li class="flex items-start gap-1.5"><?php echo lt_tabler_icon('map-pin', 'w-4 h-4 mt-0.5 text-amber-500'); ?>Crystal Bay</li>
                            <li class="flex items-start gap-1.5"><?php echo lt_tabler_icon('map-pin', 'w-4 h-4 mt-0.5 text-amber-500'); ?>Gamat Bay</li>
                            <li class="flex items-start gap-1.5"><?php echo lt_tabler_icon('map-pin', 'w-4 h-4 mt-0.5 text-amber-500'); ?>Wall Point</li>
                        </ul>

                        <h4 class="mt-5 inline-flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wider text-slate-500"><?php echo lt_tabler_icon('circle-check', 'w-4 h-4'); ?> Includes</h4>
                        <ul class="mt-2 text-sm text-slate-700 space-y-1.5">
                            <li class="flex items-start gap-1.5"><?php echo lt_tabler_icon('check', 'w-4 h-4 mt-0.5 text-emerald-600'); ?>Private snorkeling boat</li>
                            <li class="flex items-start gap-1.5"><?php echo lt_tabler_icon('check', 'w-4 h-4 mt-0.5 text-emerald-600'); ?>Snorkeling equipment</li>
                            <li class="flex items-start gap-1.5"><?php echo lt_tabler_icon('check', 'w-4 h-4 mt-0.5 text-emerald-600'); ?>Professional guide</li>
                            <li class="flex items-start gap-1.5"><?php echo lt_tabler_icon('check', 'w-4 h-4 mt-0.5 text-emerald-600'); ?>Safety equipment</li>
                            <li class="flex items-start gap-1.5"><?php echo lt_tabler_icon('check', 'w-4 h-4 mt-0.5 text-emerald-600'); ?>Mineral water</li>
                        </ul>

                        <p class="mt-5 text-sm text-slate-500">For complete snorkeling packages and premium experiences, visit
                            <a class="text-amber-700 font-semibold hover:underline" href="https://snorkelingpenida.com" target="_blank" rel="noopener">snorkelingpenida.com</a>.
                        </p>

                        <div class="mt-7 pt-5 border-t border-slate-100 flex items-end justify-between gap-4">
                            <div>
                                <div class="text-xs text-slate-500">Starting From</div>
                                <div class="text-2xl font-bold text-slate-900">IDR 1,800,000<span class="text-sm font-normal text-slate-500"> / couple</span></div>
                            </div>
                            <a href="https://snorkelingpenida.com" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 bg-slate-900 hover:bg-slate-800 text-white font-semibold px-5 py-2.5 rounded-full text-sm">Explore <?php echo lt_tabler_icon('arrow-right', 'w-4 h-4'); ?></a>
                        </div>
                    </div>
                </article>
            </div>

            <!-- 5. LUXURY BUNDLE (FULL WIDTH) -->
            <article class="mt-7 rounded-3xl overflow-hidden bg-gradient-to-br from-slate-900 via-slate-800 to-amber-900 text-white shadow-xl">
                <div class="grid grid-cols-1 lg:grid-cols-2">
                    <div class="p-8 md:p-12">
                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold uppercase tracking-[0.3em] text-amber-300"><?php echo lt_tabler_icon('crown', 'w-4 h-4'); ?> Signature Experience</span>
                        <h3 class="mt-3 text-3xl md:text-4xl font-serif font-semibold">The Ultimate Nusa Penida Escape</h3>
                        <p class="mt-5 text-slate-100/85 leading-relaxed">
                            A premium all-in-one island experience designed for couples, honeymooners, and luxury travelers seeking comfort and unforgettable moments. This exclusive package combines accommodation, private island tours, and luxury snorkeling into one seamless experience.
                        </p>

                        <h4 class="mt-7 inline-flex items-center gap-1.5 text-xs font-semibold uppercase tracking-[0.25em] text-amber-300"><?php echo lt_tabler_icon('circle-check', 'w-4 h-4'); ?> Package Includes</h4>
                        <ul class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-y-2 text-sm">
                            <li class="flex items-start gap-1.5"><?php echo lt_tabler_icon('check', 'w-4 h-4 mt-0.5 text-amber-300'); ?>Luxury accommodation</li>
                            <li class="flex items-start gap-1.5"><?php echo lt_tabler_icon('check', 'w-4 h-4 mt-0.5 text-amber-300'); ?>Private West Nusa Penida Tour</li>
                            <li class="flex items-start gap-1.5"><?php echo lt_tabler_icon('check', 'w-4 h-4 mt-0.5 text-amber-300'); ?>Private snorkeling experience</li>
                            <li class="flex items-start gap-1.5"><?php echo lt_tabler_icon('check', 'w-4 h-4 mt-0.5 text-amber-300'); ?>Fast boat tickets</li>
                            <li class="flex items-start gap-1.5"><?php echo lt_tabler_icon('check', 'w-4 h-4 mt-0.5 text-amber-300'); ?>Hotel transfers</li>
                            <li class="flex items-start gap-1.5"><?php echo lt_tabler_icon('check', 'w-4 h-4 mt-0.5 text-amber-300'); ?>Professional local assistance</li>
                            <li class="flex items-start gap-1.5"><?php echo lt_tabler_icon('check', 'w-4 h-4 mt-0.5 text-amber-300'); ?>Comfortable private transportation</li>
                        </ul>

                        <h4 class="mt-7 inline-flex items-center gap-1.5 text-xs font-semibold uppercase tracking-[0.25em] text-amber-300"><?php echo lt_tabler_icon('heart', 'w-4 h-4'); ?> Perfect For</h4>
                        <p class="mt-2 text-sm text-slate-100/85">Honeymoon couples · Romantic getaways · Luxury Bali vacations · Private island experiences</p>
                    </div>

                    <div class="p-8 md:p-12 bg-black/30 flex flex-col justify-center">
                        <div class="text-xs uppercase tracking-[0.25em] text-amber-300">Package Price</div>
                        <div class="mt-2 text-5xl font-bold">IDR 5,000,000</div>
                        <div class="text-sm text-slate-200/80 mt-1">per package · for two travelers</div>
                        <a href="<?php echo esc_url($wa_link); ?>" target="_blank" rel="noopener"
                           class="mt-7 inline-flex items-center justify-center gap-2 bg-amber-400 hover:bg-amber-300 text-slate-900 font-semibold px-7 py-4 rounded-full transition-colors shadow-lg">
                            <?php echo lt_tabler_icon('crown', 'w-5 h-5'); ?>
                            Book Luxury Package
                        </a>
                        <p class="mt-4 text-xs text-slate-200/70">Limited slots — we keep this package exclusive to maintain quality.</p>
                    </div>
                </div>
            </article>
        </div>
    </section>

    <!-- GUEST EXPERIENCE / REVIEWS -->
    <section class="py-20 md:py-24 bg-white">
        <div class="max-w-6xl mx-auto px-6">
            <div class="text-center mb-12">
                <span class="text-amber-600 uppercase text-xs tracking-[0.25em] font-semibold">Guest Experience</span>
                <h2 class="mt-3 text-3xl md:text-4xl font-serif font-semibold text-slate-900">Why International Travelers Love Us</h2>
                <p class="mt-5 text-lg text-slate-600 max-w-3xl mx-auto leading-relaxed">
                    We help travelers experience Nusa Penida with comfort, safety, and personalized service. From luxury couples trips to private snorkeling adventures, our goal is to create unforgettable memories while you enjoy Bali's most beautiful island destination.
                </p>

                <div class="mt-8 inline-flex flex-wrap items-center justify-center gap-3 bg-amber-50 border border-amber-200 px-6 py-4 rounded-2xl">
                    <div class="flex text-amber-500 fill-current" aria-label="5 out of 5 stars">
                        <?php for ($i = 0; $i < 5; $i++) echo lt_tabler_icon('star', 'w-6 h-6'); ?>
                    </div>
                    <div class="text-left">
                        <div class="text-2xl font-bold text-slate-900 leading-none">5.0 / 5.0</div>
                        <div class="text-sm text-slate-600">Based on <strong>400</strong> verified traveler reviews</div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($reviews as $i => $r) : ?>
                    <article class="bg-slate-50 border border-slate-100 rounded-2xl p-6 flex flex-col<?php echo $i >= 3 ? ' lg:col-span-1 md:col-span-1' : ''; ?>"
                             itemscope itemtype="https://schema.org/Review">
                        <meta itemprop="datePublished" content="<?php echo esc_attr($r['date']); ?>">
                        <div itemprop="reviewRating" itemscope itemtype="https://schema.org/Rating" class="flex text-amber-500 fill-current mb-3">
                            <meta itemprop="ratingValue" content="5">
                            <meta itemprop="bestRating" content="5">
                            <?php for ($s = 0; $s < 5; $s++) echo lt_tabler_icon('star', 'w-4 h-4'); ?>
                        </div>
                        <h3 class="font-semibold text-slate-900" itemprop="name"><?php echo esc_html($r['title']); ?></h3>
                        <p class="mt-2 text-slate-600 text-sm leading-relaxed flex-1" itemprop="reviewBody">
                            "<?php echo esc_html($r['text']); ?>"
                        </p>
                        <div class="mt-5 pt-4 border-t border-slate-200 text-sm">
                            <div class="font-semibold text-slate-900" itemprop="author" itemscope itemtype="https://schema.org/Person">
                                <span itemprop="name"><?php echo esc_html($r['name']); ?></span>
                            </div>
                            <div class="text-slate-500 text-xs"><?php echo esc_html($r['country']); ?> · <?php echo esc_html(date('F Y', strtotime($r['date']))); ?></div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>

            <div class="mt-10 grid grid-cols-2 md:grid-cols-4 gap-3 text-sm text-slate-600">
                <div class="py-3 px-4 bg-slate-50 rounded-xl flex items-center justify-center gap-2"><?php echo lt_tabler_icon('star', 'w-4 h-4 text-amber-500 fill-current'); ?> Google Reviews</div>
                <div class="py-3 px-4 bg-slate-50 rounded-xl flex items-center justify-center gap-2"><?php echo lt_tabler_icon('message', 'w-4 h-4 text-slate-500'); ?> Guest Testimonials</div>
                <div class="py-3 px-4 bg-slate-50 rounded-xl flex items-center justify-center gap-2"><?php echo lt_tabler_icon('camera', 'w-4 h-4 text-slate-500'); ?> Instagram Gallery</div>
                <div class="py-3 px-4 bg-slate-50 rounded-xl flex items-center justify-center gap-2"><?php echo lt_tabler_icon('video', 'w-4 h-4 text-slate-500'); ?> Drone Videos</div>
            </div>
        </div>
    </section>

    <!-- FINAL CTA -->
    <section class="relative overflow-hidden">
        <div class="absolute inset-0 bg-slate-900"></div>
        <div class="relative max-w-4xl mx-auto px-6 py-20 md:py-24 text-center text-white">
            <h2 class="text-3xl md:text-4xl font-serif font-semibold leading-tight">Ready for Your Private Nusa Penida Experience?</h2>
            <p class="mt-5 text-lg text-slate-300 leading-relaxed max-w-2xl mx-auto">
                Escape crowded tours and discover the island with personalized service, premium comfort, and unforgettable experiences. Contact our team today and let us help you plan the perfect Nusa Penida getaway.
            </p>
            <div class="mt-9 flex flex-wrap justify-center gap-4">
                <a href="<?php echo esc_url($wa_link); ?>" target="_blank" rel="noopener"
                   class="inline-flex items-center gap-2 bg-emerald-500 hover:bg-emerald-400 text-white font-semibold px-7 py-3.5 rounded-full transition-colors shadow-lg">
                    <?php echo lt_tabler_icon('whatsapp', 'w-5 h-5'); ?>
                    Contact via WhatsApp
                </a>
                <a href="<?php echo esc_url($wa_link); ?>" target="_blank" rel="noopener"
                   class="inline-flex items-center gap-2 bg-amber-400 hover:bg-amber-300 text-slate-900 font-semibold px-7 py-3.5 rounded-full transition-colors shadow-lg">
                    <?php echo lt_tabler_icon('calendar', 'w-5 h-5'); ?>
                    Start Your Booking
                </a>
            </div>
        </div>
    </section>

    <!-- LOCAL FOOTER (page-specific quick links) -->
    <section class="bg-slate-950 text-slate-300">
        <div class="max-w-6xl mx-auto px-6 py-14 grid grid-cols-1 md:grid-cols-3 gap-10">
            <div>
                <h4 class="text-white font-serif text-lg mb-4 inline-flex items-center gap-2"><?php echo lt_tabler_icon('compass', 'w-5 h-5 text-amber-300'); ?> Quick Links</h4>
                <ul class="space-y-2 text-sm">
                    <li><a class="inline-flex items-center gap-1.5 hover:text-amber-300" href="#packages"><?php echo lt_tabler_icon('arrow-right', 'w-4 h-4'); ?> West Tour</a></li>
                    <li><a class="inline-flex items-center gap-1.5 hover:text-amber-300" href="#packages"><?php echo lt_tabler_icon('arrow-right', 'w-4 h-4'); ?> East Tour</a></li>
                    <li><a class="inline-flex items-center gap-1.5 hover:text-amber-300" href="#packages"><?php echo lt_tabler_icon('arrow-right', 'w-4 h-4'); ?> One Day Tour</a></li>
                    <li><a class="inline-flex items-center gap-1.5 hover:text-amber-300" href="https://snorkelingpenida.com" target="_blank" rel="noopener"><?php echo lt_tabler_icon('arrow-right', 'w-4 h-4'); ?> Snorkeling Packages</a></li>
                    <li><a class="inline-flex items-center gap-1.5 hover:text-amber-300" href="#packages"><?php echo lt_tabler_icon('arrow-right', 'w-4 h-4'); ?> Luxury Bundle Package</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-serif text-lg mb-4 inline-flex items-center gap-2"><?php echo lt_tabler_icon('phone', 'w-5 h-5 text-amber-300'); ?> Contact</h4>
                <p class="text-sm leading-relaxed">
                    Activities Penida Tour<br>
                    <a class="inline-flex items-center gap-1.5 hover:text-amber-300" href="https://activitiespenidatour.com" target="_blank" rel="noopener"><?php echo lt_tabler_icon('world', 'w-4 h-4'); ?> activitiespenidatour.com</a><br>
                    <a class="inline-flex items-center gap-1.5 hover:text-amber-300" href="https://snorkelingpenida.com" target="_blank" rel="noopener"><?php echo lt_tabler_icon('world', 'w-4 h-4'); ?> snorkelingpenida.com</a>
                </p>
                <a href="<?php echo esc_url($wa_link); ?>" target="_blank" rel="noopener" class="mt-4 inline-flex items-center gap-2 text-emerald-400 hover:text-emerald-300 text-sm font-semibold"><?php echo lt_tabler_icon('whatsapp', 'w-4 h-4'); ?> WhatsApp our concierge</a>
            </div>
            <div>
                <h4 class="text-white font-serif text-lg mb-4 inline-flex items-center gap-2"><?php echo lt_tabler_icon('crown', 'w-5 h-5 text-amber-300'); ?> About</h4>
                <p class="text-sm leading-relaxed text-slate-400">
                    Premium private tours and luxury snorkeling experiences in Nusa Penida for travelers seeking comfort, exclusivity, and unforgettable island memories.
                </p>
            </div>
        </div>
    </section>

</main>

<?php get_template_part('template-parts/footer'); ?>
