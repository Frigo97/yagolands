<?php


class JSONMessages {

  public static function message ( $params = array ( ) ) {
    die ( json_encode ( $params ) );

  }

}