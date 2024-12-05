<?php

namespace app\modules\admin\controllers;

use app\models\Article;
use app\models\ArticleSearch;
use app\models\Category;
use app\models\ImageUpload;
use app\models\Tag;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * ArticleController implements the CRUD actions for Article model.
 */
class ArticleController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Article models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ArticleSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Article model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }


    public function actionCreate()
    {
        $model = new Article();
        $categories = ArrayHelper::map(Category::find()->all(), 'id', 'title');
        $tags = ArrayHelper::map(Tag::find()->all(), 'id', 'title');

        if ($model->load(Yii::$app->request->post()) && $model->saveArticle()) {
            $tagIds = Yii::$app->request->post('Article')['tagIds'] ?? [];
            $model->saveTags($tagIds);

            if ($imageFile = UploadedFile::getInstance($model, 'image')) {
                $imageUpload = new ImageUpload();
                $filename = $imageUpload->uploadFile($imageFile, null);
                $model->saveImage($filename);
            }

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'categories' => $categories,
            'tags' => $tags,
        ]);
    }


    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $categories = ArrayHelper::map(Category::find()->all(), 'id', 'title');
        $tags = ArrayHelper::map(Tag::find()->all(), 'id', 'title');

        $currentImage = $model->image;
        if ($model->load(Yii::$app->request->post())) {
            $tagIds = Yii::$app->request->post('Article')['tagIds'] ?? [];
            $uploadedImage = UploadedFile::getInstance($model, 'image');
            if ($uploadedImage) {
                $imageUpload = new ImageUpload();
                $filename = $imageUpload->uploadFile($uploadedImage, $currentImage);
                $model->image = $filename; // Update the image
            } else {
                $model->image = $currentImage; // Keep the old image
            }
            if ($model->saveArticle()) {
                $model->saveTags($tagIds);

                if (Yii::$app->request->post('delete_image')) {
                    $model->deleteImage();
                    $model->image = null;
                    $model->save(false); // Save changes after deletion
                }
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        $model->tagIds = $model->getSelectedTags();
        return $this->render('update', [
            'model' => $model,
            'categories' => $categories,
            'tags' => $tags,
        ]);
    }

    /**
     * Deletes an existing Article model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Article model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Article the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Article::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionSetTags($id)
    {
        $article = $this->findModel($id);
        $selectedTags = $article->getSelectedTags();
        $tags = ArrayHelper::map(Tag::find()->all(), 'id', 'title');

        if (Yii::$app->request->isPost) {
            $tags = Yii::$app->request->post('tags');
            $article->saveTags($tags);
            return $this->redirect(['view', 'id' => $article->id]);
        }

        return $this->render('tags', [
            'selectedTags' => $selectedTags,
            'tags' => $tags
        ]);
    }
}
