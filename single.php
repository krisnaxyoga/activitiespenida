<?php get_template_part('template-parts/header'); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

<?php
$post_id = get_the_ID();
$reading_time = calculate_reading_time($post_id);
$categories = get_the_category();
$tags = get_the_tags();
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('bg-white'); ?> itemscope itemtype="https://schema.org/Article">
    <!-- Schema.org JSON-LD for better SEO -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Article",
        "headline": "<?php echo esc_js(get_the_title()); ?>",
        "datePublished": "<?php echo get_the_date('c'); ?>",
        "dateModified": "<?php echo get_the_modified_date('c'); ?>",
        "wordCount": "<?php echo str_word_count(strip_tags(get_the_content())); ?>",
        <?php if (has_post_thumbnail()) : ?>
        "image": "<?php echo esc_url(get_the_post_thumbnail_url()); ?>",
        <?php endif; ?>
        "author": {
            "@type": "Person",
            "name": "<?php echo esc_js(get_the_author_meta('display_name')); ?>"
        },
        "publisher": {
            "@type": "Organization",
            "name": "<?php echo esc_js(get_bloginfo('name')); ?>",
            "logo": {
                "@type": "ImageObject",
                "url": "<?php echo esc_url(get_site_icon_url()); ?>"
            }
        },
        "mainEntityOfPage": {
            "@type": "WebPage",
            "@id": "<?php echo esc_url(get_permalink()); ?>"
        }
    }
    </script>

    <!-- Breadcrumb Navigation -->
    <nav class="bg-gray-50 border-b" aria-label="Breadcrumb">
        <div class="max-w-7xl mx-auto px-4 py-3">
            <ol class="flex items-center space-x-2 text-sm text-gray-600" itemscope itemtype="https://schema.org/BreadcrumbList">
                <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <a href="<?php echo esc_url(home_url()); ?>" class="hover:text-green-600 transition-colors" itemprop="item">
                        <span itemprop="name">Home</span>
                    </a>
                    <meta itemprop="position" content="1">
                </li>
                <li class="flex items-center">
                    <svg class="w-4 h-4 mx-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                </li>
                <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <a href="<?php echo esc_url(home_url('/blog')); ?>" class="hover:text-green-600 transition-colors" itemprop="item">
                        <span itemprop="name">Blog</span>
                    </a>
                    <meta itemprop="position" content="2">
                </li>
                <?php if (!empty($categories)) : ?>
                <li class="flex items-center">
                    <svg class="w-4 h-4 mx-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                </li>
                <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <a href="<?php echo esc_url(get_category_link($categories[0]->term_id)); ?>" class="hover:text-green-600 transition-colors" itemprop="item">
                        <span itemprop="name"><?php echo esc_html($categories[0]->name); ?></span>
                    </a>
                    <meta itemprop="position" content="3">
                </li>
                <?php endif; ?>
                <li class="flex items-center">
                    <svg class="w-4 h-4 mx-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                </li>
                <li class="text-gray-900 font-medium" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <span itemprop="name"><?php echo wp_trim_words(get_the_title(), 8, '...'); ?></span>
                    <meta itemprop="position" content="4">
                </li>
            </ol>
        </div>
    </nav>

    <!-- Article Header -->
    <div class="max-w-7xl mx-auto px-4 py-8 lg:py-12">
        <div class="max-w-4xl mx-auto">
            <!-- Categories -->
            <?php if (!empty($categories)) : ?>
            <div class="mb-4">
                <?php foreach ($categories as $category) : ?>
                <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>"
                   class="inline-block bg-green-100 text-green-800 text-xs font-medium px-3 py-1 rounded-full hover:bg-green-200 transition-colors mr-2">
                    <?php echo esc_html($category->name); ?>
                </a>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <!-- Title -->
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 leading-tight mb-6">
                <?php the_title(); ?>
            </h1>

            <!-- Excerpt/Description -->
            <?php if (has_excerpt()) : ?>
            <div class="text-xl text-gray-600 leading-relaxed mb-8">
                <?php the_excerpt(); ?>
            </div>
            <?php endif; ?>

            <!-- Meta Information -->
            <div class="flex flex-wrap items-center gap-6 text-sm text-gray-600 mb-8">
                <div class="flex items-center gap-3">
                    <?php echo get_avatar(get_the_author_meta('ID'), 40, '', '', array('class' => 'w-10 h-10 rounded-full object-cover')); ?>
                    <div>
                        <div class="font-medium text-gray-900">
                            <?php echo esc_html(get_the_author_meta('display_name')); ?>
                        </div>
                        <div class="text-xs text-gray-500">Author</div>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <time datetime="<?php echo get_the_date('c'); ?>">
                        <?php echo get_the_date('F j, Y'); ?>
                    </time>
                </div>
                <?php if (get_the_date() !== get_the_modified_date()) : ?>
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    <time datetime="<?php echo get_the_modified_date('c'); ?>">
                        Updated: <?php echo get_the_modified_date('M j, Y'); ?>
                    </time>
                </div>
                <?php endif; ?>
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span><?php echo $reading_time; ?> min read</span>
                </div>
            </div>

            <!-- Featured Image -->
            <?php
            $custom_image_url = get_post_meta(get_the_ID(), '_custom_link_url', true);
            if ($custom_image_url || has_post_thumbnail()) :
                $image_url = $custom_image_url ? $custom_image_url : get_the_post_thumbnail_url();
                $image_alt = $custom_image_url ? get_the_title() : get_post_meta(get_post_thumbnail_id(), '_wp_attachment_image_alt', true);
            ?>
            <figure class="relative mb-8 rounded-xl overflow-hidden shadow-lg">
                <img class="w-full h-64 md:h-96 object-cover"
                     src="<?php echo esc_url($image_url); ?>"
                     alt="<?php echo esc_attr($image_alt ?: get_the_title()); ?>" loading="lazy">
            </figure>
            <?php endif; ?>
        
    

            <!-- Article Content with TOC -->
            <div class="pb-12">
                <!-- Main Content -->
                <div class="md:col-span-3">
                    <!-- Mobile TOC Toggle -->

                    <!-- Main Article Content -->
                    <div class="prose prose-lg max-w-none
                            prose-headings:text-gray-900 prose-headings:font-bold prose-headings:tracking-tight
                            prose-h1:text-4xl prose-h1:mt-8 prose-h1:mb-6
                            prose-h2:text-3xl prose-h2:mt-8 prose-h2:mb-4 prose-h2:border-b prose-h2:border-gray-200 prose-h2:pb-2
                            prose-h3:text-2xl prose-h3:mt-6 prose-h3:mb-3
                            prose-h4:text-xl prose-h4:mt-5 prose-h4:mb-2
                            prose-h5:text-lg prose-h5:mt-4 prose-h5:mb-2
                            prose-h6:text-base prose-h6:mt-3 prose-h6:mb-2
                            prose-p:text-gray-700 prose-p:leading-relaxed prose-p:mb-4
                            prose-a:text-green-600 prose-a:no-underline hover:prose-a:text-green-700 hover:prose-a:underline prose-a:transition-colors
                            prose-strong:text-gray-900 prose-strong:font-semibold
                            prose-em:text-gray-600 prose-em:italic
                            prose-ul:my-6 prose-ul:pl-6
                            prose-ol:my-6 prose-ol:pl-6
                            prose-li:text-gray-700 prose-li:my-2 prose-li:leading-relaxed
                            prose-blockquote:border-l-4 prose-blockquote:border-green-500 prose-blockquote:bg-green-50 prose-blockquote:px-6 prose-blockquote:py-4 prose-blockquote:italic prose-blockquote:my-6 prose-blockquote:rounded-r-lg
                            prose-img:rounded-lg prose-img:shadow-md prose-img:my-8
                            prose-figure:my-8
                            prose-figcaption:text-center prose-figcaption:text-sm prose-figcaption:text-gray-600 prose-figcaption:mt-2
                            prose-code:bg-gray-100 prose-code:px-1 prose-code:py-0.5 prose-code:rounded prose-code:text-sm prose-code:font-mono prose-code:text-gray-800
                            prose-pre:bg-gray-900 prose-pre:text-gray-100 prose-pre:rounded-lg prose-pre:p-4 prose-pre:overflow-x-auto prose-pre:my-6
                            prose-table:w-full prose-table:border-collapse prose-table:my-6
                            prose-thead:bg-gray-50
                            prose-th:border prose-th:border-gray-300 prose-th:px-4 prose-th:py-3 prose-th:text-left prose-th:font-semibold prose-th:text-gray-900
                            prose-td:border prose-td:border-gray-300 prose-td:px-4 prose-td:py-3 prose-td:text-gray-700
                            prose-tbody:bg-white
                            prose-tr:border-b prose-tr:border-gray-200
                            prose-hr:border-gray-300 prose-hr:my-8"
                        id="article-content">
                        <?php
                        // Proper content rendering for Gutenberg blocks
                        $content = get_the_content();
                        $content = apply_filters('the_content', $content);
                        $content = str_replace(']]>', ']]&gt;', $content);
                        echo $content;
                        ?>
                    </div>

                    <!-- Tags -->
                    <?php if (!empty($tags)) : ?>
                    <div class="mt-12 pt-8 border-t border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Tags</h3>
                        <div class="flex flex-wrap gap-2">
                            <?php foreach ($tags as $tag) : ?>
                            <a href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>"
                            class="inline-block bg-gray-100 text-gray-700 text-sm px-3 py-1 rounded-full hover:bg-gray-200 transition-colors">
                                #<?php echo esc_html($tag->name); ?>
                            </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Author Bio -->
                    <div class="mt-12 pt-8 border-t border-gray-200">
                        <div class="flex items-start gap-4">
                            <?php echo get_avatar(get_the_author_meta('ID'), 80, '', '', array('class' => 'w-20 h-20 rounded-full object-cover')); ?>
                            <div class="flex-1">
                                <h3 class="text-xl py-3 font-semibold text-gray-900 mb-2">
                                    About <?php echo esc_html(get_the_author_meta('display_name')); ?>
                                </h3>
                                <?php if (get_the_author_meta('description')) : ?>
                                <p class="text-gray-600 leading-relaxed mb-3">
                                    <?php echo esc_html(get_the_author_meta('description')); ?>
                                </p>
                                <?php endif; ?>
                                <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>"
                                class="text-green-600 hover:text-green-700 font-medium text-sm">
                                    View all posts by <?php echo esc_html(get_the_author_meta('display_name')); ?> →
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</article>

