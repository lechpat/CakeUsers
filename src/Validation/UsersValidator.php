<?php
namespace Users\Validation;

use Cake\Validation\Validator;

class UsersValidator extends Validator {

/**
 * Constructor
 */
	public function __construct() {
		$this->provider('myself', $this);
		$this->validateUserName();
		$this->validateEmail();
		$this->validatePassword();
		$this->validateConfirmPassword();
	}

/**
 * Validates the username field.
 *
 * Override it as needed to change the rules for only that field.
 *
 * @return void
 */
	public function validateUserName() {
		$this->add('username', [
			'notBlank' => [
				'rule' => 'notBlank',
				'message' => __d('users', 'An username is required.')
			],
			'length' => [
				'rule' => ['lengthBetween', 3, 32],
				'message' => __d('users', 'The username must be between 3 and 32 characters.')
			],
			'unique' => [
				'rule' => ['validateUnique', ['scope' => 'username']],
				'provider' => 'table',
				'message' => __d('users', 'The username is already in use.')
			],
			'alphaNumeric' => [
				'rule' => 'alphaNumeric',
				'message' => __d('users', 'The username must be alpha numeric.')
			]
		]);
	}

/**
 * Validates the email field.
 *
 * Override it as needed to change the rules for only that field.
 *
 * @return void
 */
	public function validateEmail() {
		$this->add('email', [
			'notBlank' => [
				'rule' => 'notBlank',
				'message' => __d('users', 'An email is required.')
			],
			'unique' => [
				'rule' => ['validateUnique', ['scope' => 'email']],
				'provider' => 'table',
				'message' => __d('users', 'The email is already in use.')
			],
			'validEmail' => [
				'rule' => 'email',
				'message' => __d('users', 'Must be a valid email address.')
			]
		]);
	}

/**
 * Validates the password field.
 *
 * Override it as needed to change the rules for only that field.
 *
 * @return void
 */
	public function validatePassword() {
		$this->add('password', [
			'notBlank' => [
				'rule' => 'notBlank',
				'message' => __d('users', 'A password is required.')
			],
			'minLength' => [
				'rule' => ['minLength', 6],
				'message' => __d('users', 'The password must have at least 6 characters.')
			],
			'confirmPassword' => [
				'rule' => ['compareFields', 'confirm_password'],
				'message' => __d('users', 'The passwords don\'t match!'),
				'provider' => 'myself',
			]
		]);
	}

/**
 * Validates the confirm_password field.
 *
 * Override it as needed to change the rules for only that field.
 *
 * @return void
 */
	public function validateConfirmPassword() {
		$this->add('confirm_password', [
			'notBlank' => [
				'rule' => 'notBlank',
				'message' => __d('users', 'A password is required.')
			],
			'minLength' => [
				'rule' => ['minLength', 6],
				'message' => __d('users', 'The password must have at least 6 characters.')
			],
			'confirmPassword' => [
				'rule' => ['compareFields', 'password'],
				'message' => __d('users', 'The passwords don\'t match!'),
				'provider' => 'myself',
			]
		]);
	}

    public function validateMobileCode() {
        $this->add('code', [
            'notBlank' => [
                'rule' => 'notBlank',
                'message' => __d('users', 'A password is required.')
            ],
            'minLength' => [
                'rule' => ['minLength', 6],
                'message' => __d('users', 'The password must have at least 6 characters.')
            ]
        ]);
    }

/**
 * Compares the value of two fields.
 *
 * @param mixed $value
 * @param string $field
 * @param Entity $context
 * @return boolean
 */
	public function compareFields($value, $field, $context) {
		if (!isset($context['data'][$field])) {
			return true;
		}
		if ($value === $context['data'][$field]) {
			return true;
		}
		return false;
	}
}
