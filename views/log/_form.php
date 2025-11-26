<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datecontrol\DateControl;
use kartik\widgets\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Log */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="log-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'fechaingreso')->input('date' ,['max'=>date('Y-m-d')]) ?> <!-- con calendario -->

    <?= $form->field($model, 'fechaegreso')->input('date',['max'=> date('Y-m-d')]) ?> <!-- con calendario -->

    <?= $form->field($model, 'falla')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'observacion')->textarea(['rows' => 6]) ?>

    <div class="form-group spacing-top-2">
      <?=$form->field($model, 'id_estado')->dropDownList(
          $stateOptions,
          ['prompt' => 'Seleccione estado']
      ) ?>
    </div>

    <?= $form->field($model, 'id_equipo')->hiddenInput()->label(false) ?>


	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>

</div>
