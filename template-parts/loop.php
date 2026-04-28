<article class="apple-card">
    <a href="<?php the_permalink(); ?>">
        <?php if (has_post_thumbnail()) : ?>
        <div class="overflow-hidden">
            <img src="<?php the_post_thumbnail_url('large'); ?>" alt="<?php the_title(); ?>"
                class="w-full h-52 object-cover transition-transform duration-700 hover:scale-105">
        </div>
        <?php endif; ?>
        <div class="p-6">
            <div class="post-meta flex items-center mb-3">
                <span><?php echo get_the_date(); ?></span>
                <?php
                $reading_time = ceil(str_word_count(get_the_content()) / 200);
                if ($reading_time > 0) : ?>
                <span class="mx-2">•</span>
                <span><?php echo $reading_time; ?> min read</span>
                <?php endif; ?>
            </div>
            <h2 class="text-xl font-semibold mb-3 leading-tight"><?php the_title(); ?></h2>
            <p class="text-gray-600 mb-4"><?php echo wp_trim_words(get_the_excerpt(), 18); ?></p>
            <span class="read-more">Read more →</span>
        </div>
    </a>
</article>