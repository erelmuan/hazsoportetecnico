<?php

// use yii\grid\GridView;
use kartik\grid\GridView;
use kartik\grid\DataColumn;
use yii\bootstrap4\Modal;
use yii\helpers\Html;

 ?>


 <?=GridView::widget([
    'dataProvider'=> $dataProvider,
    'filterModel'=> $searchModel,
    'columns'=> [
      ['class' => '\kartik\grid\RadioColumn',
      'header' => Html::img("./imagenes/check.png"),
       // 'rowSelectedClass' => 'detalle-seleccionado',
     ],
     [
     'class'=>'\kartik\grid\DataColumn',
     'attribute'=>'id',
     ],
     [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'nserie',
     ],

     [

         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'codigo',
     ],
     [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'marca',
         'value'=> 'marca.nombreurl',
         'format' => 'raw',
     ],
     [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'modelo',
         'value'=> 'modelo.nombreurl',
         'format' => 'raw',

      ],
     [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'servicio',
         'value'=> 'servicio.nombreurl',
         'format' => 'raw',
     ],

     [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'tipoequipo',
         'value'=> 'tipoequipo.nombreurl',
         'format' => 'raw',
         'label'=>'Tipo de equipo'
     ],

  ],
  'toolbar' => [
      ['content'=>
          Html::a('Aceptar', '#', [
          'title' => 'Agregar registros seleccionados',
          'class' => 'btn btn-primary',
          'onclick' => "reasigComponente('$id_componente','$id_maestro')"
          ]),
      ],
  ],
  'panel' => [
      'type' => 'primary',
      'before'=>'<em>* Para buscar algún registro tipear un id o elegir una opción en el filtro. </em> </br><span id="error-no-seleccion" style="color:red;"> </span>',
      'heading' => '<i class="glyphicon glyphicon-list"></i> Acciones',
  ],
  'striped' => true,
  'condensed' => true,
  'responsive' => true,

    'pjax'=> true,

 ]);

 ?>
