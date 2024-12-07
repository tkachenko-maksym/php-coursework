<?php

namespace tests\unit\models;

use app\models\Tag;

class TagTest extends \Codeception\Test\Unit
{
    private function createTestTag(): Tag
    {
        $tag = new Tag();
        $tag->title = 'Test Tag';
        $tag->save();
        return $tag;
    }

    public function testTagCreation()
    {
        $tag = $this->createTestTag();

        $this->assertTrue($tag->save());
        $this->assertNotNull(Tag::findOne($tag->id));
    }

    public function testGetArticlesCount()
    {
        $tag = $this->createTestTag();

        $this->assertIsInt($tag->getArticlesCount());
    }

    public function testGetTagTitle()
    {
        $tag = $this->createTestTag();
        $retrievedTitle = Tag::getTagTitle($tag->id);

        $this->assertNotNull($retrievedTitle);
        $this->assertEquals($tag->title, $retrievedTitle);
    }
}
