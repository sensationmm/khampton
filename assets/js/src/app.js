'use strict';

var mobileCheck = {
    Android: function() {
        return navigator.userAgent.match(/Android/i);
    },
    BlackBerry: function() {
        return navigator.userAgent.match(/BlackBerry/i);
    },
    iOS: function() {
        return navigator.userAgent.match(/iPhone|iPod/i);
    },
    Opera: function() {
        return navigator.userAgent.match(/Opera Mini/i);
    },
    Windows: function() {
        return navigator.userAgent.match(/IEMobile/i) || navigator.userAgent.match(/WPDesktop/i);
    },
    any: function() {
        return (mobileCheck.Android() || mobileCheck.BlackBerry() || mobileCheck.iOS() || mobileCheck.Opera() || mobileCheck.Windows());
    }
};

var isMobile = (mobileCheck.any()) ? true : false;

var isIOS = (mobileCheck.iOS()) ? true : false;

	var $bodyDoc = $('body');
	var $body = $('.body');
	var $intro = $('.intro');
	var $header = $('header.site');
	var $navIcon = $('.nav-icon');
	var $navMenu = $('nav.popup');
	var $products = $('.products');
	var $product = $('.products .product');
	var $layoutSwitchCol = $('.layout .layout-col');
	var $layoutSwitchGrid = $('.layout .layout-grid');
	var $productSingle = $('div.product');
	var $productSingleImage = $('div.product .images img');
	var $productInfo = $('div.product .summary, div.product .product-info');
	var $scrollToContent = $('.scroll-content, video');
	var $sizeGuide = $('.size-guide');
	var $overlay = $('.overlay-white');
	var $overlaySizeGuide = $('.overlay-size-guide');
	var $overlaySizeGuideClose = $('.overlay-size-guide-close');
	var $variations = $('.variation .variation-empty');
	var $variationsOption = $('.variation-option');
	var $checkoutFieldsTrigger = $('.fields-header');
	var $shippingToggle = $('.different-shipping-address input');
	var $shippingPanel = $('.woocommerce-shipping-fields h3');
	var $backToTop = $('.back-to-top');
	var $scrollToBoxes = $('.scroll-to-boxes');
	var $boxes = $('.boxes');
	var $boxScroller = $('.scroll-to-next');
	var $faqHeader = $('.faq-header');
	var $faqQuestion = $('.faq');
	var $faqAnswer = $('.faq p');

	var $productGalleryThumbs = $('.thumbnail-pip');

	var $pageTemplate = $('body').attr('id');

