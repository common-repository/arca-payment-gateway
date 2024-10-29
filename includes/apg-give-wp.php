<?php
/*
	comment this action for disabled card info fields

	/Users/margaryan/Desktop/give/includes/forms/template.php:
	1094: add_action( 'give_cc_form', 'give_get_cc_form' );

*/

/**
 * Register payment method.
*/
// change the prefix insta_for_give here to avoid collisions with other functions
function insta_for_give_register_payment_method( $gateways ) {
  $gateways['apg_gatewey'] = array(
    'admin_label'    => __( 'ArCa Payment Gateway', 'apg_gatewey-for-give' ), // This label will be displayed under Give settings in admin.
    'checkout_label' => __( 'Credit Card', 'apg_gatewey-for-give' ), // This label will be displayed on donation form in frontend.
  );
  return $gateways;
}
add_filter( 'give_payment_gateways', 'insta_for_give_register_payment_method' );


/**
 * Register Section for Payment Gateway Settings.
*/
// change the insta_for_give prefix to avoid collisions with other functions.
function insta_for_give_register_payment_gateway_sections( $sections ) {
	// `apg_gatewey-settings` is the name/slug of the payment gateway section.
	$sections['apg_gatewey-settings'] = __( 'ArCa Payment Gateway', 'apg_gatewey-for-give' );
	return $sections;
}
add_filter( 'give_get_sections_gateways', 'insta_for_give_register_payment_gateway_sections' );


/**
 * Register Admin Settings.
*/
// change the insta_for_give prefix to avoid collisions with other functions.
function insta_for_give_register_payment_gateway_setting_fields( $settings ) {

	switch ( give_get_current_setting_section() ) {

		case 'apg_gatewey-settings':
			$settings = array(
				array(
					'id'   => 'give_title_apg_gatewey',
					'type' => 'title',
				),
			);

            $settings[] = array(
				'name' => __( 'API Key', 'give-square' ),
				'desc' => __( 'Enter your API Key, found in your Instamojo Dashboard.', 'apg_gatewey-for-give' ),
				'id'   => 'insta_for_give_apg_gatewey_api_key',
				'type' => 'text',
		    );

			$settings[] = array(
				'id'   => 'give_title_apg_gatewey',
				'type' => 'sectionend',
			);

			break;

	} // End switch().

	return $settings;
}
// change the insta_for_give prefix to avoid collisions with other functions.
add_filter( 'give_get_settings_gateways', 'insta_for_give_register_payment_gateway_setting_fields' );


/**
 * Process Square checkout submission.
*/
// change the insta_for_give prefix to avoid collisions with other functions.
function insta_for_give_process_apg_gatewey_donation( $posted_data ) {

	// Make sure we don't have any left over errors present.
	give_clear_errors();

	// Any errors?
	$errors = give_get_errors();

	if ( ! $errors ) {

		$form_id         = intval( $posted_data['post_data']['give-form-id'] );
		$price_id        = ! empty( $posted_data['post_data']['give-price-id'] ) ? $posted_data['post_data']['give-price-id'] : 0;
		$donation_amount = ! empty( $posted_data['price'] ) ? $posted_data['price'] : 0;

		// Setup the payment details.
		$donation_data = array(
			'price'           => $donation_amount,
			'give_form_title' => $posted_data['post_data']['give-form-title'],
			'give_form_id'    => $form_id,
			'give_price_id'   => $price_id,
			'date'            => $posted_data['date'],
			'user_email'      => $posted_data['user_email'],
			'purchase_key'    => $posted_data['purchase_key'],
			'currency'        => give_get_currency( $form_id ),
			'user_info'       => $posted_data['user_info'],
			'status'          => 'pending',
			'gateway'         => 'apg_gatewey',
		);

		// Record the pending donation.
		$donation_id = give_insert_payment( $donation_data );

		if ( ! $donation_id ) {

			// Record Gateway Error as Pending Donation in Give is not created.
			give_record_gateway_error(
				__( 'Instamojo Error', 'apg_gatewey-for-give' ),
				sprintf(
				/* translators: %s Exception error message. */
					__( 'Unable to create a pending donation with Give.', 'apg_gatewey-for-give' )
				)
			);

			// Send user back to checkout.
			give_send_back_to_checkout( '?payment-mode=apg_gatewey' );
			return;
		}

		// Do the actual payment processing using the custom payment gateway API. To access the GiveWP settings, use give_get_option() 
        // as a reference, this pulls the API key entered above: give_get_option('insta_for_give_apg_gatewey_api_key')
		
		wp_redirect( get_site_url() . "?arca_process=register&gwp_donationId=$donation_id" );


	} else {

		// Send user back to checkout.
		give_send_back_to_checkout( '?payment-mode=apg_gatewey' );
	} // End if().
}
// change the insta_for_give prefix to avoid collisions with other functions.
add_action( 'give_gateway_apg_gatewey', 'insta_for_give_process_apg_gatewey_donation' );

