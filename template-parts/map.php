<?php
/**
 * Template Name: Map
 * Description: Vehicle booking page — OpenStreetMap + OSRM routing, limited to the
 *              Nusa Penida service area. Pickup (A) and drop-off (B) are chosen via
 *              search, map tap, or GPS, then sent to WhatsApp together with Google
 *              Maps links for both points.
 *
 * Icons: Tabler Icons (MIT), inlined as SVG so the page needs no icon font or CDN.
 *
 * Production note: Nominatim (geocoding) and routing.openstreetmap.de (OSRM) are free
 * public services with rate limits. If traffic on this page grows, point the constants
 * below at a self-hosted instance or a paid provider.
 */

get_template_part('template-parts/header');

/** Destination WhatsApp number (international format, no leading "+"). */
$ap_wa_number = apply_filters('ap_booking_wa_number', '6281337567256');

/**
 * Service area — the Nusa Penida group (Penida, Lembongan, Ceningan).
 * The map cannot be panned outside it, search results are clipped to it, and
 * points that fall outside are rejected. [south, west, north, east]
 */
$ap_area_bounds = apply_filters('ap_booking_area_bounds', array(-8.88, 115.36, -8.58, 115.72));

if (!function_exists('ap_map_icon')) {
    /**
     * Renders a Tabler outline icon as inline SVG.
     * Only the icons this template actually uses are bundled here.
     */
    function ap_map_icon($name, $size = 18) {
        $paths = array(
            'car'              => array('M5 17a2 2 0 1 0 4 0a2 2 0 1 0 -4 0', 'M15 17a2 2 0 1 0 4 0a2 2 0 1 0 -4 0', 'M5 17h-2v-6l2 -5h9l4 5h1a2 2 0 0 1 2 2v4h-2m-4 0h-6m-6 -6h15m-6 0v-5'),
            'motorbike'        => array('M2 16a3 3 0 1 0 6 0a3 3 0 1 0 -6 0', 'M16 16a3 3 0 1 0 6 0a3 3 0 1 0 -6 0', 'M7.5 14h5l4 -4h-10.5m1.5 4l4 -4', 'M13 6h2l1.5 3l2 4'),
            'bus'              => array('M4 17a2 2 0 1 0 4 0a2 2 0 1 0 -4 0', 'M16 17a2 2 0 1 0 4 0a2 2 0 1 0 -4 0', 'M4 17h-2v-11a1 1 0 0 1 1 -1h14a5 7 0 0 1 5 7v5h-2m-4 0h-8', 'M16 5l1.5 7l4.5 0', 'M2 10l15 0', 'M7 5l0 5', 'M12 5l0 5'),
            'map-pin'          => array('M9 11a3 3 0 1 0 6 0a3 3 0 0 0 -6 0', 'M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0'),
            'map-pin-plus'     => array('M9 11a3 3 0 1 0 6 0a3 3 0 0 0 -6 0', 'M12.794 21.322a2 2 0 0 1 -2.207 -.422l-4.244 -4.243a8 8 0 1 1 13.59 -4.616', 'M16 19h6', 'M19 16v6'),
            'flag-3'           => array('M5 14h14l-4.5 -4.5l4.5 -4.5h-14v16'),
            'current-location' => array('M9 12a3 3 0 1 0 6 0a3 3 0 1 0 -6 0', 'M4 12a8 8 0 1 0 16 0a8 8 0 1 0 -16 0', 'M12 2l0 2', 'M12 20l0 2', 'M20 12l2 0', 'M2 12l2 0'),
            'arrows-up-down'   => array('M7 3l0 18', 'M10 6l-3 -3l-3 3', 'M20 18l-3 3l-3 -3', 'M17 21l0 -18'),
            'route'            => array('M3 19a2 2 0 1 0 4 0a2 2 0 0 0 -4 0', 'M19 7a2 2 0 1 0 0 -4a2 2 0 0 0 0 4', 'M11 19h5.5a3.5 3.5 0 0 0 0 -7h-8a3.5 3.5 0 0 1 0 -7h4.5'),
            'ruler-measure'    => array('M19.875 12c.621 0 1.125 .512 1.125 1.143v5.714c0 .631 -.504 1.143 -1.125 1.143h-15.875a1 1 0 0 1 -1 -1v-5.857c0 -.631 .504 -1.143 1.125 -1.143h15.75', 'M9 12v2', 'M6 12v3', 'M12 12v3', 'M18 12v3', 'M15 12v2', 'M3 3v4', 'M3 5h18', 'M21 3v4'),
            'clock-hour-4'     => array('M3 12a9 9 0 1 0 18 0a9 9 0 1 0 -18 0', 'M12 12l3 2', 'M12 7v5'),
            'calendar-event'   => array('M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2l0 -12', 'M16 3l0 4', 'M8 3l0 4', 'M4 11l16 0', 'M8 15h2v2h-2l0 -2'),
            'user'             => array('M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0', 'M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2'),
            'users'            => array('M5 7a4 4 0 1 0 8 0a4 4 0 1 0 -8 0', 'M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2', 'M16 3.13a4 4 0 0 1 0 7.75', 'M21 21v-2a4 4 0 0 0 -3 -3.85'),
            'note'             => array('M13 20l7 -7', 'M13 20v-6a1 1 0 0 1 1 -1h6v-7a2 2 0 0 0 -2 -2h-12a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7'),
            'brand-whatsapp'   => array('M3 21l1.65 -3.8a9 9 0 1 1 3.4 2.9l-5.05 .9', 'M9 10a.5 .5 0 0 0 1 0v-1a.5 .5 0 0 0 -1 0v1a5 5 0 0 0 5 5h1a.5 .5 0 0 0 0 -1h-1a.5 .5 0 0 0 0 1'),
            'refresh'          => array('M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4', 'M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4'),
            'chevron-up'       => array('M6 15l6 -6l6 6'),
            'chevron-down'     => array('M6 9l6 6l6 -6'),
            'alert-circle'     => array('M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0', 'M12 8v4', 'M12 16h.01'),
            'circle-check'     => array('M3 12a9 9 0 1 0 18 0a9 9 0 1 0 -18 0', 'M9 12l2 2l4 -4'),
            'info-circle'      => array('M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0', 'M12 9h.01', 'M11 12h1v4h1'),
            'map-2'            => array('M12 18.5l-3 -1.5l-6 3v-13l6 -3l6 3l6 -3v7.5', 'M9 4v13', 'M15 7v5.5', 'M21.121 20.121a3 3 0 1 0 -4.242 0c.418 .419 1.125 1.045 2.121 1.879c1.051 -.89 1.759 -1.516 2.121 -1.879', 'M19 18v.01'),
            'beach'            => array('M17.553 16.75a7.5 7.5 0 0 0 -10.606 0', 'M18 3.804a6 6 0 0 0 -8.196 2.196l10.392 6a6 6 0 0 0 -2.196 -8.196', 'M16.732 10c1.658 -2.87 2.225 -5.644 1.268 -6.196c-.957 -.552 -3.075 1.326 -4.732 4.196', 'M15 9l-3 5.196', 'M3 19.25a2.4 2.4 0 0 1 1 -.25a2.4 2.4 0 0 1 2 1a2.4 2.4 0 0 0 2 1a2.4 2.4 0 0 0 2 -1a2.4 2.4 0 0 1 2 -1a2.4 2.4 0 0 1 2 1a2.4 2.4 0 0 0 2 1a2.4 2.4 0 0 0 2 -1a2.4 2.4 0 0 1 2 -1a2.4 2.4 0 0 1 1 .25'),
            'mountain'         => array('M3 20h18l-6.921 -14.612a2.3 2.3 0 0 0 -4.158 0l-6.921 14.612', 'M7.5 11l2 2.5l2.5 -2.5l2 3l2.5 -2'),
            'anchor'           => array('M12 9v12m-8 -8a8 8 0 0 0 16 0m1 0h-2m-14 0h-2', 'M9 6a3 3 0 1 0 6 0a3 3 0 1 0 -6 0'),
            'building-arch'    => array('M3 21l18 0', 'M4 21v-15a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v15', 'M9 21v-8a3 3 0 0 1 6 0v8'),
            'pool'             => array('M2 20a2.4 2.4 0 0 0 2 1a2.4 2.4 0 0 0 2 -1a2.4 2.4 0 0 1 2 -1a2.4 2.4 0 0 1 2 1a2.4 2.4 0 0 0 2 1a2.4 2.4 0 0 0 2 -1a2.4 2.4 0 0 1 2 -1a2.4 2.4 0 0 1 2 1a2.4 2.4 0 0 0 2 1a2.4 2.4 0 0 0 2 -1', 'M2 16a2.4 2.4 0 0 0 2 1a2.4 2.4 0 0 0 2 -1a2.4 2.4 0 0 1 2 -1a2.4 2.4 0 0 1 2 1a2.4 2.4 0 0 0 2 1a2.4 2.4 0 0 0 2 -1a2.4 2.4 0 0 1 2 -1a2.4 2.4 0 0 1 2 1a2.4 2.4 0 0 0 2 1a2.4 2.4 0 0 0 2 -1', 'M15 12v-7.5a1.5 1.5 0 0 1 3 0', 'M9 12v-7.5a1.5 1.5 0 0 0 -3 0', 'M15 5l-6 0', 'M9 10l6 0'),
            'x'                => array('M18 6l-12 12', 'M6 6l12 12'),
        );

        if (!isset($paths[$name])) {
            return '';
        }

        $svg = '<svg class="ap-i" width="' . (int) $size . '" height="' . (int) $size . '" viewBox="0 0 24 24"'
             . ' fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"'
             . ' stroke-linejoin="round" aria-hidden="true" focusable="false">';
        foreach ($paths[$name] as $d) {
            $svg .= '<path d="' . esc_attr($d) . '" />';
        }
        return $svg . '</svg>';
    }
}

/**
 * Vehicle list. Only the car is active for now — add new entries here
 * (e.g. scooter / minibus) without touching the CSS or JS.
 */
