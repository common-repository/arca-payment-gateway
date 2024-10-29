<?php
if (!is_admin()){
  add_shortcode("arca-pg-form", "arca_pg_form_shortcode");
  add_shortcode("arca-pg-button", "arca_pg_button_shortcode");
  add_shortcode("arca-pg-button-custom-amount", "arca_pg_button_custom_amount_shortcode");
  add_shortcode("arca-pg-button-idram", "arca_pg_idram_shortcode");
}

function arca_pg_form_shortcode($atts){

  // [arca-pg-form id="default" productid="1" language="hy" currency="AMD"]

  global $content, $arca_config, $wpdb;

  ob_start();

  $atts = shortcode_atts([
                           "id"            => "default",
                           "language"      => $arca_config->default_language,
                           "description"  => "",
                           "currency"      => $arca_config->default_currency,
                           "productid"     => 0,
                         ], $atts);

  $errMgs = array();
  $productId = $atts["productid"];
  $language = $atts["language"];
  $description = $atts["description"];
  $currency = $atts["currency"];
  $state = "";

  if (!empty($_REQUEST["productId"])) $productId = intval($_REQUEST["productId"]);
  if (!empty($_REQUEST["language"])) $language = sanitize_text_field($_REQUEST["language"]);
  if (!empty($_REQUEST["description"])) $description = sanitize_text_field($_REQUEST["description"]);
  if (!empty($_REQUEST["currency"])) $currency = sanitize_text_field($_REQUEST["currency"]);
  if (!empty($_REQUEST["state"])) $state = sanitize_text_field($_REQUEST["state"]);

  // validate productId
  $resultCount = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) from " . $wpdb->prefix . "arca_pg_pricelist where productId = %d", $productId));
  if ($resultCount == 0){
    array_push($errMgs, "Incorect productid");
  }

  // validate language
  $resultCount = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) from " . $wpdb->prefix . "arca_pg_language where code = '%s'", $language));
  if ($resultCount == 0){
    array_push($errMgs, "Incorect language");
  }

  // validate currency
  $currency = $wpdb->get_row($wpdb->prepare("SELECT COUNT(id) as exist, code, abbr from " . $wpdb->prefix . "arca_pg_currency where (abbr = '%s' or code = '%s') and  active = 1", $currency, $currency));
  if (!empty($currency->exist)){

    $currencyAbbr = $currency->abbr;
    $currencyCode = $currency->code;

    // get product details
    $priceList = $wpdb->get_row($wpdb->prepare("select * from " . $wpdb->prefix . "arca_pg_pricelist where productId = %d", $productId));
    if (!empty($priceList)){

      $productName  = $priceList->productName;
      $productDescription  = ($description != "") ? $description : $priceList->productDescription;
      $productPrice = arca_pg_getPriceFromJson($priceList->productPrice, $currencyCode);

      if ($productPrice == 0) {
        array_push($errMgs, "Price not found");
      }

    } else {
      array_push($errMgs, "Price not found");
    }

  } else {
    array_push($errMgs, "Incorect currency");
  }

  if ($state != "" || empty($errMgs)){

    switch ($atts["id"]){
      case "default":
        include ( ARCAPG_DIR ."/forms/form-default.php" );
        break;
    }

  } else {
    echo esc_html(implode(', ', $errMgs));
  }

  $output = ob_get_clean();
  return $output;
}

