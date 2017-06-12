<?php
	/*if(!is_user_logged_in())
		header('Location: '.WP_SITEURL.'/holding/');*/

	global $headerInclude, $post, $pageObj, $template, $current_user;
	$pageObj = $post;
	$current_user = wp_get_current_user();
	$template = get_current_template();

	if($template == 'template-woocommerce.php')
		$template = 'woocommerce-cart.php';

	$template = substr($template, 0, stripos($template, '.'));
	$template = str_replace('template-', '', $template);

	if($pageObj->ID != 75)
		$pageTitle = $pageObj->post_title.' :: ';
	else 
		$pageTitle = '';
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, user-scalable=no" />
<title><?php echo $pageTitle.' '.get_bloginfo('name'); ?></title>
<base href="<?php echo WP_SITEURL; ?>wp-content/themes/kamica2016/" />
<link rel="shortcut icon" type="image/x-icon" href="assets/images/favicon.ico" />
<link rel="stylesheet" href="assets/css/style.css" />
<?php echo $headerInclude; ?>
<?php wp_head(); ?>
</head>
<body id="<?php echo $template; ?>">

	<header class="site">
		<div class="inner">
			<div class="logo">
				<a href="<?php echo WP_SITEURL; ?>" title="Go to Homepage">Kamica Hampton<br /><span>Texas</span></a>
			</div>
			<div class="nav-icon"></div>
			<div class="cart">
			<?php
				global $current_user, $user_login;
				get_currentuserinfo();
				$user_info = get_userdata($current_user->ID);
				$user_role = implode(',', $user_info->roles);
				// echo '<div class="login">';
				if(is_user_logged_in() && $user_role == 'customer') {
					echo '<a href="'.WP_SITEURL.'my-account/orders/" title="Go to My Account">My Account</a>';
				} else {
					echo '<a href="'.WP_SITEURL.'my-account/" title="Login to My Account">Sign In</a>';
				}


				echo '<a class="cart-contents" href="'.wc_get_cart_url().'" title="View my Bag">';
				echo '<span class="bag-total">'.WC()->cart->get_cart_contents_count().'</span>';
				echo '</a>';
			?>
			</div>

		</div>
	</header>