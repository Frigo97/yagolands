<?php if (UtenteWeb::status()->user->username): ?>
  Sei già autenticato
  <?php
  echo $this->link(array(
      'label' => 'Logout',
      'page' => 'site/logout'
  ));
  ?>
<?php else: ?>

  <div id="contenitore">
    <div id="side-left">
      <h1>Login</h1>
      <form name="crea_utenti" action="index.php?load=site/login" method="post">
        <label class="form-login" for="username">Username:</label> <br />
        <input type="text" name="username" id="username" /><br />
        <label class="form-login" for="password">Password:</label> <br />
        <input type="password" name="password" id="password" /><br />
        <button>crea</button>
      </form>
    </div>
    <div id="side-right">
      <h1>Attenzione!</h1>
      yagolands è ancora in beta test. Per provarlo puoi trovare le istruzioni <a href="https://github.com/sensorario/yagolands/wiki/Try-yago" target="_blank">qui</a>. Se preferisci puoi provare l'accounte di test <strong>semola</strong> ed inseire come password <strong>password</strong>.
    </div>
  </div>

<?php endif; ?>