<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\EquipoSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="equipo-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'nserie') ?>

    <?= $form->field($model, 'fechafabricacion') ?>

    <?= $form->field($model, 'fecharegistro') ?>

    <?= $form->field($model, 'codigo') ?>

    <?php // echo $form->field($model, 'id_marca') ?>

    <?php // echo $form->field($model, 'id_modelo') ?>

    <?php // echo $form->field($model, 'id_servicio') ?>

    <?php // echo $form->field($model, 'id_tipoequipo') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
