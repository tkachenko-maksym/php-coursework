<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Article $model */
/** @var app\models\Article $categories */
/** @var app\models\Article $tags */

$this->title = 'Create Article';
$this->params['breadcrumbs'][] = ['label' => 'Articles', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="article-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'categories' => $categories,
        'tags' => $tags,
    ]) ?>

</div>
