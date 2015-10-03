<h2>
	<?php echo __d('users', 'Change password'); ?>
</h2>
<?php
echo $this->Form->create($entity);
echo $this->Form->input('old_password', [
	'type' => 'password',
	'label' => __d('users', 'Old password')
]);
?>
<hr />
<?php
echo $this->Form->input('password', [
	'type' => 'password',
	'label' => __d('users', 'New password')
]);
echo $this->Form->input('confirm_password', [
	'type' => 'password',
	'label' => __d('users', 'Confirm password')
]);
echo $this->Form->submit(__d('users', 'Submit'));
echo $this->Form->end();
