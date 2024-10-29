<?php
add_action('admin_menu', 'arca_pg_add_plugin_admin_menu');
function arca_pg_add_plugin_admin_menu(){

   global $wpdb;
   // get notifications

   $notification = $wpdb->get_row("
      select " . $wpdb->prefix . "arca_pg_config.rest_serverID as sid, " . $wpdb->prefix . "arca_pg_config.adminLastVisitDate as alvdate,
      (select count(orderNumber) from " . $wpdb->prefix . "arca_pg_orders WHERE orderDate > alvdate and rest_serverID = sid) as nCount,
      (select count(id) from " . $wpdb->prefix . "arca_pg_errorlogs WHERE dateTime > alvdate and rest_serverID = sid) as eCount
      from " . $wpdb->prefix . "arca_pg_config where active = 1
    ");

   add_menu_page("ArCa Gateway", ($notification->nCount + $notification->eCount > 0) ? sprintf('ArCa Gateway <span class="awaiting-mod apg-notification">%d</span>', $notification->nCount) : "ArCa Gateway", "manage_options", "dashboard", "arca_pg_display_page_dashboard", ARCAPG_URL.'/images/icon.png');
   add_submenu_page("dashboard", __("Dashboard", 'apg') , __("Dashboard", 'apg') , "manage_options", "dashboard", "arca_pg_display_page_dashboard");
   add_submenu_page("dashboard", __("Orders", 'apg') , __("Orders", 'apg') , "manage_options", "оrderlog", "arca_pg_display_page_orders");
   add_submenu_page("dashboard", __("Error logs", 'apg') , __("Error logs", 'apg') , "manage_options", "errorlogs", "arca_pg_display_page_errorLogs");
   add_submenu_page("dashboard", __("Price list", 'apg') , __("Price list", 'apg') , "manage_options", "pricelist", "arca_pg_display_page_priceList");
   add_submenu_page("dashboard", __("Checkout form", 'apg') , __("Checkout form", 'apg') , "manage_options", "checkoutform", "arca_pg_display_page_checkoutForm");
   add_submenu_page("dashboard", __("Currency", 'apg') , __("Currency", 'apg') , "manage_options", "currency", "arca_pg_display_page_currency");
   add_submenu_page("dashboard", __("vPOS Settings", 'apg') , __("vPOS Settings", 'apg') , "manage_options", "config", "arca_pg_display_page_vpos_configuration");
   add_submenu_page("dashboard", __("Idram Settings", 'apg') , __("Idram Settings", 'apg') , "manage_options", "idramconfig", "arca_pg_display_page_idram_configuration");
   add_submenu_page("dashboard", __("Support", 'apg') , __("Support", 'apg') , "manage_options", "support", "arca_pg_display_page_support");
   add_submenu_page("dashboard", __("How To Use", 'apg') , __("How To Use", 'apg') , "manage_options", "how_to_use", "arca_pg_display_page_HowToUse");

}
function arca_pg_display_page_HowToUse(){
   require_once ("apg-how-to-use.php");
}
function arca_pg_display_page_dashboard(){
   require_once ("apg-dashboard.php");
}
function arca_pg_display_page_orders(){
   require_once ("apg-orders.php");
}
function arca_pg_display_page_errorLogs(){
   require_once ("apg-error-logs.php");
}
function arca_pg_display_page_priceList(){
   require_once ("apg-price-list.php");
}
function arca_pg_display_page_checkoutForm(){
   require_once ("apg-checkout-form.php");
}
function arca_pg_display_page_currency(){
   require_once ("apg-currency.php");
}
function arca_pg_display_page_vpos_configuration(){
   require_once ("apg-config.php");
}
function arca_pg_display_page_idram_configuration(){
   require_once ("apg-idram-config.php");
}
function arca_pg_display_page_support(){
   require_once ("apg-support.php");
}
