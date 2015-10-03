<div class="col-sm-4 col-sm-offset-4">
    <div class="col-lg-10 col-lg-offset-1">
        <h3 class="text-center"><?= __('Welcome to {0}','fond<span class="orange">&#275;</span>') ?></h3>
        <p class="text-center"><?= __('Sign in')?></p>
        <?= $this->Form->create($userEntity,[]) ?>
            <fieldset>
                        <?= $this->Flash->render('auth',['element'=>'Flash/inline']) ?>
                        <?= $this->Form->input('email',[
                            'prepend' => '<i class="fa fa-envelope"></i>',
                            'placeholder' => __d('user','Email'),
                            'required' => true,
                            'label' => false,
                            'autocomplete' => false
                        ]) ?>
                        <?= $this->Form->input('password',[
                            'prepend' => '<i class="fa fa-key"></i>',
                            'placeholder' => __d('user','Password'),
                            'required' => true,
                            'label' => false
                        ]) ?>
                        <div class="checkbox">
                        <?= $this->Form->input('remember',[
                            'skip' => 'form-control',
                            'type' => 'checkbox',
                            'label' => __d('user','Remember me')
                        ]) ?>
                        </div>
                        <?= $this->Form->submit(__d('user','Login'),['class' => 'btn btn-primary btn-block']) ?>
            </fieldset>
        <?= $this->Form->end(); ?>
        <br />
        <p class="text-center text-muted"><small><?= $this->Html->link(__d('user','Forgot password?'),['action' => 'forgot'])?></small></p>
        <p class="text-center text-muted"><small><?php // $this->Html->link(__d('user','Do not have an account?'),['action' => 'activation'])?></small></p>
    </div>
</div>
