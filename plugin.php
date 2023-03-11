<?php
/**
* Plugin Name: Multiple Currency Options 
*/

// Add custom currency option field to product post type
/*function add_custom_currency_option_field() {
global $woocommerce, $post;

echo '<div class="options_group">';

	woocommerce_wp_select( array(
	'id' => '_custom_currency_option',
	'label' => __( 'Custom Currency Option', 'woocommerce' ),
	'options' => array(
	'USD' => 'US Dollars',
	'EUR' => 'Euros',
	'GBP' => 'British Pounds',
	'CAD' => 'Canadian Dollars',
	// Add more currency options here as needed
	)
	) );

	echo '</div>';
}
add_action( 'woocommerce_product_options_general_product_data', 'add_custom_currency_option_field' );

// Save custom currency option data on product save
function save_custom_currency_option_field( $post_id ) {
$product = wc_get_product( $post_id );

$currency_option = isset( $_POST['_custom_currency_option'] ) ? sanitize_text_field( $_POST['_custom_currency_option'] ) : '';

$product->update_meta_data( '_custom_currency_option', $currency_option );
$product->save();
}
add_action( 'woocommerce_process_product_meta', 'save_custom_currency_option_field' );
*/

// Add custom currency option field to product post type
function add_custom_currency_option_field() {
	global $woocommerce, $post;

	echo '<div class="options_group">';

	woocommerce_wp_text_input(
		array(
			'id'          => '_custom_currency_option',
			'label'       => __( 'Custom Currency Option', 'woocommerce' ),
			'description' => __( 'Enter a custom currency code for this product (e.g. USD). If left blank, the default currency will be used.', 'woocommerce' ),
			'desc_tip'    => true,
			'class'       => 'wc_input_price',
			'type'        => 'text',
			'default'     => '',
		)
	);

	echo '</div>';
}
add_action( 'woocommerce_product_options_general_product_data', 'add_custom_currency_option_field' );

// Save custom currency option data on product save
function save_custom_currency_option_field( $post_id ) {
	$product = wc_get_product( $post_id );

	$currency_option = isset( $_POST['_custom_currency_option'] ) ? sanitize_text_field( $_POST['_custom_currency_option'] ) : '';

	$product->update_meta_data( '_custom_currency_option', $currency_option );
	$product->save();
}
add_action( 'woocommerce_process_product_meta', 'save_custom_currency_option_field' );

// Add custom currency pricing field to product post type
function add_custom_currency_pricing_field() {
	global $woocommerce, $post;

	echo '<div class="options_group">';

	woocommerce_wp_text_input(
		array(
			'id'          => '_custom_currency_pricing',
			'label'       => __( 'Custom Currency Pricing', 'woocommerce' ),
			'description' => '',
			'desc_tip'    => true,
			'class'       => 'wc_input_price',
			'type'        => 'repeater',
			'default'     => '',
			'options'     => array(
				'currency' => array(
					'label'       => __( 'Currency', 'woocommerce' ),
					'placeholder' => __( 'Enter currency code (e.g. USD)', 'woocommerce' ),
					'type'        => 'text',
				),
				'price' => array(
					'label'       => __( 'Price', 'woocommerce' ),
					'placeholder' => __( 'Enter price for this currency', 'woocommerce' ),
					'type'        => 'price',
				),
			),
		)
	);

	echo '</div>';
}
add_action( 'woocommerce_product_options_general_product_data', 'add_custom_currency_pricing_field' );

// Save custom currency pricing data on product save
function save_custom_currency_pricing_field( $post_id ) {
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
