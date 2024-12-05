<?php
use yii\helpers\Url;
use yii\helpers\StringHelper;

/* @var $article app\models\Article */
?>
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
            <?= StringHelper::truncateWords($article->description, 20) ?>
        </div>

        <div class="post-footer">
            <div class="post-stats">
                <span>Views: <?= $article->viewed ?></span>
                <span>Comments: <?= $article->getCommentCount() ?></span>
            </div>
        </div>
    </div>
</article>
