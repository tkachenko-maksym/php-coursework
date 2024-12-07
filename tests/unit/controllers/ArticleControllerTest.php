<?php

namespace tests\unit\controllers;


use app\modules\admin\controllers\ArticleController;
use app\models\Article;
use Yii;

class ArticleControllerTest extends \Codeception\Test\Unit
{
    private $article;
    protected function setUp(): void
    {
        parent::setUp();
        Yii::$app->controller = new ArticleController('article', Yii::$app);

        $this->article = $this->createTestArticle([
            'title' => 'Test Article',
            'description' => 'Test Description',
            'content' => 'Test Content',
            'date' => '2024-01-01',
            'category_id' => 1,
        ]);
    }

    private function createTestArticle(array $data): Article
    {
        $article = new Article();
        $article->setAttributes($data);
        $article->save();
        return $article;
    }

    public function testCreateArticle()
    {
        Yii::$app->request->bodyParams = [
            'Article' => [
                'title' => 'New Article',
                'description' => 'New Description',
                'content' => 'New Content',
                'category_id' => 1,
                'date' => '2024-01-01',
            ],
        ];

        $response = Yii::$app->controller->actionCreate();

        $this->assertInstanceOf(\yii\web\Response::class, $response);
        $this->assertEquals(302, $response->statusCode);

        $article = Article::findOne(['title' => 'New Article']);
        $this->assertNotNull($article);
    }
    public function testUpdateArticle()
    {
        Yii::$app->request->bodyParams = [
            'Article' => [
                'title' => 'Updated Title',
                'description' => 'Updated Description',
                'content' => 'Updated Content',
                'date' => '2024-01-02',
                'category_id' => 2,
            ],
        ];

        Yii::$app->controller->actionUpdate($this->article->id);

        $updatedArticle = Article::findOne($this->article->id);
        $this->assertEquals('Updated Title', $updatedArticle->title);
        $this->assertEquals('Updated Description', $updatedArticle->description);
        $this->assertEquals(2, $updatedArticle->category_id);
    }
    public function testDeleteArticle()
    {
        $articleToDelete = $this->createTestArticle([
            'title' => 'Delete Test',
            'description' => 'Delete Description',
            'content' => 'Delete Content',
            'date' => '2024-01-01',
        ]);
        $this->assertNotNull(Article::findOne($articleToDelete->id));
        Yii::$app->controller->actionDelete($articleToDelete->id);
        $this->assertNull(Article::findOne($articleToDelete->id));
    }
}