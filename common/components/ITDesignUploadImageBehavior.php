<?php
namespace common\components;

use mongosoft\file\UploadImageBehavior;
use Yii;
use yii\helpers\ArrayHelper;
use yii\imagine\Image;

class ITDesignUploadImageBehavior extends UploadImageBehavior {

    // save images as is
    var $imageResize = false;

    protected function afterUpload()
    {
        parent::afterUpload();
        if ($this->imageResize && is_array($this->imageResize)) {
            $width = ArrayHelper::getValue($this->imageResize, 'width', false);
            $height = ArrayHelper::getValue($this->imageResize, 'height', false);
            $quality = ArrayHelper::getValue($this->imageResize, 'quality', 100);

            $needResize = true;
            $needResize = $needResize && ($width !== false || $height !== false);

            $path = $this->getUploadPath($this->attribute);
            $image = Image::getImagine()->open($path);
            $image_w = $image->getSize()->getWidth();
            $image_h = $image->getSize()->getHeight();
            $needResize = $needResize && ($image_w > $width || $image_h > $height);

            if ($needResize) {
                $ratio = $image_w / $image_h;
                if ($width) {
                    $height = ceil($width / $ratio);
                } else {
                    $width = ceil($height * $ratio);
                }
                ini_set('memory_limit', '512M');
                Image::thumbnail($path, $width, $height)->save($path, ['quality' => $quality]);
            }
        }
    }

}