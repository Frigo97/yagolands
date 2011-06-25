<?php


/**
 * Questa è la classe che deve essere interrogata quando ci sono da fare delle
 * chiamate JSON. Questa classe si occupa solo di questo.
 */
class Json extends Controller {

  /**
   * @todo creare un array con i nomi dei campi di "offerte" e fare un foreach qui
   */
  public function actionElencoofferte () {

    $offerte = new MOfferte;
    $risorseUtente = Config::getRisorseUtente ();

    $listaDiOfferte = array ( );

    foreach ( $offerte->findAll () as $itemOfferta )
      if ( $itemOfferta['idutente'] != UtenteWeb::status ()->user->id )
        if ( $risorseUtente[$itemOfferta['risorsacercata']] >= $itemOfferta['quantitacercata'] )
          $listaDiOfferte[] = array (
              'id' => (int) $itemOfferta['id'],
              'idutente' => (int) $itemOfferta['idutente'],
              'risorsaofferta' => $itemOfferta['risorsaofferta'],
              'quantitaofferta' => (int) $itemOfferta['quantitaofferta'],
              'risorsacercata' => $itemOfferta['risorsacercata'],
              'quantitacercata' => (int) $itemOfferta['quantitacercata'],
          );

    JSONMessages::message ( $listaDiOfferte );

  }

  /**
   * @todo Creare una classe che faccia la conversione da datetime a singoli campettini
   */
  public function actionEndcoda () {
    $coda = new MCodadicostruzione;
    $edifici = new MEdifici;
    $arraylavori = array ( );
    $codavuota = true;
    $numerolavori = 0;
    foreach ( $coda->findAll ( array ( ), array (
        'idutente' => UtenteWeb::status ()->user->id
    ) ) as $item ) {
      $hour = substr ( $item['finelavori'], 11, 2 );
      $minute = substr ( $item['finelavori'], 14, 2 );
      $second = substr ( $item['finelavori'], 17, 2 );
      $year = substr ( $item['finelavori'], 0, 4 );
      $month = substr ( $item['finelavori'], 5, 2 );
      $day = substr ( $item['finelavori'], 8, 2 );
      $codavuota = false;
      if ( $item['idutente'] == UtenteWeb::status ()->user->id ) {
        $numerolavori ++;
        $arraylavori[] = array (
            'nome' => $edifici->getNome ( $item['idedificioincostruzione'] ),
            'livello' => $item['livelloincostruzione'],
            'anno' => $year,
            'mese' => $month,
            'giorno' => $day,
            'ore' => $hour,
            'minuti' => $minute,
            'secondi' => $second,
            'secondtstoleft' => mktime ( $hour, $minute, $second, $month, $day, $year ) - mktime (),
        );
      }
    };
    if ( $codavuota == true )
      JSONMessages::message ( array (
          'codavuota' => 'true'
      ) );

    JSONMessages::message (
            array_merge (
                    array (
                'codavuota' => 'false',
                'costruzioniincoda' => $numerolavori
                    ), $arraylavori
            )
    );

  }

  public function actionMybuildings () {

    $edifici = new MEdifici();
    $costruzioni = new MCostruzioni();

    $edificicostruiti = array ( );

    foreach ( $costruzioni->find ( array ( ), array ( 'idutente' => UtenteWeb::status ()->user->id ) ) as $item ) {
      if ( $edifici->isBuilding ( $item['idedificio'] ) ) {
        $edificicostruiti[$item['idedificio']]['nome'] = $edifici->getNome ( $item['idedificio'] );
        $edificicostruiti[$item['idedificio']]['livello'] = $item['livello'];
      }
    }

    JSONMessages::message ( $edificicostruiti );

  }

  public function actionMyfields () {

    $edifici = new MEdifici();
    $costruzioni = new MCostruzioni();

    $edificicostruiti = array ( );

    foreach ( $costruzioni->find ( array ( ), array ( 'idutente' => UtenteWeb::status ()->user->id ) ) as $item ) {
      if ( ! $edifici->isBuilding ( $item['idedificio'] ) ) {
        $edificicostruiti[$item['idedificio']]['nome'] = $edifici->getNome ( $item['idedificio'] );
        $edificicostruiti[$item['idedificio']]['livello'] = $item['livello'];
      }
    }

    JSONMessages::message ( $edificicostruiti );

  }

