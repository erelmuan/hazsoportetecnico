<?php
namespace app\components;

use kartik\grid\ActionColumn;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

class MyActionColumn extends ActionColumn
{
    public $viewOptions = [
        'role' => 'modal-remote',
        // icon using Font Awesome
        'icon' => "<span class='btn btn-success btn-circle btn-sm'><i class='fas fa-eye'></i></span>",
    ];

    public $updateOptions = [
        'role' => 'modal-remote',
        'icon' => "<span class='btn btn-primary btn-circle btn-sm'><i class='fas fa-pen'></i></span>",
    ];

    public $deleteOptions = [
        'role' => 'modal-remote',
        'icon' => "<span class='btn btn-danger btn-circle btn-sm'><i class='fas fa-trash'></i></span>",
    ];

    public $desvincularOptions = [
        'role' => 'modal-remote',
        // icon usando Font Awesome (fa-link o fa-chain-broken si querés "desvincular")
        'icon' => "<span class='btn btn-warning btn-circle btn-sm'><i class='fas fa-link'></i></span>",
    ];

    public function init()
    {
        if ($this->template === null) {
            $this->template = '{view} {update} {delete}';
        }
        parent::init();
    }

    protected function initDefaultButtons()
    {
        // parent::initDefaultButtons();

        if (!isset($this->buttons['view'])) {
            $this->buttons['view'] = function ($url, $model, $key) {
                $icon = ArrayHelper::getValue($this->viewOptions, 'icon', '<i class="fas fa-eye"></i>');
                $options = $this->viewOptions;
                unset($options['icon']);
                $options['title'] = ArrayHelper::getValue($options, 'title', \Yii::t('app','Ver'));
                $options['data-pjax'] = ArrayHelper::getValue($options, 'data-pjax', '0');
                $href = $url ?: '#';
                return Html::a($icon ?: 'V', $href, $options);
            };
        }

        if (!isset($this->buttons['update'])) {
            $this->buttons['update'] = function ($url, $model, $key) {
                $icon = ArrayHelper::getValue($this->updateOptions, 'icon', '<i class="fas fa-pen"></i>');
                $options = $this->updateOptions;
                unset($options['icon']);
                $options['title'] = ArrayHelper::getValue($options, 'title', \Yii::t('app','Editar'));
                $options['data-pjax'] = ArrayHelper::getValue($options, 'data-pjax', '0');
                $href = $url ?: '#';
                return Html::a($icon ?: 'E', $href, $options);
            };
        }

        if (!isset($this->buttons['delete'])) {
            $this->buttons['delete'] = function ($url, $model, $key) {
                $iconFromOptions = ArrayHelper::getValue($this->deleteOptions, 'icon', false);

                $buttonHtml = $iconFromOptions ?: "<span class='btn btn-danger btn-circle btn-sm'><i class='fas fa-trash'></i></span>";

                $options = $this->deleteOptions;
                if (isset($options['icon'])) {
                    unset($options['icon']);
                }

                $options['title'] = ArrayHelper::getValue($options, 'title', 'Eliminar');
                // $options['role'] = ArrayHelper::getValue($options, 'role', 'modal-remote');
                // $options['data-request-method'] = ArrayHelper::getValue($options, 'data-request-method', 'post');
                $options['data-toggle'] = ArrayHelper::getValue($options, 'data-toggle', 'tooltip');

                // Evita que yii.js manipule el enlace si necesitas custom JS
                // $options['data-method'] = false;

                $options['data-confirm-title'] = ArrayHelper::getValue($options, 'data-confirm-title', 'Confirmar');
                $options['data-confirm-message'] = ArrayHelper::getValue($options, 'data-confirm-message', '¿Esta seguro de eliminar este elemento?');
                $options['data-confirm-cancel'] = ArrayHelper::getValue($options, 'data-confirm-cancel', 'Cancelar');
                $options['data-confirm-ok'] = ArrayHelper::getValue($options, 'data-confirm-ok', 'Aceptar');
                // $options['identifier'] = ArrayHelper::getValue($options, 'identifier', 'mi_identificador');
                $href = $url ?: '#';

                return Html::a($buttonHtml, $href, $options);
            };
        }

    }
}
