<?php

/**
 * Questo è il controller degli errori.
 */
class Error extends Yagolands {

    /**
     * Questo Messaggio di errore segnala che nel cambio della password,
     * la nuova è stata inserita male la seconda volta.
     *
     * @param Controller $obj 
     */
    public function actionNuovapasswordnonvalida ( Controller $obj ) {
        $obj->layout = 'error';

    }

    /**
     * Questo messaggio di errore indica che è stata inserita una password
     * non valida. Sarà necessario ripetere il login.
     *
     * @param Controller $obj Questo 
     */
    public function actionPasswordnonvalida ( Controller $obj ) {
        $obj->layout = 'error';

    }

    /**
     * Questo messaggio dice che non è stato trovato nessun controller.
     *
     * @param Controller $obj 
     */
    public function actionNocontroller ( Controller $obj ) {
        $obj->layout = 'error';

    }

    /**
     * Questo messaggio ci dice che non è stata trovata l'azione che si stava cercando.
     *
     * @param Controller $obj 
     */
    public function actionNoaction ( Controller $obj ) {
        $obj->layout = 'error';

    }

}