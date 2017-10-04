<?php
global $wp_query;
get_header();
?>
<div class="press_big_img_title">
    <h2><?php _e('Search', 'am'); ?></h2>
</div>
<div class="container">
    <div id="posts">
        <?php
        if (have_posts()) :
            while (have_posts()) : the_post();
                 get_template_part('templates/content', 'post-search');

            endwhile;
        else :
            get_template_part('templates/content', 'none');
        endif;
        wp_reset_query();
        ?>
    </div>
    <div id="am-post-load" amq-data="<?php echo base64_encode(serialize($wp_query->query)) ?>">&nbsp;</div>
</div>
<?php
get_footer();
?>