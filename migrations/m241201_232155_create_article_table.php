<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%article}}`.
 */
class m241201_232155_create_article_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('article', [
            'id' => $this->primaryKey(),
            'title' => $this->string(),
            'description' => $this->text(),
            'content' => $this->text(),
            'date' => $this->date(),
            'image' => $this->string(),
            'viewed' => $this->integer(),
            'user_id' => $this->integer(),
            'status' => $this->integer(),
            'category_id' => $this->integer(),
        ]);

        // Add foreign key
        $this->addForeignKey(
            'fk-article-category_id',
            'article',
            'category_id',
            'category',
            'id',
            'CASCADE'
        );
    }

    public function down()
    {
        $this->dropForeignKey('fk-article-category_id', 'article');
        $this->dropTable('article');
    }
}
