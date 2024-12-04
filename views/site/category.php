<?php

use yii\helpers\Url;
use yii\widgets\LinkPager;

?>

<div class="main-content">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="category-header bg-dark">
                    <h1 class="category-title"><?= $categoryTitle ?></h1>
                </div>
                <?php if (!empty($articles)): ?>
                    <div class="posts-grid">
                        <?php foreach ($articles as $article): ?>
                            <article class="post-card">
                                <div class="post-image">
                                    <a href="<?= Url::toRoute(['site/view', 'id' => $article->id]); ?>">
                                        <img src="<?= $article->getImage(); ?>" alt="<?= $article->title ?>">
                                        <div class="post-overlay">
                                            <span>Read More</span>
                                        </div>
                                    </a>
                                </div>

                                <div class="post-content">
                                    <div class="post-meta">
                                        <span class="post-date"><?= $article->getDate(); ?></span>
                                        <span class="post-author">by <?= $article->author->username; ?></span>
                                    </div>

                                    <h2 class="post-title">
                                        <a href="<?= Url::toRoute(['site/view', 'id' => $article->id]); ?>">
                                            <?= $article->title ?>
                                        </a>
                                    </h2>

                                    <div class="post-excerpt">
                                        <?= \yii\helpers\StringHelper::truncateWords($article->description, 20) ?>
                                    </div>

                                    <div class="post-footer">
                                        <div class="post-stats">
                                            <span>Views: <?= $article->viewed ?></span>
                                            <span>Comments: <?= $article->getCommentCount() ?></span>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                    <div class="pagination-wrap">
                        <?= LinkPager::widget([
                            'pagination' => $pagination,
                            'options' => ['class' => 'pagination'],
                            'activePageCssClass' => 'active'
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
                    'categories' => $categories
                ]); ?>
            </div>
        </div>
    </div>
</div>
