<?php

/**
 * Questo qui e' il model delle celle. Eredita tutto dal model principale. Ad
 * esso, sono state aggiunti un paio di metodi: {@link createIfNotExists} che
 * crea una cella se non ne esiste già una sulla stessa posizione, oppure
 * {@link cellExists} che veririfica l'esistenza di una cella.
 */
class MCells extends Model {

    /**
     * Nel costruttore di questo model viene definito il nome della tabella
     * con cui dovremo avere a che fare.
     */
    public function __construct () {
        parent::__construct ( 'cells' );

    }

    /**
     * Questo medoto riceve in ingresso una coordinata ed alcune informazioni
     * che riguardano un utente. In particolare, abbiamo il suo id ed anche il 
     * suo username. In particolare viene controllato se la cella in questuone
     * esiste o meno. Se non esiste, viene creata.
     * 
     * @todo ci sono troppi parametri
     *
     * @param Coordinata $coordinata
     * @param int $idutente
     * @param string $username 
     */
    public function createIfNotExists ( Coordinata $coordinata, $idutente, $username ) {
        if ( ! $this->cellExist ( $coordinata->getPosition () ) ) {
            $this->create ( array_merge (
                            $coordinata->getPosition (), array (
                        'idutente' => $idutente,
                        'username' => $username
                    ) ) );
        }

    }

    /**
     * Questo metodo fa semplicemente una count nella posizione che gli viene 
     * passata tramite un array che ci da le coordinate di questa cella.
     * 
     * @todo fare in modo che questo metodo riceva un oggetto Coordinata
     *
     * @param array $params
     * @return bool 
     */
    public function cellExist ( $params = array ( ) ) {

        return $this->countwhere ( $params );

    }

}