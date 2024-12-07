<?php

namespace tests\unit\models;

use app\models\LoginForm;
use app\models\SignupForm;
use app\models\User;
use Codeception\Test\Unit;
use Yii;

class AuthTest extends Unit
{
    protected function _before()
    {
        // Clean up the user table before each test
        User::deleteAll();
    }

    protected function _after()
    {
        // Clean up after tests
        User::deleteAll();
    }

    protected function createTestUser($email = 'test@example.com', $username = 'testuser')
    {
        $user = new User();
        $user->username = $username;
        $user->email = $email;
        $user->setPassword('password123');
        $user->generateAuthKey();
        $user->generateVerificationToken();
        $user->created_at = time();
        $user->save();
        return $user;
    }

    public function testLoginSuccess()
    {
        $user = $this->createTestUser();

        $model = new LoginForm();
        $model->email = 'test@example.com';
        $model->password = 'password123';

        $this->assertTrue($model->login());
        $this->assertFalse(Yii::$app->user->isGuest);
        $this->assertEquals($user->id, Yii::$app->user->id);
    }

    public function testLoginWrongPassword()
    {
        $this->createTestUser();

        $model = new LoginForm();
        $model->email = 'test@example.com';
        $model->password = 'wrongpassword';

        $this->assertFalse($model->login());
        $this->assertTrue(Yii::$app->user->isGuest);
        $this->assertNotEmpty($model->getErrors('password'));
    }

    public function testLoginWrongEmail()
    {
        $this->createTestUser();

        $model = new LoginForm();
        $model->email = 'wrong@example.com';
        $model->password = 'password123';

        $this->assertFalse($model->login());
        $this->assertTrue(Yii::$app->user->isGuest);
        $this->assertNotEmpty($model->getErrors('password'));
    }

    public function testLoginValidation()
    {
        $model = new LoginForm();

        // Test empty fields
        $this->assertFalse($model->validate());
        $this->assertNotEmpty($model->getErrors('email'));
        $this->assertNotEmpty($model->getErrors('password'));

        // Test invalid email format
        $model->email = 'invalid-email';
        $model->password = 'password123';
        $this->assertFalse($model->validate());
        $this->assertNotEmpty($model->getErrors('email'));
    }

    public function testSignupSuccess()
    {
        $model = new SignupForm();
        $model->username = 'newuser';
        $model->email = 'newuser@example.com';
        $model->password = 'password123';

        $user = $model->signup();

        $this->assertInstanceOf(User::class, $user);
        $this->assertFalse($user->isNewRecord);
        $this->assertEquals('newuser', $user->username);
        $this->assertEquals('newuser@example.com', $user->email);
        $this->assertNotEmpty($user->password);
        $this->assertNotEmpty($user->auth_key);
        $this->assertNotEmpty($user->verification_token);
    }

    public function testSignupValidation()
    {
        $model = new SignupForm();

        // Test empty fields
        $this->assertFalse($model->validate());
        $this->assertNotEmpty($model->getErrors('username'));
        $this->assertNotEmpty($model->getErrors('email'));
        $this->assertNotEmpty($model->getErrors('password'));

        // Test invalid email
        $model->username = 'testuser';
        $model->email = 'invalid-email';
        $model->password = 'pass123';
        $this->assertFalse($model->validate());
        $this->assertNotEmpty($model->getErrors('email'));

        // Test short username
        $model->username = 'a';
        $model->email = 'valid@example.com';
        $this->assertFalse($model->validate());
        $this->assertNotEmpty($model->getErrors('username'));

        // Test short password
        $model->username = 'testuser';
        $model->password = '12345';
        $this->assertFalse($model->validate());
        $this->assertNotEmpty($model->getErrors('password'));
    }

    public function testSignupDuplicateEmail()
    {
        // Create first user
        $this->createTestUser();

        // Try to create second user with same email
        $model = new SignupForm();
        $model->username = 'differentuser';
        $model->email = 'test@example.com';
        $model->password = 'password123';

        $this->assertFalse($model->validate());
        $this->assertNotEmpty($model->getErrors('email'));
    }

    public function testSignupDuplicateUsername()
    {
        // Create first user
        $this->createTestUser();

        // Try to create second user with same username
        $model = new SignupForm();
        $model->username = 'testuser';
        $model->email = 'different@example.com';
        $model->password = 'password123';

        $this->assertFalse($model->validate());
        $this->assertNotEmpty($model->getErrors('username'));
    }

    public function testUserPasswordValidation()
    {
        $user = $this->createTestUser();

        $this->assertTrue($user->validatePassword('password123'));
        $this->assertFalse($user->validatePassword('wrongpassword'));
    }

    public function testUserFindByEmail()
    {
        $user = $this->createTestUser();

        $foundUser = User::findByEmail('test@example.com');
        $this->assertInstanceOf(User::class, $foundUser);
        $this->assertEquals($user->id, $foundUser->id);

        $notFoundUser = User::findByEmail('nonexistent@example.com');
        $this->assertNull($notFoundUser);
    }

    public function testUserFindByUsername()
    {
        $user = $this->createTestUser();

        $foundUser = User::findByUsername('testuser');
        $this->assertInstanceOf(User::class, $foundUser);
        $this->assertEquals($user->id, $foundUser->id);

        $notFoundUser = User::findByUsername('nonexistentuser');
        $this->assertNull($notFoundUser);
    }
}