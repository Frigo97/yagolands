<?php

/**
 * Questo è il controller della caserma, dove si addestrano tutte le truppe per
 * combattere nel mondo di yago.
 */
class Caserma extends Controller {

  /**
   * Training troops
   * 
   * @todo controllare che l'utente abbia le risorse prima di iniziare effettivamente la produzione
   */
  public function actionAddestra() {

    $coda = new MCodadiaddestramento;
    $truppe = new MTruppe;
    $utenti = new MUtenti;

    $idTroops = $_POST['unita'];
    $quantita = $_POST['numeroTruppe'];

    $risorseUtente = Config::getRisorseUtente();
    $risorsetruppa = $truppe->getResources($idTroops);

    $nehai = true;
    foreach (Config::risorse() as $item)
      if ($risorseUtente[$item] <= $quantita * $risorsetruppa[$item])
        $nehai = false;

    if ($nehai === true) {
      $fineaddestramento = mktime() + $truppe->getSommaRisorse($idTroops);

      for ($i = 1; $i <= $quantita; $i++) {
        $fineaddestramento = date('Y-m-d H:i:s', mktime() + $truppe->getSommaRisorse($idTroops) * $i);
        $coda->create(array(
            'idutente' => (int) UtenteWeb::status()->user->id,
            'idtruppa' => (int) $idTroops,
            'quantita' => 1,
            'fineaddestramento' => $fineaddestramento
        ));
      }

      $utenti->update(array(
          'ferro' => $risorseUtente['ferro'] - $quantita * ($risorsetruppa['ferro']),
          'grano' => $risorseUtente['grano'] - $quantita * ($risorsetruppa['grano']),
          'legno' => $risorseUtente['legno'] - $quantita * ($risorsetruppa['legno']),
          'roccia' => $risorseUtente['roccia'] - $quantita * ($risorsetruppa['roccia'])
              ), array(
          'id' => UtenteWeb::status()->user->id
      ));
    }

    /* Becouse of is an ajax request: */
    die();
  }

}