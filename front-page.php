<?php
/**
 * Front-page | Penidatour Private Tours Nusa Penida
 */
get_template_part('template-parts/header'); ?>
 <script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "LocalBusiness",
  "name": "Penidatour",
  "image": "http://activitiespenidatour.com/wp-content/uploads/2025/09/cropped-logo-activities-penidatour-nusa-penida.png",
  "aggregateRating": {
    "@type": "AggregateRating",
    "ratingValue": "5.0",
    "reviewCount": "7944"
  },
  "review": {
    "@type": "Review",
    "author": {
      "@type": "Person",
      "name": "Sarah & Tom"
    },
    "datePublished": "2025-09-15",
    "reviewRating": {
      "@type": "Rating",
      "ratingValue": "5.0",
      "bestRating": "5"
    },
    "reviewBody": "Our driver, Kadek, was incredible - so friendly, a safe driver on those crazy roads, and he took the most amazing photos for us! Worth every penny for a stress-free day."
  }
}
</script>
<!-- Mobile-optimized hero section -->
<section class="relative pt-16 sm:pt-20 min-h-screen flex items-center overflow-hidden">
    <!-- Background image -->
   <img src="<?php echo esc_url( home_url( '/wp-content/uploads/2025/10/klingking-beach-activities-penidatour-ikomangartawann.webp' ) ); ?>"
     alt="Private Tours Nusa Penida | Penidatour | Kelingking Beach"
     class="absolute inset-0 w-full h-full object-cover object-center z-0"
     fetchpriority="high" decoding="async">


    <!-- Overlay -->
    <div class="absolute inset-0 bg-black/60 z-0"></div>

    <!-- Content -->
    <div class="relative w-full px-4 sm:px-6 py-12 sm:py-24 md:py-32 text-center z-10">
        <h1 class="text-4xl sm:text-5xl md:text-7xl font-bold text-white tracking-tight leading-tight mb-6 sm:mb-8">
            Escape the Crowds.<br>
            <span class="font-light text-teal-200">Discover Nusa Penida.</span>
        </h1>
        <p class="text-lg sm:text-xl md:text-2xl text-gray-200 max-w-3xl mx-auto font-light leading-relaxed mb-8 sm:mb-12 px-4">
            Experience Nusa Penida's breathtaking beauty with our premium private car tours.<br class="hidden sm:block">
            Safe, comfortable, and completely personalized to your pace.
        </p>
        <div class="px-4">
            <a href="https://wa.me/6281337567256?text=Hello,%20I'm%20interested%20in%20booking%20a%20private%20tour%20in%20Nusa%20Penida.%20Can%20you%20please%20send%20me%20more%20details%20and%20pricing?" target="_blank"
                class="inline-flex items-center bg-teal-600 text-white px-6 sm:px-8 py-3 sm:py-4 rounded-full text-base sm:text-lg font-medium hover:bg-teal-700 transition-all duration-300 transform hover:scale-105 active:scale-95 w-full sm:w-auto justify-center">
                Explore Our Tours
                <svg class="ml-2 w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
            </a>
        </div>
    </div>
</section>


