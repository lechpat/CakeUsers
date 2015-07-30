<?php
/**
 * UserToolComponent
 *
 * @author Florian Krämer
 * @copyright 2013 - 2015 Florian Krämer
 * @license MIT
 */
namespace Burzum\UserTools\Controller\Component;

use Cake\Controller\Component;
use Cake\Event\EventManagerTrait;
use Cake\ORM\TableRegistry;
use Cake\Event\Event;
use Cake\Controller\ComponentRegistry;
use Cake\Utility\Hash;
use Cake\Network\Exception\NotFoundException;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Core\Configure;
use Cake\Network\Response;

class UserToolComponent extends Component {

	use EventManagerTrait;

/**
 * Components
 *
 * @var array
 */
	public $components = array(
		'Session',
		'Flash',
	);

/**
 * Default config
 *
 * These are merged with user-provided config when the component is used.
 *
 * @var array
 */
	protected $_defaultConfig = [
		'autoloadBehavior' => true,
		'actionMapping' => true,
		'directMapping' => false,
		'userModel' => null,
		'passwordReset' => 'token',
		'auth' => [
			'authenticate' => [
				'Form' => [
					'userModel' => 'Users',
					'fields' => [
						'username' => 'email',
						'password' => 'password'
					],
					'scope' => [
						'Users.email_verified' => 1
					]
				]
			]
		],
		'registration' => [
			'enabled' => true,
			'successFlashOptions' => [],
			'successRedirectUrl' => '/',
			'errorFlashOptions' => [],
			'errorRedirectUrl' => false,
			'setEntity' => true,
		],
		'login' => [
			'successFlashOptions' => [],
			'successRedirectUrl' => null,
			'errorFlashOptions' => [],
			'errorRedirectUrl' => false,
			'setEntity' => true,
		],
		'logout' => [
			'successFlashOptions' => [],
			'successRedirectUrl' => '/',
		],
		'verifyEmailToken' => [
			'queryParam' => 'token',
			'successRedirectUrl' => [
				'action' => 'login'
			],
			'errorRedirectUrl' => '/'
		],
		'requestPassword' => [
			'successFlashOptions' => [],
			'successRedirectUrl' => '/',
			'errorFlashOptions' => [],
			'errorRedirectUrl' => '/',
			'field' => 'email',
			'setEntity' => true,
		],
		'resetPassword' => [
			'successFlashOptions' => [],
			'successRedirectUrl' => '/',
			'errorFlashOptions' => [],
			'errorRedirectUrl' => false,
			'invalidErrorFlashOptions' => [],
			'invalidErrorRedirectUrl' => '/',
			'expiredErrorFlashOptions' => [],
			'expiredErrorRedirectUrl' => '/',
			'queryParam' => 'token',
			'tokenOptions' => [],
		],
		'changePassword' => [],
		'verifyToken' => [
			'queryParam' => 'token',
			'type' => 'Email',
			'successRedirectUrl' => [
				'action' => 'login'
			],
			'errorMessage' => null,
			'errorRedirectUrl' => '/'
		],
		'getUser' => [
			'viewVar' => 'user'
		],
		'actionMap' => [
			'index' => [
				'method' => 'listing',
				'view' => 'Burzum/UserTools.UserTools/index',
			],
			'register' => [
				'method' => 'register',
				'view' => 'Burzum/UserTools.UserTools/register'
			],
			'login' => [
				'method' => 'login',
				'view' => 'Burzum/UserTools.UserTools/login',
			],
			'logout' => [
				'method' => 'logout',
				'view' => null
			],
			'reset_password' => [
				'method' => 'resetPassword',
				'view' => 'Burzum/UserTools.UserTools/reset_password',
			],
			'request_password' => [
				'method' => 'requestPassword',
				'view' => 'Burzum/UserTools.UserTools/request_password',
			],
			'change_password' => [
				'method' => 'changePassword',
				'view' => 'Burzum/UserTools.UserTools/change_password',
			],
			'verify_email' => [
				'method' => 'verifyEmailToken',
				'view' => 'Burzum/UserTools.UserTools/verify_email',
			],
			'view' => [
				'method' => 'getUser',
				'view' => 'Burzum/UserTools.UserTools/view',
			]
		]
	];

/**
 * User Table
 *
 * @var \Cake\ORM\Table $UserTable
 */
	public $UserTable = null;

/**
 * Response object
 *
 * @var \Cake\Network\Response
 */
	public $response = null;

/**
 * Helper property to detect a redirect
 *
 * @see UserToolComponent::handleFlashAndRedirect();
 * @var \Cake\Network\Response
 */
	protected $_redirectResponse = null;

/**
 * Convenience property to avoid the need to go through the registry all time.
 *
 * @var \Cake\Controller\Controller;
 */
	protected $_controller = null;

/**
 * Constructor. Parses the accepted content types accepted by the client using HTTP_ACCEPT
 *
 * @param ComponentRegistry $registry ComponentRegistry object.
 * @param array $config Config options array
 */
	public function __construct(ComponentRegistry $registry, $config = []) {
		$this->_defaultConfig = Hash::merge(
			$this->_defaultConfig,
			$this->_translateConfigMessages(),
			(array) Configure::read('UserTools.Component')
		);
		$this->_controller = $registry->getController();
		$this->response = $this->_controller->response;
		parent::__construct($registry, $config);
	}

/**
 * Translates the messages in the configuration array
 *
 * @return array
 */
	protected function _translateConfigMessages() {
		return [
			'requestPassword' => [
				'successMessage' => __d('user_tools', 'An email was send to your address, please check your inbox.'),
				'errorMessage' => __d('user_tools', 'Invalid user.'),
			],
			'resetPassword' => [
				'successMessage' => __d('user_tools', 'Your password has been reset, you can now login.'),
				'errorMessage' => __d('user_tools', 'Please check your inputs.'),
				'invalidErrorMessage' => __d('user_tools', 'Invalid token!'),
				'expiredErrorMessage' => __d('user_tools', 'The token has expired!')
			],
			'changePassword' => [
				'successMessage' => __d('user_tools', 'Your password has been updated.'),
				'errorMessage' => __d('user_tools', 'Could not update your password, please check for errors and try again.'),
			],
			'registration' => [
				'successMessage' => __d('user_tools', 'Thank you for signing up!'),
				'errorMessage' => __d('user_tools', 'Please check your inputs'),
			],
			'login' => [
				'successMessage' => __d('user_tools', 'You are logged in!'),
				'errorMessage' => __d('user_tools', 'Invalid login credentials.'),
			],
			'logout' => [
				'successMessage' => __d('user_tools', 'You are logged out!'),
			],
			'verifyEmailToken' => [
				'successMessage' => __d('user_tools', 'Email verified, you can now login!'),
				'errorMessage' => __d('user_tools', 'Invalid email token!'),
			],
			'verifyToken' => [
				'successMessage' => __d('user_tools', 'Token verified!'),
			]
		];
	}

/**
 * Initializes the component
 *
 * @return void
 */
	public function initialize(array $config) {
		$this->setUserTable($this->_config['userModel']);
		$this->loadUserBehaviour();
	}


/**
 * User listing with pagination
 *
 * @param array $options Pagination options
 * @return \Cake\ORM\Query
 */
	public function listing($options = []) {
		$this->_controller->set('users', $this->_controller->paginate($this->UserTable, $options));
		$this->_controller->set('_serialize', ['users']);
	}

/**
 * Loads the User behavior for the user model if it is not already loaded
 *
 * @return void
 */
	public function loadUserBehaviour() {
		if ($this->_config['autoloadBehavior'] && !$this->UserTable->hasBehavior('UserTools.User')) {
			if (is_array($this->_config['autoloadBehavior'])) {
				$this->UserTable->addBehavior('Burzum/UserTools.User', $this->_config['autoloadBehavior']);
			} else {
				$this->UserTable->addBehavior('Burzum/UserTools.User');
			}
		}
	}

/**
 * Sets or instantiates the user model class.
 *
 * @param mixed $table
 * @throws \RuntimeException
 * @return void
 */
	public function setUserTable($table = null) {
		if ($table === null) {
			$this->UserTable = $this->_controller->{$this->_controller->modelClass};
		} else {
			if (is_object($table)) {
				if (!is_a($table, '\Cake\ORM\Table')) {
					throw new \RuntimeException('Passed object is not of type \Cake\ORM\Table!');
				}
				$this->UserTable = $table->alias();
			}
			if (is_string($table)) {
				$this->UserTable = TableRegistry::get($table);
			}
		}
		$this->_controller->set('userTable', $this->UserTable->alias());
	}

/**
 * Start up
 *
 * @param Event $Event
 * @link https://github.com/cakephp/cakephp/issues/4530
 * @return Response|null
 */
	public function startup(Event $Event) {
		if ($this->_config['actionMapping'] === true) {
			$result = $this->mapAction();
			if ($result instanceof Response) {
				return $result;
			}
		}
	}

/**
 * Maps a called controller action to a component method
 *
 * @return bool|\Cake\Network\Response
 */
	public function mapAction() {
		$action = $this->request->params['action'];

		if ($this->_config['directMapping'] === true) {
			if (!method_exists($this, $action)) {
				return false;
			}
			$result = $this->{$action}();
			if ($result instanceof Response) {
				return $result;
			}
			return $this->_controller->render($action);
		}

		if (isset($this->_config['actionMap'][$action]) && method_exists($this, $this->_config['actionMap'][$action]['method'])) {
			$this->{$this->_config['actionMap'][$action]['method']}();
			if ($this->_redirectResponse instanceof Response) {
				return $this->_redirectResponse;
			}
			if (is_string($this->_config['actionMap'][$action]['view'])) {
				return $this->_controller->render($this->_config['actionMap'][$action]['view']);
			} else {
				return $this->response;
			}
		}

		return false;
	}

/**
 * Login
 *
 * @var array
 * @return bool
 */
	public function login($options = []) {
		$options = Hash::merge($this->_config['login'], $options);

		$entity = $this->UserTable->newEntity([], ['validate' => false]);
		if ($this->request->is('post')) {
			$entity = $this->UserTable->patchEntity($entity, $this->request->data, ['validate' => false]);

			$event = new Event('User.beforeLogin', $this, [
				'options' => $options,
				'entity' => $entity
			]);

			$this->eventManager()->dispatch($event);
			if ($event->isStopped()) {
				return $event->result;
			}

			$Auth = $this->_getAuthObject();
			$user = $Auth->identify();

			if ($user) {
				$event = new Event('User.afterLogin', $this, ['options' => $options]);
				$this->eventManager()->dispatch($event);
				if ($event->isStopped()) {
					return $event->result;
				}
				$Auth->setUser($user);
				if ($options['successRedirectUrl'] === null) {
					$options['successRedirectUrl'] = $Auth->redirectUrl();
				}
				$this->handleFlashAndRedirect('success', $options);
				return true;
			} else {
				$this->handleFlashAndRedirect('error', $options);
			}
		}
		if ($options['setEntity']) {
			$this->_controller->set('userEntity', $entity);
		}
		return false;
	}

/**
 * Gets an user based on it's user id.
 *
 * - `viewVar` it sets the entity to the view. It's set by default to `user`. To
 *    disable setting the view var just set it to false.
 *
 * @param int|string $userId UUID or integer type user id.
 * @param array $options Configuration options.
 * @return mixed
 */
	public function getUser($userId = null, $options = []) {
		$options = Hash::merge($this->_config['getUser'], $options);
		if (is_null($userId)) {
			if (isset($this->request->params['pass'][0])) {
				$userId = $this->request->params['pass'][0];
			}
		}
		$entity = $this->UserTable->getUser($userId);
		if ($options['viewVar'] !== false) {
			$this->_controller->set($options['viewVar'], $entity);
			$this->_controller->set('_serialize', [$options['viewVar']]);
		}
		return $entity;
	}

/**
 * Deletes an user record
 *
 * @param mixed $userId
 * @param array $options
 * @return boolean
 */
	public function deleteUser($userId = null, $options = []) {
		if (is_string($userId) || is_integer($userId)) {
			$entity = $this->UserTable->newEntity([
				$this->UserTable->primaryKey() => $userId
			]);
		}
		if (is_array($userId)) {
			$entity = $this->UserTable->newEntity($userId);
		}
		if ($this->UserTable->delete($entity)) {
			$this->handleFlashAndRedirect('success', $options);
			return true;
		} else {
			$this->handleFlashAndRedirect('error', $options);
			return false;
		}
	}

/**
 * Logout
 *
 * @param array $options Options array.
 * @return void
 */
	public function logout($options = []) {
		$options = Hash::merge($this->_config['logout'], $options);
		$Auth = $this->_getAuthObject();
		$user = $Auth->user();
		if (empty($user)) {
			$this->_controller->redirect($this->_controller->referer());
			return;
		}
		$this->handleFlashAndRedirect('success', $options);
		$this->_controller->redirect($Auth->logout());
		return;
	}

/**
 * User registration
 *
 * Options:
 *
 * - `enabled` Disables/enables the registration. If false a NotFoundException is thrown. Default true.
 * - `successMessage` The success flash message.
 * - `successRedirectUrl` Success redirect url. Default /.
 * - `errorMessage` The error flash message.
 * - `errorRedirectUrl` The error redirect url.
 * - `setEntity` Set the entity to the view or not, default is true.
 *
 * @throws \Cake\Error\NotFoundException
 * @param array $options
 * @return boolean|null
 */
	public function register($options = []) {
		$options = Hash::merge($this->_config['registration'], $options);
		if ($options['enabled'] === false) {
			throw new NotFoundException();
		}
		$entity = $this->UserTable->newEntity();
		// Make the field accessible in the case the default entity class is used.
		$entity->accessible('confirm_password', true);
		if ($this->request->is('post')) {
			$entity = $this->UserTable->patchEntity($entity, $this->request->data());
			if ($this->UserTable->register($entity)) {
				$this->handleFlashAndRedirect('success', $options);
				if ($options['setEntity'] === true) {
					$this->_controller->set('usersEntity', $entity);
				}
				return true;
			} else {
				$this->handleFlashAndRedirect('error', $options);
				if ($options['setEntity'] === true) {
					$this->_controller->set('usersEntity', $entity);
				}
				return false;
			}
		}
		if ($options['setEntity'] === true) {
			$this->_controller->set('userEntity', $entity);
			// BC
			$this->_controller->set('usersEntity', $entity);
		}
	}

/**
 * verifyEmailToken
 *
 * @param array $options
 * @return mixed
 */
	public function verifyEmailToken($options = []) {
		return $this->verifyToken(Hash::merge($this->_defaultConfig['verifyEmailToken'], $options, ['type' => 'Email']));
	}

/**
 * The user can request a new password reset token, an email is send to him.
 *
 * @param array $options
 * @throws \Cake\Datasource\Exception\RecordNotFoundException
 * @return boolean|null
 */
	public function requestPassword($options = []) {
		$options = Hash::merge($this->_config['requestPassword'], $options);
		$entity = $this->UserTable->newEntity(['validate' => 'requestPassword']);
		if ($this->request->is('post')) {
			$entity = $this->UserTable->patchEntity($entity, $this->request->data, ['validate' => 'requestPassword']);

			if (!$entity->errors($options['field']) && $this->_initPasswordReset($entity, $options)) {
				return true;
			}

			if ($options['setEntity']) {
				if ($entity->dirty('email') && !$entity->errors('email')) {
					$entity->email = '';
				}
				$this->_controller->set('userEntity', $entity);
			}
			unset($this->request->data[$options['field']]);
			return false;
		}
		if ($options['setEntity']) {
			$this->_controller->set('userEntity', $entity);
		}
	}

