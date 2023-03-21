<?php
/**
* Plugin Name: Manual Currency Pricing for WooCommerce Products
* Plugin Description: Manual Currency Pricing for WooCommerce Products is a plugin that allows you to add manual currency pricing options to WooCommerce product.
* Plugin Tags: currency, woocommerce, products, online shops
*/


// Add the manual currency pricing field to product post type
function add_manual_currency_pricing_repeater() {
	global $woocommerce, $post;

	echo '<p>Manual Currency Pricing</p>';

	echo '<div class="options_group" id="manual_currency_field_container">';

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
	echo '<div>';
	echo '<button class="button" data-add-id="1">+</button>';
	echo '&nbsp;';
	echo '<button class="button" data-remove-id="1">-</button>';
	echo '</div>';
	echo '</div>';
?>
	<style>
		.woocommerce_options_panel #manual_currency_field_container  p.form-field {
			padding: 0 15px !important;

		}
		#manual_currency_field_container{
			display: flex;
			justify-content: normal;
			align-items: center;

		}
		#manual_currency_field_container label,
		#manual_currency_field_container input,
		#manual_currency_field_container select {
			display: block;
			width: 100%;
			float: none;
			margin: 0;
		}
	</style>
	<script>
		const buttons = document.querySelectorAll("#manual_currency_field_container button")
		buttons.forEach((button) => {
			button.addEventListener('click', event => event.preventDefault());
		});
	</script>
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
