<?php $search_query = get_search_query(); ?>
<form method="get" action="<?php echo esc_url(home_url() . '/'); ?>">
    <input type="text" placeholder="<?php echo esc_attr(__('Search...', 'am')); ?>" value="<?php echo esc_attr($search_query); ?>" name="s" >
    <input type="submit" value="<?php echo esc_attr(__('Go', 'am')); ?>">
</form>