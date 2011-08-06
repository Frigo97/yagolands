<?php

/**
 * Questa è la classe di bootstrap. In questo luogo viene caricato il database,
 * vengono gestite le azioni di post, caricato il backgrouind e così via.
 * 
 * @todo definire uno standard per caricare
 * @todo mettere il load del database qui dentro
 */
class Yago2 extends Controller {

  public static function getVersion() {
    return "2.0";
  }

  public $layout = 'main';
  public $controller = 'site';
  public $action = 'vista';
  public $contest = 'yago';

  public function __construct() {

    @session_start();

    /**
     * conto il numero di parametri passati
     */
    $count = 0;
    foreach ($_GET as $this->contest => $key) {
      $count++;
    };

    /**
     * Se è solo uno carico da quello il controller
     * e l'action
     */
    if ($count == 1) {
      list(
              $this->controller,
              $this->action) = explode("/", $key);
    }

    $classname = $this->controller;
    if (!class_exists($classname)) {
      $this->controller = 'error';
      $this->action = 'nocontroller';
    }


    /**
     * Il controller caricherà i model giusti.
     * 
     */
    $controller = ucfirst($this->controller);
    $action = 'action' . (ucfirst($this->action));
    $obj = new $controller();

    /**
     * Verifico che l'action esista davvero.
     */
    if (method_exists($obj, $action)) {
      $obj->$action(&$this);
    } else {
      $this->controller = 'error';
      $this->action = 'noaction';
    }
  }

  protected function render() {


    $filename = 'views/contents/' . ($this->controller) . '/' . ($this->action) . '.php';
    if (file_exists($filename)) {
      ob_start();
      include $filename;
      $content = ob_get_clean();
    } else {
      Log::save(array(
          'string' => 'Si sta cercando di caricare una view non disponibile: ' . ($filename),
          'livello' => Log::$ERROR_LEVEL
      ));
    }

    $filename = 'views/layouts/' . ($this->layout) . '.php';
    if (file_exists($filename)) {
      ob_start();
      include $filename;
      $layout = ob_get_clean();
    } else {
      Log::save(array(
          'string' => 'Si sta cercando di caricare un layout non disponibile: ' . ($filename),
          'livello' => Log::$ERROR_LEVEL
      ));
    }

    die($layout);
  }

  public static function run() {

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

    /* Mmmm usare un cronjob per questo è sbagliato: le risorse possono
     * cambiare da un secondo all'altro. */
    $pdo = new Model;
    $utenti = new MUtenti;
    $costruzioni = new MCostruzioni;
    $edifici = new MEdifici;
    $esercito = new MEsercito;
    foreach ($utenti->findAll() as $itemUtenti) {
      foreach (array('magazzino', 'granaio') as $itemContenitore) {
        $idCostruzione = $costruzioni->getIdCostruzione($edifici->getId($itemContenitore), $itemUtenti['id']);
        $livello = $costruzioni->getLivelloProprietario($idCostruzione, $itemUtenti['id']);
        $capienzaMassima = Config::moltiplicatoreCapienzaEdificio($livello);
        $risorseUtente = Config::getRisorseUtente($itemUtenti['id']);
        $arrayRisorse = $itemContenitore == 'magazzino' ? array('ferro', 'legno', 'roccia') : array('grano');
        foreach ($arrayRisorse as $itemRisorse) {
          $nomeRisorsa = $itemRisorse;
          if ($risorseUtente[$nomeRisorsa] > $capienzaMassima) {
            $risorseUtente[$nomeRisorsa] = $capienzaMassima;
          }
          $utenti->update($risorseUtente, array('id' => $itemUtenti['id']));
        }
      }
    }

    $obj = new Yago2();
    $obj->render();
  }

  /**
   * Verificare se un utente ha o meno un determinato permesso. Quando un utente
   * fa login, tutte le singole task che egli può compiere vengono memorizzate nella
   * sessione.
   * 
   * Quindi, per verificare se ha o meno un permesso, controllo se esiste l'attributo
   * corrispondente alla task indicata.
   * 
   * @param string $permission 
   * @return boolean
   */
  public function can($permission = '') {

    return UtenteWeb::status()->user->$permission || 0;
  }

}