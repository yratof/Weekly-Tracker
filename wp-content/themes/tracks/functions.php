<?php

class track_function {

  static function setup() {

    // Build pages that aren't there
    require_once( 'classes/class-setup.php' );

    // Load the content
    require_once( 'classes/class-tracking.php' );
    require_once( 'classes/class-print.php' );
    require_once( 'classes/class-view.php' );

    // Load meta data saving class
    require_once( 'classes/class-meta-data.php' );

    // Do the math for from-to dates
    require_once( 'classes/class-calculations.php' );

  }

}

track_function::setup();
