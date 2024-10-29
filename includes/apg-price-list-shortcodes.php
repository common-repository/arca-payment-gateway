<?php
global $wpdb;

// ArCa Payments Gateway configs
$arca_config = $wpdb->get_row("select * from ".$wpdb->prefix."arca_pg_config where active = 1");

// get productId
$productId = (isset($_GET["productId"])) ? intval($_GET["productId"]) : 0;

// get product details
$product = $wpdb->get_row($wpdb->prepare("select * from ".$wpdb->prefix."arca_pg_pricelist where productId = '%d'", $productId));

// get shortcode def currency abbr
$shortcode_default_currency = $wpdb->get_var($wpdb->prepare("SELECT abbr  from " . $wpdb->prefix . "arca_pg_currency where code = '%s' and active = 1", $arca_config->default_currency));

// get shortcode def currency code
$shortcode_default_currency_code = $wpdb->get_var($wpdb->prepare("SELECT code  from " . $wpdb->prefix . "arca_pg_currency where code = '%s' and active = 1", $arca_config->default_currency));

wp_print_scripts('jquery');
wp_print_styles('dashicons');
wp_register_style('arca-payments-gateway-admin', ARCAPG_URL . '/css/admin.css', FALSE, ARCAPG_VERSION);
wp_print_styles('arca-payments-gateway-admin');
wp_register_script('arca-payments-gateway', ARCAPG_URL . '/script/admin.js', array('jquery'), ARCAPG_VERSION);
wp_print_styles('wp-admin');
wp_localize_script("arca-payments-gateway", 'arcapg_admin', array(
    'copied_to_clipboard' => __("Copied to clipboard!", 'apg'),
    'confirm_delete' => __("Are you sure you want to delete this item?", 'apg'),
));
wp_print_scripts('arca-payments-gateway');
?>

<div class="apg" id="apg-shortcode">

    <h1><?php _e( "Generate your shortcode", 'apg' ) ?></h1>

    <p id="productName">&rarr; <?php echo esc_html($product->productName); ?></p>

    <p><?php _e( "Choose payment language:", 'apg' ) ?></p>
    <select id="language" onchange="generateShortcode()">
    <?php
        $arca_languages = $wpdb->get_results("select * from ".$wpdb->prefix."arca_pg_language order by language");
        if( $arca_languages ) {
            foreach ( $arca_languages as $language ) {
                echo "<option " . (($arca_config->default_language == $language->code) ? 'selected' : '') ." value='".esc_attr($language->code)."'>".esc_html($language->language)."</option>";
            }
        }
    ?>
    </select>  

    <br>
    <p><?php _e( "Choose payment currency:", 'apg' ) ?></p>
    <select id="currency" onchange="generateShortcode()">
    <?php
        $arca_currencies = $wpdb->get_results("select * from ".$wpdb->prefix."arca_pg_currency where active = 1 order by code");
        if( $arca_currencies ) {
            foreach ( $arca_currencies as $currency ) {
                echo "<option " . (($arca_config->default_currency == $currency->abbr) ? 'selected' : '') ." value='".esc_attr($currency->abbr)."'>".esc_html($currency->abbr)."</option>";
            }
        }
    ?>
    </select>

    <input type="hidden" id="productid" value="<?php echo esc_attr($productId); ?>">

    <br>
    <br>
    <br>

    <legend><?php _e( "Checkout form shortcode", 'apg' ) ?></legend>
    <div class="copyToClipboard shortcode" onclick="CopyToClipboard('shortcode-1')">
        <span id="shortcode-1" title="<?php _e( "Copy", 'apg' ) ?>">
            <?php echo '[arca-pg-form productid="'.esc_attr($productId).'" language="'.esc_attr($arca_config->default_language).'" currency="'.esc_attr($shortcode_default_currency).'"]' ?>
        </span>
        <span class="dashicons dashicons-admin-page"></span>
    </div>

    <br>

    <legend><?php _e( "Buy it button shortcode", 'apg' ) ?></legend>
    <div class="copyToClipboard shortcode" onclick="CopyToClipboard('shortcode-2')">
        <span id="shortcode-2" title="<?php _e( "Copy", 'apg' ) ?>">
            <?php echo '[arca-pg-button productid="'.esc_attr($productId).'" language="'.esc_attr($arca_config->default_language).'" currency="'.esc_attr($shortcode_default_currency).'"]'?>
        </span>
        <span class="dashicons dashicons-admin-page"></span>
    </div>
	
</div>