<?php
global $wpdb, $arca_config;

$errMgs = $strMgs = "";

// get payment form elements from $arca_confog
$formElements = json_decode( $arca_config->checkoutFormElements );

if ( $_SERVER["REQUEST_METHOD"] == "POST" ) {
	
	$act = ( isset($_POST["act"]) ) ? sanitize_text_field($_POST["act"]) : "";
	if ( $act == "save" ) {

		$elementName = (isset($_POST["elementName"])) ? sanitize_text_field($_POST["elementName"]) : "";
		$elementEnabled = (isset($_POST["elementEnabled"])) ? sanitize_text_field($_POST["elementEnabled"]) : false;
		$elementRequired = (isset($_POST["elementRequired"])) ? sanitize_text_field($_POST["elementRequired"]) : false;

		$formElements->$elementName->enabled = $elementEnabled;
		$formElements->$elementName->required = $elementRequired;

		$table = $wpdb->prefix."arca_pg_config";
		$data = array(
			'checkoutFormElements'  => json_encode($formElements),
		);

		$format = array('%s');
		$where = array('rest_serverID' => $arca_config->rest_serverID);
		$where_format = array('%d');

		// update payment form elements
		$wpdb->update( $table, $data, $where, $format, $where_format );

		$strMgs = __( "Done!", 'apg' );

	}else if($act == "set-checkout-form-page"){

		$checkoutFormPage = (isset($_POST["checkoutFormPage"])) ? sanitize_text_field($_POST["checkoutFormPage"]) : "";
		$table = $wpdb->prefix."arca_pg_config";
		$wpdb->query("update $table set checkoutFormPage = '$checkoutFormPage'");

		$strMgs = __( "Done!", 'apg' );

	}else if ($act == "arca-privacy-and-policy"){

		$privacyPolicyPage = (isset($_POST["privacyPolicyPage"])) ? sanitize_text_field($_POST["privacyPolicyPage"]) : "";
		$table = $wpdb->prefix."arca_pg_config";
		$wpdb->query("update $table set privacyPolicyPage = '$privacyPolicyPage'");

		$strMgs = __( "Done!", 'apg' );

	}else if ($act == "save-email"){
			
		// validate mailFrom
		if ( !empty($_POST["mailFrom"]) ) {
			$mailFrom = sanitize_email($_POST["mailFrom"]);
			if ( !filter_var($mailFrom, FILTER_VALIDATE_EMAIL) ) {
				$errMgs .= __( "Incorrect email address", 'apg' ) . "<br>";
			}
		} else {
			$mailFrom = "";
		}
		
		if( $errMgs == "" ) {
			
			// update mailFrom
			$table = $wpdb->prefix.'arca_pg_config';
			$wpdb->query("update $table set mailFrom = '$mailFrom'");
			
			$strMgs = __( "Done!", 'apg' );
		}		
		
	}

}

