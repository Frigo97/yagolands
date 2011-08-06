<?php

include_once '../classes/Yagolands.php';
include_once '../classes/Model.php';
include_once '../models/MEdifici.php';
include_once '../models/MCostruzioni.php';
include_once '../models/MUtenti.php';
include_once '../models/MEsercito.php';
include_once '../classes/Config.php';
include_once '../classes/Log.php';

$pdo = new Model;
$costruzione = new MCostruzioni;

foreach ($pdo->query('select * from codadicostruzione where finelavori <= \'' . (date('Y-m-d H:i:s')) . '\'') as $lavorofinito) {
  Log::save(array('string'=>var_dump($lavorofinito,true)));
  $costruzione->update(array(
      'datafinelavoro' => date('Y-m-d H:i:s'),
      'mktimefinelavoro' => mktime(),
      'idutente' => $lavorofinito['idutente'],
      'idedificio' => $lavorofinito['idedificioincostruzione'],
      'livello' => $lavorofinito['livelloincostruzione'],
      'x' => $lavorofinito['x'],
      'y' => $lavorofinito['y'],
          ), array(
      'x' => $lavorofinito['x'],
      'y' => $lavorofinito['y'],
  ));
  $pdo->query('delete from codadicostruzione where id = ' . $lavorofinito['id']);
};

Log::save(array(
    'string' => 'Coda di costruzione aggiornata'
));
