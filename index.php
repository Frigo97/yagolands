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

include_once 'classes/Error.php';
include_once 'controllers/Site.php';
include_once 'controllers/Caserma.php';
include_once 'controllers/Cells.php';
include_once 'controllers/Construct.php';
include_once 'controllers/Edifici.php';
include_once 'controllers/Json.php';
include_once 'controllers/Mercato.php';
include_once 'controllers/Moveto.php';
include_once 'controllers/Site.php';
include_once 'controllers/Stats.php';
include_once 'controllers/Utenti.php';

Yago2::run ();