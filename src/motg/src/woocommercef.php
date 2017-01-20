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

		// Set the maxinum number of products before checking out
		$maxinum_num_products = limit();
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
    $ticketTypes = build_ticket_array_name();

    for ($i = 1; $i <= $cart_num_products; $i++) {
      $label = 'Guest '.$i.'\'s Full Name <abbr class="required" title="required">*</abbr> ('.$ticketTypes[$i].')';
      $classes = array(
        'my-field-class form-row-wide',
        'validate-required',
        'woocommerce-invalid',
        'woocommerce-invalid-required-field'
      );
      woocommerce_form_field( 'guest_name_'.$i, array(
      'type'          => 'text',
      'class'         => $classes,
      'label'         => __($label),
      'placeholder'   => __(''),
      ), $checkout->get_value( 'guest_name_'.$i ));

    }

    echo '</div>';

}
add_action( 'woocommerce_after_order_notes', 'my_custom_checkout_field' );

// checks the guest name field is checked
function my_custom_checkout_field_process() {
    // Check if set, if its not set add an error.
    $cart_num_products = WC()->cart->cart_contents_count;

    for ($i = 1; $i <= $cart_num_products; $i++) {
      if ( ! $_POST['guest_name_'.$i] ){
        wc_add_notice( __( '<b>Guest '.$i.'\'s full name</b> is a required field.' ), 'error' );
      }
    }

}
add_action('woocommerce_checkout_process', 'my_custom_checkout_field_process');

//save guest name as meta with the ticket
function my_custom_checkout_field_update_order_meta( $order_id ) {
  for ($i = 1; $i <= 4; $i++) {
    $string = 'guest_name_'.$i;
    if ( ! empty( $_POST[$string] ) ) {
      update_post_meta( $order_id, $string, sanitize_text_field( $_POST[$string] ) );
    }
  }
}
add_action( 'woocommerce_checkout_update_order_meta', 'my_custom_checkout_field_update_order_meta' );


function my_custom_checkout_field_display_admin_order_meta($order){

  echo '<p><strong>'.__('My Field').':</strong> ' . get_post_meta( $order->id, 'My Field', true ) . '</p>';

}
add_action( 'woocommerce_admin_order_data_after_billing_address', 'my_custom_checkout_field_display_admin_order_meta', 10, 1 );


function build_ticket_array_name(){
  $array = [];
  foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
    $_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

    if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
      for ($i = 0; $i <= $cart_item['quantity']; $i++) {
        array_push($array, $_product->get_title());
      }
    }
  }
  return  $array;
}


function add_ticket_information($order){
  $array = [];
  foreach( $order->get_items() as $item_id => $item ) {
    $product = apply_filters( 'woocommerce_order_item_product', $order->get_product_from_item( $item ), $item );
    for ($i = 1; $i <= $item['qty']; $i++) {
      array_push($array, $item['name']);
    }
  }

  echo '</table>';
  echo '<header><h2>Ticket Information</h2></header>';
  echo '<table class="shop_table customer_details">';

  //print_r($order);
  for ($i = 1; $i <= 4; $i++) {
    $sting = 'guest_name_'.$i;
    $name = get_post_meta( $order->id, $sting, true );
    if($name){
      echo '<tr><th>'.$array[$i-1].' for:</th>';
      echo '<td>'.$name.'</td></tr>';
    }
  }
	echo '</tr>';
}
add_action( 'woocommerce_order_details_after_customer_details', 'add_ticket_information', 10, 1 );


function limit(){
  $cal = 0;

  $customer_orders = get_posts( array(
    'numberposts' => -1,
    'meta_key'    => '_customer_user',
    'meta_value'  => get_current_user_id(),
    'post_type'   => wc_get_order_types(),
    'post_status' => array_keys( wc_get_order_statuses() ),
  ) );
  //print_r($customer_orders);
  foreach( $customer_orders as $order_id => $order ) {
    //print_r($item);
    //echo $item->ID.'--';
    $ord = wc_get_order( $order->ID );
    foreach( $ord->get_items() as $item_id => $item ) {
      //echo $item['qty']."--";
      $cal = $cal + $item['qty'];
    }
  }
  return $cal;
}

//https://www.skyverge.com/blog/get-all-woocommerce-orders-for-a-customer/

function sv_disable_repeat_purchase( $purchasable, $product ) {
  $cal = limit();

  if($cal > 4){
    $purchasable = false;
  }

  return $purchasable;
}
add_filter( 'woocommerce_variation_is_purchasable', 'sv_disable_repeat_purchase', 10, 2 );
add_filter( 'woocommerce_is_purchasable', 'sv_disable_repeat_purchase', 10, 2 );

//the following will need to be modifyed

// function sv_purchase_disabled_message() {
//     // Enter the ID of the product that shouldn't be purchased again
//     $no_repeats_id = 16;
//     $no_repeats_product = wc_get_product( $no_repeats_id );
//
//     // Get the current product to check if purchasing should be disabled
//     global $product;
//
//     if ( $no_repeats_product->is_type( 'variation' ) ) {
//         // Bail if we're not looking at the product page for the non-purchasable product
//         if ( ! $no_repeats_product->parent->id === $product->id ) {
//             return;
//         }
//
//         // Render the purchase restricted message if we are
//         if ( wc_customer_bought_product( wp_get_current_user()->user_email, get_current_user_id(), $no_repeats_id ) ) {
//             sv_render_variation_non_purchasable_message( $product, $no_repeats_id );
//         }
//
//     } elseif ( $no_repeats_id === $product->id ) {
//         if ( wc_customer_bought_product( wp_get_current_user()->user_email, get_current_user_id(), $no_repeats_id ) ) {
//             // Create your message for the customer here
//             echo '<div class="woocommerce"><div class="woocommerce-info wc-nonpurchasable-message">You\'ve already purchased this product! It can only be purchased once.</div></div>';
//         }
//     }
// }
// add_action( 'woocommerce_single_product_summary', 'sv_purchase_disabled_message', 31 );

?>
