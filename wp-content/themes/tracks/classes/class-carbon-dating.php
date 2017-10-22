<?php
require 'vendor/autoload.php';
use Carbon\Carbon;

// New instance of 'NOW'!
new Carbon( 'Europe/Oslo' );

// Today
$today                   = Carbon::today()->toFormattedDateString();

// Start of the week
$start                   = Carbon::today()->startOfWeek()->toFormattedDateString();

// End of the week
$end                     = Carbon::today()->endOfWeek()->toFormattedDateString();

// Pay day this month
$lastFriday              = Carbon::today()->lastOfMonth( Carbon::FRIDAY )->toFormattedDateString();

// Cutoff for pay this month
$cutoffPay               = Carbon::today()->lastOfMonth( Carbon::FRIDAY )->subDays(7)->toFormattedDateString();
$nextCutoffPay           = Carbon::today()->addMonth()->lastOfMonth( Carbon::FRIDAY )->subDays(7)->toFormattedDateString();

// Cutoff for overtime this month
$cutoffOvertime          = Carbon::today()->lastOfMonth( Carbon::FRIDAY )->subDays(14)->toFormattedDateString();
$nextCutoffOvertime      = Carbon::today()->addMonth()->lastOfMonth( Carbon::FRIDAY )->subDays(14)->toFormattedDateString();

$cutoffOvertime_diff     = Carbon::today()->lastOfMonth( Carbon::FRIDAY )->subDays(14)->diffForHumans();
$nextCutoffOvertime_diff = Carbon::today()->addMonth()->lastOfMonth( Carbon::FRIDAY )->subDays(14)->diffForHumans();


// What is 'Now'
echo '<strong>Now: </strong>' . $today . '<br />';
echo '<strong>Start:</strong> ' . $start . '<br />';
echo '<strong>End: </strong>' . $end . '<br />';

echo '<hr />';

echo '<strong>Last friday: </strong>' . $lastFriday . '<br />';

echo '<hr />';

echo '<strong>Cuttoff pay: </strong>' . $cutoffPay . '<br />';
echo '<strong>Next Cuttoff pay: </strong>' . $nextCutoffPay . '<br />';

echo '<hr />';

echo '<strong>Cuttoff overtime: </strong>' . $cutoffOvertime . '<br />';
echo '<strong>Next Cuttoff overtime: </strong>' . $nextCutoffOvertime . '<br />';

echo '<hr />';

echo '<strong>Your overtime ended: </strong>' . $cutoffOvertime_diff . '<br />';
echo '<strong>And you should work your balls off until: </strong>' . $nextCutoffOvertime_diff . '<br />';

echo '<hr />';
