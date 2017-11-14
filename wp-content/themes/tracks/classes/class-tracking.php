<?php

class tracking {

  static function setup() {
    add_shortcode( 'tracking', __CLASS__ . '::tracking_content' );

  }

  static function tracking_content( $atts ){
    // Require the whole form, logic and math
    require_once( 'class-carbon-dating.php' );
    do_action( 'body' );
  }

}

tracking::setup();