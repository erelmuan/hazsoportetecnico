<?php
use yii\helpers\Url;
use app\components\MyActionColumn;

return [
    [
        'class' => 'kartik\grid\CheckboxColumn',
        'width' => '20px',
    ],
    [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
    ],
        // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'id',
    // ],
    [
      //nombre
      'class'=>'\kartik\grid\DataColumn',
      'attribute'=>'fechaingreso',
      'label'=> 'Fecha de ingreso',
      'value'=>'fechaingreso',
      'format' => ['date', 'php:d/m/Y'],
      'filterInputOptions' => [
          'id' => 'fecha2',
          'class' => 'form-control',
          'autoclose'=>true,
          'format' => 'dd/mm/yyyy',
          'startView' => 'year',
          'placeholder' => 'd/m/aaaa'

       ]
    ],
    [
      //nombre
      'class'=>'\kartik\grid\DataColumn',
      'attribute'=>'fechaegreso',
      'label'=> 'Fecha de egreso',
      'value'=>'fechaegreso',
      'format' => ['date', 'php:d/m/Y'],
      'filterInputOptions' => [
          'id' => 'fecha2',
          'class' => 'form-control',
          'autoclose'=>true,
          'format' => 'dd/mm/yyyy',
          'startView' => 'year',
          'placeholder' => 'd/m/aaaa'

       ]
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'falla',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'observacion',
    ],

    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'estado',
        'value'=>'estado.nombre',
    ],
    [
        'class' => MyActionColumn::class,
        'template' => '{view} {update} {delete}',
    ],

];