<!-- Why Choose Private Tour Section -->
<section class="bg-white px-4 sm:px-6 py-16 sm:py-20 md:py-32 text-center">
    <div class="max-w-6xl mx-auto">
        <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold mb-4 sm:mb-6 text-teal-800">
            Escape the Crowds. Forget the Hassle.
        </h2>
        <p class="text-lg sm:text-xl text-gray-600 max-w-4xl mx-auto mb-12 sm:mb-16 leading-relaxed">
            Nusa Penida's beauty is matched by its challenging terrain. A private car tour is not a luxury—it's the key to a safe, comfortable, and truly relaxing holiday. Here's why savvy travelers choose us.
        </p>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 sm:gap-6 md:gap-8">
            <!-- Safety & Comfort -->
            <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-lg hover:shadow-xl transition-shadow duration-300 text-center">
                <div class="w-12 h-12 sm:w-16 sm:h-16 mb-4 sm:mb-6 text-emerald-600 mx-auto flex items-center justify-center">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5" class="w-full h-full">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.333 9-6.031 9-11.623C21 7.51 20.402 5.326 18.402 4A11.96 11.96 0 0 1 12 2.764Z"/>
                    </svg>
                </div>
                <h3 class="text-xl sm:text-2xl font-semibold mb-3 sm:mb-4 text-gray-900">Ultimate Safety & Comfort</h3>
                <p class="text-gray-600 text-base sm:text-lg leading-relaxed">
                    Navigate bumpy roads with ease in our modern, air-conditioned private cars, driven by experienced local professionals.
                </p>
            </div>

            <!-- Personal Guide -->
            <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-lg hover:shadow-xl transition-shadow duration-300 text-center">
                <div class="w-12 h-12 sm:w-16 sm:h-16 mb-4 sm:mb-6 text-teal-600 mx-auto flex items-center justify-center">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5" class="w-full h-full">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z"/>
                    </svg>
                </div>
                <h3 class="text-xl sm:text-2xl font-semibold mb-3 sm:mb-4 text-gray-900">Personal Guide & Photographer</h3>
                <p class="text-gray-600 text-base sm:text-lg leading-relaxed">
                    Your driver is more than just a driver. They're your guide, and your personal photographer, skilled at capturing your best moments.
                </p>
            </div>

            <!-- All-Inclusive -->
            <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-lg hover:shadow-xl transition-shadow duration-300 text-center">
                <div class="w-12 h-12 sm:w-16 sm:h-16 mb-4 sm:mb-6 text-green-600 mx-auto flex items-center justify-center">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5" class="w-full h-full">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H3.75m0 0c.621 0 1.125.504 1.125 1.125v.375m0 0L6 8.25m0 0l1.5 1.5M6 8.25l1.5-1.5m0 0L9 8.25m0 0l1.5 1.5M9 8.25l1.5-1.5m0 0L12 8.25m0 0l1.5 1.5M12 8.25l1.5-1.5m0 0L15 8.25m0 0l1.5 1.5M15 8.25l1.5-1.5m0 0L18 8.25m0 0l1.5 1.5M18 8.25l1.5-1.5"/>
                    </svg>
                </div>
                <h3 class="text-xl sm:text-2xl font-semibold mb-3 sm:mb-4 text-gray-900">All-Inclusive & Transparent</h3>
                <p class="text-gray-600 text-base sm:text-lg leading-relaxed">
                    Your tour price includes everything: entrance fees, lunch, and private transport. No hidden costs, no surprises.
                </p>
            </div>

            <!-- Flexible -->
            <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-lg hover:shadow-xl transition-shadow duration-300 text-center">
                <div class="w-12 h-12 sm:w-16 sm:h-16 mb-4 sm:mb-6 text-blue-600 mx-auto flex items-center justify-center">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5" class="w-full h-full">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/>
                    </svg>
                </div>
                <h3 class="text-xl sm:text-2xl font-semibold mb-3 sm:mb-4 text-gray-900">Your Pace, Your Schedule</h3>
                <p class="text-gray-600 text-base sm:text-lg leading-relaxed">
                    Want to linger longer at Kelingking Beach? Or skip a spot? It's your day. Enjoy the flexibility that only a private tour can offer.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Signature Tours Section -->
