<?php
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model yii2mod\rbac\models\AuthItemModel */
?>

<div class="auth-item-form">
    <?php $form = ActiveForm::begin(['options' => ['autocomplete' => 'off']]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 64, 'class' => 'form-control form-control-sm']) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 2, 'class' => 'form-control form-control-sm']) ?>

    <?= $form->field($model, 'ruleName')->widget(\yii\jui\AutoComplete::class, [
        'options' => ['class' => 'form-control form-control-sm'],
        'clientOptions' => [
            'source' => array_keys(Yii::$app->authManager->getRules()),
        ],
    ]) ?>

    <?= $form->field($model, 'data')->textarea(['rows' => 6, 'class' => 'form-control form-control-sm']) ?>

    <div class="form-group mt-3">
        <?= Html::submitButton(
            $model->getIsNewRecord() ? Yii::t('yii2mod.rbac', 'Create') : Yii::t('yii2mod.rbac', 'Update'),
            ['class' => $model->getIsNewRecord() ? 'btn btn-success btn-sm' : 'btn btn-primary btn-sm']
        ) ?>
        <?= Html::a(Yii::t('app', 'Cancelar'), ['index'], ['class' => 'btn btn-secondary btn-sm ml-2']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
