<div class="press_item"  id="post-<?php the_ID(); ?>">
    <?php
    if (has_post_thumbnail()) {
        echo '<a href="'.get_permalink().'">';
        the_post_thumbnail('post-thumb');
        echo '</a>';
    }
    ?>
    <h3><?php the_title(); ?></h3>
    <?php
    echo substr_replace(get_the_excerpt() ,' <a href="'.get_permalink().'">See More</a></p>',-4);
    ?>
</div>