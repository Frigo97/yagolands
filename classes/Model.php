<?php


/**
 * Questo è il model. Tutte i pattern delle query sono descritti qui dentro.
 * Non una sola riga di codice DOVREBBE essere eseguita fuori da questa classe.
 */
class Model extends Yagolands {

  private $dbh;
  private $table;
  private $test;

  /**
   *
   * @param string $table nome della tabella
   * @param boolean $test debug o production status 
   */
  public function __construct ( $table = '', $test = false ) {

    $this->test = $test;
    $this->table = $table;

    try {
      $this->dbh = @new PDO ( 'mysql:host=127.0.0.1;port=8889;dbname=yago2', 'root', 'root' );
    } catch ( PDOException $PDOException ) {

      Log::save ( array ( 'string' => $PDOException->getMessage () ) );
      die ( 'Database fuori servizio.' );
    }

  }

  /**
   *
   * @param string $statement
   * @return PDOStatement
   */
  public function query ( $statement ) {
    return $this->dbh->query ( $statement );

  }

  /**
   *
   * @return string restituisce una query sql
   */
  public function count () {

    $query = 'select count(*) num from ' . ($this->table);

    if ( $this->test )
      return $query;

    foreach ( $this->dbh->query ( $query ) as $item )
      return $item['num'];

  }

  /**
   *
   * @param type $where
   * @return type 
   */
  public function countwhere ( $where = array ( ) ) {
    $conditions = $this->renderWhere ( $where );
    $query = 'select count(*) num from ' . ($this->table) . ' where ' . ($conditions);
    foreach ( $this->dbh->query ( $query ) as $item )
      return $item['num'];

  }

  /**
   *
   * @param type $params
   * @param type $where
   * @return type 
   */
  public function findAll ( $params = array ( ), $where = array ( ) ) {
    $parametri = '';
    foreach ( $params as $item )
      $parametri .= $parametri == '' ? $item : ',' . $item;

    $conditions = $this->renderWhere ( $where );

    $query = 'select ' . ($parametri == '' ? '*' : $parametri) . ' from ' . ($this->table) . ($conditions == '' ? '' : ' where ' . $conditions);
    return $this->dbh->query ( $query );

  }

  /**
   *
   * @param type $where
   * @return type 
   */
  private function renderWhere ( $where = array ( ), $lowercase = false ) {


    $conditions = '';
    foreach ( $where as $chiave => $valore ) {
      $conditions .= $conditions == '' ?
              (($lowercase ? 'REPLACE(LCASE(' : '') . $chiave . ($lowercase ? '),\' \',\'\')' : '') . ' = ' . ( ! is_numeric ( $valore ) || $chiave == "password" ?
                      '"' . ($valore) . '"' :
                      $valore)) :
              (' and ' . ($lowercase ? 'REPLACE(LCASE(' : '') . $chiave . ($lowercase ? '),\' \',\'\')' : '') . ' = ' . ( ! is_numeric ( $valore ) || $chiave == "password" ?
                      '"' . ($valore) . '"' :
                      $valore));
    }
    return $conditions;

  }

  /**
   *
   * @param type $params
   * @param type $where
   * @return string 
   */
  public function find ( $params = array ( ), $where = array ( ), $lowercase = false ) {
    $fields = '';
    foreach ( $params as $item ) {
      $fields .= $fields == '' ? $item : ',' . $item;
    }

    $conditions = $this->renderWhere ( $where, $lowercase );

    $query = 'select ' . ($fields == '' ? '*' : $fields) . ' from ' . ($this->table) . ($conditions == '' ? '' : ' where ' . $conditions);


    if ( $this->test == true )
      return $query;

    return $this->dbh->query ( $query );

  }

  public function findLowerCase ( $params = array ( ), $where = array ( ) ) {
    $fields = '';
    foreach ( $params as $item ) {
      $fields .= $fields == '' ? $item : ',' . $item;
    }

    $conditions = $this->renderWhere ( $where, true );

    $query = 'select ' . ($fields == '' ? '*' : $fields) . ' from ' . ($this->table) . ($conditions == '' ? '' : ' where ' . $conditions);

    if ( $this->test == true )
      return $query;

    return $this->dbh->query ( $query );

  }

  /**
   *
   * @param type $params
   * @param type $where
   * @return type 
   */
  public function update ( $params = array ( ), $where = array ( ) ) {
    $campi = '';
    foreach ( $params as $key => $value ) {
      $campi .= $campi == '' ? $this->table . '.' . $key . ' = ' . ( ! is_numeric ( $value ) || $key == "password" ?
                      '"' . ($value) . '"' :
                      $value) : ', ' . $this->table . '.' . $key . ' = ' . ( ! is_numeric ( $value ) || $key == "password" ?
                      '"' . ($value) . '"' :
                      $value);
    }
    $condizioni = '';
    foreach ( $where as $key => $value ) {
      $condizioni .= $condizioni == '' ? $key . ' = ' . ( ! is_numeric ( $value ) || $key == "password" ?
                      '"' . ($value) . '"' :
                      $value) : ' and ' . $key . ' = ' . ( ! is_numeric ( $value ) || $key == "password" ?
                      '"' . ($value) . '"' :
                      $value);
    }

    $query = 'update ' . ($this->table) . ' set ' . ($campi) . ($condizioni != '' ? ' where ' . $condizioni : '');
    //Log::save ( array ( 'string' => $query ) );
    return $this->dbh->exec ( $query );

  }

  /**
   *
   * @param type $params 
   */
  public function create ( $params = array ( ), $debug = false ) {
    $chiavi = '';
    $valori = '';
    foreach ( $params as $chiave => $valore ) {
      $chiavi .= $chiavi == '' ? $chiave : ', ' . ($chiave);
      if ( is_string ( $valore ) )
        $valori .= ( $valori == '' ? '"' . $valore . '"' : ', ' . '"' . ($valore) . '"');
      else
        $valori .= $valori == '' ? $valore : ', ' . ($valore);
    }
    $query = 'insert into ' . ($this->table) . ' (' . ($chiavi) . ') values (' . ($valori) . ')';

    if ( $debug )
      return $query;
    
    $this->dbh->query ( $query );

  }

  /**
   * Questo metodo cancella un record quando noi gli passiamo un id.
   * 
   * @todo Assicurarsi di aver ricevuto un id esistente.
   * @param int $id 
   */
  public function delete ( $id = 0 ) {
    $this->dbh->exec ( 'delete from ' . ($this->table) . ' where id = ' . ($id) );

  }

  /**
   * 
   */
  public function truncate () {
    $this->dbh->exec ( 'truncate table ' . ($this->table) );

  }

  /**
   *
   * @return type 
   */
  public function lastInsertId () {
    return $this->dbh->lastInsertId ();

  }

  /**
   *
   * @param type $param
   * @param type $where
   * @param type $limit
   * @return string 
   */
  public function findOrderBy ( $param = array ( ), $where = array ( ), $limit = null ) {

    $conditions = $this->renderWhere ( $where );

    $orderby = '';
    foreach ( $param as $key => $value )
      $orderby .= ( $orderby == '' ? '' : ',') . $key . ' ' . $value;

    $query = 'select * from ' . ($this->table) . ' ' . ($conditions == '' ? '' : 'where ' . $conditions . ' ') . (($orderby == '') ? '' : 'order by ') . ($orderby) . ($limit == null ? '' : ' limit ' . $limit);

    if ( $this->test == true )
      return $query;

    return $this->dbh->query ( $query );

  }

}