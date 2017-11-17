<?php


/**
 * 39 Hours per weeks
 * 9 Hour days - 30 minutes lunch
 * £11.56 per hour for Overtime
 * £7.76 * 1.5 = Overtime cost
 * How overtime is paid.
 * Paid on last Friday of the Month. Call it $lastFriday
 * The paid amount is 4 weeks from $lastFriday - 1 week before $lastFriday
 * Overtime is calculated from two weeks before the $lastFriday to two weeks before the $lastFriday
 * Possible no internet. Use Service worker to store all information and then oush when back on the internet
 *
 */

require get_stylesheet_directory() . '/vendor/autoload.php';

use Carbon\Carbon;

// New instance of 'NOW'!
new Carbon( 'Europe/Oslo' );

// Parse the URL to get the page we're on
$the_url = "$_SERVER[REQUEST_URI]";
$parts   = parse_url( $the_url );
if ( isset( $parts['query'] ) ) {
  parse_str( $parts['query'], $query );
  // A quick reminder of what the day is:
  echo '<script>console.log( "Viewing the date: ' . $query['date'] . '" );</script>';
} else {
  // die ( 'We need a date in the url' );
  $query['date'] = Carbon::today()->startOfWeek()->toFormattedDateString();
}

// Get the query string date
$current_week = $query['date'];

// Double check there's a date before going
// further, so we don't show any errors to users
$date_format = '^\d{4}-\d{2}-\d{2}^';
$url_check   = preg_match( $date_format, $query['date'] );
if ( 0 == $url_check ) {
  // When the date is wrong, or doesn't make sense. Log it so we refer back to that date
  // then set the date to this current week so stop anything going wrong.
  error_log( 'URL happened to contain a wrong date. Autocorrected it to current date. ', 0 );
  $current_week = Carbon::today()->startOfWeek()->toFormattedDateString();
}


// Weekly dates & labels for pagination
$lastWeek                = Carbon::parse( $current_week )->subDays( 7 )->format('Y-m-d');
$lastWeek_label          = Carbon::parse( $current_week )->subDays( 7 )->format('jS M');
$nextWeek                = Carbon::parse( $current_week )->addDays( 7 )->format('Y-m-d');
$nextWeek_label          = Carbon::parse( $current_week )->addDays( 7 )->format('jS M');

// Debug information & general information
$today                   = Carbon::parse( $current_week )->toFormattedDateString();
$start                   = Carbon::parse( $current_week )->startOfWeek()->toFormattedDateString();
$end                     = Carbon::parse( $current_week )->endOfWeek()->toFormattedDateString();
$lastFriday              = Carbon::parse( $current_week )->lastOfMonth( Carbon::FRIDAY )->toFormattedDateString();

// Cutoff times
$cutoffPay               = Carbon::parse( $current_week )->lastOfMonth( Carbon::FRIDAY )->subDays(6);
$nextCutoffPay           = Carbon::parse( $current_week )->addMonth()->lastOfMonth( Carbon::FRIDAY )->subDays(5);
// Labels for Fridays
$cutoffPay_friday        = Carbon::parse( $current_week )->lastOfMonth( Carbon::FRIDAY )->subDays(6)->toFormattedDateString();
$nextCutoffPay_friday    = Carbon::parse( $current_week )->addMonth()->lastOfMonth( Carbon::FRIDAY )->subDays(7)->toFormattedDateString();

$cutoffPay_link          = $cutoffPay->format( 'Y-m-d' );
$cutoffPay               = $cutoffPay->toFormattedDateString();

$nextCutoffPay_link      = $nextCutoffPay->format( 'Y-m-d' );
$nextCutoffPay           = $nextCutoffPay->toFormattedDateString();

