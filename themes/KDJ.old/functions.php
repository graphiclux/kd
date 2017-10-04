<?php
global $am_option;

if( isset( $_GET['showall'] ) ){ 
    add_filter( 'loop_shop_per_page', create_function( '$cols', 'return -1;' ) ); 
} else {
    add_filter( 'loop_shop_per_page', create_function( '$cols', 'return '.get_option('posts_per_page').';' ) );
}

$am_option['shortname'] = "am";
$am_option['textdomain'] = "am";
$am_option['version'] = "1.0.1";

$am_option['url']['includes_path'] = 'includes';
$am_option['url']['includes_url'] = get_template_directory_uri().'/'.$am_option['url']['includes_path'];
$am_option['url']['extensions_path'] = $am_option['url']['includes_path'].'/extensions';
$am_option['url']['extensions_url'] = get_template_directory_uri().'/'.$am_option['url']['extensions_path'];

// Functions
require_once($am_option['url']['includes_path'].'/fn-core.php');
require_once($am_option['url']['includes_path'].'/fn-custom.php');

// Extensions
require_once($am_option['url']['extensions_path'].'/breadcrumb-trail.php');
require_once($am_option['url']['extensions_path'].'/resize.php');

/* Theme Init */
require_once ($am_option['url']['includes_path'].'/theme-widgets.php');
require_once($am_option['url']['includes_path'].'/theme-init.php');

/*--------------------------------------------------------------------------------------------------
 Don't show Categories -  Add a comma after category ie; 'gifts-50-and-under', 'rings'
--------------------------------------------------------------------------------------------------*/
add_filter( 'get_terms', 'get_subcategory_terms', 10, 3 );
function get_subcategory_terms( $terms, $taxonomies, $args ) {
 $new_terms = array();
 // if a product category and on the shop page
 if ( in_array( 'product_cat', $taxonomies ) && ! is_admin() ) {
 foreach ( $terms as $key => $term ) {
 if ( ! in_array( $term->slug, array( 'gifts-50-and-under' ) ) ) {
 $new_terms[] = $term;
 }
 }
 $terms = $new_terms;
 }
 return $terms;
}

?>