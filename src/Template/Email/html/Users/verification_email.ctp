<p><?= __d('user', 'Hello {0}!', h($user->name)) ?></p>
<p><?= __d('user', 'Your password is: {0}', $user->clear_password) ?></p>
<p>Te recomendamos cambiar tu contraseña una vez que hayas iniciado sesión</p>
<p><?= __d('user', 'Please click this link to activate your account.') ?></p>
<?= \Cake\Routing\Router::url(['plugin' => 'Users','controller' => 'Gateway', 'action' => 'verify_email', '?' => ['token' => $user->email_token]], true) ?>