<section id="tours" class="bg-gray-50 px-4 sm:px-6 py-16 sm:py-20 md:py-32">
    <div class="max-w-7xl mx-auto">
        <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold text-center mb-12 sm:mb-16 text-teal-800">
            Our Signature Private Tours
        </h2>
        <p class="text-lg sm:text-xl text-gray-600 max-w-3xl mx-auto text-center mb-12 sm:mb-16 leading-relaxed">
            Choose your perfect adventure. All tours include private transport, professional guide, and unforgettable memories.
        </p>

        <!-- Mobile App-like Card Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">

            <!-- West Tour Card -->
            <div class="bg-white rounded-3xl shadow-xl overflow-hidden transform hover:scale-105 transition-all duration-300 hover:shadow-2xl">
                <!-- Card Image -->
                <div class="relative h-48 sm:h-56 overflow-hidden">
                    <img src="<?php echo esc_url(home_url()); ?>/wp-content/uploads/2025/10/klingking-beach2-activities-penidatour-scaled_komangtawan-1_11zon.webp"
                         alt="West Tour - Kelingking Beach T-Rex View"
                         class="w-full h-full object-cover" loading="lazy">
                    <div class="absolute top-4 left-4">
                        <span class="bg-teal-600 text-white px-3 py-1 rounded-full text-sm font-semibold">Most Popular</span>
                    </div>
                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/60 to-transparent p-4">
                        <h3 class="text-white font-bold text-xl">West Nusa Penida</h3>
                        <p class="text-gray-200 text-sm">Famous Instagram Spots</p>
                    </div>
                </div>

                <!-- Card Content -->
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center text-yellow-400">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                <path d="m12 2 3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                            <span class="ml-1 text-gray-600 text-sm">4.9 (127 reviews)</span>
                        </div>
                    </div>

                    <ul class="space-y-2 mb-6 text-gray-600">
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-teal-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m9 12 2 2 4-4"/>
                            </svg>
                            <span class="text-sm">Kelingking Beach (T-Rex)</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-teal-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m9 12 2 2 4-4"/>
                            </svg>
                            <span class="text-sm">Angel's Billabong</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-teal-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m9 12 2 2 4-4"/>
                            </svg>
                            <span class="text-sm">Broken Beach</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-teal-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m9 12 2 2 4-4"/>
                            </svg>
                            <span class="text-sm">Crystal Bay</span>
                        </li>
                    </ul>

                    <a href="https://booking.activitiespenidatour.com/booking?product=4df0ac840cca94f09b60"
                       target="_blank" rel="noopener noreferrer"
                       class="w-full bg-gradient-to-r from-teal-600 to-teal-700 text-white font-bold py-3 px-6 rounded-full hover:from-teal-700 hover:to-teal-800 transition-all duration-300 text-center inline-block shadow-lg hover:shadow-xl">
                        Book West Tour
                    </a>
                </div>
            </div>

            <!-- East Tour Card -->
            <div class="bg-white rounded-3xl shadow-xl overflow-hidden transform hover:scale-105 transition-all duration-300 hover:shadow-2xl">
                <!-- Card Image -->
                <div class="relative h-48 sm:h-56 overflow-hidden">
                    <img src="<?php echo esc_url(home_url()); ?>/wp-content/uploads/2025/10/diamond-beach-activity-penidatour-komangtawan.webp"
                         alt="East Tour - Diamond Beach White Sand"
                         class="w-full h-full object-cover" loading="lazy">
                    <div class="absolute top-4 left-4">
                        <span class="bg-blue-600 text-white px-3 py-1 rounded-full text-sm font-semibold">Adventure</span>
                    </div>
                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/60 to-transparent p-4">
                        <h3 class="text-white font-bold text-xl">East Nusa Penida</h3>
                        <p class="text-gray-200 text-sm">Hidden Paradise Beaches</p>
                    </div>
                </div>

                <!-- Card Content -->
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center text-yellow-400">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                <path d="m12 2 3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                            <span class="ml-1 text-gray-600 text-sm">4.8 (89 reviews)</span>
                        </div>
                    </div>

                    <ul class="space-y-2 mb-6 text-gray-600">
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m9 12 2 2 4-4"/>
                            </svg>
                            <span class="text-sm">Diamond Beach</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m9 12 2 2 4-4"/>
                            </svg>
                            <span class="text-sm">Atuh Beach</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m9 12 2 2 4-4"/>
                            </svg>
                            <span class="text-sm">Tree House Viewpoint</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m9 12 2 2 4-4"/>
                            </svg>
                            <span class="text-sm">Thousands Island</span>
                        </li>
                    </ul>

                    <a href="https://booking.activitiespenidatour.com/booking?product=de02c7b9596b94a61407"
                       target="_blank" rel="noopener noreferrer"
                       class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white font-bold py-3 px-6 rounded-full hover:from-blue-700 hover:to-blue-800 transition-all duration-300 text-center inline-block shadow-lg hover:shadow-xl">
                        Book East Tour
                    </a>
                </div>
            </div>

            <!-- Combination Tour Card -->
            <div class="bg-white rounded-3xl shadow-xl overflow-hidden transform hover:scale-105 transition-all duration-300 hover:shadow-2xl">
                <!-- Card Image -->
                <div class="relative h-48 sm:h-56 overflow-hidden">
                    <img src="<?php echo esc_url(home_url()); ?>/wp-content/uploads/2025/09/snorkling-crystal-beach-activities-penidatour.webp"
                         alt="Snorkling | Combination Tour - Complete Island Experience | Activities Penidatour"
                         class="w-full h-full object-cover" loading="lazy">
                    <div class="absolute top-4 left-4">
                        <span class="bg-purple-600 text-white px-3 py-1 rounded-full text-sm font-semibold">Best Value</span>
                    </div>
                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/60 to-transparent p-4">
                        <h3 class="text-white font-bold text-xl">Combination Tour</h3>
                        <p class="text-gray-200 text-sm">Complete Island Experience</p>
                    </div>
                </div>

                <!-- Card Content -->
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center text-yellow-400">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                <path d="m12 2 3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                            <span class="ml-1 text-gray-600 text-sm">5.0 (45 reviews)</span>
                        </div>
                    </div>

                    <ul class="space-y-2 mb-6 text-gray-600">
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-purple-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m9 12 2 2 4-4"/>
                            </svg>
                            <span class="text-sm">Best of West & East</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-purple-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m9 12 2 2 4-4"/>
                            </svg>
                            <span class="text-sm">2 Days 1 Night</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-purple-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m9 12 2 2 4-4"/>
                            </svg>
                            <span class="text-sm">Accommodation</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-purple-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m9 12 2 2 4-4"/>
                            </svg>
                            <span class="text-sm">Snorkeling Option</span>
                        </li>
                    </ul>

                    <a href="https://wa.me/6281337567256?text=Hi!%20I'm%20interested%20in%20booking%20the%20Combination%20Tour%20(2D1N).%20Can%20you%20send%20me%20more%20details?"
                       target="_blank" rel="noopener noreferrer"
                       class="w-full bg-gradient-to-r from-purple-600 to-purple-700 text-white font-bold py-3 px-6 rounded-full hover:from-purple-700 hover:to-purple-800 transition-all duration-300 text-center inline-block shadow-lg hover:shadow-xl">
                        Book Combo Tour
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Gallery Section -->
<section id="gallery" class="bg-gray-50 px-4 sm:px-6 py-16 sm:py-20 md:py-32">
    <div class="max-w-6xl mx-auto">
        <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold text-center mb-12 sm:mb-16 text-teal-800">
            Gallery - Experience Nusa Penida's Beauty
        </h2>
        <p class="text-lg sm:text-xl text-gray-600 max-w-3xl mx-auto text-center mb-12 sm:mb-16 leading-relaxed">
            See the stunning destinations and memorable moments from our private tours. These photos were all taken by our professional guides for our happy guests.
        </p>

        <!-- Mobile-Friendly Gallery Slider -->
        <div class="relative">
            <!-- Desktop Grid (hidden on mobile) -->
            <div class="hidden md:grid md:grid-cols-3 gap-6">
                <?php
                $galleries = [
                    ['url' => home_url('/wp-content/uploads/2025/10/klingking-beach-camping-nusa-penida-scaled_ikomang-artawan.webp'), 'alt' => 'Kelingking Beach - The T-Rex viewpoint from our West Tour', 'title' => 'Kelingking Beach T-Rex Viewpoint'],
                    ['url' => home_url('/wp-content/uploads/2025/10/broken-beach-activities-penidatour-scaled_komangtawan.webp'), 'alt' => 'Broken Beach natural arch - West Nusa Penida highlight', 'title' => 'Broken Beach Natural Arch'],
                    ['url' => home_url('/wp-content/uploads/2025/09/Diamond-beach-white-sands-activity-penidatour-scaled.webp'), 'alt' => 'Diamond Beach pristine white sands - East Tour destination', 'title' => 'Diamond Beach White Sands'],
                    ['url' => home_url('/wp-content/uploads/2025/09/crystal-beach-nusapenidacamping.webp'), 'alt' => 'Crystal Bay sunset views - Perfect photo opportunity', 'title' => 'Crystal Bay Sunset Views'],
                    ['url' => home_url('/wp-content/uploads/2025/10/pexels-darren-lawrence-rumahpohon-scaled_11zon.webp'), 'alt' => 'Tree House viewpoint - Instagram famous spot', 'title' => 'Tree House Viewpoint'],
                    ['url' => home_url('/wp-content/uploads/2025/10/ocean-patung-nusa-penida-scaled_11zon.webp'), 'alt' => 'Octopus Queen | Activities Penidatour', 'title' => 'Octopus Queen | Activities Penidatour']
                ];
                foreach ($galleries as $index => $img) : ?>
                <figure class="group relative overflow-hidden rounded-2xl shadow-sm hover:shadow-lg transition-shadow duration-300 cursor-pointer gallery-item"
                        data-index="<?= $index ?>"
                        data-src="<?= esc_attr($img['url']) ?>"
                        data-alt="<?= esc_attr($img['alt']) ?>"
                        data-title="<?= esc_attr($img['title']) ?>">
                    <img data-src="<?= esc_url($img['url']) ?>" alt="<?= esc_attr($img['alt']) ?>" title="<?= esc_attr($img['title']) ?>"
                        class="lazy w-full h-64 object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
                    <figcaption class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent p-4">
                        <span class="text-white text-sm font-medium leading-tight"><?= esc_html($img['title']) ?></span>
                    </figcaption>
                </figure>
                <?php endforeach; ?>
            </div>

            <!-- Mobile Slider (visible only on mobile) -->
            <div class="md:hidden">
                <div class="gallery-slider overflow-x-auto">
                    <div class="flex space-x-4 pb-4" style="width: max-content;">
                        <?php foreach ($galleries as $index => $img) : ?>
                        <figure class="relative overflow-hidden rounded-2xl shadow-sm hover:shadow-lg transition-shadow duration-300 cursor-pointer gallery-item flex-shrink-0"
                                style="width: 280px;"
                                data-index="<?= $index ?>"
                                data-src="<?= esc_attr($img['url']) ?>"
                                data-alt="<?= esc_attr($img['alt']) ?>"
                                data-title="<?= esc_attr($img['title']) ?>">
                            <img data-src="<?= esc_url($img['url']) ?>" alt="<?= esc_attr($img['alt']) ?>" title="<?= esc_attr($img['title']) ?>"
                                class="lazy w-full h-48 object-cover" loading="lazy">
                            <figcaption class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent p-4">
                                <span class="text-white text-sm font-medium leading-tight"><?= esc_html($img['title']) ?></span>
                            </figcaption>
                        </figure>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Image Modal -->
        <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-90 z-50 hidden items-center justify-center p-4">
            <div class="relative max-w-4xl max-h-full">
                <!-- Close Button -->
                <button id="closeModal" class="absolute -top-10 right-0 text-white hover:text-gray-300 z-10">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>

                <!-- Navigation Buttons -->
                <button id="prevImage" class="absolute left-4 top-1/2 transform -translate-y-1/2 text-white hover:text-gray-300 z-10">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>

                <button id="nextImage" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-white hover:text-gray-300 z-10">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>

                <!-- Image Container -->
                <div class="text-center">
                    <img id="modalImage" src="" alt="" class="max-w-full max-h-[70vh] object-contain rounded-lg shadow-2xl" loading="lazy">
                    <div class="text-white mt-4">
                        <h3 id="modalTitle" class="text-xl font-semibold mb-2"></h3>
                        <p id="modalAlt" class="text-gray-300 text-sm"></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- CTA in gallery -->
        <div class="text-center mt-12 sm:mt-16">
            <a href="https://wa.me/6281337567256?text=Hi%2C%20I%27d%20like%20to%20see%20more%20photos%20from%20your%20tours%20and%20get%20pricing%20information."
                target="_blank" rel="noopener noreferrer"
                class="inline-flex items-center bg-green-600 text-white px-8 py-4 rounded-full font-semibold text-lg hover:bg-green-700 transition duration-300 shadow-lg hover:shadow-xl">
                <div class="w-6 h-6 mr-3">
                    <svg fill="currentColor" viewBox="0 0 24 24" class="w-full h-full">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.106"/>
                    </svg>
                </div>
                View More Photos via WhatsApp
            </a>
        </div>
    </div>
