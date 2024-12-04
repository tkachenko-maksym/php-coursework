<?php

namespace app\models;

use Yii;
use yii\data\Pagination;

/**
 * This is the model class for table "tag".
 *
 * @property int $id
 * @property string|null $title
 */
class Tag extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tag';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
        ];
    }
    public function getArticles()
    {
        return $this->hasMany(Article::class, ['id' => 'article_id'])
            ->viaTable('article_tag', ['tag_id' => 'id']);
    }

    public function getArticlesCount()
    {
        return $this->getArticles()->count();
    }

    public static function getTagTitle($id)
    {
        $tag = self::findOne($id);
        return $tag ? $tag->title : null;
    }

    public static function getArticlesByTag($id)
    {
        $query = Article::find()->joinWith('tags')->where(['tag.id' => $id]);

        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => 6]);

        $articles = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        return [
            'articles' => $articles,
            'pagination' => $pagination,
        ];
    }



}
