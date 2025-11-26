<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Modelo */
?>
<div class="modelo-update">

    <?= $this->render('_form', [
        'model' => $model,
        'marcas'=>$marcas,
    ]) ?>

</div>
