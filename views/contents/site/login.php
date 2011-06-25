<h1>Login</h1>

<?php if (UtenteWeb::status()->user->username): ?>
    Sei già autenticato
    <?php
    echo $this->link(array(
        'label' => 'Logout',
        'page' => 'site/logout'
    ));
    ?>
<?php else: ?>
    <form name="crea_utenti" action="index.php?load=site/login" method="post">
        Username: <br />
        <input type="text" name="username" id="username" /><br />
        Password: <br />
        <input type="password" name="password" id="password" /><br />
        <button>crea</button>
    </form>

    <?php
    echo $this->link(array(
        'label' => 'Hai dimenticato la password?',
        'page' => 'site/lostpassword'
    ));
    ?>

<?php endif; ?>

