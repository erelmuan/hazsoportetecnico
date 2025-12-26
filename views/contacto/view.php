<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Contacto */
?>
<div class="contacto-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'nombre',
            'telefono',
            'email:email',
            'id_proveedor',
            'cargo',
        ],
    ]) ?>

</div>
