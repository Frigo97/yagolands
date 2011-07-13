<?php

/**
 * This is the user model
 */
class MUtenti extends Model {

  /**
   * The constructor
   */
  public function __construct() {
    parent::__construct('utenti');
  }

  /**
   * Verifico se l'utente, tra le sue costruzioni, ha un determinato edificio
   */
  public function hasBuilding($idedificio) {
    $costruzioni = new MCostruzioni();
    foreach ($costruzioni->findAll(array(), array('idutente' => UtenteWeb::status()->user->id, 'idedificio' => $idedificio)) as $itemCostruz)
      return true;
    return false;
  }

  /**
   * This method return the name of a user by his id
   *
   * @param int $idutente
   * @return string
   */
  public function getNome($idutente) {
    foreach ($this->find(array(), array('id' => $idutente))as $itemUtente)
      return $itemUtente['username'];
  }

  /**
   * Questo metodo restituisce un array di due elementi che sono la posiuzione delle
   * ascisse e delle ordinate dell'utente corrente.
   *
   * @return type 
   */
  public function getPosition() {
    if (UtenteWeb::status()->user->id != null) {
      foreach ($this->find(array('x', 'y'), array('id' => UtenteWeb::status()->user->id)) as $item) {
        $return = array(
            'x' => $item['x'],
            'y' => $item['y']
        );
        return $return;
      }
    }
    Log::save(array('string' => 'Richiamato MUtenti::getPosition(); quando l\'utente non è loggato.', 'livello' => 'error'));
    return array();
  }

}