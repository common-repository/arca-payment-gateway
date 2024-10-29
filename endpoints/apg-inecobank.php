<?php
global $wpdb;
$arca_process = (isset($_REQUEST["arca_process"])) ? sanitize_text_field($_REQUEST["arca_process"]) : null;
define( 'APG_URL_IF_TEST_MODE', (($arca_config->rest_serverID == 2) ? "test" : "") );
  
// ArCa - start payment
if ($arca_process == "register"){

    // data validation
    $errMgs = array();

    // get form data or defaults values
    $wc_orderId		= 	( !empty($_REQUEST["wc_orderId"]) ) ? intval($_REQUEST["wc_orderId"]) : null;
    $gwp_donationId	= 	( !empty($_REQUEST["gwp_donationId"]) ) ? intval($_REQUEST["gwp_donationId"]) : null;
    $productId		=	( !empty($_REQUEST["productId"]) ) ? intval($_REQUEST["productId"]) : 0;
    $amount			=	( !empty($_REQUEST["amount"]) ) ? ( (APG_URL_IF_TEST_MODE != "test") ? doubleval($_REQUEST["amount"]) : 10 ) : 0;
    $description	=	( !empty($_REQUEST["description"]) ) ? sanitize_text_field($_REQUEST["description"]) : __( "Online payment", 'apg' );
    $language		=	( !empty($_REQUEST["language"]) ) ? sanitize_text_field($_REQUEST["language"]) : $arca_config->default_language;
    $currency		=	( !empty($_REQUEST["currency"]) ) ? sanitize_text_field($_REQUEST["currency"]) : $arca_config->default_currency;
    $custom_amount	= ( !empty($_REQUEST["custom_amount"]) && intval($_REQUEST["custom_amount"]) == 1 ) ? 1 : 0;
   
    // get payment initiator, woocommerce, givewp or apg
    if(isset($wc_orderId)) {

  		// get wc order
    	$apg_wc_order = wc_get_order($wc_orderId);

		// get order total amount
    	$amount = $apg_wc_order->get_total();

	    // validate currency
	    $currency = $wpdb->get_var($wpdb->prepare("SELECT code  from " . $wpdb->prefix . "arca_pg_currency where (abbr = '%s' or code = '%s') and active = 1", $apg_wc_order->get_currency(), $apg_wc_order->get_currency()));
	    if (!isset($currency)) array_push($errMgs, "Incorect currency:" . $currency); 

		
    } else if (isset($gwp_donationId)) {
		
		// get give wp donation amount
		$amount = give_donation_amount($gwp_donationId);
		
	    // get give wp donation currency abbr
	    // get give wp donation currency abbr
	    $apg_givewp_currency = give_get_payment_currency_code($gwp_donationId);
		
	    // validate currency
	    $currency = $wpdb->get_var($wpdb->prepare("SELECT code  from " . $wpdb->prefix . "arca_pg_currency where (abbr = '%s' or code = '%s')  and active = 1", $apg_givewp_currency, $apg_givewp_currency));
		
	    if (!isset($currency)) array_push($errMgs, "Incorect currency:" . $currency);
		
    } else {

		if($custom_amount == 0){

			// validate productId
			$resultCount = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) from " . $wpdb->prefix . "arca_pg_pricelist where productId = %d", $productId));
			if ($resultCount == 0) array_push($errMgs, "incorrect productId:" . $productId);

			// get product details
			$priceList = $wpdb->get_row($wpdb->prepare("select * from " . $wpdb->prefix . "arca_pg_pricelist where productId = %d", $productId));

			// get price, validate price
			$amount = arca_pg_getPriceFromJson($priceList->productPrice, $currency);
			if ($amount == 0) array_push($errMgs, "Incorrect amount:" . $amount . " with the currency:" . $currency);

		}

	    // validate currency
	    $resultCount = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) from " . $wpdb->prefix . "arca_pg_currency where (abbr = '%s' or code = '%s') and active = 1", $currency, $currency));
	    if ($resultCount == 0) array_push($errMgs, "Incorect currency:" . $currency); 

    }

    // validate language
    $resultCount = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) from " . $wpdb->prefix . "arca_pg_language where code = '%s'", $language));
    if ($resultCount == 0) $language = $arca_config->default_language;

    // validate checkout form permalink
    if (arca_pg_checkOutPagePermalink() == false) array_push($errMgs, "Checkout form not found");

 /*   // get pageView
    $ua			= strtolower($_SERVER['HTTP_USER_AGENT']);
    $isMob		= is_numeric(strpos($ua, "mobile"));
    $pageView	= ($isMob) ? "MOBILE" : "DESKTOP";
*/
    $pageView	= "";

    // get orderDetails
    $orderDetails = json_encode($_REQUEST, JSON_UNESCAPED_UNICODE);

    // validation
    if (empty($errMgs)){

		// create order blank row in db
		$table = $wpdb->prefix . 'arca_pg_orders';
		$data = array(
			'productId'     => $productId,
			'wc_orderId'    => $wc_orderId,
			//'gwp_donationId'    	=> $gwp_donationId,
			'amount'        => $amount,
			'currency'      => $currency,
			'orderDetails'  => $orderDetails,
			'rest_serverID' => $arca_config->rest_serverID,
			'orderDate'     => current_time('mysql') ,
		);
		$format = array(
			'%d',
			'%d',
			//'%d',
			'%f',
			'%s',
			'%s',
			'%d',
			'%s'
		);
		$insert = $wpdb->insert($table, $data, $format);
		if ($insert){

            // get orderNumber from created row
            $orderNumber  = $wpdb->insert_id;

			// REST - order registration request
			$register_url = 'https://pg.inecoecom.am/payment/rest/register.do';
			$args = array(
				'headers'     => array('Content-Type: text/html; charset=UTF-8'),
				'body'        => array(
					'userName'    => $apg_vpos_accuonts[$currency]["api_userName"],
					'password'    => $apg_vpos_accuonts[$currency]["api_password"],
					'orderNumber' => ($arca_config->orderNumberPrefix != "") ? $arca_config->orderNumberPrefix . '-' . $orderNumber : $orderNumber,
					'amount'      => $amount * 100,
					'currency'    => $currency,
					'returnUrl'   => get_site_url() . "?arca_process=payment_completed&wc_orderId=$wc_orderId&gwp_donationId=$gwp_donationId&language=$language&currency=$currency",
					'description' => $description,
					'language'    => $language,
					'pageView'    => $pageView,
				),
				'method'      => 'POST',
				'data_format' => 'body',
			);
			$response = wp_remote_post( $register_url, $args );

			if( is_object($response) ){
				arca_pg_errorCatch("REST respons error: " . json_encode($response, JSON_UNESCAPED_UNICODE), $wc_orderId, $gwp_donationId);
			} else {
				$response = json_decode($response['body']);
			}

            // check REST response to JSON format
            if (is_object($response) && isset($response->errorCode)) {

				$OrderStatusExtended = json_encode( array( "Response 1" => $response ) );

				if ($response->errorCode == 0) {

					// update order row from REST response
					$sql = "update " . $wpdb->prefix . "arca_pg_orders set OrderStatusExtended = '$OrderStatusExtended', errorCode = '0', paymentState = 'Registered', orderId = '$response->orderId' where orderNumber = $orderNumber";
					$wpdb->query($sql);

					// redirect to bank page
					wp_redirect($response->formUrl);
					exit;

				} else {

					// update error number from REST system
					$sql = "update " . $wpdb->prefix . "arca_pg_orders set OrderStatusExtended = '$OrderStatusExtended', errorCode = '$response->errorCode', paymentState = 'Registration failed' where orderNumber = $orderNumber";
					$wpdb->query($sql);

					arca_pg_errorCatch("REST respons error, errorCode: " . sanitize_text_field($response->errorCode), $wc_orderId, $gwp_donationId);
				}

	        } else {
				// REST response is not JSON data
				arca_pg_errorCatch("REST response is not JSON data, response: " . sanitize_text_field($response), $wc_orderId, $gwp_donationId);
	        }

		} else {
			arca_pg_errorCatch("Error on insert new order row, wpdb error: " . $wpdb->error, $wc_orderId, $gwp_donationId);
		}

    } else {
		arca_pg_errorCatch("Data validation error: " . implode(', ', $errMgs), $wc_orderId, $gwp_donationId);
    }

}

