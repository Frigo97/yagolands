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

  /**
   * Quando ci sono delle truppe in coda di addestramento e questo è terminato,
   * deve essere aggiunta una truppa all'esercito del giocatore corrispondenten
   *
   * @param int $idtruppa 
   */
  public function addOne ( $idtruppa, $idutente ) {
    if ( $this->countwhere ( array ( 'idtruppa' => $idtruppa, 'idutente' => $idutente ) ) == 0 )
      $this->create ( array (
          'idtruppa' => $idtruppa,
          'idutente' => $idutente,
          'quantita' => 1
      ) );
    else
      $this->incvalue ( 'quantita', array (
          'idtruppa' => $idtruppa,
          'idutente' => $idutente
      ) );

  }

}