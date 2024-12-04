<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>

<div class="sidebar">
    <?php if (!empty($popular)): ?>
        <aside class="widget">
            <h3 class="widget-title">Popular Posts</h3>
            <div class="popular-posts">
                <?php foreach($popular as $article): ?>
                    <div class="post-item">
                        <?php if ($article->image): ?>
                            <div class="post-thumb">
                                <a href="<?= Url::toRoute(['site/view', 'id'=>$article->id]) ?>">
                                    <?= Html::img($article->getImage(), [
                                        'alt' => $article->title,
                                        'class' => 'thumb-image'
                                    ]) ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        <div class="post-info">
                            <h4>
                                <a href="<?= Url::toRoute(['site/view', 'id'=>$article->id]) ?>">
                                    <?= Html::encode($article->title) ?>
                                </a>
                            </h4>
                            <span class="post-date"><?= $article->getDate() ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </aside>
    <?php endif; ?>

    <?php if (!empty($categories)): ?>
        <aside class="widget">
            <h3 class="widget-title">Categories</h3>
            <ul class="category-list">
                <?php foreach($categories as $category): ?>
                    <li>
                        <a href="<?= Url::toRoute(['site/category', 'id'=>$category->id]) ?>">
                            <?= Html::encode($category->title) ?>
                            <span class="post-count">(<?= $category->getArticlesCount() ?>)</span>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </aside>
    <?php endif; ?>

    <?php if (!empty($recent)): ?>
        <aside class="widget">
            <h3 class="widget-title">Recent Posts</h3>
            <ul class="recent-posts">
                <?php foreach($recent as $article): ?>
                    <li>
                        <a href="<?= Url::toRoute(['site/view', 'id'=>$article->id]) ?>">
                            <?= Html::encode($article->title) ?>
                        </a>
                        <span class="post-date"><?= $article->getDate() ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </aside>
    <?php endif; ?>
</div>