$ap_vehicles = apply_filters('ap_booking_vehicles', array(
    array(
        'id'        => 'car',
        'icon'      => 'car',
        'label'     => 'Car',
        'desc'      => 'Avanza / Innova · up to 6 people',
        'seats'     => 6,
        'available' => true,
    ),
    // array( 'id' => 'scooter', 'icon' => 'motorbike', 'label' => 'Scooter', 'desc' => '150cc · up to 2 people',  'seats' => 2,  'available' => false ),
    // array( 'id' => 'minibus', 'icon' => 'bus',       'label' => 'Minibus', 'desc' => 'Hiace · up to 14 people', 'seats' => 14, 'available' => false ),
));

$ap_available_vehicles = array_values(array_filter($ap_vehicles, function ($v) {
    return !empty($v['available']);
}));
$ap_default_vehicle = !empty($ap_available_vehicles) ? $ap_available_vehicles[0] : $ap_vehicles[0];
$ap_max_seats       = (int) $ap_default_vehicle['seats'];
$ap_today           = current_time('Y-m-d');

/**
 * Popular spots shown as tappable markers on the map.
 *
 * Coordinates for Kelingking, Diamond Beach, Broken Beach (Pasih Uwug),
 * Teletubbies Hill and Sampalan Harbour were resolved from OpenStreetMap.
 * The four marked 'approx' => true are best-effort placements — Banjar Nyuh,
 * the two Sundi spots and Pramana Natura (Banjar Anyar, Dusun Sebunibus, Desa
 * Sakti) have no OSM entry. Correct their lat/lng here and nothing else needs
 * to change.
 *
 * `image` points at the URLs supplied with the data. They are hotlinked from a
 * third-party host, so moving them into the WordPress media library is the
 * safer long-term option — swap the URL here when you do.
 */
$ap_places = apply_filters('ap_booking_places', array(
    array(
        'id' => 1, 'name' => 'Kelingking Beach', 'category' => 'Beach', 'icon' => 'beach',
        'location' => 'Southwest Nusa Penida', 'lat' => -8.751263, 'lng' => 115.473132, 'approx' => false,
        'description' => 'Pantai ikonik berbentuk T-Rex dengan tebing karst yang menjulang tinggi dan air laut berwarna turquoise.',
        'image'  => 'https://kimi-web-img.kimi.ai/img/www.indonesia.travel/b7e0ec42efa7aa22dbe5281998bd0cc1dc49897c.aspx',
        'source' => 'https://www.indonesia.travel/cn/zh-cn/destination/bali-nusa-tenggara/bali/kelingking-beach-a-marvelous-wonder-in-the-southwest-of-nusa-penida',
    ),
    array(
        'id' => 2, 'name' => 'Diamond Beach', 'category' => 'Beach', 'icon' => 'beach',
        'location' => 'East Nusa Penida', 'lat' => -8.776136, 'lng' => 115.618636, 'approx' => false,
        'description' => 'Pantai eksotis dengan formasi batu karang berbentuk berlian dan pasir putih yang dikelilingi tebing kapur.',
        'image'  => 'https://kimi-web-img.kimi.ai/img/finnsbeachclub.com/0d18e379c7f15f0593252bdaccf7ec8323940726.jpg',
        'source' => 'https://finnsbeachclub.com/guides/diamond-beach-bali-the-ultimate-guide-to-the-most-incredible-beach-on-nusa-penida-island/',
    ),
    array(
        'id' => 3, 'name' => 'Broken Beach', 'category' => 'Natural Landmark', 'icon' => 'building-arch',
        'location' => 'West Nusa Penida', 'lat' => -8.733129, 'lng' => 115.450800, 'approx' => false,
        'description' => 'Kolam alami yang terbentuk dari erosi tebing karst membentuk lengkungan besar (natural arch) dengan lautan biru di tengahnya.',
        'image'  => 'https://kimi-web-img.kimi.ai/img/neptunescubadiving.com/36486eab18b90752f302405f9986c918b9a68edc.webp',
        'source' => 'https://neptunescubadiving.com/zh/nusa-penida/broken-beach/',
    ),
    array(
        'id' => 4, 'name' => 'Bukit Teletubbies', 'category' => 'Hill', 'icon' => 'mountain',
        'location' => 'Southeast Nusa Penida', 'lat' => -8.787972, 'lng' => 115.579370, 'approx' => false,
        'description' => 'Bukit hijau berundulasi yang mirip dengan bukit di serial Teletubbies, cocok untuk foto landscape dan sunset.',
        'image'  => 'https://kimi-web-img.kimi.ai/img/finnsbeachclub.com/005c801335bf8801fe4de4d1c3dc6768fa582fb6.jpg',
        'source' => 'https://finnsbeachclub.com/guides/teletubbies-hills-nusa-penida-bali/',
    ),
    array(
        'id' => 5, 'name' => 'Banjar Nyuh Harbour', 'category' => 'Harbour', 'icon' => 'anchor',
        'location' => 'North Nusa Penida', 'lat' => -8.680000, 'lng' => 115.498000, 'approx' => true,
        'description' => 'Pelabuhan utama di sisi utara Nusa Penida untuk kedatangan fast boat dari Sanur dan Padang Bai.',
        'image'  => 'https://kimi-web-img.kimi.ai/img/www.asiaferries.com/273f08c3bc9c8b9b2feab764704a16dca1265f74.jpg',
        'source' => 'https://www.asiaferries.com/banjar-nyuh',
    ),
    array(
        'id' => 6, 'name' => 'Sampalan Harbour', 'category' => 'Harbour', 'icon' => 'anchor',
        'location' => 'Northeast Nusa Penida', 'lat' => -8.672701, 'lng' => 115.554272, 'approx' => false,
        'description' => 'Pelabuhan alternatif di sisi timur laut Nusa Penida dengan akses yang lebih dekat ke spot wisata timur.',
        'image'  => 'https://kimi-web-img.kimi.ai/img/nusapenidafastboatticket.com/49913b459cacedfe637653ea229a46f962c48245.jpg',
        'source' => 'https://nusapenidafastboatticket.com/20-minutes-reaching-nusa-penida-via-sampalan-harbour/',
    ),
    array(
        'id' => 7, 'name' => 'Sundi Ocean', 'category' => 'Ocean View / Resort', 'icon' => 'pool',
        'location' => 'East Coast Nusa Penida', 'lat' => -8.698000, 'lng' => 115.479000, 'approx' => true,
        'description' => 'Area pesisir timur Nusa Penida dengan view laut lepas, dikenal dengan resort-resort tepi tebing yang menawarkan panorama samudra Hindia.',
        'image'  => 'https://kimi-web-img.kimi.ai/img/cf.bstatic.com/5d4492b447268b78ad5d71dce8ce641141f6bc3b.jpg',
        'source' => 'https://www.booking.com/hotel/id/cindy-view-cottage.html',
    ),
    array(
        'id' => 8, 'name' => 'Sundi Hill', 'category' => 'Hill / Viewpoint', 'icon' => 'mountain',
        'location' => 'East Nusa Penida', 'lat' => -8.706000, 'lng' => 115.482000, 'approx' => true,
        'description' => 'Bukit di area Sundi dengan pemandangan laut dan sunset yang memukau, banyak dikelilingi cottage dan penginapan.',
        'image'  => 'https://kimi-web-img.kimi.ai/img/cf.bstatic.com/f8442e0d448acec90b1b4542338167a525f1e485.jpg',
        'source' => 'https://www.booking.com/hotel/id/sundi-hill-cottage.html',
    ),
    array(
        'id' => 9, 'name' => 'Pramana Natura Nusa Penida', 'category' => 'Resort / Viewpoint', 'icon' => 'pool',
        'location' => 'North Nusa Penida', 'lat' => -8.702897, 'lng' => 115.490042, 'approx' => true,
        'description' => 'Resort mewah di tepi tebing Nusa Penida dengan infinity pool menghadap laut dan pemandangan spektakuler.',
        'image'  => 'https://kimi-web-img.kimi.ai/img/cf.bstatic.com/2f22b8983acc283befde8118ba00a91507646329.jpg',
        'source' => 'https://www.booking.com/hotel/id/pramana-natura-nusa-penida.html',
    ),
));

/** Marker glyphs, keyed by place id, so the JS can build the map pins. */
$ap_place_icons = array();
foreach ($ap_places as $ap_place) {
    $ap_place_icons[$ap_place['id']] = ap_map_icon($ap_place['icon'], 16);
}

/** Icons the JS needs at runtime (status messages, sheet toggle). */
$ap_js_icons = array(
    'error'       => ap_map_icon('alert-circle', 16),
    'ok'          => ap_map_icon('circle-check', 16),
    'info'        => ap_map_icon('info-circle', 16),
    'chevronUp'   => ap_map_icon('chevron-up', 18),
    'chevronDown' => ap_map_icon('chevron-down', 18),
    'pinA'        => ap_map_icon('map-pin', 16),
    'pinB'        => ap_map_icon('flag-3', 16),
    'places'      => $ap_place_icons,
);
?>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<style>
/* --------------------------------------------------------------------------
   This page is self-contained (it does not depend on the theme's Tailwind
   build), so it renders correctly even when `npm run build` hasn't been run.
   -------------------------------------------------------------------------- */
.ap-book * { box-sizing: border-box; }

/* Design tokens mirror the theme's Tailwind usage: teal-600/700 as the primary
   accent, the gray ramp for text and borders, green-600 for WhatsApp (same as
   the footer's floating button), rounded-2xl cards and pill-shaped buttons. */
