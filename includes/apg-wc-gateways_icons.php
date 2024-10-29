<?php
	add_filter( 'woocommerce_gateway_icon', 'custom_payment_gateway_icons', 10, 2 );
	function custom_payment_gateway_icons( $icon, $gateway_id ){
		
		global $arca_idram_config;

		foreach( WC()->payment_gateways->get_available_payment_gateways() as $gateway )
			if( $gateway->id == $gateway_id ){
				$title = $gateway->get_title();
				break;
			}

		// The path (subfolder name(s) in the active theme)
		$path = esc_url(ARCAPG_URL) . '/images/';

		// Setting (or not) a custom icon to the payment IDs
		if($gateway_id == 'wc_apg_gatewey')
			$icon = '<img id="wc_apg_gatewey_card_icons" src="' . WC_HTTPS::force_https_url( "$path/payment-icon.png" ) . '" alt="' . esc_attr( $title ) . '" />';
		elseif( $gateway_id == 'wc_apg_gatewey_idram' )
			$icon = '<img id="wc_apg_gatewey_idram_icons" src="' . $path . (($arca_idram_config->rocketLine) ? "idram-rocket-2.png" : "idram.png") . '" alt="' . esc_attr( $title ) . '" />';

		return $icon;
	}
?>