<?php
use yii\helpers\Url;
use app\components\MyActionColumn;
use yii\helpers\Html;
return [
    [
        'class' => 'kartik\grid\CheckboxColumn',
        'width' => '20px',
    ],
    [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'id',
    ],

    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'tiempo',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'tipotiempo',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'condicion',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'modelo',
    ],

    [
        'attribute' => 'color',
        'format' => 'raw',
        'value' => function($model) {
            $c = $model->color ?: '#000';
            if (strpos($c, '#') !== 0) $c = '#'.$c;
            return Html::tag('span', '', [
                'style' => "display:inline-block; width:18px; height:18px; border:1px solid #ccc; margin-right:6px; vertical-align:middle; background:{$c};"
            ]) . Html::encode($c);
        },
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'descripcion',
    ],
    [
        'class' => MyActionColumn::class,
        'template' => '{view} {update} {delete}',
    ],


];
