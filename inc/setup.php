<?php
/**
 * inc/setup.php
 * Semua setup dasar theme - OPTIMIZED VERSION
 */

/*--------------------------------------------------------------
>>> TABLE OF CONTENTS:
----------------------------------------------------------------
1. Theme setup
2. Custom image sizes
3. JPEG quality
4. Nav menu attributes
5. Calculate reading time
6. 404 Page enhancements
7. Auto update post modified date
8. Auto title suffix
9. Auto internal linking system
10. Business Directory System (OPTIMIZED + PAGINATION)
11. Business rewrite rules
--------------------------------------------------------------*/

/*--------------------------------------------------------------
1. Theme setup
--------------------------------------------------------------*/
add_action('after_setup_theme', 'theme_setup');
function theme_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    register_nav_menus([
        'primary' => __('Primary Menu', 'textdomain'),
        'footer'  => __('Footer Menu', 'textdomain'),
    ]);
}

/*--------------------------------------------------------------
2. Custom image sizes
--------------------------------------------------------------*/
add_action('init', 'theme_image_sizes');
function theme_image_sizes() {
    add_image_size('blog-thumbnail', 800, 450, true);
    add_image_size('blog-single', 1200, 630, true);
    add_image_size('business-logo', 150, 150, true); // NEW: Business logo size
}

/*--------------------------------------------------------------
3. JPEG quality
--------------------------------------------------------------*/
add_filter('jpeg_quality', fn() => 95);
add_filter('wp_editor_set_quality', fn() => 95);

/*--------------------------------------------------------------
4. Nav menu attributes
--------------------------------------------------------------*/
add_filter('nav_menu_link_attributes', 'theme_fix_anchor_links', 10, 3);
function theme_fix_anchor_links($atts, $item, $args) {
    if (!isset($atts['href'])) return $atts;
    $href = $atts['href'];
    $hashPos = strpos($href, '#');
    if ($hashPos !== false) {
        $hash = substr($href, $hashPos);
        $href = home_url() . $hash;
    }
    $atts['href'] = $href;
    return $atts;
}

/*--------------------------------------------------------------
5. Calculate reading time
--------------------------------------------------------------*/
function calculate_reading_time($post_id) {
    $post = get_post($post_id);
    if (!$post) return 0;
    $content = strip_tags($post->post_content);
    $word_count = str_word_count($content);
    $reading_time = ceil($word_count / 200);
    return max(1, $reading_time);
}

/*--------------------------------------------------------------
6. 404 Page enhancements
--------------------------------------------------------------*/
function np_enqueue_404_css() {
    if (is_404()) {
        wp_enqueue_style(
            'np-404-english-tailwind',
            get_template_directory_uri() . '/assets/css/404-english-tailwind.css',
            [],
            wp_get_theme()->get('Version')
        );
    }
}
add_action('wp_enqueue_scripts', 'np_enqueue_404_css');

function np_404_recent_posts() {
    return new WP_Query([
        'post_type'      => 'post',
        'posts_per_page' => 3,
        'post_status'    => 'publish'
    ]);
}

/*--------------------------------------------------------------
7. Auto update post modified date
--------------------------------------------------------------*/
function auto_update_post_modified_date() {
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => 3,
        'orderby' => 'rand',
    );
    $posts = get_posts($args);
    foreach ($posts as $post) {
        wp_update_post(array(
            'ID' => $post->ID,
            'post_modified' => current_time('mysql'),
            'post_modified_gmt' => gmdate('Y-m-d H:i:s'),
        ));
    }
}
add_action('my_daily_post_update', 'auto_update_post_modified_date');
if (!wp_next_scheduled('my_daily_post_update')) {
    wp_schedule_event(time(), 'daily', 'my_daily_post_update');
}

/*--------------------------------------------------------------
8. Auto title suffix
--------------------------------------------------------------*/
add_filter('the_title', function($title) {
    if (is_single() && in_the_loop()) {
        $title .= ' (' . date('F Y') . ')';
    }
    return $title;
});

