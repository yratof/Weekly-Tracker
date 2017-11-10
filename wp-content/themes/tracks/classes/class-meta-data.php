<?php

class meta_data {

  static function setup() {
    // Show us what the form posts
    if ( $_POST ) {
      print_r( '<pre>' );
      print_r( $_POST );
      print_r( '</pre>' );
      exit;
    }
  }
}

meta_data::setup();
