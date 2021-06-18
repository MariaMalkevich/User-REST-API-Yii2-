<?php
namespace backend\modules\user\controllers;

use Yii;
use yii\rest\Controller;
use yii\filters\Cors;
use yii\web\UnauthorizedHttpException;

use backend\modules\user\models\LoginForm;
use backend\modules\user\models\SignupForm;
use backend\modules\user\models\PasswordResetRequestForm;
use backend\modules\user\models\ResetPasswordForm;
use backend\modules\user\models\ResendVerificationEmailForm;
use backend\modules\user\models\VerifyEmailForm;


/**
 * Default controller for the `user` module
 */
class UserController extends Controller
{   
    
    
      public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'cors' => Cors::class
        ]);
    }
    
    
    
     /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        $model = new LoginForm();
        
        if ($model->load(Yii::$app->request->post(), '') && $model->login()) {
            return $model->getUser();
        }
        
        Yii::$app->response->statusCode = 422;
        return [
            'errors' => $model->errors
        ];
    }
    
      /**
     * Signup action.
     *
     * @return string
     */
      public function actionSignup()
    {

        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post(), '') && $model->register()) {
            return $model->user;
        }

        Yii::$app->response->statusCode = 422;
        return [
            'errors' => $model->errors
        ];
    }
    
    
       /**
     * Verify email address
     *
     * @param string $token
     * @throws BadRequestHttpException
     * @return yii\web\Response
     */
    public function actionVerifyEmail($token)
    {
        try {
            $model = new VerifyEmailForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if ($user = $model->verifyEmail()) {
             return true;
        }else{
            return false;
        }
        
         Yii::$app->response->statusCode = 422;
        return [
            'errors' => $model->errors
        ];

    }

    /**
     * Resend verification email
     *
     * @return mixed
     */
    public function actionResendVerificationEmail()
    {
        $model = new ResendVerificationEmailForm();
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
              return $model->sendEmail();      
        }
              Yii::$app->response->statusCode = 422;
        return [
            'errors' => $model->errors
        ];
       
    }
    
    
       /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post(), '') && $model->validate()) {
            return $model->sendEmail(); 
        }
        
         Yii::$app->response->statusCode = 422;
        return [
            'errors' => $model->errors
        ];
        
    }
    
    
      /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post(), '') && $model->validate() && $model->resetPassword()) {
           return $model->resetPassword();
        }

         Yii::$app->response->statusCode = 422;
        return [
            'errors' => $model->errors
        ];
    }
    

    public function actionData()
    {
        $headers = Yii::$app->request->headers;
        if (!isset($headers['Authorization'])){
            throw new UnauthorizedHttpException();
        }
        $passwordHash = explode(" ", $headers['Authorization'])[1];
        $user = UserResource::findIdentityByAccessToken($passwordHash);
        if (!$user){
            throw new UnauthorizedHttpException();
        }
        return $user;
    }
    
    
    
}
