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

    $coda = new MCodadiaddestramento;
    $truppe = new MTruppe;
    $utenti = new MUtenti;

    $idtruppa = $_POST['unita'];
    $quantita = $_POST['numeroTruppe'];

    $risorseUtente = Config::getRisorseUtente ();
    $risorsetruppa = Config::getRisorseTruppa ( $idtruppa );

    Log::save ( array (
        'string' => 'Utente' . var_export ( $risorseUtente, true )
    ) );
    Log::save ( array (
        'string' => 'Truppa' . var_export ( $risorsetruppa, true )
    ) );

    $nehai = true;
    foreach ( Config::getArrayRisorse () as $item )
      if ( $risorseUtente[$item] <= $quantita * $risorsetruppa[$item] ) {
        // Log::save ( array ( 'string' => 'Risorse necessarie di' . ($item) . ': ' . ($quantita * $risorsetruppa[$item]) ) );
        // Log::save ( array ( 'string' => 'Risorse di proprietà: ' . ($risorseUtente[$item]) ) );
        $nehai = false;
      }
    // Log::save ( array ( 'string' => $nehai === true ? 'Hai risorse a sufficienza' : 'Non hai risorse a sufficienza' ) );

    if ( $nehai === true ) {
      $fineaddestramento = mktime () + $truppe->getSommaRisorse ( $idtruppa );

      for ( $i = 1; $i <= $quantita; $i ++  )
        $coda->create ( array (
            'idutente' => (int) UtenteWeb::status ()->user->id,
            'idtruppa' => (int) $idtruppa,
            'quantita' => 1,
            'fineaddestramento' => date ( 'Y-m-d H:i:s', mktime () + $truppe->getSommaRisorse ( $idtruppa ) * $i )
        ) );

      $utenti->update ( array (
          'ferro' => $risorseUtente['ferro'] - $quantita * ($risorsetruppa['ferro']),
          'grano' => $risorseUtente['grano'] - $quantita * ($risorsetruppa['grano']),
          'legno' => $risorseUtente['legno'] - $quantita * ($risorsetruppa['legno']),
          'roccia' => $risorseUtente['roccia'] - $quantita * ($risorsetruppa['roccia'])
              ), array (
          'id' => UtenteWeb::status ()->user->id
      ) );
    }

  }

}