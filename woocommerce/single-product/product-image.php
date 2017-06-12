<?php
/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.6.3
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $post, $product, $woocommerce;
?>
<div class="images fade-in-css">
	<?php
		if ( has_post_thumbnail() ) {
			echo '<div class="image-slide" id="image0">';
			$attachment_count = count( $product->get_gallery_attachment_ids() );
			$gallery          = $attachment_count > 0 ? '[product-gallery]' : '';
			$props            = wc_get_product_attachment_props( get_post_thumbnail_id(), $post );
			$image            = get_the_post_thumbnail( $post->ID, apply_filters( 'single_product_large_thumbnail_size', 'shop_single' ), array(
				'title'	 => $props['title'],
				'alt'    => $props['alt'],
			) );
			echo apply_filters(
				'woocommerce_single_product_image_html',
				sprintf(
					'<a href="%s" itemprop="image" class="woocommerce-main-image zoom" title="%s" data-rel="prettyPhoto%s">%s</a>',
					esc_url( $props['url'] ),
					esc_attr( $props['caption'] ),
					$gallery,
					$image
				),
				$post->ID
			);
			echo '<div class="image-link-mask"></div>';
			echo '</div>';
		} else {
			echo apply_filters( 'woocommerce_single_product_image_html', sprintf( 
				'<img src="%s" alt="%s" />', 
				wc_placeholder_img_src(), 
				__( 'Placeholder', 'woocommerce' ) ), 
				$post->ID 
			);
		}


		$thumbs_str = '';
		$attachment_ids = $product->get_gallery_attachment_ids();

		if ( $attachment_ids ) {
			$loop 		= 1;
			$columns 	= apply_filters( 'woocommerce_product_thumbnails_columns', 3 );

			foreach ( $attachment_ids as $attachment_id ) {

				echo '<div class="image-slide image-slide-hidden" id="image'.$loop.'">';

				$classes = array( 'zoom' );

				if ( $loop === 0 || $loop % $columns === 0 ) {
					$classes[] = 'first';
				}

				if ( ( $loop + 1 ) % $columns === 0 ) {
					$classes[] = 'last';
				}

				$image_class = implode( ' ', $classes );
				$props       = wc_get_product_attachment_props( $attachment_id, $post );

				if ( ! $props['url'] ) {
					continue;
				}

				echo apply_filters(
					'woocommerce_single_product_image_thumbnail_html',
					sprintf(
						'<a href="%s" class="%s" title="%s" data-rel="prettyPhoto[product-gallery]">%s</a>',
						esc_url( $props['url'] ),
						esc_attr( $image_class ),
						esc_attr( $props['caption'] ),
						wp_get_attachment_image( 
							$attachment_id, 
							apply_filters( 'single_product_large_size', 'shop_large' ), 
							0, 
							$props 
						)
					),
					$attachment_id,
					$post->ID,
					esc_attr( $image_class )
				);

				echo '</div>';
				$thumbs_str .= '<div class="thumbnail-pip" rel="image'.$loop.'"></div>';

				$loop++;
			}
		}
	?>

	<?php if($thumbs_str != '') { ?>
	<div class="thumbnails fade-in-css">
		<div class="thumbnails-label">Select Image</div>
		<?php echo '<div class="thumbnail-pip" rel="image0"></div>'.$thumbs_str; ?>
	</div>
	<?php } ?>
</div>