</section>

<!-- Testimonials -->
<section class="bg-white px-4 sm:px-6 py-16 sm:py-20 md:py-32">
    <div class="max-w-6xl mx-auto">
        <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold text-center mb-12 sm:mb-16 text-teal-800">
            What Our Guests Say
        </h2>

        <div class="grid gap-6 sm:gap-8 md:grid-cols-2 lg:grid-cols-3">
            <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-lg hover:shadow-xl transition-shadow duration-300 text-center">
                <p class="text-gray-600 mb-4 sm:mb-6 text-base sm:text-lg leading-relaxed">
                    "Absolutely the best decision we made. Our driver, Kadek, was incredible - so friendly, a safe driver on those crazy roads, and he took the most amazing photos for us! Worth every penny for a stress-free day."
                </p>
                <p class="font-semibold text-gray-800 text-base sm:text-lg">- Sarah & Tom, Australia</p>
            </div>

            <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-lg hover:shadow-xl transition-shadow duration-300 text-center">
                <p class="text-gray-600 mb-4 sm:mb-6 text-base sm:text-lg leading-relaxed">
                    "We saw other tourists packed into shared vans or struggling on scooters. Our private car was a cool, comfortable oasis. The all-inclusive price meant we didn't have to worry about a thing. Highly recommend Penidatour!"
                </p>
                <p class="font-semibold text-gray-800 text-base sm:text-lg">- Michael, USA</p>
            </div>

            <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-lg hover:shadow-xl transition-shadow duration-300 md:col-span-2 lg:col-span-1 mx-auto max-w-md md:max-w-none">
                <p class="text-gray-600 mb-4 sm:mb-6 text-base sm:text-lg leading-relaxed">
                    "As a solo female traveler, safety was my priority. The team was professional from booking to drop-off. My guide made me feel completely at ease and helped me see all the highlights without any hassle. A five-star experience!"
                </p>
                <p class="font-semibold text-gray-800 text-base sm:text-lg">- Chloe, Singapore</p>
            </div>
        </div>
    </div>
