<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Componente */

?>
<div class="componente-create">
    <?= $this->render('_form', [
        'model' => $model,
        'tipocomponentes' =>$tipocomponentes

    ]) ?>
</div>
