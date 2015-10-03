<h2>
    <?php echo __d('user', 'Change password'); ?>
</h2>
<?php
echo $this->Form->create($entity);
echo $this->Form->input('old_password', [
    'type' => 'password',
    'label' => __d('user', 'Old password')
]);
?>
<hr />
<?php
echo $this->Form->input('password', [
    'type' => 'password',
    'label' => __d('user', 'New password')
]);
echo $this->Form->input('confirm_password', [
    'type' => 'password',
    'label' => __d('user', 'Confirm password')
]);
echo $this->Form->submit(__d('user', 'Submit'));
echo $this->Form->end();
