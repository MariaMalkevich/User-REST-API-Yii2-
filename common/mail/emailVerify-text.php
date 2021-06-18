<?php

/* @var $this yii\web\View */
/* @var $user common\models\User */

$verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['user/user/verify-email', 'token' => $user->verification_token]);
?>
Hello <?= $user->email ?>,

Follow the link below to verify your email:

<?= $verifyLink ?>
