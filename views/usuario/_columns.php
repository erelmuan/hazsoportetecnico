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
        'attribute'=>'nombreusuario',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'contrasenia',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'nombreapellido',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'descripcion',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'activo',
        'format'=>'boolean',
    ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'imagen',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'access_token',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'token_expire_at',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'must_change_password',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'reset_token',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'reset_token_expire',
    // ],
    [
        'class' => MyActionColumn::class,
        'template' => '{view} {update} {delete}',
    ],

];
