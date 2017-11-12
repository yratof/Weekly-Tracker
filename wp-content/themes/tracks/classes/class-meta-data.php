<?php

class meta_data {

  // Load the meta
  static function setup() {

    add_action( 'after_setup_theme', __CLASS__ . '::capture', 100 );
    // Bring back our meta fields when ACF is installed
    add_filter('acf/settings/remove_wp_meta_box', '__return_false');
  }

  // Capture the fields
  static function capture() {
    // Not logged in? We DO NOTHING!
    if ( ! is_user_logged_in() ) {
      return;
    }
    // No post, no go.
    if ( empty( $_POST ) ) {
      return;
    }

    // Print the _post shit to see wtf. is happening.
    // print_r( '<pre>' );
    // print_r( $_POST );
    // print_r( '</pre>' );
    foreach ($_POST['date'] as $year => $year_group ) {
      foreach( $year_group as $month => $month_group ) {
        foreach ($month_group as $day => $data ) {
          self::capture_day( $year, $month, $day, $data );
        }
      }
    }
  }

  // Save each day as a post
  static function capture_day( $year, $month, $day, $data ) {
    // echo "<h1>$year-$month-$day</h1>";
    // var_dump( $data );
    // echo "<hr>";

    // Get the post via title
    $post = get_page_by_title( "$year-$month-$day", OBJECT, 'post' );
    if ( ! $post ) {
      // Create the post if it doesn't exist
      $post_id = wp_insert_post( [
        'post_title'  => "$year-$month-$day",
        'post_status' => 'publish',
      ] );
      $post = get_post( $post_id );
    }

    // Prefix fields with user_IDs
    $prefix = 'usr_' . self::user() . '_log';
    // Update the four fields
    update_post_meta( $post->ID, $prefix . '_start', $data['start'] );
    update_post_meta( $post->ID, $prefix . '_finish', $data['finish'] );
    update_post_meta( $post->ID, $prefix . '_total', $data['total'] );
    update_post_meta( $post->ID, $prefix . '_overtime', $data['overtime'] );

    // Convert to seconds for the math adding at a later date... Might waste time
    update_post_meta( $post->ID, $prefix . '_start_microtime', self::seconds( $data['start'] ) );
    update_post_meta( $post->ID, $prefix . '_finish_microtime', self::seconds( $data['finish'] ) );
    update_post_meta( $post->ID, $prefix . '_total_microtime', self::seconds( $data['total'] ) );
    update_post_meta( $post->ID, $prefix . '_overtime_microtime', self::seconds( $data['overtime'] ) );
  }

  // Convert a HH:i into microtime
  static function seconds( $time ) {
    if ( ! $time ) { return '0'; } // No seconds at all
    sscanf( $time, "%d:%d", $hours, $minutes );
    $seconds = $hours * 3600 + $minutes * 60;
    return $seconds;
  }

  // Return the Current user ID,
  // returns 0 if not logged in
  static function user() {
    return get_current_user_id();
  }

  // Get the field data from the day
  static function get_day_data( $date, $field, $seconds = false ) {
    if ( ! $date ) { return ''; }
    if ( ! $field ) { return ''; }
    $post = get_page_by_title( "$date", OBJECT, 'post' );
    if ( ! $post ) {
      return '';
    }
    $state_meta_name = 'usr_' . self::user() . '_log_' . $field;
    // If we need the seconds version, add a true flag to the end of the function
    if ( true === $seconds ) {
      $state_meta_name = 'usr_' . self::user() . '_log_' . $field . '_microtime';
    }
    return get_post_meta( $post->ID, $state_meta_name, true );
  }
}

meta_data::setup();
