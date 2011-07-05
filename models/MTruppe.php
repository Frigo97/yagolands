<?php

/**
 * We need these classes for test with phpUnit :-\
 * We need these?
 * 
 * @author Simone (Demo) Gentilio
 */
include_once __DIR__ . '/../classes/Yagolands.php';
include_once __DIR__ . '/../classes/Config.php';
include_once __DIR__ . '/../classes/Model.php';
include_once __DIR__ . '/../classes/Log.php';

/**
 * This is the "Truppe" model. Truppe means troops.
 */
class MTruppe extends Model {

  /**
   * This method returns an array that contains all the resources of a troop.
   *
   * @param int $id
   * @return array
   */
  public function getResources($idTroops = null) {

    foreach ($this->find(Config::risorse(), array('id' => $idTroops)) as $item)
      return array(
          'ferro' => $item['ferro'],
          'grano' => $item['grano'],
          'legno' => $item['legno'],
          'roccia' => $item['roccia']
      );
  }

  /**
   * Here we say at the constructor that this model work with "truppe" table
   */
  public function __construct() {
    parent::__construct('truppe');
  }

  /**
   * This method return the name of a troop from his id.
   *
   * @param int $idtruppa the id of troop
   * @return string the name of the troop 
   */
  public function getNome($idtruppa) {
    foreach ($this->find(array('nome'), array('id' => $idtruppa)) as $itemTruppa)
      return $itemTruppa['nome'];
  }

  /**
   * Here we count the sum of all resources that you need to create a troop.
   * Is the same method of /models/MEdifici.php.
   *
   * @param int $idtruppa 
   */
  public function getSommaRisorse($idtruppa) {

    foreach ($this->find(array(), array('id' => $idtruppa)) as $item)
      return $item['ferro'] + $item['grano'] + $item['legno'] + $item['roccia'];
  }

}