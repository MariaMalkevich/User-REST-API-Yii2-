<?php

namespace backend\modules\user\controllers;

use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\Cors;


/**
 * BaseApi controller 
 */
class BaseApiController extends ActiveController
{
  
    public function behaviors()
    {    
        
       $behaviors = parent::behaviors();
        // Options 1: Authenticator works on every action except options
      $behaviors['authenticator']['authMethods'] = [
           HttpBearerAuth::class
       ];
       $behaviors['authenticator']['except'] = ['options'];
       
       $behaviors['cors'] = [
           'class' => Cors::class
       ];
//        // Options 2: Remove authenticator, Add Cors and then Add authenticator
//        $auth = $behaviors['authenticator'];
//        $auth['authMethods'] = [
//            HttpBearerAuth::class
//        ];
//        unset($behaviors['authenticator']);
//        $behaviors['cors'] = [
//            'class' => Cors::class
//        ];
//        $behaviors['authenticator'] = $auth;

        return $behaviors;
        
    } 
}


