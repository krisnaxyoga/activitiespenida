<?php get_template_part('template-parts/header'); ?>

<div class="container mx-auto px-4 py-12">
    <h1 class="text-3xl font-bold mb-8">
        <?php echo (is_category()) ? single_cat_title('Category: ') : get_the_archive_title(); ?>
    </h1>
    <?php if (have_posts()) : ?>
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
        <?php while (have_posts()) : the_post(); ?>
        <?php get_template_part('template-parts/loop'); ?>
        <?php endwhile; ?>
    </div>
    <?php the_posts_pagination(); ?>
    <?php else : ?>
    <p>Tidak ada artikel.</p>
    <?php endif; ?>
</div>

<?php get_template_part('template-parts/footer'); ?>