</section>

<!-- Booking & Final CTA -->
<section id="booking" class="bg-white px-4 sm:px-6 py-12 sm:py-16 md:py-20">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white p-8 sm:p-12 rounded-2xl shadow-2xl text-center">
            <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold mb-4 sm:mb-6 text-teal-800">
                Ready for Your Nusa Penida Adventure?
            </h2>
            <p class="text-lg sm:text-xl text-gray-600 max-w-3xl mx-auto mb-8 sm:mb-12 leading-relaxed">
                Let us handle the details so you can focus on making memories. Our team is ready to help you craft the perfect private tour.
            </p>
            <div class="flex justify-center">
                <a href="https://wa.me/6281337567256?text=Hello,%20I'm%20interested%20in%20booking%20a%20private%20tour%20in%20Nusa%20Penida.%20Can%20you%20please%20send%20me%20more%20details%20and%20pricing?"
                   target="_blank" rel="noopener noreferrer"
                   class="inline-flex items-center bg-green-600 text-white font-bold py-5 px-10 sm:px-12 rounded-full text-lg sm:text-xl hover:bg-green-700 transition duration-300 shadow-lg hover:shadow-xl">
                    <div class="w-7 h-7 mr-4">
                        <svg fill="currentColor" viewBox="0 0 24 24" class="w-full h-full">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.106"/>
                        </svg>
                    </div>
                    Book Your Private Tour Now
                </a>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="bg-gray-50 px-4 sm:px-6 py-16 sm:py-20 md:py-32">
    <div class="max-w-4xl mx-auto">
        <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold text-center mb-12 sm:mb-16 text-teal-800">
            Frequently Asked Questions
        </h2>
        <p class="text-lg sm:text-xl text-gray-600 text-center mb-12 sm:mb-16 leading-relaxed">
            Everything you need to know about our private tours in Nusa Penida
        </p>

        <div class="space-y-4">
            <!-- FAQ Item 1 -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
                <button class="faq-toggle w-full text-left p-6 sm:p-8 flex justify-between items-center hover:bg-gray-50 transition-colors duration-200" type="button">
                    <h3 class="text-lg sm:text-xl font-semibold text-gray-900 pr-4" itemprop="name">
                        What's included in the private tour price?
                    </h3>
                    <svg class="w-6 h-6 text-teal-600 transform transition-transform duration-200 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div class="faq-content overflow-hidden transition-all duration-300 ease-out" style="max-height: 0px;">
                    <div class="p-6 sm:p-8 pt-0 text-gray-600 leading-relaxed" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
                        <div itemprop="text">
                            <p class="mb-4">Our private tour price is all-inclusive and covers:</p>
                            <ul class="space-y-2 ml-4">
                                <li>• Private air-conditioned car with experienced driver/guide</li>
                                <li>• All entrance fees to attractions</li>
                                <li>• Traditional Indonesian lunch</li>
                                <li>• Professional photography service</li>
                                <li>• Bottled water throughout the day</li>
                            </ul>
                            <p class="mt-4">No hidden costs, no surprises - everything is included in one transparent price.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FAQ Item 2 -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
                <button class="faq-toggle w-full text-left p-6 sm:p-8 flex justify-between items-center hover:bg-gray-50 transition-colors duration-200" type="button">
                    <h3 class="text-lg sm:text-xl font-semibold text-gray-900 pr-4" itemprop="name">
                        How long does the tour take and what time do we start?
                    </h3>
                    <svg class="w-6 h-6 text-teal-600 transform transition-transform duration-200 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div class="faq-content overflow-hidden transition-all duration-300 ease-out" style="max-height: 0px;">
                    <div class="p-6 sm:p-8 pt-0 text-gray-600 leading-relaxed" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
                        <div itemprop="text">
                            <p>We start early to maximize your time exploring the island:</p>
                            <ul class="space-y-2 ml-4 mt-4">
                                <li><strong>8:00 AM</strong> - Meet your private guide at agreed location in Nusa Penida</li>
                                <li><strong>8:30 AM - 4:00 PM</strong> - Full day island exploration with lunch break</li>
                                <li><strong>4:30 PM</strong> - Tour completion at your preferred end location</li>
                            </ul>
                            <p class="mt-4">The tour duration is approximately 8 hours of comprehensive Nusa Penida exploration.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FAQ Item 3 -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
                <button class="faq-toggle w-full text-left p-6 sm:p-8 flex justify-between items-center hover:bg-gray-50 transition-colors duration-200" type="button">
                    <h3 class="text-lg sm:text-xl font-semibold text-gray-900 pr-4" itemprop="name">
                        Is it safe to drive on Nusa Penida roads?
                    </h3>
                    <svg class="w-6 h-6 text-teal-600 transform transition-transform duration-200 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div class="faq-content overflow-hidden transition-all duration-300 ease-out" style="max-height: 0px;">
                    <div class="p-6 sm:p-8 pt-0 text-gray-600 leading-relaxed" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
                        <div itemprop="text">
                            <p>Safety is our top priority. Here's why our private car tours are the safest option:</p>
                            <ul class="space-y-2 ml-4 mt-4">
                                <li>• <strong>Local expertise:</strong> Our drivers are born and raised on Nusa Penida, knowing every curve and pothole</li>
                                <li>• <strong>Proper vehicles:</strong> We use modern, well-maintained cars with air conditioning and safety features</li>
                                <li>• <strong>No scooter risks:</strong> Unlike renting scooters, you travel safely in an enclosed vehicle</li>
                                <li>• <strong>Insurance covered:</strong> All our vehicles and guests are fully insured</li>
                                <li>• <strong>Emergency prepared:</strong> Drivers carry first aid kits and emergency contact numbers</li>
                            </ul>
                            <p class="mt-4">The roads can be challenging, but with our experienced local drivers, you can relax and enjoy the scenery safely.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FAQ Item 4 -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
                <button class="faq-toggle w-full text-left p-6 sm:p-8 flex justify-between items-center hover:bg-gray-50 transition-colors duration-200" type="button">
                    <h3 class="text-lg sm:text-xl font-semibold text-gray-900 pr-4" itemprop="name">
                        What should I bring for the tour?
                    </h3>
                    <svg class="w-6 h-6 text-teal-600 transform transition-transform duration-200 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div class="faq-content overflow-hidden transition-all duration-300 ease-out" style="max-height: 0px;">
                    <div class="p-6 sm:p-8 pt-0 text-gray-600 leading-relaxed" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
                        <div itemprop="text">
                            <p>Pack light but smart for your Nusa Penida adventure:</p>
                            <div class="grid sm:grid-cols-2 gap-4 mt-4">
                                <div>
                                    <h4 class="font-semibold text-gray-900 mb-2">Essential Items:</h4>
                                    <ul class="space-y-1 text-sm">
                                        <li>• Sunscreen (SPF 30+)</li>
                                        <li>• Comfortable walking shoes</li>
                                        <li>• Hat and sunglasses</li>
                                        <li>• Camera/phone charger</li>
                                        <li>• Light jacket (can be windy)</li>
                                    </ul>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900 mb-2">Optional:</h4>
                                    <ul class="space-y-1 text-sm">
                                        <li>• Swimwear (if planning to swim)</li>
                                        <li>• Small backpack</li>
                                        <li>• Personal snacks</li>
                                        <li>• Motion sickness medication</li>
                                        <li>• Cash for souvenirs</li>
                                    </ul>
                                </div>
                            </div>
                            <p class="mt-4 text-sm text-gray-500">Note: We provide bottled water and lunch, so no need to bring heavy food items.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FAQ Item 5 -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
                <button class="faq-toggle w-full text-left p-6 sm:p-8 flex justify-between items-center hover:bg-gray-50 transition-colors duration-200" type="button">
                    <h3 class="text-lg sm:text-xl font-semibold text-gray-900 pr-4" itemprop="name">
                        Can we customize the itinerary?
                    </h3>
                    <svg class="w-6 h-6 text-teal-600 transform transition-transform duration-200 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div class="faq-content overflow-hidden transition-all duration-300 ease-out" style="max-height: 0px;">
                    <div class="p-6 sm:p-8 pt-0 text-gray-600 leading-relaxed" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
                        <div itemprop="text">
                            <p>Absolutely! That's the beauty of a private tour - it's <strong>your</strong> adventure:</p>
                            <ul class="space-y-2 ml-4 mt-4">
                                <li>• <strong>Flexible timing:</strong> Spend longer at places you love, skip places that don't interest you</li>
                                <li>• <strong>Add stops:</strong> Want to visit a specific beach or viewpoint? Just let us know</li>
                                <li>• <strong>Photo priorities:</strong> Tell us your must-have Instagram shots and we'll make them happen</li>
                                <li>• <strong>Physical limitations:</strong> Some spots require hiking - we can adjust based on your comfort level</li>
                                <li>• <strong>Special occasions:</strong> Celebrating an anniversary or birthday? We can arrange special touches</li>
                            </ul>
                            <p class="mt-4">Just discuss your preferences when booking, and we'll create the perfect itinerary for your group.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FAQ Item 6 -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
                <button class="faq-toggle w-full text-left p-6 sm:p-8 flex justify-between items-center hover:bg-gray-50 transition-colors duration-200" type="button">
                    <h3 class="text-lg sm:text-xl font-semibold text-gray-900 pr-4" itemprop="name">
                        What happens if the weather is bad?
                    </h3>
                    <svg class="w-6 h-6 text-teal-600 transform transition-transform duration-200 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div class="faq-content overflow-hidden transition-all duration-300 ease-out" style="max-height: 0px;">
                    <div class="p-6 sm:p-8 pt-0 text-gray-600 leading-relaxed" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
                        <div itemprop="text">
                            <p>We monitor weather conditions closely and have flexible policies:</p>
                            <ul class="space-y-2 ml-4 mt-4">
                                <li>• <strong>Light rain:</strong> Tours continue with umbrellas provided - some of the best photos happen in dramatic weather!</li>
                                <li>• <strong>Heavy rain/storms:</strong> We can adjust timing or reschedule for your safety and enjoyment</li>
                                <li>• <strong>Severe weather:</strong> If conditions are unsafe, we offer full refund or reschedule at no extra cost</li>
                                <li>• <strong>Indoor alternatives:</strong> We know covered viewpoints and cultural sites for rainy day backup plans</li>
                                <li>• <strong>Flexible rebooking:</strong> Weather-related changes incur no penalties</li>
                            </ul>
                            <p class="mt-4">Our local knowledge helps us work around weather patterns to still deliver an amazing experience, even if conditions aren't perfect.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- FAQ CTA -->
        <div class="text-center mt-12 sm:mt-16">
            <p class="text-lg text-gray-600 mb-6">Still have questions?</p>
            <a href="https://wa.me/6281337567256?text=Hi!%20I%20have%20some%20questions%20about%20your%20Nusa%20Penida%20private%20tours.%20Could%20you%20help%20me%20with%20more%20details?"
               target="_blank" rel="noopener noreferrer"
               class="inline-flex items-center bg-teal-600 text-white font-semibold py-3 px-8 rounded-full hover:bg-teal-700 transition duration-300 shadow-lg hover:shadow-xl">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 0 1-2.555-.337A5.972 5.972 0 0 1 5.41 20.97a5.969 5.969 0 0 1-.474-.065 4.48 4.48 0 0 0 .978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25Z"/>
                </svg>
                Chat with us directly
            </a>
        </div>
    </div>
