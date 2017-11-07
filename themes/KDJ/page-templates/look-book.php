<?php
/*
  Template Name: Look Book
 */

get_header();
?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

        <div class="look_book_big_img_title">
            <?php
            if (has_post_thumbnail()) {
                ?>
                <div class="look_book_big_img_box">
                    <div>
                        <?php the_post_thumbnail('look-book-thumb'); ?>
                    </div>
                </div>

                <?php
            }
            $page_title = get_field('page_title');
            $page_title_prefix = get_field('page_title_prefix');

            if ($page_title || $page_title_prefix) {
                ?>
                <h2>
                    <?php
                    if ($page_title_prefix) {
                        echo '<i>' . $page_title_prefix . ' </i>';
                    }
                    if ($page_title) {
                        echo '<span>' . $page_title . '</span>';
                    }
                    ?>
                </h2>
                <?php
            }
            $left_block_title = get_field('left_block_title');
            $left_block_text = get_field('left_block_text');
            $left_block_url = get_field('left_block_url');

            if ($left_block_title || $left_block_text) {
                ?>
                <div class="look_book_title_text">
                    <?php if ($left_block_title) { ?>
                        <h3><?php echo $left_block_title; ?></h3>
                        <?php
                    }
                    if ($left_block_text) {
                        echo wpautop($left_block_text);
                    }
                    if ($left_block_url) {
                        ?>
                        <a href="<?php echo $left_block_url; ?>" class="the_button">SHOP THE LOOK</a>
                        <?php
                    }
                    ?>
                </div>
                <?php
            }
            ?>
        </div><!--/look_book_big_img_title-->

        <div class="container repeater">
	        <?php if( have_rows('slider_repeater') ): ?>
		        <?php while( have_rows('slider_repeater') ): the_row(); 
	
				// vars
				$slider = get_sub_field('slider');
				$title = get_sub_field('slider_title');
				$text = get_sub_field('slider_text');
		
				?>
			<div class="look_book_slider_1_box not-mobile">
                <?php
                    if ($slider) {
                ?>	
	        	<div class="slider-holder">
					<ul class="look_book_slider_1">
						<?php foreach ($slider as $slide) { ?>
                        	<li>
                            	<div class="lbs_1_image_box">
                                	<img src="<?php echo $slide["sizes"]["look-book-slider"] ?>" alt="">
                               	</div>
							</li>
                          <?php } ?>
                    </ul>
                </div>
                <?php }
					if ($title || $text) {
                ?>
                    <div class="lbs_1_text_box">
	                    <?php
	                    if ($title) {
	                        echo '<h4>' . $title . '</h4>';
	                    }
	                    if ($text) {
	                        echo wpautop($text);
	                    }
	                    ?>
                    </div>
                    <?php } ?>
                </div>
                <div class="look_book_slider_1_box mobile">
                <?php
                    if ($slider) {
                ?>	
	        	
                <?php }
					if ($title || $text) {
                ?>
                    <div class="lbs_1_text_box">
	                    <?php
	                    if ($title) {
	                        echo '<h4>' . $title . '</h4>';
	                    }
	                    if ($text) {
	                        echo wpautop($text);
	                    }
	                    ?>
                    </div>
                    <div class="slider-holder">
					<ul class="look_book_slider_1">
						<?php foreach ($slider as $slide) { ?>
                        	<li>
                            	<div class="lbs_1_image_box">
                                	<img src="<?php echo $slide["sizes"]["look-book-slider"] ?>" alt="">
                               	</div>
							</li>
                          <?php } ?>
                    </ul>
                </div>
                    <?php } ?>
                    
                </div>
				<?php endwhile; ?>
	       <?php endif; ?> 
	        
	      <div class="centered lookbook">
		      <div class="entry"> 
          <?php the_field('custom_text'); ?>  
		      </div>
	      </div>
        </div><!--/container-->
        <?php
    endwhile;
endif;
?>

<?php get_footer(); ?>