// REST - payment completed and returned back
if ($arca_process == 'payment_completed' && isset($_REQUEST['paymentID']) && isset($_REQUEST['currency'])) {

	$orderId = sanitize_text_field($_REQUEST['paymentID']);
	$currency = sanitize_text_field($_REQUEST['currency']);

	// create order status request from REST / Extended
	$orderStatus_url = 'https://pg.inecoecom.am/payment/rest/getOrderStatusExtended.do';
	$args = array(
		'headers'     => array('Content-Type: text/html; charset=UTF-8'),
		'body'        => array(
		'userName'    => $apg_vpos_accuonts[$currency]["api_userName"],
		'password'    => $apg_vpos_accuonts[$currency]["api_password"],
		'orderId'     => $orderId,
	),
		'method'      => 'POST',
		'data_format' => 'body',
	);
	$response = wp_remote_post( $orderStatus_url, $args );

	if( is_object($response) ){
		arca_pg_errorCatch("REST respons error: " . json_encode($response, JSON_UNESCAPED_UNICODE), $wc_orderId, $gwp_donationId);
	} else {
		$response = $response['body'];
	}	

	// get order payment state from REST response
	$paymentState = (isset(json_decode($response)->paymentAmountInfo->paymentState)) ? strtoupper(json_decode($response)->paymentAmountInfo->paymentState) : "state-not-found";

	// get previous stored requests
	$OrderStatusExtended =  json_decode( $wpdb->get_var("select OrderStatusExtended from " . $wpdb->prefix . "arca_pg_orders where orderId = '$orderId'"), true );

	// add new request
	$OrderStatusExtended = array_merge($OrderStatusExtended, array( "Response 2" => json_decode($response, true) ) );
	
	$OrderStatusExtended = json_encode( $OrderStatusExtended );
	
	// update order payment REST response data
	$sql = "update " . $wpdb->prefix . "arca_pg_orders set OrderStatusExtended = '$OrderStatusExtended', paymentState = '$paymentState' where orderId = '$orderId'";
	$wpdb->query($sql);

	$wc_orderId = ( !empty($_REQUEST["wc_orderId"]) ) ? intval($_REQUEST["wc_orderId"]) : null;
	$gwp_donationId	= ( !empty($_REQUEST["gwp_donationId"]) ) ? intval($_REQUEST["gwp_donationId"]) : null;

	// if woocommerce
    if(isset($wc_orderId)) {

		// get wc order
		$apg_wc_order = wc_get_order($wc_orderId);

		// get ws order status processing or failed
		$apg_wc_orderStatus = ( $paymentState == 'DEPOSITED' ) ? $arca_config->wc_order_status : 'failed';

		// set wc order status 
		$apg_wc_order->set_status($apg_wc_orderStatus, 'wc_apg_gatewey');
		$apg_wc_order->save();
        
        $apg_wc_order->reduce_order_stock();
        WC()->cart->empty_cart();
		
		// set email sent if email exist in wc order
		$sql = "update " . $wpdb->prefix . "arca_pg_orders set mailSent = 1 where orderId = '$orderId'";
		$wpdb->query($sql);
		
		// get wc return url
		$wc_apg_gatewey = new wc_apg_gatewey();
		
		// redirect to final page with REST payment state
		wp_redirect( $wc_apg_gatewey->get_return_url( $apg_wc_order ) );
		exit;

	// if give wp
    } else if(isset($gwp_donationId)){

		// get give wp order status publish or failed
		$apg_givewp_orderStatus = ( $paymentState == 'DEPOSITED' ) ? 'publish' : 'failed';    	

		// set give wp donate status 
		give_update_payment_status( $gwp_donationId, $apg_givewp_orderStatus );

		// set email sent if email exist in wc order
		$sql = "update " . $wpdb->prefix . "arca_pg_orders set mailSent = 1 where orderId = '$orderId'";
		$wpdb->query($sql);

    	// redirect to final page with REST payment state
		give_send_to_success_page();
		exit;

    }

    // get language
    $language = ( isset($_REQUEST["language"]) ) ? $_REQUEST["language"] : null;

	// redirect to final page with REST payment state
	wp_redirect( arca_pg_checkOutPagePermalink($language) . "?state=$paymentState&orderId=$orderId");
	exit;
}
