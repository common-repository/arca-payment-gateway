<?php
$strMgs = "";

switch ($state) {
	case "error":
		$strMgs = __( "An error has occurred", 'apg' );
	break;
	default:
		$strMgs = __( "Unfortunately your payment has failed", 'apg' );
}
?>

<div id="arca-pg-message" class="arca-pg">

	<h1><?php _e( "Payment has failed", 'apg' ) ?></h1>

	<p><?php echo $strMgs; ?></p>

</div>