// Working out totals
$totalHoursThisWeek      = calculations::total_this_week( Carbon::parse( $current_week )->startOfWeek(), Carbon::parse( $current_week )->endOfWeek(), 'total' );
$totalOvertimeThisWeek   = calculations::total_this_week( Carbon::parse( $current_week )->startOfWeek(), Carbon::parse( $current_week )->endOfWeek(), 'overtime' );

$totalHoursThisPeriod    = calculations::total_this_week( Carbon::parse( $current_week )->lastOfMonth( Carbon::FRIDAY )->subDays(6), Carbon::parse( $current_week )->addMonth()->lastOfMonth( Carbon::FRIDAY )->subDays(5), 'total' );
$totalOvertimeThisPeriod = calculations::total_this_week( Carbon::parse( $current_week )->lastOfMonth( Carbon::FRIDAY )->subDays(6), Carbon::parse( $current_week )->addMonth()->lastOfMonth( Carbon::FRIDAY )->subDays(5), 'overtime' );

// If we've gone past the month, but not the date of the cut off, lets just take it back a month
if ( Carbon::parse( $current_week )->subMonth()->lastOfMonth( Carbon::FRIDAY )->subDays(6)->format( 'Y-m-d' ) < $current_week && Carbon::parse( $current_week )->lastOfMonth( Carbon::FRIDAY )->subDays(5)->format( 'Y-m-d' ) > $current_week ) {
  $cutoffPay               = Carbon::parse( $current_week )->subMonth()->lastOfMonth( Carbon::FRIDAY )->subDays(6);
  $nextCutoffPay           = Carbon::parse( $current_week )->lastOfMonth( Carbon::FRIDAY )->subDays(5);
  // Labels for Fridays
  $cutoffPay_friday        = Carbon::parse( $current_week )->subMonth()->lastOfMonth( Carbon::FRIDAY )->subDays(6)->toFormattedDateString();
  $nextCutoffPay_friday    = Carbon::parse( $current_week )->lastOfMonth( Carbon::FRIDAY )->subDays(7)->toFormattedDateString();

  $cutoffPay_link       = $cutoffPay->format( 'Y-m-d' );
  $cutoffPay            = $cutoffPay->toFormattedDateString();
  $nextCutoffPay_link   = $nextCutoffPay->format( 'Y-m-d' );
  $nextCutoffPay        = $nextCutoffPay->toFormattedDateString();

  $totalHoursThisPeriod    = calculations::total_this_week( Carbon::parse( $current_week )->subMonth()->lastOfMonth( Carbon::FRIDAY )->subDays(6), Carbon::parse( $current_week )->lastOfMonth( Carbon::FRIDAY )->subDays(5), 'total' );
  $totalOvertimeThisPeriod = calculations::total_this_week( Carbon::parse( $current_week )->subMonth()->lastOfMonth( Carbon::FRIDAY )->subDays(6), Carbon::parse( $current_week )->lastOfMonth( Carbon::FRIDAY )->subDays(5), 'overtime' );
}

$cutoffOvertime            = Carbon::parse( $current_week )->lastOfMonth( Carbon::FRIDAY )->subDays(12)->toFormattedDateString();
$nextCutoffOvertime        = Carbon::parse( $current_week )->addMonth()->lastOfMonth( Carbon::FRIDAY )->subDays(11)->toFormattedDateString();

$cutoffOvertime_friday     = Carbon::parse( $current_week )->lastOfMonth( Carbon::FRIDAY )->subDays(14)->toFormattedDateString();
$nextCutoffOvertime_friday = Carbon::parse( $current_week )->addMonth()->lastOfMonth( Carbon::FRIDAY )->subDays(14)->toFormattedDateString();


$cutoffOvertime_diff     = Carbon::parse( $current_week )->lastOfMonth( Carbon::FRIDAY )->subDays(14)->diffForHumans();
$nextCutoffOvertime_diff = Carbon::parse( $current_week )->addMonth()->lastOfMonth( Carbon::FRIDAY )->subDays(14)->diffForHumans();

