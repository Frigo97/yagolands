<?php

include_once '../classes/Yagolands.php';
include_once '../classes/Model.php';
include_once '../models/MEdifici.php';
include_once '../models/MCostruzioni.php';
include_once '../models/MUtenti.php';
include_once '../classes/Config.php';
include_once '../classes/Log.php';

$edifici = new MEdifici();
$costruzioni = new MCostruzioni();
$utenti = new MUtenti();

foreach ($edifici->findAll(array('id', 'camporisorsa'), array('risorsa' => 1)) as $itemEdificio) {
  foreach ($costruzioni->find(array('id', 'idutente', 'mktimefinelavoro', 'livello'), array('idedificio' => $itemEdificio['id'])) as $itemCostruzione) {
    $secondipassati = mktime() - $itemCostruzione['mktimefinelavoro'];
    $secondiperrisorsa = (int) (3600 / Config::risorseAllOra($itemCostruzione['livello']));

    if ($secondiperrisorsa > 0) {

      $nomeRisorsa = $itemEdificio['camporisorsa'];
      $unitadaaggiungere = $secondipassati / $secondiperrisorsa;
      $temporimanente = ($unitadaaggiungere) - ((int) ($unitadaaggiungere));
      $minutiperrisorsa = (int) (60 / Config::risorseAllOra($itemCostruzione['livello']));

      if ($secondipassati > $secondiperrisorsa) {
        try {
          $resto = @$secondipassati % @$secondiperrisorsa;
        } catch (Exception $E) {
          $resto = 0;
        }
        $unità = ($secondipassati - $resto) / $secondiperrisorsa;
        $risorseUtente = Config::getRisorseUtente($itemCostruzione['idutente']);
        $risorseUtente[$nomeRisorsa] += $unità;
        $utenti->update($risorseUtente, array('id' => $itemCostruzione['idutente']));
        $costruzioni->update(array('mktimefinelavoro' => mktime() + $resto), array('id' => $itemCostruzione['id']));
      }
    } else {
      Log::save(array(
          'string' => 'yagolands non prevede che vi siano più di 1 risorsa al secondo',
          'livello' => Log::$ERROR_LEVEL
      ));
    }
  }
}

Log::save(array(
    'string' => 'Risorse aggiornate correttamente'
));
