<?php
global $wpdb, $arca_config;

$strMgs = $errMgs = "";

if ( $_SERVER["REQUEST_METHOD"] == "POST" ) {

	$act = ( !empty($_POST["act"]) ) ? sanitize_text_field( $_POST["act"] ) : "";
	$bankId = ( isset($_POST["bankId"]) ) ? intval($_POST["bankId"]) : $arca_config->bankId;

	if( $act == "save" ) {
		
		// get vPOS accounts
		$apg_vpos_accuonts = json_decode( $arca_config->vpos_accuonts, true );

		// validate AMD user, pass
		if(isset($_POST["amd_api_userName"]) || isset($_POST["amd_api_password"])){
			if ( !empty($_POST["amd_api_userName"]) ){
				$apg_vpos_accuonts["051"]["api_userName"] = sanitize_text_field($_POST["amd_api_userName"]);
			} else{
				$errMgs .= __( "Incorrect API username", 'apg' ) . " - AMD<br>";
			}
			if ( !empty($_POST["amd_api_password"]) ) {
				$apg_vpos_accuonts["051"]["api_password"] = $_POST["amd_api_password"];
			} else {
				$errMgs .= __( "Incorrect API password", 'apg' ) . " - AMD<br>";
			}
		}

		// validate RUB user, pass
		if(isset($_POST["rub_api_userName"]) || isset($_POST["rub_api_password"])){
			if ( !empty($_POST["rub_api_userName"]) ){
				$apg_vpos_accuonts["643"]["api_userName"] = sanitize_text_field($_POST["rub_api_userName"]);
			} else{
				$errMgs .= __( "Incorrect API username", 'apg' ) . " - RUB<br>";
			}
			if ( !empty($_POST["rub_api_password"]) ) {
				$apg_vpos_accuonts["643"]["api_password"] = $_POST["rub_api_password"];
			} else {
				$errMgs .= __( "Incorrect API password", 'apg' ) . " - RUB<br>";
			}
		}

		// validate USD user, pass
		if(isset($_POST["usd_api_userName"]) || isset($_POST["usd_api_password"])){
			if ( !empty($_POST["usd_api_userName"]) ){
				$apg_vpos_accuonts["840"]["api_userName"] = sanitize_text_field($_POST["usd_api_userName"]);
			} else{
				$errMgs .= __( "Incorrect API username", 'apg' ) . " - USD<br>";
			}
			if ( !empty($_POST["usd_api_password"]) ) {
				$apg_vpos_accuonts["840"]["api_password"] = $_POST["usd_api_password"];
			} else {
				$errMgs .= __( "Incorrect API password", 'apg' ) . " - USD<br>";
			}
		}

		// validate EUR user, pass
		if(isset($_POST["eur_api_userName"]) || isset($_POST["eur_api_password"])){
			if ( !empty($_POST["eur_api_userName"]) ){
				$apg_vpos_accuonts["978"]["api_userName"] = sanitize_text_field($_POST["eur_api_userName"]);
			} else{
				$errMgs .= __( "Incorrect API username", 'apg' ) . " - EUR<br>";
			}
			if ( !empty($_POST["eur_api_password"]) ) {
				$apg_vpos_accuonts["978"]["api_password"] = $_POST["eur_api_password"];
			} else {
				$errMgs .= __( "Incorrect API password", 'apg' ) . " - EUR<br>";
			}
		}

		// validate arca api PORT
		if( $arca_config->rest_serverID == 2 && ($arca_config->bankId != 10 && $bankId != 10 && $arca_config->bankId != 4 && $bankId != 4) ) {

			if(!empty($_POST["arca_test_api_port"]) && is_numeric($_POST["arca_test_api_port"])){
				$arca_test_api_port =  intval($_POST["arca_test_api_port"]);
			} else {
				$errMgs .= __( "Incorrect API Test Port:", 'apg' ) . "<br>";				
			}	

		} else {
			$arca_test_api_port = 0;
		}		

		// validate default_language
		if ( !empty($_POST["default_language"]) ) {
			$default_language = arca_pg_sanitize_input($_POST["default_language"]);
		} else {
			$errMgs .= __( "incorrect default_language", 'apg' ) . "<br>";
		}

		// validate default_currency
		if ( !empty($_POST["default_currency"]) ) {
			$default_currency = arca_pg_sanitize_input($_POST["default_currency"]);
		} else{
			$errMgs .= __( "incorrect default_currency", 'apg' ) . "<br>";
		}

		// validate orderNumberPrefix
		if ( !empty($_POST["orderNumberPrefix"]) ) {
			$orderNumberPrefix = arca_pg_sanitize_input($_POST["orderNumberPrefix"]);
		} else{
			$orderNumberPrefix = "";
		}

		// validate startOrderNumber
		if ( !empty($_REQUEST["startOrderNumber"]) && is_numeric($_REQUEST["startOrderNumber"]) ) {
			$startOrderNumber = intval($_REQUEST["startOrderNumber"]);
		} else {
			$startOrderNumber = 0;
		}

		// validate wc order status
		if ( !empty($_REQUEST["wc_order_status"]) ) {
			$wc_order_status = esc_attr($_REQUEST["wc_order_status"]);
		} else {
			$wc_order_status = "Processing";
		}

		// validate rest_serverID
		if ( !empty($_POST["rest_serverID"]) && is_numeric($_POST["rest_serverID"]) ) {
			$rest_serverID = intval($_POST["rest_serverID"]);
		} else {
			$errMgs .= __( "incorrect rest_serverID", 'apg' ) . "<br>";
		}

		// validate bankId
		if ( !empty($_POST["bankId"]) && is_numeric($_POST["bankId"]) ) {
			$bankId = intval($_POST["bankId"]);
		} else {
			$bankId = 0;
		}

		// validate ameriabankClientID
		if( $arca_config->bankId == 10 && $bankId == 10 ) {

			if(!empty($_POST["ameriabankClientID"])){
				$ameriabankClientID =  arca_pg_sanitize_input($_POST["ameriabankClientID"]);
			} else {
				$errMgs .= __( "Incorrect ameriabankClientID", 'apg' ) . "<br>";				
			}	

		} else {
			$ameriabankClientID = "";
		}

		// validate ameriabankMinTestOrderId
		if ( !empty($_POST["ameriabankMinTestOrderId"]) && is_numeric($_POST["ameriabankMinTestOrderId"]) ) {
			$ameriabankMinTestOrderId = intval($_POST["ameriabankMinTestOrderId"]);
		} else {
			$ameriabankMinTestOrderId = 0;
		}

		// validate ameriabankMaxTestOrderId
		if ( !empty($_POST["ameriabankMaxTestOrderId"]) && is_numeric($_POST["ameriabankMaxTestOrderId"]) ) {
			$ameriabankMaxTestOrderId = intval($_POST["ameriabankMaxTestOrderId"]);
		} else {
			$ameriabankMaxTestOrderId = 0;
		}		

		if( $errMgs == "" ) {

			// update configs
			$table = $wpdb->prefix.'arca_pg_config';
				$data = array(
				'vpos_accuonts'				=> json_encode($apg_vpos_accuonts),
				'default_language'			=> $default_language,
				'default_currency'			=> $default_currency,
				'orderNumberPrefix'			=> $orderNumberPrefix,
				'ameriabankClientID'		=> $ameriabankClientID,
				'ameriabankMinTestOrderId'	=> $ameriabankMinTestOrderId,
				'ameriabankMaxTestOrderId'	=> $ameriabankMaxTestOrderId,
				'arca_test_api_port'		=> $arca_test_api_port,
				'wc_order_status'			=> $wc_order_status,
			);

			$data_format = array('%s', '%s', '%s', '%s', '%s', '%d', '%d', '%d', '%s');
			$where = array('rest_serverID' => $rest_serverID);
			$where_format = array('%d');
			$wpdb->update( $table, $data, $where, $data_format, $where_format );

			// set start orders number if startOrderNumber exist 
			if ( $startOrderNumber > $arca_config->startOrderNumber ) {

				// alter table
				$wpdb->query("ALTER TABLE ".$wpdb->prefix."arca_pg_orders AUTO_INCREMENT = $startOrderNumber;");

				// update config startOrderNumber for display only
				$wpdb->query("update $table set startOrderNumber = $startOrderNumber");

			}

			$wpdb->query("update $table set bankId = $bankId");

			$strMgs = __( "Done!", 'apg' );
		}

	// switch work server
	} elseif ( $act == "switch-server" ) {

		// validate rest_serverID
		if ( !empty($_POST["rest_serverID"]) && is_numeric($_POST["rest_serverID"]) ) {
			$rest_serverID = intval($_POST["rest_serverID"]);
		} else {
			$errMgs .= __( "incorrect rest_serverID", 'apg' ) . "<br>";
		}

		// switch rest server

		// reset all
	    $table = $wpdb->prefix.'arca_pg_config';
	    $sql = "update ".$table." set active = 0";
		$wpdb->query($sql);

		// set one
		$sql = "update " . $table . " set active = 1 where rest_serverID = %d";
		$wpdb->query($wpdb->prepare($sql, $rest_serverID));

		$strMgs = __( "Done!", 'apg' );

	}

}

