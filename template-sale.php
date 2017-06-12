<?php
/**
 * @package WordPress
 * @subpackage Kamica Hampton 2016
 * Template Name: Sale
*/
get_header();  
?>
	
		<?php 

			$pageTitle = $pageObj->post_title;

			$pageTitleOverride = get_field('page_title_override', $pageObj->ID);
			$pageTitleTagline = get_field('page_title_tagline', $pageObj->ID);

			if($pageTitleOverride != '')
				$pageTitle = $pageTitleOverride;

				echo '<div class="header-outer">'.output_page_title($pageTitle, '', $pageTitleTagline).'</div>';
			?>


	<div class="body-outer shop-header">
		<div class="body">
			<?php
			if ( have_posts() ) {
				while ( have_posts() ) {
					the_post(); 
					
					the_content();
				}
			}

			$args = array('post_type' => 'product',
						  'product_cat' => 'sale',
						  'posts_per_page' => -1);

			$saleItems = new WP_Query($args);

			global $wp_query; 
			if ($saleItems->have_posts() ) : 


				echo '<div class="body-outer shop-header">';
					echo '<div class="body col-layout">';
							echo '<div class="layout fade-in" style="opacity: 1; top: 0px;">';
								echo '<div class="layout-label">Select Display</div>';
								echo '<div class="layout-col"></div>';
								echo '<div class="layout-grid"></div>';
							echo '</div>';
							
							echo '<ul class="products">';

				while ( $saleItems->have_posts() ) : $saleItems->the_post();


					$output = '<li class="post-23 product type-product status-publish has-post-thumbnail first instock taxable shipping-taxable purchasable product-type-variable has-default-attributes has-children">';
					$output .= '<a href="'.get_the_permalink().'" class="woocommerce-LoopProduct-link">';
					$output .= '<div class="image-wrapper fade-in">';

					//show override image if exists
					$overrideImgID = get_field('shop_image', get_the_ID());
					if($overrideImgID != '') {

						$image_array = wp_get_attachment_image_src($overrideImgID, 'shop_thumb');
						$image_url = $image_array[0];

							$output .= '<img src="'.$image_url.'" alt="'.get_the_title().'" />';

						$output .= '';
					} else {
						if ( has_post_thumbnail() ) {
							$output .= get_the_post_thumbnail( get_the_ID(), $size ); 
						} else {
							$output .= '<img src="'. woocommerce_placeholder_img_src() .'" alt="Placeholder" width="' . $placeholder_width . '" height="' . $placeholder_height . '" />';
						}
					}

					$collectionNum = get_field('page_title_tagline', get_the_ID());
					$collectionNum = explode(' ', $collectionNum);
					$collectionNum = $collectionNum[2];
					
					$output .= '<div class="product-overlay">'.get_the_title().'</div>';
					$output .= '<div class="product-num">'.$collectionNum.'</div>';
					$output .= '</div>';
					$output .= '</a>';
					// $output .= '<a rel="nofollow" href="'.get_the_permalink().'" data-quantity="1" data-product_id="23" data-product_sku="" class="button product_type_variable add_to_cart_button">';
					// $output .= 'Instinct<br>No. '.$collectionNum.'</a>';
					$output .= '</li>';
					
					echo $output;
				endwhile;
				echo '</ul>';
				echo '</div>';
				echo '</div>';
			else:
				echo '<h2 style="text-align: center;">There are no products currently on sale</h2>';
			endif;
		?>
		</div>
	</div>

<?php get_footer(); ?> 