<?php

class track_function {

  use source_finder;

  static function setup() {
    add_action( 'wp_enqueue_scripts', __CLASS__ . '::scripts', 10 );

    // Load meta data saving class
    require_once( 'classes/class-meta-data.php' );

    // Do the math for from-to dates
    require_once( 'classes/class-calculations.php' );
  }

  static function scripts() {
    wp_register_style( 'styles', self::scry( '/style.css' ), '', '', false );
    wp_enqueue_style( 'styles' );

    // Register scripts
    wp_register_script( 'prefix', self::scry( '/assets/prefix.js' ), [ 'jquery' ], '', true );
    wp_register_script( 'tracking_script', self::scry( '/assets/script.js' ), [ 'jquery' ], '', true );
    wp_register_script( 'time', self::scry( '/assets/time.js' ), [ 'jquery' ], '', true );

    // Use scripts
    wp_enqueue_script( 'jquery' );
    wp_enqueue_script( 'prefix' );
    wp_enqueue_script( 'tracking_script' );
    wp_enqueue_script( 'time' );
  }
}

trait source_finder {
  /**
   * Scry - determine the css & js uri based on a relative path
   * Also implement cache busting basted on file make time
   */
  static function scry( $path ) {
    $uri = get_template_directory_uri();
    $dir = get_template_directory();

    if ( file_exists( $dir . $path ) ) {
      // If the file exist, lets get the last time it was modified
      $file_uri = $uri . $path .'?'. filemtime( $dir . $path );
    } else {
      // Just give us the file, no query
      $file_uri = $uri . $path;
    }
    return apply_filters( 'source_finder', $file_uri );
  }
}

track_function::setup();
