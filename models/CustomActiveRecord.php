<?php
/**
 * User: Taufiq Rahman (Rahman.taufiq@gmail.com)
 * Date: 11/09/20
 * Time: 08.39
 */
namespace app\models;

use Yii;

/**
 * @property int|null $created_by
 * @property string $created_at
 * @property int|null $updated_by
 * @property string|null $updated_at
 * @property int|null $deleted_by
 * @property string|null $deleted_at
 */

class CustomActiveRecord extends \yii\db\ActiveRecord
{
    public function beforeSave($insert)
    {
        date_default_timezone_set('Asia/Jakarta');
        if (is_a(Yii::$app, '\yii\web\Application')) {
            if ($this->isNewRecord) {
                // $this->created_at = new \yii\db\Expression('NOW()'); //sudah di buat di DB
                if (!Yii::$app->user->isGuest) {
                    $this->created_by = Yii::$app->user->id;
                    $this->updated_by = Yii::$app->user->id;
                } else {
                    $this->created_by = 1;
                    $this->updated_by = 1;
                }
            } else {
                if (!Yii::$app->user->isGuest) {
                    $this->updated_by = Yii::$app->user->id;
                    $this->updated_at = date('Y-m-d H:i:s');
                } else {
                    $this->updated_by = 1;
                }
            }
        }
        return parent::beforeSave($insert);
    }
}
