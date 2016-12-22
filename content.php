<?php get_header(); ?>
            <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

            <article id="post-<?php the_ID(); ?>" class="item" itemscope itemtype="http://schema.org/BlogPosting" itemprop="blogPost">
                <header class="entry-header">
                    <a href="<?php the_permalink();?>" style="background-image: url(<?php echo get_post_thumb($post->ID); ?>);"></a>
                    <h3 class="entry-title" itemprop="name"><?php the_title(); ?></h3>
                </header>
                <p class="entry-summary" itemprop="description"><?php echo mb_strimwidth(strip_shortcodes(strip_tags(apply_filters('the_content', $post->post_content))), 0, 145,"...");?></p>
                <ul class="actions">
                    <li><a href="<?php the_permalink();?>" class="button">More</a></li>
                </ul>
            </article>
            <?php endwhile; endif;
            ?>
<?php get_footer(); ?>