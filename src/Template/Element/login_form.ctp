<?php
echo $this->Form->create($userEntity);
echo $this->Form->input('email', array(
	'label' => __d('users', 'Email'),
	'required' => false,
));
echo $this->Form->input('password', array(
	'type' => 'password',
	'label' => __d('users', 'Password'),
	'required' => false,
));
?>
<p>
	<?php
		echo $this->Html->link(__d('users', 'Register'), ['action' => 'register']);
		echo ' | ';
		echo $this->Html->link(__d('users', 'Reset Password'), ['action' => 'request_password']);
	?>
</p>
<?php
echo $this->Form->submit(__d('users', 'Login'));
echo $this->Form->end();
