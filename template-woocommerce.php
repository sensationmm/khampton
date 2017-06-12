<?php
/**
 * @package WordPress
 * @subpackage Kamica Hampton 2016
 * Template Name: Woocommerce
*/
get_header();  
?>
	
	<div class="body-outer">
		<div class="body">
		<?php 

			$pageTitle = $pageObj->post_title;

			$pageTitleOverride = get_field('page_title_override', $pageObj->ID);
			$pageTitleTagline = get_field('page_title_tagline', $pageObj->ID);

			if($pageTitleOverride != '')
				$pageTitle = $pageTitleOverride;

			echo output_page_title($pageTitle, $pageTitleTagline);

			if ( have_posts() ) {
				while ( have_posts() ) {
					the_post(); 
					
					the_content();
				}
			}
		?>
		</div>
	</div>

<?php get_footer(); ?> 