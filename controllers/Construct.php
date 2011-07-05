<?php


class Construct extends Controller {

    public function actionBuilding () {

        $utenti = new MUtenti;
        $costruzioni = new MCostruzioni;
        $coda = new MCodadicostruzione;
        $edifici = new MEdifici;

        /**
         * Se non esiste una cella in questa posizione non posso costruire, 
         * quindi restituisco un array vuoto.
         */
        $cells = new MCells;
        if ( $cells->countwhere ( $utenti->getPosition () ) == 0 )
            die ( json_encode ( array ( ) ) );

        /**
         * @todo impedire al costruzione di edifici in territori non propri.
         */
        $risorseedificio = $edifici->getRisorseComeArray ( $_POST['idedificio'] );

        if ( $costruzioni->countwhere ( $utenti->getPosition () ) == 0 ) {

            /**
             * @todo togliere il mktime o la data. In ogni caso, uniformare
             */
            $costruzioni->create ( array_merge ( array (
                        'datafinelavoro' => date ( 'Y-m-d H:i:s' ),
                        'mktimefinelavoro' => mktime (),
                        'idedificio' => $_POST['idedificio'],
                        'idutente' => UtenteWeb::status ()->user->id,
                        'livello' => 0
                            ), $utenti->getPosition () ) );
            $cells->update ( array ( 'cell' => $_POST['idedificio'] ), $utenti->getPosition () );

            $coordinata = new Coordinata ( $utenti->getPosition () );
            $coordinata->muoviInAltoASinistra ();
            $coordinata->muoviADestra ();
            $cells->createIfNotExists ( $coordinata, UtenteWeb::status ()->user->id, UtenteWeb::status ()->user->username );
            $coordinata->muoviInBassoADestra ();
            $cells->createIfNotExists ( $coordinata, UtenteWeb::status ()->user->id, UtenteWeb::status ()->user->username );
            $coordinata->muoviInBassoASinistra ();
            $cells->createIfNotExists ( $coordinata, UtenteWeb::status ()->user->id, UtenteWeb::status ()->user->username );
            $coordinata->muoviASinistra ();
            $cells->createIfNotExists ( $coordinata, UtenteWeb::status ()->user->id, UtenteWeb::status ()->user->username );
            $coordinata->muoviInAltoASinistra ();
            $cells->createIfNotExists ( $coordinata, UtenteWeb::status ()->user->id, UtenteWeb::status ()->user->username );
            $coordinata->muoviInAltoADestra ();
            $cells->createIfNotExists ( $coordinata, UtenteWeb::status ()->user->id, UtenteWeb::status ()->user->username );

            /**
             * @todo controllare anche qui che l'utente sia in possesso delle risorse necessarie
             */
            foreach ( Config::risorse() as $item )
                foreach ( $utenti->find ( array ( ), array ( 'id' => UtenteWeb::status ()->user->id ) ) as $itemm )
                    $utenti->update ( array ( $item => $itemm[$item] - $risorseedificio[$item] ), array ( 'id' => UtenteWeb::status ()->user->id ) );

            $expression = $coda->findOrderBy ( array ( 'finelavori' => 'desc' ), array ( 'idutente' => UtenteWeb::status ()->user->id ), 1 );
            $cisonoaltrilavori = false;
            foreach ( $expression as $item ) {
                $finelavori = $item['finelavori'];
                $cisonoaltrilavori = true;
            }
            $second = substr ( $finelavori, 17, 2 );
            $minute = substr ( $finelavori, 14, 2 );
            $hour = substr ( $finelavori, 11, 2 );
            $day = substr ( $finelavori, 8, 2 );
            $month = substr ( $finelavori, 5, 2 );
            $year = substr ( $finelavori, 0, 4 );
            if ( mktime ( $hour, $minute, $second, $month, $day, $year ) >= mktime () )
                $tempodipartenza = mktime ( $hour, $minute, $second, $month, $day, $year );
            else
                $tempodipartenza = mktime ();

            $coda->create ( array_merge ( array (
                        'idedificioincostruzione' => $_POST['idedificio'],
                        'livelloincostruzione' => 1,
                        'idcostruzione' => $costruzioni->lastInsertId (),
                        'finelavori' => date ( 'Y-m-d H:i:s', $tempodipartenza + $edifici->getSommaRisorse ( $_POST['idedificio'] ) * 1 ),
                        'idutente' => UtenteWeb::status ()->user->id
                            ), $utenti->getPosition () ) );
        } else {
            /**
             * @todo controllare che l'utente sia in possesso delle risorse necessarie
             */
            foreach ( $costruzioni->find ( array ( 'livello' ), array ( 'idedificio' => $_POST['idedificio'], 'idutente' => UtenteWeb::status ()->user->id ) ) as $item ) {
                $livelloattuale = $item['livello'];
                $nuovolivello = $livelloattuale + 1;

//                Log::save ( array ( 'string' => 'Tolgo risorse all\'utente.' ) );
//                Log::save ( array ( 'string' => 'Per costruire l\'edificio ' . ($edifici->getNome ( $_POST['idedificio'] ) . ' di livello ' . ($nuovolivello) . ' occorre: ') ) );
                foreach ( Config::risorse() as $itemRisorse )
                    foreach ( $utenti->find ( array ( ), array ( 'id' => UtenteWeb::status ()->user->id ) ) as $itemm ) {
                        $utenti->update ( array ( $itemRisorse => $itemm[$itemRisorse] - ($risorseedificio[$itemRisorse] * Config::moltiplicatoreRisorseEdificio ( $nuovolivello )) ), array ( 'id' => UtenteWeb::status ()->user->id ) );
//                        Log::save ( array ( 'string' => 'tolgo ' . ($risorseedificio[$itemRisorse] * Config::moltiplicatoreRisorseEdificio ( $nuovolivello )) . ' che poi è ' . $risorseedificio[$itemRisorse] . '*' . Config::moltiplicatoreRisorseEdificio ( $nuovolivello ) ) );
                    }


                $expression = $coda->findOrderBy ( array ( 'finelavori' => 'desc' ), array ( 'idcostruzione' => $item['id'], 'idutente' => UtenteWeb::status ()->user->id ), 1 );
                $cisonoaltrilavori = false;
                foreach ( $expression as $item ) {
                    $finelavori = $item['finelavori'];
                    $cisonoaltrilavori = true;
                }
                $second = substr ( $finelavori, 17, 2 );
                $minute = substr ( $finelavori, 14, 2 );
                $hour = substr ( $finelavori, 11, 2 );
                $day = substr ( $finelavori, 8, 2 );
                $month = substr ( $finelavori, 5, 2 );
                $year = substr ( $finelavori, 0, 4 );
                if ( mktime ( $hour, $minute, $second, $month, $day, $year ) >= mktime () )
                    $tempodipartenza = mktime ( $hour, $minute, $second, $month, $day, $year );
                else
                    $tempodipartenza = mktime ();

                $coda->create ( array_merge ( array (
                            'idedificioincostruzione' => $_POST['idedificio'],
                            'livelloincostruzione' => $nuovolivello,
                            'idcostruzione' => $costruzioni->lastInsertId (),
                            'finelavori' => date ( 'Y-m-d H:i:s', $tempodipartenza + $edifici->getSommaRisorse ( $_POST['idedificio'] ) * Config::moltiplicatoreRisorseEdificio ( $nuovolivello ) ),
                            'idutente' => UtenteWeb::status ()->user->id
                                ), $utenti->getPosition () ) );
            }
        }
        die ();

    }

}