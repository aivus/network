<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * Class RegistrationForm for registration form
 * @package app\models
 */
class RegistrationForm extends User
{
    /**
     * @var string Password_hash for registration with invite
     */
    public $password_hash;

    /**
     * @var string Repeat_password for registration
     */
    public $repeat_password;

    /**
     * @return validation rules array
     */
    public function rules() {
        return array(
            array('email, first_name, last_name, password, repeat_password', 'required'),
            array('email', 'email'),
            array('email', 'validateEmail'),
            array('password_hash', 'validatePasswordHash'),
            array('repeat_password', 'compare', 'compareAttribute'=>'password')
        );
    }

    public function scenarios() {
        return array(
            'default' => array('email', 'first_name', 'last_name', 'password', 'password_hash', 'repeat_password'),
            'firstVisit' => array('email', 'password_hash')
        );
    }

    public function validateEmail() {
        $user = User::findByEmail($this->email);

        if (!$user) {
            $this->addError('email', 'User with this email was not found');
        }
    }

    /**
     * Validation password hash
     */
    public function validatePasswordHash() {
        $user = User::findByEmail($this->email);
        if (!$user || $this->password_hash != $user->password)
            $this->addError('password_hash', 'Incorrect password hash in the invite token');
    }

    public function registration() {
        if ($this->validate()) {
            $user = User::findByEmail($this->email);

            $user->first_name = $this->first_name;
            $user->is_active  = 1;
            $user->last_name  = $this->last_name;
            $user->password   = $this->hashPassword($this->password);
            $user->save();
            return true;
        }

        return false;
    }
}