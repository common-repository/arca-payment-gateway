<?php
add_action('wp_ajax_arca_pg_popup', 'arca_pg_iframe_popup');
add_action('wp_ajax_nopriv_arca_pg_popup', 'arca_pg_iframe_popup');
function arca_pg_iframe_popup(){
	require_once ("apg-price-list-shortcodes.php");
	die();
}
