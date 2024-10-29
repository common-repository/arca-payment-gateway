<?php
add_filter('display_post_states', 'arca_pg_ecs_add_post_state', 10, 2);
function arca_pg_ecs_add_post_state($post_states, $post) {

   global $arca_config;

   if ( urldecode($post->post_name) == urldecode($arca_config->checkoutFormPage) ){
      $post_states[] = 'ArCa Payment Gateway Checkout page';
   }

   return $post_states;
}

add_filter('display_post_states', 'arca_pg_ecs_add_post_state2', 10, 2);
function arca_pg_ecs_add_post_state2($post_states, $post) {

   global $arca_config;

   if ( urldecode($post->post_name) == urldecode($arca_config->privacyPolicyPage) ){
      $post_states[] = 'ArCa Payment Gateway Privacy Policy page';
   }

   return $post_states;
}

