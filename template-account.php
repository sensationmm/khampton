<?php
/**
 * @package WordPress
 * @subpackage Kamica Hampton 2016
 * Template Name: My Account
*/
get_header();  

global $current_user;
get_currentuserinfo();

?>
	
	<div class="body-outer">
		<div class="body">
			<?php 
				if(is_user_logged_in()) {
					echo output_page_title('Hello '.$current_user->user_firstname);
				} else if(is_page(199)) { 
					echo output_page_title($pageObj->post_title);
				} else { 
					echo output_page_title('My Account');
				}

				$isDetails = ($_SERVER["REQUEST_URI"] == '/my-account/edit-account/') ? true : false;
				$isWishlist = ($pageObj->ID == 199) ? true : false;
				$isOrders = ($_SERVER["REQUEST_URI"] == '/my-account/orders/' || substr($_SERVER["REQUEST_URI"], 0, -4) == '/my-account/view-order/') ? true : false;
				$isAddresses = ($_SERVER["REQUEST_URI"] == '/my-account/edit-address/' || $_SERVER["REQUEST_URI"] == '/my-account/edit-address/billing/' || $_SERVER["REQUEST_URI"] == '/my-account/edit-address/shipping/') ? true : false;
			?>

			<?php if(is_user_logged_in()) { ?>
			<div class="account-panel">
				<ul>
				<li><a <?php if($isOrders) echo 'class="current" '; ?>href="/my-account/orders/" title="Your Order">Your orders</a></li>
				<li><a <?php if($isWishlist) echo 'class="current" '; ?>href="/wishlist/" title="Your Wishlist">Your wishlist</a></li>
				<li><a <?php if($isDetails) echo 'class="current" '; ?>href="/my-account/edit-account/" title="Your Details">Your details</a></li>
				<li><a <?php if($isAddresses) echo 'class="current" '; ?>href="/my-account/edit-address/" title="Your Details">Your addresses</a></li>
				</ul>

				<p><a href="<?php echo wp_logout_url( home_url() ); ?>">Logout of My Account</a></p>
			</div>
			<?php } ?>

			<?php if(is_user_logged_in() || is_page(199)) { ?>
			<div class="account-content">
			<?php } else { ?>
			<div class="account-login">
			<?php } ?>
			<?php
				if ( have_posts() ) {
					while ( have_posts() ) {
						the_post(); 
						
						the_content();
					}
				}
			?>
			</div>
		</div>
	</div>

<?php get_footer(); ?> 