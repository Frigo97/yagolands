<?php


/**
 * @author Simone (Demo) Gentili
 */
class Stats extends Controller {

  /**
   *
   * @param Controller $obj 
   */
  public function actionStats ( Controller $obj ) {

    $obj->layout = 'stats';
    $utenti = new MUtenti;
    $obj->model = $utenti->find(array(
        '*','(select livello from costruzioni where idutente = utenti.id and idedificio = 4) livellomagazzino'
    ));

  }

}