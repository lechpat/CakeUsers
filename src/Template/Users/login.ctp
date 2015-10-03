<?= $this->Form->create($userEntity)?>
    <?= $this->Form->input('email', [
        'label' => __d('users', 'Email'),
        'required' => false,
    ]) ?>
    <?= $this->Form->input('password', [
        'type' => 'password',
        'label' => __d('users', 'Password'),
        'required' => false,
    ]) ?>
    <p>
        <?php
            echo $this->Html->link(__d('users', 'Reset Password'), ['action' => 'request_password']);
        ?>
    </p>
    <?= $this->Form->submit(__d('users', 'Login')) ?>
<?= $this->Form->end() ?>
