<?php


/**
 * Questo è il controller Site. Qui sono raccolte tutte le azioni che
 * riguardano il sito web. In questo caso Yago.
 * 
 * @todo ottimizzare il codice per la ricerca dei permessi
 * @todo bloccare il reset quando il gioco è in produzione
 * @todo actionPermissions e actionLogin condividono lo stesso codice: ottimizzare
 * @todo tenere traccia di entrate ed uscite dei giocatori
 */
class Site extends Controller {

  /**
   * Questo metodo, mostra tutte le task e restituisce un json che per 
   * ognuna fa sapere se sono in possesso o meno di un utente. L'utente
   * viene passato da querystring.
   * 
   * @sample index.php?3=site/permissions
   * 
   * @param Controller $obj 
   */
  public function actionPermissions ( Controller $obj ) {

    $json = array ( );

    $task = new MTask;
    $permessi = new MPermessi;
    $ruoli = new MRuoli;
    $gruppi = new MGruppi;

    $arrayTasks = array ( );

    foreach ( $task->findAll () as $nometask )
      $arrayTasks[$nometask['nome']] = false;

    foreach ( $gruppi->find ( array ( 'idruolo' ), array ( 'idutente' => $obj->contest ) )as $itemruolo )
      foreach ( $permessi->find ( array ( 'idtask' ), array ( 'idruolo' => $itemruolo['idruolo'] ) )as $itemtask )
        foreach ( $task->find ( array ( 'nome' ), array ( 'id' => $itemtask['idtask'] ) )as $nometask )
          $arrayTasks[$nometask['nome']] = true;

    die ( json_encode ( $arrayTasks ) );

  }

  /**
   * Questa action contente di gestire gli utenti. Allo stato iniziale,
   * vengono caricati tutti gli utenti e le task, ma senza connessione logica.
   * 
   * Sulla sinistra appariranno i nomi degli utenti. Ciascun nome sarà
   * cliccabile. Cliccando su di un nome si caricheranno i suoi permessi.
   * 
   * @param Controller $obj 
   */
  public function actionManageusers ( Controller $obj ) {

    $obj->layout = 'admin';

    /**
     * Carico tutti gli utenti
     */
    $utenti = new MUtenti;
    $obj->modelutenti = $utenti->findAll ();

    /**
     * Carico tutti i permessi
     */
    $tasks = new MTask;
    $obj->tasklist = $tasks->findAll ();

  }

  /**
   * This function grow resources to 1000. It's just for test version of yago :-p
   *
   * @param Controller $obj 
   */
  public function actionIncreaseresources ( Controller $obj ) {

    $utenti = new MUtenti;

    $utenti->update ( array (
        'ferro' => 1000,
        'grano' => 1000,
        'legno' => 1000,
        'roccia' => 1000,
            ), array (
        'id' => UtenteWeb::status ()->user->id
    ) );

    $this->redirect ( 'site/vista' );

  }

  /**
   * Questo metodo azzera TUTTI i parametri del gioco. Sarà come se non si 
   * fosse mai iniziato.
   * 
   * @todo valutare se ha senso fare un foreach nella creazione dei model
   * @todo valutare se ha senso fare un foreach nell'elenco delle truncate
   * 
   * @param Controller $obj 
   */
  public function actionReset ( Controller $obj ) {

    $cells = new MCells;
    $costruzioni = new MCostruzioni;
    $codadicostruzione = new MCodadicostruzione;
    $codadiaddestramento = new MCodadiaddestramento;
    $proprieta = new MProprieta;
    $utenti = new MUtenti;
    $donelist = new MDonelist;
    $posizioneiniziale = new NuovaPosizione;
    $esercito = new MEsercito;

    $cells->truncate ();
    $costruzioni->truncate ();
    $codadicostruzione->truncate ();
    $codadiaddestramento->truncate ();
    $proprieta->truncate ();
    $esercito->truncate ();

    $utenti->update ( array (
        'ferro' => Config::$risorseIniziali,
        'grano' => Config::$risorseIniziali,
        'legno' => Config::$risorseIniziali,
        'roccia' => Config::$risorseIniziali,
    ) );

    /**
     * Azzero la pozizione degli utenti
     */
    $nuovaposizione = new NuovaPosizione;
    foreach ( $utenti->findAll () as $item ) {
      $utenti->update ( $nuovaposizione->getActivationCoords ( $item['id'] ), array ( 'id' => $item['id'] ) );
      $utenti->update ( array ( 'x' => 0 ), array ( 'id' => $item['id'] ) );
      $utenti->update ( array ( 'y' => 0 ), array ( 'id' => $item['id'] ) );
    }

    /**
     * Azzero le cose fatte
     */
    $donelist->update ( array (
        '1' => 0, // Fondare un villaggio
        '2' => 0, // Costruire un campo di grano
        '3' => 0, // Costruire un campo di legno
        '4' => 0  // Costruire un campo di ferro
    ) );

    /**
     * Torno alla vista
     */
    $this->redirect ( 'site/logout' );

  }

