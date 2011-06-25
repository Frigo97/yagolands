<?php


/**
 * Questa è una classe di log che consente di non dover impostare alcun tipo
 * di parametro. E' stata create per rendere il codice meno verboso.
 * 
 * <code>
 * <?php echo Stdlog::message('Messaggio di log.'); ?>
 * </code>
 */
class Stdlog extends Log {

    /**
     * Nella versione base, Log, era sempre necessario inviare un array al
     * logger. Con questo metodo, dobbiamo indicare solamente il messaggio da
     * registrare nel file di log.
     *
     * @param string $message 
     */
    public static function message ( $message = '' ) {

        Log::save ( array (
            'string' => $message,
            'livello' => 'standard',
            'categoria' => 'generale'
        ) );

    }

}