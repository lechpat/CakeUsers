<?php
namespace Users\Controller\Admin;

use Users\Controller\Admin\AppController;
use Users\Controller\UserSignTrait;

/**
 * Gateway manager controller.
 *
 * Provides login and logout methods for backend.
 */
class GatewayController extends AppController
{
    public function initialize() {
        parent::initialize();
        $this->loadComponent('Users.User',[
            'userModel' => 'Users.Users',
            'registration' => [
                'enabled' => false
            ],
            'login' => [
                'successMessage' => false
            ],
            'logout' => [
                'successMessage' => false
            ],
            'actionMap' => [
                'login' => [
                    'view' => 'Users./Admin/Gateway/login',
                    'layout' => 'login' 
                ]
            ]
        ]);
    }

}
