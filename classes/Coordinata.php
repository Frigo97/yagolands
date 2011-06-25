<?php


class Coordinata extends Yagolands {

    private $x = 0;
    private $y = 0;

    public function __construct ( $params = array ( ) ) {
        $this->x = $params['x'] ? $params['x'] : 0;
        $this->y = $params['y'] ? $params['y'] : 0;

    }

    public function getPosition () {
        return array (
            'x' => $this->x,
            'y' => $this->y
        );

    }

    public function muoviASinistra () {
        $this->x --;

    }

    public function muoviADestra () {
        $this->x ++;

    }

    public function muoviInAltoASinistra () {
        if ( $this->y ++ % 2 != 0 )
            $this->x --;

    }

    public function muoviInAltoADestra () {
        if ( $this->y ++ % 2 == 0 )
            $this->x ++;

    }

    public function muoviInBassoASinistra () {
        if ( $this->y % 2 != 0 )
            $this->x --;
        $this->y --;

    }

    public function muoviInBassoADestra () {
        if ( $this->y % 2 == 0 )
            $this->x ++;
        $this->y --;

    }

}