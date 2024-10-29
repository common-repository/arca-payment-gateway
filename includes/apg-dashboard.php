<?php
global $wpdb, $arca_config;

if ( ARCAPG_PRO ) {

  
}

// array to table
function arca_pg_array2table( $array, $recursive = false, $null = '&nbsp;' )
{
    // Sanity check
    if ( empty($array) || !is_array($array) ) {
        return false;
    }
    if ( !isset($array[0]) || !is_array($array[0]) ) {
        $array = array($array);
    }
    // Start the table
    $table = "<table>\n";
    // The header
    $table .= "\t<tr>";
    // Take the keys from the first row as the headings
    foreach ( array_keys($array[0]) as $heading ) {
        $table .= '<th>' . $heading . '</th>';
    }
    $table .= "</tr>\n";
    // The body
    foreach ( $array as $row ) {
        $table .= "\t<tr>";
        foreach ( $row as $cell ) {
            $table .= '<td>';
            // Cast objects
            if ( is_object($cell) ) {
                $cell = (array) $cell;
            }
            if ( $recursive === true && is_array($cell) && !empty($cell) ) {
                // Recursive mode
                $table .= "\n" . arca_pg_array2table($cell, true, true) . "\n";
            } else {
                $table .= strlen($cell) > 0 ? htmlspecialchars((string) $cell) : $null;
            }
            $table .= '</td>';
        }
        $table .= "</tr>\n";
    }
    $table .= '</table>';
    return $table;
}

?>

