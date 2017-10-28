$=jQuery;

console.log('grayson.js');

$( document ).on( "mobileinit", function() {
  $.extend(  $.mobile , {autoInitializePage: false});
    $.mobile.loader.prototype.options.disabled = true;

    $.mobile.ajaxEnabled=false;

    $.mobile.loading().hide();


});



$(document).ready(function() {


    $('.dropdown-total').attr('href', '/cart');


    /////////////////////////////////////////////////////////////////////////////////////////////////////// ADDED TO CART JS

    if ($('.woocommerce-message').length > 0) {

        $('.woocommerce-message').fadeOut();

        var $button = $('.widget_shopping_mini_cart_content').parent();
        var $popup = $('.dropdown', $button);

        if(!$popup.is(':visible')){

            $popup.removeClass('drop-left')
                .removeClass('drop-bottom');

            // get width/height
            $popup.show();
            var $width = $popup.width();
            var $height = $popup.height();
            var $button_offset = $button.get(0).getBoundingClientRect();
            $popup.hide();

            var $left = $button_offset.right - $width;
            var $right = $(window).width() - ($button_offset.left + $width);
            var $top = $button_offset.bottom - $height;
            var $bottom = $(window).height() - ($button_offset.bottom + $height);

            if($left < 10 && $right > 0){
                $popup.addClass('drop-left');
            }

            if($bottom < 10 && $top > 0){
                $popup.addClass('drop-bottom');
            }

            $popup.slideDown();
        }

    }


    $('.big_img_title').css({'background-image': 'url(' + $('.big_img_title img').attr('src') + ')'});

    $('#question').unbind().click(function() {
        $('html, body').animate({
            scrollTop: $('#question_form').offset().top - 100
        }, 500);
    });

    // slider
    list = $('.cdx-theme-list-view ul li');
    var youllLoveTheseCount = list.length;

    list = $('.rc_wc_rvp_product_list_widget li');
    var recentlyViewedCount = list.length;

//  console.log('recents: '+recentlyViewedCount);
//  console.log('youll love: '+youllLoveTheseCount);

    if (recentlyViewedCount == 0) {
        $('.recently_viewed').remove();
        $('.youll_love_these').css({'border': 'none', 'width': '100%'});
        $('.recently_viewed #bullet-1').remove();
        $('.recently_viewed #bullet-3').remove();
        $('.recently_viewed #bullet-2').remove();
        $('.recently_viewed #bullet-1').remove();
    } else if (recentlyViewedCount < 4) {
        $('.recently_viewed #bullet-3').remove();
        $('.recently_viewed #bullet-2').remove();
    } else if (recentlyViewedCount < 7) {
        $('.recently_viewed #bullet-3').remove();
    }

    if (youllLoveTheseCount < 4) {
        $('.youll_love_these #bullet-3').remove();
        $('.youll_love_these #bullet-2').remove();
        $('.youll_love_these #bullet-1').remove();
    } else if (youllLoveTheseCount < 7) {
        $('.youll_love_these #bullet-3').remove();
    }


    if ($(window).width() < 768) {

        // slider
        list = $('.cdx-theme-list-view ul li');
        var youllLoveTheseCount = list.length

        for(var i = 0; i < list.length; i+=2) {
          list.slice(i, i+2).wrapAll("<div id='slide-"+i+"' class='product_slide'></div>");
        }

        list = $('.rc_wc_rvp_product_list_widget li');
        var recentlyViewedCount = list.length

        for(var i = 0; i < list.length; i+=2) {
          list.slice(i, i+2).wrapAll("<div id='slide-"+i+"' class='product_slide'></div>");
        }


        $('.recently_viewed #slide-0').show();
        $('.recently_viewed #slide-2').hide();
        $('.recently_viewed #slide-4').hide();
        $('.recently_viewed #slide-6').hide();
        $('.recently_viewed #slide-8').hide();
        $('.youll_love_these #slide-0').show();
        $('.youll_love_these #slide-2').hide();
        $('.youll_love_these #slide-4').hide();
        $('.youll_love_these #slide-6').hide();
        $('.youll_love_these #slide-8').hide();



        $('.recently_viewed #bullet-1').click(function(e) {
            e.preventDefault();
            $('.recently_viewed #bullet-1').css({'color': '#111'});
            $('.recently_viewed #bullet-2').css({'color': '#f37f93'});
            $('.recently_viewed #bullet-3').css({'color': '#f37f93'});
            $('.recently_viewed #slide-0').fadeIn();
            $('.recently_viewed #slide-2').hide();
            $('.recently_viewed #slide-4').hide()

        });

        $('.recently_viewed #bullet-2').click(function(e) {
            e.preventDefault();
            $('.recently_viewed #bullet-2').css({'color': '#111'});
            $('.recently_viewed #bullet-1').css({'color': '#f37f93'});
            $('.recently_viewed #bullet-3').css({'color': '#f37f93'});
            $('.recently_viewed #slide-0').hide();
            $('.recently_viewed #slide-2').fadeIn();
            $('.recently_viewed #slide-4').hide()
        });

        $('.recently_viewed #bullet-3').click(function(e) {
            e.preventDefault();
            $('.recently_viewed #bullet-3').css({'color': '#111'});
            $('.recently_viewed #bullet-1').css({'color': '#f37f93'});
            $('.recently_viewed #bullet-2').css({'color': '#f37f93'});
            $('.recently_viewed #slide-0').hide();
            $('.recently_viewed #slide-2').hide();
            $('.recently_viewed #slide-4').fadeIn()
        });

        $('.youll_love_these #bullet-1').click(function(e) {
            e.preventDefault();
            $('.youll_love_these #bullet-1').css({'color': '#111'});
            $('.youll_love_these #bullet-2').css({'color': '#f37f93'});
            $('.youll_love_these #bullet-3').css({'color': '#f37f93'});
            $('.youll_love_these #slide-0').fadeIn();
            $('.youll_love_these #slide-2').hide();
            $('.youll_love_these #slide-4').hide()
        });

        $('.youll_love_these #bullet-2').click(function(e) {
            e.preventDefault();
            $('.youll_love_these #bullet-2').css({'color': '#111'});
            $('.youll_love_these #bullet-1').css({'color': '#f37f93'});
            $('.youll_love_these #bullet-3').css({'color': '#f37f93'});
            $('.youll_love_these #slide-0').hide();
            $('.youll_love_these #slide-2').fadeIn();
            $('.youll_love_these #slide-4').hide()
        });

        $('.youll_love_these #bullet-3').click(function(e) {
            e.preventDefault();
            $('.youll_love_these #bullet-3').css({'color': '#111'});
            $('.youll_love_these #bullet-2').css({'color': '#f37f93'});
            $('.youll_love_these #bullet-1').css({'color': '#f37f93'});
            $('.youll_love_these #slide-0').hide();
            $('.youll_love_these #slide-2').hide();
            $('.youll_love_these #slide-4').fadeIn()
        });


        if (recentlyViewedCount < 5) {
            $('.recently_viewed #bullet-3').remove();
        } else if (recentlyViewedCount < 3) {
            $('.recently_viewed #bullet-3').remove();
            $('.recently_viewed #bullet-2').remove();
        } else if (recentlyViewedCount == 0) {
            $('.recently_viewed #bullet-1').remove();
            $('.recently_viewed #bullet-3').remove();
            $('.recently_viewed #bullet-2').remove();
        }

        if (youllLoveTheseCount < 5) {
            $('.youll_love_these #bullet-3').remove();
        } else if (youllLoveTheseCount < 3) {
            $('.youll_love_these #bullet-3').remove();
            $('.youll_love_these #bullet-2').remove();
        } else if (youllLoveTheseCount == 0) {
            $('.youll_love_these #bullet-1').remove();
            $('.youll_love_these #bullet-3').remove();
            $('.youll_love_these #bullet-2').remove();
        }


        $(".youll_love_these_list").on("swipe",function(swipe){
            product_swipe(swipe, '.youll_love_these', youllLoveTheseCount);
        });



        $(".rc_wc_rvp_product_list_widget").on("swipe",function(swipe){
            product_swipe(swipe, '.recently_viewed', recentlyViewedCount);
        });

    } else {

        // slider
        list = $('.cdx-theme-list-view ul li');
        var youllLoveTheseCount = list.length

        for(var i = 0; i < list.length; i+=3) {
          list.slice(i, i+3).wrapAll("<div id='slide-"+i+"' class='product_slide'></div>");
        }

        list = $('.rc_wc_rvp_product_list_widget li');
        var recentlyViewedCount = list.length

        for(var i = 0; i < list.length; i+=3) {
          list.slice(i, i+3).wrapAll("<div id='slide-"+i+"' class='product_slide'></div>");
        }


        $('.recently_viewed #slide-0').show();
        $('.recently_viewed #slide-3').hide();
        $('.recently_viewed #slide-6').hide()
        $('.youll_love_these #slide-0').show();
        $('.youll_love_these #slide-3').hide();
        $('.youll_love_these #slide-6').hide()

        $('.recently_viewed #bullet-1').click(function(e) {
            e.preventDefault();
            $('.recently_viewed #bullet-1').css({'color': '#111'});
            $('.recently_viewed #bullet-2').css({'color': '#f37f93'});
            $('.recently_viewed #bullet-3').css({'color': '#f37f93'});
            $('.recently_viewed #slide-0').fadeIn();
            $('.recently_viewed #slide-3').hide();
            $('.recently_viewed #slide-6').hide()
        });

        $('.recently_viewed #bullet-2').click(function(e) {
            e.preventDefault();
            $('.recently_viewed #bullet-2').css({'color': '#111'});
            $('.recently_viewed #bullet-1').css({'color': '#f37f93'});
            $('.recently_viewed #bullet-3').css({'color': '#f37f93'});
            $('.recently_viewed #slide-0').hide();
            $('.recently_viewed #slide-3').fadeIn();
            $('.recently_viewed #slide-6').hide()
        });

        $('.recently_viewed #bullet-3').click(function(e) {
            e.preventDefault();
            $('.recently_viewed #bullet-3').css({'color': '#111'});
            $('.recently_viewed #bullet-1').css({'color': '#f37f93'});
            $('.recently_viewed #bullet-2').css({'color': '#f37f93'});
            $('.recently_viewed #slide-0').hide();
            $('.recently_viewed #slide-3').hide();
            $('.recently_viewed #slide-6').fadeIn()
        });

        $('.youll_love_these #bullet-1').click(function(e) {
            e.preventDefault();
            $('.youll_love_these #bullet-1').css({'color': '#111'});
            $('.youll_love_these #bullet-2').css({'color': '#f37f93'});
            $('.youll_love_these #bullet-3').css({'color': '#f37f93'});
            $('.youll_love_these #slide-0').fadeIn();
            $('.youll_love_these #slide-3').hide();
            $('.youll_love_these #slide-6').hide()
        });

        $('.youll_love_these #bullet-2').click(function(e) {
            e.preventDefault();
            $('.youll_love_these #bullet-2').css({'color': '#111'});
            $('.youll_love_these #bullet-1').css({'color': '#f37f93'});
            $('.youll_love_these #bullet-3').css({'color': '#f37f93'});
            $('.youll_love_these #slide-0').hide();
            $('.youll_love_these #slide-3').fadeIn();
            $('.youll_love_these #slide-6').hide()
        });

        $('.youll_love_these #bullet-3').click(function(e) {
            e.preventDefault();
            $('.youll_love_these #bullet-3').css({'color': '#111'});
            $('.youll_love_these #bullet-2').css({'color': '#f37f93'});
            $('.youll_love_these #bullet-1').css({'color': '#f37f93'});
            $('.youll_love_these #slide-0').hide();
            $('.youll_love_these #slide-3').hide();
            $('.youll_love_these #slide-6').fadeIn()
        });

    }


    ////////////////////////////////////////////////////////////////////////////////////////////////////////////// CHECKOUT CUSTOM JS


    //////////////////////////////////////////////////////////////////////////////////////////////////// IMPORTANT!!! - PAYMENT JS MUST BE PROCESSED IN PAYMENT.PHP

    $("p .checkout_edit").unwrap();

    // HIDE BEFORE PAGE LOAD
    hideShipping();
    // hidePayment() is getting called in /wp-content/themes/KDJ/woocommerce/checkout/payment.php
    hideReviewAndPurchase();



    // CONTINUE BUTTON CLICKS
    $('#billing_continue').unbind().click(function() {
        billing_form = "#billing_slide";

        var billing_errors = validateForm( billing_form );;

    if ( billing_errors.length === 0 ) {
        $("#billing_continue").hide();
        hideBilling();
        showShipping();

        // ADD EDIT BUTTON
        $('#billing_edit').fadeIn();

        // ADD FIELD REVIEW AREA
        var billingFirstName = '<p>' + $('#billing_first_name').val() + '</p>';
        var billingEmail = '<p>' + $('#billing_email').val() + '</p>';

        var billingFieldReviewWrapper = $('#billing_field_review');

        $(billingFirstName).appendTo(billingFieldReviewWrapper);
        $(billingEmail).appendTo(billingFieldReviewWrapper);
        $('#billing_field_review').fadeIn();


        $('html,body').animate({
            scrollTop: $("#customer_details .col-1").offset().top - 50},
          'slow');
    } else {

        var warning = $(".woocommerce-billing-fields .cmrd-warning");

        $(warning).css("visibility", "visible");

        $(".continue").on("click", function(e) {
            $(warning).css("visibility", "hidden");
            $(".continue").off(e);
        });

        $(billing_errors).each(function(idx, field) {
          outlineRed(field);

        });

        $('html,body').animate({
            scrollTop: $(".woocommerce-billing-fields").offset().top - 50},
          'slow');
        }

    });


    $('#shipping_continue').unbind().click(function() {
        var shipping_errors = validateForm( '.woocommerce-shipping-fields' );

    if ( shipping_errors.length === 0 ) {

        hideShipping();
        showPayment();

        $('html,body').animate({
            scrollTop: $("#payment").offset().top},
          'slow');

        // ADD EDIT BUTTON
        $('#shipping_edit').fadeIn();

        $('#customer_details .col-2').css({'height': 'auto'});

        var shippingFieldReviewWrapper = $('#shipping_field_review');

        // ADD FIELD REVIEW AREA
        var shippingName = '<p>' + $('#shipping_first_name').val() + ' ' + $('#shipping_last_name').val() + '</p>';
        var shippingAddress1 = '<p>' + $('#shipping_address_1').val() + '</p>';
        var shippingAddress2 = '<p>' + $('#shipping_address_2').val() + '</p>';
        var shippingCityStateAndZip = '<p>' + $('#shipping_city').val() + ', ' + $('#shipping_state').val() + ' ' + $('#shipping_postcode').val() + '</p>';
        var shippingCountry = '<p>' + $('#shipping_country').val() + '</p>';

        var shippingOption = '<br><br><p>' + $('#shipping_method_0 option:selected' ).text() + '</p>';

        if (shippingAddress2 == '<p></p>') {
            var shippingReviewFields = shippingName + shippingAddress1 + shippingCityStateAndZip + shippingCountry + shippingOption;
        } else {
            var shippingReviewFields = shippingName + shippingAddress1 + shippingAddress2 + shippingCityStateAndZip + shippingCountry + shippingOption;
        }

        $(shippingReviewFields).appendTo(shippingFieldReviewWrapper);
        $('#shipping_field_review').fadeIn();

    } else {

        var warning = $(".woocommerce-shipping-fields .cmrd-warning");
        $(warning).css("visibility", "visible");

        $(".continue").on("click", function(e) {
            $(warning).css("visibility", "hidden");
            $(".continue").off(e);
        });

        $(shipping_errors).each(function(idx, field) {
          outlineRed(field);
        });

        $('html,body').animate({
            scrollTop: $(".woocommerce-shipping-fields").offset().top - 50},
          'slow');
        }

    });

    $('#place_order').unbind().click(function(e) {

        e.preventDefault();



    $('#whiteout').fadeIn();

    // $.post('?wc-ajax=checkout', {}).done(function(data) {
    //  if (typeof data.messages != 'undefined') {
    //      var message = data.messages;
    //      $(message).insertBefore('#whiteout');
            // }
    //   console.log(data);
    //
    //   $('#whiteout').delay(2000).fadeOut();
    //
    //   $('html,body').animate({
            //  scrollTop: $("#customer_details").offset().top - 50
            // },'slow');
    //
    // });

    $('.checkout').submit();

    showBilling();
    showShipping();
    showPayment();

    $('html,body').animate({
      scrollTop: $("#customer_details").offset().top - 50
    },'slow');

    $('#whiteout').delay(2000).fadeOut();

    });



    ///////////////////////////////////////////////////////////////////////////////////////////////////////// BILLING EDIT
    $('#billing_edit').unbind().click(function(e) {

        e.preventDefault();
        $("#billing_continue").show();
        showBilling();
        hideShipping();
        hidePayment();
        hideReviewAndPurchase();

        $('html,body').animate({
            scrollTop: $("#customer_details .col-1").offset().top},
        'slow');

        // REMOVE EDIT BUTTON
        $('#billing_edit').fadeOut();
        $('#billing_field_review').fadeOut();
        $('#billing_field_review').empty();
        $('#shipping_field_review').empty();
        $('.payment_review_fields_left').empty();
        $('.payment_review_fields_right').empty();


    });

    $('#shipping_edit').unbind().click(function(e) {

        e.preventDefault();

        showShipping();
        hideBilling();
        hidePayment();
        hideReviewAndPurchase();

        $('html,body').animate({
            scrollTop: $("#customer_details .col-2").offset().top},
        'slow');

        // REMOVE EDIT BUTTON
        $('#shipping_edit').fadeOut();
        $('#shipping_field_review').fadeOut();
        $('#shipping_field_review').empty();
        $('.payment_review_fields_left').empty();
        $('.payment_review_fields_right').empty();


    });


    // DISABLE FORM SUBMIT ON ENTER
    $('.woocommerce-checkout').on('keyup keypress', function(e) {
      var keyCode = e.keyCode || e.which;
      if (keyCode === 13) {
        e.preventDefault();
        return false;
      }
    });

    if ($('body').hasClass('woocommerce-cart')) {
    clean_up_cart_variations();
    }

});

