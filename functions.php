<?php
    
    add_theme_support('menus');
	add_theme_support( 'post-thumbnails' );

	function custom_menu_page_removing() {
	    remove_menu_page( 'edit-comments.php' );
	    remove_menu_page( 'edit-posts.php' );
	}
	add_action( 'admin_menu', 'custom_menu_page_removing' );

	function textdomain_jquery_enqueue() {
	   wp_deregister_script( 'jquery' );
	   wp_register_script( 'jquery', "http" . ($_SERVER['SERVER_PORT'] == 443 ? "s" : "") . "://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js", false, null );
	   wp_enqueue_script( 'jquery' );
	}

	if ( !is_admin() ) {
	    add_action( 'wp_enqueue_scripts', 'textdomain_jquery_enqueue', 11 );
	}

	add_filter( 'template_include', 'var_template_include', 1000 );
	function var_template_include( $t ){
	    $GLOBALS['current_theme_template'] = basename($t);
	    return $t;
	}

	add_filter( 'woocommerce_enqueue_styles', 'jk_dequeue_styles' );
	function jk_dequeue_styles( $enqueue_styles ) {
		unset( $enqueue_styles['woocommerce-smallscreen'] );	// Remove the smallscreen optimisation
		return $enqueue_styles;
	}

	/*
	* get current page template
	* $echo: (bool) echo or return value
	*/
	function get_current_template( $echo = false ) {
	    if( !isset( $GLOBALS['current_theme_template'] ) )
	        return 'false';
	    if( $echo )
	        echo $GLOBALS['current_theme_template'];
	    else
	        return $GLOBALS['current_theme_template'];
	}

	function output_page_title($title, $tagline_before = '', $tagline_after = '') {

		$title_str = '<div class="page_title">';
		if($tagline_before != '')
			$title_str .= '<h2 class="fade-in">'.$tagline_before.'</h2>';
		$title_str .= '<h1 class="fade-in">'.$title.'</h1>';
		if($tagline_after != '')
			$title_str .= '<h2 class="fade-in">'.$tagline_after.'</h2>';
		$title_str .= '</div>';

		return $title_str;
	}

	/*
	 * WooCommerce Support
	 */
	add_action( 'after_setup_theme', 'woocommerce_support' );
	function woocommerce_support() {
	    add_theme_support( 'woocommerce' );
	}

	/*
	 * Hide Sale category from shop page
	 */
	add_action( 'pre_get_posts', 'custom_pre_get_posts_query' );
	function custom_pre_get_posts_query( $q ) {
		if ( ! $q->is_main_query() ) return;
		if ( ! $q->is_post_type_archive() ) return;
		
		if ( ! is_admin() && is_shop() ) {
			$q->set( 'tax_query', array(array(
				'taxonomy' => 'product_cat',
				'field' => 'slug',
				'terms' => array( 'sale' ), // Don't display products in the knives category on the shop page
				'operator' => 'NOT IN'
			)));
		}
		remove_action( 'pre_get_posts', 'custom_pre_get_posts_query' );
	}

	/*
	 * Add wrapper and hover state around product images on shop page
	 */
	remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
	add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);

	if ( ! function_exists( 'woocommerce_template_loop_product_thumbnail' ) ) {
		function woocommerce_template_loop_product_thumbnail() {
			echo woocommerce_get_product_thumbnail();
		} 
	}

	if ( ! function_exists( 'woocommerce_get_product_thumbnail' ) ) {
		
		function woocommerce_get_product_thumbnail( $size = 'shop_product_image', $placeholder_width = 0, $placeholder_height = 0  ) {
			global $post, $woocommerce;
			// if ( ! $placeholder_width )
			// 	$placeholder_width = $woocommerce->get_image_size( 'shop_catalog_image_width' );
			// if ( ! $placeholder_height )
			// 	$placeholder_height = $woocommerce->get_image_size( 'shop_catalog_image_height' );
				
				$output = '<div class="image-wrapper fade-in">';

				//show override image if exists
				$overrideImgID = get_field('shop_image', $post->ID);
				if($overrideImgID != '') {

					$image_array = wp_get_attachment_image_src($overrideImgID, 'shop_thumb');
					$image_url = $image_array[0];

						$output .= '<img src="'.$image_url.'" alt="'.$post->post_title.'" />';

					$output .= '';
				} else {
					if ( has_post_thumbnail() ) {
						$output .= get_the_post_thumbnail( $post->ID, $size ); 
					} else {
						$output .= '<img src="'. woocommerce_placeholder_img_src() .'" alt="Placeholder" width="' . $placeholder_width . '" height="' . $placeholder_height . '" />';
					}
				}

				$collectionNum = get_field('page_title_tagline', $product->ID);
				$collectionNum = explode(' ', $collectionNum);
				$collectionNum = $collectionNum[2];
				
				$output .= '<div class="product-overlay">'.$post->post_title.'</div>';
				$output .= '<div class="product-num">'.$collectionNum.'</div>';
				$output .= '</div>';
				
				return $output;
		}
	}

	// Change Shop page title
	// code goes in functions.php for your child theme
	// used for themes that use a product title for the shop page title
	add_filter('post_type_archive_title', 'shop_page_title' );
	function shop_page_title( $title ) {
		if( $title == __('Products', 'woocommerce')) {
			$shop_page_id = woocommerce_get_page_id( 'shop' );
			$page_title = get_the_title( $shop_page_id );
			return $page_title;
		}
		return $title;
	}

	/*
	 * Remove unwanted woocommerce hooks
	 */
	function override_page_title() {
		return false;
	}
	add_filter('woocommerce_show_page_title', 'override_page_title');

	remove_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10);
	remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);

	remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
	remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);


	/*********************************************************************************************************************
	 * Products page
	 ********************************************************************************************************************/
	remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);
	remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10);
	remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);
	remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);

	//Edit add to cart message
	add_filter( 'wc_add_to_cart_message', 'custom_add_to_cart_message', 10, 2);
	function custom_add_to_cart_message($message, $product_id) {	 
		global $woocommerce;

		$return_to = get_permalink(woocommerce_get_page_id('cart'));
		$message    = sprintf('%s has been added to your bag | <a href="%s" title="View your Bag">%s</a> ', 
			get_the_title($product_id),
			$return_to, 
			__('View Bag', 'woocommerce') );
		return $message;
	}

	//duplicate price in description column (hidden/shown based on breakpoints)
	add_action('woocommerce_after_single_product_summary', 'woocommerce_template_single_price', 5);

	add_action('woocommerce_after_single_product_summary', 'add_desc_wrapper_start', 1);
	add_action('woocommerce_after_single_product_summary', 'add_desc_wrapper_end', 50);
	function add_desc_wrapper_start() {
		echo '<div class="product-info fade-in-css">';
		// echo '<div class="product-info-inner">';
	}
	function add_desc_wrapper_end() {
		echo '</div>';
		// echo '</div>';
	}



	add_action('woocommerce_single_product_summary', 'add_size_guide', 31);
	function add_size_guide() {
		echo '<div class="size-guide">Size guide</div>';
	}



	add_action('woocommerce_single_product_summary', 'add_card_wrapper_start', 1);
	add_action('woocommerce_single_product_summary', 'do_social_sharer', 99);
	add_action('woocommerce_single_product_summary', 'add_card_wrapper_end', 100);
	function add_card_wrapper_start() {
		echo '<div class="summary-inner fade-in-css">';
	}
	function do_social_sharer() {
		echo do_shortcode('[shareaholic app="share_buttons" id="25950265"]');
	}
	function add_card_wrapper_end() {
		echo '</div>';
	}

	//custom cart button
	add_filter( 'woocommerce_product_single_add_to_cart_text', 'woo_custom_cart_button_text' );    // 2.1 +
	function woo_custom_cart_button_text() {
	    return __( 'Add to Bag', 'woocommerce' );
	}

	//Custom tab content
	add_filter( 'woocommerce_product_tabs', 'woo_remove_product_tabs', 98 );
	function woo_remove_product_tabs( $tabs ) {
	    unset( $tabs['reviews'] ); 
	    unset( $tabs['additional_information'] );
	    return $tabs;
	}

	add_filter( 'woocommerce_product_tabs', 'woo_new_product_tab', 99);
	function woo_new_product_tab( $tabs ) {
		$tabs['materials'] = array(
			'title' 	=> __( 'Materials', 'woocommerce' ),
			'priority' 	=> 50,
			'callback' 	=> 'woo_materials_tab_content'
		);
		$tabs['fit'] = array(
			'title' 	=> __( 'Fit', 'woocommerce' ),
			'priority' 	=> 50,
			'callback' 	=> 'woo_fit_tab_content'
		);
		return $tabs;
	}
	function woo_materials_tab_content() {
		wc_get_template( 'single-product/tabs/materials.php' );
	}
	function woo_fit_tab_content() {
		wc_get_template( 'single-product/tabs/fit.php' );
	}

	/**
	* Change Proceed To Checkout Text in WooCommerce
	* Add continue shopping button
	**/
	function woocommerce_button_proceed_to_checkout() {
		$shop_page_url = get_permalink( woocommerce_get_page_id( 'shop' ));
		$checkout_url = WC()->cart->get_checkout_url();
		?>
		<a href="<?php echo $shop_page_url; ?>" class="continue-shopping-button button alt wc-forward"><?php _e( 'Continue Shopping', 'woocommerce' ); ?></a>
		<a href="<?php echo $checkout_url; ?>" class="checkout-button button alt wc-forward"><?php _e( 'Checkout', 'woocommerce' ); ?></a>
		<?php
	}



	/*********************************************************************************************************************
	 * Cart page
	 ********************************************************************************************************************/
	//Add cart wrapper
	add_filter( 'woocommerce_before_cart_table', 'cart_wrapper_start', 98 );
	add_filter( 'woocommerce_checkout_before_customer_details', 'cart_wrapper_start', 98 );
	function cart_wrapper_start() {
		$contact = get_field('checkout_support_contact', 6);
		echo '<div class="cart-table">';
	    echo '<div class="cart-contact">'.$contact.'</div>';
	}
	add_filter( 'woocommerce_after_cart_table', 'cart_wrapper_end', 98 );
	add_filter( 'woocommerce_checkout_after_customer_details', 'cart_wrapper_end', 98 );
	add_filter( 'woocommerce_checkout_after_customer_details', 'cart_summary_start', 99 );
	add_filter( 'woocommerce_checkout_after_order_review', 'cart_summary_end', 99 );
	function cart_wrapper_end() {
		echo '</div>';
	}
	function cart_summary_start() {
		echo '<div class="cart-collaterals">';
	}
	function cart_summary_end() {
		echo '</div>';
	}

	//Coupon label
	function woocommerce_rename_coupon_message_on_checkout() {
		return 'Have a Promo Code?' . ' <a href="#" class="showcoupon">' . __( 'Click here to enter your code', 'woocommerce' ) . '</a>';
	}
	add_filter( 'woocommerce_checkout_coupon_message', 'woocommerce_rename_coupon_message_on_checkout' );
	// rename the coupon field on the checkout page
	function woocommerce_rename_coupon_field_on_checkout( $translated_text, $text, $text_domain ) {
		// bail if not modifying frontend woocommerce text
		if ( is_admin() || 'woocommerce' !== $text_domain ) {
			return $translated_text;
		}
		if ( 'Coupon code' === $text ) {
			$translated_text = 'Add Promotional Code';
		
		} elseif ( 'Apply Coupon' === $text ) {
			$translated_text = 'Apply Code';
		}
		return $translated_text;
	}
	add_filter( 'gettext', 'woocommerce_rename_coupon_field_on_checkout', 10, 3 );
	// hide coupon field on checkout page
	function hide_coupon_field_on_checkout( $enabled ) {
		if ( is_checkout() ) {
			$enabled = false;
		}
		return $enabled;
	}
	add_filter( 'woocommerce_coupons_enabled', 'hide_coupon_field_on_checkout' );


	//Add VIP details
	add_action('woocommerce_proceed_to_checkout', 'add_vip_details', 1);
	function add_vip_details() {
		global $current_user;
		if($current_user->ID != 0) {
			$vip = get_field('vip', 'user_'.$current_user->ID);
			if($vip) {
				echo '<div class="vip-warning">';
				echo '<p><b>You are a VIP customer!</b></p>';
				echo '<p>You can get free gift wrapping on this order</p></div>';
			}
		}
	}


	/*********************************************************************************************************************
	 * Mini basket update
	 ********************************************************************************************************************/
	add_filter('add_to_cart_fragments', 'woocommerce_header_add_to_cart_fragment');
	function woocommerce_header_add_to_cart_fragment( $fragments ) {
		global $woocommerce;
		
		ob_start();
		
		echo '<a class="cart-contents" href="'.wc_get_cart_url().'" title="View my Bag">';
		echo '<span class="bag-total">'.WC()->cart->get_cart_contents_count().'</span>';
		echo '</a>';
		
		$fragments['a.cart-contents'] = ob_get_clean();
		
		return $fragments;
		
	}


	//no shipping message
	function woocommerce_call_for_shipping() {
		return __( 'Call to arrange overseas delivery on 0800 1234 567', 'woocommerce' );
	}
	add_filter( 'woocommerce_no_shipping_available_html', 'woocommerce_call_for_shipping' );
	add_filter( 'woocommerce_cart_no_shipping_available_html', 'woocommerce_call_for_shipping' );


	function wc_dropdown_variation_attribute_options2( $args = array() ) {
        $args = wp_parse_args( apply_filters( 'woocommerce_dropdown_variation_attribute_options_args', $args ), array(
            'options'          => false,
            'attribute'        => false,
            'product'          => false,
            'selected'         => false,
            'name'             => '',
            'id'               => '',
            'class'            => '',
            'show_option_none' => __( 'Choose an option', 'woocommerce' )
        ) );

        $options   = $args['options'];
        $product   = $args['product'];
        $attribute = $args['attribute'];
        $name      = $args['name'] ? $args['name'] : 'attribute_' . sanitize_title( $attribute );
        $id        = $args['id'] ? $args['id'] : sanitize_title( $attribute );
        $class     = $args['class'];

        if ( empty( $options ) && ! empty( $product ) && ! empty( $attribute ) ) {
            $attributes = $product->get_variation_attributes();
            $options    = $attributes[ $attribute ];
        }

        $html = '<select id="' . esc_attr( $id ) . '" 
            class="' . esc_attr( $class ) . '" name="' . esc_attr( $name ) . '" 
            data-attribute_name="attribute_' . esc_attr( sanitize_title( $attribute ) ) . '">';


        $html2 = '<div class="variation '.esc_attr($class).'">';
        $html2 .= '<input type="hidden" id="'.esc_attr($id).'"
            name="' . esc_attr( $name ) . '" 
            data-attribute_name="attribute_' . esc_attr( sanitize_title( $attribute ) ) . '" 
            value="'.$args["selected"].'" 
        />';

        if ( $args['show_option_none'] ) {
            $html .= '<option value="">' . esc_html( $args['show_option_none'] ) . '</option>';
            $html2 .= '<div class="variation-empty">' . (($args["selected"] != '') ? $args["selected"] : esc_html( $args['show_option_none'] )) . '</div>';
        }

        if ( ! empty( $options ) ) {
        	$html2 .= '<div class="variation-options">';
            if ( $product && taxonomy_exists( $attribute ) ) {
                // Get terms if this is a taxonomy - ordered. We need the names too.
                $terms = wc_get_product_terms( $product->id, $attribute, array( 'fields' => 'all' ) );

                foreach ( $terms as $term ) {
                    if ( in_array( $term->slug, $options ) ) {
                        $html .= '<option value="' . esc_attr( $term->slug ) . '" 
                            ' . selected( sanitize_title( $args['selected'] ), $term->slug, false ) . '>
                            ' . esc_html( apply_filters( 'woocommerce_variation_option_name', $term->name ) ) . '</option>';

                        $html2 .= '<div class="variation-option" data-attribute_value="'.esc_attr($term->slug).'">';
                        $html2 .= esc_html(apply_filters( 'woocommerce_variation_option_name', $term->name));
                        $html2 .= '</div>';
                    }
                }
            } else {
                foreach ( $options as $option ) {
                    // This handles < 2.4.0 bw compatibility where text attributes were not sanitized.
                    $selected = sanitize_title( $args['selected'] ) === $args['selected'] ? selected( $args['selected'], sanitize_title( $option ), false ) : selected( $args['selected'], $option, false );
                    $html .= '<option value="' . esc_attr( $option ) . '" ' . $selected . '>' 
                    	. esc_html( apply_filters( 'woocommerce_variation_option_name', $option ) ) . '</option>';

                    $html2 .= '<div class="variation-option" data-attribute_value="'.esc_attr($option).'">';
                        $html2 .= esc_html(apply_filters( 'woocommerce_variation_option_name', $option));
                        $html2 .= '</div>';
                }
            }
            $html2 .= '</div>';
        }

        $html .= '</select>';
        $html2 .= '</div>';

        // echo apply_filters( 'woocommerce_dropdown_variation_attribute_options_html', $html, $args );
        echo apply_filters( 'woocommerce_dropdown_variation_attribute_options_html', $html2, $args );
    }




	/*********************************************************************************************************************
	 * Checkout
	 ********************************************************************************************************************/

	//remove company name field
    add_filter('woocommerce_checkout_fields', 'alter_woocommerce_checkout_fields');
	function alter_woocommerce_checkout_fields( $fields ) {
		unset($fields['billing']['billing_company']); 
		unset($fields['shipping']['shipping_company']);

		return $fields;
	}




	//back to cart button
	add_action('woocommerce_checkout_order_review', 'back_to_bag', 99);
	function back_to_bag() {
		echo '<a href="/cart/" class="continue-shopping-button button alt wc-forward">Edit my bag</a>';
		// echo '<a href="/checkout/" class="checkout-button button alt wc-forward">Make payment</a>';
		echo '<input type="submit" class="button alt" name="woocommerce_checkout_place_order" id="place_order" value="Make payment" data-value="Make payment">';
	}




	/*********************************************************************************************************************
	 * Account
	 ********************************************************************************************************************/

	add_filter('woocommerce_login_redirect', 'wc_login_redirect');
	function wc_login_redirect( $redirect_to ) {
	     $redirect_to = '/my-account/orders/';
	     return $redirect_to;
	}

	// Custom view order button
	add_filter('gettext',  'custom_labels');
	add_filter('ngettext',  'custom_labels');
	function custom_labels($translated) {
	     $translated = str_ireplace('Choose an option',  'Select Size',  $translated);
	     $translated = str_ireplace('Ship to a different address?',  'Shipping Details',  $translated);
	     // $translated = str_ireplace('View',  'Order Details',  $translated);
	     $translated = str_ireplace(array('Username or email address', 'Username or email'),  'Username',  $translated);
	     $translated = str_ireplace('Current Password (leave blank to leave unchanged)',  'Current Password',  $translated);
	     $translated = str_ireplace('New Password (leave blank to leave unchanged)',  'New Password',  $translated);

	     $translated = str_ireplace('Please enter an alternative shipping address', 'Please call to arrange overseas delivery on 0800 1234 567', $translated);

	    $translated = str_ireplace('Sorry, it seems that there are no available payment methods for your state. Please contact us if you require assistance or wish to make alternate arrangements.', 'We currently cannot process overseas orders via the website. Please call Customer Services on 0800 1234 567 and provide the SKU given above and one of our agents will be happy to process your order.', $translated);
	     return $translated;
	}

	/************************************************************************************************************
	 * Add VIP Status
	 ************************************************************************************************************/
	add_action( 'user_register', 'vip_registration', 10, 1 );
	function vip_registration( $user_id ) {
		$maxVIPs = intval(get_field('vip_offer', 7));
		$currentVIPs = intval(get_field('vip_count', 7));

		if($currentVIPs < $maxVIPs) {
        	update_user_meta($user_id, 'vip', 1);

        	$newVIPs = $currentVIPs + 1;
        	update_post_meta(7, 'vip_count', $newVIPs);
        }

	}

	/************************************************************************************************************
	 * Show VIP Status in users screen
	 ************************************************************************************************************/
	//add column
	function show_vip_status( $column ) {
	    $column['vip'] = 'VIP?';
	    return $column;
	}
	add_filter( 'manage_users_columns', 'show_vip_status' );
	//return data for user
	function show_vip_status_row( $val, $column_name, $user_id ) {
	    switch ($column_name) {
	        case 'vip' :
	            $vipStatus = get_user_meta($user_id, 'vip', true);
	            switch($vipStatus) {
	            	case 1:
	            		return 'Yes'; break;
	            	case 0:
	            		return ''; break;
	            	default:
	            		return '';
	            }
	            break;
	        default:
	    }
	    return $val;
	}
	add_filter( 'manage_users_custom_column', 'show_vip_status_row', 10, 3 );
	
	//add filter
	function add_vip_filter($which) {
	    if ( isset( $_GET[ 'vip_filter' ])) {
	        $section = $_GET[ 'vip_filter' ];
	    } else {
	        $section = -1;
	    }
	    if($which == 'top') {
		    echo ' <select name="vip_filter'.(($which == 'bottom') ? '2' : '').'" id="vip_filter'.(($which == 'bottom') ? '2' : '').'" style="float:none;">';
	    	echo '<option value="-1">Filter by VIP Status</option>';
	        $selected = $i == $section ? ' selected="selected"' : '';
	        echo '<option value="1" '.(($section == '1') ? 'selected="selected"' : '').'>VIP Only</option>';
	        // echo '<option value="0" '.(($section == '0') ? 'selected="selected"' : '').'>Not VIP</option>';
		    echo '</select>';
		    echo '<input type="submit" class="button" value="Filter">';
		}
	}
	add_action( 'restrict_manage_users', 'add_vip_filter' );
	//perform filtering
	function filter_users_by_vip_status( $query ) {
	    global $pagenow;

	    if ( is_admin() && 
	         'users.php' == $pagenow && 
	         isset( $_GET[ 'vip_filter' ] ) && $_GET["vip_filter"] != '-1'
	        ) {
	        $section = $_GET[ 'vip_filter' ];
	    	if($section == '1') {
		        $meta_query = array(
		            array(
		                'key' => 'vip',
		                'value' => '1'
		            )
		        );
		    }
	        $query->set( 'meta_key', 'vip' );
	        $query->set( 'meta_query', $meta_query );
	    }
	}
	add_filter( 'pre_get_users', 'filter_users_by_vip_status' );


	function change_post_menu_label() {
	    global $menu;
	    global $submenu;
	    $menu[5][0] = 'FAQs';
	    $submenu['edit.php'][5][0] = 'FAQs';
	    $submenu['edit.php'][10][0] = 'Add FAQs';
	    echo '';
	}

	function change_post_object_label() {
	        global $wp_post_types;
	        $labels = &$wp_post_types['post']->labels;
	        $labels->name = 'FAQs';
	        $labels->singular_name = 'FAQ';
	        $labels->add_new = 'Add FAQ';
	        $labels->add_new_item = 'Add FAQ';
	        $labels->edit_item = 'Edit FAQs';
	        $labels->new_item = 'FAQ';
	        $labels->view_item = 'View FAQ';
	        $labels->search_items = 'Search FAQs';
	        $labels->not_found = 'No FAQs found';
	        $labels->not_found_in_trash = 'No FAQs found in Trash';
	    }
	    add_action( 'init', 'change_post_object_label' );
	    add_action( 'admin_menu', 'change_post_menu_label' );

?>