<?php


/**
 * Questo è il controller base. Questa classe si occupa della scrittura dei
 * links, verifica se ci sono o meno variabili post oppure esegue il redirect
 * in formato html.
 * 
 * @author Simone (demo) Gentili - sensorario@gmail.com
 */
class Controller extends Yagolands {

    /**
     * Questo metodo redirige l'utente da qualche parte. Accett in ingresso
     * il nome della pagina.
     *
     * @param string $page 
     */
    public static function redirect ( $page = 'logout' ) {
        die ( '<meta http-equiv="refresh" content="0;URL=index.php?page=' . ($page) . '" />' );

    }

    /**
     * Questo metodo ci dice se ci sono o meno delle variabili _POST in pagina.
     *
     * @return boolean 
     */
    public function ciSonoVariaibliPost () {
        return count ( $_POST ) || false;

    }

    /**
     * Questo metodo viene utilizzato da echolink l'intero array dei parametri.
     * Questo array, potrebbe contenere l'attributo visible che ci dice quale 
     * è la vislità del link. Questo metodo è necessario perchè non è detto che
     * il parametro venga impostato dall'esterno. Se non impostato, comunque, si
     * considera true. In caso contrario si controlla che valore ha.
     *
     * @param array $params
     * @return boolean 
     */
    private function isVisible ( $params = array ( ) ) {

        return isset ( $params['visible'] ) ?
                $params['visible'] :
                true;

    }

    /**
     * Questo metodo non restituisce nessun valore, ma stampa il link direttamente. Utilizza il metodo private {@link isVisible} ed accetta come parametro un array:
     * <code>
     *       <?php
     *           $this->echolink(array(
     *                'page' => 'page', // contesto
     *                'label' => 'Nome del Link', // label
     *                'visible' => true, // visibilità
     *           ));
     *       ?>
     * </code>
     * Il parametro visible è un booleano. Viene utilizzato in combinazione
     * con i metodi che consentono di verificare se l'utente è autenticato o
     * meno.
     * <code>
     *       <?php
     *           $this->echolink ( array (
     *               'page' => 'site/manageusers',
     *               'label' => 'Gestione Utenti',
     *               'visible' => $this->can ( 'manageusers' ),
     *               'append' => '  &raquo;'
     *           ) );
     *       ?>
     * </code> 
     *
     * @param string $params 
     */
    public function echolink ( $params = array ( ) ) {

        if ( $this->isVisible ( $params ) )
            echo $this->link ( $params ) . ($params['append']);

    }

    /**
     * Questo metodo restituisce un link html:
     * <code>
     * return '<a href="index.php?page=' . ($params['page']) . '">' . ($params['label']) . '</a>';
     * </code>
     *
     * @param array $params
     * @return string 
     */
    public function link ( $params = array ( ) ) {
        return '<a href="index.php?page=' . ($params['page']) . '">' . ($params['label']) . '</a>';

    }

}

