<?php
namespace backend\modules\user\models;

use backend\modules\user\resources\UserResource;


/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class LoginForm extends \backend\models\user\LoginForm
{

     /**
     * Finds user by [[email]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = UserResource::findByEmail($this->email);
        }

        return $this->_user;
    }
    
}