<?php

/**
 * Questa classe viene utilizzata quando si deve comunicare qualche cosa di
 * particolare ad un utente. E' la pagina di cortesia. Contiene tutti gli
 * avvisi che si devono fare ad un utente.
 * 
 * @version 1.0
 */
class Avviso extends Yagolands {

    /**
     * Avviso::actionPasswordmodificata(); Viene richiamata quando un utente ha
     * modificato la propria password.
     * 
     * @param Controller $obj 
     */
    public function actionPasswordmodificata(Controller $obj) {
        $obj->layout = 'avviso';
    }

}