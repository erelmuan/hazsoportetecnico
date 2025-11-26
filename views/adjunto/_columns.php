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
        'attribute'=>'nombreoriginal',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'observacion',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'tipocategoria',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'tipoarchivo',
    ],
    [
        'class' => MyActionColumn::class,
        'template' => '{view} {update} {delete}',
    ],

];
