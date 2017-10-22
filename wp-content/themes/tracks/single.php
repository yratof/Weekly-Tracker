<?php acf_form_head(); ?>
<?php get_header();

  /* Globals */
  $current_user    = wp_get_current_user();

  /* Times */
  $this_week       = date( 'W', current_time( 'timestamp', 1 ) );
  $this_year       = date( 'Y', current_time( 'timestamp', 1 ) );
  $start_week      = strtotime( "Last Monday" );
  $start_week_date = date( 'l jS F Y', $start_week );

  $end_week        = strtotime( "This Sunday" );
  $end_week_date   = date( 'l jS F Y', $end_week );

  var_dump( $this_week, $start_week_date, $end_week_date );
?>

<header class="header">
  <strong><?php echo date( 'l jS F Y', current_time( 'timestamp', 1 ) ); ?></strong>
  <span><?php echo $current_user->display_name; ?> — <a class="logout" href="<?php echo wp_logout_url(); ?>">Logout</a></span>
</header>

<main>

  <?php

    the_field( 'start' );
    the_field( 'finish' );
    the_field( 'total' );
    the_field( 'overtime' );

    if ( get_the_title() == $this_week . '_' . $this_year ) {
      acf_form([
        'fields'       => [
          'field_59ec895376e66',
          'field_59ec896a76e67',
          'field_59ec897676e68',
          'field_59ec898976e69'
        ],
        'submit_value' => 'Save week'
      ]);
    } else {
      acf_form([
        'post_id'      => 'new_post',
        'fields'       => [
          'field_59ec895376e66',
          'field_59ec896a76e67',
          'field_59ec897676e68',
          'field_59ec898976e69'
        ],
        'new_post' => [
          'post_type'   => 'post',
          'post_status' => 'publish',
          'post_title'  => $this_week . '_' . $this_year
        ],
        'submit_value' => 'Save new week'
      ]);
    }
    ?>
</main>

<!-- 39 Hours per weeks-->
<!-- 9 Hour days - 30 minutes lunch-->
<!-- £11.56 per hour for Overtime-->
<!-- £7.76 * 1.5 = Overtime cost-->
<!-- How overtime is paid.-->
<!-- Paid on last Friday of the Month. Call it $lastFriday-->
<!-- The paid amount is 4 weeks from $lastFriday - 1 week-->
<!-- Overtime is calculated from two weeks before the $lastFriday to two weeks afterwards. $lastFriday - 2 weeks -> 2 weeks before $lastFriday-->
<!-- Overtime Is offset by 2 weeks prior to $lastFriday-->
<!-- Possible no internet. Use Service worker to store all information and then oush when back on the internet-->
<?php get_footer(); ?>