.ap-book {
  --ap-teal-50:  #f0fdfa;
  --ap-teal-100: #ccfbf1;
  --ap-teal-600: #0d9488;
  --ap-teal-700: #0f766e;
  --ap-teal-800: #115e59;
  --ap-green-600:#16a34a;
  --ap-green-700:#15803d;
  --ap-gray-50:  #f9fafb;
  --ap-gray-100: #f3f4f6;
  --ap-gray-200: #e5e7eb;
  --ap-gray-300: #d1d5db;
  --ap-gray-400: #9ca3af;
  --ap-gray-500: #6b7280;
  --ap-gray-600: #4b5563;
  --ap-gray-800: #1f2937;
  --ap-gray-900: #111827;

  position: relative;
  height: calc(100vh - 64px);
  height: calc(100dvh - 64px);
  width: 100%;
  overflow: hidden;
  /* Same stack Tailwind's font-sans resolves to, so the page matches the theme. */
  font-family: ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto,
               "Helvetica Neue", Arial, "Noto Sans", sans-serif;
  color: var(--ap-gray-800);
  -webkit-font-smoothing: antialiased;
}

.ap-book__map { position: absolute; inset: 0; z-index: 0; }
.ap-book__map .leaflet-control-attribution { font-size: 10px; }

/* Zoom control lives on the right (the panel owns the left/bottom) and is
   restyled to match the theme's rounded, soft-shadow surfaces. */
.ap-book .leaflet-top.leaflet-right { margin: 6px 6px 0 0; }
.ap-book .leaflet-control-zoom { border: 0; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(17,24,39,.18); }
.ap-book .leaflet-control-zoom a {
  width: 34px; height: 34px; line-height: 34px;
  color: var(--ap-gray-600); border-bottom-color: var(--ap-gray-200);
}
.ap-book .leaflet-control-zoom a:hover { background: var(--ap-teal-50); color: var(--ap-teal-700); }

/* Tabler icons render at the size given in the markup and inherit text colour. */
.ap-i { flex: none; display: inline-block; vertical-align: -.15em; }

