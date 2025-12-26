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
        // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'id',
    // ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'razonsocial',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'sitioweb',
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
        'attribute'=>'observacion',
    ],
    [
        'class' => MyActionColumn::class,
        'template' => '{view} {update} {delete} {contacto}',
        'buttons' => [
          'contacto' => function($url, $model, $key) {
              return Html::a(
                  '<i class="fas fa-users"></i>',
                  ['contacto/index', 'id_proveedor' => $model->id],
                  [
                      'class' => 'btn btn-info btn-circle btn-sm',
                      'title' => 'Contactos del proveedor',
                      'data-pjax' => '0'
                  ]
              );
          },
    ],
    ],

];
