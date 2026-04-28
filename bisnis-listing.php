<?php
/**
 * Template Name: Business Directory
 * Description: Template for displaying Nusa Penida business directory
 */

get_template_part('template-parts/header');
?>

<div class="business-directory-page">
    <div class="container">
        <header class="business-directory-header">
            <h1 class="page-title"><?php the_title(); ?></h1>
            <p class="page-description">Discover the best businesses in Nusa Penida</p>
        </header>

        <!-- Filter Section -->
        <div class="business-filters">
            <div class="filter-grid">
                <div class="filter-group">
                    <label for="business-search">Search Businesses:</label>
                    <input type="text" id="business-search" placeholder="Search by name, address, phone, or website...">
                </div>
                
                <div class="filter-group">
                    <label for="category-filter">Category:</label>
                    <select id="category-filter">
                        <option value="">All Categories</option>
                        <?php
                        $categories = get_terms(array(
                            'taxonomy' => 'business_category',
                            'hide_empty' => true,
                        ));
                        
                        if (!empty($categories) && !is_wp_error($categories)) {
                            foreach ($categories as $category) {
                                echo '<option value="' . esc_attr($category->slug) . '">' . esc_html($category->name) . '</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label for="sort-by">Sort By:</label>
                    <select id="sort-by">
                        <option value="title-asc">Name A-Z</option>
                        <option value="title-desc">Name Z-A</option>
                        <option value="date-desc">Newest First</option>
                        <option value="date-asc">Oldest First</option>
                    </select>
                </div>
            </div>
            
            <div class="filter-results">
                <span id="results-count">Loading...</span>
                <button type="button" id="reset-filters" class="reset-btn">Reset Filters</button>
            </div>
        </div>

        <!-- Loading Indicator -->
        <div id="loading-indicator" class="loading-indicator">
            <div class="spinner"></div>
            <p>Loading businesses...</p>
        </div>

        <!-- Business Listings -->
        <div id="business-listings">
            <!-- Content will be loaded via AJAX -->
        </div>

        <!-- No Results Message -->
        <div id="no-results" class="no-results-message" style="display: none;">
            <h3>Sorry, no businesses found</h3>
            <p>Try different keywords or reset filters to see all businesses.</p>
        </div>

        <!-- Load More Button -->
        <div id="load-more-container" style="display: none;">
            <button id="load-more" class="load-more-btn">Load More</button>
        </div>
    </div>
</div>

<script>
// Pass PHP variables to JavaScript
window.businessDirectory = {
    ajax_url: '<?php echo admin_url('admin-ajax.php'); ?>',
    nonce: '<?php echo wp_create_nonce('business_directory_nonce'); ?>'
};
</script>

<?php get_template_part('template-parts/footer'); ?>