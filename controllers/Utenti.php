<?php

class Utenti extends Controller {

  public function actionSalvaposizione() {

    $utenti = new MUtenti;

    if (!UtenteWeb::status()->isAutenticato())
      $this->redirect('site/login');

    if ($this->ciSonoVariaibliPost()) {
      $utenti->update(array(
          'x' => $_POST['x'],
          'y' => $_POST['y'],
              ), array(
          'id' => UtenteWeb::status()->user->id
      ));
      UtenteWeb::setPosition(array('x' => $_POST['x'], 'y' => $_POST['y']));
    }

    die();
  }

  /**
   *
   * @param Controller $obj
   * Questa classe riceve la password 
   */
  public function actionCambiapassword( Controller $obj) {

    if (!UtenteWeb::status()->isAutenticato())
      $this->redirect('site/login');

    $obj->layout = 'stats';
    
    if ($this->ciSonoVariaibliPost()) {
      $vecchiaPassword = @$_POST['vecchiapassword'];

      $u = new MUtenti;
      foreach ($u->find(array(), array(
          'username' => UtenteWeb::status()->user->username,
          'password' => md5($vecchiaPassword)
      )) as $item) {
        $nuovaPassword = @$_POST['nuovapassword'];
        $confermaPassword = @$_POST['confermapassword'];
        if ($nuovaPassword == $confermaPassword) {
          UtenteWeb::status()->nuovaPassword($nuovaPassword);
        }
        $this->redirect('error/nuovapasswordnonvalida');
      };
      $this->redirect('error/passwordnonvalida');
    }
  }

}