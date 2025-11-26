<?php
namespace app\models;

use Yii;
use yii\base\Model;

class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user = false;

    public function rules()
    {
        return [
            [['username','password'], 'required'],
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => 'Nombre de usuario',
            'password' => 'Contraseña',
            'rememberMe' => 'Recordarme',
        ];
    }

    public function validatePassword($attribute, $params)
    {
        if ($this->hasErrors()) return;
        $user = $this->getUser();
        if (!$user || !$user->validatePassword($this->password)) {
            $this->addError($attribute, 'Nombre de usuario o contraseña incorrectos.');
        }
    }

    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = Usuario::findByUsername($this->username);
        }
        return $this->_user;
    }

    public function login()
    {
        if ($this->validate()) {
            $duration = $this->rememberMe ? 3600*24*30 : 0; // 30 días
            return Yii::$app->user->login($this->getUser(), $duration);
        }
        return false;
    }
}
