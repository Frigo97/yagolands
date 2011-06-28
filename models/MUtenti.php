<?php


/**
 * This is the user model
 */
class MUtenti extends Model {

  /**
   * The constructor
   */
  public function __construct () {
    parent::__construct ( 'utenti' );

  }

  /**
   * This method return the name of a user by his id
   *
   * @param int $idutente
   * @return string
   */
  public function getNome ( $idutente ) {
    foreach ( $this->find ( array ( ), array ( 'id' => $idutente ) )as $itemUtente )
      return $itemUtente['username'];

  }

  /**
   * Questo metodo restituisce un array di due elementi che sono la posiuzione delle
   * ascisse e delle ordinate dell'utente corrente.
   *
   * @return type 
   */
  public function getPosition () {
    if ( UtenteWeb::status ()->user->id != null ) {
      foreach ( $this->find ( array ( 'x', 'y' ), array ( 'id' => UtenteWeb::status ()->user->id ) ) as $item ) {
        $return = array (
            'x' => $item['x'],
            'y' => $item['y']
        );
        return $return;
      }
    }
    Log::save ( array ( 'string' => 'Richiamato MUtenti::getPosition(); quando l\'utente non è loggato.', 'livello' => 'error' ) );
    return array ( );

  }

}