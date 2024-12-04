<?php

namespace app\controllers;

use app\models\Article;
use app\models\Category;
use app\models\Comment;
use app\models\CommentForm;
use app\models\Tag;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $data = Article::getAll();
        $popular = Article::getPopular();
        $recent = Article::getRecent();
        $categories = Category::getAll();

        return $this->render('index', [
            'articles' => $data['articles'],
            'pagination' => $data['pagination'],
            'popular' => $popular,
            'recent' => $recent,
            'categories' => $categories,
        ]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
    public function actionEntry()
    {
        $model = new EntryForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            // Successful form validation
            return $this->render('entry-confirm', ['model' => $model]);
        } else {
            // If it's the first time or validation fails
            return $this->render('entry', ['model' => $model]);
        }
    }
    public function actionView($id)
    {
        $article = Article::findOne($id);
        $popular = Article::getPopular();
        $recent = Article::getRecent();
        $categories = Category::getAll();
        $comments = $article->getArticleComments();

        $commentForm = new CommentForm();

        $article->viewedCounter();

        return $this->render('single_article',[
            'article'=>$article,
            'popular'=>$popular,
            'recent'=>$recent,
            'categories'=>$categories,
            'comments'=>$comments,
            'commentForm'=>$commentForm
        ]);
    }
    public function actionCategory($id)
    {
        $data = Category::getArticlesByCategory($id);
        $popular = Article::getPopular();
        $recent = Article::getRecent();
        $categories = Category::getAll();
        $categoryTitle = Category::getCategoryTitle($id);
        return $this->render('category',[
            'categoryTitle'=>$categoryTitle,
            'articles'=>$data['articles'],
            'pagination'=>$data['pagination'],
            'popular'=>$popular,
            'recent'=>$recent,
            'categories'=>$categories,
        ]);
    }
    public function actionTag($id)
    {
        $data = Tag::getArticlesByTag($id);
        $popular = Article::getPopular();
        $recent = Article::getRecent();
        $categories = Category::getAll();
        $tagTitle = Tag::getTagTitle($id);

        return $this->render('tag', [
            'tagTitle' => $tagTitle,
            'articles' => $data['articles'],
            'pagination' => $data['pagination'],
            'popular' => $popular,
            'recent' => $recent,
            'categories' => $categories,
        ]);
    }
    public function actionComment($id)
    {
        $model = new CommentForm();
        $article = Article::findOne($id);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $comment = new Comment();
            $comment->text = $model->comment;
            $comment->user_id = Yii::$app->user->id;
            $comment->article_id = $article->id;
            $comment->status = 1;
            $comment->save();

            Yii::$app->session->setFlash('success', 'Comment added successfully!');
            return $this->redirect(['view', 'id' => $article->id]);
        }

        Yii::$app->session->setFlash('error', 'Error adding comment.');
        return $this->redirect(['view', 'id' => $article->id]);
    }
}

