<?php
/**
 * @package WordPress
 * @subpackage Kamica Hampton 2016
 * Template Name: About
*/
get_header();  
?>
	<div class="body-outer">
		<div class="body">
		<?php 
			$pageTitle = $pageObj->post_title;

			$pageTitleOverride = get_field('page_title_override', $pageObj->ID);

			if($pageTitleOverride != '')
				$pageTitle = $pageTitleOverride;

			echo output_page_title($pageTitle);

			if ( have_posts() ) {
				echo '<div class="intro">';
				while ( have_posts() ) {
					the_post(); 
					
					the_content();
				}
				echo '</div>';
			}
		?>
		</div>

	<div class="scroll-to-boxes">&nbsp;</div>
	</div>

	<?php
    	if( have_rows('about_boxes') ):
    		echo '<div class="boxes">';
          	while ( have_rows('about_boxes') ) : the_row();
          		$title = get_sub_field('about_boxes_title');
              	$text = get_sub_field('about_boxes_text');
	            $layout = get_sub_field('about_boxes_layout');
	            $textcolour = get_sub_field('about_boxes_textcolour');
              	$image = get_sub_field('about_boxes_image');

              	echo '<div class="box box-'.$layout.' text-'.$textcolour.'" style="background-image:url('.$image.');">';
	              	// echo '<div class="box-inner">';
			              	echo '<div class="box-text">';
				              	echo '<div class="box-title">'.$title.'</div>';
				              	echo $text;
			              	echo '</div>';
			              	echo '<div class="scroll-to-next"></div>';
	              	// echo '</div>';
              	echo '</div>';
          	endwhile;
          	echo '<div style="clear:both;"></div>';
          	echo '</div>';
      	endif;
	?>

<?php get_footer(); ?> 