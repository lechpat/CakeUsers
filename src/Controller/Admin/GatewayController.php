<?php
namespace Users\Controller\Admin;

use Users\Controller\AppController;
use Users\Controller\UserSignTrait;

/**
 * Gateway manager controller.
 *
 * Provides login and logout methods for backend.
 */
class GatewayController extends AppController
{

    use UserSignTrait;
}
