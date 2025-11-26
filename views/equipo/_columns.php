<?php
use yii\helpers\Url;
use yii\helpers\Html;
use app\components\MyActionColumn;
return [

    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'id',
    // ],
    // [
    //     'class'=>'\kartik\grid\DataColumn',
    //     'attribute'=>'nserie',
    // ],
    // [
    //     'class' => '\kartik\grid\DataColumn',
    //     'attribute' => 'fechafabricacion',
    //     'label' => 'Fecha de fabricación',
    //     'value' => 'fechafabricacion',
    //     'format' => ['date', 'php:d/m/Y'],
    //     'contentOptions' => function ($model) {
    //         $estilo = $model->getEstiloFecha('fechafabricacion');
    //         return $estilo ? ['style' => $estilo] : [];
    //     },
    //     'filterInputOptions' => [
    //         'id' => 'fecha2',
    //         'class' => 'form-control',
    //         'autoclose' => true,
    //         'format' => 'dd/mm/yyyy',
    //         'startView' => 'year',
    //         'placeholder' => 'd/m/aaaa'
    //     ]
    // ],
    // [
    //   'class'=>'\kartik\grid\DataColumn',
    //   'attribute'=>'fecharegistro',
    //   'label'=> 'Fecha de registro',
    //   'value'=>'fecharegistro',
    //   'contentOptions' => function ($model) {
    //       $estilo = $model->getEstiloFecha('fecharegistro');
    //       return $estilo ? ['style' => $estilo] : [];
    //   },
    //   'format' => ['date', 'php:d/m/Y'],
    //   'filterInputOptions' => [
    //       'id' => 'fecha2',
    //       'class' => 'form-control',
    //       'autoclose'=>true,
    //       'format' => 'dd/mm/yyyy',
    //       'startView' => 'year',
    //       'placeholder' => 'd/m/aaaa'
    //
    //    ]
    // ],
    // [
    //
    //     'class'=>'\kartik\grid\DataColumn',
    //     'attribute'=>'codigo',
    // ],
    // [
    //     'class'=>'\kartik\grid\DataColumn',
    //     'attribute'=>'marca',
    //     'value'=> 'marca.nombreurl',
    //     'format' => 'raw',
    // ],
    // [
    //     'class'=>'\kartik\grid\DataColumn',
    //     'attribute'=>'modelo',
    //     'value'=> 'modelo.nombreurl',
    //     'format' => 'raw',
    //
    //  ],
    // [
    //     'class'=>'\kartik\grid\DataColumn',
    //     'attribute'=>'servicio',
    //     'value'=> 'servicio.nombreurl',
    //     'format' => 'raw',
    // ],
    //
    // [
    //     'class'=>'\kartik\grid\DataColumn',
    //     'attribute'=>'tipoequipo',
    //     'value'=> 'tipoequipo.nombreurl',
    //     'format' => 'raw',
    //     'label'=>'Tipo de equipo'
    // ],
    // [
    //     'class'=>'\kartik\grid\DataColumn',
    //     'attribute'=>'estado',
    //     'value'=> 'estado.nombre'
    // ],
    // [
    //     'class'=>'\kartik\grid\DataColumn',
    //     'attribute'=>'observacion',
    // ],
    [
        'class' => MyActionColumn::class,
        'template' => '{view} {update} {delete} {attachments} {log}',
        'buttons' => [
        'attachments' => function($url, $model, $key) {
            $icon = '<i class="fas fa-paperclip"></i>';
            return Html::a($icon, Url::to(['adjunto/index', 'id_equipo' => $model->id]), [
                'class' => 'btn btn-secondary btn-circle btn-sm',
                'title' => 'Archivos adjuntos',
                'data-pjax' => '0'
            ]);
        },
        'log' => function($url, $model, $key) {
            $icon = '<i class="fas fa-file-alt"></i>';
            return Html::a($icon, Url::to(['log/index', 'id_equipo' => $model->id]), [
                'class' => 'btn btn-info btn-circle btn-sm',
                'title' => 'Ver log',
                'data-pjax' => '0'
            ]);
        },
    ],
    ],

];
