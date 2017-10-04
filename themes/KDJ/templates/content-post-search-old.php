
<div class="post_item"  id="post-<?php the_ID(); ?>">
    <?php
    if (has_post_thumbnail()) {
        echo '<div class="thumb"><a href="'.get_permalink().'">';
        the_post_thumbnail('thumbnail');
        echo '</a></div>';
    }
    ?>
    <div class="info">
    <h3><?php the_title(); ?></h3>
	<?php echo get_post_type(); ?>

   <?php $product = new WC_Product( get_the_ID() ); if($product) : ?><h4><?php echo $product->get_price_html(); ?></h4><?php endif; ?>
    <?php
    echo get_the_excerpt() ,' <a href="'.get_permalink().'">See More</a>';
    ?>
		

    </div>
</div>