function clean_up_cart_variations() {

  $('.cart_item').each(function() {
    if ($(this).find('.variation').length > 0) {
      $(this).find('.cart_product_color').remove();
    }
  });

}

function hideBilling() {
    $('#billing_slide').slideUp();
//  $('#customer_details .col-1').css({'height': '80px'});
}

function hideShipping() {
    $('.woocommerce-shipping-fields__field-wrapper').slideUp();
    $('.woocommerce-checkout-review-shipping-table').slideUp();
    $('#shipping_continue').slideUp();
    $('#customer_details .col-2').delay(5000).css({'height': '80px'});
}

function hidePayment() {
    $('.wc_payment_methods').slideUp();
    $('#billing_header').slideUp();
    $('#same_as_shipping').slideUp();
    $('#same_as_shipping_text').slideUp();
    $('#billing_info').slideUp();
    $('#payment_continue').slideUp();
    $('.payment_logos').fadeOut('fast');

    $('#payment').delay(5000).css({'height': '80px'});
}

function hideReviewAndPurchase() {
    $('.review_and_purchase p').slideUp();
    $('.review_and_purchase').css({'height': '80px'});
}

function showBilling() {
    $('#billing_slide').slideDown();
}

function showShipping() {
    $('.woocommerce-shipping-fields__field-wrapper').slideDown();
    $('.woocommerce-checkout-review-shipping-table').slideDown();
    $('#shipping_continue').slideDown();
    $('#customer_details .col-2').delay(5000).css({'height': 'auto'});
}

