<?php

//removes notice for custom woocommerce theme
add_action( 'after_setup_theme', 'woocommerce_support' );
function woocommerce_support() {
  add_theme_support( 'woocommerce' );
}

// add our global variable for purchase limit,
//TODO: this could be moved out to user meta later for per user basis
function per_customer_limit() {
    return 4;
}
add_action( 'after_theme_setup', 'per_customer_limit' );


add_filter('woocommerce_sale_flash', 'owoo_custom_hide_sales_flash');
function owoo_custom_hide_sales_flash(){
  return '<div class="early">Early Bird Price</div>';
}

// removes the review tabs per product
  function remove_reviews_tab($tabs) {
  unset($tabs['reviews']);
  return $tabs;
}
add_filter( 'woocommerce_product_tabs', 'remove_reviews_tab', 98 );

// Remove the sorting dropdown from Woocommerce
remove_action( 'woocommerce_before_shop_loop' , 'woocommerce_catalog_ordering', 30 );
// Remove the result count from WooCommerce
remove_action( 'woocommerce_before_shop_loop' , 'woocommerce_result_count', 20 );

//changes the listing on the users dashboard
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

// removes the additional fields for company
add_filter( 'woocommerce_checkout_fields' , 'alter_woocommerce_checkout_fields' );
function alter_woocommerce_checkout_fields( $fields ) {
  unset($fields['billing']['billing_company']); // remove the option to enter in a company
  return $fields;
}

// Set a maximum number of products requirement before checking out
function set_max_num_purchases() {
	if( is_cart() || is_checkout() ) {
		global $woocommerce;
    $per_customer_limit = per_customer_limit();

		$maxinum_num_products = limit();

		$cart_num_products = WC()->cart->cart_contents_count;

		if( $cart_num_products > $maxinum_num_products ) {
			// Display our error message

      if($maxinum_num_products)

        wc_add_notice( sprintf( '<strong>A Maximum of %s tickets are available from this site per customer.</strong>'
        . '<br />Current number of items in the cart: %s.',
        	$per_customer_limit,
        	$cart_num_products ),
        'error' );

		}
	}
}
add_action( 'woocommerce_check_cart_items', 'set_max_num_purchases' );


/// add additional input to checkout form

function my_custom_checkout_field( $checkout ) {

    echo '<div id="my_custom_checkout_field"><h2>' . __('Ticket Information') . '</h2>';

    $cart_num_products = WC()->cart->cart_contents_count;
    $ticketTypes = build_ticket_array_name('cart');

    for ($i = 1; $i <= $cart_num_products; $i++) {

      $guest = 'Guest '.$i;
      $label = $guest.' Full Name <abbr class="required" title="required">*</abbr> ('.$ticketTypes[$i].')';
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

      woocommerce_form_field( 'guest_'.$i.'_meal', array(
      'type'          => 'select',
      'class'         => array('my-field-class form-row-wide'),
      'label'         => __($guest." Dietary Requirments"),
      'placeholder'   => __(''),
      'options'       => array(
        'standard' => __('None', 'woocommerce' ),
        'vegitarian' => __('Vegitarian', 'woocommerce' ),
        'vegan' => __('Vegan', 'woocommerce' ),
        'allergies' => __('Allergies (please specify in Order Notes)', 'woocommerce' ),
        'other' => __('Other (please specify in Order Notes)', 'woocommerce' )
      )
    ), $checkout->get_value( 'guest_'.$i.'_meal' ));

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
  $per_customer_limit = per_customer_limit();
  for ($i = 1; $i <= $per_customer_limit; $i++) {
    $name_term = 'guest_name_'.$i;
    if ( ! empty( $_POST[$name_term] ) ) {
      update_post_meta( $order_id, $name_term, sanitize_text_field( $_POST[$name_term] ) );
    }
    $dietary_term = 'guest_'.$i.'_meal';
    if ( ! empty( $_POST[$dietary_term] ) ) {
      update_post_meta( $order_id, $dietary_term, sanitize_text_field( $_POST[$dietary_term] ) );
    }
  }
}
add_action( 'woocommerce_checkout_update_order_meta', 'my_custom_checkout_field_update_order_meta' );

// tickets sent to notice
function ticket_sent_to_notice(){
  $cart_num_products = WC()->cart->cart_contents_count;
  if($cart_num_products > 1){
    echo '<div class="woocommerce-message"><b>All Guest Tickets</b> will be sent to the billing address unless specified otherwise in <b>Order Notes<b>.</div>';
  }
}
add_action('woocommerce_checkout_before_customer_details', 'ticket_sent_to_notice');

//show the guest info to us, admins
function guest_info_admin_display($order){
  $per_customer_limit = per_customer_limit();
  $array = build_ticket_array_name($order);
  for ($i = 1; $i <= $per_customer_limit; $i++) {
    $name_term = 'guest_name_'.$i;
    $dietary_term = 'guest_'.$i.'_meal';
    $name = get_post_meta( $order->id, $name_term, true );
    $dietary = get_post_meta( $order->id, $dietary_term, true );
    if($name){
      echo '<p><strong>'.__('Guest Name').':</strong> ' . $name. '</br>';
      echo 'Dietary Request: '.$dietary. '</br>';
      echo 'Ticket: '.$array[$i-1]. '</br>';
      echo '</p>';
    }
  }
}
add_action( 'woocommerce_admin_order_data_after_billing_address', 'guest_info_admin_display', 10, 1 );

// build array of tickets purchased
function build_ticket_array_name($order){
  $array = [];
  if($order == 'cart'){
    foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
      $_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
      if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
        for ($i = 0; $i <= $cart_item['quantity']; $i++) {
          array_push($array, $_product->get_title());
        }
      }
    }
  }else{
    foreach( $order->get_items() as $item_id => $item ) {
      $product = apply_filters( 'woocommerce_order_item_product', $order->get_product_from_item( $item ), $item );
      for ($i = 1; $i <= $item['qty']; $i++) {
        array_push($array, $item['name']);
      }
    }
  }
  return  $array;
}

