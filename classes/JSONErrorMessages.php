<?php

/**
 * Questa è una classe che si occupa di raccogliere le varie risposte json delle
 * pagine.
 */
class JSONErrorMessages {

  public static function message ($success = true, $message = '') {
    die ( json_encode ( array (
                'success' => $success,
                'message' => $message
            ) ) );

  }

}