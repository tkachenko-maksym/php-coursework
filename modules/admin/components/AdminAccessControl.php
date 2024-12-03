<?php
namespace app\modules\admin\components;

use Yii;
use yii\base\ActionFilter;
use yii\web\ForbiddenHttpException;

class AdminAccessControl extends ActionFilter
{
    public function beforeAction($action)
    {
        if (!$this->isUserAdmin()) {
            throw new ForbiddenHttpException('Access denied.');
        }
        return parent::beforeAction($action);
    }

    protected function isUserAdmin(): bool
    {
        return !Yii::$app->user->isGuest
            && Yii::$app->user->identity instanceof \app\models\User
            && Yii::$app->user->identity->isAdmin;
    }
}