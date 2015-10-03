<?php
echo $this->Form->create($userEntity);
echo $this->Form->input('email', array(
	'label' => __d('users', 'Email'),
	'required' => false,
));
echo $this->Form->submit(__d('users', 'Submit'));
echo $this->Form->end();
