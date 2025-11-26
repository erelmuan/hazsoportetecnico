<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Log */
?>
<div class="log-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
              'value'=> ($model->fechaingreso)? date('d/m/Y',strtotime($model->fechaingreso)):$model->fechaingreso,
              'label' => 'Fecha de ingreso'
            ],
            [
              'value'=> ($model->fechaegreso)? date('d/m/Y',strtotime($model->fechaegreso)):$model->fechaegreso,
              'label' => 'Fecha de ingreso'
            ],
            'falla:ntext',
            'observacion:ntext',
            [
                'class'=>'\kartik\grid\DataColumn',
                'attribute'=>'estado',
                'value'=> $model->estado->nombre,
            ],
        ],
    ]) ?>

</div>
