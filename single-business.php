<?php
/**
 * Template Name: Single Business
 * Description: Template for displaying single business details
 */

get_template_part('template-parts/header');

// Get current business post
$business_id = get_the_ID();
$business = get_post($business_id);

if (!$business || $business->post_type !== 'business') {
    wp_redirect(home_url('/business-directory/'));
    exit;
}

// Get business meta data
$website = get_post_meta($business_id, '_business_website', true);
$phone = get_post_meta($business_id, '_business_phone', true);
$email = get_post_meta($business_id, '_business_email', true);
$address = get_post_meta($business_id, '_business_address', true);
$description = get_post_meta($business_id, '_business_description', true);
$google_maps = get_post_meta($business_id, '_business_google_maps', true);
$instagram = get_post_meta($business_id, '_business_instagram', true);
$facebook = get_post_meta($business_id, '_business_facebook', true);
$tiktok = get_post_meta($business_id, '_business_tiktok', true);

// Get business categories
$categories = get_the_terms($business_id, 'business_category');
$category_name = $categories && !is_wp_error($categories) ? $categories[0]->name : '';

// Get featured image
$featured_image = get_the_post_thumbnail_url($business_id, 'large');
?>

<div class="single-business-page">
    <div class="container">
        <!-- Breadcrumb -->
        <nav class="business-breadcrumb">
            <a href="<?php echo home_url('/'); ?>">Home</a>
            <span class="separator">/</span>
            <a href="<?php echo home_url('/business-directory/'); ?>">Business Directory</a>
            <span class="separator">/</span>
            <span class="current"><?php echo get_the_title(); ?></span>
        </nav>

        <!-- Business Header -->
        <header class="business-single-header">
            <div class="business-header-content">
                <?php if ($featured_image) : ?>
                    <div class="business-featured-image">
                        <img src="<?php echo esc_url($featured_image); ?>" alt="<?php echo esc_attr(get_the_title()); ?>">
                    </div>
                <?php endif; ?>
                
                <div class="business-header-info">
                    <div class="business-title-section">
                        <h1 class="business-single-title"><?php echo get_the_title(); ?></h1>
                        <?php if ($category_name) : ?>
                            <span class="business-category-badge"><?php echo esc_html($category_name); ?></span>
                        <?php endif; ?>
                    </div>
                    
                    <?php if ($description) : ?>
                        <div class="business-excerpt">
                            <?php echo esc_html(wp_trim_words($description, 30)); ?>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Quick Actions -->
                    <div class="business-quick-actions">
                        <?php if ($phone) : ?>
                            <a href="https://wa.me/ <?php echo esc_attr(preg_replace('/[^\d+]/', '', $phone)); ?>" 
                               class="action-btn whatsapp" target="_blank" rel="noopener">
                                <span class="btn-icon"><i class="icon-whatsapp"></i></span>
                                WhatsApp
                            </a>
                        <?php endif; ?>
                        
                        <?php if ($google_maps) : ?>
                            <a href="<?php echo esc_url($google_maps); ?>" 
                               class="action-btn map" target="_blank" rel="noopener">
                                <span class="btn-icon"><i class="icon-map"></i></span>
                                View on Maps
                            </a>
                        <?php endif; ?>
                        
                        <?php if ($website) : ?>
                            <a href="<?php echo esc_url($website); ?>" 
                               class="action-btn website" target="_blank" rel="noopener">
                                <span class="btn-icon"><i class="icon-website"></i></span>
                                Visit Website
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </header>

        <!-- Business Main Content -->
        <div class="business-single-content">
            <div class="business-main-details">
                <!-- Business Description -->
                <?php if ($description) : ?>
                <section class="business-section">
                    <h2 class="section-title">About</h2>
                    <div class="business-description">
                        <p><?php echo esc_html($description); ?></p>
                    </div>
                </section>
                <?php endif; ?>

                <!-- Contact Information -->
                <section class="business-section">
                    <h2 class="section-title">Contact Information</h2>
                    <div class="contact-details-grid">
                        <?php if ($phone) : ?>
                        <div class="contact-item">
                            <div class="contact-icon"><i class="icon-phone"></i></div>
                            <div class="contact-info">
                                <span class="contact-label">Phone</span>
                                <a href="tel:<?php echo esc_attr(preg_replace('/[^\d+]/', '', $phone)); ?>" class="contact-value">
                                    <?php echo esc_html($phone); ?>
                                </a>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($email) : ?>
                        <div class="contact-item">
                            <div class="contact-icon"><i class="icon-email"></i></div>
                            <div class="contact-info">
                                <span class="contact-label">Email</span>
                                <a href="mailto:<?php echo esc_attr($email); ?>" class="contact-value">
                                    <?php echo esc_html($email); ?>
                                </a>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($address) : ?>
                        <div class="contact-item">
                            <div class="contact-icon"><i class="icon-location"></i></div>
                            <div class="contact-info">
                                <span class="contact-label">Address</span>
                                <span class="contact-value"><?php echo esc_html($address); ?></span>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($website) : ?>
                        <div class="contact-item">
                            <div class="contact-icon"><i class="icon-website"></i></div>
                            <div class="contact-info">
                                <span class="contact-label">Website</span>
                                <a href="<?php echo esc_url($website); ?>" class="contact-value" target="_blank" rel="noopener">
                                    <?php echo esc_html(parse_url($website, PHP_URL_HOST)); ?>
                                </a>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </section>

                <!-- Social Media -->
                <?php if ($instagram || $facebook || $tiktok) : ?>
                <section class="business-section">
                    <h2 class="section-title">Follow Us</h2>
                    <div class="social-media-links">
                        <?php if ($instagram) : ?>
                        <a href="<?php echo esc_url($instagram); ?>" 
                           class="social-media-link instagram" target="_blank" rel="noopener">
                            <span class="social-icon"><i class="icon-instagram"></i></span>
                            <span class="social-text">Instagram</span>
                        </a>
                        <?php endif; ?>
                        
                        <?php if ($facebook) : ?>
                        <a href="<?php echo esc_url($facebook); ?>" 
                           class="social-media-link facebook" target="_blank" rel="noopener">
                            <span class="social-icon"><i class="icon-facebook"></i></span>
                            <span class="social-text">Facebook</span>
                        </a>
                        <?php endif; ?>
                        
                        <?php if ($tiktok) : ?>
                        <a href="<?php echo esc_url($tiktok); ?>" 
                           class="social-media-link tiktok" target="_blank" rel="noopener">
                            <span class="social-icon"><i class="icon-tiktok"></i></span>
                            <span class="social-text">TikTok</span>
                        </a>
                        <?php endif; ?>
                    </div>
                </section>
                <?php endif; ?>
            </div>

            <!-- Sidebar -->
            <aside class="business-sidebar">
                <!-- Quick Contact Card -->
                <div class="sidebar-card">
                    <h3 class="sidebar-title">Get in Touch</h3>
                    <div class="sidebar-actions">
                        <?php if ($phone) : ?>
                        <a href="tel:<?php echo esc_attr(preg_replace('/[^\d+]/', '', $phone)); ?>" 
                           class="sidebar-btn phone">
                            <span class="btn-icon"><i class="icon-phone"></i></span>
                            Call Now
                        </a>
                        <?php endif; ?>
                        
                        <?php if ($phone) : ?>
                        <a href="https://wa.me/ <?php echo esc_attr(preg_replace('/[^\d+]/', '', $phone)); ?>" 
                           class="sidebar-btn whatsapp" target="_blank" rel="noopener">
                            <span class="btn-icon"><i class="icon-whatsapp"></i></span>
                            WhatsApp
                        </a>
                        <?php endif; ?>
                        
                        <?php if ($email) : ?>
                        <a href="mailto:<?php echo esc_attr($email); ?>" 
                           class="sidebar-btn email">
                            <span class="btn-icon"><i class="icon-email"></i></span>
                            Send Email
                        </a>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Location Card -->
                <?php if ($google_maps) : ?>
                <div class="sidebar-card">
                    <h3 class="sidebar-title">Location</h3>
                    <a href="<?php echo esc_url($google_maps); ?>" 
                       class="sidebar-btn map" target="_blank" rel="noopener">
                        <span class="btn-icon"><i class="icon-map"></i></span>
                        Open in Google Maps
                    </a>
                </div>
                <?php endif; ?>

                <!-- Share Business -->
                <div class="sidebar-card">
                    <h3 class="sidebar-title">Share Business</h3>
                    <div class="share-buttons">
                        <button class="share-btn facebook-share" data-url="<?php the_permalink(); ?>" data-title="<?php the_title(); ?>">
                            <span class="share-icon"><i class="icon-facebook"></i></span>
                            Facebook
                        </button>
                        <button class="share-btn twitter-share" data-url="<?php the_permalink(); ?>" data-title="<?php the_title(); ?>">
                            <span class="share-icon"><i class="icon-twitter"></i></span>
                            Twitter
                        </button>
                        <button class="share-btn copy-link" data-url="<?php the_permalink(); ?>">
                            <span class="share-icon"><i class="icon-link"></i></span>
                            Copy Link
                        </button>
                    </div>
                </div>
            </aside>
        </div>

        <!-- Related Businesses -->
        <?php
        $related_args = array(
            'post_type' => 'business',
            'posts_per_page' => 4,
            'post_status' => 'publish',
            'post__not_in' => array($business_id),
            'tax_query' => $category_name ? array(
                array(
                    'taxonomy' => 'business_category',
                    'field' => 'slug',
                    'terms' => $category_name
                )
            ) : array()
        );
        
        $related_businesses = new WP_Query($related_args);
        
        if ($related_businesses->have_posts()) :
        ?>
        <section class="related-businesses">
            <h2 class="section-title">Related Businesses</h2>
            <div class="related-businesses-grid">
                <?php while ($related_businesses->have_posts()) : $related_businesses->the_post(); 
                    $related_id = get_the_ID();
                    $related_website = get_post_meta($related_id, '_business_website', true);
                    $related_phone = get_post_meta($related_id, '_business_phone', true);
                    $related_description = get_post_meta($related_id, '_business_description', true);
                    $related_categories = get_the_terms($related_id, 'business_category');
                    $related_category = $related_categories && !is_wp_error($related_categories) ? $related_categories[0]->name : '';
                ?>
                <div class="related-business-card">
                    <?php if (has_post_thumbnail()) : ?>
                    <div class="related-business-image">
                        <a href="<?php the_permalink(); ?>">
                            <?php the_post_thumbnail('medium'); ?>
                        </a>
                    </div>
                    <?php endif; ?>
                    
                    <div class="related-business-content">
                        <h3 class="related-business-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h3>
                        
                        <?php if ($related_category) : ?>
                        <span class="related-business-category"><?php echo esc_html($related_category); ?></span>
                        <?php endif; ?>
                        
                        <?php if ($related_description) : ?>
                        <p class="related-business-description">
                            <?php echo esc_html(wp_trim_words($related_description, 15)); ?>
                        </p>
                        <?php endif; ?>
                        
                        <?php if ($related_phone) : ?>
                        <div class="related-business-phone">
                            <span class="phone-icon"><i class="icon-phone"></i></span>
                            <?php echo esc_html($related_phone); ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
        </section>
        <?php endif; ?>
    </div>
