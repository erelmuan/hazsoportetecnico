<?php
use yii\helpers\Url;
use app\components\MyActionColumn;

return [

    [
    'class'=>'\kartik\grid\DataColumn',
    'attribute'=>'id',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'nombre',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'ninterno',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'correo',
        'format' => 'email', // genera <a href="mailto:...">
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'responsable',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'observacion',
    ],
    [
        'class' => MyActionColumn::class,
        'template' => '{view} {update} {delete}',
    ],
];
