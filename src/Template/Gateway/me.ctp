<?php echo $this->Form->create($user, ['id' => 'user-form']); ?>
    <?php echo $this->Flash->render('user_profile'); ?>
    <fieldset>
        <legend><?php echo __d('user', "Editing User's Information"); ?></legend>
        <?php echo $this->Form->input('name', ['label' => __d('user', 'Real name')]); ?>
        <em class="help-block"><?php echo __d('user', "User's real name, e.g. John Locke"); ?></em>

        <?php echo $this->Form->input('username', ['label' => __d('user', 'User Name'), 'disabled']); ?>
        <em class="help-block"><?php echo __d('user', 'Username cannot be changed, it is used only for identification purposes.'); ?></em>

        <?php echo $this->Form->input('email', ['label' => __d('user', 'e-Mail')]); ?>
        <em class="help-block"><?php echo __d('user', 'Must be unique.'); ?></em>

        <?php echo $this->Form->input('locale', ['type' => 'select', 'options' => $languages, 'label' => __d('user', 'Preferred Language'), 'empty' => __d('user', 'Default')]); ?>
        <em class="help-block"><?php echo __d('user', "Preferred user's language"); ?></em>

        <?php echo $this->Form->input('password', ['type' => 'password', 'label' => __d('user', 'Password'), 'value' => false]); ?>

        <?php echo $this->Form->input('password2', ['type' => 'password', 'label' => __d('user', 'Confirm Password')]); ?>
        <em class="help-block"><?php echo __d('user', "Leave both fields empty if you do not need to change User's password."); ?></em>
    </fieldset>

    <?php if (isset($user->_fields) && $user->_fields->count()): ?>
    <fieldset>
        <legend><?php echo __d('user', 'Additional Information'); ?></legend>
        <?php foreach ($user->_fields as $field): ?>
            <?php echo $this->Form->input($field); ?>
        <?php endforeach; ?>
    </fieldset>
    <?php endif; ?>

    <?php echo $this->Form->submit(__d('user', 'Save Changes')); ?>
<?php echo $this->Form->end(); ?>
