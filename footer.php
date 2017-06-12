	<?php
		if(is_product()) {

		echo '<div class="overlay-white">';
		echo '</div>';

		echo '<div class="overlay-size-guide">';
		$sizeGuideContent = get_page(242);
		echo '<div class="overlay-header">'.$sizeGuideContent->post_title.'</div>';
		echo apply_filters('the_content', $sizeGuideContent->post_content);
		echo '<div class="overlay-size-guide-close"></div>';
		echo '</div>';
		}
	?>

    <?php wp_footer(); ?>

    <div class="back-to-top">Back to Top</div>

    <footer>
    	<div class="inner">
    		<div class="footer-menu">
		    <?php
		    	$nav = wp_get_nav_menu_items('footer');
		    	$nav_str = '';
				if(sizeof($nav) > 0) {
		            $nav_str .= '<ul class="menu footer">';
					$navigationContainer = '';
					for($i=0; $i<sizeof($nav); $i++) {
						$navPage = get_field('_menu_item_object_id', $nav[$i]->ID);
						$navPage = get_post($navPage);
						
						$nav_str .= '<li>';

						$nav_str .= '<a ';

						if($nav[$i]->type == 'custom')
							$nav_str .= 'target="_blank" ';

						$nav_str .= 'href="'.$nav[$i]->url.'" title="Go to '.$navPage->post_title.'">';
						$nav_str .= $nav[$i]->title.'</a>';
						$nav_str .= '</li>';
					}
					$nav_str .= '</ul>';
					echo $nav_str;
				}
			?>
			</div>

			<?php mailchimpSF_signup_form(); ?>
		</div>
	    	<div class="footer-logo"><img src="assets/images/logo-icon.png" /></div>
    </footer>

    <nav class="popup">
    <?php


    	$nav = wp_get_nav_menu_items('header');
		if(sizeof($nav) > 0) {
            echo '<ul class="menu main">';
			for($i=0; $i<sizeof($nav); $i++) {
				
				$navPage = get_field('_menu_item_object_id', $nav[$i]->ID);
				$navPage = get_post($navPage);
				
				echo '<li>';

				echo '<a ';

				if($nav[$i]->type == 'custom')
					echo 'target="_blank" ';

				echo 'href="'.$nav[$i]->url.'" title="Go to '.$navPage->post_title.'">';
				echo $nav[$i]->title.'</a>';
				echo '</li>';
			}
			echo '</ul>';
		}



    	echo $nav_str;
    ?>
    </nav>

    <script type="text/javascript" src="assets/js/app.min.js"></script>

	<script type="text/javascript">
	$(document).ready(function() {
		$('.fields-container').css('display','none');
		$('.woocommerce-billing-fields .fields-container').delay(500).slideDown('fast');
		$('.woocommerce-shipping-fields input[type="text"], .woocommerce-shipping-fields textarea, .woocommerce-shipping-fields .select2-container').attr('disabled','disabled');

		if($('body').outerHeight() < ($(window).height()*2.5)) {
			$('.back-to-top').css('display','none');
		}

		$('#mc_mv_EMAIL').attr('placeholder', 'Receive our newsletter');
	});
	</script>
</body>
</html>