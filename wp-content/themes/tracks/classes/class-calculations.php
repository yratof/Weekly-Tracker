<?php

// Working out the totals between two dates
class calculations {

  // Get the totals for this week...
  static function total_this_week(  $start,  $end, $field ) {
    if ( ( ! $start ) || ( ! $end ) ) { return ; } // Nothing

    // $weekly_hours = [];
    $total_hours  = 0;

    for ( $date = $start; $date->lte($end); $date->addDay() ) {
      // $weekly_hours[] = meta_data::get_day_data( $date->format('Y-m-d'), $field ); // Make array of hours
      $total_hours    += meta_data::get_day_data( $date->format('Y-m-d'), $field, true ); // Add seconds together
    }

    // Put the seconds into hours and minutes
    $H           = floor( $total_hours / 3600 ); // Hours
    $i           = ( $total_hours / 60 ) % 60; // Minutes
    $total_hours = sprintf( "%02d hours, %02d minutes", $H, $i ); // Formatiing

    return $total_hours;
  }

}
