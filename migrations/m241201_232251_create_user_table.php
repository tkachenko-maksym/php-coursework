<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user}}`.
 */
class m241201_232251_create_user_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('user', [
            'id' => $this->primaryKey(),
            'username'=>$this->string(),
            'email'=>$this->string()->defaultValue(null),
            'password'=>$this->string(),
            'isAdmin'=>$this->integer()->defaultValue(0),
            'photo'=>$this->string()->defaultValue(null),
            'created_at' => $this->integer()->notNull(),
            'auth_key' => $this->string(32)->notNull(),
            'verification_token' => $this->string()->defaultValue(null),
        ]);
        $this->createIndex(
            'idx-user-username',
            'user',
            'username'
        );

        $this->createIndex(
            'idx-user-email',
            'user',
            'email'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('user');
    }
}
