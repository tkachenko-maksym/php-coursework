<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;

?>

<div class="main-content">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <?php if (!empty($articles)): ?>
                    <?php foreach ($articles as $article): ?>
                        <article class="post">
                            <div class="post-thumb">
                                <a href="<?= Url::toRoute(['site/view', 'id' => $article->id]); ?>">
                                    <?= Html::img($article->getImage(), ['alt' => $article->title]) ?>
                                </a>
                                <a href="<?= Url::toRoute(['site/view', 'id' => $article->id]); ?>"
                                   class="post-thumb-overlay">
                                    <div class="view-post">View Post</div>
                                </a>
                            </div>
                            <div class="post-content">
                                <header class="entry-header">
                                    <?php if ($article->category): ?>
                                        <h6 class="category-link">
                                            <a href="<?= Url::toRoute(['site/category', 'id' => $article->category->id]) ?>">
                                                <?= Html::encode($article->category->title) ?>
                                            </a>
                                        </h6>
                                    <?php endif; ?>
                                    <h1 class="entry-title">
                                        <a href="<?= Url::toRoute(['site/view', 'id' => $article->id]); ?>">
                                            <?= Html::encode($article->title) ?>
                                        </a>
                                    </h1>
                                </header>

                                <div class="entry-content">
                                    <?= Html::encode($article->description) ?>
                                    <div class="continue-reading">
                                        <a href="<?= Url::toRoute(['site/view', 'id' => $article->id]); ?>"
                                           class="btn-link">
                                            Continue Reading
                                        </a>
                                    </div>
                                </div>

                                <div class="post-meta">
                                    <span class="post-author">
                                        By <?= Html::encode($article->author->username) ?>
                                    </span>
                                    <span class="post-date">
                                        On <?= $article->getDate() ?>
                                    </span>
                                    <div class="post-stats">
                                        <div class="post-stats">
                                            <span>Views: <?= $article->viewed ?? 0 ?></span>
                                            <span>Comments: <?= $article->getCommentCount() ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>

                    <div class="pagination-wrap">
                        <?= LinkPager::widget([
                            'pagination' => $pagination,
                            'options' => ['class' => 'pagination'],
                            'activePageCssClass' => 'active',
                        ]); ?>
                    </div>
                <?php else: ?>
                    <div class="no-posts">
                        <p>No articles found.</p>
                    </div>
                <?php endif; ?>
            </div>

            <div class="col-md-4">
                <?= $this->render('/partials/sidebar', [
                    'popular' => $popular,
                    'recent' => $recent,
                    'categories' => $categories,
                ]); ?>
            </div>
        </div>
    </div>
</div>