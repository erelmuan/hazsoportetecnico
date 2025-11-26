<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Usuario */
?>
<div class="usuario-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'nombreusuario',
            'contrasenia',
            'nombreapellido',
            'descripcion:ntext',
            'activo:boolean',
            'imagen',
            'access_token',
            'token_expire_at',
            'must_change_password:boolean',
            'reset_token',
            'reset_token_expire',
        ],
    ]) ?>

</div>
