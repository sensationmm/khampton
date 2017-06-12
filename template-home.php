<?php
/**
 * @package WordPress
 * @subpackage Kamica Hampton 2016
 * Template Name: Home
*/
get_header();  

$video_src = get_field('homepage_video', $pageObj->ID);
?>
	<div class="intro">
		<div class="intro-logo"><img src="assets/images/logo-home.png" /></div>


		<!--video playsinline="" autoplay="" muted="" poster="http://creators.radeon.com/wp-content/uploads/2016/09/home.jpg"-->

		<video playsinline="" autoplay="" muted="" loop="" preload="yes">
			<source src="<?php echo $video_src; ?>" type="video/mp4">
  			Your browser does not support the video tag.
		</video>
		<div class="scroll-content"></div>
		<div class="nav-icon"></div>
	</div>
	
		<?php 
			$pageTitle = $pageObj->post_title;

			$pageTitleOverride = get_field('page_title_override', $pageObj->ID);

			if($pageTitleOverride != '')
				$pageTitle = $pageTitleOverride;

			echo '<div class="header-outer">'.output_page_title($pageTitle).'</div>';
			?>

	<div class="body-outer">
		<div class="body">
			<?php

			if ( have_posts() ) {
				echo '<div class="home-content fade-in">';
				while ( have_posts() ) {
					the_post(); 
					
					the_content();
				}
				echo '</div>';
			}

			$numBoxes = count(get_field('homepage_boxes'), $pageObj->ID);
			$numOutput = 0;
	    	if( have_rows('homepage_boxes') ):
	    		echo '<div class="boxes">';
		    		echo '<div class="boxes-row">';
		    		$count = 0;
		          	while ( have_rows('homepage_boxes') ) : the_row();
		          		$count++;
		          		$numOutput++;

		              	$text = get_sub_field('homepage_boxes_text');
		              	$image = get_sub_field('homepage_boxes_image');
		              	$link = get_sub_field('homepage_boxes_link');
			            $external = get_sub_field('homepage_boxes_external');

			            $boxStyle = 'box'.$count;

			            if(in_array($count, array(1,5,6))) {
			            	$boxStyle .= ' textup';
			            } else {
			            	$boxStyle .= ' textdown';
			            }
			            // if($numBoxes == 4 && $numOutput == 4)
			            // 	$boxStyle = 'box4';
		              	echo '<div class="box '.$boxStyle.'">';
		              		echo '<a href="'.(($external != '') ? $external : $link).'" title="'.strip_tags($text).'">';
			              	echo '<div class="box-inner">';
				              	echo '<div class="box-text">'.$text.'</div>';
				              	if($image != '')
				              		echo '<img src="'.$image.'" />';
			              	echo '</div>';
			              	echo '</a>';
		              	echo '</div>';

		              	if($count == 6) {
		              		$count = 0;
		              	}

		              	if($count%3 == 0) {
		              		echo '<div class="clear;"></div></div><div class="boxes-row">';
		              	}
		          	endwhile;
		          	echo '<div class="clear;"></div>';
	          		echo '</div>';
	          	echo '</div>';
	      	endif;
		?>
		</div>
	</div>
	<script type="text/javascript">
	$(document).ready(function() {
		$('header').css('position','absolute').css('top', $intro.outerHeight());
	});
	</script>

<?php get_footer(); ?> 