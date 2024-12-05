<?php

namespace app\models;

use Yii;
use yii\data\Pagination;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;


/**
 * This is the model class for table "article".
 *
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property string $content
 * @property string $date
 * @property string $image
 * @property integer $viewed
 * @property integer $user_id
 * @property integer $status
 * @property integer $category_id
 *
 * @property ArticleTag[] $articleTags
 * @property Comment[] $comments
 */
class Article extends ActiveRecord
{
    protected $tagIds;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'article';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'description', 'content'], 'required'],
            [['title', 'description', 'content'], 'string'],
            [['date'], 'date', 'format' => 'php:Y-m-d'],
            [['date'], 'default', 'value' => date('Y-m-d')],
            [['title'], 'string', 'max' => 255],
            [['category_id'], 'number'],
            [['tags'], 'each', 'rule' => ['integer']], // For array of tag IDs
            [['tags'], 'safe'],
            [['image'], 'file', 'extensions' => 'jpg, jpeg, png']

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'description' => 'Description',
            'content' => 'Content',
            'date' => 'Date',
            'image' => 'Image',
            'viewed' => 'Viewed',
            'user_id' => 'User ID',
            'status' => 'Status',
            'category_id' => 'Category ID',
        ];
    }
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if (empty($this->date)) {
                $this->date = date('Y-m-d');
            }
            return true;
        }
        return false;
    }
    public function saveArticle()
    {
        $this->user_id = Yii::$app->user->id;
        return $this->save(false);
    }

    public function saveImage($filename)
    {
        $this->image = $filename;
        return $this->save(false);
    }

    public function getImage()
    {
        $imagesPath = Yii::getAlias('@web') . '/uploads/';
        return ($this->image) ? $imagesPath . $this->image : $imagesPath . '/no_image.jpg';
    }

    public function deleteImage()
    {
        $imageUploadModel = new ImageUpload();
        $imageUploadModel->deleteCurrentImage($this->image);
    }

    public function beforeDelete()
    {
        $this->deleteImage();
        return parent::beforeDelete(); // TODO: Change the autogenerated stub
    }

    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    public function saveCategory($category_id)
    {
        $category = Category::findOne($category_id);
        if ($category != null) {
            $this->link('category', $category);
            return true;
        }
    }
    public function getTagIds()
    {
        return $this->tagIds ?? $this->getSelectedTags();
    }

    public function setTagIds($value)
    {
        $this->tagIds = $value;
    }
    public function getTags()
    {
        return $this->hasMany(Tag::className(), ['id' => 'tag_id'])
            ->viaTable('article_tag', ['article_id' => 'id']);
    }

    public function getSelectedTags()
    {
        $selectedIds = $this->getTags()->select('id')->asArray()->all();
        return ArrayHelper::getColumn($selectedIds, 'id');
    }

    public function saveTags($tagIds)
    {
        if (is_array($tagIds)) {
            $this->clearCurrentTags();

            foreach ($tagIds as $tagId) {
                $tag = Tag::findOne($tagId);
                if ($tag) {
                    $articleTag = new ArticleTag();
                    $articleTag->article_id = $this->id;
                    $articleTag->tag_id = $tag->id;
                    $articleTag->save();
                }
            }
        }
    }

    public function clearCurrentTags()
    {
        ArticleTag::deleteAll(['article_id' => $this->id]);
    }

    public function getDate()
    {
        return Yii::$app->formatter->asDate($this->date);
    }

    public static function getAll($pageSize = 5)
    {
        // build a DB query to get all articles
        $query = Article::find();

        // get the total number of articles (but do not fetch the article data yet)
        $count = $query->count();

        // create a pagination object with the total count
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => $pageSize]);

        // limit the query using the pagination and retrieve the articles
        $articles = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        $data['articles'] = $articles;
        $data['pagination'] = $pagination;

        return $data;
    }

    public static function getPopular()
    {
        return Article::find()->orderBy('viewed desc')->limit(3)->all();
    }

    public static function getRecent()
    {
        return Article::find()->orderBy('date asc')->limit(4)->all();
    }

    public function getComments()
    {
        return $this->hasMany(Comment::className(), ['article_id' => 'id']);
    }

    public function getArticleComments()
    {
        return $this->getComments()->where(['status' => 1])->all();
    }
    public function getCommentCount()
    {
        return $this->hasMany(Comment::className(), ['article_id' => 'id'])->count();
    }
    public function getAuthor()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function viewedCounter()
    {
        $this->viewed += 1;
        return $this->save(false);
    }
    public function getSharingUrls()
    {
        $url = Url::to(['site/view', 'id' => $this->id], true);
        $title = urlencode($this->title);

        return [
            'facebook' => "https://www.facebook.com/sharer/sharer.php?u={$url}",
            'twitter' => "https://twitter.com/intent/tweet?url={$url}&text={$title}",
            'linkedin' => "https://www.linkedin.com/sharing/share-offsite/?url={$url}"
        ];
    }
}
