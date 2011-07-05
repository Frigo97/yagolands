<?php

/**
 * Questa è la classe da cui deriva ogni cosa. Crea una forte interdipendenza,
 * che è male, ma consente di dare a tutte le classi dei valori globali.
 * 
 * @author Simone (Demo) Gentili 
 */
class Yagolands {

  /**
   * Array di configurazione del sistema.
   *
   * @return array
   */
  public static function database() {
    return array(
        'host' => '127.0.0.1',
        'port' => '8889',
        'dbname' => 'yago2',
        'user' => 'root',
        'password' => 'root'
    );
  }

  /**
   * Array delle risorse del gioco.
   * 
   * @todo fare si che cambiando questo array possa cambiare l'intero gioco.
   *
   * @return array 
   */
  public static function risorse() {

    return array(
        'ferro',
        'grano',
        'legno',
        'roccia'
    );
  }

}