// sanitize form data
function arca_pg_sanitize_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

// ArCa Payments Gateway configs
$arca_config = $wpdb->get_row("select * from " . $wpdb->prefix . "arca_pg_config where active = 1");

// get vPOS accounts
$apg_vpos_accuonts = json_decode( $arca_config->vpos_accuonts, true );

$apg_hide_if_not_ameria = ($arca_config->bankId != 10) ? "apg-hidden": "";
?>

<div class="wrap apg" id="apg-config">
	
	<h1><?php _e( "vPOS Settings", 'apg' ) ?></h1>

	<p>
		<?php
			if($errMgs != "" || $strMgs != "" ){
				echo $errMgs, $strMgs;
			}
		?>
	</p>

	<?php
		$row = $wpdb->get_row("select * from ".$wpdb->prefix."arca_pg_config where active = 1", ARRAY_A);
	?>

	<form action="" method="post">

		<legend><?php _e( "Bank:", 'apg' ) ?></legend>
		<select name="bankId" id="apg-bank-switcher">
			<option value="0"></option>
			<?php
				$arca_banks = $wpdb->get_results("select * from ".$wpdb->prefix."arca_pg_banks order by bankName asc");
				if( $arca_banks ) {
					foreach ( $arca_banks as $arca_bank ) {
						echo "<option " . (($arca_bank->bankId == $arca_config->bankId) ? 'selected' : '') ." value='$arca_bank->bankId'>$arca_bank->bankName</option>";
					}
				}
			?>
		</select>
		<?php if($arca_config->bankId > 0){ ?>
			<img class='bank-logo' src='<?php echo esc_url(ARCAPG_URL . "/images/bank-logos/" . $arca_config->bankId . ".png"); ?>'>
		<?php } ?>

		<div class="<?php echo esc_attr($apg_hide_if_not_ameria); ?> apg-ameria-fields">
			<legend><?php _e( "Client ID:", 'apg' ) ?></legend>
			<input type="text" name="ameriabankClientID" value="<?php echo esc_attr($row["ameriabankClientID"]); ?>">
		</div>

		<?php
			// create accounts forms for currencies
			$currencies = $wpdb->get_results("select code, abbr from ".$wpdb->prefix."arca_pg_currency where active = 1");
			foreach ($currencies as $currency) {
		?>

		  	<h3 class="apg-vpos-login-pass-caption"><?php _e( "vPOS Account", 'apg' ); ?> <?php echo esc_html($currency->abbr); ?></h3>

			<legend><?php _e( "API username:", 'apg' ) ?></legend>
			<input type="text" name="<?php echo strtolower($currency->abbr); ?>_api_userName" value="<?php echo $apg_vpos_accuonts[$currency->code]["api_userName"]; ?>" autocomplete="off">
			
			<legend><?php _e( "API password:", 'apg' ) ?></legend>
			<div class="api-password-container">
				<input type="password" name="<?php echo strtolower($currency->abbr); ?>_api_password" class="api-password" value="<?php echo $apg_vpos_accuonts[$currency->code]["api_password"]; ?>" autocomplete="new-password">
				<span class="show-hide"></span>
			</div>			

		<?php
			}
		?>

		<div class="apg-saperator"></div>

		<br>

		<?php
			// if not AmeriaBank or Inecobank
			if($arca_config->bankId != 10 && $arca_config->bankId != 4 && $arca_config->rest_serverID == 2){ ?>

			<legend><?php _e( "API Test Port:", 'apg' ) ?></legend>
			<input type="text" name="arca_test_api_port" value="<?php echo intval($row["arca_test_api_port"]); ?>">
		<?php } ?>		

		<legend><?php _e( "Default language:", 'apg' ) ?></legend>
		<select name="default_language">
		<?php
			$arca_languages = $wpdb->get_results("select * from ".$wpdb->prefix."arca_pg_language order by language");
			if( $arca_languages ) {
				foreach ( $arca_languages as $language ) {
					echo "<option " . (($row["default_language"] == $language->code) ? 'selected' : '') ." value='". esc_attr($language->code) ."'>". esc_html($language->code ." - ". $language->language) ."</option>";
				}
			}
		?>
		</select>	

		<legend><?php _e( "Default currency:", 'apg' ) ?></legend>
		<select name="default_currency">
		<?php
			$arca_currencies = $wpdb->get_results("select * from ".$wpdb->prefix."arca_pg_currency where active = 1 order by code");
			if( $arca_currencies ) {
				foreach ( $arca_currencies as $currency ) {
					echo "<option " . (($row["default_currency"] == $currency->code) ? 'selected' : '') ." value='". esc_attr($currency->code) ."'>". esc_html($currency->abbr ." - ". $currency->name) ."</option>";
				}
			}
		?>
		</select>

		<?php
			// if not AmeriaBank
			if($arca_config->bankId != 10){ ?>

			<legend><?php _e( "Orders number prefix:", 'apg' ) ?></legend>
			<input type="text" name="orderNumberPrefix" value="<?php echo esc_attr($row["orderNumberPrefix"]); ?>">
		<?php } ?>

		<?php
			// if not AmeriaBank or AmeriaBank and real server
			if($arca_config->bankId != 10 || ($arca_config->bankId == 10 && $arca_config->rest_serverID == 1)){ ?>

			<legend><?php _e( "Order number starting from", 'apg' ) ?></legend>
			<input type="text" name="startOrderNumber" value="<?php echo esc_attr($row["startOrderNumber"]); ?>">
		    <p class="description"><?php _e( "Orders will increase starting from the specified number, the type must be integer (for example, 100).", 'apg' ) ?></p>					
		<?php } ?>

		<?php
			// if AmeriaBank and test server
			if($arca_config->bankId == 10 && $arca_config->rest_serverID == 2){ ?>

			<legend><?php _e( "Start of the OrderID range in the test mode:", 'apg' ) ?></legend>
			<input type="text" name="ameriabankMinTestOrderId" value="<?php echo esc_attr($row["ameriabankMinTestOrderId"]); ?>">

			<legend><?php _e( "End of the OrderID range in the test mode:", 'apg' ) ?></legend>
			<input type="text" name="ameriabankMaxTestOrderId" value="<?php echo esc_attr($row["ameriabankMaxTestOrderId"]); ?>">
		<?php } ?>
		
		<?php if ( class_exists('woocommerce') ) { ?>
			<legend><?php _e( "WooCommerce order status:", 'apg' ) ?></legend>
			<select name="wc_order_status">
				<option value="processing" <?php echo ($row["wc_order_status"] == "processing") ? 'selected' : ''; ?>>Processing</option>
				<option value="completed" <?php echo ($row["wc_order_status"] == "completed") ? 'selected' : ''; ?>>Completed</option>
			</select>
		<?php } ?>

	    <br>

	    <input type="hidden" name="rest_serverID" value="<?php echo $row["rest_serverID"]; ?>">
		<input type="hidden" name="act" value="save">
		<input class="submitLink button-primary" type="submit" value="<?php _e( "Save", 'apg' )?>">

	</form>

	<br>
	<h2><?php _e( "Working mode", 'apg' ) ?></h2>

	<div class="mode-switcher">
		<input disabled readonly="" type="radio" name="rest_serverID" value="1"> Real server
		<input disabled readonly="" type="radio" name="rest_serverID" checked value="2"> Test server
		<span class="actions">
			<input type="hidden" name="act" value="switch-server">
			<input disabled readonly class="submitLink button-primary" type="button" value="<?php _e( "Switch", 'apg' ) ?>">
			<a href="https://store.planetstudio.am/product/arca-payment-gateway-pro/" target="_blank" class="upgrade-to-pro"><?php _e( "Upgrade to PRO", 'apg' ) ?></a>
		</span>
	</div>

</div>
