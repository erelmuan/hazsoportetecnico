<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Log */
?>
<div class="log-update">

    <?= $this->render('_form', [
        'model' => $model,
        'stateOptions'=>$stateOptions
    ]) ?>

</div>
