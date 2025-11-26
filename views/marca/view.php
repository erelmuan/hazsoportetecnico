<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Marca */
?>
<div class="marca-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'nombre',
            'pais',
            [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'sitioweb',
            'format' => 'raw',   // importante
            'value' => function($model) {
                return \yii\helpers\Html::a(
                    $model->sitioweb,
                    $model->sitioweb,
                    ['target' => '_blank', 'rel' => 'noopener noreferrer']
                );
            }
        ],
        ],
    ]) ?>

</div>