  /**
   * @todo si deve costruire vicino ad un edificio già esistente, il primo si può costruire ovunque).
   * @todo non devo mostrare edifici di cui non possiedo le risorse necessarie.
   */
  public function actionBuildable () {

    $edifici = new MEdifici;
    $costruzioni = new MUtenti;
    $costruzione = new MCostruzioni;
    $coda = new MCodadicostruzione;
    $edificio = new MEdifici;

    /**
     * Verifico che in questa posizione ci sia una cella di pianura, in
     * caso contrario, dico che non si può costruire nulla.
     */
    $cells = new MCells;
    if ( $cells->countwhere ( $costruzioni->getPosition () ) == 0 )
      JSONMessages::message ();

    /**
     * Verifico che in questa posizione ci sia una cella di mia proprieta.
     */
    if ( $cells->countwhere ( array_merge ( $costruzioni->getPosition (), array ( 'idutente' => UtenteWeb::status ()->user->id ) ) ) == 0 )
      JSONMessages::message ();


    $mierisorse = Config::getRisorseUtente ();

    if ( $costruzione->countwhere ( $costruzioni->getPosition () ) == 1 ) {
      if ( $costruzione->countwhere (
                      array_merge ( $costruzioni->getPosition (), array (
                          'idutente' => UtenteWeb::status ()->user->id
                      ) )
              ) == 1 ) {
        foreach ( $costruzione->find ( array ( ), $costruzioni->getPosition () ) as $item ) {
          $idedificio = $item['idedificio'];
          $livellodacostruire = $item['livello'] + 1;
          foreach ( $coda->findOrderBy ( array (
              'finelavori' => 'desc'
                  ), array (
              'idedificioincostruzione' => $idedificio,
              'idutente' => UtenteWeb::status ()->user->id ), 1 ) as $itemInCoda )
            JSONMessages::message ();

          $hadipendenze = false;
          $d = new MDipendenze;
          foreach ( $d->find ( array ( ), array (
              'iddipendente' => $idedificio,
              'livellodipendente' => $livellodacostruire
          ) ) as $dipendenza )
            if ( ! $costruzione->exists ( UtenteWeb::status ()->user->id, $dipendenza['iddipeso'], $dipendenza['livellodipeso'] ) )
              JSONMessages::message ();

          foreach ( $edifici->find ( array ( ), array ( 'id' => $idedificio ) ) as $item ) {
            $risorseedificio = array (
                'ferro' => $item['ferro'] * $livellodacostruire,
                'grano' => $item['grano'] * $livellodacostruire,
                'legno' => $item['legno'] * $livellodacostruire,
                'roccia' => $item['roccia'] * $livellodacostruire
            );
          }

          foreach ( Config::getArrayRisorse () as $risorsa )
            if ( $mierisorse[$risorsa] < ($risorseedificio[$risorsa]) )
              JSONMessages::message ();

          JSONMessages::message ( array (
              $idedificio => array (
                  'livello' => $livellodacostruire,
                  'nome' => $edifici->getNome ( $idedificio )
              )
          ) );
        }
        JSONMessages::message ();
      }
      JSONMessages::message ();
    }

    $edificicostruibili = array ( );
    $miecostruzioni = array ( );
    $livelli = array ( );

    // Log::save ( array ( 'string' => 'Carico i miei edifici' ) );
    foreach ( $costruzione->find ( array ( ), array (
        'idutente' => UtenteWeb::status ()->user->id
    ) )as $item ) {
      $miecostruzioni[] = $item['idedificio'];
    }

    // Log::save ( array ( 'string' => 'Carico tutti gli edifici' ) );
    foreach ( $edifici->findAll () as $key => $value ) {

      $risorse = array (
          'ferro' => $value['ferro'],
          'grano' => $value['grano'],
          'legno' => $value['legno'],
          'roccia' => $value['roccia']
      );

      if ( ! in_array ( $value['id'], $miecostruzioni ) ) {

//                Log::save ( array ( 'string' => 'L\'edificio "' . ($value['nome']) . '" non è stato costruito.' ) );
//                Log::save ( array ( 'string' => 'L\'edificio "' . ($value['nome']) . '" potrebbe avere delle dipendenze.' ) );





        $hadipendenze = false;
        $d = new MDipendenze;
        foreach ( $d->find ( array ( ), array ( 'iddipendente' => $value['id'], 'livellodipendente' => 1 ) ) as $dipendenza ) {
//                    Log::save ( array ( 'string' => 'Non posso costruire l\'edificio "' . ($value['nome']) . '".' ) );
          if ( ! $costruzione->exists ( UtenteWeb::status ()->user->id, $dipendenza['iddipeso'], $dipendenza['livellodipeso'] ) )
            $hadipendenze = true;
        }
        if ( $hadipendenze == false ) {
          // Log::save ( array ( 'string' => 'Posso costruire l\'edificio "' . ($value['nome']) . '".' ) );


          foreach ( $edifici->find ( array ( ), array ( 'id' => $value['id'] ) ) as $item ) {
            $risorseedificio = array (
                'ferro' => $item['ferro'] * Config::moltiplicatoreRisorseEdificio ( 1 ),
                'grano' => $item['grano'] * Config::moltiplicatoreRisorseEdificio ( 1 ),
                'legno' => $item['legno'] * Config::moltiplicatoreRisorseEdificio ( 1 ),
                'roccia' => $item['roccia'] * Config::moltiplicatoreRisorseEdificio ( 1 )
            );
          }

          $haabbastanzarisorse = true;
          foreach ( Config::getArrayRisorse () as $nomeRisorsa ) {
            if ( $haabbastanzarisorse == true )
              if ( $mierisorse[$nomeRisorsa] < $risorseedificio[$nomeRisorsa] ) {
                $haabbastanzarisorse = false;
                break;
              }
          }


          if ( $haabbastanzarisorse == true ) {
            $edificicostruibili[$value['id']]['nome'] = $value['nome'];
            $edificicostruibili[$value['id']]['livello'] = 1;
          }
        }
      }
    }
    JSONMessages::message ( $edificicostruibili );

  }

