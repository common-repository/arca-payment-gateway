<?php
// Регистрация виджета консоли
add_action( 'wp_dashboard_setup', 'add_apg_dashboard_widgets' );

// Используется в хуке
function add_apg_dashboard_widgets() {
   wp_add_dashboard_widget( 'apg_dashboard_widget', 'ArCa Payment Gateway: ' . __( "Finance report", 'apg' ), 'apg_dashboard_widget_function' );
}

// Выводит контент
function apg_dashboard_widget_function( $post, $callback_args ) {
   require_once ("apg-widget.php");
}