$('document').ready(function() {

	if(isIOS) {
		$bodyDoc.addClass('ios');
	}

	/*****************************************************************************************
	 * Header behaviour
	 ****************************************************************************************/
	// if(!isMobile) {
		var $headerHeight = $header.outerHeight();
		var $maxHeaderOffset = $headerHeight * -1;

		var lastScrollTop = 0;
		$(window).scroll(function(event){
			var st = $(this).scrollTop();

			var $headerTopInt = $header.css('top');
			$headerTopInt = $headerTopInt.replace('px', '');
			var $offset = Math.abs(st - lastScrollTop);

			if (st > lastScrollTop){
				if($pageTemplate === 'home' && st > $intro.outerHeight()) {
					$header.css('position','fixed');
				} if($pageTemplate === 'home') {
					$header.css('position','absolute');
				} else {
					//scrolling down
					if($headerTopInt > $maxHeaderOffset) {
						$header.css('top', $headerTopInt - $offset);
					} else {
						$header.css('top', $maxHeaderOffset);
					}
				}
			} else {
				//scrolling up
				// if($headerTopInt < 0 || ($pageTemplate === 'home' && st <= $intro.outerHeight())) {
				// 	$header.css('top', ($headerTopInt) - ($offset * -1));
				// } else {
				// 	$header.css('top', 0);
				// }
				if($pageTemplate === 'home' && st < $intro.outerHeight()) {
					$header.css('position','absolute').css('top', $intro.outerHeight());
				} else {
					$header.css('position','fixed').css('top', 0);
				}
			}
			lastScrollTop = st;
		});
	// }

	/*****************************************************************************************
	 * Nav menu
	 ****************************************************************************************/
	$navIcon.click(function() {
		if($bodyDoc.hasClass('nav-active')) {
			$navMenu.fadeOut('fast');
			$bodyDoc.removeClass('nav-active');
		} else {
			$navMenu.fadeIn('fast');
			$bodyDoc.addClass('nav-active');
		}
	});

	/*****************************************************************************************
	 * Homepage nav bar fade in
	 ***************************************************************************************

	if($bodyDoc.is('#home') && !$bodyDoc.is('.nav-active')) {
		$(window).scroll(function(){
			if($(window).scrollTop() > ($(window).height() - 50)) {
				$header.fadeIn(500);
			} else if($(window).scrollTop() === 0) {
				$header.fadeOut(0);
			}
		});
	}

	if($(window).scrollTop() > $(window).height()) {
		$header.css('display','block');
	}*/

	/*****************************************************************************************
	 * Homepage scroll to content
	 ****************************************************************************************/

	// if(!isMobile) {
		$scrollToContent.click(function() {
			$('body,html').animate({
		        scrollTop : $intro.outerHeight()
		    }, 1000);

		    setTimeout(function() {
				$header.fadeIn(500);
			}, 1000);
		});
	// }

	/*****************************************************************************************
	 * Scroll back to top
	 ****************************************************************************************/
	$backToTop.click(function() {
		$('body,html').animate({
	        scrollTop : 0
	    }, 500);
	});

	/*****************************************************************************************
	 * About scrollers
	 ****************************************************************************************/
	$scrollToBoxes.click(function() {
		$('body,html').animate({
	        scrollTop : $boxes.offset().top
	    }, 500);
	});

	$boxScroller.click(function() {
		$('body,html').animate({
	        scrollTop : Math.round($(this).parent().offset().top + $(this).parent().outerHeight())
	    }, 500);
	});

	/*****************************************************************************************
	 * FAQ accordion
	 ****************************************************************************************/
	$faqHeader.click(function() {
		var $content = $(this).parent().find('.faq-content');
		if($content.css('display') === 'none') {
			$content.slideDown('fast');
			$(this).addClass('active');
		} else {
			$content.slideUp('fast');
			$(this).removeClass('active');
		}
	});
	$faqQuestion.click(function() {
		$faqAnswer.slideUp('fast');
		var $answer = $(this).find('p');
		if($answer.css('display') === 'none') {
			$answer.slideDown('fast');
		} else {
			$answer.slideUp('fast');
		}
	});


	/*****************************************************************************************
	 * Shop page layout switch
	 ****************************************************************************************/
	$layoutSwitchCol.click(function() {
		$products.animate({'opacity':'0'}, 500);
		$(this).animate({'opacity':'1'}, 500);

		$layoutSwitchGrid.animate({'opacity':'0.2'}, 500);
		setTimeout(function() {
			$body.removeClass('grid-layout').addClass('col-layout');
			$products.animate({'opacity':'1'}, 500);
		}, 500);
	});

	$layoutSwitchGrid.click(function() {
		$products.animate({'opacity':'0'}, 500);
		$(this).animate({'opacity':'1'}, 500);

		$layoutSwitchCol.animate({'opacity':'0.2'}, 500);
		setTimeout(function() {
			$body.removeClass('col-layout').addClass('grid-layout');
			$products.animate({'opacity':'1'}, 500);


	    	var bottomOfWindow = $(window).scrollTop() + $(window).height();

		    if(!isMobile) {
		        bottomOfWindow = bottomOfWindow - 200;  
		    }

			$('.fade-in').each( function(i){
		        var topOfObject = $(this).offset().top;
		      
		        if( bottomOfWindow > topOfObject ){
		            $(this).animate({'opacity':'1', 'top':'0'}, 1000);
		        }
		    }); 
		}, 500);

	});

	/*****************************************************************************************
	 * Shop page hover states
	 ****************************************************************************************/
	$product.mouseenter(function() {
		var $overlay = $(this).find('.product-overlay');
		var $image = $(this).find('img');
		var $collectionNum = $(this).find('.product-num');
		$collectionNum.fadeOut('fast');
		$image.animate({'opacity':'0.3'}, 500);
		$overlay.animate({'opacity':'1', 'top':'50%'}, 500);
	}).mouseleave(function() {
		var $overlay = $(this).find('.product-overlay');
		var $image = $(this).find('img');
		var $collectionNum = $(this).find('.product-num');
		$image.animate({'opacity':'1'}, 500);
		$overlay.animate({'opacity':'0', 'top':'55%'}, 500);
		setTimeout(function() {
			$collectionNum.fadeIn('slow');
		}, 500);
	});

	/*****************************************************************************************
	 * Product page vertical alignment
	 ****************************************************************************************/
	if(!isMobile) {
		setTimeout(function() {
			var $imgHeight = $productSingleImage.css('height');
			$productInfo.css('height', $imgHeight);
		}, 100);
	}

	/*****************************************************************************************
	 * Product page image gallery
	 ****************************************************************************************/
	 var $productGalleryActive = 'image0';
	$productGalleryThumbs.click(function() {
		var $target = $(this).attr('rel');

		$('#'+$productGalleryActive).animate({'opacity':'0'}, 750).css('zIndex', 1);
		$('[rel="'+$productGalleryActive+'"]').css('opacity', 0.4);
		$('#'+$target).animate({'opacity':'1'}, 750).css('zIndex', 2);
		$('[rel="'+$target+'"]').css('opacity', 1);

		$productGalleryActive = $target;
	});

	/*****************************************************************************************
	 * Product page size guide
	 ****************************************************************************************/
	$sizeGuide.click(function() {
		$overlay.fadeIn('fast');
		$overlaySizeGuide.css('display','block').animate({'opacity':'1', 'top':'50%'}, 500); 
	});

	$overlaySizeGuideClose.click(function() {
		$overlaySizeGuide.animate({'opacity':'0', 'top':'55%'}, 500, function () {
			setTimeout(function() {
				$overlaySizeGuide.css('display','none'); 
			}, 500);
		});
		$overlay.fadeOut('fast');
	});

	/*****************************************************************************************
	 * Product page variation select
	 ****************************************************************************************/
	$variations.click(function() {
		$(this).parent().find('.variation-options').slideDown('fast');
	});

	$variationsOption.click(function() {
		var $select = $(this).parent().parent().find('.variation-empty');
		var $input = $(this).parent().parent().find('input[type=hidden]');
		$select.html($(this).html());
		$input.val($(this).html());
		$select.attr('data-attribute_value', $(this).attr('data-attribute_value'));
		$(this).parent().slideUp('fast');
	});

	$('.variation-options').mouseleave(function() {
		$(this).slideUp('fast');
	});

	/*****************************************************************************************
	 * Checkout concertina
	 ****************************************************************************************/
	$checkoutFieldsTrigger.click(function() {
		var $content = $(this).parent().find('.fields-container');
		if($content.css('display') === 'none') {
			$(this).find('h3').css('background-image', 'url(assets/images/arrow-up-reversed.png)');
			$content.slideDown('fast');
		} else {
			$content.slideUp('fast');
			$(this).find('h3').css('background-image', 'url(assets/images/arrow-reversed.png)');
		}
	});

	/*****************************************************************************************
	 * Checkout shipping toggle
	 ****************************************************************************************/
	$shippingToggle.click(function() {
		if(this.checked === true) {
			$(this).parent().parent().find('input[type="text"], textarea, .select2-container').removeAttr('disabled');
		} else {
			$(this).parent().parent().find('input[type="text"], textarea, .select2-container').attr('disabled','disabled');
		}
	});

	/*****************************************************************************************
	 * Checkout shipping info prepopulation
	 ****************************************************************************************/
	$shippingPanel.click(function() {
		if($('#shipping_first_name').val() === '' && $('#ship-to-different-address-checkbox').is(':checked') === false) {
			$('#shipping_first_name').val($('#billing_first_name').val());
		}
		if($('#shipping_last_name').val() === '' && $('#ship-to-different-address-checkbox').is(':checked') === false) {
			$('#shipping_last_name').val($('#billing_last_name').val());
		}
		if($('#shipping_company').val() === '' && $('#ship-to-different-address-checkbox').is(':checked') === false) {
			$('#shipping_company').val($('#billing_company').val());
		}
		if($('#shipping_address_1').val() === '' && $('#ship-to-different-address-checkbox').is(':checked') === false) {
			$('#shipping_address_1').val($('#billing_address_1').val());
		}
		if($('#shipping_address_2').val() === '' && $('#ship-to-different-address-checkbox').is(':checked') === false) {
			$('#shipping_address_2').val($('#billing_address_2').val());
		}
		if($('#shipping_city').val() === '' && $('#ship-to-different-address-checkbox').is(':checked') === false) {
			$('#shipping_city').val($('#billing_city').val());
		}
		if($('#shipping_postcode').val() === '' && $('#ship-to-different-address-checkbox').is(':checked') === false) {
			$('#shipping_postcode').val($('#billing_postcode').val());
		}
		if($('#shipping_country').val() === '' && $('#ship-to-different-address-checkbox').is(':checked') === false) {
			$('#shipping_country').val($('#billing_country').val());
		}
		if($('#shipping_state').val() === '' && $('#ship-to-different-address-checkbox').is(':checked') === false) {
			$('#shipping_state').val($('#billing_state').val());
		}
	});



	/*****************************************************************************************
	 * Content transitions
	 ****************************************************************************************/
    var bottomOfWindow = $(window).scrollTop() + $(window).height();

    /* Adjust the "200" to either have a delay or that the content starts fading a bit before you reach it  */

    // if(!mobileCheck.iOS()) {
    if(!isMobile) {
        bottomOfWindow = bottomOfWindow - 200;  
    }

    $('.fade-in').each( function(i){
        var topOfObject = $(this).offset().top;
      
        if( bottomOfWindow > topOfObject ){
            $(this).animate({'opacity':'1', 'top':'0'}, 500);  
        }
    }); 



});

