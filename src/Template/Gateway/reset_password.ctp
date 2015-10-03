<?php
echo $this->Form->create($entity);
echo $this->Form->input('password', array(
    'label' => __d('users', 'New Password'),
    'required' => false,
));
echo $this->Form->input('confirm_password', array(
    'type' => 'password',
    'label' => __d('users', 'Repeat Password'),
    'required' => false,
));
echo $this->Form->submit(__d('users', 'Submit'));
echo $this->Form->end();
