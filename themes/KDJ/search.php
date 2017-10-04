<?php
get_header();
global $wp_query;
?>
<div class="press_big_img_title">
    <h2><?php _e('Search', 'am'); ?></h2>
</div>
<div class="container">
    <div id="posts">
        <?php if ( have_posts() ) :

            while ( have_posts() ) : the_post(); ?>
				<?php get_template_part('templates/content', 'post-search'); ?>
				
            <?php endwhile; ?> 
        <?php endif; ?>
		<?php wp_reset_query(); ?>
    </div>
  <div id="am-post-load" amq-data="<?php echo base64_encode(serialize($wp_query->query)) ?>">&nbsp;</div>
</div>
<?php get_footer(); ?>