<?php

// Require Carbon
require 'vendor/autoload.php';
use Carbon\Carbon;

class view {

  static function setup() {
    add_shortcode( 'view', __CLASS__ . '::view_content' );
    // add_action( 'wp_footer', __CLASS__ . '::scripts' );
  }

  static function view_content( $atts ) {
    $range = self::range_date();
    // var_dump( $range );
    if ( empty( $range ) ) {
      return;
    }
    ?>
    <form method="POST">
      <input id="range" value="">
      <div id="range-container" style="width: 500px"></div>

      <?php /* Build the table. */
        // var_dump( self::between_dates( $range['0'], $range['1'] ) );
        foreach( self::between_dates( $range['0'], $range['1'] ) as $table_element) {
          echo $table_element;
        } ?>
      <input type="submit" value="<?= __( 'View dates', 'tracks' );?>"/>
    </form>
    <?php
  }

  static function scripts() {
    wp_add_inline_script( 'range', "jQuery('#range').dateRangePicker({
      inline:     true,
      container: '#range-container',
      alwaysOpen: true,
      separator: '|',
      monthSelect: true
    });", 'after' );
  }

  static function range_date() {
    new Carbon( 'Europe/Oslo' );

    // Parse the URL to get the page we're on
    $the_url = $_SERVER[ 'REQUEST_URI' ];
    $parts   = parse_url( $the_url );
    if ( isset( $parts['query'] ) ) {
      parse_str( $parts['query'], $query );

      if ( isset( $query['from'] ) ) {
        $from = $query['from'];
      } else {
        $from = Carbon::today()->lastOfMonth( Carbon::FRIDAY )->subDays(7)->format( 'Y-m-d' );
      }
      if ( isset( $query['to'] ) ) {
        $to   = $query['to'];
      } else {
        $to   = Carbon::today()->addMonth()->lastOfMonth( Carbon::FRIDAY )->subDays(7)->format( 'Y-m-d' );
      }

    } else {
      $from = Carbon::today()->lastOfMonth( Carbon::FRIDAY )->subDays(7)->format( 'Y-m-d' );
      $to   = Carbon::today()->addMonth()->lastOfMonth( Carbon::FRIDAY )->subDays(7)->format( 'Y-m-d' );
    }


    // Double check there's a date before going
    // further, so we don't show any errors to users
    $date_format = '^\d{4}-\d{2}-\d{2}^';
    $from_check  = preg_match( $date_format, $from );
    $to_check    = preg_match( $date_format, $to );

    // If anything fails...
    if ( 0 == $from_check || 0 == $to_check ) {
      // When the date is wrong, or doesn't make sense. Log it so we refer back to that date
      $from = Carbon::today()->lastOfMonth( Carbon::FRIDAY )->subDays(7);
      $to   = Carbon::today()->addMonth()->lastOfMonth( Carbon::FRIDAY )->subDays(7);
    }

    return [ $from, $to ];
  }

  static function between_dates( $from, $to ) {
    $from = Carbon::parse( $from )->lastOfMonth( Carbon::FRIDAY )->subDays(7);
    $to   = Carbon::parse( $to )->addMonth()->lastOfMonth( Carbon::FRIDAY )->subDays(7);
    return self::timeline( $from , $to );
  }

  // Maddness. Creating HTML via an array...
  static function timeline( $from, $to ) {
    $line   = [];
    $line[] = '<table>
                <tbody>
                  <tr>
                    <th>' . __( 'Date', 'tracks' ) . '</th>
                    <th>' . __( 'Start', 'tracks' ) . '</th>
                    <th>' . __( 'Finish', 'tracks' ) . '</th>
                    <th>' . __( 'Total', 'tracks' ) . '</th>
                    <th>' . __( 'Overtime', 'tracks' ) . '</th>
                    <th>' . __( 'Overtime Earnt', 'tracks' ) . '</th>
                  </tr>
                </tbody>';
    for ( $date = $from; $date->lte( $to ); $date->addDay() ) {
      ob_start();
        ?>
          <tr>
            <td><?= Carbon::parse( $date )->toFormattedDateString(); ?></td>
            <td><?= meta_data::get_day_data( $date->format('Y-m-d'), 'start', false ); ?></td>
            <td><?= meta_data::get_day_data( $date->format('Y-m-d'), 'finish', false ); ?></td>
            <td><?= meta_data::get_day_data( $date->format('Y-m-d'), 'total', false ); ?></td>
            <td> <?= meta_data::get_day_data( $date->format('Y-m-d'), 'overtime', false ); ?></td>
            <td>&pound; <?= meta_data::get_day_data( $date->format('Y-m-d'), 'overtime', false ) * 11.56; ?></td>
          </tr>
      <?php
    $line[] = ob_get_clean();
    }
    return $line;
  }



}

view::setup();