// ArCa Payments Gateway configs
$arca_config = $wpdb->get_row("select * from " . $wpdb->prefix . "arca_pg_config where active = 1");
?>
<div class="wrap apg" id="apg-checkoutForm">
	<h1><?php _e( "Checkout form", 'apg' ) ?></h1>

	<h2><?php _e( "Billing details", 'apg' ) ?></h2>

	<p>
		<?php
	if ( $errMgs != "" || $strMgs != "" ) {
		echo $errMgs, $strMgs;
	}
		?>
	</p>

	<table>
		<tr>
			<th><?php _e( "Input name", 'apg' )?></th>
			<th class="center"><?php _e( "Type", 'apg' )?></th>
			<th class="center"><?php _e( "Enabled", 'apg' )?></th>
			<th class="center"><?php _e( "Required", 'apg' )?></th>
			<th class="actions"><?php _e( "Actions", 'apg' )?></th>
		</tr>
		<?php foreach ( $formElements as $elementName => $elementOptions ) { ?>
		<form method="post">
			<tr>
				<td><?php _e( $elementOptions->label, 'apg' )?></td>
				<td class="center"><?php echo ucfirst(esc_html($elementOptions->type)) ?></td>
				<td class="center"><input type="checkbox" name="elementEnabled" value="true" <?php echo ($elementOptions->enabled) ? "checked" : "" ?>></td>
				<td class="center"><input type="checkbox" name="elementRequired" value="true" <?php echo ($elementOptions->required) ? "checked" : "" ?>></td>
				<td class="actions">
					<input type="hidden" name="act" value="save">
					<input type="hidden" name="elementName" value="<?php echo esc_attr($elementName); ?>">
					<input class="submitLink button button-primary" type="submit" value="<?php _e( "Save", 'apg' )?>">
				</td>
			</tr>
		</form>
		<?php } ?>
	</table>

	<br>

	<legend><?php _e( "Shortcode", 'apg' ) ?></legend>

	<div id="shortcode-1" class="copyToClipboard shortcode" onclick="CopyToClipboard('shortcode-1')" title="<?php _e( "Copy", 'apg' ) ?>">
		[arca-pg-form]
		<span class="dashicons dashicons-admin-page"></span>
	</div>

	<br>
	
	<legend><?php _e( "Email from:", 'apg' ) ?></legend>
	<form method="post" id="mailFrom">
		<input type="text" name="mailFrom" value="<?php echo esc_attr($arca_config->mailFrom); ?>">
		<input type="hidden" name="act" value="save-email">
		<input class="submitLink button-primary" type="submit" value="<?php _e( "Save", 'apg' )?>">
	</form>
	<p><?php _e( "Specify the email address from which the payer will receive the invoice, admin email is default.", 'apg' ) ?></p>
	
	<br>
	
	<h2><?php _e( "Checkuot page", 'apg' )?></h2>
	
	<form method="post">
		<select name="checkoutFormPage">
			<option value="<?php echo esc_attr($arca_config->checkoutFormPage); ?>"><?php echo esc_html($arca_config->checkoutFormPage); ?></option>
			<optgroup label="<?php _e( "Select to change", 'apg' )?>">
				<?php
	// get all pages
	$pages = get_pages( [
		'sort_order'   => 'ASC',
		'sort_column'  => 'post_title',
		'hierarchical' => 1,
		'exclude'      => '',
		'include'      => '',
		'meta_key'     => '',
		'meta_value'   => '',
		'authors'      => '',
		'child_of'     => 0,
		'parent'       => -1,
		'exclude_tree' => '',
		'number'       => '',
		'offset'       => 0,
		'post_type'    => 'page',
		'post_status'  => 'publish',
	] );
					  foreach( $pages as $post ){
						  // get end decode post slug
						  $arca_pg_postName = urldecode($post->post_name);
						  echo "<option value='" . esc_attr($arca_pg_postName) . "'>" . esc_html($post->post_title) . (($arca_config->checkoutFormPage == $arca_pg_postName) ? " &larr;" : "") . "</option>";
					  }  
				?>
			</optgroup>
		</select>
		<input type="hidden" name="act" value="set-checkout-form-page">
		<input class="submitLink button button-primary" type="submit" value="<?php _e( "Save", 'apg' )?>">
	</form>

	<br>

	<h2><?php _e( "Privacy Policy Page", 'apg' )?></h2>

	<form method="post">
		<select name="privacyPolicyPage">
			<option value="<?php echo esc_attr($arca_config->privacyPolicyPage); ?>"><?php echo esc_html($arca_config->privacyPolicyPage); ?></option>
			<optgroup label="<?php _e( "Select to change", 'apg' )?>">
				<?php
	// get all pages
	$pages = get_pages( [
		'sort_order'   => 'ASC',
		'sort_column'  => 'post_title',
		'hierarchical' => 1,
		'exclude'      => '',
		'include'      => '',
		'meta_key'     => '',
		'meta_value'   => '',
		'authors'      => '',
		'child_of'     => 0,
		'parent'       => -1,
		'exclude_tree' => '',
		'number'       => '',
		'offset'       => 0,
		'post_type'    => 'page',
		'post_status'  => 'publish',
	] );
					  foreach( $pages as $post ){
						  // get end decode post slug
						  $arca_pg_postName = urldecode($post->post_name);
						  echo "<option value='" . esc_attr($arca_pg_postName) . "'>" . esc_html($post->post_title) . (($arca_config->privacyPolicyPage == $arca_pg_postName) ? " &larr;" : "") . "</option>";
					  }  
				?>
			</optgroup>
		</select>
		<input type="hidden" name="act" value="arca-privacy-and-policy">
		<input class="submitLink button button-primary" type="submit" value="<?php _e( "Save", 'apg' )?>">
	</form>

	<br>

</div>