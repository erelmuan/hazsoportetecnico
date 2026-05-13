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
        'attribute'=>'tipocomponente',
        'value'=> 'tipocomponente.nombre'
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'serie',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'observacion',
    ],
    [
        'class'=>'\kartik\grid\BooleanColumn',
        'attribute'=>'baja',
        'trueLabel' => 'Sí',
        'falseLabel' => 'No',
         'trueIcon' => '<span class="label label-success" ">Sí</span>',
         'falseIcon' => '<span class="label label-danger" ">No</span>',
         'filterInputOptions' => [
           'class' => 'form-control',
            'prompt' => 'Seleccionar'
         ],
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'equipo',
        'value'=>'equipo.tipoequipo.nombre'
    ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'fechabaja',
    // ],

    [
        'class' => MyActionColumn::class,
        'template' => '{view} {update} {delete}',
    ],

];
