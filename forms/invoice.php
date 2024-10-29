<?php
global $wpdb, $arca_config;

// get orderId
$orderId = (isset($_REQUEST['orderId'])) ? sanitize_text_field($_REQUEST['orderId']) : null;

if(!isset($orderId)) return false;

// get order
$order = $wpdb->get_row( $wpdb->prepare("select * from ".$wpdb->prefix."arca_pg_orders where orderId = '%s'", $orderId) );

// get order details from json
$orderDetails = json_decode($order->orderDetails);
$currency = $wpdb->get_row( $wpdb->prepare("SELECT abbr from ".$wpdb->prefix."arca_pg_currency where code = '%s'", $order->currency) );

// get product details
$productDetails = $wpdb->get_row( $wpdb->prepare("select * from ".$wpdb->prefix."arca_pg_pricelist where productId = %d", $order->productId) );
?>

<div id="arca-pg-order-invoice" class="arca-pg">
	<h1><?php _e( "Your invoice", 'apg' ) ?></h1>
	<p>
		<?php _e( "Invoice number:", 'apg' ) ?> <?php echo esc_html($order->orderNumber); ?><br>
		<?php _e( "Date of payment:", 'apg' ) ?> <?php echo esc_html($order->orderDate); ?><br>
		<?php _e( "Sum", 'apg' ) ?> <?php echo esc_html($order->amount); ?> <?php echo esc_html($currency->abbr); ?><br>
	</p>
	<p>
		<?php _e( "Thanks for your payment, our specialist will contact you.", 'apg' ) ?>
	</p>
</div>

<?php
if(isset($orderDetails->email) && !$order->mailSent){

	$from 		=	"From: ". get_bloginfo('name') ." <" . (($arca_config->mailFrom != "") ? $arca_config->mailFrom : get_bloginfo('admin_email')) . ">";
	$headers	=	array(
					$from,
					"content-type: text/html",
					);
	$to			=	$orderDetails->email;
	
	$subject	=	__( "Invoice", 'apg' );

	$message	=	"";
	$message	.=	"<h1>" . __( "Your invoice", 'apg' ) . "</h1>";
	$message	.=	__( "Invoice number:", 'apg' ) ." " . $order->orderNumber . "<br>";
	$message	.=	__( "Date of payment:", 'apg' ) ." " . $order->orderDate . "<br>";
	$message	.=	__( "Sum", 'apg' ) ." " . $order->amount . " " . $currency->abbr . "<br>";
	$message	.=	"<br>";
	$message	.=	__( "Thanks for your payment, our specialist will contact you.", 'apg' ) . "<br>";
	$message	.=	"<br>";
	$message	.=	"<a href='". get_bloginfo('url') ."'>" . get_bloginfo('name') . "</a>";

	// send mail
	wp_mail( $to, $subject, $message, $headers );

	// set mailSent true
	$wpdb->query( $wpdb->prepare("update ".$wpdb->prefix."arca_pg_orders set mailSent = true where orderId = '%s'", $orderId) );

}