/*--------------------------------------------------------------
9. Auto Internal Link System
--------------------------------------------------------------*/
function apt_auto_internal_links($content) {
    if (!is_single() || is_admin()) {
        return $content;
    }
    
    $auto_links = array(
        'snorkeling nusa penida'   => 'https://activitiespenidatour.com/blog/the-ultimate-guide-to-snorkeling-with-giant-manta-rays-at-manta-point-nusa-penida/',
        'west trip nusa penida'    => 'https://activitiespenidatour.com/blog/we-found-one-piece-is-atuh-beach-in-nusa-penida-the-real-life-laugh-tale-island/',
        'east trip nusa penida'    => 'https://activitiespenidatour.com/blog/the-ultimate-guide-to-west-nusa-penida-tour-exploring-the-iconic-kelingking-broken-beach-angels-billabong/',
        'day trip nusa penida'     => 'https://activitiespenidatour.com/blog/discover-nusa-penida-a-complete-guide-to-tours-trusted-car-rentals-and-unique-camping-experiences/',
        'tour nusa penida'         => 'https://activitiespenidatour.com/blog/why-nusa-penida-is-becoming-a-top-travel-destination/',
        'nusa penida package'      => 'https://activitiespenidatour.com',
        'private tour nusa penida' => 'https://activitiespenidatour.com/blog/nusa-penida-cruise-the-coastline-on-your-own-private-bali-adventure/',
        'one day trip nusa penida' => 'https://activitiespenidatour.com/blog/your-ultimate-guide-to-the-best-nusa-penida-tour-from-bali/',
    );
    
    $max_links = 3;
    $link_count = 0;
    
    foreach ($auto_links as $keyword => $url) {
        if ($link_count >= $max_links) break;
        if (preg_match('/<a[^>]+>' . preg_quote($keyword, '/') . '<\/a>/i', $content)) continue;
        
        $pattern = '/\b(' . preg_quote($keyword, '/') . ')\b/i';
        if (preg_match($pattern, $content)) {
            $replacement = '<a href="' . esc_url($url) . '" title="Explore ' . esc_attr($keyword) . ' in Nusa Penida">' . $keyword . '</a>';
            $content = preg_replace($pattern, $replacement, $content, 1);
            $link_count++;
        }
    }
    
    return $content;
}
add_filter('the_content', 'apt_auto_internal_links', 12);

/*--------------------------------------------------------------
10. Business Directory System - OPTIMIZED WITH PAGINATION
--------------------------------------------------------------*/
add_action('wp_ajax_get_business_listings_template', 'get_business_listings_template');
add_action('wp_ajax_nopriv_get_business_listings_template', 'get_business_listings_template');

