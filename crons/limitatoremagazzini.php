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
$utenti = new MUtenti;
$costruzioni = new MCostruzioni;
$edifici = new MEdifici;
$esercito = new MEsercito;

foreach ($utenti->findAll() as $itemUtenti) {

  foreach (array('magazzino', 'granaio') as $itemContenitore) {

    $idCostruzione = $costruzioni->getIdCostruzione($edifici->getId($itemContenitore), $itemUtenti['id']);

    $livello = $costruzioni->getLivelloProprietario($idCostruzione, $itemUtenti['id']);

    $capienzaMassima = Config::moltiplicatoreCapienzaEdificio($livello);

    $risorseUtente = Config::getRisorseUtente($itemUtenti['id']);

    $arrayRisorse = $itemContenitore == 'magazzino' ? array('ferro', 'legno', 'roccia') : array('grano');

    foreach ($arrayRisorse as $itemRisorse) {
      $nomeRisorsa = $itemRisorse;

      if ($risorseUtente[$nomeRisorsa] > $capienzaMassima) {
        $risorseUtente[$nomeRisorsa] = $capienzaMassima;
      }
      $utenti->update($risorseUtente, array('id' => $itemUtenti['id']));
    }
  }
}

Log::save(array(
    'string' => 'Limite dei magazzini e dei granai rispettato'
));
