<?php
global $wpdb, $arca_config;

// set admin visit date
$wpdb->query("update ".$wpdb->prefix."arca_pg_config set adminLastVisitDate = now() where active = 1");

// get test or real logs
$rest_serverID = ( isset($_GET["rest_serverID"]) && is_numeric($_GET["rest_serverID"]) ) ? intval($_GET["rest_serverID"]) : $arca_config->rest_serverID ;

$act = (isset($_GET["act"])) ? sanitize_text_field($_GET["act"]) : "";
$orderNumber = (isset($_GET["orderNumber"]) && is_numeric($_GET["orderNumber"])) ? intval($_GET["orderNumber"]) : 0;
$strMgs = $errMgs = "";

if ( $act == "delete" ) {
	$sql = "delete from ".$wpdb->prefix."arca_pg_orders where orderNumber = $orderNumber";
	$wpdb->query( $wpdb->prepare($sql, $orderNumber) );
	$strMgs = __( "Done!", 'apg' );
} else if ( $act == "delete_all" ){
	$sql = "delete from ".$wpdb->prefix."arca_pg_orders";
	$wpdb->query( $wpdb->prepare($sql) );
	$strMgs = __( "Done!", 'apg' );	
}
?>

<div id="apg-jsonView" class="apg-popup">
	<span class="popupClose"><?php _e( "Close", 'apg' )?></span>
	<textarea readonly></textarea>
</div>
	
<div class="wrap apg" id="apg-orders">


	<h1><?php _e( "Order log", 'apg' ) ?></h1>

	<?php
		if ($arca_config->bankId == 10){
			$arca_adminPageUrl = "https://". (($arca_config->rest_serverID == 2) ? "test" : "") ."payments.ameriabank.am/admin/clients";
		} elseif($arca_config->bankId == 4){
			$arca_adminPageUrl = "https://pg.inecoecom.am/admin/";
		} else {
			$arca_adminPageUrl = "https://ipay". (($arca_config->rest_serverID == 2) ? "test" : "") .".arca.am:". (($arca_config->rest_serverID == 2) ? "8444" : "") ."/payment/admin";
		}
	?>
	<p><a href="<?php echo esc_url($arca_adminPageUrl);?>" target="_blank"><?php _e( "ArCa admin panel", 'apg' ) ?></a></p>

	<p>
		<?php
			if ( $errMgs != "" || $strMgs != "" ){
				echo $errMgs, $strMgs;
			}
		?>
	</p>

	<p>
		<a style="margin-right:20px" class="linkDelate button" onclick="return confirmDelete();" href="<?php echo esc_url("?page=оrderlog&act=delete_all"); ?>"><?php _e( "Delete All", 'apg' )?></a>
		<a class="button<?php echo (($rest_serverID == 1) ? '-primary' : '');?>" href="<?php echo esc_url("?page=оrderlog&rest_serverID=1"); ?>"><?php _e( "Real Orders", 'apg' )?></a>
		<a class="button<?php echo (($rest_serverID == 2) ? '-primary' : '');?>" href="<?php echo esc_url("?page=оrderlog&rest_serverID=2"); ?>"><?php _e( "Test Orders", 'apg' )?></a>
	</p>
	
	<table>
	<tr>
		<th><?php _e( "Order number", 'apg' ) ?></th>
		<th><?php _e( "Order id", 'apg' ) ?></th>
		<th><?php _e( "Product id", 'apg' ) ?></th>
		<th><?php _e( "WC order", 'apg' ) ?></th>
		<th><?php _e( "Amount", 'apg' ) ?></th>
		<th><?php _e( "Currency", 'apg' ) ?></th>
		<th><?php _e( "Error code", 'apg' ) ?></th>
		<th><?php _e( "Payment state", 'apg' ) ?></th>
		<th><?php _e( "Order date", 'apg' ) ?></th>
		<th><?php _e( "Email sent", 'apg' ) ?></th>
		<th><?php _e( "Order status", 'apg' ) ?></th>
		<th><?php _e( "Order details", 'apg' ) ?></th>
		<th><?php _e( "Actions", 'apg' )?></th>
	</tr>

	<?php
		$p =  ( isset($_REQUEST['p']) ) ? intval($_REQUEST['p']) : 1;
		$results_per_page = 15;
		$page_first_result = ($p-1) * $results_per_page;
		
		$wpdb->get_results($wpdb->prepare("select * from ".$wpdb->prefix."arca_pg_orders where rest_serverID = '%d'", $arca_config->rest_serverID));
		$number_of_result = $wpdb->num_rows;
			
		$number_of_page = ceil ($number_of_result / $results_per_page);
								
		$result = $wpdb->get_results($wpdb->prepare("select * from ".$wpdb->prefix."arca_pg_orders where rest_serverID = '%d' order by orderDate desc LIMIT $page_first_result, $results_per_page", $rest_serverID));
			
		foreach ( $result as $row ) {
	  ?>
		<tr>
			<td><?php echo esc_html($row->orderNumber); ?></td>
			<td><?php echo esc_html($row->orderId); ?></td>
			<td><?php echo esc_html($row->productId); ?></td>
			<td><?php echo esc_html($row->wc_orderId); ?></td>
			<td><?php echo esc_html($row->amount); ?></td>
			<td><?php echo esc_html($row->currency); ?></td>
			<td><?php echo esc_html($row->errorCode); ?></td>
			<td <?php echo ($row->paymentState == "DECLINED") ? "class='paymentStateDECLINED'" : "" ?>><?php echo esc_html($row->paymentState); ?></td>
			<td><?php echo $row->orderDate; ?></td>
			<td class="center"><?php echo ($row->mailSent) ? '<span class="dashicons dashicons-yes-alt"></span>' : ''; ?></td>
			<td <?php echo (!empty($row->OrderStatusExtended)) ? "class='jsonView'" : '' ?>>
				<?php if(!empty($row->OrderStatusExtended)){ ?>
					<span class="dashicons dashicons-media-text"></span>
					<span class="jsonData"><?php echo htmlentities($row->OrderStatusExtended); ?></span>
				<?php } ?>
			</td>
			<td <?php echo (!empty($row->orderDetails)) ? "class='jsonView'" : '' ?>>
				<?php if(!empty($row->orderDetails)){ ?>
					<span class="dashicons dashicons-media-text"></span>
					<span class="jsonData"><?php echo htmlentities($row->orderDetails); ?></span>
				<?php } ?>
			</td>
			<td class="actions">
				<a class="linkDelate button" onclick="return confirmDelete();" href="<?php echo esc_url("?page=оrderlog&act=delete&orderNumber=".$row->orderNumber); ?>"><?php _e( "Delete", 'apg' )?></a>
			</td>
		</tr>
	<?php
		}
	?>
	</table>
	
	<div id="apg-pagination">
		<?php
			for($i = 1; $i <= $number_of_page; $i++) {
				$class = ($i == $p) ? "class='current'" : "";
				echo "<a $class href='?page=оrderlog&p=$i'>$i</a>";
			}
		?>
	</div>

</div>
