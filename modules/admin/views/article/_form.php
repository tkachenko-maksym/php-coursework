<?php

use app\models\Category;
use app\models\Tag;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Article $model */
/** @var yii\widgets\ActiveForm $form */
/** @var app\models\Article $categories */
/** @var app\models\Article $tags */
?>

<div class="article-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'content')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'date')->textInput() ?>

    <?= $form->field($model, 'category_id')->dropDownList(
        $categories,
        ['prompt' => 'Select Category']
    ) ?>

    <?= $form->field($model, 'tagIds')->checkboxList(
        $tags,
        [
            'item' => function($index, $label, $name, $checked, $value) {
                return "<div class='form-control mb-2'>" .
                    "<label class='checkbox-inline'>" .
                    Html::checkbox($name, $checked, ['value' => $value]) .
                    " <span class='ml-2'>{$label}</span>" .
                    "</label></div>";
            }
        ]
    ) ?>

    <?= $form->field($model, 'image')->fileInput([
        'maxlength' => true,
        'id' => 'image-input',
    ]) ?>

    <?php if ($model->image): ?>
        <div class="form-group">
            <img id="image-preview" src="<?= $model->getImage() ?>" alt="" width="200">
            <?= Html::hiddenInput('delete_image', 0) ?>
            <?= Html::checkbox('delete_image', false, ['label' => 'Delete image']) ?>
        </div>
    <?php else: ?>
        <div class="form-group">
            <img id="image-preview" src="" alt="" style="display: none;" width="200">
        </div>
    <?php endif; ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
<!--    update preview image-->
    <?php
    $this->registerJs(<<<JS
    document.getElementById('image-input').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('image-preview');
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    });
JS
    );
    ?>
</div>
