<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Componente */
?>
<div class="componente-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'nombre',
            [
            'class'=>'\kartik\grid\DataColumn',
            'attribute'=>'tipocomponente',
            'value'=> function($model){
                return $model->tipocomponente->nombre;
            },
            'label'=> 'Tiop de componente'
            ],
            'serie',
            'observacion:ntext',
            [
              'class'=>'kartik\grid\DataColumn',
              'attribute'=> 'id_equipo',
              'value'=> function( $model){
                return ($model->equipo)?$model->equipo->tipoequipo->nombre: 'No definido';
              },
              'label'=> 'Equipo'
            ],
            'baja:boolean',
            'fechabaja',
        ],
    ]) ?>

</div>
