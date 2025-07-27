<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\components;

use app\helpers\StatusHelper;
use Yii;
use yii\web\ServerErrorHttpException;
use yii\rest\Action;
use yii\db\Expression;

/**
 * DeleteAction implements the API endpoint for deleting a model.
 *
 * For more details and usage information on DeleteAction, see the [guide article on rest controllers](guide:rest-controllers).
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class DeleteAction extends Action
{
    /**
     * Deletes a model.
     * @param mixed $id id of the model to be deleted.
     * @throws ServerErrorHttpException on failure.
     */
    public function run($id)
    {
        $model = $this->findModel($id);
        $model->status_active = StatusHelper::INACTIVE;
        $model->deleted_by = Yii::$app->user->id;
        $model->deleted_at = new Expression('NOW()');

        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id, $model);
        }

        if ($model->update() === false) {
            // var_dump($model->getFirstErrors()); die();
            throw new ServerErrorHttpException('Failed to delete the object for unknown reason.');
        }

        Yii::$app->getResponse()->setStatusCode(204);
    }
}
