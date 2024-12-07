<?php

namespace tests\unit\models;

use app\models\Comment;

class CommentTest extends \Codeception\Test\Unit
{
    public function testCommentCreation()
    {
        $comment = new Comment();
        $comment->user_id = 1;
        $comment->article_id = 1;
        $comment->text = 'This is a test comment';
        $comment->status = 1;

        $this->assertTrue($comment->save());
        $this->assertNotNull(Comment::findOne($comment->id));
    }

    public function testGetArticle()
    {
        $comment = Comment::findOne(1);
        $this->assertNotNull($comment->getArticle()->one());
    }

    public function testGetUser()
    {
        $comment = Comment::findOne(1);
        $this->assertNotNull($comment->getUser()->one());
    }
}
