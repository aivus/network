<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * Class LoginForm for login form
 * @package app\models
 */
class LoginForm extends Model
{

    /**
     * @var integer Number of seconds that the user can remain in logged-in status.
     * Defaults to 0, meaning login till the user closes the browser or the session is manually destroyed.
     * If greater than 0 and [[enableAutoLogin]] is true, cookie-based login will be supported.
     */
    const DEFAULT_LOGIN_DURATION = 0;
    /**
     * @var string Email for login
     */
    public $email;

    /**
     * @var string Password for login
     */
    public $password;

    /**
     * @var string Captcha for login
     */
    public $captcha;

    /**
     * @return validation rules array
     */
    public function rules() {
        return array(
            array('email, password', 'required'),
            array('email', 'email'),
            array('email', 'isBlock'),
            array('email', 'validateEmail'),
            array('password', 'validatePassword')
            //array('captcha', 'captcha', 'captchaAction' => 'auth/captcha'),
        );
    }

    /**
     * @return scenarios array
     */
    public function scenarios() {
        return array(
            'default' => array('email', 'password')
        );
    }

    /**
     * is this user blocked
     */
    public function isBlock() {
        $user = User::findByEmail($this->email);
        if ($user && 0 == $user->is_active) {
            $this->addError('email', 'Your account was been blocked by admin');
        }
    }

    /**
     * Validation email
     */
    public function validateEmail() {
        $user = User::findByEmail($this->email);

        if (!$user) {
            $this->addError('email', 'Incorrect email');
        }
    }

    /**
     * Validation password
     */
    public function validatePassword() {
        $user = User::findByEmail($this->email);

        if (!$user || !$user->validatePassword($this->password)) {
            $this->addError('password', 'Incorrect password');
        }
    }

    public function login() {
        if ($this->validate()) {
            $user = User::findByEmail($this->email);

            Yii::$app->getUser()->login($user, self::DEFAULT_LOGIN_DURATION);
            return true;
        }
        return false;
    }
}
