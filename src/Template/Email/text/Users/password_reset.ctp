<?= __d('users', 'Hello {0}!', h($user->username)) ?>

<?= __d('users', 'Please click this link to reset your password.') ?>

<?= \Cake\Routing\Router::url(['controller' => 'users', 'action' => 'reset_password', '?' => ['token' => $user->password_token]], true) ?>
