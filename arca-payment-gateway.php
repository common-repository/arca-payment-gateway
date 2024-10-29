<?php
/**
 * Plugin Name: ArCa Payment Gateway
 * Description: ArCa payment gateway, this Plugin allows you to accept online payments from local and international customers to Armenian banks, Idram payment system and adds ArCa paycenter as a payment gateway for WooCommerce and for GiveWP donation Plugin.
 * Version: 1.3.1
 * Author: Planet Studio team
 * Author URI: https://planetstudio.am
 * License: GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
defined('ABSPATH') || die('Access Denied');
//define('ARCAPG_PREFIX', 'apg');
define('ARCAPG_DIR', WP_PLUGIN_DIR . "/" . plugin_basename(dirname(__FILE__)));
define('ARCAPG_DIR_NAME', dirname(plugin_basename( __FILE__ )));
define('ARCAPG_URL', plugins_url(plugin_basename(dirname(__FILE__))));
define('ARCAPG_VERSION', '1.3.1');
define('ARCAPG_PRO', FALSE);

global $wpdb;

// functions
require_once('includes/apg-function.php');

// Idram configs
$arca_idram_config = $wpdb->get_row("select * from " . $wpdb->prefix . "arca_pg_idram_config where id = 1");	

add_action('init', 'arca_pg_init', 500);
function arca_pg_init(){
		
   global $wpdb, $arca_config, $arca_idram_config;
	
   if(is_admin()){
      
      // check version for update
      $apg_db_version = get_option("apg_db_version");
      if(!empty($apg_db_version["version"])){
         if(version_compare(ARCAPG_VERSION, $apg_db_version["version"]) > 0){
            update_option("apg_db_version", array("version" => ARCAPG_VERSION), 1);
            arca_pg_activate();
         }
      } else {
         update_option("apg_db_version", array("version" => ARCAPG_VERSION), 1);
         arca_pg_activate();

      }

      // add thickbox
      add_thickbox();

   }

   // ArCa Payments Gateway configs
   $arca_config = $wpdb->get_row("select * from " . $wpdb->prefix . "arca_pg_config where active = 1");
   
   // get vPOS accounts
   $apg_vpos_accuonts = json_decode( $arca_config->vpos_accuonts, true );

   // load transations
   load_plugin_textdomain( 'apg', false, ARCAPG_DIR_NAME . '/lang/' );

   // set post states in admin panel
   require_once('includes/apg-set-post-states.php');

   // show poup windows
   require_once('includes/apg-show-popup.php');

   // shortcodes
   require_once('includes/apg-shortcodes.php');

   // register scripts styles
   require_once('includes/apg-register-scripts-styles.php');

   // admin menu
   require_once('includes/apg-admin-menu.php');

   // widgets
   require_once('includes/apg-dashboard-widgets.php');

   // switch API
   switch ($arca_config->bankId) {
      case "4":
         // InecoBank API
         require_once('endpoints/apg-inecobank.php');
      break;
      case "10":
         // AmeriaBank API
         require_once('endpoints/apg-ameria-bank.php');
      break;
      default:
         // ArCa API
         require_once('endpoints/apg-arca.php');
      break;
   }

   // Idram API
   if($arca_idram_config->idramEnabled){
      require_once('endpoints/apg-idram.php');
   }
	
   // Woocommerce gateways icons
   require_once('includes/apg-wc-gateways_icons.php');


   if ( ARCAPG_PRO ) {
      require_once('includes/apg-rest-controller.php');
      new apg_rest();
   }

}

// if not woocommerce exists add wc_api endpoint for idram requests
if ( !class_exists('woocommerce') && $arca_idram_config->idramEnabled ) {
	add_action( 'init', function(){
		add_rewrite_endpoint( 'wc-api', EP_ROOT );
      flush_rewrite_rules();
	});
}

// add plugin action links
add_filter( 'plugin_action_links', 'apg_plugin_action_links', 10, 2 );
function apg_plugin_action_links( $actions, $plugin_file ){

	if( false === strpos( $plugin_file, basename(__FILE__) ) ){
		return $actions;
	}

	$settings_link = '<a href="' . admin_url( 'admin.php?page=config' ) .'">' . __('Settings') . '</a>'; 
	array_unshift( $actions, $settings_link ); 

	return $actions; 
}

// add plugin row meta links
add_filter( 'plugin_row_meta', 'apg_plugin_row_meta', 10, 4 );
function apg_plugin_row_meta( $meta, $plugin_file ){
	if( false === strpos( $plugin_file, basename(__FILE__) ) )
		return $meta;

	$meta[] = '<a href="' . admin_url( 'admin.php?page=how_to_use' ) .'">' . __('How to use') . '</a>';
	$meta[] = '<a href="' . admin_url( 'admin.php?page=config' ) .'">' . __('vPOS Settings') . '</a>';
	$meta[] = '<a href="' . admin_url( 'admin.php?page=idramconfig' ) .'">' . __('Idram Settings') . '</a>';
	return $meta; 
}


// woocommerce
add_filter('woocommerce_payment_gateways', 'wc_apg_add_to_gateways');
function wc_apg_add_to_gateways($gateways) {
   $gateways[] = 'wc_apg_gatewey';
   return $gateways;
}
if($arca_idram_config->idramEnabled){
	add_filter('woocommerce_payment_gateways', 'wc_apg_add_to_gateways_idram');
	function wc_apg_add_to_gateways_idram($gateways) {
		$gateways[] = 'wc_apg_gatewey_idram';
		return $gateways;
	}
}	
add_action('plugins_loaded', 'wc_apg_gateway_init', 550);
function wc_apg_gateway_init() {
   global $arca_idram_config;
	if ( class_exists('woocommerce') ) {
		require_once('includes/apg-wc.php');
      if($arca_idram_config->idramEnabled){
   		require_once('includes/apg-wc-idram.php');
      }
	}
}

// give wp donations
add_action('plugins_loaded', 'give_wp_apg_gateway_init', 550);
function give_wp_apg_gateway_init() {
	global $arca_idram_config;
	if ( class_exists('Give') ) {
		require_once('includes/apg-give-wp.php');
		if($arca_idram_config->idramEnabled){
			require_once('includes/apg-give-wp-idram.php');
		}
	}
}



// create / insert / update
register_activation_hook(__FILE__, 'arca_pg_activate');
function arca_pg_activate(){  
	
   require_once ("insert.php");
   arcapgInsert::arca_pg_tables();

   if ( ARCAPG_PRO ) {
      require_once('includes/apg-rest-controller.php');
      $apg_rest = new apg_rest();
      $apg_options = get_option('apg_options');
      $activation_key = $apg_options['activation_key'];
      $apg_rest->check_rest($activation_key);   
   }
   
}

// deactivate popup
add_action('admin_footer', 'apg_deactivate_popup');
function apg_deactivate_popup(){
   require_once('includes/apg-deactivate-popup.php');
}


// remove givewp cc form

// remove all give fieldsets
function give_remove_fieldsets() {
   //remove_action( 'give_donation_form_top', 'give_output_donation_amount_top' );
   //remove_action( 'give_payment_mode_select', 'give_payment_mode_select' );
   //remove_action( 'give_donation_form_after_user_info', 'give_user_info_fields' );
   remove_action( 'give_cc_form', 'give_get_cc_form' );
   //remove_all_actions( 'give_donation_form_after_cc_form' );
}
add_action( 'give_init', 'give_remove_fieldsets' );

// add all give fieldsets in another order
/*
function give_reorder_fieldsets() {
   add_action( 'give_donation_form_top', 'give_output_donation_amount_top' );
   add_action( 'give_donation_form_top', 'give_user_info_fields' );
   add_action( 'give_donation_form_top', 'give_payment_mode_select', 10, 2 );
   add_action( 'give_donation_form_bottom', 'give_checkout_submit', 9999, 2 );
}
add_action( 'give_init', 'give_reorder_fieldsets' );*/

