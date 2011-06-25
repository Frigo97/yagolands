<?php

/**
 * Il controller Cells gestisce l'azione che crea nuove celle.
 * 
 * 
 * 
 * @todo non era necessario creare un controller apposta per questa azione
 */
class Cells extends Controller {

    /**
     * Questo metodo crea una nuova cella di terreno.
     *
     * @todo disabilitare in automatico la creazione manuale dei terreni in produzione
     * @todo spostare il controllo dell'autenticazione all'esterno
     * 
     * @param Controller $obj 
     */
    public function actionCreateplace (Controller $obj) {

        if ( ! UtenteWeb::status ()->isAutenticato () )
            $this->redirect ( 'site/login' );

        $utenti = new MUtenti;
        $cells = new MCells;
        foreach ( $utenti->find ( array ( 'x', 'y' ), array ( 'username' => UtenteWeb::status ()->user->username ) ) as $item )
            if ( ! $cells->cellExist ( array (
                        'x' => $item['x'],
                        'y' => $item['y'],
                    ) ) )
                $cells->create ( array (
                    'x' => $item['x'],
                    'y' => $item['y'],
                ) );

        die ();

    }

}