function showPayment() {
    $('.wc_payment_methods').slideDown();
    $('#billing_header').slideDown();
    $('#same_as_shipping').slideDown();
    $('#same_as_shipping_text').slideDown();
    $('#billing_info').slideDown();
    $('#payment_continue').slideDown();
    $('.payment_logos').fadeIn('fast');

    $('#payment').delay(5000).css({'height': 'auto'});
}

function showReviewAndPurchase() {

    $('.review_and_purchase').delay(5000).css({'height': 'auto'});
    $('.review_and_purchase p').slideDown();

}

function product_swipe(swipe, elementClass, count) {

    var directionValue = swipe.swipestart.coords[0] - swipe.swipestop.coords[0];

    if (directionValue > 0) {
        var direction = 'left';
    } else {
        var direction = 'right';
    }

    var slideNumber = 0;

    $(elementClass+' ul').children().each(function(i) {

        if (!$(this).is(":hidden")) {
            slideNumber = $(this).attr('id').split('slide-')[1];
        }

    });

    console.log(direction);
    console.log(slideNumber);

    if (slideNumber == 0) {

        if (direction == 'left') {
            if (count > 2) {
                $(elementClass+' #bullet-2').css({'color': '#111'});
                $(elementClass+' #bullet-1').css({'color': '#f37f93'});
                $(elementClass+' #bullet-3').css({'color': '#f37f93'});
                $(elementClass+' #slide-0').hide();
                $(elementClass+' #slide-2').fadeIn();
                $(elementClass+' #slide-4').hide();
            }

        } else {
            return false;
        }

    } else if (slideNumber == 2) {

        if (direction == 'left') {
            if (count > 4) {
                $(elementClass+' #bullet-3').css({'color': '#111'});
                $(elementClass+' #bullet-2').css({'color': '#f37f93'});
                $(elementClass+' #bullet-1').css({'color': '#f37f93'});
                $(elementClass+' #slide-0').hide();
                $(elementClass+' #slide-2').hide();
                $(elementClass+' #slide-4').fadeIn();
            }
        } else {
            $(elementClass+' #bullet-1').css({'color': '#111'});
            $(elementClass+' #bullet-2').css({'color': '#f37f93'});
            $(elementClass+' #bullet-3').css({'color': '#f37f93'});
            $(elementClass+' #slide-0').fadeIn();
            $(elementClass+' #slide-2').hide();
            $(elementClass+' #slide-4').hide()
        }

    } else if (slideNumber == 4) {

        if (direction == 'left') {
            return false;
        } else {
            $(elementClass+' #bullet-3').css({'color': '#111'});
            $(elementClass+' #bullet-2').css({'color': '#f37f93'});
            $(elementClass+' #bullet-1').css({'color': '#f37f93'});
            $(elementClass+' #slide-0').hide();
            $(elementClass+' #slide-2').hide();
            $(elementClass+' #slide-4').fadeIn();
        }
    }
}