function get_business_listings_template() {
    // Security check
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'business_directory_nonce')) {
        wp_send_json_error('Security check failed');
        return;
    }
    
    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $per_page = 10; // Optimized: 10 items per page for better performance
    $filters = isset($_POST['filters']) ? json_decode(stripslashes($_POST['filters']), true) : array();
    
    // Base query args
    $args = array(
        'post_type'      => 'business',
        'posts_per_page' => $per_page,
        'paged'          => $page,
        'post_status'    => 'publish',
        'no_found_rows'  => false, // Need this for pagination
    );
    
    global $business_search_term;
    $business_search_term = '';
    
    // Apply search filter
    if (!empty($filters['search'])) {
        $business_search_term = sanitize_text_field($filters['search']);
        add_filter('posts_search', 'business_custom_search', 10, 2);
        add_filter('posts_join', 'business_custom_search_join', 10, 2);
        add_filter('posts_distinct', 'business_custom_search_distinct', 10, 2);
    }
    
    // Apply category filter
    if (!empty($filters['category'])) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'business_category',
                'field'    => 'slug',
                'terms'    => sanitize_text_field($filters['category'])
            )
        );
    }
    
    // Apply sorting
    if (!empty($filters['sort'])) {
        switch ($filters['sort']) {
            case 'title-desc':
                $args['orderby'] = 'title';
                $args['order'] = 'DESC';
                break;
            case 'date-desc':
                $args['orderby'] = 'date';
                $args['order'] = 'DESC';
                break;
            case 'date-asc':
                $args['orderby'] = 'date';
                $args['order'] = 'ASC';
                break;
            case 'priority':
                $args['meta_key'] = '_business_priority';
                $args['orderby'] = array(
                    'meta_value_num' => 'DESC',
                    'title' => 'ASC'
                );
                break;
            default:
                $args['orderby'] = 'title';
                $args['order'] = 'ASC';
        }
    } else {
        $args['meta_key'] = '_business_priority';
        $args['orderby'] = array(
            'meta_value_num' => 'DESC',
            'title' => 'ASC'
        );
    }
    
    $business_query = new WP_Query($args);
    
    // Remove filters
    remove_filter('posts_search', 'business_custom_search', 10);
    remove_filter('posts_join', 'business_custom_search_join', 10);
    remove_filter('posts_distinct', 'business_custom_search_distinct', 10);
    
    $total_businesses = $business_query->found_posts;
    $total_pages = $business_query->max_num_pages;
    $start_num = ($page - 1) * $per_page + 1;
    $end_num = min($page * $per_page, $total_businesses);
    
    ob_start();
    
    if ($business_query->have_posts()) {
        while ($business_query->have_posts()) {
            $business_query->the_post();
            
            $business_id = get_the_ID();
            $website = get_post_meta($business_id, '_business_website', true);
            $phone = get_post_meta($business_id, '_business_phone', true);
            $email = get_post_meta($business_id, '_business_email', true);
            $address = get_post_meta($business_id, '_business_address', true);
            $description = get_post_meta($business_id, '_business_description', true);
            $google_maps = get_post_meta($business_id, '_business_google_maps', true);
            $instagram = get_post_meta($business_id, '_business_instagram', true);
            $facebook = get_post_meta($business_id, '_business_facebook', true);
            $tiktok = get_post_meta($business_id, '_business_tiktok', true);
            $priority = get_post_meta($business_id, '_business_priority', true);
            
            $categories = get_the_terms($business_id, 'business_category');
            $category_name = $categories && !is_wp_error($categories) ? $categories[0]->name : '';
            
            // Generate initials
            $business_title = get_the_title();
            $words = explode(' ', $business_title);
            $initials = '';
            foreach ($words as $word) {
                if (!empty($word)) {
                    $initials .= strtoupper(substr($word, 0, 1));
                    if (strlen($initials) >= 2) break;
                }
            }
            if (strlen($initials) < 2) {
                $initials = strtoupper(substr($business_title, 0, 2));
            }
            
            // Generate color
            $colors = ['#4285f4', '#ea4335', '#fbbc04', '#34a853', '#ff6d00', '#46bdc6', '#7b1fa2', '#f50057'];
            $color_index = abs(crc32($business_title)) % count($colors);
            $brand_color = $colors[$color_index];
            ?>
            
            <article class="business-card-google mb-2" itemscope itemtype="https://schema.org/LocalBusiness">
    <div class="business-google-container">
        
        <!-- Content Area - FIRST in HTML -->
        <div class="business-content-area">
            
            <!-- Header -->
            <header class="business-header-google">
                <div class="business-title-row">
                    <h3 class="business-title-google" itemprop="name">
                        <a href="<?php the_permalink(); ?>" 
                           class="business-title-link" 
                           itemprop="url"
                           aria-label="Visit <?php echo esc_attr(get_the_title()); ?> page">
                            <?php the_title(); ?>
                        </a>
                    </h3>
                    <?php if ($priority > 0) : ?>
                        <span class="featured-badge-google" aria-label="Featured Business">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                        </span>
                    <?php endif; ?>
                </div>
                
                <!-- Meta Row -->
                <div class="business-meta-row">
                    <?php if ($category_name) : ?>
                        <span class="business-category-google" itemprop="additionalType">
                            <?php echo esc_html($category_name); ?>
                        </span>
                        <span class="meta-separator" aria-hidden="true">¡¤</span>
                    <?php endif; ?>
                    <?php if ($website) : ?>
                        <span class="business-url-google">
                            <?php 
                            $parsed_url = parse_url($website);
                            echo esc_html($parsed_url['host'] ?? $website); 
                            ?>
                        </span>
                    <?php endif; ?>
                </div>
            </header>
            
            <!-- Description -->
            <?php if ($description) : ?>
                <div class="business-description-google" itemprop="description">
                    <?php echo esc_html(wp_trim_words($description, 25)); ?>
                </div>
            <?php endif; ?>
            
            <!-- Contact Tags -->
            <div class="business-tags-google">
                <?php if ($phone) : ?>
                    <a href="tel:<?php echo esc_attr(preg_replace('/[^\d+]/', '', $phone)); ?>" 
                       class="business-tag-item"
                       itemprop="telephone"
                       aria-label="Call <?php echo esc_attr($phone); ?>">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        <span><?php echo esc_html($phone); ?></span>
                    </a>
                <?php endif; ?>
                
                <?php if ($address) : ?>
                    <span class="business-tag-item" itemprop="address" itemscope itemtype="https://schema.org/PostalAddress">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span itemprop="streetAddress"><?php echo esc_html(wp_trim_words($address, 6, '...')); ?></span>
                    </span>
                <?php endif; ?>
                
                <?php if ($email) : ?>
                    <a href="mailto:<?php echo esc_attr($email); ?>" 
                       class="business-tag-item"
                       itemprop="email"
                       aria-label="Email <?php echo esc_attr($email); ?>">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <span><?php echo esc_html($email); ?></span>
                    </a>
                <?php endif; ?>
            </div>
            
            <!-- Action Buttons -->
            <div class="business-actions-google">
                <?php if ($phone) : ?>
                    <a href="https://wa.me/<?php echo esc_attr(preg_replace('/[^\d+]/', '', $phone)); ?>" 
                       class="action-btn-google whatsapp-btn" 
                       target="_blank" 
                       rel="noopener noreferrer"
                       aria-label="Contact via WhatsApp">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                        </svg>
                        WhatsApp
                    </a>
                <?php endif; ?>
                
                <?php if ($google_maps) : ?>
                    <a href="<?php echo esc_url($google_maps); ?>" 
                       class="action-btn-google map-btn" 
                       target="_blank" 
                       rel="noopener noreferrer"
                       aria-label="View on Google Maps">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                        </svg>
                        Maps
                    </a>
                <?php endif; ?>
                
                <?php if ($website) : ?>
                    <a href="<?php echo esc_url($website); ?>" 
                       class="action-btn-google website-btn" 
                       target="_blank" 
                       rel="noopener noreferrer"
                       aria-label="Visit website">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                        </svg>
                        Website
                    </a>
                <?php endif; ?>
            </div>
            
            <!-- Social Links -->
            <?php if ($instagram || $facebook || $tiktok) : ?>
                <div class="business-social-google">
                    <?php if ($instagram) : ?>
                        <a href="<?php echo esc_url($instagram); ?>" 
                           class="social-link-google instagram" 
                           target="_blank" 
                           rel="noopener noreferrer"
                           aria-label="Follow on Instagram">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                            </svg>
                        </a>
                    <?php endif; ?>
                    
                    <?php if ($facebook) : ?>
                        <a href="<?php echo esc_url($facebook); ?>" 
                           class="social-link-google facebook" 
                           target="_blank" 
                           rel="noopener noreferrer"
                           aria-label="Follow on Facebook">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                    <?php endif; ?>
                    
                    <?php if ($tiktok) : ?>
                        <a href="<?php echo esc_url($tiktok); ?>" 
                           class="social-link-google tiktok" 
                           target="_blank" 
                           rel="noopener noreferrer"
                           aria-label="Follow on TikTok">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/>
                            </svg>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
        </div>
        
        <!-- Logo Area - SECOND in HTML (will appear on RIGHT) -->
        <div class="business-logo-area">
            <?php if (has_post_thumbnail()) : ?>
                <div class="business-logo-image">
                    <?php 
                    // Lazy loading for performance
                    the_post_thumbnail('thumbnail', array(
                        'alt' => get_the_title(),
                        'loading' => 'lazy',
                        'itemprop' => 'image',
                        'class' => 'business-logo-img'
                    )); 
                    ?>
                </div>
            <?php else : ?>
                <div class="business-logo-fallback" style="background-color: <?php echo esc_attr($brand_color); ?>;">
                    <span class="business-initials" aria-label="<?php echo esc_attr($business_title); ?> logo">
                        <?php echo esc_html($initials); ?>
                    </span>
                </div>
            <?php endif; ?>
        </div>
        
    </div>
</article>
            
            <?php
        }
        wp_reset_postdata();
    }
    
    $html = ob_get_clean();
    
    // Generate pagination
    $pagination_html = '';
    if ($total_pages > 1) {
        $pagination_html = business_generate_pagination($page, $total_pages, $start_num, $end_num, $total_businesses);
    }
    
    wp_send_json_success(array(
        'html'       => $html,
        'pagination' => $pagination_html,
        'has_more'   => $page < $total_pages,
        'total'      => $total_businesses,
        'current_page' => $page,
        'total_pages' => $total_pages,
        'start'      => $start_num,
        'end'        => $end_num
    ));
}

