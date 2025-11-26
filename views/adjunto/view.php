<?php

use yii\widgets\DetailView;
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $model app\models\Adjunto */
?>
<div class="adjunto-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'nombreoriginal',
            [
              'label'=>'Adjunto',
              'value' => function ($model) {
                 return Html::a('Descargar', ['/uploads/adjuntos/'. $model->tipocategoria .'/'. $model->nombreasignado], [
                     'class' => 'btn btn-primary btn-sm',
                     'data-toggle' => 'tooltip',
                     'target'=>'_blank',
                     'title' => 'Descargar el archivo adjunto'
                 ]);
             },
              'format'=>'raw',
            ],
            'observacion:ntext',
            'tipocategoria',
            'tipoarchivo',
            [
              'value'=>date('d/m/Y H:i:s' , strtotime($model->fechahora)),
              'attribute'=> 'fechahora',

            ]
        ],
    ]) ?>

</div>
