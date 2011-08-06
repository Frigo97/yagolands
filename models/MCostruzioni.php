<?php

/**
 * Questo è il model delle costruzioni e qui si gestiscono tutti i comportamenti
 * che fanno riferimento alle costrizioni.
 * 
 * @author Simone (Demo) Gentili
 */
class MCostruzioni extends Model {

  /**
   * Beh questo è il costruttore... che cosa ci vuoi fare con il costruttore?
   */
  public function __construct() {
    parent::__construct('costruzioni');
  }

  /**
   * Questo metodo restituisce il nome di una costruzione.
   *
   * @param type $idcostruzione
   * @return string 
   */
  public function getNome($idcostruzione, MEdifici $edificio) {
    foreach ($this->find(array('idedificio'), array('id' => $idcostruzione)) as $itemCostruzione)
      foreach ($edificio->findAll(array('nome'), array('id' => $itemCostruzione['idedificio'])) as $itemEdificio)
        return $itemEdificio['nome'];
  }

  /**
   * Dato l'id dell'edificio, mi restituisce il livello. Questa funzione ha 
   * bisogno di un utente loggato. Infatti fa uso della classe UtenteWeb.
   * Per ovvie ragioni, non può funzionare se è un utente che non ha
   * effettuato il login ad utilizzarla.
   *
   * @param int $idedificio
   * @return int 
   */
  public function getLivello($idedificio) {

    if (UtenteWeb::status()->user->id == null)
      Log::save(array(
          'string' => 'Si sta cercando di usare MCostruzioni::getLivello senza essere autenticati.',
          'livello' => Log::$ERROR_LEVEL
      ));

    foreach ($this->find(array(), array('idedificio' => $idedificio, 'idutente' => UtenteWeb::status()->user->id)) as $item)
      return $item['livello'];
    return 0;
  }

  /**
   * Dato l'id dell'edificio, mi restituisce il livello. Questa funzione ha 
   * bisogno di un utente loggato. Infatti fa uso della classe UtenteWeb.
   * Per ovvie ragioni, non può funzionare se è un utente che non ha
   * effettuato il login ad utilizzarla.
   *
   * @param int $idedificio
   * @return int 
   */
  public function getLivelloProprietario($idedificio, $idutente) {

    foreach ($this->find(array(), array('idedificio' => $idedificio, 'idutente' => $idutente)) as $item)
      return $item['livello'];
    return 0;
  }

  /**
   * Dato l'id dell'edificio, mi restituisce l'id costruzione.
   *
   * @param int $idedificio
   * @return int 
   */
  public function getIdCostruzione($idedificio, $idutente) {
    foreach ($this->find(array(), array('idedificio' => $idedificio, 'idutente' => $idutente)) as $item)
      return $item['livello'];
    return 0;
  }

  /**
   * Dato l'id dell'edificio, mi restituisce il proprietario.
   *
   * @param int $idcostruzione
   * @return int $idutente
   */
  public function getIdOwner($idcostruzione) {
    foreach ($this->find(array('idutente'), array('idedificio' => $idcostruzione)) as $item)
      return $item['idutente'];
    return 0;
  }

  /**
   * Non deve succedere per nessuna ragione che esistano due edifici dello
   * stesso tipo. Questa funzione viene richiamata per capire se un edificio
   * esiste o meno. In particolare, quando ci sono delle dipendenze tra
   * edifici, devo assicurarmi che esista un particolare edificio ad un
   * particolare livello. Questa funzione svolte questo scopo.
   *
   * @param int $idutente
   * @param int $idedificio
   * @param int $livello
   * @return bool 
   */
  public function exists($idutente, $idedificio, $livello) {

    $e = new MEdifici;

    foreach ($this->find(array(), array('idedificio' => $idedificio, 'idutente' => $idutente)) as $item)
      if ($item['livello'] >= $livello)
        return true;
    return false;
  }

}