<?php
global $wpdb, $arca_config;
//$wpdb->show_errors(); 

$errMgs = $strMgs = "";

if ( $_SERVER["REQUEST_METHOD"] == "POST" ) {

	$act = (isset($_POST["act"])) ? sanitize_text_field($_POST["act"]) : "";
	if( $act == "save" ) {

		$id = (isset($_POST["id"])) ? intval($_POST["id"]) : 0;
		$active = (isset($_POST["active"])) ? intval($_POST["active"]) : 0;

	    $table = $wpdb->prefix."arca_pg_currency";
	    $data = array(
	      'active'	=> $active,
	    );
	    $format = array('%d');
	    $where = array('id' => $id);
	    $where_format = array('%d');

	    // update payment form elements
	    $wpdb->update( $table, $data, $where, $format, $where_format );
	    
		$strMgs = __( "Done!", 'apg' );
	}

}
?>

<div class="wrap apg" id="apg-currency">
  <h1><?php _e( "Currency", 'apg' ) ?></h1>

	<p>
		<?php
			if ( $errMgs != "" || $strMgs != "" ) {
				echo $errMgs, $strMgs;
			}
		?>
	</p>

	<table>
	<tr>
		<th class="center"><?php _e( "Currency code", 'apg' ) ?></th>
		<th class="center"><?php _e( "Currency abbr", 'apg' ) ?></th>
		<th><?php _e( "Currency name", 'apg' ) ?></th>
		<th class="center"><?php _e( "Active", 'apg' ) ?></th>
		<th></th>
	</tr>
	<?php
		$result = $wpdb->get_results("select * from ".$wpdb->prefix."arca_pg_currency");
		foreach ( $result as $row ) {
	?>	
	<form method="post">
		<tr>
			<td class="center"><?php echo esc_html($row->code); ?></td>
			<td class="center"><?php echo esc_html($row->abbr); ?></td>
			<td><?php echo esc_html($row->name); ?></td>
			<td class="center"><input type="checkbox" name="active" value="1" <?php echo ($row->active == 1) ? 'checked' : '' ?> <?php echo ($row->abbr == $arca_config->default_currency) ? 'disabled' : '' ?>></td>
			<td class="actions">
				<input type="hidden" name="act" value="save">
				<input type="hidden" name="id" value="<?php echo esc_attr($row->id); ?>">

				<?php if ( $row->code != $arca_config->default_currency ) { ?>
					<input class="submitLink button button-primary" type="submit" value="<?php _e( "Save", 'apg' )?>">
				<?php } else {?>
					<?php _e( "Default", 'apg' ) ?>
				<?php } ?>

			</td>
		</tr>
	</form>
	<?php
		}
	?>
	</table>

</div>
