<?php
/**
 * AuthHelper
 *
 * @author Florian Krämer
 * @copyright 2013 - 2015 Florian Krämer
 * @license MIT
 */
namespace Burzum\UserTools\View\Helper;

use Cake\Utility\Hash;
use Cake\View\Helper;
use Cake\View\View;

class AuthHelper extends Helper {

/**
 * Default settings
 *
 * @var array
 */
	protected $_defaultConfig = [
		'session' => false,
		'viewVar' => 'userData',
		'viewVarException' => false,
		'roleField' => 'role'
	];

/**
 * User data
 *
 * @array
 */
	protected $_userData = [];

/**
 * Constructor
 *
 * @param \Cake\View\View $View
 * @param array $settings
 * @throws RuntimeException
 * @return AuthHelper
 */
	public function __construct(\Cake\View\View $View, $settings = []) {
		parent::__construct($View, $settings);
		$this->_setupUserData();
	}

/**
 * Sets up the user data from session or view var
 *
 * @throws \RuntimeException
 * @return void
 */
	protected function _setupUserData() {
		if (is_string($this->_config['session'])) {
			$this->_userData = $this->_View->request->session()->read($this->_config['session']);
		} else {
			if (!array_key_exists($this->_config['viewVar'], $this->_View->viewVars)) {
				if ($this->_config['viewVarException'] === true) {
					throw new \RuntimeException(sprintf('View var `%s` not present! Please set the auth data to the view. See the documentation.', $this->_config['viewVar']));
				} else {
					$this->_userData = [];
				}
			} else {
				$this->_userData = $this->_View->viewVars[$this->_config['viewVar']];
			}
		}
	}

/**
 * Convinience method to the the user data in any case as array.
 *
 * This is mostly done because accessing the Entity object via Hash() caused an error when passing an object.
 *
 * @param bool $asArray Return as array, default true.
 * @return array
 */
	protected function _userData($asArray = true) {
		if ($asArray === true) {
			if (is_a($this->_userData, '\Cake\ORM\Entity')) {
				$this->_userData = $this->_userData->toArray();
			}
		}
		return $this->_userData;
	}

/**
 * Checks if a user is logged in
 *
 * @return boolean
 */
	public function isLoggedIn() {
		return (!empty($this->_userData));
	}

/**
 * This check can be used to tell if a record that belongs to some user is the
 * current logged in user
 *
 * @param string|integer $userId
 * @param string $field Name of the field in the user record to check against, id by default
 * @return boolean
 */
	public function isMe($userId, $field = 'id') {
		return ($userId === $this->user($field));
	}

/**
 * Method equal to the AuthComponent::user()
 *
 * @param string $key
 * @return mixed
 */
	public function user($key = null) {
		if ($key === null) {
			return $this->_userData;
		}
		return Hash::get($this->_userData(true), $key);
	}

/**
 * Role check.
 *
 * @param string String of the role identifier.
 * @return boolean|null True if the role is in the set of roles for the active user data.
 */
	public function hasRole($role) {
		if (!is_string($role)) {
			throw new \InvalidArgumentException('Role must be a string!');
		}
		$roles = $this->user($this->config('roleField'));
		if (is_string($roles)) {
			return ($role === $roles);
		}
		if (is_array($roles)) {
			return (in_array($role, $roles));
		}
	}

}
