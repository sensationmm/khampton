<?php
/**
 * @package WordPress
 * @subpackage Kamica Hampton 2016
*/
get_header();  
?>

		<?php 
			if(is_shop() && !is_product()) {
				$pageTitle = woocommerce_page_title(false);
				$pageID = wc_get_page_id($pageTitle);

				$pageTitleOverride = get_field('page_title_override', $pageID);
				$pageTitleTagline = get_field('page_title_tagline', $pageID);

				if($pageTitleOverride != '')
					$pageTitle = $pageTitleOverride;

				echo '<div class="header-outer">'.output_page_title($pageTitle, '', $pageTitleTagline).'</div>';

				?>
	<div class="body-outer shop-header">
		<div class="body col-layout">
				<div class="layout fade-in">
					<div class="layout-label">Select Display</div>
					<div class="layout-col"></div>
					<div class="layout-grid"></div>
				</div>
				<?php

			} else {

				?>
	<div class="body-outer">
		<div class="body col-layout">
			<?php
				$pageTitle = $pageObj->post_title;
				$pageID = $pageObj->ID;

				$pageTitleOverride = get_field('page_title_override', $pageID);
				$pageTitleTagline = get_field('page_title_tagline', $pageID);

				if($pageTitleOverride != '')
					$pageTitle = $pageTitleOverride;

				echo output_page_title($pageTitle, $pageTitleTagline);
			}


			woocommerce_content(); 
			wp_reset_query();
		?>
		</div>
	</div>

<?php get_footer(); ?> 