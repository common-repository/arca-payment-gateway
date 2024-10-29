<?php
// vPOS for woocommerce
 class wc_apg_gatewey extends WC_Payment_Gateway {

   public function __construct() {
      $this->id = 'wc_apg_gatewey';
      $this->has_fields = true;
      $this->method_title = 'ArCa Payment Gateway by Planet Studio';
      $this->method_description = 'Payment gateway for Armenian banks';
      $this->enabled = $this->get_option('enabled');
      $this->title = $this->get_option('title');
      $this->description = $this->get_option('description');
      $this->init_form_fields();
      $this->init_settings();         
      add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
   }

   public function init_form_fields() {
       $this->form_fields = apply_filters( 'wc_apg_form_fields', array(
           'enabled' => array(
               'title'   => 'Enable / Disable',
               'type'    => 'checkbox',
               'label'   => 'ArCa / InecoBank / Ameria Bank Payment Gateway',
               'default' => 'yes'
           ),
           'title' => array(
               'title'       => 'Title',
               'type'        => 'text',
               'description' => '',
               'default'     => 'Credit Card',
               'desc_tip'    => true,
           ),
           'description' => array(
               'title'       => 'Description',
               'type'        => 'text',
               'description' => '',
               'default'     => 'ArCa, MasterCard, Visa, Maestro',
               'desc_tip'    => true,
           ),
       ) );
   }

   public function process_payment($wc_orderId) {
      return array(
         "result"    => "success",
         "redirect"  => get_site_url() . "?arca_process=register&wc_orderId=$wc_orderId"
      );
   }

}
