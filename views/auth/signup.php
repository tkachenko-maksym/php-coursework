<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap5\ActiveForm */

/* @var $model app\models\LoginForm */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;


$this->title = 'Register now!';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1 align="center"><?= Html::encode($this->title) ?></h1>

    <p align="center">Please fill out the following fields to register:</p>
    <div class="row justify-content-center align-items-center">
        <div class="col-lg-5">

            <?php $form = ActiveForm::begin([
                'id' => 'signup-form',
                'layout' => 'horizontal',
                'fieldConfig' => [
                    'template' => "{label}\n{input}\n{error}",
                    'labelOptions' => ['class' => 'col-lg-1 col-form-label mr-lg-3'],
                    'inputOptions' => ['class' => 'col-lg-3 form-control'],
                    'errorOptions' => ['class' => 'col-lg-7 invalid-feedback'],
                ],
            ]); ?>

            <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

            <?= $form->field($model, 'email')->textInput() ?>

            <?= $form->field($model, 'password')->passwordInput() ?>

            <div class="form-group">
                <div class="col-lg-offset-1 col-lg-11">
                    <?= Html::submitButton('Sign up', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                </div>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
</div>
