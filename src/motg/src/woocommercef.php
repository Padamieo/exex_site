<?php

add_action( 'after_setup_theme', 'woocommerce_support' );
function woocommerce_support() {
  add_theme_support( 'woocommerce' );
}

add_filter('woocommerce_sale_flash', 'owoo_custom_hide_sales_flash');
function owoo_custom_hide_sales_flash(){
  return '<div class="early">Early Bird Price</div>';
}

// add_filter( 'woocommerce_page_title', 'woo_shop_page_title');
// function woo_shop_page_title( $page_title ) {
//   if( 'Shop' == $page_title) {
//     return "My new title";
//   }
// }

add_filter( 'woocommerce_product_tabs', 'wcs_woo_remove_reviews_tab', 98 );
  function wcs_woo_remove_reviews_tab($tabs) {
  unset($tabs['reviews']);
  return $tabs;
}

// Remove the sorting dropdown from Woocommerce
remove_action( 'woocommerce_before_shop_loop' , 'woocommerce_catalog_ordering', 30 );
// Remove the result count from WooCommerce
remove_action( 'woocommerce_before_shop_loop' , 'woocommerce_result_count', 20 );

function custom_my_account_menu_items( $items ) {
  $items = array(
    'dashboard'         => __( 'Dashboard', 'woocommerce' ),
    'orders'            => __( 'Orders', 'woocommerce' ),
    'edit-address'      => __( 'Addresses', 'woocommerce' ),
    'edit-account'      => __( 'Edit Account', 'woocommerce' ),
    'customer-logout'   => __( 'Logout', 'woocommerce' ),
  );
  return $items;
}
add_filter( 'woocommerce_account_menu_items', 'custom_my_account_menu_items' );

// function so_27030769_maybe_empty_cart( $valid, $product_id, $quantity ) {
//   if( ! empty ( WC()->cart->get_cart() ) && $valid ){
//     WC()->cart->empty_cart();
//     wc_add_notice( 'Whoa hold up. You can only have 1 item in your cart', 'error' );
//   }
//   return $valid;
// }
// add_filter( 'woocommerce_add_to_cart_validation', 'so_27030769_maybe_empty_cart', 10, 3 );

// function my_custom_add_to_cart_redirect( $url ) {
// 	$url = WC()->get_cart_url();
// 	// $url = wc_get_checkout_url(); // since WC 2.5.0
// 	return $url;
// }
// add_filter( 'woocommerce_add_to_cart_redirect', 'my_custom_add_to_cart_redirect' );

add_filter( 'woocommerce_checkout_fields' , 'alter_woocommerce_checkout_fields' );
function alter_woocommerce_checkout_fields( $fields ) {
  unset($fields['billing']['billing_company']); // remove the option to enter in a company
  return $fields;
}


// Set a minimum number of products requirement before checking out
add_action( 'woocommerce_check_cart_items', 'spyr_set_min_num_products' );
function spyr_set_min_num_products() {
	// Only run in the Cart or Checkout pages
	if( is_cart() || is_checkout() ) {
		global $woocommerce;

		// Set the minimum number of products before checking out
		$minimum_num_products = 4;
		// Get the Cart's total number of products
		$cart_num_products = WC()->cart->cart_contents_count;

		// Compare values and add an error is Cart's total number of products
	    // happens to be less than the minimum required before checking out.
		// Will display a message along the lines of
		// A Minimum of 20 products is required before checking out. (Cont. below)
		// Current number of items in the cart: 6
		if( $cart_num_products > $minimum_num_products ) {
			// Display our error message
	        wc_add_notice( sprintf( '<strong>A Minimum of %s products is required before checking out.</strong>'
          . '<br />Current number of items in the cart: %s.',
	        	$minimum_num_products,
	        	$cart_num_products ),
	        'error' );
		}
	}
}


/**
 * Add the field to the checkout
 */
add_action( 'woocommerce_after_order_notes', 'my_custom_checkout_field' );

function my_custom_checkout_field( $checkout ) {

    echo '<div id="my_custom_checkout_field"><h2>' . __('Ticket Information') . '</h2>';

    $cart_num_products = WC()->cart->cart_contents_count;

    for ($i = 1; $i <= $cart_num_products; $i++) {
      woocommerce_form_field( 'my_field_name', array(
      'type'          => 'text',
      'class'         => array('my-field-class form-row-wide'),
      'label'         => __('Guest '.$i.' Full Name'),
      'placeholder'   => __('Enter something'),
      ), $checkout->get_value( 'my_field_name' ));
    }

    echo '</div>';

}


?>
