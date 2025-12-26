<?php
namespace app\base;

use Yii;
use yii\helpers\ArrayHelper;

class Model extends \yii\base\Model
{
    /**
     * Creates and populates a set of models.
     *
     * @param string $modelClass
     * @param array $multipleModels
     * @return array
     */
    public static function createMultiple($modelClass, $multipleModels = [])
    {
        $model    = new $modelClass;
        $formName = $model->formName();
        $post     = Yii::$app->request->post($formName);
        $models   = [];

        if (! empty($multipleModels)) {
            $keys = array_keys(ArrayHelper::map($multipleModels, 'id', 'id'));
            $multipleModels = array_combine($keys, $multipleModels);
        }

        if ($post && is_array($post)) {
            foreach ($post as $i => $item) {
                if (isset($item['id']) && !empty($item['id']) && isset($multipleModels[$item['id']])) {
                    $models[] = $multipleModels[$item['id']];
                } else {
                    $models[] = new $modelClass;
                }
            }
        }

        unset($model, $formName, $post);
        return $models;
    }

    /**
     * Loads multiple models with data.
     *
     * @param array $models
     * @param array $data
     * @param string $formName
     * @return bool
     */
    public static function loadMultiple($models, $data, $formName = null)
    {
        if ($formName === null) {
            $first = reset($models);
            if ($first === false) {
                return false;
            }
            $formName = $first->formName();
        }

        $success = false;
        foreach ($models as $i => $model) {
            if (isset($data[$formName][$i])) {
                if ($model->load($data[$formName][$i], '')) {
                    $success = true;
                }
            }
        }

        return $success;
    }

    /**
     * Validates multiple models.
     *
     * @param array $models
     * @param array $attributeNames
     * @return bool
     */
    public static function validateMultiple($models, $attributeNames = null)
    {
        $valid = true;
        foreach ($models as $model) {
            $valid = $model->validate($attributeNames) && $valid;
        }

        return $valid;
    }
}
