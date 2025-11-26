<?php
namespace app\components\behaviors;

use yii\base\Behavior;
use yii\db\ActiveRecord;

class MessageBehaviors extends Behavior
{
    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'afterInsert',
            ActiveRecord::EVENT_AFTER_UPDATE => 'afterUpdate',
            ActiveRecord::EVENT_AFTER_DELETE => 'afterDelete',
        ];
    }
  
    public function afterInsert($event)
    {
        \Yii::$app->session->setFlash('success', 'Se ha creado correctamente el registro');
    }

    public function afterUpdate($event)
    {
        \Yii::$app->session->setFlash('success', 'Se ha actualizado correctamente el registro');
    }

    public function afterDelete($event)
    {
        \Yii::$app->session->setFlash('success', 'Se ha eliminado correctamente el registro');
    }
}
