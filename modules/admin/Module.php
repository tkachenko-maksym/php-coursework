<?php

namespace app\modules\admin;

use app\modules\admin\components\AdminAccessControl;

/**
 * admin module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $layout = '/admin_main.php';
    public $controllerNamespace = 'app\modules\admin\controllers';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AdminAccessControl::class,
            ]
        ];
    }
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
