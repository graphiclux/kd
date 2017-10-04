<?php
/*
  Template Name: Press
 */

get_header();
$inline_css="";
if (has_post_thumbnail()) { 
    $image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'page-thumb');
    $inline_css=' style="background-image: url(\''.$image_url[0].'\');"';
    }
?>
<div class="press_big_img_title" <?php echo $inline_css; ?>>
    <h2><?php the_title(); ?></h2>
</div>
<div class="container">
    <div class="press_item_box">
        <?php
        query_posts('post_type=press-post&posts_per_page='.get_option('posts_per_page'));
        if (have_posts()) :
            while (have_posts()) : the_post();
                get_template_part('templates/content', 'post-prew');
            endwhile;
        else :
            get_template_part('templates/content', 'none');
        endif;
        wp_reset_query();
        ?>
    </div>
    <div id="am-post-load" amq-data="">&nbsp;</div>
</div><!--/container-->
<?php
get_footer();
?>