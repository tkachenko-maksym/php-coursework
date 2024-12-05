<?php

use yii\helpers\Url;
use yii\widgets\LinkPager;

?>

<div class="main-content">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="category-header bg-dark">
                    <h1 class="category-title">Search Results for "<?= $searchQuery ?>"</h1>
                    <div class="tag-meta">
                        <span class="tag-count"><?= count($articles) ?> articles</span>
                    </div>
                </div>
                <?php if (!empty($articles)): ?>
                    <div class="posts-grid">
                        <?php foreach ($articles as $article): ?>
                            <?= $this->render('/partials/post-card', ['article' => $article]); ?>
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
