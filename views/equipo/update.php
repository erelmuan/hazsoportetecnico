<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Equipo */
?>
<div class="equipo-update">

    <?= $this->render('_form', [
        'model' => $model,
        'arrayHelper'=>$arrayHelper
    ]) ?>

</div>