if(!isMobile) {
	$(window).on('resize', function() {
		var $imgHeight = $productSingleImage.css('height');
		$productSingle.css('height', $imgHeight);
	});
}



$(window).scroll( function(){

	// if(isMobile) {
	// 	$intro.css('height', '100vh');
	// }

    var bottomOfWindow = $(window).scrollTop() + $(window).height();

    /* Adjust the "200" to either have a delay or that the content starts fading a bit before you reach it  */
    if(!isMobile) {
        bottomOfWindow = bottomOfWindow - 200;  
    }
   
    $('.fade-in').each( function(i){
        var topOfObject = $(this).offset().top;
      
        if( bottomOfWindow > topOfObject ){
            $(this).animate({'opacity':'1', 'top':'0'}, 1000);
        }
    }); 

    $('.boxes .box').each(function(i) {
        var topOfObject = $(this).offset().top;
      
        if( (bottomOfWindow - 150) > topOfObject ){
            $(this).find('img').animate({'opacity':'1', 'top':'0'}, 1000);
            if($(this).hasClass('textup')) {
				$(this).find('.box-text').animate({'opacity':'1', 'bottom':'-5'}, 1000);
            } else if($(this).hasClass('textdown')) {
				$(this).find('.box-text').animate({'top':'5', 'opacity':'1'}, 1000);
            }
        }

    });

    // Ensure header doesnt finish out of viewport
	var $finalTop = $header.css('top');
	$finalTop = $finalTop.replace('px', '');

	if($(window).scrollTop() === 0 && $finalTop < 0) {
		$header.css('top', 0);
	}

});
