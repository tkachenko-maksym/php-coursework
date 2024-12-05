<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class User extends ActiveRecord implements IdentityInterface
{

    public static function tableName()
    {
        return 'user';
    }


    public function rules()
    {
        return [
            [['username', 'email', 'password', 'auth_key'], 'required'],
            [['created_at'], 'integer'],
            [['username', 'email'], 'string', 'max' => 255],
            [['email'], 'email'],
            [['username', 'email', 'password', 'verification_token'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['username'], 'unique', 'targetClass' => '\app\models\User', 'message' => 'This username has already been taken.'],
            [['email'], 'unique', 'targetClass' => '\app\models\User', 'message' => 'This email has already been taken.'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'email' => 'Email',
            'password' => 'Password',
            'isAdmin' => 'Is Admin',
            'photo' => 'Photo',
            'created_at' => 'Created At',
            'auth_key' => 'Auth Key',
            'verification_token' => 'Verification Token',
        ];
    }
    public function isAdmin()
    {
        return $this->isAdmin == 1;
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }


    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }


    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }


    public static function findByUsername($username)
    {
        return User::find()->where(['username'=>$username])->one();
    }
    public static function findByEmail($email)
    {
        return User::find()->where(['email'=>$email])->one();
    }

    public function getId()
    {
        return $this->id;
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->generateAuthKey();
                $this->generateVerificationToken();
            }
            return true;
        }
        return false;
    }

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    public function setPassword($password)
    {
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }

    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }
    public function generateVerificationToken()
    {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    public function create()
    {
        return $this->save(false);
    }
}
