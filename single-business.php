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
                <?php else : ?>
                    <div class="business-missing-photo-card" style="background:#fef9c3;border:1px solid #fde047;border-left:6px solid #eab308;color:#713f12;border-radius:10px;padding:18px 20px;margin:0 0 20px 0;font-size:14px;line-height:1.6;box-shadow:0 1px 2px rgba(0,0,0,0.04);" role="note" aria-label="Listing incomplete — submit business details">
                        <div style="display:flex;align-items:flex-start;gap:12px;">
                            <span aria-hidden="true" style="font-size:22px;line-height:1;">📸</span>
                            <div>
                                <strong style="display:block;font-size:15px;margin-bottom:6px;color:#854d0e;">Are you the owner of <?php echo esc_html(get_the_title()); ?>?</strong>
                                <p style="margin:0 0 8px 0;">
                                    We couldn't find a photo for this business yet. Help travellers discover you — send us your business details and we'll publish them <strong>completely free of charge</strong>:
                                </p>
                                <ul style="margin:0 0 10px 18px;padding:0;list-style:disc;">
                                    <li>WhatsApp / phone number</li>
                                    <li>Email address</li>
                                    <li>Business photos (logo, venue, products)</li>
                                    <li>Short business description</li>
                                </ul>
                                <p style="margin:0 0 12px 0;">
                                    Send everything to
                                    <a href="https://wa.me/6285121102295?text=<?php echo rawurlencode('Hallo admin, saya mau menambah data penida finder di activitiespenidatour'); ?>" target="_blank" rel="noopener" style="color:#854d0e;font-weight:600;text-decoration:underline;">our WhatsApp</a>
                                    or email
                                    <a href="mailto:hello@snorkelingpenida.com" style="color:#854d0e;font-weight:600;text-decoration:underline;">hello@snorkelingpenida.com</a>
                                    and our admin will input it for you — no fees, no catch.
                                </p>
                                <a href="https://wa.me/6285121102295?text=<?php echo rawurlencode('Hallo admin, saya mau menambah data penida finder di activitiespenidatour'); ?>"
                                   target="_blank" rel="noopener"
                                   style="display:inline-flex;align-items:center;gap:8px;background:#25D366;color:#fff;padding:10px 18px;border-radius:8px;font-weight:600;text-decoration:none;font-size:14px;box-shadow:0 1px 2px rgba(0,0,0,0.08);"
                                   aria-label="Chat admin via WhatsApp">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                    </svg>
                                    Chat Admin on WhatsApp
                                </a>
                            </div>
                        </div>
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