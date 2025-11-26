<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2
/* @var $this yii\web\View */
/* @var $model app\models\Modelo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="modelo-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nombre')->textInput() ?>

    <?= $form->field($model, 'descripcion')->textInput() ?>

    <?= $form->field($model, 'anio')->textInput() ?>


    <?= $form->field($model, 'id_marca')->widget(Select2::classname(), [
        'data'=>$marcas,
        'options'=> ['Seleccion la marca'],
        'pluginOptions'=>[
          'allowClear'=> true,
        ]
    ]) ; ?>


	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>

</div>
