<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Modelo */

?>
<div class="modelo-create">
    <?= $this->render('_form', [
        'model' => $model,
        'marcas'=>$marcas,
    ]) ?>
</div>
