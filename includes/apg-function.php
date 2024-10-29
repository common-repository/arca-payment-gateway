<?php
// get price from json str
function arca_pg_getPriceFromJson($priceJson, $currencyCode){

   // decode db price value to associative array
   $priceArray   = json_decode($priceJson, true);

   // get price for currency by currency code
   $productPrice = (is_array($priceArray) && isset($priceArray[$currencyCode])) ? $priceArray[$currencyCode] : 0;

   return (is_numeric($productPrice)) ? $productPrice : 0;

}

// error catch
function arca_pg_errorCatch($errMgs = "", $wc_orderId = null, $gwp_donationId = null){

   global $wpdb, $arca_config;

   if ($errMgs == ""){
      $errMgs = "unknown-error";
   }
   $errMgs = trim($errMgs);
   $errMgs = preg_replace('/,$/', '', $errMgs);
   $table  = $wpdb->prefix . 'arca_pg_errorlogs';

   $data   = array(
      'error'     => $errMgs,
      'rest_serverID' => $arca_config->rest_serverID,
   );
   $format = array(
      '%s',
      '%d'
   );
   $wpdb->insert($table, $data, $format);

   // if woocommerce
   if(isset($wc_orderId)){

      // get wc order
      $apg_wc_order = wc_get_order($wc_orderId);

      // set wc order status failed
      $apg_wc_order->set_status('failed', 'wc_apg_gatewey');
      $apg_wc_order->save();

   } elseif (isset($gwp_donationId)) {
      
      // set give wp donate status 
      give_update_payment_status( $gwp_donationId, "failed" );     

   }

   wp_redirect( arca_pg_checkOutPagePermalink() . "?state=error");
   exit;

}

// remove order number prefix
function arca_pg_removeOrderNumberPrefix($orderNumber){
   $splitedOrderNumber = explode("-", $orderNumber);
   return (is_array($splitedOrderNumber) && count($splitedOrderNumber) > 1) ? $splitedOrderNumber[1] : $orderNumber;
}

// get checkout page permalink
function arca_pg_checkOutPagePermalink($language = null){

   global $arca_config;

   // if config checkoutFormPage empaty
   if( empty( $arca_config->checkoutFormPage ) ) return false;

   // get post by post_name
   $checkoutFormPage = get_page_by_path( $arca_config->checkoutFormPage );

   // if post not exists
   if( empty( $checkoutFormPage ) || is_null( $checkoutFormPage ) ) return false;

      // get translation

      // if polylang
      if ( function_exists('pll_current_language') ) {

         // get page id for custom $language, if not exist for pll_default_language
         $page_ID_current_lang = ( pll_get_post( $checkoutFormPage->ID, $language ) ) ? pll_get_post( $checkoutFormPage->ID, $language ) : pll_get_post( $checkoutFormPage->ID, pll_default_language() ) ;
         return !empty( $page_ID_current_lang ) ? get_permalink( $page_ID_current_lang ) : get_permalink( $checkoutFormPage->ID );
         
      }

   // if translation not found
   return get_permalink( $checkoutFormPage->ID );

 }


// get checkout page permalink
function arca_pg_privacyPolicyPagePermalink($language = null){

   global $arca_config;

   // if config checkoutFormPage empaty
   if( empty( $arca_config->privacyPolicyPage ) ) return false;

   // get post by post_name
   $privacyPolicyPage = get_page_by_path( $arca_config->privacyPolicyPage );

   // if post not exists
   if( empty( $privacyPolicyPage ) || is_null( $privacyPolicyPage ) ) return false;

      // get translation

      // if polylang
      if ( function_exists('pll_current_language') ) {

         // get page id for custom $language, if not exist for pll_default_language
         $page_ID_current_lang = ( pll_get_post( $privacyPolicyPage->ID, $language ) ) ? pll_get_post( $privacyPolicyPage->ID, $language ) : pll_get_post( $privacyPolicyPage->ID, pll_default_language() ) ;
         return !empty( $page_ID_current_lang ) ? get_permalink( $page_ID_current_lang ) : get_permalink( $privacyPolicyPage->ID );
         
      }

   // if translation not found
   return get_permalink( $privacyPolicyPage->ID );

 }

function apg_rest_status() {
   

   
   return false;
   
}