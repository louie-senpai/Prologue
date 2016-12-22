<?php get_header(); ?>        
            <?php while ( have_posts() ) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemscope itemtype="http://schema.org/BlogPosting" itemprop="blogPost">
                 <div class="entry-content" itemprop="mainEntity">
                    <?php the_content();?>
                </div><!-- .entry-content -->
                <div class="tags"><?php echo the_tags('', ' ', ' '); ?></div>
            </article>
            <div class="adjacent-post">
            	<div class="previous"><?php previous_post_link('%link','« %title') ?></div>
            	<div class="next"><?php next_post_link('%link','%title »') ?></div>
            </div>
            <?php comments_template(); ?>
            <?php endwhile; ?>
<?php get_footer(); ?>