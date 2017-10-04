<div class="post page">

	<div class="title">
		<h2><?php _e( 'Nothing Found', 'am' ); ?></h2>
	</div>
	
	<div class="entry">
		<?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>
	
		<p><?php printf( __( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'am' ), admin_url( 'post-new.php' ) ); ?></p>
	
		<?php elseif ( is_search() ) : ?>
	
		<p><?php _e( 'Sorry, but nothing matched your search terms. Please try again with different keywords.', 'am' ); ?></p>
		<?php get_search_form(); ?>
	
		<?php else : ?>
	
		<p><?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'am' ); ?></p>
		<?php get_search_form(); ?>
	
		<?php endif; ?>
	</div>

</div>