/* cmRD Scripts */
$(document).ready( function() {
    setTimeout( callScripts, 700);
});

function callScripts() {
    console.log("cmrd");

    /* If it is a single product page, check page for loaded image gallery */
    if ( $("body.single-product").length ) {

        var triggerScroll = ".thumbnail-scroll";
        var checkerScroll = setInterval( function() {
            if ( $(triggerScroll).length ) {
                clearInterval(checkerScroll);

                var target = ".MagicToolboxSelectorsContainer div[id^=Magic]";

                if ( !$(target).length ) {
                    target = ".mcs-items-container";
                }
                setAutoScroll( triggerScroll, target );

                /* Function not animating on first trigger
                       this emulates the first and second triggers, just to make sure
                       it works as expected.
                */
                    $( $(triggerScroll).children()[0] ).mouseenter();
                    $( $(triggerScroll).children()[0] ).mouseleave();

                    $( $(triggerScroll).children()[0] ).mouseenter();
                    $( $(triggerScroll).children()[0] ).mouseleave();
            }
        }, 200);
    }

    /* Set shipping script to force international when non United States is selected */
    if ( $("body.woocommerce-checkout").length ) {

        // Woocommerce forces my element to be a child of <p>. This undoes that.
        $(".cmrd-warning").unwrap();

        // Scroll to billing when you click on credit card button
        scrollToBilling();

        var triggerShip = $("#billing_continue");

        // Wait for the elements to finish loading before calling script
        var checkerShip = setInterval( function() {
            if ( $(triggerShip).length ) {
                clearInterval(checkerShip);

                window.countrySelect = $("#shipping_country");
                window.optSelect = $("select[id^='shipping_method']");

                var checkerShip2 = setInterval(function() {
                    if ( countrySelect.length && optSelect.length ) {
                        clearInterval(checkerShip2);
                        shippingScript();
                    }

                    window.countrySelect = $("#shipping_country");
                    window.optSelect = $("select[id^='shipping_method']");
                }, 200);
            }
        }, 200);
    }

   /* Ajax to add remove coupon functinoality to checkout and cart page */
   if ( $("body.woocommerce-checkout").length || $("body.woocommerce-cart").length ) {
        var triggerCoupon = $(".remove-coupon");

        var checkerCoupon = setInterval(function() {
            if( $(triggerCoupon).length ) {

                clearInterval(checkerCoupon);

                $(triggerCoupon).click(function(e) {
                    makeRemoveRequest(e, triggerCoupon);
                });
            }

        }, 200);

        if ($("body.woocommerce-checkout").length) {
            setInterval(patchCheckout, 500);
        }
    }
}

