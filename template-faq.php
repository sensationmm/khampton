<?php
/**
 * @package WordPress
 * @subpackage Kamica Hampton 2016
 * Template Name: FAQS
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

			echo '<div class="faqs">';
			$categories = get_categories();
			if(sizeof($categories) > 0) {
				for($c=0; $c<sizeof($categories); $c++) {
					echo '<div class="faq-section">';
						echo '<div class="faq-header"><h2>'.$categories[$c]->name.'</h2></div>';

						echo '<div class="faq-content">';

							$args = array('post_type' => 'post',
										  'cat' => $categories[$c]->term_id);
							$qus = new WP_Query($args);

							if ($qus->have_posts() ) : 
								while ( $qus->have_posts() ) : $qus->the_post();

								echo '<div class="faq">';
								echo '<h3>'.get_the_title().'</h3>';
								$content = get_the_content();
								echo apply_filters('the_content', $content);
								echo '</div>';

								endwhile;
							endif;
						echo '</div>';
					echo '</div>';
					wp_reset_query();
				}
			}
			echo '</div>';
		?>
		</div>
	</div>

<?php get_footer(); ?> 