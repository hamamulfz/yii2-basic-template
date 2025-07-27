<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "api_web_log".
 *
 * @property int $id
 * @property string|null $ipclient
 * @property string|null $app_name
 * @property string|null $ws_type
 * @property string|null $request
 * @property string|null $response
 * @property string $createdon
 * @property int|null $createdby
 */
class ApiLog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'api_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['params', 'body', 'endpoint', 'method', 'response', 'status_code'], 'string'],
            [['created_at'], 'safe'],
            [['created_by'], 'integer'],
            [['ip_client'], 'string', 'max' => 32],
            [['app_name', 'ws_type'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ip_client' => 'Ipclient',
            'app_name' => 'App Name',
            'ws_type' => 'Ws Type',
            'request' => 'Request',
            'response' => 'Response',
            'createdon' => 'Createdon',
            'createdby' => 'Createdby',
        ];
    }
}
