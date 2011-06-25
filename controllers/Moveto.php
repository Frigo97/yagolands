<?php


class Moveto extends Controller {

    public function actionBuilding () {
        $c = new MCostruzioni();
        $utenti = new MUtenti;
        foreach ( $c->find ( array ( ), array ( 'idedificio' => $_POST['idedificio'], 'idutente' => UtenteWeb::status ()->user->id ) ) as $item ) {
            //Log::save ( array ( 'string' => 'Moveto::actionBuilding();' ) );
            $utenti->update ( array ( 'x' => $item['x'], 'y' => $item['y'] ), array ( 'id' => UtenteWeb::status ()->user->id ) );
            $_SESSION['user']['x'] = $item['x'];
            $_SESSION['user']['y'] = $item['y'];
        };
        die ();

    }

}