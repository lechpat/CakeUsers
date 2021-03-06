<?php
namespace Users\Controller\Component;

//use Cake\Controller\Component;
//use Cake\Controller\ComponentRegistry;
use Cake\Controller\Component\AuthComponent as CakeAuthComponent;
/**
 * Auth component
 */
class AuthComponent extends CakeAuthComponent
{

    /**
     * Default configuration.
     *
     * @var array
     */
//    protected $_defaultConfig = [];

    public function hasRole($role)
    {
        return \Cake\Utility\Hash::check($this->user('roles'),sprintf('{n}[alias=/%s/]',$role));
    }
}
