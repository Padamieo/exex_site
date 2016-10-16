<?php

// add_action( 'after_setup_theme', 'woocommerce_support' );
// function woocommerce_support() {
//     add_theme_support( 'woocommerce' );
// }

add_filter('woocommerce_sale_flash', 'owoo_custom_hide_sales_flash');
function owoo_custom_hide_sales_flash()
{
    return '<div class="early">Early Bird Price</div>';
}

add_filter( 'woocommerce_page_title', 'woo_shop_page_title');
function woo_shop_page_title( $page_title ) {
  if( 'Shop' == $page_title) {
    return "My new title";
  }
}

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


function so_27030769_maybe_empty_cart( $valid, $product_id, $quantity ) {
  if( ! empty ( WC()->cart->get_cart() ) && $valid ){
    WC()->cart->empty_cart();
    wc_add_notice( 'Whoa hold up. You can only have 1 item in your cart', 'error' );
  }
  return $valid;
}
add_filter( 'woocommerce_add_to_cart_validation', 'so_27030769_maybe_empty_cart', 10, 3 );

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
?>
