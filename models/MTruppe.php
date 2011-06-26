<?php


/**
 * This is the "Truppe" model. Truppe means troops.
 */
class MTruppe extends Model {

  /**
   * Here we say at the constructor that this model work with "truppe" table
   */
  public function __construct () {
    parent::__construct ( 'truppe' );

  }

  /**
   * This method return the name of a troop from his id.
   *
   * @param int $idtruppa the id of troop
   * @return string the name of the troop 
   */
  public function getNome ( $idtruppa ) {
    foreach ( $this->find ( array ( 'nome' ), array ( 'id' => $idtruppa ) ) as $itemTruppa )
      return $itemTruppa['nome'];

  }

  /**
   * Here we count the sum of all resources that you need to create a troop.
   * Is the same method of /models/MEdifici.php.
   *
   * @param int $idtruppa 
   */
  public function getSommaRisorse ( $idtruppa ) {

    foreach ( $this->find ( array ( ), array ( 'id' => $idtruppa ) ) as $item )
      return $item['ferro'] + $item['grano'] + $item['legno'] + $item['roccia'];

  }

}