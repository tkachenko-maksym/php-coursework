<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Article $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="article-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'content')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'date')->textInput() ?>


    <?= $form->field($model, 'image')->fileInput(['maxlength' => true]) ?>

    <?php if($model->image): ?>
        <div class="form-group">
            <img src="<?= $model->getImage() ?>" alt="" width="200">
            <?= Html::hiddenInput('delete_image', 0) ?>
            <?= Html::checkbox('delete_image', false, ['label' => 'Delete image']) ?>
        </div>
    <?php endif; ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
