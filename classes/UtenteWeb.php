<?php


class UtenteWeb extends Yagolands {

    public $user;

    public function nuovaPassword ( $password ) {

        $utenti = new MUtenti;

        $this->user->password = $password;
        $password = md5 ( $password );
        $r = $utenti->update ( array (
                    'password' => $password
                        ), array (
                    'username' => $this->user->username
                ) );
        Controller::redirect ( 'avviso/passwordmodificata' );

    }

    public function logout () {
        foreach ( $_SESSION as $key => $value ) {
            $_SESSION[$key] = null;
        }
        $this->user = null;

    }

    public static function setPosition ( $params = array ( ) ) {
        $obj = new UtenteWeb();
        $obj->user->x = $params['x'];
        $obj->user->y = $params['y'];

    }

    public function autentica ( $params = array ( ) ) {
//        Log::save ( array ( 'string' => var_export ( $params, true ) ) );
        foreach ( $params as $key => $value ) {
            $this->user->$key = $value;
        }
        $_SESSION['user'] = $this->user;

    }

    public function isAutenticato () {
        $username = UtenteWeb::status ()->user->username;
        $lenusername = strlen ( $username );
        return (bool) ($lenusername || 0);

    }

    public function __construct () {
        if ( isset ( $_SESSION['user'] ) )
            $this->user = $_SESSION['user'];

    }

    public static function status () {
        @session_start ();
        $utente = new UtenteWeb;
        return $utente;

    }

}