<?php
/*
Very simple pages. Everything is
controlled by shortcodes and placed
based on the page it's using.
*/

get_header();
if ( have_posts() ) :
  while ( have_posts() ) :
    the_post();
      // The content is all that matters here
      the_content();
  endwhile;
endif;
get_footer();
