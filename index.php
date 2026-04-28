 <!-- Header WordPress -->
 <?php get_template_part('template-parts/header'); ?>

 <!-- Main Content -->
 <div class="container mx-auto px-4 py-12">
     <div class="text-center mb-16">
         <h1 class="text-4xl md:text-5xl font-bold mb-4"><?php echo get_the_title(get_option('page_for_posts')); ?></h1>
         <p class="text-lg text-gray-500 max-w-2xl mx-auto">Discover the latest insights and ideas from our team.</p>
     </div>

     <!-- Blog Grid -->
     <?php if (have_posts()) : ?>
     <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12 blog-grid">
         <?php while (have_posts()) : the_post(); ?>
         <?php get_template_part('template-parts/loop'); ?>
         <?php endwhile; ?>
     </div>

     <!-- Pagination -->
     <div class="pagination">
         <?php
            the_posts_pagination(array(
                'mid_size' => 2,
                'prev_text' => '&larr;',
                'next_text' => '&rarr;',
                'screen_reader_text' => 'Posts navigation'
            ));
            ?>
     </div>
     <?php else : ?>
     <p class="text-center text-gray-500 py-12">Tidak ada artikel.</p>
     <?php endif; ?>
 </div>

 <!-- Footer WordPress -->
 <?php get_template_part('template-parts/footer'); ?>