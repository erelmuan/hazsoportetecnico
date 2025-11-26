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
        'attribute'=>'pais',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'sitioweb',
        'format' => 'url',
    ],

    [
        'class' => MyActionColumn::class,
        'template' => '{view} {update} {delete}',
    ],

];
