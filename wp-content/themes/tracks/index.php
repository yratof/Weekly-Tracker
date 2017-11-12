<?php get_header();


// Require the whole form, logic and math
require_once( 'classes/class-carbon-dating.php' );

// The cleaner, class version of the form
// require_once( 'classes/class-carbon-dating-cleanup.php' );

// In order to drop content onto the page, use
// this action here.
do_action( 'body' );

get_footer(); ?>