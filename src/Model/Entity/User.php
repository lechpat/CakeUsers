<?php
namespace Users\Model\Entity;

use Cake\ORM\Entity;

/**
 * User Entity.
 *
 * @property string $id
 * @property string $username
 * @property string $name
 * @property string $email
 * @property string $email_token
 * @property bool $email_verified
 * @property \Cake\I18n\Time $email_token_expires
 * @property bool $active
 * @property string $password
 * @property string $password_token
 * @property \Cake\I18n\Time $password_token_expires
 * @property string $role
 * @property \Cake\I18n\Time $last_login
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 * @property \App\Model\Entity\Role[] $roles
 */
class User extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false,
    ];
}
