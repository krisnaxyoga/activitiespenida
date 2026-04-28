<!doctype html>
<html <?php language_attributes(); ?> class="scroll-smooth">

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
   <meta name="google-site-verification" content="3NnJdiSDGXKzqjJtb-WAjWEejPBYwqaYgiTTCBGMTnQ" />
   
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-QF68RWJEY0">
</script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-QF68RWJEY0');
</script>
    <!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-N9VHSWMM');</script>
<!-- End Google Tag Manager -->
<link rel="preload" as="image"
        href="<?php echo esc_url( home_url( '/wp-content/uploads/2025/10/klingking-beach-activities-penidatour-ikomangartawann.webp' ) ); ?>"
        fetchpriority="high" type="image/webp">
    <?php wp_head(); ?>
   
</head>

<body <?php body_class('bg-gray-50 text-gray-800 antialiased'); ?>>
    <?php wp_body_open(); ?>
    <!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-N9VHSWMM"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
    <!-- Mobile Header (hanya nama aplikasi) -->
    <header class="md:hidden sticky top-0 z-50 bg-white/95 backdrop-blur shadow-sm">
        <div class="container mx-auto px-4 h-16 flex items-center justify-between">
            <a class="text-lg font-bold tracking-tight text-gray-900" href="<?php echo esc_url(home_url()); ?>">
                <img src="<?php echo esc_url(home_url()); ?>/wp-content/uploads/2025/10/logo-activities-penidatour-nusa-penida-komangtawan.png"
                    alt="Logo Activities Penidatour"
                    title="Logo Activities Penidatour"
                    class="w-12 h-auto" loading="lazy">
            </a>
            <button id="mobile-menu-button" class="p-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-300"
                aria-label="Toggle Mobile Menu">
                <svg id="menu-icon" class="w-6 h-6 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg id="close-icon" class="w-6 h-6 text-gray-800 hidden" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <!-- Mobile Menu (tersembunyi default) -->
        <div id="mobile-menu" class="hidden px-4 pb-4 bg-white border-t border-gray-100">
            <?php
            wp_nav_menu([
                'theme_location' => 'primary',
                'container'      => false,
                'menu_class'     => 'flex flex-col space-y-3 font-medium',
                'depth'          => 1
            ]);
            ?>
        </div>
    </header>

    <!-- Desktop Header (nama + menu) -->
    <header class="hidden md:block sticky top-0 z-50 bg-white/95 backdrop-blur shadow-sm">
        <div class="container mx-auto px-4 flex items-center justify-between h-16">
            <a class="text-xl font-bold tracking-tight text-gray-900" href="<?php echo esc_url(home_url()); ?>">
                <img src="<?php echo esc_url(home_url()); ?>/wp-content/uploads/2025/10/logo-activities-penidatour-nusa-penida-komangtawan.png"
                    alt="Logo Activities Penidatour"
                    title="Logo Activities Penidatour"
                    class="w-12 h-auto" loading="lazy">
            </a>
            <nav class="flex space-x-6 font-medium">
                <?php
                wp_nav_menu([
                    'theme_location' => 'primary',
                    'container'      => false,
                    'menu_class'     => 'flex space-x-6',
                    'fallback_cb'    => false,
                    'depth'          => 1
                ]);
                ?>
            </nav>
        </div>
    </header>