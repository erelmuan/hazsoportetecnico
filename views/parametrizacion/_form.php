<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\color\ColorInput
/* @var $this yii\web\View */
/* @var $model app\models\Parametrizacion */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="parametrizacion-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'tiempo')->input('number', ['min' => 0, 'max' => 120]) ?>

    <?= $form->field($model, 'tipotiempo')->dropDownList([ 'dia/s' => 'Dia/s', 'año/s' => 'Año/s', ], ['prompt' => '']) ?>

 		<?= $form->field($model, 'condicion')->dropDownList([ 'igual' => 'Igual', 'mayor' => 'Mayor', ], ['prompt' => '']) ?> 

    <?= $form->field($model, 'modelo')->dropDownList([ 'log' => 'Log', 'equipo' => 'Equipo', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'color')->widget(ColorInput::classname(), [
    'options' => ['placeholder' => 'Selección de color ...', 'class'=>'text-center','readonly' => true],
        'useNative' => true,
    'pluginOptions' => ['container' => 'body'],
    ]);?>
    <?= $form->field($model, 'descripcion')->textInput() ?>


	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>

</div>
