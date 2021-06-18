<?php

namespace backend\modules\user\models;

use backend\models\user\User;
use backend\modules\user\resources\UserResource;
use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class SignupForm extends Model
{
    public $email;
    public $password;
    public $password_repeat;

    public $user = null;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            ['email', 'trim'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            [['email', 'password', 'password_repeat'], 'required'],
            ['password', 'compare', 'compareAttribute' => 'password_repeat'],
            ['password', 'string', 'min' => 6],
            ['email', 'unique',
                'targetClass' => '\backend\modules\user\resources\UserResource',
                'message' => 'This email has already been taken.'
            ],
        ];
    }

    public function register()
    {
        if ($this->validate()) {
            $user = new UserResource();
            $user->email = $this->email;
            $user->created_at = $time = time();
            $user->updated_at = $time;
            $user->auth_key = Yii::$app->security->generateRandomString();
            $user->password_hash = Yii::$app->security->generatePasswordHash($this->password);
            $user->generateEmailVerificationToken();
            $this->user = $user;
            $user->save(false) && $this->sendEmail($user);
            //$user->save(false);
             // the following three lines were added:
            $auth = Yii::$app->authManager;
            $userRole = $auth->getRole('user');
            $auth->assign($userRole, $user->getId());
            
            if ($user->save()) {
                return Yii::$app->user->login($user, 0);
            }
            return false;
        }
        return false;
    }
    
      /**
     * Sends confirmation email to user
     * @param User $user user model to with email should be send
     * @return bool whether the email was sent
     */
    protected function sendEmail($user)
    {
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'emailVerify-html', 'text' => 'emailVerify-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject('Account registration at ' . Yii::$app->name)
            ->send();
    }
    
    
}