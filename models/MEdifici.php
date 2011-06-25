<?php


/**
 * 
 */
class MEdifici extends Model {

    public static $CASERMA = 2;

    public function __construct () {
        parent::__construct ( 'edifici' );

    }

    public function isBuilding ( $id ) {
        foreach ( $this->find ( array ( ), array ( 'id' => $id, 'edificio' => 1 ) ) as $item )
            return true;
        return false;

    }

    public function getNome ( $id ) {
        foreach ( $this->find ( array ( ), array ( 'id' => $id ) ) as $item )
            return $item['nome'];
    }

    public function getId ( $nome ) {
        foreach ( $this->find ( array ( ), array ( 'nome' => $nome ), true ) as $item )
            return $item['id'];
    }

    public function getNomePerContest ( $id ) {
        $nome = $this->getNome ( $id );
        $nome = strtolower ( $nome );
        return str_replace ( " ", "", $nome );

    }

    public function getRisorseComeArray ( $id ) {

        foreach ( $this->find ( array ( ), array ( 'id' => $id ) ) as $item )
            return array (
                'ferro' => $item['ferro'],
                'grano' => $item['grano'],
                'legno' => $item['legno'],
                'roccia' => $item['roccia']
            );

    }

    public function getSommaRisorse ( $id ) {

        foreach ( $this->find ( array ( ), array ( 'id' => $id ) ) as $item )
            return $item['ferro'] + $item['grano'] + $item['legno'] + $item['roccia'];

    }

}