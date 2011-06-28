<h1>Login</h1>
<?php if ( UtenteWeb::status ()->user->username ): ?>
  Sei già autenticato
  <?php
  echo $this->link ( array (
      'label' => 'Logout',
      'page' => 'site/logout'
  ) );
  ?>
<?php else: ?>
  <form name="crea_utenti" action="index.php?load=site/login" method="post">
    <label class="form-login" for="username">Username:</label> <br />
    <input type="text" name="username" id="username" /><br />
    <label class="form-login" for="password">Password:</label> <br />
    <input type="password" name="password" id="password" /><br />
    <button>crea</button>
  </form>
  <?php
  echo $this->link ( array (
      'label' => 'Hai dimenticato la password?',
      'page' => 'site/lostpassword'
  ) );
  ?>
<?php endif; ?>