function makeRemoveRequest(event, element) {
    console.log("in make remove request");
    var notice = document.createElement("span");
    $(notice).text("Processing. . . ");

    $(".remove-coupon").after(notice);

    var code = $(this).data("code");
    $.ajax({
        method: "post",
        data: {
            action: 'cmrd_remove_coupon',
            code: code
        },
        url: ajax_url,
        complete: function() {
            location.reload();
        }
    });
}


/**
* @descr If a field has any kind of value, it passes. Else it is pushed into errors.
* @param string jQuery selector to target the form
* @return errors Array of inputs with no values
*/
function validateForm( form ) {
    var errors = [];
    var whiteList = [$("#shipping_address_2")[0], $("#billing_address_2")[0] ];

    var inputs = $(form + " input[type=text]," +  form + " select");

    $(inputs).each(function(idx, field) {
        if ( $(field).is(":visible") ) {

            if ( !$(field).val() ) {
                $.inArray(field, whiteList) >= 0  ? '' : errors.push(field);
            }

        }
    });

    return errors;
}

/**
* @descr Highlights a form field in red to emphasize it currently is invalid.
* @param field An HTML element
*/
function outlineRed( field ) {
    $(field).addClass("cmrd-invalid");

    $(field).on("change", function(e) {
        $(field).removeClass("cmrd-invalid");
        $(field).off(e);
    });
}

