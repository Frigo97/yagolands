<?php

/**
 * @author Simone (Demo) Gentili
 */
class Stats extends Controller {

  /**
   *
   * @param Controller $obj 
   */
  public function actionStats(Controller $obj) {

    $obj->layout = 'stats';
    $utenti = new MUtenti;

    $obj->page = $obj->contest;
    $start = $obj->contest * 10;
    $limit = 10;

    $obj->totalPages = $utenti->count() / $limit;
    $obj->model = $utenti->findLimit(array(
        '*', '(select livello from costruzioni where idutente = utenti.id and idedificio = 4) livellomagazzino'
        , '(select livello from costruzioni where idutente = utenti.id and idedificio = 5) livellogranaio'
            ), array(), array(
        'start' => $start,
        'limit' => $limit
            ));
  }

}