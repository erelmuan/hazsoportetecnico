<?php
use yii\helpers\Url;
use yii\helpers\Html;
use app\components\MyActionColumn;
return [

    [
        'class' => MyActionColumn::class,
        'template' => '{view} {update} {delete} {attachments} {log}',
        'buttons' => [
        'attachments' => function($url, $model, $key) {
            $icon = '<i class="fas fa-paperclip"></i>';
            return Html::a($icon, Url::to(['adjunto/index', 'id_equipo' => $model->id]), [
                'class' => 'btn btn-secondary btn-circle btn-sm',
                'title' => 'Archivos adjuntos',
                'data-pjax' => '0'
            ]);
        },
        'log' => function($url, $model, $key) {
            $icon = '<i class="fas fa-file-alt"></i>';
            return Html::a($icon, Url::to(['log/index', 'id_equipo' => $model->id]), [
                'class' => 'btn btn-info btn-circle btn-sm',
                'title' => 'Ver log',
                'data-pjax' => '0'
            ]);
        },
    ],
    ],

];