/**
 * Generate Pagination HTML
 */
function business_generate_pagination($current_page, $total_pages, $start, $end, $total) {
    ob_start();
    ?>
    <div class="business-pagination" role="navigation" aria-label="Business listings pagination">
        <div class="pagination-info">
            Showing <?php echo esc_html($start); ?>-<?php echo esc_html($end); ?> of <?php echo esc_html($total); ?> businesses
        </div>
        
        <button class="pagination-btn prev-btn" 
                data-page="<?php echo ($current_page - 1); ?>"
                <?php echo ($current_page <= 1) ? 'disabled' : ''; ?>
                aria-label="Previous page">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M15 18l-6-6 6-6"/>
            </svg>
            Previous
        </button>
        
        <div class="pagination-numbers">
            <?php
            $range = 2;
            
            // First page
            if ($current_page > $range + 1) {
                echo '<button class="pagination-number" data-page="1">1</button>';
                if ($current_page > $range + 2) {
                    echo '<span class="pagination-ellipsis">...</span>';
                }
            }
            
            // Pages around current
            for ($i = max(1, $current_page - $range); $i <= min($total_pages, $current_page + $range); $i++) {
                $active_class = ($i === $current_page) ? ' active' : '';
                echo '<button class="pagination-number' . $active_class . '" data-page="' . $i . '" ' . ($i === $current_page ? 'aria-current="page"' : '') . '>' . $i . '</button>';
            }
            
            // Last page
            if ($current_page < $total_pages - $range) {
                if ($current_page < $total_pages - $range - 1) {
                    echo '<span class="pagination-ellipsis">...</span>';
                }
                echo '<button class="pagination-number" data-page="' . $total_pages . '">' . $total_pages . '</button>';
            }
            ?>
        </div>
        
        <button class="pagination-btn next-btn" 
                data-page="<?php echo ($current_page + 1); ?>"
                <?php echo ($current_page >= $total_pages) ? 'disabled' : ''; ?>
                aria-label="Next page">
            Next
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M9 18l6-6-6-6"/>
            </svg>
        </button>
    </div>
    <?php
    return ob_get_clean();
}

