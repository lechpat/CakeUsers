<?= __d('user', 'Hello {0}!', h($user->name)) ?>

<?= __d('user', 'Please click this link to reset your password.') ?>

<?= \Cake\Routing\Router::url(['controller' => 'Gateway', 'action' => 'reset_password', '?' => ['token' => $user->password_token]], true) ?>