<!-- Related Articles Section -->
<section class="bg-gray-50 py-16">
    <div class="max-w-7xl mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <h2 class="text-3xl font-bold text-gray-900 mb-8">Related Articles</h2>

            <?php
            if ($categories) {
                $category_ids = wp_list_pluck($categories, 'term_id');
                $related_query = new WP_Query(array(
                    'category__in' => $category_ids,
                    'post__not_in' => array(get_the_ID()),
                    'posts_per_page' => 3,
                    'ignore_sticky_posts' => 1,
                    'meta_query' => array(
                        array(
                            'key' => '_thumbnail_id',
                            'compare' => 'EXISTS'
                        )
                    )
                ));

                if ($related_query->have_posts()) : ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <?php while ($related_query->have_posts()) {
                            $related_query->the_post();
                            $custom_image_url = get_post_meta(get_the_ID(), '_custom_link_url', true);
                        ?>
                            <article class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                                <a href="<?php the_permalink(); ?>" class="block">
                                    <?php if ($custom_image_url || has_post_thumbnail()) : ?>
                                        <div class="aspect-w-16 aspect-h-9 bg-gray-200">
                                            <img class="w-full h-48 object-cover"
                                                 src="<?php echo $custom_image_url ? esc_url($custom_image_url) : get_the_post_thumbnail_url(); ?>"
                                                 alt="<?php the_title(); ?>" loading="lazy">
                                        </div>
                                    <?php endif; ?>

                                    <div class="p-6">
                                        <h3 class="text-lg font-semibold text-gray-900 mb-3 line-clamp-2 hover:text-green-600 transition-colors">
                                            <?php the_title(); ?>
                                        </h3>

                                        <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                                            <?php echo wp_trim_words(get_the_excerpt(), 20, '...'); ?>
                                        </p>

                                        <div class="flex items-center justify-between text-xs text-gray-500">
                                            <time datetime="<?php echo get_the_date('c'); ?>">
                                                <?php echo get_the_date('M j, Y'); ?>
                                            </time>
                                            <span><?php echo calculate_reading_time(get_the_ID()); ?> min read</span>
                                        </div>
                                    </div>
                                </a>
                            </article>
                        <?php } ?>
                    </div>
                <?php
                    wp_reset_postdata();
                else : ?>
                    <div class="text-center py-12">
                        <p class="text-gray-600">No related articles found.</p>
                    </div>
                <?php endif;
            } ?>
        </div>
    </div>
</section>

<?php endwhile; endif; ?>

<?php get_template_part('template-parts/footer'); ?>