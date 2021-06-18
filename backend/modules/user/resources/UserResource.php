<?php
namespace backend\modules\user\resources;


use backend\models\user\User;

/**
 * Class UserResource
 *
 * @author Maria Malkevich <malkevihma@gmail.com>
 * @package backend\modules\user\resources
 */
class UserResource extends User
{
    public function fields()
    {
        return [
            'id', 'email', 'password_hash'
        ];
    }
}