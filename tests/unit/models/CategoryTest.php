<?php

namespace tests\unit\models;

use app\models\Category;

class CategoryTest extends \Codeception\Test\Unit
{
    public function testGetArticlesCount()
    {
        $category = Category::findOne(1); 
        $this->assertNotNull($category, 'Category should exist.');

        $count = $category->getArticlesCount();
        $this->assertIsInt($count, 'Articles count should return an integer.');
    }

    public function testGetCategoryTitle()
    {
        $category = Category::findOne(1);
        $this->assertNotNull($category, 'Category should exist.');

        $title = Category::getCategoryTitle(1);
        $this->assertEquals($category->title, $title, 'Category title should match.');
    }
}
