<?php


class MUtenti extends Model {

    private static $coordiante = null;

    public function __construct () {
        parent::__construct ( 'utenti' );

    }

    public function getPosition () {

//        if ( MUtenti::$coordiante == null ) {
            if ( UtenteWeb::status ()->user->id == null ) {
                Log::save ( array (
                    'string' => 'Richiamato MUtenti::getPosition(); quando l\'utente non è loggato.',
                    'livello' => 'error'
                    ) );
                return array ( );
            } else {
               // Log::save ( array ( 'string' => 'Conosco l\'id dell\'utente' ) );
                foreach ( $this->find ( array ( 'x', 'y' ), array ( 'id' => UtenteWeb::status ()->user->id ) ) as $item ) {
                    $return = array (
                        'x' => $item['x'],
                        'y' => $item['y']
                    );
//                    Log::save ( array ( 'string' => "\r\n" . (var_export ( $return, true )) ) );
//                    MUtenti::$coordiante = $return;
                    return $return;
                }
            }
//            Log::save ( array (
//                'string' => 'ERRORE',
//            ) );
//        } else {
//            Log::save ( array ( 'string' => 'Carico il valore statico' ) );
//            return MUtenti::$coordiante;
//        }

    }

}