/*--------------------------------------------------------------
11. Custom search filter functions
--------------------------------------------------------------*/
function business_custom_search($search, $wp_query) {
    global $wpdb, $business_search_term;
    
    if (empty($business_search_term) || $wp_query->get('post_type') !== 'business') {
        return $search;
    }
    
    $search_term = '%' . $wpdb->esc_like($business_search_term) . '%';
    
    $search = " AND (
        ({$wpdb->posts}.post_title LIKE %s)
        OR (mt1.meta_value LIKE %s)
        OR (mt2.meta_value LIKE %s)
        OR (mt3.meta_value LIKE %s)
        OR (mt4.meta_value LIKE %s)
        OR (mt5.meta_value LIKE %s)
    )";
    
    $search = $wpdb->prepare($search, $search_term, $search_term, $search_term, $search_term, $search_term, $search_term);
    
    return $search;
}

function business_custom_search_join($join, $wp_query) {
    global $wpdb, $business_search_term;
    
    if (empty($business_search_term) || $wp_query->get('post_type') !== 'business') {
        return $join;
    }
    
    $join .= " LEFT JOIN {$wpdb->postmeta} AS mt1 ON ({$wpdb->posts}.ID = mt1.post_id AND mt1.meta_key = '_business_phone')";
    $join .= " LEFT JOIN {$wpdb->postmeta} AS mt2 ON ({$wpdb->posts}.ID = mt2.post_id AND mt2.meta_key = '_business_address')";
    $join .= " LEFT JOIN {$wpdb->postmeta} AS mt3 ON ({$wpdb->posts}.ID = mt3.post_id AND mt3.meta_key = '_business_website')";
    $join .= " LEFT JOIN {$wpdb->postmeta} AS mt4 ON ({$wpdb->posts}.ID = mt4.post_id AND mt4.meta_key = '_business_email')";
    $join .= " LEFT JOIN {$wpdb->postmeta} AS mt5 ON ({$wpdb->posts}.ID = mt5.post_id AND mt5.meta_key = '_business_description')";
    
    return $join;
}

function business_custom_search_distinct($distinct, $wp_query) {
    global $business_search_term;
    
    if (empty($business_search_term) || $wp_query->get('post_type') !== 'business') {
        return $distinct;
    }
    
    return 'DISTINCT';
}

/*--------------------------------------------------------------
12. Business rewrite rules
--------------------------------------------------------------*/
add_action('init', 'business_rewrite_rules');
function business_rewrite_rules() {
    add_rewrite_rule(
        '^business/([^/]+)/?$',
        'index.php?post_type=business&name=$matches[1]',
        'top'
    );
}

register_activation_hook(__FILE__, 'business_flush_rewrite_rules');
function business_flush_rewrite_rules() {
    business_rewrite_rules();
    flush_rewrite_rules();
}