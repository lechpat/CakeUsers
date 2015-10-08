<?php
namespace Users\Config;

use Cake\Routing\Router;

Router::connect('/unauthorized', [
    'plugin' => 'Users',
    'controller' => 'Gateway',
    'action' => 'unauthorized',
], ['routeClass' => 'Cake\Routing\Route\InflectedRoute']);

Router::connect('/me', [
    'plugin' => 'Users',
    'controller' => 'Gateway',
    'action' => 'me',
], ['routeClass' => 'Cake\Routing\Route\InflectedRoute']);

Router::connect('/admin/me', [
    'plugin' => 'Users',
    'controller' => 'Gateway',
    'action' => 'me',
    'prefix' => 'admin',
], ['routeClass' => 'Cake\Routing\Route\InflectedRoute']);

Router::connect('/profile', [
    'plugin' => 'Users',
    'controller' => 'Gateway',
    'action' => 'profile',
], ['routeClass' => 'Cake\Routing\Route\InflectedRoute']);

Router::connect('/admin/profile', [
    'plugin' => 'Users',
    'controller' => 'Gateway',
    'action' => 'profile',
    'prefix' => 'admin',
], ['routeClass' => 'Cake\Routing\Route\InflectedRoute']);

Router::connect('/login', [
    'plugin' => 'Users',
    'controller' => 'Gateway',
    'action' => 'login',
], ['routeClass' => 'Cake\Routing\Route\InflectedRoute']);

Router::connect('/admin/login', [
    'plugin' => 'Users',
    'controller' => 'Gateway',
    'action' => 'login',
    'prefix' => 'admin',
], ['routeClass' => 'Cake\Routing\Route\InflectedRoute']);

Router::connect('/logout', [
    'plugin' => 'Users',
    'controller' => 'Gateway',
    'action' => 'logout',
], ['routeClass' => 'Cake\Routing\Route\InflectedRoute']);

Router::connect('/admin/logout', [
    'plugin' => false,
    'controller' => 'Users',
    'action' => 'logout',
    'prefix' => 'admin',
], ['routeClass' => 'Cake\Routing\Route\InflectedRoute']);

Router::connect('/activation', [
    'plugin' => 'Users',
    'controller' => 'Gateway',
    'action' => 'activation',
], ['routeClass' => 'Cake\Routing\Route\InflectedRoute']);

Router::connect('/forgot', [
    'plugin' => 'Users',
    'controller' => 'Gateway',
    'action' => 'forgot',
], ['routeClass' => 'Cake\Routing\Route\InflectedRoute']);
Router::connect('/verify_email', [
    'plugin' => 'Users',
    'controller' => 'Gateway',
    'action' => 'verify_email',
], ['routeClass' => 'Cake\Routing\Route\InflectedRoute']);
Router::connect('/validate_mobile', [
    'plugin' => 'Users',
    'controller' => 'Gateway',
    'action' => 'validate_mobile',
], ['routeClass' => 'Cake\Routing\Route\InflectedRoute']);
Router::connect('/verify_mobile', [
    'plugin' => 'Users',
    'controller' => 'Gateway',
    'action' => 'verify_mobile',
], ['routeClass' => 'Cake\Routing\Route\InflectedRoute']);
Router::connect('/reset_password', [
    'plugin' => 'Users',
    'controller' => 'Gateway',
    'action' => 'reset_password',
], ['routeClass' => 'Cake\Routing\Route\InflectedRoute']);
