<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Modelo */
?>
<div class="modelo-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'nombre',
            'descripcion',
            'anio',
          [
            'attribute'=> 'marca.nombre',
            'label'=> 'Marca'
          ],
        ],
    ]) ?>

</div>
