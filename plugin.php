<?php
/**
* Plugin Name: Manual Currency Pricing for WooCommerce Products
*/


// Add the manual currency pricing field to product post type
function add_manual_currency_pricing_field() {
	global $woocommerce, $post;

	echo '<div class="options_group">';

		woocommerce_wp_select( array(
			'id' => '_manual_currency[]',
			'label' => __( 'Currency', 'woocommerce' ),
			'options' => array(
				'USD' => 'US Dollars',
				'EUR' => 'Euros',
				'GBP' => 'British Pounds',
				'UGX' => 'Uganda Shillings',
				'KSh' => 'Kenyan Shillings',
				// Add more currency options here as needed
			 )
			) );
	
	woocommerce_wp_text_input(
		array(
			'id'          => '_manual_currency_price[]',
			'label'       => __( 'Price', 'woocommerce' ),
			'description' => '',
			'desc_tip'    => true,
			'class'       => 'wc_input_price',
			'type'        => 'text',
			'default'     => '',
		)
	);

	echo '</div>';
}
add_action( 'woocommerce_product_options_general_product_data', 'add_manual_currency_pricing_field' );

// Save manual currency pricing data on product save
function save_manual_currency_pricing_field( $post_id ) {
	$product = wc_get_product( $post_id );

	$currency_pricing = isset( $_POST['_custom_currency_pricing'] ) ? $_POST['_custom_currency_pricing'] : '';

	$currency_pricing_data = array();

	foreach ( $currency_pricing as $currency_price ) {
		$currency_code = isset( $currency_price['currency'] ) ? sanitize_text_field( $currency_price['currency'] ) : '';
		$price = isset( $currency_price['price'] ) ? sanitize_text_field( $currency_price['price'] ) : '';

		if ( $currency_code && $price ) {
			$currency_pricing_data[ $currency_code ] = $price;
		}
	}

	$product->update_meta_data( '_custom_currency_pricing', $currency_pricing_data );
	$product->save();
}
add_action( 'woocommerce_process_product_meta', 'save_custom_currency_pricing');
