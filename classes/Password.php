<?php

/**
 * Questa classe ha il banale compito di generare una password casuale.
 */
class Password extends Yagolands {

    /**
     *
     * @var string 
     */
    private $password;

    /**
     * Questo metodo restituisce il valore della password generata
     *
     * @return string
     */
    public function __toString() {
        return $this->getPassword();
    }

    /**
     * Questo metodo ha il medesimo funzionamento di __toString();, la 
     * differenza sta nel fatto che il metodo magico, viene richiamato 
     * da solo se si vuole rappresentare la stringa, e non se si vuole
     * ricuperare il valore.
     *
     * @return type 
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * Questo è il metodo che genera la password.
     *
     * @return string la password
     */
    private function generatePassword() {
        $chars = "abcdefghijkmnopqrstuvwxyz023456789";
        srand((double) microtime() * 1000000);
        $i = 0;
        $pass = '';

        while ($i <= 7) {
            $num = rand() % 33;
            $tmp = substr($chars, $num, 1);
            $pass = $pass . $tmp;
            $i++;
        }

        return $pass;
    }

    /**
     * Questo è il costruttore è c'è davvero poco da dire a riguardo.
     */
    public function __construct() {
        $this->password = $this->generatePassword();
    }

}