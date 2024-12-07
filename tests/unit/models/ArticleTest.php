<?php

namespace tests\unit\models;

use app\models\Article;
use app\models\Category;
use app\models\Tag;
use app\models\User;
use Codeception\Test\Unit;
use Yii;

class ArticleTest extends Unit
{
    protected function _before()
    {
        // Setup test application components
        Yii::$app->set('user', [
            'class' => 'yii\web\User',
            'identityClass' => 'app\models\User',
        ]);
    }

    public function testValidation()
    {
        $article = new Article();
        
        // Test required fields
        $this->assertFalse($article->validate());
        $this->assertArrayHasKey('title', $article->errors);
        $this->assertArrayHasKey('description', $article->errors);
        $this->assertArrayHasKey('content', $article->errors);

        // Test valid data
        $article->title = 'Test Title';
        $article->description = 'Test Description';
        $article->content = 'Test Content';
        $article->date = '2024-01-01';
        $this->assertTrue($article->validate());
    }

    public function testBeforeSave()
    {
        $article = new Article();
        $article->title = 'Test Title';
        $article->description = 'Test Description';
        $article->content = 'Test Content';
        
        // Test date auto-setting
        $this->assertTrue($article->beforeSave(true));
        $this->assertEquals(date('Y-m-d'), $article->date);
    }

    public function testGetImage()
    {
        $article = new Article();
        
        // Test default image
        $this->assertStringEndsWith('no_image.jpg', $article->getImage());
        
        // Test custom image
        $article->image = 'test.jpg';
        $this->assertStringEndsWith('test.jpg', $article->getImage());
    }

    public function testTagOperations()
    {
        $article = new Article([
            'title' => 'Test Article',
            'description' => 'Test Description',
            'content' => 'Test Content'
        ]);
        
        // Mock tags
        $tagIds = [1, 2];
        $article->setTagIds($tagIds);
        
        $this->assertEquals($tagIds, $article->getTagIds());
    }

    public function testViewedCounter()
    {
        $article = new Article([
            'title' => 'Test Article',
            'description' => 'Test Description',
            'content' => 'Test Content',
            'viewed' => 0
        ]);
        
        $article->viewedCounter();
        $this->assertEquals(1, $article->viewed);
    }
    
}