/**
* @descr Forces international shipping option if the shipping country is not United States
*/
function shippingScript() {
    //international shipping option
    var world = $(optSelect).children("option[value='woocommerce_flatrate_percountry']");

    // domestic shipping options
    var domestics = $(optSelect).children("option:not([value='woocommerce_flatrate_percountry'])");
    var wcInternational = $(optSelect).children("option[value='flat_rate:5']");

    if ( wcInternational.length ) {
        $(wcInternational).remove();
    }

    function showDomestics() {
        $(domestics).show();
        $(world).hide();

        var selected = $(optSelect).children(":selected");

        if ( $(selected).is(world) ) {
            $(selected).removeAttr("selected");
        }

        $(domestics[0]).click();
        $(optSelect).trigger("change");
        $(domestics[0]).attr("selected", "selected");
    }

    function hideDomestics() {
        $(domestics).hide();
        $(world).show();
        var selected = $(optSelect).children(":selected");

        if ( $(selected).is(domestics) ) {
            $(selected).removeAttr("selected");

        }

        $(world).click();
        $(optSelect).trigger("change");
        $(world).attr("selected", "selected");
    }

    // Ensures the required options exist
    if ( domestics.length < 2 ) {
        var opt = document.createElement("option");
        var opt2 = document.createElement("option");
        opt.value = "flat_rate:2";
        $(opt).val("flat_rate:2").text("Domestic Shipping + Tracking (arrival time: 3-5 business days after shipped): $7.50");
        $(opt2).val("flat_rate:4").text("Expedited 2 Day Shipping (Domestic Only): $25.00")

        $("#shipping_method_0").append(opt, opt2);

        domestics = $(optSelect).children("option:not([value='woocommerce_flatrate_percountry'])");

        hideDomestics();
    }


    $(countrySelect).on("change", function() {
        if ( domestics.length ) {

            var selected = $(this).children("option:selected");
            $(selected).val() === 'US' ? showDomestics() : hideDomestics();

        }

    });

    function init() {
        var country = $(countrySelect).val();

        country == 'US' ? showDomestics() : hideDomestics();
    }

    init();
}


