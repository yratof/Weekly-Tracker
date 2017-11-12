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

require 'vendor/autoload.php';

use Carbon\Carbon;

// New instance of 'NOW'!
new Carbon( 'Europe/Oslo' );

// Parse the URL to get the page we're on
$the_url = "$_SERVER[REQUEST_URI]";
$parts   = parse_url( $the_url );
if ( isset( $parts['query'] ) ) {
  parse_str( $parts['query'], $query );
  // A quick reminder of what the day is:
  echo '<script>console.log( "' . $query['date'] . '" );</script>';
} else {
  // die ( 'We need a date in the url' );
  $query['date'] = Carbon::today()->startOfWeek()->toFormattedDateString();
}

// Weekly dates & labels for pagination
$lastWeek       = Carbon::parse( $query['date'] )->subDays( 7 )->format('Y-m-d');
$lastWeek_label = Carbon::parse( $query['date'] )->subDays( 7 )->format('jS M');
$nextWeek       = Carbon::parse( $query['date'] )->addDays( 7 )->format('Y-m-d');
$nextWeek_label = Carbon::parse( $query['date'] )->addDays( 7 )->format('jS M');

// Debug information & general information
$today                   = Carbon::parse( $query['date'] )->toFormattedDateString();
$start                   = Carbon::parse( $query['date'] )->startOfWeek()->toFormattedDateString();
$end                     = Carbon::parse( $query['date'] )->endOfWeek()->toFormattedDateString();
$lastFriday              = Carbon::parse( $query['date'] )->lastOfMonth( Carbon::FRIDAY )->toFormattedDateString();
$cutoffPay               = Carbon::parse( $query['date'] )->lastOfMonth( Carbon::FRIDAY )->subDays(7)->toFormattedDateString();
$nextCutoffPay           = Carbon::parse( $query['date'] )->addMonth()->lastOfMonth( Carbon::FRIDAY )->subDays(7)->toFormattedDateString();
$cutoffOvertime          = Carbon::parse( $query['date'] )->lastOfMonth( Carbon::FRIDAY )->subDays(14)->toFormattedDateString();
$nextCutoffOvertime      = Carbon::parse( $query['date'] )->addMonth()->lastOfMonth( Carbon::FRIDAY )->subDays(14)->toFormattedDateString();
$cutoffOvertime_diff     = Carbon::parse( $query['date'] )->lastOfMonth( Carbon::FRIDAY )->subDays(14)->diffForHumans();
$nextCutoffOvertime_diff = Carbon::parse( $query['date'] )->addMonth()->lastOfMonth( Carbon::FRIDAY )->subDays(14)->diffForHumans();

/* Weekdays */
$weekdays = [ 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday' ];

/* States ( Start, Finish etc ) */
$states = [ 'start', 'finish', 'total', 'overtime' ];
?>

<form method="POST" class="track">

    <!-- Navigation-->
    <header class="meta">
      <a href="/?date=<?= $lastWeek; ?>" class="prev"><small>Last week</small><?= $lastWeek_label ?></a>
      <strong class="week">
        <?= Carbon::parse( $query['date'] )->startOfWeek()->format('jS F Y') . '—' . Carbon::parse( $query['date'] )->endOfWeek()->format('jS F Y') ?>
        <small>
          <?php
          echo 'Pay day: ' . $lastFriday . ' – ';
          echo 'Pay range: ' . $cutoffPay . ' - ' . $nextCutoffPay . '<br>';
          echo 'Overtime range: ' . $cutoffOvertime . ' – ' . $nextCutoffOvertime . '</em>';
          ?>
        </small>
      </strong>
      <a href="/?date=<?= $nextWeek ?>" class="next"><small>Next week</small><?= $nextWeek_label ?></a>
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
              <th data-day="<?= $weekday ?>" data-date="<?= Carbon::parse( $query['date'] )->startOfWeek()->addDays( $d )->toFormattedDateString(); ?>" class="day"><?= $weekday ?>
                <small><?= Carbon::parse( $query['date'] )->startOfWeek()->addDays( $d )->toFormattedDateString(); ?></small>
              </th>
            <?php
            $d++;
            } ?>
        </tr>
      </thead>
      <tfoot>
        <tr>
          <!-- Buttons that help do things-->
          <th colspan="4"><a class="view-all" href="#view">View month</a><a class="view-all" href="#print">Print month</a></th>
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
            foreach ( $weekdays as $key => $weekday ) { ?>
              <td>
                <input value="<?= meta_data::get_day_data( Carbon::parse( $query['date'] )->startOfWeek()->addDays( $key )->format('Y-m-d'), $state ) ?>" tabindex="<?= $key + 1 ?>" placeholder="00:00" name="date<?= Carbon::parse( $query['date'] )->startOfWeek()->addDays( $key )->format('[Y][m][d]') . '[' . $state . ']'; ?>" />
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
    var today       = '<?php echo Carbon::today()->toFormattedDateString(); ?>';
    var lastfriday  = '<?php echo $lastFriday ?>';
    var pay_cutoff  = '<?php echo $cutoffPay; ?>';
    var over_cutoff = '<?php echo $cutoffOvertime ?>';
    // console.log( 'Today is ' + today );

    // Remove the tabinxed and make these fields read-only
    $( 'tr.total input' ).attr( 'tabindex', '', 'readonly', 'readonly' )
    $( 'tr.overtime input' ).attr( 'tabindex', '', 'readonly', 'readonly' )

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
        $( this ).attr( 'data-notice', 'Payday' );
      }

      // If OVERTIME CUTTOFF is shown
      if ( over_cutoff == $(this).data( 'date' ) ) {
        $( this ).addClass( 'overtime' );
        $( this ).attr( 'data-notice', 'Overtime Paid' );
      }

      // Last friday, regardless
      if ( lastfriday == $(this).data( 'date' ) ) {
        $( this ).addClass( 'lastfriday' );
        $( this ).attr( 'data-notice', 'Last Friday' );
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