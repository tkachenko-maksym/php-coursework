<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

class ImageUpload extends Model {
    public $image;
    private $uploadPath;

    public function __construct()
    {
        parent::__construct();
        $this->uploadPath = Yii::getAlias('@webroot') . '/uploads/';
        FileHelper::createDirectory($this->uploadPath);
    }

    public function rules()
    {
        return [
            [['image'], 'required'],
            [['image'], 'file', 'extensions' => 'jpg,png']
        ];
    }

    public function uploadFile(UploadedFile $file, $currentImage)
    {
        $this->image = $file;

        if ($this->validate()) {
            $this->deleteCurrentImage($currentImage);
            return $this->saveImage();
        }
        return false;
    }

    private function saveImage()
    {
        $filename = $this->generateFilename();
        return $this->image->saveAs($this->uploadPath . $filename) ? $filename : false;
    }

    private function generateFilename()
    {
        return strtolower(md5(uniqid($this->image->baseName)) . '.' . $this->image->extension);
    }

    public function deleteCurrentImage($currentImage)
    {
        if ($this->fileExists($currentImage)) {
            unlink($this->uploadPath . $currentImage);
        }
    }

    private function fileExists($currentImage)
    {
        return !empty($currentImage) && file_exists($this->uploadPath . $currentImage);
    }
}