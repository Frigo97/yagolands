<?php

/**
 * Questo è il controller degli edifici. Da qui si può vedere il dettaglio di
 * un edificio e quindi accedere alle azioni che lo riguardano.
 */
class Edifici extends Controller {

  /**
   * Questa action consente di visualizzare il dettaglio dell'edificio. E'
   * la action di default.
   * 
   * Qui devono essere impostati il nome dell'edificio. Questa particolare
   * action utilizza un template differente da quello standard: qui,
   * infatti, non vengono eseguite azioni particolari con javascript, come
   * invece avviene nella pagina principale del gioco.
   * 
   * @todo mettere delle pagine nascoste =)
   * @todo caricare capienza edificio solo se l'edificio lo richiede
   * @todo calcolare il livello dell'edificio solo se lo stesso lo richiede
   *
   * @param Controller $obj 
   */
  public function actionDettaglio ( Controller $obj ) {

    $costruzioni = new MCostruzioni;
    $edifici = new MEdifici;

    $nomeCompressoEdificio = $obj->contest;
    $idEdificio = $edifici->getId ( $nomeCompressoEdificio );
    $nomeEdificio = $edifici->getNome ( $idEdificio );
    $livello = $costruzioni->getLivello ( $idEdificio );
    $capienzaEdificio = Config::moltiplicatoreCapienzaEdificio ( $livello );

    $layouts = array (
        'mercato',
        'caserma'
    );

    $obj->layout = in_array ( $obj->contest, $layouts ) ? $obj->contest : 'edificio';
    $obj->pageTitle = 'Dettaglio della costruzione';
    $obj->idEdificio = $idEdificio;
    $obj->nomeEdificio = $nomeEdificio;
    $obj->action .= '/' . $nomeCompressoEdificio;

    $obj->capienzaEdificio = number_format ( $capienzaEdificio );
    $obj->livelloEdificio = $livello;

  }

}