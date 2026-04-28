<?php
/**
 * Template Name: Luxury Nusa Penida Tour Landing
 * Description: High-converting landing page for premium private Nusa Penida tours.
 */

if (!defined('ABSPATH')) exit;

$wa_number   = '6285121102295';
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
        'text'    => 'The private snorkeling package was magical. Our guide spotted three manta rays at Manta Point and the boat crew was so attentive. They even prepared towels and fresh water. Five stars without hesitation.',
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
                    Explore Packages
                </a>
                <a href="<?php echo esc_url($wa_link); ?>" target="_blank" rel="noopener"
                   class="inline-flex items-center gap-2 bg-white/10 backdrop-blur border border-white/40 hover:bg-white/20 text-white font-semibold px-7 py-3.5 rounded-full transition-colors">
                    Book Your Private Tour
                </a>
            </div>
            <div class="mt-10 flex items-center gap-3 text-sm text-slate-100/80">
                <div class="flex text-amber-300" aria-label="5 out of 5 stars">
                    <?php for ($i = 0; $i < 5; $i++) : ?>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    <?php endfor; ?>
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
                    <div class="w-11 h-11 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center mb-4">🚙</div>
                    <h3 class="font-semibold text-slate-900">Private Transport</h3>
                    <p class="text-sm text-slate-600 mt-2">Air-conditioned car with professional driver, only for your group.</p>
                </div>
                <div class="bg-slate-50 border border-slate-100 rounded-2xl p-6">
                    <div class="w-11 h-11 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center mb-4">🧭</div>
                    <h3 class="font-semibold text-slate-900">Expert Local Guides</h3>
                    <p class="text-sm text-slate-600 mt-2">English-speaking guides who know every hidden viewpoint and beach.</p>
                </div>
                <div class="bg-slate-50 border border-slate-100 rounded-2xl p-6">
                    <div class="w-11 h-11 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center mb-4">✨</div>
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
                        <span class="text-xs font-semibold uppercase tracking-wider text-amber-600">Most Popular</span>
                        <h3 class="mt-2 text-2xl font-serif font-semibold text-slate-900">Private Nusa Penida West Tour</h3>
                        <p class="mt-3 text-slate-600">Explore the most iconic spots in western Nusa Penida with a private guide and comfortable transportation.</p>

                        <h4 class="mt-5 text-xs font-semibold uppercase tracking-wider text-slate-500">Destinations</h4>
                        <ul class="mt-2 grid grid-cols-2 gap-y-1 text-sm text-slate-700">
                            <li>• Kelingking Beach</li>
                            <li>• Broken Beach</li>
                            <li>• Angel's Billabong</li>
                            <li>• Crystal Bay</li>
                        </ul>

                        <h4 class="mt-5 text-xs font-semibold uppercase tracking-wider text-slate-500">Includes</h4>
                        <ul class="mt-2 text-sm text-slate-700 space-y-1">
                            <li>✓ Private car &amp; driver</li>
                            <li>✓ English-speaking guide</li>
                            <li>✓ Hotel pickup &amp; drop-off</li>
                            <li>✓ Fast boat assistance</li>
                            <li>✓ Mineral water</li>
                        </ul>

                        <div class="mt-7 pt-5 border-t border-slate-100 flex items-end justify-between gap-4">
                            <div>
                                <div class="text-xs text-slate-500">Starting From</div>
                                <div class="text-2xl font-bold text-slate-900">IDR 850,000<span class="text-sm font-normal text-slate-500"> / person</span></div>
                            </div>
                            <a href="<?php echo esc_url($wa_link); ?>" target="_blank" rel="noopener" class="bg-slate-900 hover:bg-slate-800 text-white font-semibold px-5 py-2.5 rounded-full text-sm">Book West Tour</a>
                        </div>
                    </div>
                </article>

                <!-- 2. EAST TOUR -->
                <article class="bg-white rounded-2xl border border-slate-200 overflow-hidden flex flex-col shadow-sm hover:shadow-lg transition-shadow">
                    <div class="p-7 flex-1 flex flex-col">
                        <span class="text-xs font-semibold uppercase tracking-wider text-amber-600">Hidden Gems</span>
                        <h3 class="mt-2 text-2xl font-serif font-semibold text-slate-900">Private Nusa Penida East Tour</h3>
                        <p class="mt-3 text-slate-600">Discover the dramatic cliffs and hidden beaches of eastern Nusa Penida with a personalized private experience.</p>

                        <h4 class="mt-5 text-xs font-semibold uppercase tracking-wider text-slate-500">Destinations</h4>
                        <ul class="mt-2 grid grid-cols-2 gap-y-1 text-sm text-slate-700">
                            <li>• Diamond Beach</li>
                            <li>• Atuh Beach</li>
                            <li>• Tree House</li>
                            <li>• Thousand Island Viewpoint</li>
                        </ul>

                        <h4 class="mt-5 text-xs font-semibold uppercase tracking-wider text-slate-500">Includes</h4>
                        <ul class="mt-2 text-sm text-slate-700 space-y-1">
                            <li>✓ Private transportation</li>
                            <li>✓ Local guide</li>
                            <li>✓ Flexible itinerary</li>
                            <li>✓ Fast boat assistance</li>
                        </ul>

                        <div class="mt-7 pt-5 border-t border-slate-100 flex items-end justify-between gap-4">
                            <div>
                                <div class="text-xs text-slate-500">Starting From</div>
                                <div class="text-2xl font-bold text-slate-900">IDR 900,000<span class="text-sm font-normal text-slate-500"> / person</span></div>
                            </div>
                            <a href="<?php echo esc_url($wa_link); ?>" target="_blank" rel="noopener" class="bg-slate-900 hover:bg-slate-800 text-white font-semibold px-5 py-2.5 rounded-full text-sm">Book East Tour</a>
                        </div>
                    </div>
                </article>

                <!-- 3. ONE DAY TOUR -->
                <article class="bg-white rounded-2xl border border-slate-200 overflow-hidden flex flex-col shadow-sm hover:shadow-lg transition-shadow">
                    <div class="p-7 flex-1 flex flex-col">
                        <span class="text-xs font-semibold uppercase tracking-wider text-amber-600">From Bali</span>
                        <h3 class="mt-2 text-2xl font-serif font-semibold text-slate-900">One Day Nusa Penida Tour</h3>
                        <p class="mt-3 text-slate-600">Perfect for travelers staying in Bali who want to experience the highlights of Nusa Penida in one unforgettable day.</p>

                        <h4 class="mt-5 text-xs font-semibold uppercase tracking-wider text-slate-500">Includes</h4>
                        <ul class="mt-2 text-sm text-slate-700 space-y-1">
                            <li>✓ Return fast boat tickets</li>
                            <li>✓ Hotel transfer in Bali</li>
                            <li>✓ Private island tour</li>
                            <li>✓ Lunch</li>
                            <li>✓ Tour guide</li>
                            <li>✓ Comfortable transportation</li>
                        </ul>

                        <div class="mt-7 pt-5 border-t border-slate-100 flex items-end justify-between gap-4">
                            <div>
                                <div class="text-xs text-slate-500">Starting From</div>
                                <div class="text-2xl font-bold text-slate-900">IDR 1,500,000<span class="text-sm font-normal text-slate-500"> / person</span></div>
                            </div>
                            <a href="<?php echo esc_url($wa_link); ?>" target="_blank" rel="noopener" class="bg-slate-900 hover:bg-slate-800 text-white font-semibold px-5 py-2.5 rounded-full text-sm">Reserve Your Day Trip</a>
                        </div>
                    </div>
                </article>

                <!-- 4. PRIVATE SNORKELING -->
                <article class="bg-white rounded-2xl border border-slate-200 overflow-hidden flex flex-col shadow-sm hover:shadow-lg transition-shadow">
                    <div class="p-7 flex-1 flex flex-col">
                        <span class="text-xs font-semibold uppercase tracking-wider text-amber-600">For Couples</span>
                        <h3 class="mt-2 text-2xl font-serif font-semibold text-slate-900">Private Snorkeling Package</h3>
                        <p class="mt-3 text-slate-600">Experience the crystal-clear waters of Nusa Penida with a premium private snorkeling adventure. Swim alongside manta rays and explore Bali's most stunning underwater destinations with experienced local crews.</p>

                        <h4 class="mt-5 text-xs font-semibold uppercase tracking-wider text-slate-500">Snorkeling Spots</h4>
                        <ul class="mt-2 grid grid-cols-2 gap-y-1 text-sm text-slate-700">
                            <li>• Manta Point</li>
                            <li>• Crystal Bay</li>
                            <li>• Gamat Bay</li>
                            <li>• Wall Point</li>
                        </ul>

                        <h4 class="mt-5 text-xs font-semibold uppercase tracking-wider text-slate-500">Includes</h4>
                        <ul class="mt-2 text-sm text-slate-700 space-y-1">
                            <li>✓ Private snorkeling boat</li>
                            <li>✓ Snorkeling equipment</li>
                            <li>✓ Professional guide</li>
                            <li>✓ Safety equipment</li>
                            <li>✓ Mineral water</li>
                        </ul>

                        <p class="mt-5 text-sm text-slate-500">For complete snorkeling packages and premium experiences, visit
                            <a class="text-amber-700 font-semibold hover:underline" href="https://snorkelingpenida.com" target="_blank" rel="noopener">snorkelingpenida.com</a>.
                        </p>

                        <div class="mt-7 pt-5 border-t border-slate-100 flex items-end justify-between gap-4">
                            <div>
                                <div class="text-xs text-slate-500">Starting From</div>
                                <div class="text-2xl font-bold text-slate-900">IDR 1,800,000<span class="text-sm font-normal text-slate-500"> / couple</span></div>
                            </div>
                            <a href="https://snorkelingpenida.com" target="_blank" rel="noopener" class="bg-slate-900 hover:bg-slate-800 text-white font-semibold px-5 py-2.5 rounded-full text-sm">Explore Snorkeling Packages</a>
                        </div>
                    </div>
                </article>
            </div>

            <!-- 5. LUXURY BUNDLE (FULL WIDTH) -->
            <article class="mt-7 rounded-3xl overflow-hidden bg-gradient-to-br from-slate-900 via-slate-800 to-amber-900 text-white shadow-xl">
                <div class="grid grid-cols-1 lg:grid-cols-2">
                    <div class="p-8 md:p-12">
                        <span class="inline-block text-xs font-semibold uppercase tracking-[0.3em] text-amber-300">Signature Experience</span>
                        <h3 class="mt-3 text-3xl md:text-4xl font-serif font-semibold">The Ultimate Nusa Penida Escape</h3>
                        <p class="mt-5 text-slate-100/85 leading-relaxed">
                            A premium all-in-one island experience designed for couples, honeymooners, and luxury travelers seeking comfort and unforgettable moments. This exclusive package combines accommodation, private island tours, and luxury snorkeling into one seamless experience.
                        </p>

                        <h4 class="mt-7 text-xs font-semibold uppercase tracking-[0.25em] text-amber-300">Package Includes</h4>
                        <ul class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-y-2 text-sm">
                            <li>✓ Luxury accommodation</li>
                            <li>✓ Private West Nusa Penida Tour</li>
                            <li>✓ Private snorkeling experience</li>
                            <li>✓ Fast boat tickets</li>
                            <li>✓ Hotel transfers</li>
                            <li>✓ Professional local assistance</li>
                            <li>✓ Comfortable private transportation</li>
                        </ul>

                        <h4 class="mt-7 text-xs font-semibold uppercase tracking-[0.25em] text-amber-300">Perfect For</h4>
                        <p class="mt-2 text-sm text-slate-100/85">Honeymoon couples · Romantic getaways · Luxury Bali vacations · Private island experiences</p>
                    </div>

                    <div class="p-8 md:p-12 bg-black/30 flex flex-col justify-center">
                        <div class="text-xs uppercase tracking-[0.25em] text-amber-300">Package Price</div>
                        <div class="mt-2 text-5xl font-bold">IDR 5,000,000</div>
                        <div class="text-sm text-slate-200/80 mt-1">per package · for two travelers</div>
                        <a href="<?php echo esc_url($wa_link); ?>" target="_blank" rel="noopener"
                           class="mt-7 inline-flex items-center justify-center gap-2 bg-amber-400 hover:bg-amber-300 text-slate-900 font-semibold px-7 py-4 rounded-full transition-colors shadow-lg">
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
                    <div class="flex text-amber-500" aria-label="5 out of 5 stars">
                        <?php for ($i = 0; $i < 5; $i++) : ?>
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                        <?php endfor; ?>
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
                        <div itemprop="reviewRating" itemscope itemtype="https://schema.org/Rating" class="flex text-amber-500 mb-3">
                            <meta itemprop="ratingValue" content="5">
                            <meta itemprop="bestRating" content="5">
                            <?php for ($s = 0; $s < 5; $s++) : ?>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                            <?php endfor; ?>
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

            <div class="mt-10 grid grid-cols-2 md:grid-cols-4 gap-3 text-center text-sm text-slate-500">
                <div class="py-3 px-4 bg-slate-50 rounded-xl">⭐ Google Reviews</div>
                <div class="py-3 px-4 bg-slate-50 rounded-xl">💬 Guest Testimonials</div>
                <div class="py-3 px-4 bg-slate-50 rounded-xl">📷 Instagram Gallery</div>
                <div class="py-3 px-4 bg-slate-50 rounded-xl">🎬 Drone Videos</div>
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
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    Contact via WhatsApp
                </a>
                <a href="<?php echo esc_url($wa_link); ?>" target="_blank" rel="noopener"
                   class="inline-flex items-center gap-2 bg-amber-400 hover:bg-amber-300 text-slate-900 font-semibold px-7 py-3.5 rounded-full transition-colors shadow-lg">
                    Start Your Booking
                </a>
            </div>
        </div>
    </section>

    <!-- LOCAL FOOTER (page-specific quick links) -->
    <section class="bg-slate-950 text-slate-300">
        <div class="max-w-6xl mx-auto px-6 py-14 grid grid-cols-1 md:grid-cols-3 gap-10">
            <div>
                <h4 class="text-white font-serif text-lg mb-4">Quick Links</h4>
                <ul class="space-y-2 text-sm">
                    <li><a class="hover:text-amber-300" href="#packages">West Tour</a></li>
                    <li><a class="hover:text-amber-300" href="#packages">East Tour</a></li>
                    <li><a class="hover:text-amber-300" href="#packages">One Day Tour</a></li>
                    <li><a class="hover:text-amber-300" href="https://snorkelingpenida.com" target="_blank" rel="noopener">Snorkeling Packages</a></li>
                    <li><a class="hover:text-amber-300" href="#packages">Luxury Bundle Package</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-serif text-lg mb-4">Contact</h4>
                <p class="text-sm leading-relaxed">
                    Activities Penida Tour<br>
                    <a class="hover:text-amber-300" href="https://activitiespenidatour.com" target="_blank" rel="noopener">activitiespenidatour.com</a><br>
                    <a class="hover:text-amber-300" href="https://snorkelingpenida.com" target="_blank" rel="noopener">snorkelingpenida.com</a>
                </p>
                <a href="<?php echo esc_url($wa_link); ?>" target="_blank" rel="noopener" class="mt-4 inline-flex items-center gap-2 text-emerald-400 hover:text-emerald-300 text-sm font-semibold">💬 WhatsApp our concierge</a>
            </div>
            <div>
                <h4 class="text-white font-serif text-lg mb-4">About</h4>
                <p class="text-sm leading-relaxed text-slate-400">
                    Premium private tours and luxury snorkeling experiences in Nusa Penida for travelers seeking comfort, exclusivity, and unforgettable island memories.
                </p>
            </div>
        </div>
    </section>

</main>

<?php get_template_part('template-parts/footer'); ?>
