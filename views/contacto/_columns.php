<?php
use yii\helpers\Url;
use app\components\MyActionColumn;

return [

    [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
    ],
        // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'id',
    // ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'nombre',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'telefono',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'email',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'cargo',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'id_proveedor',
    ],
    [
        'class' => MyActionColumn::class,
        'template' => '{view} {update} {delete}',
    ],

];
