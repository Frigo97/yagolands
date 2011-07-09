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
    <div style="margin-left: 200px; margin-top: 10px; position: absolute;">
      <h1>Come si gioca?</h1>
      <p>Quando si entra dentro il mondo di yago, verrà generato un esagono per il tuo utente. Verrà generato un esagono anche per ciascuno dei 6 lati attorno a te.</p>
      <p>Per prima cosa dovrai costruire i campi delle risorse (ferro, grano, legno, roccia). Solo allora sarà possibile sbloccare il centro del villaggio. Ogni volta che costruirai qualche cosa, i tuoi possedimenti cresceranno sempre di più.</p>
      <p>Una volta costruito anche quello, potrai procedere con caserma, se vorrai attaccare i villaggi nemici, oppure mercato, per effettuare scambi commerciali.</p>
      <h1>Attenzione!</h1>
      <p>yagolands è ancora in beta test. Per provarlo puoi trovare le istruzioni <a href="https://github.com/sensorario/yagolands/wiki/Try-yago" target="_blank">qui</a>. Se preferisci puoi provare l'accounte di test <strong>semola</strong> ed inseire come password <strong>password</strong>.</p>
    </div>
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
  </div>

<?php endif; ?>