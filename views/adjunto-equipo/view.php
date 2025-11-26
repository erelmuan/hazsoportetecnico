<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\AdjuntoEquipo */
?>
<div class="adjunto-equipo-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'id_equipo',
            'id_adjunto',
        ],
    ]) ?>

</div>
