<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
?>

<div class="main-content">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <article class="post post-detail">
                    <?php if ($article->image): ?>
                        <div class="post-thumb">
                            <?= Html::img($article->getImage(), ['alt' => $article->title]) ?>
                        </div>
                    <?php endif; ?>

                    <div class="post-content">
                        <header class="entry-header">
                            <div class="category-link">
                                <a href="<?= Url::toRoute(['site/category', 'id' => $article->category->id]) ?>">
                                    <?= Html::encode($article->category->title) ?>
                                </a>
                            </div>
                            <h1 class="entry-title"><?= Html::encode($article->title) ?></h1>
                            <div class="post-meta">
                                <span class="author">By <?= Html::encode($article->author->username) ?></span>
                                <span class="date">On <?= $article->getDate() ?></span>
                                <span class="views">Views: </i> <?= (int)$article->viewed ?></span>
                            </div>
                        </header>

                        <div class="entry-content">
                            <?= $article->content ?>
                        </div>

                        <?php if (!empty($article->tags)): ?>
                            <div class="post-tags">
                                <span class="tags-title">Tags:</span>
                                <?php foreach ($article->tags as $tag): ?>
                                    <a href="<?= Url::toRoute(['site/tag', 'id' => $tag->id]) ?>" class="tag">
                                        <?= Html::encode($tag->title) ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <div class="social-share">
                            <div class="share-title">Share this article</div>
                            <div class="share-buttons">
                                <a href="#" class="share-btn facebook">Facebook</a>
                                <a href="#" class="share-btn twitter">Twitter</a>
                                <a href="#" class="share-btn linkedin">Linkedin</a>
                            </div>
                        </div>
                    </div>
                </article>

                <section class="comments-section">
                    <h3 class="comments-title">Comments (<?= count($comments) ?>)</h3>

                    <div class="comments-list">
                        <?php foreach ($comments as $comment): ?>
                            <div class="comment">
                                <div class="comment-avatar">
<!--                                    <img src="--><?php //= $comment->user->getAvatar() ?><!--" alt="User avatar">-->
                                </div>
                                <div class="comment-content">
                                    <div class="comment-meta">
                                        <span class="comment-author"><?= Html::encode($comment->user->username) ?></span>
                                        <span class="comment-date"><?= $comment->getDate() ?></span>
                                    </div>
                                    <div class="comment-text">
                                        <?= Html::encode($comment->text) ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <?php if (!Yii::$app->user->isGuest): ?>
                        <div class="comment-form-wrap">
                            <h3>Leave a Comment</h3>
                            <?php $form = ActiveForm::begin([
                                'action' => ['site/comment', 'id' => $article->id],
                                'options' => ['class' => 'comment-form']
                            ]); ?>

                            <?= $form->field($commentForm, 'comment')
                                ->textarea(['rows' => 5])
                                ->label(false)
                            ?>

                            <div class="form-group">
                                <?= Html::submitButton('Post Comment', ['class' => 'btn btn-primary']) ?>
                            </div>

                            <?php ActiveForm::end(); ?>
                        </div>
                    <?php else: ?>
                        <div class="login-to-comment">
                            <p>Please <?= Html::a('login', ['/site/login']) ?> to leave a comment.</p>
                        </div>
                    <?php endif; ?>
                </section>
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