/**
* @descr Adds an autoscroll feature on hover or click to target element
* @param String trigger jQuery selector of the element that triggers scrolling
* @param String target jQuery selector of element to get the event listener
*/
function setAutoScroll( trigger, target ) {
    if ( typeof(target) == 'undefined' ) {
        return;
    }

    window.isScrolling = false;

    $(trigger).children().on("mouseenter click", function(e) {
        e.preventDefault();

        if (e.type === "click" && isScrolling === true ) {
            $(trigger).children().off("mouseleave");
            clearInterval(scrolling);
        } else {
            window.scrolled = 0;
        }

        var maxHeight = parseInt( $(target).css("height") );

        scrolling = setInterval(function() {
            isScrolling = true;
            var property = $(target).css("transform");
            var startIdx = property.lastIndexOf(",") + 1;
            var stopIdx = property.lastIndexOf(")");

            var scrollTop = parseInt( property.slice( startIdx, stopIdx ) );

            scrollTop -= 3;
            scrolled += 3;

            var newProp = "matrix(1, 0, 0, 1, 0, " + scrollTop + ")";


            $(target).css("transform", newProp);

            if ( scrolled > maxHeight ) {
                clearInterval(scrolling);
                window.isScrolling = false;
                resetHeight(target);
            }

        }, 30);

        if (e.type === "mouseenter") {
            $(trigger).children().on("mouseleave", function() {
                clearInterval( scrolling );
                window.isScrolling = false;
                resetHeight( target );
            });
        }

    });
}

