<?php

/**
 * Questa è la classe di configurazione dove vengono memorizzate tutte quante
 * le variabili del sito. In alcuni casi si tratta di variabili semplici. Per
 * tutti questi casi, verrà creata una variabile.
 * 
 * Se abbiamo a che fare con qualche calcolo più complesso, allora, verrà
 * creata un metodo apposta.
 * 
 * @todo mettere tutte le variabili dentro ad un array e generarle dinamicamente
 */
class Config extends Yagolands {

  /**
   * Questa è una variabile molto importante. Quando il codice viene messo in
   * produzione, questa vatiabile deve essere impostata a TRUE. Quando è su
   * FALSE significa che stiamo ancora sviluppando. In fase di sviluppo si 
   * possono vedere molte cose in più. Per esempio è possibile vedere le
   * statistiche.
   *
   * @var bool
   */
  public static $PRODUCTION = FALSE;

  /**
   * Ci sono casi in cui il sito deve andare offline (il sito rimane attivo ma
   * non è possibile giocare. Questo può accadere in quei casi ci sia bisogno
   * di fare interventi manuali o aggiornamenti al software. Senza questo
   * stratagemma i giocatori potrebbero richiedere pagine non disponibili.
   * Questo disagio non deve esserci.
   *
   * @var bool
   */
  public static $OFFLINE = TRUE;

  /**
   *
   * @var int
   */
  public static $passoPerAllocazioneNuoviTerreni = 7;

  /**
   *
   * @var int
   */
  public static $risorseIniziali = 400;

  /**
   *
   * @var float
   */
  public static $incrementoDiLivelloPerEdificio = 1.4;

  /**
   *
   * @var float
   */
  public static $incrementoDiSpazioPerEdificio = 1.4;

  /**
   *
   * @var float
   */
  public static $incrementoDiRisorsaPerEdificio = 1.5;

  /**
   *
   * @var float
   */
  public static $incrementoDiCapienzaPerMagazzino = 1.3;

  /**
   *
   * @var float
   */
  public static $incrementoDiCapienzaPerGranaio = 1.3;

  /**
   *
   * @param int $livelloEdificio
   * @return int 
   */
  public static function moltiplicatoreRisorseEdificio($livelloEdificio = 1) {

    $moltiplicatore = 1;

    if ($livelloEdificio == 1)
      return $moltiplicatore;

    for (; $livelloEdificio > 1; $livelloEdificio--)
      $moltiplicatore*=Config::$incrementoDiLivelloPerEdificio;

    return $moltiplicatore;
  }

  public static function moltiplicatoreCapienzaEdificio($livelloEdificio = 0) {

    $moltiplicatore = 200;

    if ($livelloEdificio == 0)
      return $moltiplicatore;

    for (; $livelloEdificio > 1; $livelloEdificio--)
      $moltiplicatore*=Config::$incrementoDiSpazioPerEdificio;

    return (int) $moltiplicatore;
  }

  /**
   * Questo metodo dice quante risorse orarie si tanno producento in un campo.
   *
   * @param int $livelloEdificio
   * @return int 
   */
  public static function risorseAllOra($livelloEdificio = 1) {

    $moltiplicatore = 10; /* La base è 5 unità ogni ora. */

    if ($livelloEdificio == 1)
      return $moltiplicatore;

    for (; $livelloEdificio > 1; $livelloEdificio--)
      $moltiplicatore*=Config::$incrementoDiRisorsaPerEdificio;

    return (int) $moltiplicatore;
  }

  /**
   * @todo non credo sia responsabilità di Config questa informazione, ma dell'utente
   *
   * @param int $id
   * @return array
   */
  public static function getRisorseUtente($id = null) {

    $utenti = new MUtenti;
    $idutente = $id ? $id : UtenteWeb::status()->user->id;

    foreach ($utenti->find(Config::risorse(), array('id' => $idutente)) as $item)
      return array(
          'ferro' => $item['ferro'],
          'grano' => $item['grano'],
          'legno' => $item['legno'],
          'roccia' => $item['roccia']
      );
  }

}