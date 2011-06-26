<?php


/**
 * Questo è il model dell'esercito
 */
class MEsercito extends Model {

  /**
   * Richiamo il costruttore di Model passando il nome della tabella che deve
   * essere associata a questo model
   */
  public function __construct () {
    parent::__construct ( 'esercito' );

  }

  public function addOne ( $idtruppa ) {
    if ( $this->countwhere ( array ( 'idtruppa' => $idtruppa ) ) == 0 )
      $this->create ( array (
          'idtruppa' => $idtruppa,
          'idutente' => UtenteWeb::status ()->user->id,
          'quantita' => 1
      ) );
    else
      $this->incvalue ( 'quantita', array (
          'idtruppa' => $idtruppa,
          'idutente' => UtenteWeb::status ()->user->id
      ) );

  }

}