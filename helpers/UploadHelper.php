<?php

/**
 * User: Aziz (mufti.aziz@gmail.com)
 * Date: 2025/04/02
 * Time: 16.30
 */

namespace app\helpers;
use app\models\IntFiles;
use Yii;

class UploadHelper
{
    static function convertType($type) {
        $convertList = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
        ];
        return $convertList[$type];
    }

    static function upload($params) {
        $isUrl=@$params['isUrl'];
        if ($isUrl) {
                $imageUrl = @$params['upload'];
                $res= UploadHelper::uploadByUrl($imageUrl);
                if ($res[0]) {
                    $model = new IntFiles();
                    $model->source = @$params['source'];
                    $model->report_id = @$params['report_id'];
                    $model->progress_id = @$model->progress_id;
                    $model->created_at = date('Y-m-d H:i:s'); 
                    $model->file_url = $res[2];
                    if($model->save()) {
                        return [true, $model];
                    }else{
                        return [false,$model->getErrors()];
                    }
                } else {
                    return [false,['Failed to save the image.']];
                }
        }
        $model = new IntFiles();
        $fileUpload = \yii\web\UploadedFile::getInstanceByName('upload');

        if ($fileUpload->size > Yii::$app->params['max_file_size'])
        {
            return [false, 'File too Big, over '. Utils::fileSize(Yii::$app->params['max_file_size'])];
        }
        $model->report_id = @$params['report_id'];
        $model->source = @$params['source'];
        $model->progress_id = @$params['progress_id'];
        if(is_object($fileUpload)){
                $fileName = date('YmdHis') . '-'.$fileUpload->name;
                $fileImageDir = Yii::$app->params['storageLoc'];
                $model->source = @$params['source'];
                $model->file_url = $fileName;
                $model->report_id = $model->report_id;
                $model->created_at = date('Y-m-d H:i:s');
                if($model->save()) {
                    $fileUpload->saveAs($fileImageDir.'/'.$fileName);
                    return [true, $model];
                }else{
                    return [false,$model->getErrors()];
                }
                
        } else {
            return [false, ['Upload gagal, file harus berupa object.']];
        }
    }

    static function uploadByUrl($url) {
        /** Upload by URl - Taufiq Rahman -20.03.2025 **/
        $imageContents = @file_get_contents($url);
        if ($imageContents === false) {
            return [false, ['Failed to download image from URL:'. $url]];
        }
        $uploadPath= Yii::$app->params['storageLoc'];
        $imageName = date('YmdHis') . '-'.basename(parse_url($url, PHP_URL_PATH));
        $filePath = $uploadPath .'/'. $imageName;
        if (file_put_contents($filePath, $imageContents)) {
            return [true,['Image uploaded successfully:'.$filePath],$imageName];
        } else {
            return [false,['Failed to save the image.']];
        }
    }
}