function resetHeight( target ) {
    var matrix = "matrix(1, 0, 0, 1, 0, 0)";
    var props = {
        "webkitTransform": matrix,
        "MozTransform": matrix,
        "msTransform": matrix,
        "OTransform": matrix,
        "transform": matrix
    };

    $(target).css(props);
}

function saysWho() {
    var ua= navigator.userAgent, tem,f
    M= ua.match(/(opera|chrome|safari|firefox|msie|trident(?=\/))\/?\s*(\d+)/i) || [];
    if(/trident/i.test(M[1])){
        tem=  /\brv[ :]+(\d+)/g.exec(ua) || [];
        return 'IE '+(tem[1] || '');
    }
    if(M[1]=== 'Chrome'){
        tem= ua.match(/\b(OPR|Edge)\/(\d+)/);
        if(tem!= null) return tem.slice(1).join(' ').replace('OPR', 'Opera');
    }
    M= M[2]? [M[1], M[2]]: [navigator.appName, navigator.appVersion, '-?'];
    return M.join(' ');
}

/**
* @descr Ajax to PayPal to get payment data
*/

function fetchPaypalDetails() {

    $.ajax({
        method: "post",
        data: {
            action: 'get_paypal_response',
            order: "333"
        },
        url: ajax_url,
        complete: (data) => console.log( data )
    });
}

function patchCheckout() {
    var parent = $(".checkout_and_cart_featured_image");

    var target = $(parent).css("background-image").replace(/^url\(["']?/, '').replace(/["']?\)$/, '');

    if ( target.indexOf("juju") === -1 ) {
        $(parent).attr("style", "background: url(https://katiedeanjewel.staging.wpengine.com/wp-content/uploads/2017/09/badjujuimage.jpg)");
        $(parent).html(`
            <span class="checkout_and_cart_featured_text">Protect yourself from all the bad juju with the Evil Eye Ring!</span><br>
                <a href="https://katiedeanjewel.staging.wpengine.com/shop/rings/evil-eye-ring-2/" class="cta" id="featured_checkout_item" data-product-id="2692">ADD TO CART</a>
        `);
    }
}

function scrollToBilling() {
    var cc = $("#cmrd-payment-methods");
    var target = $("#wc-billing-fields");

    $(cc).click(function(e) {
        $("html, body").animate({
            scrollTop: $(target).offset().top
        }, 1000);
    });
}


