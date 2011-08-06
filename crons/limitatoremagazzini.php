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

  Log::save(array('string' => 'itemUtenti => ' . var_export($itemUtenti['id'], true)));

  foreach (array('magazzino', 'granaio') as $itemContenitore) {

    Log::save(array('string' => 'edificio => ' . var_export($itemContenitore, true)));
    
    $idCostruzione = $costruzioni->getIdCostruzione($edifici->getId($itemContenitore), $itemUtenti['id']);

    Log::save(array('string' => 'costruzione => ' . var_export($idCostruzione, true)));
    
    $livello = $costruzioni->getLivelloProprietario($idCostruzione, $idutente);

    Log::save(array('string' => 'livello => ' . var_export($livello, true)));
    
    $capienzaMassima = Config::moltiplicatoreCapienzaEdificio($livello);
    
    $risorseUtente = Config::getRisorseUtente($idutente);
    
    $arrayRisorse = $itemContenitore == 'magazzino' ? array('ferro', 'legno', 'roccia') : array('grano');
    
    foreach ($arrayRisorse as $itemRisorse) {
      $nomeRisorsa = $itemRisorse;
      if ($risorseUtente[$nomeRisorsa] > $capienzaMassima) {
        $risorseUtente[$nomeRisorsa] = $capienzaMassima;
      }
      $utenti->update($risorseUtente, array('id' => $idutente));
    }
  }
}

Log::save(array(
    'string' => 'Limite dei magazzini e dei granai rispettato'
));
