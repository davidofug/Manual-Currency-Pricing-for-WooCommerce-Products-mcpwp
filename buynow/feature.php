<?php
// Add custom "Buy Now" button to product page
function custom_buy_now_button() {
    global $product;
    $product_id = $product->get_id();
    $product_permalink = get_permalink( $product_id );
    echo '<a href="' . $product_permalink . '?buy_now=1" class="button buy-now">Buy Now</a>';
}
add_action( 'woocommerce_single_product_summary', 'custom_buy_now_button', 30 );

// Redirect to payment page on "Buy Now" button click
function custom_buy_now_redirect() {
    if ( isset( $_GET['buy_now'] ) ) {
        $product_id = get_the_ID();
        $product = wc_get_product( $product_id );
        $product_price = $product->get_price();
        $checkout_url = WC()->cart->get_checkout_url();
        $checkout_url = add_query_arg( 'buy_now', $product_id, $checkout_url );
        wp_redirect( $checkout_url );
        exit;
    }
}
add_action( 'template_redirect', 'custom_buy_now_redirect' );

// Set product as pre-selected on payment page when clicked "Buy Now"
function custom_buy_now_set_product( $posted ) {
    if ( isset( $_GET['buy_now'] ) ) {
        $product_id = $_GET['buy_now'];
        $product = wc_get_product( $product_id );
        $posted['add-to-cart'] = $product_id;
        WC()->cart->empty_cart();
        WC()->cart->add_to_cart( $product_id );
    }
    return $posted;
}
add_filter( 'woocommerce_add_to_cart_validation', 'custom_buy_now_set_product' );
