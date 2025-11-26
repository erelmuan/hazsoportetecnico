<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Equipo */
?>
<div class="equipo-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'nserie',
            'codigo',
            [
              'value'=> ($model->fechafabricacion)? date('d/m/Y',strtotime($model->fechafabricacion)):$model->fechafabricacion,
              'label' => 'Fecha de fabricación'
            ],
            [
              'value'=> ($model->fecharegistro)? date('d/m/Y',strtotime($model->fecharegistro)):$model->fecharegistro,
              'label' => 'Fecha de registro'
            ],
            [
                'attribute' => 'id_marca',
                'label' => 'Marca',
                'value' => function($model) {
                    return $model->marca ? $model->marca->nombre : '(No asignado)';
                },
            ],
            [
                'attribute' => 'id_modelo',
                'label' => 'Modelo',
                'value' => function($model) {
                    return $model->modelo ? $model->modelo->nombre : '(No asignado)';
                },
            ],
            [
                'attribute' => 'id_servicio',
                'label' => 'Servicio',
                'value' => function($model) {
                    return $model->servicio ? $model->servicio->nombre : '(No asignado)';
                },
            ],
            [
                'attribute' => 'id_tipoequipo',
                'label' => 'Tipo de equipo',
                'value' => function($model) {
                    return $model->tipoequipo ? $model->tipoequipo->nombre : '(No asignado)';
                },
            ],
          'operativo:boolean',
          'observacion:ntext',
        ],
    ]) ?>

</div>
