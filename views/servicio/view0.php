<?php

use yii\widgets\DetailView;

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
                'format' => 'email', // genera <a href="mailto:...">
                // si querés personalizar:
                // 'value' => function($m){ return Html::a($m->correo, 'mailto:'.$m->correo); },
                'visible' => !empty($model->correo),
            ],
            'responsable',
            'observacion:ntext',
        ],
    ]) ?>

</div>
