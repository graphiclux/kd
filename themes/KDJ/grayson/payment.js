$=jQuery;

function initialize() {

	console.log('initialized');

  clean_up_cart_variations();

	// amazonCheckout = getUrlParameter('amazon_payments_advanced');
  if ($('#amazon_addressbook_widget').length > 0) {
    var amazonElement = $('.wc-amazon-checkout-message');
    // $('#billing_header').prepend(amazonElement);
    $('.checkout_left #customer_details').hide();
    $('.cart_discount_code').hide();

    ///// DON'T HIDE WITH THESE, IT HIDES AMAZON
    // $('.col-1').hide();
    // $('.col-2').hide();

    $('#payment').hide();
    // $('#billing_header').hide();
    // $('#same_as_shipping_wrapper').hide();
    // $('#payment_continue').hide();
    // showPayment();
    showReviewAndPurchase();
  } else {

    // MOVE THE AMAZON AND PAYPAL BUTTONS VIA SUBPAR WAY BECAUSE I COULDN'T FIGURE OUT HOW TO HOOK INTO THEM

    if ($('#billing_header .wc-amazon-checkout-message.wc-amazon-payments-advanced-populated').length === 0) {
      console.log('here');
      var amazonElement = $('.wc-amazon-checkout-message');
      $('#billing_header').prepend(amazonElement);
      $(amazonElement).fadeIn();
    }

    hidePayment();

	}

  if ( $(".remove-coupon").length ) {
      $(".remove-coupon").click(function() {
          var code = $(this).data("code");
          $.ajax({
              method: "post",
              data: {
                  action: 'cmrd_remove_coupon',
                  code: code
              },
              url: ajax_url,
              complete: location.reload()
          });
      });
  }


  if ($('#billing_header .express_checkout_button_chekout_page').length === 0) {
    var paypalElement = $('.express_checkout_button_chekout_page');
    $('#billing_header').prepend(paypalElement);
    $(paypalElement).fadeIn();
  }




  if ($(window).width() < 1046) {

    // DISCOUNT CODE MOVE TO TOP ON MOBILE

		if ($('.checkout_left .cart_discount_code').length === 0) {
      $('.cart_discount_code.skin').insertBefore('.checkout_left');
		}
  }






  $('#stripe-card-number').attr("placeholder", "Card Number");

// SAME AS SHIPPING
  $("#same_as_shipping").change(function() {
    if(this.checked) {
      $('#payment #billing_first_name').val($('#shipping_first_name').val());
      $('#billing_last_name').val($('#shipping_last_name').val());
      $('#billing_address_1').val($('#shipping_address_1').val());
      $('#billing_address_2').val($('#shipping_address_2').val());
      $('#billing_city').val($('#shipping_city').val());
      $('#billing_state').val($('#shipping_state').val());
      $('#billing_postcode').val($('#shipping_postcode').val());
      $('#billing_country').val($('#shipping_country').val());
      $('#billing_phone').val($('#shipping_phone').val());

      $('#billing_info').slideUp();
    } else {
      $('#payment #billing_first_name').val('');
      $('#billing_last_name').val('');
      $('#billing_address_1').val('');
      $('#billing_address_2').val('');
      $('#billing_city').val('');
      $('#billing_state').val('');
      $('#billing_postcode').val('');
      $('#billing_country').val('');
      $('#billing_phone').val('');

      $('#billing_info').slideDown();
    }
  });




  $('#payment_continue').unbind().click(function() {

    if (($('#stripe-card-number').val() != "") && ($('#stripe-card-expiry').val() != "") && ($('#stripe-card-csv').val() != "")) {

      hidePayment();
      showReviewAndPurchase();

      // ADD EDIT BUTTON
      $('#payment_edit').fadeIn();

      $('#payment').css({'height': 'auto'});

      // ADD FIELD REVIEW AREA
      var billingName = '<p>' + $('#payment #billing_first_name').val() + ' ' + $('#payment #billing_last_name').val() + '</p>';
      var billingAddress1 = '<p>' + $('#billing_address_1').val() + '</p>';
      var billingAddress2 = '<p>' + $('#billing_address_2').val() + '</p>';
      var billingCityStateAndZip = '<p>' + $('#billing_city').val() + ', ' + $('#billing_state').val() + ' ' + $('#billing_postcode').val() + '</p>';
      var billingCountry = '<p>' + $('#billing_country').val() + '</p>';

      if (billingAddress2 == '<p></p>') {
        var paymentReviewFieldsLeft = billingName + billingAddress1 + billingCityStateAndZip + billingCountry;
      } else {
        var paymentReviewFieldsLeft = billingName + billingAddress1 + billingAddress2 + billingCityStateAndZip + billingCountry;
      }


      var cc_num = $('#stripe-card-number').val();
      var cc_len = cc_num.length;
      var hider = '*';
      var _numField = hider.repeat(cc_len - 4) + cc_num.slice(-4);
      var billingCardNumber = '<p>' + _numField + '</p>';


      if (typeof document.getElementById('stripe-card-number').className.split(/\s+/)[3] != 'undefined') {

        if ($('#stripe-card-expiry').val().substr(0, 2) == 42) {
          var billingCardCompany = "<p>" + document.getElementById('stripe-card-number').className.split(/\s+/)[2].ucfirst() + "</p>";
        } else {
          var billingCardCompany = "<p>" + document.getElementById('stripe-card-number').className.split(/\s+/)[3].ucfirst() + "</p>";
        }

      } else {
        var billingCardCompany = "<p></p>";
      }

      var billingCardExp = '<p>Exp. ' + $('#stripe-card-expiry').val() + '</p>';

      var paymentReviewFieldsRight = billingCardNumber + billingCardCompany + billingCardExp;

      var paymentFieldReviewWrapper = $('#payment_field_review');

      $(paymentReviewFieldsLeft).appendTo($('.payment_review_fields_left'));
      $(paymentReviewFieldsRight).appendTo($('.payment_review_fields_right'));

      $('html,body').animate({
            scrollTop: $(".review_and_purchase").offset().top},
          'slow');

      $('#payment_field_review').fadeIn();

    } else {

      alert('Please finish filling out your billing information before you can proceed.');

      $('html,body').animate({
            scrollTop: $("#payment").offset().top - 50},
          'slow');

    }

  });


  $('#payment_edit').unbind().click(function(e) {

    e.preventDefault();

    showPayment();
    hideBilling();
    hideShipping();
    hideReviewAndPurchase();

    $('html,body').animate({
          scrollTop: $("#payment").offset().top},
        'slow');

    // REMOVE EDIT BUTTON
    $('#payment_edit').fadeOut();
    $('#payment_field_review').fadeOut();
    $('.payment_review_fields_left').empty();
    $('.payment_review_fields_right').empty();


  });



  String.prototype.ucfirst = function()
  {
    return this.charAt(0).toUpperCase() + this.substr(1);
  }


  $('#featured_checkout_item').unbind().click(function(e) {
    e.preventDefault();
    woo_add_to_cart();
  });


  $('input[name="apply_coupon"]').click(function(e){
    e.preventDefault();
    apply_coupon($(this));
  });

}



