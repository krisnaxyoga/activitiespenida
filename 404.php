<?php
/**
 * Template Name: 404 English Tailwind
 * Author: Yoga Krisna
 * @package Nusa Penida Camping
 */
http_response_code(404);
get_template_part('template-parts/header');
?>

<!-- 404 CONTENT -->
<section class="min-h-screen bg-gradient-to-br from-yellow-50 to-emerald-100 flex items-center justify-center px-4 py-12">
    <div class="max-w-4xl mx-auto text-center">

        <!-- 404 Code -->
        <h1 class="np-404-title">404</h1>

        <!-- Message -->
        <h2 class="np-404-subtitle">Page Not Found</h2>
        <p class="np-404-description">
            Sorry, the page you are looking for does not exist or has been moved. Let's get you back on track.
        </p>

        <!-- Search -->
        <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>" class="mt-8 max-w-lg mx-auto">
            <div class="relative">
                <input type="search" name="s" placeholder="Search articles, activities, camping info..." required
                    class="np-404-search-input">
                <button type="submit" class="np-404-search-button">Search</button>
            </div>
        </form>

        <!-- Recent Posts -->
        <?php
        $recent = np_404_recent_posts();
        if ($recent->have_posts()) : ?>
        <div class="np-404-recent-posts">
            <h3 class="np-404-recent-title">Recent Articles</h3>
            <div class="np-404-recent-grid">
                <?php while ($recent->have_posts()) : $recent->the_post(); ?>
                <article class="np-404-article">
                    <a href="<?php the_permalink(); ?>">
                        <?php the_post_thumbnail('medium', ['class' => 'np-404-thumbnail']); ?>
                    </a>
                    <h4 class="np-404-article-title">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h4>
                    <p class="np-404-article-date"><?php echo get_the_date('M j, Y'); ?></p>
                </article>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Back Button -->
        <button onclick="history.back()" class="np-404-back-button">
            <svg class="np-404-back-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Go Back
        </button>

    </div>
</section>


<?php get_template_part('template-parts/footer'); ?>