/* Weekdays */
$weekdays                = [ 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday' ];

/* States ( Start, Finish etc ) */
$states                  = [ 'start', 'finish', 'total', 'overtime' ];



?>

<form method="POST" class="track">
    <!-- Navigation-->
    <header class="meta">
      <a href="<?= add_query_arg( 'date', $lastWeek, '/tracking' ); ?>" class="prev"><small><?= __( 'Last week', 'tracks' ); ?></small><?= $lastWeek_label ?></a>
      <div class="week">
        <span>
          <strong>Start:</strong> <?= Carbon::parse( $current_week )->startOfWeek()->format('jS F Y'); ?><br />
          <strong>End: </strong><?= Carbon::parse( $current_week )->endOfWeek()->format('jS F Y') ?>
        </span>
        <small>
          <?php

          // Might not be the best way of doing this...
          echo sprintf( '<strong>%1$s</strong>: %2$s <br> <strong>%3$s:</strong> %4$s – %5$s <br> <strong>%6$s:</strong> %7$s – %8$s',
            __( 'Pay day', 'tracks' ),
            $lastFriday,
            __( 'Pay period', 'tracks' ),
            $cutoffPay_friday,
            $nextCutoffPay_friday,
            __( 'Overtime period', 'tracks' ),
            $cutoffOvertime_friday,
            $nextCutoffOvertime_friday
          );

          ?>
        </small>
      </div>
      <a href="<?= add_query_arg( 'date', $nextWeek, '/tracking' ); ?>" class="next"><small><?= __( 'Next week', 'tracks' ); ?></small><?= $nextWeek_label ?></a>
    </header>

    <table>
      <thead>
        <tr>
          <!-- Top left blank cell -->
          <th class="day"></th>
          <?php
            $d = 0;
            // Loop through the days of the week to get th cells
            foreach ( $weekdays as $weekday ) { ?>
              <th data-day="<?= $weekday ?>" data-date="<?= Carbon::parse( $current_week )->startOfWeek()->addDays( $d )->toFormattedDateString(); ?>" class="day"><?= $weekday ?>
                <small><?= Carbon::parse( $current_week )->startOfWeek()->addDays( $d )->toFormattedDateString(); ?></small>
              </th>
            <?php
            $d++;
            } ?>
        </tr>
      </thead>
      <tfoot>

        <tr class="total-this-week">
          <td colspan="7">
              <span><strong><?= __( 'Week total', 'tracks' ); ?>:</strong> <?= $totalHoursThisWeek; ?></span>
              <span><strong><?= __( 'Week overtime', 'tracks' ); ?>:</strong> <?= $totalOvertimeThisWeek; ?></span>
          </td>
        </tr>

        <tr class="total-this-period">
          <td colspan="7">
              <span><strong><?= __( 'Month total', 'tracks' ); ?>:</strong> <?= $totalHoursThisPeriod; ?></span>
              <span><strong><?= __( 'Overtime total', 'tracks' ); ?>:</strong> <?= $totalOvertimeThisPeriod; ?></span>
          </td>
        </tr>

        <tr>
          <!-- Buttons that help do things-->
          <th colspan="4">
            <a class="view-all" href="/view?from=<?= $cutoffPay_link; ?>&to=<?= $nextCutoffPay_link; ?>">View month</a>
            <a class="view-all" href="/print?from=<?= $cutoffPay_link; ?>&to=<?= $nextCutoffPay_link; ?>">Print month</a>
          </th>
          <th class="summary" colspan="4">
            <input class="view-all" type="submit" value="Save"/>
          </th>
        </tr>

      </tfoot>
      <tbody>
        <?php
        // Loop through all the states to get inputs
        foreach ( $states as $state ) { ?>
          <tr class="<?= $state; ?>">
            <th><?= $state; ?></th>
            <?php
            // Loop through the days of the week for cells
            foreach ( $weekdays as $key => $weekday ) {
              ?>
              <td data-count="day-<?= $key + 1; ?>" data-day="<?= strtolower( $weekday ); ?>">
                <input type="text"
                  value="<?= meta_data::get_day_data( Carbon::parse( $current_week )->startOfWeek()->addDays( $key )->format('Y-m-d'), $state ) ?>"
                  tabindex="<?= $key + 1 ?>"
                  placeholder="00:00"
                  name="date<?= Carbon::parse( $current_week )->startOfWeek()->addDays( $key )->format('[Y][m][d]') . '[' . $state . ']'; ?>" />
              </td>
            <?php
            } ?>
          </tr>
        <?php
        }
        ?>

      </tbody>
    </table>
  </form>

<script>
  /*
  DO A LITTLE DANCE!
  This will highlight specific dates to help show wtf is going on
  */
  jQuery( function( $ ){
    var today       = '<?= Carbon::today()->toFormattedDateString(); ?>';
    var lastfriday  = '<?= $lastFriday ?>';
    var pay_cutoff  = '<?= $nextCutoffPay_friday; ?>';
    var over_cutoff = '<?= $cutoffOvertime_friday; ?>';
    // console.log( 'Today is ' + today );

    // Remove the tabinxed and make these fields read-only
    $( 'tr.total input' ).attr( 'tabindex', '' );
    $( 'tr.total input' ).attr( 'readonly', 'readonly' );

    $( 'tr.overtime input' ).attr( 'tabindex', '' );
    $( 'tr.overtime input' ).attr( 'readonly', 'readonly' );

    // Loop the days
    $( 'th.day' ).each( function() {

      // If today is the date, we highlight
      if ( today == $(this).data( 'date' ) ) {
        $( this ).addClass( 'today' );
        $( this ).attr( 'data-notice', 'Today' );
      }

      // If PAY CUTTOFF is shown
      if ( pay_cutoff == $(this).data( 'date' ) ) {
        $( this ).addClass( 'payday' );
        $( this ).attr( 'data-notice', 'Pay cut-off' );
      }

      // If OVERTIME CUTTOFF is shown
      if ( over_cutoff == $(this).data( 'date' ) ) {
        $( this ).addClass( 'overtime' );
        $( this ).attr( 'data-notice', 'Overtime cut-off' );
      }

      // Last friday, regardless
      if ( lastfriday == $(this).data( 'date' ) ) {
        $( this ).addClass( 'lastfriday' );
        $( this ).attr( 'data-notice', 'Payday' );
      }

      if ( today == pay_cutoff || today == over_cutoff || today == lastfriday ) {
        console.log( 'Today ' + today + ' - Pay ' + pay_cutoff + ' - Over ' + over_cutoff );
        $( '[data-date="' + today + '"]' ).attr( 'style', '--clash: "Today"' );
      }

    });
  } );

</script>

<div id="debug">
<?php

// What is 'Now'
echo '<strong>Now: </strong>' . $today . ' | ';
echo '<strong>Start:</strong> ' . $start . ' | ';
echo '<strong>End: </strong>' . $end;

echo '<hr />';

echo '<strong>Last friday: </strong>' . $lastFriday;

echo '<hr />';

echo '<strong>Cuttoff pay: </strong>' . $cutoffPay . ' | ';
echo '<strong>Next Cuttoff pay: </strong>' . $nextCutoffPay;

echo '<hr />';

echo '<strong>Cuttoff overtime: </strong>' . $cutoffOvertime . ' | ';
echo '<strong>Next Cuttoff overtime: </strong>' . $nextCutoffOvertime;

echo '<hr />';

echo '<strong>Your overtime ended: </strong>' . $cutoffOvertime_diff . ' | ';
echo '<strong>And you should work your balls off until: </strong>' . $nextCutoffOvertime_diff;

echo '<hr />';

/* date[2017][10][23][start] */
?>
</div>
