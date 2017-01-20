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


// Set a maximum number of products requirement before checking out
add_action( 'woocommerce_check_cart_items', 'spyr_set_min_num_products' );
function spyr_set_min_num_products() {
	// Only run in the Cart or Checkout pages
	if( is_cart() || is_checkout() ) {
		global $woocommerce;

		// Set the minimum number of products before checking out
		$maxinum_num_products = 4;
		// Get the Cart's total number of products
		$cart_num_products = WC()->cart->cart_contents_count;

		// Compare values and add an error is Cart's total number of products
	    // happens to be less than the minimum required before checking out.
		// Will display a message along the lines of
		// A Minimum of 20 products is required before checking out. (Cont. below)
		// Current number of items in the cart: 6
		if( $cart_num_products > $maxinum_num_products ) {
			// Display our error message
	        wc_add_notice( sprintf( '<strong>A Maximum of %s tickets are available from this site per customer.</strong>'
          . '<br />Current number of items in the cart: %s.',
	        	$maxinum_num_products,
	        	$cart_num_products ),
	        'error' );
		}
	}
}


/// add additional input to checkout form

function my_custom_checkout_field( $checkout ) {

    echo '<div id="my_custom_checkout_field"><h2>' . __('Ticket Information') . '</h2>';

    $cart_num_products = WC()->cart->cart_contents_count;

    for ($i = 1; $i <= $cart_num_products; $i++) {

      woocommerce_form_field( 'my_field_name'.$i, array(
      'type'          => 'text',
      'class'         => array('my-field-class form-row-wide', 'validate-required
      woocommerce-invalid
      woocommerce-invalid-required-field'),
      'label'         => __('Guest '.$i.'\'s Full Name <abbr class="required" title="required">*</abbr>'),
      'placeholder'   => __(''),
      ), $checkout->get_value( 'my_field_name'.$i ));

    }

    echo '</div>';

}
add_action( 'woocommerce_after_order_notes', 'my_custom_checkout_field' );

// checks the guest name field is checked
function my_custom_checkout_field_process() {
    // Check if set, if its not set add an error.
    $cart_num_products = WC()->cart->cart_contents_count;

    for ($i = 1; $i <= $cart_num_products; $i++) {
      if ( ! $_POST['my_field_name'.$i] ){
        wc_add_notice( __( '<b>Guest '.$i.'\'s full name</b> is a required field.' ), 'error' );
      }
    }

}
add_action('woocommerce_checkout_process', 'my_custom_checkout_field_process');


function my_custom_checkout_field_update_order_meta( $order_id ) {

    if ( ! empty( $_POST['my_field_name1'] ) ) {
        update_post_meta( $order_id, 'My Field', sanitize_text_field( $_POST['my_field_name1'] ) );
    }
}
add_action( 'woocommerce_checkout_update_order_meta', 'my_custom_checkout_field_update_order_meta' );


function my_custom_checkout_field_display_admin_order_meta($order){
  echo '<p><strong>'.__('My Field').':</strong> ' . get_post_meta( $order->id, 'My Field', true ) . '</p>';
}
add_action( 'woocommerce_admin_order_data_after_billing_address', 'my_custom_checkout_field_display_admin_order_meta', 10, 1 );


function sv_disable_repeat_purchase( $purchasable, $product ) {
    // Enter the ID of the product that shouldn't be purchased again
    $non_purchasable = 356;

    // Get the ID for the current product (passed in)
    $product_id = $product->is_type( 'variation' ) ? $product->variation_id : $product->id;

    // Bail unless the ID is equal to our desired non-purchasable product
    if ( $non_purchasable != $product_id ) {
      return $purchasable;
    }

    // return false if the customer has bought the product
    if ( wc_customer_bought_product( wp_get_current_user()->user_email, get_current_user_id(), $product_id ) ) {
      $purchasable = false;
    }

    // Double-check for variations: if parent is not purchasable, then variation is not
    if ( $purchasable && $product->is_type( 'variation' ) ) {
      $purchasable = $product->parent->is_purchasable();
    }

    return $purchasable;
}
add_filter( 'woocommerce_variation_is_purchasable', 'sv_disable_repeat_purchase', 10, 2 );
add_filter( 'woocommerce_is_purchasable', 'sv_disable_repeat_purchase', 10, 2 );

?>
