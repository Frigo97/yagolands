<?php


/**
 * Quando un giocatore entra per la prima volta dentro a yago si devono creare
 * i terreni in una posizione nuova. Al centro si trova la posizione 1. La
 * posizione successiva è nel "girone" superiore quindi ci si posiziona (si va
 * in alto a sinistra) e si cerca la posizione numero 2. La 3, la 4 la 5, la 6
 * e la7 si trvano nelle altre 5 celle. Quindi ci riposiziona nuovamente e si 
 * procede in maniera analoga nell'alveare.
 * 
 * Per fare in modo che l'allocazione di nuovi terreni non sia troppo ammassata,
 * i "passi" sono di un certo numero di caselle difinito in
 * Config::$passoPerAllocazioneNuoviTerreni.
 * 
 */
class NuovaPosizione extends Yagolands {

    private $coordinata;

    /**
     * Nel costruttore di questa classe, le coordinate di default sono il centro
     * della mappa.
     * 
     * @todo definire la posizione e non forzarla a 0,0
     */
    public function __construct () {
        $this->coordinata = new Coordinata ( array ( 'x' => 0, 'y' => 0 ) );

    }

    public function getActivationCoords ( $numeroAttivazione ) {

        if ( $numeroAttivazione == 1 )
            return $this->coordinata->getPosition ();
        
        $passo = Config::$passoPerAllocazioneNuoviTerreni;

        $numeroPosizionamento = 1;

        for ( $i = 1;; $i ++  ) {
            //if ( $i == 1 ) {
            /* posizionamento */
            for ( $j = $passo; $j > 0; $j --, $this->coordinata->muoviInAltoASinistra () )
                ;
            /* 6 cicli */
            //}

            for ( $girone = $i; $girone > 0; $girone --  ) {
                for ( $j = $passo; $j > 0; $j --, $this->coordinata->muoviADestra () )
                    ;
                ++ $numeroPosizionamento;
                if ( $numeroPosizionamento == $numeroAttivazione )
                    return $this->coordinata->getPosition ();
            }

            for ( $girone = $i; $girone > 0; $girone --  ) {
                for ( $j = $passo; $j > 0; $j --, $this->coordinata->muoviInBassoADestra () )
                    ;
                ++ $numeroPosizionamento;
                if ( $numeroPosizionamento == $numeroAttivazione )
                    return $this->coordinata->getPosition ();
            }

            for ( $girone = $i; $girone > 0; $girone --  ) {

                for ( $j = $passo; $j > 0; $j --, $this->coordinata->muoviInBassoASinistra () )
                    ;
                ++ $numeroPosizionamento;
                if ( $numeroPosizionamento == $numeroAttivazione )
                    return $this->coordinata->getPosition ();
            }

            for ( $girone = $i; $girone > 0; $girone --  ) {
                for ( $j = $passo; $j > 0; $j --, $this->coordinata->muoviASinistra () )
                    ;
                ++ $numeroPosizionamento;
                if ( $numeroPosizionamento == $numeroAttivazione )
                    return $this->coordinata->getPosition ();
            }

            for ( $girone = $i; $girone > 0; $girone --  ) {
                for ( $j = $passo; $j > 0; $j --, $this->coordinata->muoviInAltoASinistra () )
                    ;
                ++ $numeroPosizionamento;
                if ( $numeroPosizionamento == $numeroAttivazione )
                    return $this->coordinata->getPosition ();
            }

            for ( $girone = $i; $girone > 0; $girone --  ) {
                for ( $j = $passo; $j > 0; $j --, $this->coordinata->muoviInAltoADestra () )
                    ;
                ++ $numeroPosizionamento;
                if ( $numeroPosizionamento == $numeroAttivazione )
                    return $this->coordinata->getPosition ();
            }
        }

    }

}