// AJAX DISCOUNT CODE
function apply_coupon(element) {

  var coupon = $('#coupon_code').val();

  $.post('?wc-ajax=apply_coupon', {security: localized_config.coupon_nonce,coupon_code : coupon}).done(function(data) {

    if ( data.indexOf( 'success' ) ) {
        var removeBtn = document.createElement("button");
        removeBtn.className = "remove-button";
        $(removeBtn).attr({
            "data-code": coupon,
            "class": "remove-button"
        });

        $(removeBtn).on("click", function(e) {
            e.preventDefault();
            var code = $(this).data("code");
                $.ajax({
                    method: "post",
                    data: {
                        action: 'cmrd_remove_coupon',
                        code: code
                    },
                    url: ajax_url,
                    complete: location.reload()
                });
        });

        $(".coupon form form-row-last").after(removeBtn);
    }

    $('.woocommerce-message').fadeOut();
    $('.woocommerce-error').fadeOut();

    $('header').append(data);

    // LOADS AJAX TO REFRESH THE WOOCOMMERCE TOTALS
    $( 'body' ).trigger( 'update_checkout' );

    $(element).closest('.cart_discount_code').fadeOut('slow');
    $(element).closest('.cart_discount_code').remove();

  });

}

function woo_add_to_cart() {

  var productID = $('#featured_checkout_item').data('product-id');

  $.post('?wc-ajax=add_to_cart', {product_id : productID, quantity: 1}).done(function(data) {

    $('.woocommerce-message').fadeOut();
    $('.woocommerce-error').fadeOut();

    $('header').prepend(data);

    // LOADS AJAX TO REFRESH THE WOOCOMMERCE TOTALS
    $( 'body' ).trigger( 'update_checkout' );

    $(element).closest('.cart_discount_code').fadeOut('slow');
    $(element).closest('.cart_discount_code').remove();

  });
}

function getUrlParameter(sParam) {
  var sPageURL = decodeURIComponent(window.location.search.substring(1)),
      sURLVariables = sPageURL.split('&'),
      sParameterName,
      i;

  for (i = 0; i < sURLVariables.length; i++) {
    sParameterName = sURLVariables[i].split('=');

    if (sParameterName[0] === sParam) {
      return sParameterName[1] === undefined ? true : sParameterName[1];
    }
  }
};