</section>

<!-- Structured Data for SEO -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    {
      "@type": "Question",
      "name": "What's included in the private tour price?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Our private tour price is all-inclusive and covers: Private air-conditioned car with experienced driver/guide, All entrance fees to attractions, Traditional Indonesian lunch, Professional photography service, Bottled water throughout the day. No hidden costs, no surprises."
      }
    },
    {
      "@type": "Question",
      "name": "How long does the tour take and what time do we start?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "We start at 8:00 AM meeting your private guide at agreed location in Nusa Penida, explore the island from 8:30 AM to 4:00 PM with lunch break, and complete the tour at 4:30 PM at your preferred end location. Total tour duration is approximately 8 hours of comprehensive Nusa Penida exploration."
      }
    },
    {
      "@type": "Question",
      "name": "Is it safe to drive on Nusa Penida roads?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Safety is our top priority. Our drivers are local experts who know every road, we use modern well-maintained cars with air conditioning, all vehicles and guests are fully insured, and drivers carry first aid kits and emergency contacts. Much safer than renting scooters."
      }
    },
    {
      "@type": "Question",
      "name": "Can we customize the itinerary?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Absolutely! Being a private tour, you have flexible timing, can add stops, prioritize specific photo locations, accommodate physical limitations, and arrange special touches for celebrations. Just discuss your preferences when booking."
      }
    },
    {
      "@type": "Question",
      "name": "What happens if the weather is bad?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "We monitor weather closely and offer flexible policies. Light rain tours continue with umbrellas provided. Heavy storms can be rescheduled. Severe weather conditions get full refunds or rescheduling at no cost. We know indoor alternatives and offer flexible rebooking with no penalties for weather-related changes."
      }
    }
  ]
}
</script>

<?php get_template_part('template-parts/footer'); ?>