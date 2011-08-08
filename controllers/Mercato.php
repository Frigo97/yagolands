<?php

/**
 * Questo controller gestisce tutte le azioni del mercato.
 * 
 * @todo validare le offerte
 */
class Mercato extends Controller {

  /**
   * Questo metodo mi dice se l'utente che sta cercando di fare lo scambio ha
   * o meno le risorse necessarie.
   *
   * @return bool
   */
  private function userHasResources() {
    $utenti = new MUtenti;
    foreach ($utenti->find(array(), $utenti->getPosition()) as $itemUtenti) {
      if ($itemUtenti[$_POST['nomeRisorsaOfferta']] < $_POST['quantitaOfferta']) {
        return false;
      }
    }
    return true;
  }

  /**
   * Questo metodo si assicura che chi sta cercando di fare lo scambio non abbia
   * esagerato con le proporzioni.
   *
   * @return bool
   */
  private function unbalancedSupply() {
    $rapporto = $_POST['quantitaCercata'] / $_POST['quantitaOfferta'];
    if ($rapporto >= 0.5 && $rapporto <= 2)
      return false;
    return true;
  }

  /**
   * Con questo metodo tolgo le risorse dall'utente e le metto nel mercato
   * in modo tale che tutti gli altri giocatori le possano vedere.
   */
  private function mettiOffertaInMercato() {

    $offerte = new MOfferte;

    $nomeRisorsaOfferta = $_POST['nomeRisorsaOfferta'];
    $quantitaOfferta = $_POST['quantitaOfferta'];
    $nomeRisorsaCercata = $_POST['nomeRisorsaCercata'];
    $quantitaCercata = $_POST['quantitaCercata'];

    $offerte->create(array(
        'idutente' => (int) UtenteWeb::status()->user->id,
        'risorsaofferta' => $nomeRisorsaOfferta,
        'quantitaofferta' => (int) $quantitaOfferta,
        'risorsacercata' => $nomeRisorsaCercata,
        'quantitacercata' => (int) $quantitaCercata,
    ));
  }

  /**
   * Quando viene creata una offerta per il mercato, le risorse vengono trasferite li
   * quindi non si devono più vedere tra le risorse disponibili in magazzino
   */
  private function togliRisorseDaiMagazzini() {

    $utenti = new MUtenti;

    $nomeRisorsaOfferta = $_POST['nomeRisorsaOfferta'];
    $quantitaOfferta = $_POST['quantitaOfferta'];
    $nomeRisorsaCercata = $_POST['nomeRisorsaCercata'];
    $quantitaCercata = $_POST['quantitaCercata'];

    foreach ($utenti->findAll(Config::risorse(), $utenti->getPosition()) as $itemUtente) {
      $utenti->update(array(
          $nomeRisorsaOfferta => $itemUtente [$nomeRisorsaOfferta] - $quantitaOfferta), $utenti->getPosition()
      );
    }
  }

  /**
   * Controllare anche qui che chi accetta l'offerta abbia risorse a sufficienza
   *
   * @param Controller $obj 
   */
  public function actionAccettaofferta(Controller $obj) {

    $offerte = new MOfferte();
    $utenti = new MUtenti();

    foreach ($offerte->find(array(), array('id' => $obj->contest)) as $itemOfferta) {
      $risorseDiChiOffre = Config::getRisorseUtente($itemOfferta['idutente']);
      $risorseDiChiCerca = Config::getRisorseUtente(UtenteWeb::status()->user->id);

      /* Utente che accetta l'offerta  */
      $utenti->update(array(
          $itemOfferta['risorsaofferta'] => $risorseDiChiCerca[$itemOfferta['risorsaofferta']] + $itemOfferta['quantitaofferta'],
          $itemOfferta['risorsacercata'] => $risorseDiChiCerca[$itemOfferta['risorsacercata']] - $itemOfferta['quantitacercata']
              ), array(
          'id' => UtenteWeb::status()->user->id
      ));

      /* Utente che ha creato l'offerta */
      $utenti->update(array(
          $itemOfferta['risorsacercata'] => $risorseDiChiOffre[$itemOfferta['risorsacercata']] + $itemOfferta['quantitacercata']
              ), array(
          'id' => $itemOfferta['idutente']
      ));
      $offerte->delete($obj->contest);
    }

    JSONMessages::message(array('success' => true));
  }

  /**
   * Questo metodo è pubblico e viene richiamato quando si vuole fare uno scambio.
   * Se l'utente ha le risorse necessarie per fare lo scambio e le proporzioni sono
   * buone. allora gli vengono tolte le risorse dal magazzino e da quel momento 
   * le risorse saranno disponibili per chiunque voglia effettuare lo scambio.
   *
   * @param Controller $obj 
   */
  public function actionOfferta(Controller $obj) {

    if (!$this->userHasResources())
      JSONErrorMessages::message(
              false, 'Non hai risorse a sufficienza'
      );

    if ($this->unbalancedSupply())
      JSONErrorMessages::message(
              false, 'Il rapporto tra le due quantità non può essere più del doppio.'
      );

    $this->togliRisorseDaiMagazzini();
    $this->mettiOffertaInMercato();

    JSONErrorMessages::message(
            true, 'L\'offerta è stata messa sul mercato.'
    );
  }

}