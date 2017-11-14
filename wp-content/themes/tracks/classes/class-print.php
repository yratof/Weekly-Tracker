<?php

class print_page {

  static function setup() {
    add_shortcode( 'print', __CLASS__ . '::print_content' );
  }

  static function print_content( $atts ) {
    return "Print content is here";
  }

}

print_page::setup();