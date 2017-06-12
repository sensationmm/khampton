<?php
/**
 * Fit tab
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post;

$ID = $post->ID;

$content = get_field('product_info_fit', $ID);

if($content != '')
	echo $content;
else 
	echo 'No info added';
?>