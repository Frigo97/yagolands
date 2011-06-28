<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="css/standard.css" />
    <link rel="stylesheet" type="text/css" href="css/yago.css" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta http-equiv="content-script-type" content="text/javascript" />
    <title><?php echo $this->pageTitle; ?></title>
    <script src="javascript/jquery.js"></script>
    <script src="javascript/admin.js"></script>
  </head>
  <body>
    <div id="side-left" class="testoverde arial size12px">
      <?php foreach ( $this->modelutenti as $item ): ?>
        <div class="username">
          <input type="hidden" class="index" value="<?php echo $item['id']; ?>" />
          <span class="nickname"><?php echo $item['username']; ?></span>
        </div>
      <?php endforeach; ?>
    </div>
    <div id="porta-header">
      <div id="fascia-header">
        <img src="images/loghi/yagolands.png" />
      </div>
    </div>
    <div class="titolo-pagina arial size14px">
      Gestione degli utenti.
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
        Amministrazione
      <?php endif; ?>
    </div>
    <div id="contenitore">
      <?php echo $content; ?>
    </div>
  </body>
</html>