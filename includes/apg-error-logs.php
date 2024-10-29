<?php
global $wpdb, $arca_config;

$act = (isset($_GET["act"])) ? sanitize_text_field($_GET["act"]) : "";
$id = (isset($_GET["id"]) && is_numeric($_GET["id"])) ? intval($_GET["id"]) : 0;

// get test or real logs
$rest_serverID = ( isset($_GET["rest_serverID"]) && is_numeric($_GET["rest_serverID"]) ) ? intval($_GET["rest_serverID"]) : $arca_config->rest_serverID ;

$strMgs = $errMgs = "";

if ( $act == "delete" ) {
	$sql = "delete from ".$wpdb->prefix."arca_pg_errorlogs where id = '%d'";
	$wpdb->query( $wpdb->prepare($sql, $id) );
	$strMgs = __( "Done!", 'apg' );
} else if ( $act == "delete_all" ){
	$sql = "delete from ".$wpdb->prefix."arca_pg_errorlogs";
	$wpdb->query( $wpdb->prepare($sql) );
	$strMgs = __( "Done!", 'apg' );	
}
?>

<div class="wrap apg" id="apg-errorLogs">
	<h1><?php _e( "Error logs", 'apg' ) ?></h1>
	<p>
		<?php
    if ( $errMgs != "" || $strMgs != "" ) {
      echo $errMgs, $strMgs;
    }
		?>
	</p>

	<p>
		<a style="margin-right:20px" class="linkDelate button" onclick="return confirmDelete();" href="<?php echo esc_url("?page=errorlogs&act=delete_all"); ?>"><?php _e( "Delete All", 'apg' )?></a>
		<a class="button<?php echo (($rest_serverID == 1) ? '-primary' : '');?>" href="<?php echo esc_url("?page=errorlogs&rest_serverID=1"); ?>"><?php _e( "Real Orders", 'apg' )?></a>
		<a class="button<?php echo (($rest_serverID == 2) ? '-primary' : '');?>" href="<?php echo esc_url("?page=errorlogs&rest_serverID=2"); ?>"><?php _e( "Test Orders", 'apg' )?></a>
	</p>
	
	<table>
	<tr>
		<th><?php _e( "ID", 'apg' )?></th>
		<th><?php _e( "Date", 'apg' )?></th>
		<th><?php _e( "Error", 'apg' )?></th>
		<th><?php _e( "Actions", 'apg' )?></th>
	</tr>

	<?php
	$result = $wpdb->get_results($wpdb->prepare("select * from ".$wpdb->prefix."arca_pg_errorlogs where rest_serverID = '%d' order by id desc", $rest_serverID));

  foreach ( $result as $row ) {
	?>	
		<tr>
			<td><?php echo esc_html($row->id); ?></td>
			<td><?php echo esc_html($row->dateTime); ?></td>
			<td><?php echo esc_html($row->error); ?></td>
			<td class="actions">
				<a class="linkDelate button" onclick="return confirmDelete();" href="<?php echo esc_url("?page=errorlogs&act=delete&id=" . $row->id); ?>"><?php _e( "Delete", 'apg' )?></a>
			</td>
		</tr>
	<?php
		}
	?>
	</table>

</div>
