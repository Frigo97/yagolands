<?php

function __autoload ( $class ) {
  foreach (
  array (
      'classes', // Tutte le classi del framework
      'models', // I models dell'applicazione
      'controllers' // I controllers dell'applicazione
  ) as $value )
    if ( file_exists ( $value . '/' . $class . '.php' ) )
      include_once $value . '/' . $class . '.php';

}

Yago2::run ();