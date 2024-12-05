<?php

use yii\helpers\Url;

?>
<div class="admin-dashboard">
    <h1>Admin Dashboard</h1>
    <p>Welcome to the admin panel! Use the links below to manage the blog's content:</p>

    <div class="admin-actions">
        <a href="<?= Url::to(['article/create']) ?>" class="btn-link">
            Create Article
        </a>
        <a href="<?= Url::to(['category/create']) ?>" class="btn-link">
            Create Category
        </a>
        <a href="<?= Url::to(['tag/create']) ?>" class="btn-link">
            Create Tag
        </a>
    </div>
</div>
