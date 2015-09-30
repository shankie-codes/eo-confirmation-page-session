<?php

function _eventorganiser_booking_confirmation_shortcode_handler($atts){

  $a = shortcode_atts( array(
        'unset' => true
    ), $atts );

  ob_start();

  if ( $overridden_template = locate_template( 'eo-booking-confirmation.php' ) ) {
     // locate_template() returns path to file
     // if either the child theme or the parent theme have overridden the template
     include( $overridden_template );
   } else {
     // If neither the child nor parent theme have overridden the template,
     // we load the template from the 'templates' sub-directory of the directory this file is in
     include( dirname( __FILE__ ) . '/../templates/eo-booking-confirmation.php' );
   }

  $html = ob_get_contents();
  ob_end_clean();
  
  return $html;
}

add_shortcode( 'booking-confirmation' , '_eventorganiser_booking_confirmation_shortcode_handler' );