<div class="wrap apg" id="apg-dashboard">

    <h1><?php _e( "Dashboard", 'apg' ) ?></h1>
	
	<h2><?php _e( "Gateway status", 'apg' ) ?></h2>
    	
	<?php
		if ( ARCAPG_PRO ) {
			do_action('apg_activate_form');
		}   
	?>
	
	<span><?php _e( "Plugin version:", 'apg' ) ?> </span>
	<?php echo ARCAPG_VERSION; ?>
	
	<div class="apg-saperator"></div>
	
	<?php
	// ArCa Payments Gateway configs
	$arca_config = $wpdb->get_row("select * from " . $wpdb->prefix . "arca_pg_config where active = 1");

	// get vPOS accounts
	$apg_vpos_accuonts = json_decode( $arca_config->vpos_accuonts, true );
		
	// Idram configs
	$arca_idram_config = $wpdb->get_row("select * from " . $wpdb->prefix . "arca_pg_idram_config where id = 1");			
	?>		

	
	<span><?php _e( "Bank:", 'apg' ) ?> </span>
	<?php
	// get bank name
	$bank_name = $wpdb->get_var($wpdb->prepare("SELECT bankName  from " . $wpdb->prefix . "arca_pg_banks where bankId = '%d'", $arca_config->bankId));
	echo $bank_name; ?>

	<br>
	
	<span><?php _e( "Active currencies:", 'apg' ) ?> </span>
	<?php
	// get Bank vPOS active accounts
	$currencies = $wpdb->get_results("select code, abbr from ".$wpdb->prefix."arca_pg_currency where active = 1");
	foreach ($currencies as $currency) {
 		echo esc_html($currency->abbr) . " ";
	}?>		
	
	<br>
	
	<span><?php _e( "Default currency:", 'apg' ) ?> </span>
	<?php
	$currency = $wpdb->get_var( $wpdb->prepare("SELECT abbr from ".$wpdb->prefix."arca_pg_currency where code = '%s'", $arca_config->default_currency) );
	echo $currency; ?>	
	
	<br>
	
	<span><?php _e( "Default language:", 'apg' ) ?> </span>
	<?php
	$language = $wpdb->get_var( $wpdb->prepare("SELECT language from ".$wpdb->prefix."arca_pg_language where code = '%s'", $arca_config->default_language) );
	echo $language; ?>	
	
	<br>
	
	<span><?php _e( "Working mode:", 'apg' ) ?> </span>
	<?php echo ($arca_config->rest_serverID == 2) ? __( "Test server", 'apg' ) : __( "Real server", 'apg' ) ?>
	
	
	<div class="apg-saperator"></div>
	
	<span><?php _e( "Idram status:", 'apg' ) ?> </span>
	<?php echo ($arca_idram_config->idramEnabled) ? __( "Enabled", 'apg' ) : __( "Disabled", 'apg' ) ?>
	
	<br>
	
	<span><?php _e( "Idram test mode:", 'apg' ) ?> </span>
	<?php echo ($arca_idram_config->testMode) ? __( "Enabled", 'apg' ) : __( "Disabled", 'apg' ) ?>
	
	<br>

	<span><?php _e( "Roket Line:", 'apg' ) ?> </span>
	<?php echo ($arca_idram_config->rocketLine) ? __( "Enabled", 'apg' ) : __( "Disabled", 'apg' ) ?>

	
	<br>
	<br>
	
	<h2><?php _e( "Finance report", 'apg' ) ?></h2>
    <?php

    $sql_if = "";
    $sql = "";
    $prepareParams = array();
    $result = $wpdb->get_results("select code, abbr from ".$wpdb->prefix."arca_pg_currency where active = 1");
    foreach ($result as $row) {
      $sql_if .= ", SUM(IF(currency = '%s', amount, 0)) AS '%s'";
      $prepareParamsif[] = $row->code;
      $prepareParamsif[] = $row->abbr;
    }

    $prepareParams = $prepareParamsif;
    $prepareParams[] = $arca_config->rest_serverID;
    $prepareParams = array_merge($prepareParams,$prepareParamsif);
    $prepareParams[] = $arca_config->rest_serverID;
    $prepareParams = array_merge($prepareParams,$prepareParamsif);
    $prepareParams[] = $arca_config->rest_serverID;
    $prepareParams = array_merge($prepareParams,$prepareParamsif);
    $prepareParams[] = $arca_config->rest_serverID;
    $prepareParams = array_merge($prepareParams,$prepareParamsif);
    $prepareParams[] = $arca_config->rest_serverID;
    $sql .= "SELECT ('" . __( "Today:", 'apg' ) . "') as " . __( "Orders", 'apg' ) . ", count(orderNumber) as " . __( "Count", 'apg' ) . " $sql_if FROM ".$wpdb->prefix."arca_pg_orders WHERE DATE(orderDate) = CURDATE() AND (paymentState = 'DEPOSITED' OR paymentState = 'Successful' ) AND rest_serverID = '%d'";
    $sql .= " UNION ";
    $sql .= "SELECT ('" . __( "This week:", 'apg' ) . "') as " . __( "Orders", 'apg' ) . ", count(orderNumber) as " . __( "Count", 'apg' ) . " $sql_if FROM ".$wpdb->prefix."arca_pg_orders WHERE  YEARWEEK(orderDate, 1) = YEARWEEK(CURDATE(), 1)	AND (paymentState = 'DEPOSITED' OR paymentState = 'Successful' ) AND rest_serverID = '%d'";
    $sql .= " UNION ";
    $sql .= "SELECT ('" . __( "Last week:", 'apg' ) . "') as " . __( "Orders", 'apg' ) . ", count(orderNumber) as " . __( "Count", 'apg' ) . " $sql_if FROM ".$wpdb->prefix."arca_pg_orders WHERE orderDate >= now() - INTERVAL DAYOFWEEK(now())+6 DAY AND orderDate < now() - INTERVAL DAYOFWEEK(now())-1 DAY AND (paymentState = 'DEPOSITED' OR paymentState = 'Successful' ) AND rest_serverID = '%d'";
    $sql .= " UNION ";
    $sql .= "SELECT ('" . __( "Last month:", 'apg' ) . "') as " . __( "Orders", 'apg' ) . ", count(orderNumber) as " . __( "Count", 'apg' ) . " $sql_if FROM ".$wpdb->prefix."arca_pg_orders WHERE YEAR(orderDate) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND MONTH(orderDate) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH) AND (paymentState = 'DEPOSITED' OR paymentState = 'Successful' ) AND rest_serverID = '%d'";
    $sql .= " UNION ";
    $sql .= "SELECT ('" . __( "All time:", 'apg' ) . "') as " . __( "Orders", 'apg' ) . ", count(orderNumber) as " . __( "Count", 'apg' ) . " $sql_if FROM ".$wpdb->prefix."arca_pg_orders WHERE (paymentState = 'DEPOSITED' OR paymentState = 'Successful' ) AND rest_serverID = '%d'";
    $report = $wpdb->get_results( $wpdb->prepare($sql, $prepareParams), ARRAY_A );

    echo arca_pg_array2table($report);
    ?>

</div>
