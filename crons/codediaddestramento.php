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
$esercito = new MEsercito;

foreach ($pdo->query('select * from codadiaddestramento where fineaddestramento <= \'' . (date('Y-m-d H:i:s')) . '\'') as $addestramentoFinito) {
  $esercito->addOne($addestramentoFinito['idtruppa'], $addestramentoFinito['idutente']);
  $pdo->query('delete from codadiaddestramento where id = ' . $addestramentoFinito['id']);
};

Log::save(array(
    'string' => 'Coda di addestramento aggiornata'
));
