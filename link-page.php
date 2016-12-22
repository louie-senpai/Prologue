<?php
/*
Template Name: 友链
*/
?>
<?php get_header(); ?>        
            <?php while ( have_posts() ) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemscope itemtype="http://schema.org/BlogPosting" itemprop="blogPost">
                 <div class="entry-content" itemprop="mainEntity">
                 	<?php the_content();?>
                    <?php echo get_link_items(); ?>
                </div><!-- .entry-content -->
            </article>
            <?php comments_template(); ?>
            <?php endwhile; ?>
<?php get_footer(); ?>