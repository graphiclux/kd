<?php get_header();  ?>
<div class="inner_page_box">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            <?php get_template_part('templates/content', 'page'); ?>
        <?php endwhile;
    endif; ?>
</div><!--/inner_page_box-->
<?php get_footer(); ?>