  public function actionCreateplace () {

    $c = new MCells();
    $u = new MUtenti();
    $num = $c->countwhere ( $u->getPosition () );
    JSONMessages::message ( array (
        'empty' => $num == 0 ?
                'true' :
                'false'
    ) );

  }

  public function actionCostruzionipresenti () {
    $utenti = new MUtenti();
    $costruzioni = new MCostruzioni();
    $edific = new MEdifici();
    foreach ( $utenti->find ( array ( 'x', 'y' ), array ( 'id' => UtenteWeb::status ()->user->id ) ) as $item )
      foreach ( $costruzioni->find ( array ( 'id', 'idedificio' ), array ( 'x' => $item['x'], 'y' => $item['y'] ) ) as $item )
        die ( json_encode ( array_merge ( array ( 'nomepercontest' => $edific->getNomePerContest ( $item['idedificio'] ), 'nome' => $edific->getNome ( $item['idedificio'] ), 'id' => $item['id'] ), array ( 'id' => $item['id'] ) ) ) );
    die ( json_encode ( array ( 'nome' => null ) ) );

  }

  public function actionRisorse () {
    JSONMessages::message ( Config::getRisorseUtente () );

  }

  public function actionPosizione () {
    $utenti = new MUtenti;
    JSONMessages::message ( $utenti->getPosition () );

  }

  public function actionCells () {
    
    

    $celle = new MCells;
    $proprieta = new MProprieta;
    $numproprieta = $celle->countwhere ( array ( 'idutente' => UtenteWeb::status ()->user->id ) );
    if ( $numproprieta == 0 ) {
      $nuovocentro = new NuovaPosizione();
      $idutente = UtenteWeb::status ()->user->id;
      $nuovaposizione = new NuovaPosizione();
      $coordinata = new Coordinata ( $nuovocentro->getActivationCoords ( $idutente ) );
      $coordinataCentro = $nuovocentro->getActivationCoords ( $idutente );
      $celle->createIfNotExists ( $coordinata, UtenteWeb::status ()->user->id, UtenteWeb::status ()->user->username );
      $coordinata->muoviInAltoASinistra ();
      $celle->createIfNotExists ( $coordinata, UtenteWeb::status ()->user->id, UtenteWeb::status ()->user->username );
      $coordinata->muoviADestra ();
      $celle->createIfNotExists ( $coordinata, UtenteWeb::status ()->user->id, UtenteWeb::status ()->user->username );
      $coordinata->muoviInBassoADestra ();
      $celle->createIfNotExists ( $coordinata, UtenteWeb::status ()->user->id, UtenteWeb::status ()->user->username );
      $coordinata->muoviInBassoASinistra ();
      $celle->createIfNotExists ( $coordinata, UtenteWeb::status ()->user->id, UtenteWeb::status ()->user->username );
      $coordinata->muoviASinistra ();
      $celle->createIfNotExists ( $coordinata, UtenteWeb::status ()->user->id, UtenteWeb::status ()->user->username );
      $coordinata->muoviInAltoASinistra ();
      $celle->createIfNotExists ( $coordinata, UtenteWeb::status ()->user->id, UtenteWeb::status ()->user->username );
    }

    $jsonCelle = array ( );
    foreach ( $celle->findAll () as $key => $value )
      $jsonCelle[$value['id']] = array (
          'x' => $value['x'],
          'y' => $value['y'],
          'cell' => $value['cell'],
          'owner' => $value['username'] == UtenteWeb::status ()->user->username ? 'you' : 'others',
      );


    JSONMessages::message ( $jsonCelle );

  }

}