/* ---- A / B pins ---- */
.ap-pin { display: flex; align-items: center; justify-content: center; filter: drop-shadow(0 2px 3px rgba(0,0,0,.35)); }
.ap-pin span { position: absolute; top: 6px; color: #fff; font-size: 12px; font-weight: 700; line-height: 1; }

/* ---- Panel ---- */
.ap-panel {
  position: absolute;
  z-index: 1000;
  background: rgba(255,255,255,.97);
  backdrop-filter: blur(8px);
  display: flex;
  flex-direction: column;
  box-shadow: 0 -10px 30px rgba(17,24,39,.15);
}

.ap-panel__grip { display: none; }

.ap-panel__head {
  display: flex; align-items: center; justify-content: space-between; gap: 8px;
  padding: 14px 18px 8px;
  flex: none;
}
/* Matches the theme's headings: font-bold + tracking-tight, gray-900. */
.ap-panel__title {
  font-size: 18px; font-weight: 700; letter-spacing: -.02em;
  color: var(--ap-gray-900); margin: 0;
  display: flex; align-items: center; gap: 8px;
}
.ap-panel__title .ap-i { color: var(--ap-teal-600); }
.ap-panel__sub { font-size: 11px; color: var(--ap-gray-400); display: inline-flex; align-items: center; gap: 4px; }

.ap-panel__body {
  padding: 0 18px 12px;
  overflow-y: auto;
  overscroll-behavior: contain;
  flex: 1 1 auto;
}

.ap-panel__foot {
  flex: none;
  padding: 12px 18px calc(12px + env(safe-area-inset-bottom));
  border-top: 1px solid var(--ap-gray-200);
  background: #fff;
  display: grid;
  grid-template-columns: 1fr auto;
  gap: 8px;
  align-items: center;
}

/* ---- Base fields ---- */
.ap-field { margin-bottom: 10px; }
.ap-label {
  display: flex; align-items: center; gap: 5px;
  font-size: 11px; font-weight: 600; letter-spacing: .02em; text-transform: uppercase;
  color: var(--ap-gray-500); margin-bottom: 5px;
}
.ap-label .ap-i { color: var(--ap-teal-600); }
.ap-input, .ap-select, .ap-textarea {
  width: 100%; border: 1px solid var(--ap-gray-300); border-radius: 12px;
  padding: 11px 14px; font-size: 14px; background: #fff; color: var(--ap-gray-900);
  -webkit-appearance: none; appearance: none;
  transition: border-color .2s ease, box-shadow .2s ease;
}
.ap-input::placeholder, .ap-textarea::placeholder { color: var(--ap-gray-400); }
/* Native select arrow is removed by appearance:none — put the Tabler
   chevron-down back in its place. */
.ap-select {
  padding-right: 36px;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='18' height='18' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M6 9l6 6l6 -6'/%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: right 12px center;
}
.ap-input:focus, .ap-select:focus, .ap-textarea:focus {
  outline: none; border-color: var(--ap-teal-600); box-shadow: 0 0 0 3px rgba(13,148,136,.18);
}
.ap-textarea { min-height: 64px; resize: vertical; }
.ap-input--pin { padding-left: 38px; padding-right: 112px; }

.ap-point { position: relative; margin-bottom: 8px; }
.ap-point__icon { position: absolute; left: 13px; top: 50%; transform: translateY(-50%); display: flex; pointer-events: none; }
.ap-point__icon--a { color: var(--ap-teal-600); }
.ap-point__icon--b { color: var(--ap-gray-900); }
.ap-point__pick {
  position: absolute; right: 6px; top: 50%; transform: translateY(-50%);
  display: inline-flex; align-items: center; gap: 4px;
  border: 0; background: var(--ap-gray-100); color: var(--ap-gray-600); border-radius: 9999px;
  font-size: 11px; font-weight: 600; padding: 7px 11px; cursor: pointer;
  transition: all .2s ease;
}
.ap-point__pick:hover { background: var(--ap-gray-200); }
.ap-point__pick.is-active { background: var(--ap-teal-600); color: #fff; }

.ap-results {
  position: absolute; z-index: 1100; top: calc(100% + 4px); left: 0; right: 0;
  background: #fff; border: 1px solid var(--ap-gray-200); border-radius: 16px;
  box-shadow: 0 12px 28px rgba(17,24,39,.14);
  max-height: 200px; overflow-y: auto; list-style: none; margin: 0; padding: 0;
  font-size: 13px;
}
.ap-results li { padding: 11px 14px; border-bottom: 1px solid var(--ap-gray-100); cursor: pointer; color: var(--ap-gray-600); }
.ap-results li:last-child { border-bottom: 0; }
.ap-results li:hover { background: var(--ap-teal-50); color: var(--ap-teal-800); }
.ap-results li.is-empty { color: var(--ap-gray-400); cursor: default; }
.ap-results li.is-empty:hover { background: transparent; color: var(--ap-gray-400); }

/* ---- Buttons: pill-shaped with the theme's hover-lift, like the site's CTAs ---- */
.ap-btn {
  display: inline-flex; align-items: center; justify-content: center; gap: 7px;
  border-radius: 9999px; font-size: 14px; font-weight: 600; padding: 12px 18px;
  border: 1px solid transparent; cursor: pointer; width: 100%;
  transition: all .3s ease;
}
.ap-btn--ghost { background: #fff; border-color: var(--ap-gray-300); color: var(--ap-gray-600); }
.ap-btn--ghost:hover { background: var(--ap-gray-50); border-color: var(--ap-gray-400); color: var(--ap-gray-800); }
.ap-btn--primary { background: var(--ap-teal-600); color: #fff; box-shadow: 0 10px 15px -3px rgba(13,148,136,.25); }
.ap-btn--primary:hover:not(:disabled) { background: var(--ap-teal-700); transform: scale(1.02); }
.ap-btn--primary:active:not(:disabled) { transform: scale(.97); }
.ap-btn--wa { background: var(--ap-green-600); color: #fff; font-size: 15px; font-weight: 700; box-shadow: 0 10px 15px -3px rgba(22,163,74,.3); }
.ap-btn--wa:hover:not(:disabled) { background: var(--ap-green-700); transform: scale(1.02); }
.ap-btn--wa:active:not(:disabled) { transform: scale(.97); }
.ap-btn:disabled { opacity: .45; cursor: not-allowed; box-shadow: none; }
.ap-btn--icon { width: auto; padding: 12px; color: var(--ap-gray-500); }

.ap-quick { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-bottom: 14px; }
.ap-quick .ap-btn { font-size: 13px; padding: 10px 12px; }

/* ---- Vehicle picker ---- */
.ap-vehicles { display: grid; gap: 8px; margin-bottom: 14px; }
.ap-vehicle {
  display: flex; align-items: center; gap: 12px; width: 100%; text-align: left;
  border: 1px solid var(--ap-gray-200); border-radius: 16px; background: #fff;
  padding: 12px 14px; cursor: pointer;
  transition: all .3s ease;
}
.ap-vehicle:hover:not(:disabled):not(.is-active) { border-color: var(--ap-gray-300); box-shadow: 0 4px 10px rgba(17,24,39,.06); }
.ap-vehicle.is-active { border-color: var(--ap-teal-600); background: var(--ap-teal-50); box-shadow: 0 0 0 1px var(--ap-teal-600) inset; }
.ap-vehicle.is-active .ap-vehicle__icon { color: var(--ap-teal-600); }
.ap-vehicle.is-active .ap-vehicle__desc { color: var(--ap-teal-800); }
.ap-vehicle:disabled { opacity: .5; cursor: not-allowed; }
.ap-vehicle__icon { display: flex; color: var(--ap-gray-500); }
.ap-vehicle__label { font-size: 15px; font-weight: 700; color: var(--ap-gray-900); }
.ap-vehicle__desc { font-size: 12px; color: var(--ap-gray-600); }
.ap-vehicle__badge { margin-left: auto; font-size: 10px; font-weight: 700; text-transform: uppercase; color: var(--ap-gray-400); }

/* ---- Route summary ---- */
.ap-summary {
  border: 1px solid var(--ap-teal-100); background: var(--ap-teal-50); border-radius: 16px;
  padding: 12px 14px; margin-bottom: 14px;
  display: grid; grid-template-columns: 1fr 1fr; gap: 8px;
}
.ap-summary__label { font-size: 11px; color: var(--ap-teal-700); font-weight: 600; display: flex; align-items: center; gap: 4px; }
.ap-summary__value { font-size: 17px; font-weight: 700; color: var(--ap-gray-900); margin-top: 2px; letter-spacing: -.01em; }

.ap-status { border-radius: 14px; padding: 10px 14px; font-size: 13px; margin-bottom: 12px; display: flex; align-items: flex-start; gap: 8px; }
.ap-status .ap-i { margin-top: 1px; }
.ap-status--error { background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; }
.ap-status--info  { background: var(--ap-gray-50); border: 1px solid var(--ap-gray-200); color: var(--ap-gray-600); }
.ap-status--ok    { background: var(--ap-teal-50); border: 1px solid var(--ap-teal-100); color: var(--ap-teal-700); }

.ap-hint { font-size: 12px; color: var(--ap-gray-500); line-height: 1.6; margin: 0 0 12px; }
.ap-hint b { color: var(--ap-gray-800); font-weight: 600; }
.ap-section-title {
  display: flex; align-items: center; gap: 5px;
  font-size: 11px; font-weight: 700; letter-spacing: .04em; text-transform: uppercase;
  color: var(--ap-gray-400); margin: 16px 0 8px;
}
.ap-section-title .ap-i { color: var(--ap-teal-600); }
.ap-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
.ap-hidden { display: none !important; }

/* ==========================================================================
   Popular-spot markers + their detail view
   (centred modal on desktop, bottom sheet on mobile)
   ========================================================================== */
.ap-place-pin {
  display: flex; align-items: center; justify-content: center;
  width: 34px; height: 34px; border-radius: 9999px;
  background: #fff; color: var(--ap-teal-600);
  border: 2px solid var(--ap-teal-600);
  box-shadow: 0 3px 8px rgba(17,24,39,.28);
  transition: transform .2s ease;
}
.ap-place-pin:hover { transform: scale(1.12); }
.ap-place-pin.is-chosen { background: var(--ap-teal-600); color: #fff; }

.ap-modal {
  position: fixed; inset: 0; z-index: 2000;
  display: flex; opacity: 0; pointer-events: none;
  transition: opacity .25s ease;
}
.ap-modal.is-open { opacity: 1; pointer-events: auto; }
.ap-modal__backdrop { position: absolute; inset: 0; background: rgba(17,24,39,.55); }

.ap-modal__card {
  position: relative;
  background: #fff;
  width: 100%;
  display: flex; flex-direction: column;
  overflow: hidden;
  box-shadow: 0 25px 50px -12px rgba(17,24,39,.4);
}

.ap-modal__media { position: relative; background: var(--ap-gray-100); flex: none; }
.ap-modal__media img { display: block; width: 100%; height: 100%; object-fit: cover; }
.ap-modal__close {
  position: absolute; top: 12px; right: 12px; z-index: 2;
  display: flex; align-items: center; justify-content: center;
  width: 36px; height: 36px; border: 0; border-radius: 9999px; cursor: pointer;
  background: rgba(255,255,255,.92); color: var(--ap-gray-800);
  box-shadow: 0 2px 8px rgba(17,24,39,.2);
  transition: all .2s ease;
}
.ap-modal__close:hover { background: #fff; transform: scale(1.06); }

.ap-modal__badge {
  position: absolute; left: 14px; bottom: 14px; z-index: 2;
  display: inline-flex; align-items: center; gap: 5px;
  background: var(--ap-teal-600); color: #fff;
  font-size: 11px; font-weight: 700; letter-spacing: .02em;
  padding: 6px 12px; border-radius: 9999px;
  box-shadow: 0 4px 10px rgba(17,24,39,.25);
}

.ap-modal__body { padding: 16px 18px 4px; overflow-y: auto; flex: 1 1 auto; }
.ap-modal__title {
  font-size: 20px; font-weight: 700; letter-spacing: -.02em;
  color: var(--ap-gray-900); margin: 0 0 4px;
}
.ap-modal__meta {
  display: flex; align-items: center; gap: 5px;
  font-size: 12px; color: var(--ap-gray-500); margin-bottom: 10px;
}
.ap-modal__meta .ap-i { color: var(--ap-teal-600); }
.ap-modal__desc { font-size: 14px; line-height: 1.65; color: var(--ap-gray-600); margin: 0 0 12px; }
.ap-modal__source {
  display: inline-flex; align-items: center; gap: 4px;
  font-size: 11px; color: var(--ap-gray-400); text-decoration: none;
}
.ap-modal__source:hover { color: var(--ap-teal-600); text-decoration: underline; }

.ap-modal__foot {
  flex: none; padding: 14px 18px calc(14px + env(safe-area-inset-bottom));
  border-top: 1px solid var(--ap-gray-200);
  display: grid; grid-template-columns: 1fr 1fr; gap: 8px;
}
.ap-modal__foot .ap-btn { font-size: 13px; padding: 11px 12px; }

@media (max-width: 767px) {
  /* Bottom sheet — thumb-reachable actions, slides up from the bottom edge. */
  .ap-modal { align-items: flex-end; }
  .ap-modal__card {
    border-radius: 24px 24px 0 0;
    max-height: 92dvh;
    transform: translateY(100%);
    transition: transform .3s cubic-bezier(.32,.72,0,1);
  }
  .ap-modal.is-open .ap-modal__card { transform: translateY(0); }
  .ap-modal__media { height: 190px; }
  /* Grab handle, matching the booking sheet. */
  .ap-modal__card::before {
    content: ""; position: absolute; top: 9px; left: 50%; transform: translateX(-50%);
    z-index: 3; width: 44px; height: 4px; border-radius: 9999px; background: rgba(255,255,255,.85);
  }
}

@media (min-width: 768px) {
  .ap-modal { align-items: center; justify-content: center; padding: 24px; }
  .ap-modal__card {
    max-width: 430px; max-height: 86vh;
    border-radius: 16px;
    transform: scale(.96) translateY(8px);
    transition: transform .25s ease;
  }
  .ap-modal.is-open .ap-modal__card { transform: scale(1) translateY(0); }
  .ap-modal__media { height: 220px; }
}

/* ==========================================================================
   MOBILE: the panel becomes a bottom sheet so every primary control sits
   within thumb reach at the bottom of the screen.
   ========================================================================== */
@media (max-width: 767px) {
  .ap-panel {
    left: 0; right: 0; bottom: 0;
    border-radius: 24px 24px 0 0;   /* rounded-3xl, like the theme's large cards */
    border-top: 1px solid var(--ap-gray-200);
    max-height: 88dvh;
    transition: transform .28s cubic-bezier(.32,.72,0,1);
  }

  .ap-panel__grip { display: block; padding: 9px 0 2px; flex: none; cursor: grab; touch-action: none; }
  .ap-panel__grip::before {
    content: ""; display: block; width: 44px; height: 4px; margin: 0 auto;
    border-radius: 9999px; background: var(--ap-gray-300);
  }

  /* Collapsed: the body folds away, the header and WhatsApp button stay visible. */
  .ap-panel.is-collapsed .ap-panel__body { max-height: 0; padding-top: 0; padding-bottom: 0; opacity: 0; overflow: hidden; }
  .ap-panel__body { max-height: 62dvh; opacity: 1; transition: max-height .28s ease, opacity .2s ease, padding .28s ease; }

  .ap-panel.is-collapsed .ap-panel__head { padding-bottom: 10px; }

  /* Peek: the A → B summary shown while the sheet is collapsed. */
  .ap-peek { display: none; font-size: 12px; color: var(--ap-gray-500); padding: 0 18px 10px; }
  .ap-panel.is-collapsed .ap-peek { display: block; }
  .ap-peek b { color: var(--ap-gray-900); font-weight: 600; }

  /* Keep the OSM attribution clear of the sheet. */
  .ap-book .leaflet-bottom.leaflet-right { margin-bottom: 128px; }
}

/* ==========================================================================
   DESKTOP: the panel becomes a floating card on the left.
   ========================================================================== */
@media (min-width: 768px) {
  .ap-panel {
    left: 16px; top: 16px; bottom: 16px;
    width: 384px;
    border-radius: 16px;            /* rounded-2xl, the theme's card radius */
    border: 1px solid var(--ap-gray-200);
    box-shadow: 0 20px 40px rgba(17,24,39,.15);
  }
  .ap-panel__foot { border-radius: 0 0 16px 16px; }
  .ap-peek { display: none; }
}
</style>

<div class="ap-book" id="ap-book">

  <div id="ap-map" class="ap-book__map"></div>

  <div class="ap-panel" id="ap-panel">

    <!-- Drag handle (mobile only) -->
    <div class="ap-panel__grip" id="ap-grip" role="button" tabindex="0" aria-label="Open or close the booking panel"></div>

    <div class="ap-panel__head">
      <div>
        <h1 class="ap-panel__title"><?php echo ap_map_icon('car', 20); ?> Book a Vehicle</h1>
        <span class="ap-panel__sub"><?php echo ap_map_icon('map-2', 12); ?> Nusa Penida area only</span>
      </div>
      <span class="ap-panel__sub" id="ap-step-badge">Step 1 of 3</span>
    </div>

    <!-- Short summary shown while the sheet is collapsed -->
    <div class="ap-peek" id="ap-peek">Choose your pickup &amp; drop-off to get started.</div>

    <div class="ap-panel__body" id="ap-panel-body">

      <!-- 1. Pickup & drop-off -->
      <div class="ap-point">
        <span class="ap-point__icon ap-point__icon--a"><?php echo ap_map_icon('map-pin', 16); ?></span>
        <input type="text" id="ap-search-a" class="ap-input ap-input--pin" autocomplete="off"
               placeholder="Pickup location (A)…">
        <button type="button" class="ap-point__pick" data-pick="A">
          <?php echo ap_map_icon('map-pin-plus', 13); ?> Pick on map
        </button>
        <ul class="ap-results ap-hidden" id="ap-results-a"></ul>
      </div>

      <div class="ap-point">
        <span class="ap-point__icon ap-point__icon--b"><?php echo ap_map_icon('flag-3', 16); ?></span>
        <input type="text" id="ap-search-b" class="ap-input ap-input--pin" autocomplete="off"
               placeholder="Drop-off location (B)…">
        <button type="button" class="ap-point__pick" data-pick="B">
          <?php echo ap_map_icon('map-pin-plus', 13); ?> Pick on map
        </button>
        <ul class="ap-results ap-hidden" id="ap-results-b"></ul>
      </div>

      <div class="ap-quick">
        <button type="button" class="ap-btn ap-btn--ghost" id="ap-locate">
          <?php echo ap_map_icon('current-location', 16); ?><span class="ap-btn__label">Use My Location</span>
        </button>
        <button type="button" class="ap-btn ap-btn--ghost" id="ap-swap">
          <?php echo ap_map_icon('arrows-up-down', 16); ?><span class="ap-btn__label">Swap A/B</span>
        </button>
      </div>

      <p class="ap-hint" id="ap-hint">
        Search a place, tap <b>Pick on map</b>, or tap the map to drop a point.
      </p>

      <div class="ap-status ap-status--error ap-hidden" id="ap-status"></div>

      <!-- 2. Vehicle -->
      <div class="ap-section-title"><?php echo ap_map_icon('car', 14); ?> Choose Vehicle</div>
      <div class="ap-vehicles" id="ap-vehicles">
        <?php foreach ($ap_vehicles as $vehicle) :
            $is_default = ($vehicle['id'] === $ap_default_vehicle['id']); ?>
          <button type="button"
                  class="ap-vehicle<?php echo $is_default ? ' is-active' : ''; ?>"
                  data-vehicle="<?php echo esc_attr($vehicle['id']); ?>"
                  data-label="<?php echo esc_attr($vehicle['label']); ?>"
                  data-desc="<?php echo esc_attr($vehicle['desc']); ?>"
                  data-seats="<?php echo esc_attr($vehicle['seats']); ?>"
                  <?php disabled(empty($vehicle['available'])); ?>>
            <span class="ap-vehicle__icon"><?php echo ap_map_icon($vehicle['icon'], 24); ?></span>
            <span>
              <span class="ap-vehicle__label"><?php echo esc_html($vehicle['label']); ?></span><br>
              <span class="ap-vehicle__desc"><?php echo esc_html($vehicle['desc']); ?></span>
            </span>
            <?php if (empty($vehicle['available'])) : ?>
              <span class="ap-vehicle__badge">Soon</span>
            <?php endif; ?>
          </button>
        <?php endforeach; ?>
      </div>

      <!-- 3. Route summary -->
      <div class="ap-summary ap-hidden" id="ap-summary">
        <div>
          <div class="ap-summary__label"><?php echo ap_map_icon('ruler-measure', 13); ?> Distance</div>
          <div class="ap-summary__value" id="ap-distance">—</div>
        </div>
        <div>
          <div class="ap-summary__label"><?php echo ap_map_icon('clock-hour-4', 13); ?> Estimated time</div>
          <div class="ap-summary__value" id="ap-duration">—</div>
        </div>
      </div>

      <button type="button" class="ap-btn ap-btn--primary" id="ap-calculate" style="margin-bottom:12px" disabled>
        <?php echo ap_map_icon('route', 17); ?><span class="ap-btn__label">Calculate Route</span>
      </button>

      <!-- 4. Booking details -->
      <div class="ap-section-title"><?php echo ap_map_icon('note', 14); ?> Booking Details</div>

      <div class="ap-field">
        <label class="ap-label" for="ap-name"><?php echo ap_map_icon('user', 13); ?> Full Name</label>
        <input type="text" id="ap-name" class="ap-input" placeholder="Your full name" autocomplete="name">
      </div>

      <div class="ap-grid-2">
        <div class="ap-field">
          <label class="ap-label" for="ap-date"><?php echo ap_map_icon('calendar-event', 13); ?> Date</label>
          <input type="date" id="ap-date" class="ap-input"
                 min="<?php echo esc_attr($ap_today); ?>" value="<?php echo esc_attr($ap_today); ?>">
        </div>
        <div class="ap-field">
          <label class="ap-label" for="ap-time"><?php echo ap_map_icon('clock-hour-4', 13); ?> Pickup Time</label>
          <input type="time" id="ap-time" class="ap-input" value="09:00">
        </div>
      </div>

      <div class="ap-field">
        <label class="ap-label" for="ap-pax"><?php echo ap_map_icon('users', 13); ?> Passengers</label>
        <select id="ap-pax" class="ap-select">
          <?php for ($i = 1; $i <= $ap_max_seats; $i++) : ?>
            <option value="<?php echo esc_attr($i); ?>"<?php selected($i, 2); ?>>
              <?php echo esc_html($i); ?> <?php echo $i === 1 ? 'person' : 'people'; ?>
            </option>
          <?php endfor; ?>
        </select>
      </div>

      <div class="ap-field">
        <label class="ap-label" for="ap-note"><?php echo ap_map_icon('note', 13); ?> Notes (optional)</label>
        <textarea id="ap-note" class="ap-textarea" placeholder="e.g. 2 large suitcases, child seat needed…"></textarea>
      </div>

      <button type="button" class="ap-btn ap-btn--ghost" id="ap-reset">
        <?php echo ap_map_icon('refresh', 16); ?><span class="ap-btn__label">Reset All</span>
      </button>
    </div>

    <!-- Primary action: always pinned to the bottom of the screen (mobile & desktop) -->
    <div class="ap-panel__foot">
      <button type="button" class="ap-btn ap-btn--wa" id="ap-book-wa" disabled>
        <?php echo ap_map_icon('brand-whatsapp', 18); ?><span class="ap-btn__label">Book via WhatsApp</span>
      </button>
      <button type="button" class="ap-btn ap-btn--ghost ap-btn--icon" id="ap-toggle" aria-expanded="true"
              aria-controls="ap-panel-body" title="Show or hide details"><?php echo ap_map_icon('chevron-down', 18); ?></button>
    </div>

  </div>

  <!-- Popular-spot detail: centred modal on desktop, bottom sheet on mobile -->
  <div class="ap-modal" id="ap-modal" role="dialog" aria-modal="true" aria-labelledby="ap-modal-title" aria-hidden="true">
    <div class="ap-modal__backdrop" id="ap-modal-backdrop"></div>

    <div class="ap-modal__card">
      <div class="ap-modal__media" id="ap-modal-media">
        <!-- src is set when a spot is opened; an empty src would re-request this page -->
        <img id="ap-modal-image" alt="" loading="lazy" decoding="async">
        <span class="ap-modal__badge" id="ap-modal-badge"></span>
        <button type="button" class="ap-modal__close" id="ap-modal-close" aria-label="Close">
          <?php echo ap_map_icon('x', 18); ?>
        </button>
      </div>

      <div class="ap-modal__body">
        <h2 class="ap-modal__title" id="ap-modal-title"></h2>
        <p class="ap-modal__meta">
          <?php echo ap_map_icon('map-pin', 14); ?><span id="ap-modal-location"></span>
        </p>
        <p class="ap-modal__desc" id="ap-modal-desc"></p>
        <a class="ap-modal__source" id="ap-modal-source" href="#" target="_blank" rel="noopener nofollow">
          <?php echo ap_map_icon('info-circle', 12); ?> Photo &amp; info source
        </a>
      </div>

      <div class="ap-modal__foot">
        <button type="button" class="ap-btn ap-btn--ghost" id="ap-modal-set-a">
          <?php echo ap_map_icon('map-pin', 15); ?><span class="ap-btn__label">Set as pickup</span>
        </button>
        <button type="button" class="ap-btn ap-btn--primary" id="ap-modal-set-b">
          <?php echo ap_map_icon('flag-3', 15); ?><span class="ap-btn__label">Set as drop-off</span>
        </button>
      </div>
    </div>
  </div>
</div>

<script>
(function () {
  'use strict';

  // -------------------------------------------------------------------------
  // Config — every external endpoint lives here so it can be swapped for a
  // self-hosted instance once traffic grows (see the note at the top of this file).
  // -------------------------------------------------------------------------
  var WA_NUMBER = <?php echo wp_json_encode($ap_wa_number); ?>;
  var ICONS = <?php echo wp_json_encode($ap_js_icons); ?>;
  var PLACES = <?php echo wp_json_encode($ap_places); ?>;

  // Same values as the CSS custom properties above (theme teal-600 / gray-900).
  var THEME = { teal: '#0d9488', dark: '#111827' };
  var OSRM_URL = 'https://routing.openstreetmap.de/routed-car/route/v1/driving';
  var NOMINATIM_SEARCH = 'https://nominatim.openstreetmap.org/search';
  var NOMINATIM_REVERSE = 'https://nominatim.openstreetmap.org/reverse';

  // Service area: the Nusa Penida group (Penida, Lembongan, Ceningan).
  // The map cannot be panned outside it and every point is validated against it.
  var AREA = <?php echo wp_json_encode(array_map('floatval', $ap_area_bounds)); ?>; // [south, west, north, east]
  var AREA_BOUNDS = L.latLngBounds([AREA[0], AREA[1]], [AREA[2], AREA[3]]);
  var AREA_NAME = 'Nusa Penida';

  var DEFAULT_ZOOM = 12;
  var MIN_ZOOM = 11;
  var FETCH_TIMEOUT_MS = 15000;
  var SEARCH_DEBOUNCE_MS = 500;
  var SEARCH_MIN_CHARS = 3;

  // -------------------------------------------------------------------------
  // State
  // -------------------------------------------------------------------------
  var state = {
    map: null,
    markerA: null,
    markerB: null,
    routeLayer: null,
    labelA: '',
    labelB: '',
    route: null,        // { distance, duration } from the latest OSRM response
    pickMode: null,     // 'A' | 'B' | null  ("Pick on map" mode)
    lastValidA: null,   // last in-area position, used to undo an out-of-area drag
    lastValidB: null,
    vehicle: null,      // { id, label, desc, seats }
    placeMarkers: [],   // [{ place, marker }] for the popular spots
    activePlace: null,  // the spot currently shown in the modal
    searchCache: new Map()
  };

  var el = {};
  ['ap-map','ap-panel','ap-grip','ap-peek','ap-panel-body','ap-step-badge','ap-search-a','ap-results-a',
   'ap-search-b','ap-results-b','ap-locate','ap-swap','ap-hint','ap-status','ap-vehicles','ap-summary',
   'ap-distance','ap-duration','ap-calculate','ap-name','ap-date','ap-time','ap-pax','ap-note',
   'ap-reset','ap-book-wa','ap-toggle','ap-modal','ap-modal-backdrop','ap-modal-media','ap-modal-image',
   'ap-modal-badge','ap-modal-close','ap-modal-title','ap-modal-location','ap-modal-desc',
   'ap-modal-source','ap-modal-set-a','ap-modal-set-b'].forEach(function (id) {
    el[id] = document.getElementById(id);
  });

  function setButtonLabel(button, text) {
    var label = button.querySelector('.ap-btn__label');
    if (label) label.textContent = text;
  }

  // -------------------------------------------------------------------------
  // Map — locked to the service area
  // -------------------------------------------------------------------------
  function initMap() {
    state.map = L.map('ap-map', {
      // The booking panel occupies the top-left on desktop and the bottom edge
      // on mobile, so the zoom control is re-added on the right instead.
      zoomControl: false,
      minZoom: MIN_ZOOM,
      maxBounds: AREA_BOUNDS,
      maxBoundsViscosity: 1  // hard stop: dragging cannot leave the area at all
    }).fitBounds(AREA_BOUNDS);

    L.control.zoom({ position: 'topright' }).addTo(state.map);

    // OSM attribution is required by the tile usage policy — do not remove it.
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 19,
      minZoom: MIN_ZOOM,
      bounds: AREA_BOUNDS,       // no tiles are requested outside the area
      attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(state.map);

    // Visible hint of where the service area ends.
    L.rectangle(AREA_BOUNDS, {
      color: THEME.teal, weight: 1.5, dashArray: '6 6', fill: false, interactive: false
    }).addTo(state.map);

    addPlaceMarkers();

    state.map.on('click', onMapClick);
  }

  function isInsideArea(lat, lng) {
    return AREA_BOUNDS.contains(L.latLng(lat, lng));
  }

  function outsideAreaMessage() {
    return 'That location is outside our ' + AREA_NAME + ' service area. Please pick a point on or around the island.';
  }

  function onMapClick(e) {
    var lat = e.latlng.lat, lng = e.latlng.lng;

    if (!isInsideArea(lat, lng)) {
      showStatus(outsideAreaMessage(), 'error');
      return;
    }

    if (state.pickMode === 'A')      { setPoint('A', lat, lng); setPickMode(null); }
    else if (state.pickMode === 'B') { setPoint('B', lat, lng); setPickMode(null); }
    else if (!state.markerA)         { setPoint('A', lat, lng); }
    else if (!state.markerB)         { setPoint('B', lat, lng); }
    else { return; }

    if (state.markerA && state.markerB) calculateRoute();
  }

  // -------------------------------------------------------------------------
  // Popular spots: map markers + detail view
  // -------------------------------------------------------------------------
  function addPlaceMarkers() {
    PLACES.forEach(function (place) {
      var marker = L.marker([place.lat, place.lng], {
        icon: L.divIcon({
          className: '',   // the wrapper stays unstyled; .ap-place-pin does the work
          html: '<div class="ap-place-pin">' + (ICONS.places[place.id] || '') + '</div>',
          iconSize: [34, 34],
          iconAnchor: [17, 17],
          tooltipAnchor: [0, -14]
        }),
        title: place.name,
        riseOnHover: true,
        keyboard: true,
        alt: place.name
      }).addTo(state.map);

      marker.bindTooltip(place.name, { direction: 'top', offset: [0, -8] });

      marker.on('click', function () {
        // While "Pick on map" is armed, tapping a spot is the fastest way to
        // use it as that point — no need to go through the detail view.
        if (state.pickMode) {
          usePlaceAs(state.pickMode, place);
          setPickMode(null);
          return;
        }
        openPlaceModal(place);
      });

      state.placeMarkers.push({ place: place, marker: marker });
    });
  }

  function usePlaceAs(which, place) {
    setPoint(which, place.lat, place.lng, place.name);
    state.map.setView([place.lat, place.lng], 14);
    if (state.markerA && state.markerB) calculateRoute();
    markChosenPlaces();
  }

  /** Highlights the markers currently used as pickup or drop-off. */
  function markChosenPlaces() {
    state.placeMarkers.forEach(function (entry) {
      var el = entry.marker.getElement();
      if (!el) return;
      var pin = el.querySelector('.ap-place-pin');
      if (!pin) return;
      pin.classList.toggle('is-chosen',
        state.labelA === entry.place.name || state.labelB === entry.place.name);
    });
  }

  function openPlaceModal(place) {
    state.activePlace = place;

    el['ap-modal-title'].textContent = place.name;
    el['ap-modal-location'].textContent = place.location;
    el['ap-modal-desc'].textContent = place.description;
    el['ap-modal-badge'].innerHTML = (ICONS.places[place.id] || '') + '<span></span>';
    el['ap-modal-badge'].querySelector('span').textContent = place.category;
    el['ap-modal-source'].href = place.source;

    // Hotlinked third-party photos can disappear — drop the image area rather
    // than showing a broken frame.
    el['ap-modal-media'].style.display = '';
    el['ap-modal-image'].alt = place.name;
    el['ap-modal-image'].src = place.image;

    el['ap-modal'].classList.add('is-open');
    el['ap-modal'].setAttribute('aria-hidden', 'false');
    el['ap-modal-close'].focus();
  }

  function closePlaceModal() {
    el['ap-modal'].classList.remove('is-open');
    el['ap-modal'].setAttribute('aria-hidden', 'true');
    state.activePlace = null;
  }

  function initPlaceModal() {
    el['ap-modal-close'].addEventListener('click', closePlaceModal);
    el['ap-modal-backdrop'].addEventListener('click', closePlaceModal);

    el['ap-modal-image'].addEventListener('error', function () {
      el['ap-modal-media'].style.display = 'none';
    });

    el['ap-modal-set-a'].addEventListener('click', function () {
      if (!state.activePlace) return;
      usePlaceAs('A', state.activePlace);
      closePlaceModal();
    });

    el['ap-modal-set-b'].addEventListener('click', function () {
      if (!state.activePlace) return;
      usePlaceAs('B', state.activePlace);
      closePlaceModal();
    });

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && el['ap-modal'].classList.contains('is-open')) closePlaceModal();
    });
  }

  function pinIcon(label, color) {
    var svg = '<svg width="34" height="44" viewBox="0 0 34 44" xmlns="http://www.w3.org/2000/svg">' +
      '<path d="M17 0C7.6 0 0 7.6 0 17c0 12.7 17 27 17 27s17-14.3 17-27C34 7.6 26.4 0 17 0z" fill="' + color + '"/>' +
      '<circle cx="17" cy="17" r="8" fill="white" opacity="0.25"/></svg>';
    return L.divIcon({
      className: 'ap-pin',
      html: svg + '<span>' + label + '</span>',
      iconSize: [34, 44],
      iconAnchor: [17, 44]
    });
  }

  /**
   * Drops or moves point A or B. `label` is passed when the position came from a
   * search result (it already has a name); otherwise (map tap / drag / GPS) the
   * name is filled in afterwards via reverse geocoding.
   */
  function setPoint(which, lat, lng, label) {
    var isA = which === 'A';
    var markerKey = isA ? 'markerA' : 'markerB';
    var input = isA ? el['ap-search-a'] : el['ap-search-b'];

    if (state[markerKey]) {
      state[markerKey].setLatLng([lat, lng]);
    } else {
      state[markerKey] = L.marker([lat, lng], {
        draggable: true,
        icon: pinIcon(which, isA ? THEME.teal : THEME.dark),
        title: isA ? 'Pickup' : 'Drop-off'
      })
        .bindPopup(isA ? 'Pickup (A)' : 'Drop-off (B)')
        .addTo(state.map);

      state[markerKey].on('dragend', function (ev) {
        var pos = ev.target.getLatLng();

        // A marker must never end up outside the service area — snap it back.
        if (!isInsideArea(pos.lat, pos.lng)) {
          var previous = which === 'A' ? state.lastValidA : state.lastValidB;
          ev.target.setLatLng(previous);
          showStatus(outsideAreaMessage(), 'error');
          return;
        }

        rememberValid(which, pos);
        applyLabel(which, pos.lat, pos.lng);
        if (state.markerA && state.markerB) calculateRoute();
      });
    }

    rememberValid(which, L.latLng(lat, lng));
    applyLabel(which, lat, lng, label);
    input.blur();
    refreshUI();
  }

  /** Last in-area position per marker, used to undo an out-of-area drag. */
  function rememberValid(which, latlng) {
    if (which === 'A') state.lastValidA = latlng; else state.lastValidB = latlng;
  }

  function applyLabel(which, lat, lng, label) {
    var isA = which === 'A';
    var input = isA ? el['ap-search-a'] : el['ap-search-b'];
    var fallback = lat.toFixed(5) + ', ' + lng.toFixed(5);

    if (label) {
      input.value = label;
      if (isA) state.labelA = label; else state.labelB = label;
      refreshUI();
      return;
    }

    input.value = fallback;
    if (isA) state.labelA = fallback; else state.labelB = fallback;
    refreshUI();

    reverseGeocode(lat, lng).then(function (name) {
      if (!name) return;
      input.value = name;
      if (isA) state.labelA = name; else state.labelB = name;
      refreshUI();
    });
  }

  function reverseGeocode(lat, lng) {
    return fetch(NOMINATIM_REVERSE + '?format=jsonv2&lat=' + lat + '&lon=' + lng, {
      headers: { 'Accept-Language': 'en' }
    })
      .then(function (r) { return r.ok ? r.json() : null; })
      .then(function (d) { return d && d.display_name ? d.display_name : null; })
      .catch(function () { return null; }); // best effort — coordinates are already the fallback
  }

  // -------------------------------------------------------------------------
  // Location search (Nominatim) — results are clipped to the service area
  // -------------------------------------------------------------------------

  // Nominatim viewbox order is "west,north,east,south".
  var SEARCH_VIEWBOX = AREA[1] + ',' + AREA[2] + ',' + AREA[3] + ',' + AREA[0];

  function setupSearchBox(inputEl, resultsEl, which) {
    var timer = null;
    var controller = null;

    inputEl.addEventListener('input', function () {
      var query = inputEl.value.trim();
      clearTimeout(timer);
      if (query.length < SEARCH_MIN_CHARS) { hideResults(resultsEl); return; }
      timer = setTimeout(function () { runSearch(query); }, SEARCH_DEBOUNCE_MS);
    });

    inputEl.addEventListener('focus', function () {
      if (resultsEl.childElementCount > 0) resultsEl.classList.remove('ap-hidden');
    });

    function runSearch(query) {
      var key = query.toLowerCase();
      if (state.searchCache.has(key)) { renderResults(resultsEl, state.searchCache.get(key), which); return; }

      if (controller) controller.abort();
      controller = new AbortController();
      var timeout = setTimeout(function () { controller.abort(); }, FETCH_TIMEOUT_MS);

      // bounded=1 makes Nominatim return results from inside the viewbox only.
      var url = NOMINATIM_SEARCH + '?format=jsonv2&limit=8&addressdetails=0&bounded=1' +
                '&viewbox=' + SEARCH_VIEWBOX + '&q=' + encodeURIComponent(query);

      fetch(url, { signal: controller.signal, headers: { 'Accept-Language': 'en' } })
        .then(function (r) { if (!r.ok) throw new Error('geocode failed'); return r.json(); })
        .then(function (results) {
          // Second guard: Nominatim can still return near-misses on the edge.
          var inArea = (results || []).filter(function (r) {
            return isInsideArea(parseFloat(r.lat), parseFloat(r.lon));
          }).slice(0, 5);

          state.searchCache.set(key, inArea);
          renderResults(resultsEl, inArea, which);
        })
        .catch(function (err) { if (err.name !== 'AbortError') hideResults(resultsEl); })
        .then(function () { clearTimeout(timeout); });
    }
  }

  function renderResults(resultsEl, results, which) {
    resultsEl.innerHTML = '';

    if (!results || results.length === 0) {
      var empty = document.createElement('li');
      empty.className = 'is-empty';
      empty.textContent = 'No matching place in the ' + AREA_NAME + ' area.';
      resultsEl.appendChild(empty);
      resultsEl.classList.remove('ap-hidden');
      return;
    }

    results.forEach(function (result) {
      var li = document.createElement('li');
      li.textContent = result.display_name;
      li.addEventListener('click', function () {
        var lat = parseFloat(result.lat), lng = parseFloat(result.lon);
        setPoint(which, lat, lng, result.display_name);
        state.map.setView([lat, lng], 15);
        hideResults(resultsEl);
        if (state.markerA && state.markerB) calculateRoute();
      });
      resultsEl.appendChild(li);
    });

    resultsEl.classList.remove('ap-hidden');
  }

  function hideResults(resultsEl) { resultsEl.classList.add('ap-hidden'); }

  // -------------------------------------------------------------------------
  // Routing (OSRM)
  // -------------------------------------------------------------------------
  function calculateRoute() {
    if (!state.markerA || !state.markerB) {
      showStatus('Set your pickup (A) and drop-off (B) first.', 'info');
      return;
    }

    var a = state.markerA.getLatLng();
    var b = state.markerB.getLatLng();

    hideStatus();
    el['ap-calculate'].disabled = true;
    setButtonLabel(el['ap-calculate'], 'Calculating route…');

    // OSRM expects "lng,lat" — the opposite of Leaflet's [lat, lng] order.
    var coords = a.lng + ',' + a.lat + ';' + b.lng + ',' + b.lat;
    var controller = new AbortController();
    var timeout = setTimeout(function () { controller.abort(); }, FETCH_TIMEOUT_MS);

    fetch(OSRM_URL + '/' + coords + '?overview=full&geometries=geojson', { signal: controller.signal })
      .then(function (r) {
        if (r.status === 429) throw new Error('Too many requests. Please wait a moment and try again.');
        if (!r.ok) throw new Error('The routing service is having trouble. Please try again later.');
        return r.json();
      })
      .then(function (data) {
        if (data.code !== 'Ok' || !data.routes || !data.routes.length) {
          throw new Error('No road route could be found between those two points.');
        }
        renderRoute(data.routes[0]);
      })
      .catch(function (err) {
        removeRouteLayer();
        state.route = null;
        el['ap-summary'].classList.add('ap-hidden');
        showStatus(err.name === 'AbortError'
          ? 'The route request took too long. Please try again.'
          : (err.message || 'Something went wrong. Please try again.'), 'error');
        refreshUI();
      })
      .then(function () {
        clearTimeout(timeout);
        setButtonLabel(el['ap-calculate'], 'Calculate Route');
        refreshUI();
      });
  }

  function renderRoute(route) {
    removeRouteLayer();

    // GeoJSON LineString ([lng, lat]) is converted automatically by L.geoJSON.
    state.routeLayer = L.geoJSON(route.geometry, {
      style: { color: THEME.teal, weight: 5, opacity: .85 }
    }).addTo(state.map);

    state.map.fitBounds(state.routeLayer.getBounds(), { padding: [50, 50] });

    state.route = { distance: route.distance, duration: route.duration };
    el['ap-distance'].textContent = formatDistance(route.distance);
    el['ap-duration'].textContent = formatDuration(route.duration);
    el['ap-summary'].classList.remove('ap-hidden');
    showStatus('Route found. Fill in your booking details below.', 'ok');
  }

  function removeRouteLayer() {
    if (state.routeLayer) { state.map.removeLayer(state.routeLayer); state.routeLayer = null; }
  }

  // -------------------------------------------------------------------------
  // GPS location
  // -------------------------------------------------------------------------
  function useMyLocation() {
    hideStatus();

    if (!('geolocation' in navigator)) {
      showStatus('Your browser does not support location detection.', 'error');
      return;
    }

    el['ap-locate'].disabled = true;
    setButtonLabel(el['ap-locate'], 'Locating…');

    navigator.geolocation.getCurrentPosition(function (pos) {
      el['ap-locate'].disabled = false;
      setButtonLabel(el['ap-locate'], 'Use My Location');

      var lat = pos.coords.latitude, lng = pos.coords.longitude;

      // Most visitors book before they arrive, so being off-island is expected —
      // say so plainly instead of silently doing nothing.
      if (!isInsideArea(lat, lng)) {
        showStatus('You are currently outside ' + AREA_NAME + '. Pick your pickup point on the map instead.', 'info');
        return;
      }

      setPoint('A', lat, lng);
      state.map.setView([lat, lng], 15);
      if (state.markerB) calculateRoute();
    }, function (error) {
      el['ap-locate'].disabled = false;
      setButtonLabel(el['ap-locate'], 'Use My Location');

      var messages = {
        1: 'Location permission denied. Enable location access in your browser.',
        2: 'Your location could not be determined right now.',
        3: 'The location request took too long.'
      };
      showStatus(messages[error.code] || 'Could not get your location.', 'error');
    }, { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 });
  }

  // -------------------------------------------------------------------------
  // Booking → WhatsApp
  // -------------------------------------------------------------------------
  function gmapsPoint(latlng) {
    return 'https://www.google.com/maps/search/?api=1&query=' + latlng.lat.toFixed(6) + ',' + latlng.lng.toFixed(6);
  }

  function gmapsDirections(a, b) {
    return 'https://www.google.com/maps/dir/?api=1' +
      '&origin=' + a.lat.toFixed(6) + ',' + a.lng.toFixed(6) +
      '&destination=' + b.lat.toFixed(6) + ',' + b.lng.toFixed(6) +
      '&travelmode=driving';
  }

  function buildWhatsAppMessage() {
    var a = state.markerA.getLatLng();
    var b = state.markerB.getLatLng();
    var vehicle = state.vehicle || { label: 'Car', desc: '' };
    var pax = parseInt(el['ap-pax'].value, 10) || 1;
    var note = el['ap-note'].value.trim();

    var lines = [
      'Hello Activities Penida Tour, I would like to *book a vehicle*.',
      '',
      '🚗 Vehicle: ' + vehicle.label + (vehicle.desc ? ' (' + vehicle.desc + ')' : ''),
      '👤 Name: ' + el['ap-name'].value.trim(),
      '📅 Date: ' + formatDate(el['ap-date'].value),
      '🕐 Pickup time: ' + el['ap-time'].value + ' WITA',
      '👥 Passengers: ' + pax + (pax === 1 ? ' person' : ' people'),
      '',
      '📍 *Pickup:*',
      state.labelA,
      gmapsPoint(a),
      '',
      '🏁 *Drop-off:*',
      state.labelB,
      gmapsPoint(b)
    ];

    if (state.route) {
      lines.push('', '🧭 Route: ' + gmapsDirections(a, b));
      lines.push('📏 Distance: ' + formatDistance(state.route.distance) +
                 ' · ⏱️ Estimated: ' + formatDuration(state.route.duration));
    }

    if (note) lines.push('', '📝 Notes: ' + note);

    lines.push('', 'Please let me know availability and pricing. Thank you 🙏');

    return lines.join('\n');
  }

  function sendBooking() {
    if (!isBookingReady()) return;
    var url = 'https://wa.me/' + WA_NUMBER + '?text=' + encodeURIComponent(buildWhatsAppMessage());
    window.open(url, '_blank', 'noopener');
  }

  function isBookingReady() {
    return !!(state.markerA && state.markerB && el['ap-name'].value.trim() &&
              el['ap-date'].value && el['ap-time'].value);
  }

  // -------------------------------------------------------------------------
  // UI helpers
  // -------------------------------------------------------------------------
  function refreshUI() {
    var hasA = !!state.markerA, hasB = !!state.markerB;

    el['ap-calculate'].disabled = !(hasA && hasB);
    el['ap-book-wa'].disabled = !isBookingReady();

    // Contextual hint
    if (state.pickMode) {
      el['ap-hint'].innerHTML = 'Tap the map to drop your <b>' +
        (state.pickMode === 'A' ? 'pickup point (A)' : 'drop-off point (B)') + '</b>.';
    } else if (!hasA) {
      el['ap-hint'].innerHTML = 'Tap a <b>popular spot</b> on the map, search a place, or tap anywhere on the map to set your <b>pickup point (A)</b>.';
    } else if (!hasB) {
      el['ap-hint'].innerHTML = 'Pickup is set. Now choose your <b>drop-off point (B)</b>.';
    } else if (!state.route) {
      el['ap-hint'].innerHTML = 'Tap <b>Calculate Route</b> to see the distance and estimated time.';
    } else {
      el['ap-hint'].innerHTML = 'Drag marker A or B to adjust — the route recalculates automatically.';
    }

    // Step badge
    var step = !hasA || !hasB ? 1 : (!state.route ? 2 : 3);
    el['ap-step-badge'].textContent = 'Step ' + step + ' of 3';

    // Peek summary (mobile, while the sheet is collapsed)
    if (!hasA && !hasB) {
      el['ap-peek'].innerHTML = 'Choose your pickup &amp; drop-off to get started.';
    } else {
      var short = function (text) {
        if (!text) return '—';
        var first = text.split(',')[0];
        return first.length > 26 ? first.slice(0, 26) + '…' : first;
      };
      var summary = '<b>' + escapeHtml(short(state.labelA)) + '</b> → <b>' + escapeHtml(short(state.labelB)) + '</b>';
      if (state.route) {
        summary += ' · ' + formatDistance(state.route.distance) + ' · ' + formatDuration(state.route.duration);
      }
      el['ap-peek'].innerHTML = summary;
    }

    markChosenPlaces();
  }

  function escapeHtml(str) {
    return String(str).replace(/[&<>"']/g, function (c) {
      return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
    });
  }

  function setPickMode(mode) {
    state.pickMode = mode;
    document.querySelectorAll('.ap-point__pick').forEach(function (btn) {
      btn.classList.toggle('is-active', btn.dataset.pick === mode);
    });
    // Collapse the sheet so the map is fully visible while picking a point.
    if (mode) collapseSheet(true);
    refreshUI();
  }

  function showStatus(message, kind) {
    kind = kind || 'error';
    el['ap-status'].className = 'ap-status ap-status--' + kind;
    el['ap-status'].innerHTML = (ICONS[kind] || ICONS.error) + '<span></span>';
    el['ap-status'].querySelector('span').textContent = message;
  }

  function hideStatus() {
    el['ap-status'].className = 'ap-status ap-status--error ap-hidden';
    el['ap-status'].innerHTML = '';
  }

  function formatDistance(meters) {
    return meters >= 1000 ? (meters / 1000).toFixed(1) + ' km' : Math.round(meters) + ' m';
  }

  function formatDuration(seconds) {
    var minutes = Math.round(seconds / 60);
    if (minutes < 60) return minutes + ' min';
    var hours = Math.floor(minutes / 60);
    var rest = minutes % 60;
    return rest > 0 ? hours + ' hr ' + rest + ' min' : hours + ' hr';
  }

  function formatDate(value) {
    if (!value) return '—';
    var parts = value.split('-');
    var months = ['January','February','March','April','May','June',
                  'July','August','September','October','November','December'];
    return months[parseInt(parts[1], 10) - 1] + ' ' + parseInt(parts[2], 10) + ', ' + parts[0];
  }

  function resetAll() {
    if (state.markerA) { state.map.removeLayer(state.markerA); state.markerA = null; }
    if (state.markerB) { state.map.removeLayer(state.markerB); state.markerB = null; }
    removeRouteLayer();

    state.route = null;
    state.labelA = '';
    state.labelB = '';
    el['ap-search-a'].value = '';
    el['ap-search-b'].value = '';
    el['ap-note'].value = '';
    el['ap-summary'].classList.add('ap-hidden');
    hideResults(el['ap-results-a']);
    hideResults(el['ap-results-b']);
    hideStatus();
    closePlaceModal();
    setPickMode(null);
    state.map.fitBounds(AREA_BOUNDS);
    refreshUI();
  }

  function swapPoints() {
    if (!state.markerA || !state.markerB) {
      showStatus('Both points must be set before they can be swapped.', 'info');
      return;
    }

    var a = state.markerA.getLatLng();
    var b = state.markerB.getLatLng();
    var labelA = state.labelA, labelB = state.labelB;

    setPoint('A', b.lat, b.lng, labelB);
    setPoint('B', a.lat, a.lng, labelA);
    calculateRoute();
  }

  // -------------------------------------------------------------------------
  // Bottom sheet (mobile): toggle by button, tapping the handle, or dragging.
  // -------------------------------------------------------------------------
  function collapseSheet(collapsed) {
    el['ap-panel'].classList.toggle('is-collapsed', collapsed);
    el['ap-toggle'].innerHTML = collapsed ? ICONS.chevronUp : ICONS.chevronDown;
    el['ap-toggle'].setAttribute('aria-expanded', collapsed ? 'false' : 'true');
    // The map container size changes once the sheet has moved.
    setTimeout(function () { state.map.invalidateSize(); }, 300);
  }

  function isCollapsed() { return el['ap-panel'].classList.contains('is-collapsed'); }

  function initSheet() {
    el['ap-toggle'].addEventListener('click', function () { collapseSheet(!isCollapsed()); });

    var startY = null;
    var grip = el['ap-grip'];

    grip.addEventListener('pointerdown', function (e) {
      startY = e.clientY;
      grip.setPointerCapture(e.pointerId);
    });

    grip.addEventListener('pointerup', function (e) {
      if (startY === null) return;
      var dy = e.clientY - startY;
      startY = null;

      if (dy > 30) collapseSheet(true);        // drag down → close
      else if (dy < -30) collapseSheet(false); // drag up   → open
      else collapseSheet(!isCollapsed());      // tap       → toggle
    });

    grip.addEventListener('keydown', function (e) {
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        collapseSheet(!isCollapsed());
      }
    });

    // On mobile the sheet starts collapsed so the map is visible right away.
    if (window.matchMedia('(max-width: 767px)').matches) {
      el['ap-panel'].classList.add('is-collapsed');
      el['ap-toggle'].innerHTML = ICONS.chevronUp;
      el['ap-toggle'].setAttribute('aria-expanded', 'false');
    }
  }

  // -------------------------------------------------------------------------
  // Wiring
  // -------------------------------------------------------------------------
  function initControls() {
    setupSearchBox(el['ap-search-a'], el['ap-results-a'], 'A');
    setupSearchBox(el['ap-search-b'], el['ap-results-b'], 'B');

    document.addEventListener('click', function (e) {
      if (!el['ap-search-a'].contains(e.target) && !el['ap-results-a'].contains(e.target)) hideResults(el['ap-results-a']);
      if (!el['ap-search-b'].contains(e.target) && !el['ap-results-b'].contains(e.target)) hideResults(el['ap-results-b']);
    });

    document.querySelectorAll('.ap-point__pick').forEach(function (btn) {
      btn.addEventListener('click', function () {
        setPickMode(state.pickMode === btn.dataset.pick ? null : btn.dataset.pick);
      });
    });

    el['ap-vehicles'].querySelectorAll('.ap-vehicle').forEach(function (btn) {
      if (btn.disabled) return;
      btn.addEventListener('click', function () {
        el['ap-vehicles'].querySelectorAll('.ap-vehicle').forEach(function (b) { b.classList.remove('is-active'); });
        btn.classList.add('is-active');
        selectVehicle(btn);
      });
      if (btn.classList.contains('is-active')) selectVehicle(btn);
    });

    el['ap-locate'].addEventListener('click', useMyLocation);
    el['ap-swap'].addEventListener('click', swapPoints);
    el['ap-calculate'].addEventListener('click', calculateRoute);
    el['ap-reset'].addEventListener('click', resetAll);
    el['ap-book-wa'].addEventListener('click', sendBooking);

    ['ap-name', 'ap-date', 'ap-time', 'ap-pax'].forEach(function (id) {
      el[id].addEventListener('input', refreshUI);
      el[id].addEventListener('change', refreshUI);
    });
  }

  /** Stores the selected vehicle and rebuilds the passenger options to match its capacity. */
  function selectVehicle(btn) {
    state.vehicle = {
      id: btn.dataset.vehicle,
      label: btn.dataset.label,
      desc: btn.dataset.desc,
      seats: parseInt(btn.dataset.seats, 10) || 1
    };

    var select = el['ap-pax'];
    var previous = parseInt(select.value, 10) || 1;
    select.innerHTML = '';
    for (var i = 1; i <= state.vehicle.seats; i++) {
      var option = document.createElement('option');
      option.value = String(i);
      option.textContent = i + (i === 1 ? ' person' : ' people');
      select.appendChild(option);
    }
    select.value = String(Math.min(previous, state.vehicle.seats));
    refreshUI();
  }

  // -------------------------------------------------------------------------
  // Init
  // -------------------------------------------------------------------------
  initMap();
  initControls();
  initSheet();
  initPlaceModal();
  refreshUI();
})();
</script>

<?php wp_footer(); ?>
</body>
</html>
