<?php

class setup {

  use source_finder;

  static function everything() {

    // Build content pages
    add_action( 'init', __CLASS__ . '::build_pages', 10 );

    // Load scripts
    add_action( 'wp_enqueue_scripts', __CLASS__ . '::scripts', 10 );

    // Not logged in? You better login before continuing
    add_action( 'template_redirect', __CLASS__ . '::force_login_form' );
  }

  // Create pages with a title
  static function create_page( $page, $title ) {
    if ( get_page_by_title( $page ) == NULL ) {
        $createPage = [
          'post_title'   => $title,
          'post_content' => "[$page]", // Shortcode dedicated to that page
          'post_status'  => 'publish',
          'post_author'  => 1,
          'post_type'    => 'page',
          'post_name'    => $page
        ];
        wp_insert_post( $createPage );
    }
  }

  // Build the pages if they don't exist
  static function build_pages() {
    self::create_page( 'tracking', 'Tracking' );
    self::create_page( 'view', 'View' );
    self::create_page( 'print', 'Print' );
  }

  static function scripts() {
    wp_register_style( 'styles', self::scry( '/style.css' ), '', '', false );
    wp_enqueue_style( 'styles' );

    // Register scripts
    wp_register_script( 'prefix', self::scry( '/assets/prefix.js' ), [ 'jquery' ], '', true );
    wp_register_script( 'tracking_script', self::scry( '/assets/script.js' ), [ 'jquery' ], '', true );
    wp_register_script( 'time', self::scry( '/assets/time.js' ), [ 'jquery' ], '', true );
    wp_register_script( 'range', self::scry( '/assets/range.js' ), [ 'jquery' ], '', true );

    // Use scripts
    wp_enqueue_script( 'jquery' );
    wp_enqueue_script( 'prefix' );
    wp_enqueue_script( 'tracking_script' );
    wp_enqueue_script( 'time' );
    wp_enqueue_script( 'range' );
  }

  // Force login page
  static function force_login_form() {
    if ( ! is_user_logged_in() && ! is_admin() ) {
      auth_redirect();
    }
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

setup::everything();