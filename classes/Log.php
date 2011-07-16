<?php

/**
 * Questa è la classe del log
 * 
 * <code>
 * <?php echo Log::save(array('string' => 'Messaggio di log.')); ?>
 * </code>
 */
class Log extends Yagolands {
  
  public static $ERROR_LEVEL = 'errore';
  public static $INFO_LEVEL = 'info';

  public static function save($params = array()) {

    /**
     * Mi assicuro che ci siano dei valori di default per le variabili
     */
    $livello = isset($params['livello']) ? $params['livello'] : 'livello';
    $categoria = isset($params['categoria']) ? $params['categoria'] : 'categoria';
    $string = isset($params['string']) ? $params['string'] : 'string';

    /**
     * This code fix this Warning: Warning: date() [function.date]: It is 
     * not safe to rely on the system's timezone settings. You are 
     * *required* to use the date.timezone setting or the 
     * date_default_timezone_set() function. In case you used any of those 
     * methods and you are still getting this warning, you most likely 
     * misspelled the timezone identifier. We selected 'Europe/Berlin' for 
     * 'CEST/2.0/DST' instead in 
     * /Library/WebServer/Documents/yago2/classes/Log.php on line 21
     */
    $date_default_timezone_set = date_default_timezone_set('Europe/Rome');

    /**
     * Appendo il messaggio ad una stringa che contiene la data
     */
    $string = '[' . date('H:i:s') . '] ' . $string . "\r\n";

    /**
     * Controllo l'esistenza delle cartelle anno e mese
     */
    if (!file_exists(__DIR__ . '/../log/' . (date("Y"))))
      mkdir(__DIR__ . '/../log/' . (date("Y")));
    if (!file_exists(__DIR__ . '/../log/' . (date("Y")) . '/' . (date("m"))))
      mkdir(__DIR__ . '/../log/' . (date("Y")) . '/' . (date("m")));
    if (!file_exists(__DIR__ . '/../log/' . (date("Y")) . '/' . (date("m")) . '/' . (date("d"))))
      mkdir(__DIR__ . '/../log/' . (date("Y")) . '/' . (date("m")) . '/' . (date("d")));

    /**
     * Salvo il messaggio di log su disco
     */
    $pathcompleto = __DIR__ . '/../log/' . (date("Y")) . '/' . (date("m")) . '/' . (date("d"));
    $nomedelfile = $livello . '-' . $categoria . '.log';

    $handle = fopen($pathcompleto . '/' . $nomedelfile, 'a+');
    fwrite($handle, $string);
    fclose($handle);
  }

}