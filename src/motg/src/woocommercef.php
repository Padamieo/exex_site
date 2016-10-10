<?php

add_filter('woocommerce_sale_flash', 'owoo_custom_hide_sales_flash');
function owoo_custom_hide_sales_flash()
{
    return '<div>boop</div>';
}

add_filter( 'woocommerce_page_title', 'woo_shop_page_title');
function woo_shop_page_title( $page_title ) {
  if( 'Shop' == $page_title) {
    return "My new title";
  }
}
?>
