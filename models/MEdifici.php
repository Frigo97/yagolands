<?php


/**
 * Questo è il model degli edifici
 */
class MEdifici extends Model {
  
  /**
   * Richiamo il costruttore di Model passando il nome della tabella che deve
   * essere associata a questo model
   */
  public function __construct () {
    parent::__construct ( 'edifici' );

  }

  /**
   * Verifico se esiste o meno un determinato edifici ricercandolo per id
   *
   * @param int $id
   * @return bool 
   */
  public function isBuilding ( $id ) {
    foreach ( $this->find ( array ( ), array ( 'id' => $id, 'edificio' => 1 ) ) as $item )
      return true;
    return false;

  }

  /**
   * Ricavo il nome di un edificio partendo dal suo id
   * 
   * @todo e se si passa un id non valido?
   *
   * @param int $id
   * @return string 
   */
  public function getNome ( $id ) {
    foreach ( $this->find ( array ( ), array ( 'id' => $id ) ) as $item )
      return $item['nome'];

  }

  /**
   * Ricavo l'id di un edificio partendo dal nome.
   *
   * @param type $nome
   * @return type 
   */
  public function getId ( $nome ) {
    foreach ( $this->findLowerCase ( array ( ), array ( 'nome' => $nome ), true ) as $item )
      return $item['id'];

  }

  /**
   * Dato il nome di un edificio, vengono tolgi gli spazi e si restituisce il
   * nome in formato lowercase. Questo nome viene usato nella querystring ma 
   * anche come nome del template o del file javascript associato ad un certo
   * edificio.
   *
   * @param int $id
   * @return string 
   */
  public function getNomePerContest ( $id ) {
    $nome = $this->getNome ( $id );
    $nome = strtolower ( $nome );
    return str_replace ( " ", "", $nome );

  }

  /**
   * Questo metodo restituisce un array con tutte le risorse necessarie per 
   * realizzare questo edificio a livello 1.
   *
   * @param type $id
   * @return type 
   */
  public function getRisorseComeArray ( $id ) {

    foreach ( $this->find ( array ( ), array ( 'id' => $id ) ) as $item )
      return array (
          'ferro' => $item['ferro'],
          'grano' => $item['grano'],
          'legno' => $item['legno'],
          'roccia' => $item['roccia']
      );

  }

  /**
   * Questo metodo somma le risorse per ogni edificio. Viene utilizzanto quando
   * si devono calcolare i tempi di realizzazione di un edificio.
   *
   * @param int $id
   * @return int
   */
  public function getSommaRisorse ( $id ) {

    foreach ( $this->find ( array ( ), array ( 'id' => $id ) ) as $item )
      return $item['ferro'] + $item['grano'] + $item['legno'] + $item['roccia'];

  }

}