//on order detail list each guest ticket info
function add_ticket_information($order){
  $per_customer_limit = per_customer_limit();
  $array = build_ticket_array_name($order);

  echo '</table>';
  echo '<header><h2>Ticket Information</h2></header>';
  echo '<table class="shop_table customer_details">';

  echo '<tr><th>Type</th><th>Name</th><th>Dietary Request</th></tr>';
  for ($i = 1; $i <= $per_customer_limit; $i++) {
    $name_term = 'guest_name_'.$i;
    $name = get_post_meta( $order->id, $name_term, true );
    $dietary_term = 'guest_'.$i.'_meal';
    $dietary = get_post_meta( $order->id, $dietary_term, true );
    if($name){
      echo '<td>'.$array[$i-1].'</td>';
      echo '<td>'.$name.'</td>';
      echo '<td>'.$dietary.'</td>';
    }
  }
	echo '</tr>';
}
add_action( 'woocommerce_order_details_after_customer_details', 'add_ticket_information', 10, 1 );


// removes the order again button
if ( ! function_exists( 'woocommerce_order_again_button' ) ) {
	function woocommerce_order_again_button( $order ) {
		return;
	}
}

// finds out how many tickets this user can purchase
function limit(){
  $per_customer_limit = per_customer_limit();
  if(get_current_user_id() != 0){
    $cal = 0;

    $customer_orders = get_posts( array(
      'numberposts' => -1,
      'meta_key'    => '_customer_user',
      'meta_value'  => get_current_user_id(),
      'post_type'   => wc_get_order_types(),
      'post_status' => array_keys( wc_get_order_statuses() ),
    ) );
    foreach( $customer_orders as $order_id => $order ) {
      $ord = wc_get_order( $order->ID );
      foreach( $ord->get_items() as $item_id => $item ) {
        $cal = $cal + $item['qty'];
      }
    }
  }else{
    $cal = $per_customer_limit;
  }
  return $cal;

}

//decides if the user can purchase based on previouse purchases and global limit
function define_if_user_can_purchase( $purchasable, $product ) {
  $per_customer_limit = per_customer_limit();
  $cal = limit();
  if($cal > $per_customer_limit){
    $purchasable = false;
  }
  return $purchasable;
}
add_filter( 'woocommerce_variation_is_purchasable', 'define_if_user_can_purchase', 10, 2 );
add_filter( 'woocommerce_is_purchasable', 'define_if_user_can_purchase', 10, 2 );

//https://www.skyverge.com/blog/get-all-woocommerce-orders-for-a-customer/

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
