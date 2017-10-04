<?php
/*
  Template Name: Contact
 */

get_header();
?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <?php if (has_post_thumbnail()) { ?>
            <div class="big_img_title">
                <?php the_post_thumbnail('page-thumb'); ?>
            </div>
        <?php } ?>
        <div class="container">
            <div class="contacts_info_box">
                <h2><?php the_title(); ?></h2>
                <?php
                $media_inquiries = get_field('media_inquiries');
                $wholesale_inquiries = get_field('wholesale_inquiries');
                $returns_andor_exchanges = get_field('returns_and/or_exchanges');

                if ($media_inquiries) {
                    ?>

                    <p>Media Inquiries: <br><a href="mailto:<?php echo antispambot($media_inquiries); ?>"><?php echo antispambot($media_inquiries); ?></a></p>
                    <?php
                }
                if ($wholesale_inquiries) {
                    ?>
                    <p>Wholesale Inquiries: <br><a href="<?php echo antispambot($wholesale_inquiries); ?>"><?php echo antispambot($wholesale_inquiries); ?></a></p>
                    <?php
                }
                if ($returns_andor_exchanges) {
                    ?>
                    <p>Returns and/or Exchanges: <br><?php echo $returns_andor_exchanges; ?></p>
                    <?php
                }
                ?>
            </div>

            <div class="contacts_form_box">
                <?php the_content(__('Read more', 'am')); ?>
                <div class="contacts_form">
                    <?php
                    the_field('form');
                    ?>
                </div>
               
            </div>

        </div><!--/container-->
        <?php
    endwhile;
endif;
?>

<?php get_footer(); ?>