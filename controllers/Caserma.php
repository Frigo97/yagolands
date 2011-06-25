<?php


/**
 * Questo è il controller della caserma, dove si addestrano tutte le truppe per
 * combattere nel mondo di yago.
 */
class Caserma extends Controller {

  /**
   * Addestra le truppe
   * 
   * @todo controllare che l'utente abbia le risorse prima di iniziare effettivamente la produzione
   */
  public function actionAddestra () {

    $idtruppa = $_POST['unita'];
    $quantita = $_POST['numeroTruppe'];
    $coda = new MCodadiaddestramento();

    $fineaddestramento = mktime();
    
    /**
     * 
     */
    
    $coda->create ( array (
        'idutente' => UtenteWeb::status ()->user->id,
        'idtruppa' => $idtruppa,
        'quantita' => $quantita,
        'fineaddestramento' => $fineaddestramento
            ), true );

  }

}