	protected function _initPasswordReset($entity, $options) {
		try {
			$this->UserTable->initPasswordReset($this->request->data[$options['field']]);
			$this->handleFlashAndRedirect('success', $options);
			if ($options['setEntity']) {
				$this->_controller->set('userEntity', $entity);
			}
			return true;
		} catch (RecordNotFoundException $e) {
			$this->handleFlashAndRedirect('error', $options);
		}
		return false;
	}

/**
 * Allows the user to enter a new password.
 *
 * @param string $token
 * @param array $options
 * @return void
 */
	public function resetPassword($token = null, $options = []) {
		$options = (Hash::merge($this->_defaultConfig['resetPassword'], $options));
		if (!empty($this->request->query[$options['queryParam']])) {
			$token = $this->request->query[$options['queryParam']];
		}
		try {
			$entity = $this->UserTable->verifyPasswordResetToken($token, $options['tokenOptions']);
		} catch (RecordNotFoundException $e) {
			if (empty($this->_config['resetPassword']['invalidErrorMessage'])) {
				$this->_config['resetPassword']['invalidErrorMessage'] = $e->getMessage();
			}
			$this->handleFlashAndRedirect('invalidError', $options);
			$entity = $this->UserTable->newEntity();
		}

		if (isset($entity->token_is_expired) && $entity->token_is_expired === true) {
			if (empty($this->_config['resetPassword']['invalidErrorMessage'])) {
				$this->_config['resetPassword']['invalidErrorMessage'] = $e->getMessage();
			}
			$this->handleFlashAndRedirect('expiredError', $options);
		}

		if ($this->request->is('post')) {
			$entity = $this->UserTable->patchEntity($entity, $this->request->data);
			if ($this->UserTable->resetPassword($entity)) {
				$this->handleFlashAndRedirect('success', $options);
			} else {
				$this->handleFlashAndRedirect('error', $options);
			}
		} else {
			$entity = $this->UserTable->newEntity();
		}
		$this->_controller->set('entity', $entity);
	}

/**
 * Let the logged in user change his password.
 *
 * @param array $options
 * @return void
 */
	public function changePassword($options = []) {
		$options = (Hash::merge($this->_defaultConfig['changePassword'], $options));
		$entity = $this->UserTable->newEntity();
		$entity->accessible(['id', 'old_password', 'new_password', 'confirm_password'], true);
		if ($this->request->is(['post', 'put'])) {
			$entity = $this->UserTable->patchEntity($entity, $this->request->data);
			$entity->id = $this->_controller->Auth->user('id');
			$entity->isNew(false);
			if ($this->UserTable->changePassword($entity)) {
				$this->request->data = [];
				$entity = $this->UserTable->newEntity();
				$entity->id = $this->_controller->Auth->user('id');
				$entity->isNew(false);
				$this->handleFlashAndRedirect('success', $options);
			} else {
				$this->handleFlashAndRedirect('error', $options);
			}
		}
		$this->_controller->set('entity', $entity);
	}

/**
 * Verify Token
 *
 * @param array $options
 * @throws \Cake\Error\NotFoundException;
 * @return mixed
 */
	public function verifyToken($options = []) {
		$options = Hash::merge($this->_defaultConfig['verifyToken'], $options);

		if (!isset($this->request->query[$options['queryParam']])) {
			throw new NotFoundException(__d('user_tools', 'No token present!'));
		}

		$methodName = 'verify' . $options['type'] . 'Token';
		try {
			$result = $this->UserTable->$methodName($this->request->query[$options['queryParam']]);
			$this->handleFlashAndRedirect('success', $options);
		} catch (RecordNotFoundException $e) {
			if (is_null($options['errorMessage'])) {
				$options['errorMessage'] = $e->getMessage();
			}
			$this->handleFlashAndRedirect('error', $options);
			$result = false;
		}

		return $result;
	}

/**
 * Handles flashes and redirects
 *
 * @param string $type Prefix for the array key, mostly "success" or "error"
 * @param array $options Options
 * @return mixed
 */
	public function handleFlashAndRedirect($type, $options) {
		$this->_handleFlash($type, $options);
		$this->_handleRedirect($type, $options);
	}

/**
 * Handles the redirect options.
 *
 * @param string $type Prefix for the array key, mostly "success" or "error"
 * @param array $options Options
 * @return mixed
 */
	protected function _handleRedirect($type, $options) {
		if (isset($options[$type . 'RedirectUrl']) && $options[$type . 'RedirectUrl'] !== false) {
			$result = $this->_controller->redirect($options[$type . 'RedirectUrl']);
			return $this->_redirectResponse = $result;
		}
		return false;
	}

/**
 * Handles the flash options.
 *
 * @param string $type Prefix for the array key, mostly "success" or "error"
 * @param array $options Options
 * @return boolean
 */
	protected function _handleFlash($type, $options) {
		if (isset($options[$type . 'Message']) && $options[$type . 'Message'] !== false) {
			if (is_string($options[$type . 'Message'])) {
				$flashOptions = [];
				if (isset($options[$type . 'FlashOptions'])) {
					$flashOptions = $options[$type . 'FlashOptions'];
				}
				$this->Flash->set($options[$type . 'Message'], $flashOptions);
				return true;
			}
		}
		return false;
	}

/**
 * Gets the auth component object
 *
 * If there is an auth component loaded it will take that one from the
 * controller. If not the configured default settings will be used to create
 * a new instance of the auth component. This is mostly thought as a fallback,
 * in a real world scenario the app should have set auth set up in it's
 * AppController.
 *
 * @return AuthComponent
 */
	protected function _getAuthObject() {
		if (!$this->_registry->has('Auth')) {
			$Auth = $this->_registry->load('Auth', $this->_config['auth']);
			$Auth->request = $this->request;
			$Auth->response = $this->response;
			return $Auth;
		} else {
			return $this->_registry->Auth;
		}
	}

}