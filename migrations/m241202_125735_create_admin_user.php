<?php

use yii\db\Migration;

/**
 * Class m241202_125735_create_admin_role
 */
class m241202_125735_create_admin_user extends Migration
{
    public function up()
    {
        $this->insert('user', [
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => Yii::$app->security->generatePasswordHash('admin'),
            'isAdmin' => 1,
            'created_at'=> time(),
            'auth_key' => Yii::$app->security->generateRandomString(),
            'verification_token' => Yii::$app->security->generateRandomString(),

        ]);

    }

    public function down()
    {
        $this->delete('user', ['email' => 'admin@example.com']);
    }
}
