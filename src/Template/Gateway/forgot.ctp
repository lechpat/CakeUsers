<?= $this->Form->create($userEntity);?>
    <?= $this->Form->input('email', array(
        'label' => __d('user', 'Email'),
        'required' => true,
    ))?>
    <?= $this->Form->submit(__d('users', 'Send Instructions'));?>
<?= $this->Form->end();?>
