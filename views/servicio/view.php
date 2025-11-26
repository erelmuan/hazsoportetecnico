<?php

use yii\widgets\DetailView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Servicio */
?>
<div class="servicio-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'nombre',
            'ninterno',
            [
                'attribute' => 'correo',
                'format' => 'raw',
                'value' => function($model) {
                    return Html::a(
                        $model->correo,
                        "mailto:{$model->correo}",
                        ['target' => '_blank']
                    );
                }
            ],
            'responsable',
            'observacion:ntext',
        ],
    ]) ?>

</div>