  /**
   *
   * @param Controller $obj 
   */
  public function actionVista ( Controller $obj ) {

    if ( ! UtenteWeb::status ()->isAutenticato () )
      $this->redirect ( 'site/login' );

    $nuova = new NuovaPosizione;
    $utenti = new MUtenti;

    $utenti->update ( $nuova->getActivationCoords ( UtenteWeb::status ()->user->id ) );

    $obj->pageTitle = 'Vista di Yago';

  }

  /**
   * Mostro la pagina per recuperare la password.
   *
   * @param Controller $obj 
   */
  public function actionLostpassword ( Controller $obj ) {

    $obj->pageTitle = 'Hai dimenticato la password';

  }

  /**
   * Vengono azzerati tutte le variabili di sessione. Una volta eseguito il
   * metodo, sarà come se l'utente non fosse mai entrato.
   *
   * @param Controller $obj 
   */
  public function actionLogout ( Controller $obj ) {
    UtenteWeb::status ()->logout ();
    unset ( $_SESSION );
    $this->redirect ( 'site/login' );

  }

  /**
   * Questa azione mostra la pagina di login.
   *
   * @param Controller $obj 
   */
  public function actionLogin ( Controller $obj ) {

    $gruppi = new MGruppi();
    $permessi = new MPermessi();
    $task = new MTask();

    $obj->pageTitle = 'Accedi al mondo di Yago';

    if ( $this->ciSonoVariaibliPost () ) {

      $utenti = new MUtenti;
      foreach ( $utenti->find ( array ( ), array ( 'username' => $_POST['username'], 'password' => md5 ( $_POST['password'] ) ) ) as $item ) {

        $idutente = $item['id'];
        $xutente = $item['x'];
        $yutente = $item['y'];
        $nuovaposizione = new NuovaPosizione;
        $arrayTasks = array ( );

        foreach ( $gruppi->find ( array ( 'idruolo' ), array ( 'idutente' => $idutente ) )as $itemruolo )
          foreach ( $permessi->find ( array ( 'idtask' ), array ( 'idruolo' => $itemruolo['idruolo'] ) )as $itemtask )
            foreach ( $task->find ( array ( 'nome' ), array ( 'id' => $itemtask['idtask'] ) )as $nometask )
              $arrayTasks[$nometask['nome']] = true;

        UtenteWeb::status ()->autentica ( array_merge ( array (
                    'username' => $_POST['username'],
                    'id' => $idutente,
                    'x' => $xutente,
                    'y' => $yutente,
                        ), $arrayTasks, $nuovaposizione->getActivationCoords ( $idutente ) ) );

        $this->redirect ( 'site/vista' );
      }
      $this->redirect ( 'site/login' );
    }

  }

  /**
   * Questa è l'azione di default. Il sito normalmente carica questa pagina
   * se non c'è nessuna diversa segnalazione nella querystring.
   * 
   * @param Controller $obj 
   */
  public function actionIndex ( Controller $obj ) {

    $obj->pageTitle = 'Yago ' . ($obj->getVersion ());

  }

}