function arca_pg_button_shortcode($atts) {

  // [arca-pg-button productid="1" language="hy" currency="AMD"]

  global $content, $arca_config, $wpdb;

  ob_start();

  $atts = shortcode_atts([
                           "productid"     => 0,
                           "language"      => $arca_config->default_language,
                           "description"   => "",
                           "currency"      => $arca_config->default_currency,
                         ], $atts);

  $errMgs = array();
  $productId = $atts["productid"];
  $language = $atts["language"];
  $description = $atts["description"];
  $currency = $atts["currency"];

  // validate productId
  $resultCount = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) from " . $wpdb->prefix . "arca_pg_pricelist where productId = %d", $productId));
  if ($resultCount == 0){
    array_push($errMgs, "Incorect productid");
  }

  // validate language
  $resultCount = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) from " . $wpdb->prefix . "arca_pg_language where code = '%s'", $language));
  if ($resultCount == 0){
    array_push($errMgs, "Incorect language");
  }

  // validate currency
  $currency = $wpdb->get_row($wpdb->prepare("SELECT COUNT(id) as exist, code, abbr from " . $wpdb->prefix . "arca_pg_currency where (abbr = '%s' or code = '%s') and  active = 1", $currency, $currency));
  if (!empty($currency->exist)){
    $currency = $currency->code;
  } else {
    array_push($errMgs, "Incorect currency");
  }

  // get checkout form permalink
  if (arca_pg_checkOutPagePermalink() == false){
    array_push($errMgs, __("ArCa Payments Gateway form not found", 'apg'));
  }

  if (empty($errMgs)){
    include ( ARCAPG_DIR . "/buttons/button-default.php" );
  } else {
    echo esc_html(implode(', ', $errMgs));
  }

  $output = ob_get_clean();
  return $output;

}

function arca_pg_button_custom_amount_shortcode($atts) {

  // [arca-pg-button productid="1" language="hy" currency="AMD"]

  global $content, $arca_config, $wpdb;

  ob_start();

  $atts = shortcode_atts([
                           "amount"       => 0,
                           "language"      => $arca_config->default_language,
                           "description"   => "",
                           "currency"      => $arca_config->default_currency,
                           "arca_process"  => "register",
                         ], $atts);

  $errMgs = array();
  $amount = $atts["amount"];
  $language = $atts["language"];
  $description = $atts["description"];
  $currency = $atts["currency"];
  $arca_process = $atts["arca_process"];


  // validate language
  $resultCount = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) from " . $wpdb->prefix . "arca_pg_language where code = '%s'", $language));
  if ($resultCount == 0){
    array_push($errMgs, "Incorect language");
  }

  // validate currency
  $currency = $wpdb->get_row($wpdb->prepare("SELECT COUNT(id) as exist, code, abbr from " . $wpdb->prefix . "arca_pg_currency where (abbr = '%s' or code = '%s') and  active = 1", $currency, $currency));
  if (!empty($currency->exist)){
    $currency_abbr = $currency->abbr;
    $currency = $currency->code;
  } else {
    array_push($errMgs, "Incorect currency");
  }

  if ($arca_process != "register" && $arca_process != "idram"){
    array_push($errMgs, __("Incorrect payment method", 'apg'));
  }

  // get checkout form permalink
  if (arca_pg_checkOutPagePermalink() == false){
    array_push($errMgs, __("ArCa Payments Gateway form not found", 'apg'));
  }

  if (empty($errMgs)){
    include ( ARCAPG_DIR . "/buttons/button-custom-amount.php" );
  } else {
    echo esc_html(implode(', ', $errMgs));
  }

  $output = ob_get_clean();
  return $output;

}


function arca_pg_idram_shortcode($atts) {

  // [arca-pg-button-idram productid="1" language="hy" currency="AMD"]

  global $content, $arca_config, $wpdb;

  ob_start();

  $atts = shortcode_atts([
                           "productid"     => 0,
                           "language"      => $arca_config->default_language,
                           "description"   => "",
                         ], $atts);

  $errMgs = array();
  $productId = $atts["productid"];
  $language = $atts["language"];
  $description = $atts["description"];

  // validate productId
  $resultCount = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) from " . $wpdb->prefix . "arca_pg_pricelist where productId = %d", $productId));
  if ($resultCount == 0){
    array_push($errMgs, "Incorect productid");
  }

  // validate language
  $resultCount = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) from " . $wpdb->prefix . "arca_pg_language where code = '%s'", $language));
  if ($resultCount == 0){
    array_push($errMgs, "Incorect language");
  }

  // get checkout form permalink
  if (arca_pg_checkOutPagePermalink() == false){
    array_push($errMgs, __("ArCa Payments Gateway form not found", 'apg'));
  }

  if (empty($errMgs)){
    include ( ARCAPG_DIR . "/buttons/button-idram.php" );
  } else {
    echo esc_html(implode(', ', $errMgs));
  }

  $output = ob_get_clean();
  return $output;

}


