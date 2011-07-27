<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="css/standard.css" />
    <link rel="stylesheet" type="text/css" href="css/yago.css" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta http-equiv="content-script-type" content="text/javascript" />
    <title><?php echo $this->pageTitle; ?></title>
    <script src="javascript/jquery.js"></script>
    <script src="javascript/attacco.js"></script>
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
      <?php if (UtenteWeb::status()->isAutenticato()): ?>
        <strong><?php echo UtenteWeb::status()->user->username; ?></strong> &raquo;
        <?php $this->echolink(array('page' => 'site/manageusers', 'label' => 'Gestione Utenti', 'visible' => $this->can('manageusers'), 'append' => '  &raquo;')); ?>
        <?php $this->echolink(array('page' => 'stats/stats', 'label' => 'Classifica', 'visible' => $this->can('viewstat'), 'append' => '  &raquo;')); ?>
        <?php $this->echolink(array('page' => 'site/vista', 'label' => 'Mappa')); ?>  &raquo;
        <?php $this->echolink(array('page' => 'site/logout', 'label' => 'Logout')); ?>  &raquo;
        <?php $this->echolink(array('label' => 'Cambia password', 'page' => 'utenti/cambiapassword')); ?>
      <?php else: ?>
        Entra nel mondo di yago!
      <?php endif; ?>
    </div>
    <div id="contenitore">
      <?php echo $content; ?>
      <div id="side-left" class="testoverde arial size12px">
        colonna di sinistra
      </div>
      <div id="side-right" class="testoverde arial size12px">
        colonna di destra
      </div>
    </div>
  </body>
</html>