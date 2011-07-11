<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="css/standard.css" />
    <link rel="stylesheet" type="text/css" href="css/yago.css" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta http-equiv="content-script-type" content="text/javascript" />
    <title><?php echo $this->pageTitle; ?></title>
    <?php if ( $this->controller == 'site' ): ?>
      <?php if ( $this->action != 'login' ): ?>
        <script src="javascript/jquery.js"></script>
        <script src="javascript/yago.js"></script>
      <?php endif; ?>
    <?php endif; ?>

  </head>
  <body>
    <div id="porta-header">
      <div id="fascia-header">
        <img src="images/loghi/yagolands.png" />
      </div>
    </div>
    <div class="titolo-pagina arial size14px">
      Le terre di yago
    </div>
    <div class="menu-pagina arial size14px">
      <?php if ( UtenteWeb::status ()->isAutenticato () ): ?>
        <strong><?php echo UtenteWeb::status ()->user->username; ?></strong> &raquo;
        <?php $this->echolink ( array ( 'page' => 'site/manageusers', 'label' => 'Gestione Utenti', 'visible' => $this->can ( 'manageusers' ), 'append' => '  &raquo;' ) ); ?>
        <?php $this->echolink ( array ( 'page' => 'stats/stats', 'label' => 'Classifica', 'visible' => $this->can ( 'viewstat' ), 'append' => '  &raquo;' ) ); ?>
        <?php $this->echolink ( array ( 'page' => 'site/vista', 'label' => 'Mappa' ) ); ?>  &raquo;
        <?php $this->echolink ( array ( 'page' => 'site/logout', 'label' => 'Logout' ) ); ?>  &raquo;
        <?php $this->echolink ( array ( 'label' => 'Cambia password', 'page' => 'utenti/cambiapassword' ) ); ?>
      <?php else: ?>
        Entra nel mondo di yago!
      <?php endif; ?>
    </div>
    <div id="contenitore">
      <?php echo $content; ?>
      <div id="side-left" class="testoverde arial size12px">
        <?php if ( UtenteWeb::status ()->isAutenticato () ): ?>
          <h2>La tua posizione</h2>
          <div id="posizione"></div>
          <div id="costruzionipresenti"></div>
          <h2>Le tue risorse</h2>
          <div class="ferro">ferro: <span id="ferro"></span></div>
          <div class="grano">grano: <span id="grano"></span></div>
          <div class="legno">legno: <span id="legno"></span></div>
          <div class="roccia">roccia: <span id="roccia"></span></div>
          <h2>Le tue truppe</h2>
          <div id="mytroops"></div>
          <h2>I tuoi Edifici</h2>
          <div id="mybuildings"></div>
          <h2>La tua produzione</h2>
          <div id="myfields"></div>
        <?php endif; ?>
      </div>
      <div id="side-right" class="testoverde arial size12px">
        <?php if ( UtenteWeb::status ()->isAutenticato () ): ?>
          <?php if ( @UtenteWeb::status ()->user->createplace ): ?>
            <div id="createplace"></div>
          <?php endif; ?>
          <h2>Cosa puoi costruire</h2>
          <div id="buildable"></div>
          <h2>Cosa stai costruendo</h2>
          <div id="coda-lavori"></div>
          <h2>Cosa stai addestrando</h2>
          <div id="coda-addestramenti"></div>
        <?php endif; ?>
      </div>
    </div>
  </body>
</html>