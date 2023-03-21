<?php
/**
* Plugin Name: Manual Currency Pricing for WooCommerce Products
* Author: David Wampamba
* Author URI: https://davidofug.com
* Description: Manual Currency Pricing for WooCommerce Products is a plugin that allows you to add manual currency pricing options to WooCommerce product.
* Tags: currency, woocommerce, products, online shops
*/

// Add the manual currency pricing field to product post type
function add_manual_currency_pricing_repeater() {
	global $woocommerce, $post;
?>
	<p>Manual Currency Pricing</p>

	<div class="options_group" id="manual_currency_field_container">
		<?php
		woocommerce_wp_select( array(
			'id' => '_manual_currency[]',
			'label' => __( 'Currency', 'woocommerce' ),
			'description' => 'Choose a currency!',
			'desc_tip'    => false,
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
				'description' => 'Enter the amount!',
				'desc_tip'    => false,
				'class'       => 'wc_input_price',
				'type'        => 'text',
				'default'     => '00.0',
			)
		);
		?>
		<div>
			<button class="button" data-add-id="1">+</button> &nbsp;
			<button class="button" data-remove-id="1">-</button>
		</div>
	</div>
<?php

}
add_action( 'woocommerce_product_options_general_product_data', 'add_manual_currency_pricing_repeater' );

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

function enqueue_manual_currency_pricing_scripts_and_styles() {
    global $post_type;
    if ( 'product' == $post_type ) {
        wp_enqueue_style( 'manual_currency_pricing-plugin-admin-style', plugin_dir_url( __FILE__ ) . 'css/my-plugin-admin.css' );
        wp_enqueue_script( 'mmanual_currency_pricing-plugin-admin-script', plugin_dir_url( __FILE__ ) . 'js/my-plugin-admin.js', array( 'jquery' ), '', true );
    }
}

add_action( 'admin_enqueue_scripts', 'enqueue_manual_currency_pricing_scripts_and_styles' );

