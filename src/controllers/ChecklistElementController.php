<?php
/**
 * @package yii2-kanban
 * @author Simon Karlen <simi.albi@outlook.com>
 * @copyright Copyright © 2019 Simon Karlen
 */

namespace simialbi\yii2\kanban\controllers;


use simialbi\yii2\kanban\models\ChecklistElement;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Class ChecklistElementController
 * @package simialbi\yii2\kanban\controllers
 *
 * @property-read \simialbi\yii2\kanban\Module $module
 */
class ChecklistElementController extends Controller
{
    /**
     * {@inheritDoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['set-done'],
                        'roles' => ['@']
                    ]
                ]
            ]
        ];
    }

    /**
     * Set checklist element to done status
     * @param integer $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionSetDone($id)
    {
        $model = $this->findModel($id);

        $model->is_done = true;
        $model->save();

        return $this->renderAjax('/task/item', [
            'model' => $model->task,
            'users' => call_user_func([Yii::$app->user->identityClass, 'findIdentities']),
            'statuses' => $this->module->statuses
        ]);
    }

    /**
     * Finds the model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return ChecklistElement the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ChecklistElement::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('yii', 'Page not found.'));
        }
    }
}