</div>

<script>
// Share functionality
document.addEventListener('DOMContentLoaded', function() {
    // Facebook Share
    const facebookButtons = document.querySelectorAll('.facebook-share');
    facebookButtons.forEach(button => {
        button.addEventListener('click', function() {
            const url = this.dataset.url;
            const title = this.dataset.title;
            window.open(`https://www.facebook.com/sharer/sharer.php?u= ${encodeURIComponent(url)}&t=${encodeURIComponent(title)}`, 'facebook-share', 'width=600,height=400');
        });
    });

    // Twitter Share
    const twitterButtons = document.querySelectorAll('.twitter-share');
    twitterButtons.forEach(button => {
        button.addEventListener('click', function() {
            const url = this.dataset.url;
            const title = this.dataset.title;
            window.open(`https://twitter.com/intent/tweet?text= ${encodeURIComponent(title)}&url=${encodeURIComponent(url)}`, 'twitter-share', 'width=600,height=400');
        });
    });

    // Copy Link
    const copyButtons = document.querySelectorAll('.copy-link');
    copyButtons.forEach(button => {
        button.addEventListener('click', function() {
            const url = this.dataset.url;
            navigator.clipboard.writeText(url).then(() => {
                // Show success message
                const originalText = this.innerHTML;
                this.innerHTML = '<span class="share-icon"><i class="icon-check"></i></span> Copied!';
                setTimeout(() => {
                    this.innerHTML = originalText;
                }, 2000);
            }).catch(err => {
                console.error('Failed to copy: ', err);
            });
        });
    });
});
</script>

<?php get_template_part('template-parts/footer'); ?>