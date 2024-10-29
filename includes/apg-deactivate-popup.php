<?php
if( $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST["apgDeactivate"]) && $_POST["apgDeactivate"] == "deactivate" ){

	// get plugin deactivate url
	$apgDeactivateUrl = ( isset($_POST["apgDeactivateUrl"]) ) ? sanitize_url($_POST["apgDeactivateUrl"]) : "?";

    // get deactivate reason
	$reason = ( isset($_POST["reason"]) ) ? intval($_POST["reason"]) : 0;
	
    // get deactivate user reason
	$userReason = ( isset($_POST["user-reason"]) ) ? sanitize_text_field($_POST["user-reason"]) : null;

	$requestUrl = "https://store.planetstudio.am/test.php";
	$args = array(
		'headers'	=> array('Content-Type: text/html; charset=UTF-8'),
		'body'		=> array(
			'domain'		=> get_site_url(),
			'reason'		=> $reason,
			'userReason'	=> $userReason,
			'plugin'		=> 'ArCa Payment Gateway ' . ARCAPG_VERSION,
		),
		'method'		=> 'POST',
		'data_format'	=> 'body',
	);
	wp_remote_post( $requestUrl, $args );

	// redirect to bank page
	wp_redirect($apgDeactivateUrl);
	exit;

}
?>
<div id="apg-deactivate-popup" class="apg-popup">
	<div id="apg-deactivate-form-container">
		
		<form method="post">

			<div class="apg-deactivate-form-header">
				<?php _e( "ArCa Payment Gateway Deactivation", 'apg' ) ?>
			</div>

			<div class="apg-deactivate-form-body">

				<p><?php _e( "If you have a moment, please let us know why you are deactivating this plugin. All submissions are anonymous and we only use this feedback to improve this plugin.", 'apg' ) ?></p>

				<label>
					<input type="radio" name="reason" value="1">
					<?php _e( "I'm only deactivating temporarily", 'apg' ) ?>
				</label>
				<label>
					<input type="radio" name="reason" value="2">
					<?php _e( "I no longer need the plugin", 'apg' ) ?>
				</label>
				<label>
					<input type="radio" name="reason" value="3">
					<?php _e( "I only needed the plugin for a short period", 'apg' ) ?>
				</label>
				<label>
					<input type="radio" name="reason" value="4">
					<?php _e( "The plugin broke my site", 'apg' ) ?>
				</label>
				<div id="apg-reason-other-field-4" class="apg-reason-other-field">
					<p><?php _e( "We're sorry to hear that, check our support. Can you describe the issue?", 'apg' ) ?></p>
					<textarea name="user-reason" rows="6"></textarea>
				</div>			
				<label>
					<input type="radio" name="reason" value="5">
					<?php _e( "The plugin suddenly stopped working", 'apg' ) ?>
				</label>
				<div id="apg-reason-other-field-5" class="apg-reason-other-field">
					<p><?php _e( "We're sorry to hear that, check our support. Can you describe the issue?", 'apg' ) ?></p>
					<textarea name="user-reason" rows="6"></textarea>
				</div>
				<label>
					<input type="radio" name="reason" value="6">
					<?php _e( "Other", 'apg' ) ?>
				</label>
				<div id="apg-reason-other-field-6" class="apg-reason-other-field">
					<p><?php _e( "Please describe why you're deactivating", 'apg' ) ?></p>
					<textarea name="user-reason" rows="6"></textarea>
				</div>

				<div id="apg-deactivation-error-msg">
					<?php _e( "Please select at least one option.", 'apg' ) ?>
				</div>

			</div>
				
			<div class="apg-deactivate-form-footer">
				<a href="#" id="skip-and-deactivate"><?php _e( "Skip and Deactivate", 'apg' ) ?></a>
				<input type="hidden" name="apgDeactivate" value="deactivate">
				<input type="hidden" id="apg-deactivate-url" name="apgDeactivateUrl" value="">
				<input type="button" class="button popupCloseButton" value="<?php _e( "Cancel", 'apg' ) ?>">
				<input type="submit" class="button button-primary" value="<?php _e( "Submit and Deactivate", 'apg' ) ?>">
			</div>

		</form>

	</div>
</div>