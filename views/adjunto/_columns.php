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
        'attribute' => 'tipocategoria',
        'value' => function($model) {
            return \app\models\Adjunto::optsTipocategoria()[$model->tipocategoria] ?? $model->tipocategoria;
        },
        'filter' => \app\models\Adjunto::optsTipocategoria(),
    ],
    [
        'attribute' => 'tipoarchivo',
        'value' => function($model) {
            return \app\models\Adjunto::optsTipoarchivo()[$model->tipoarchivo] ?? $model->tipoarchivo;
        },
        'filter' => \app\models\Adjunto::optsTipoarchivo(),
    ],
    [
        'class' => MyActionColumn::class,
        'template' => '{view} {update} {delete}',
    ],

];
