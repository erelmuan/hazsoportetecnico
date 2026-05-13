<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
/* @var $this yii\web\View */
/* @var $model app\models\Componente */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="componente-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nombre')->textInput() ?>

    <?=$form->field($model, 'id_tipocomponente')->widget(Select2::classname(), [
        'data' => $tipocomponentes,
        'options' => ['placeholder' => 'Seleccione...'],
        'pluginOptions' => [
            'allowClear' => true,
        ],
        'pluginEvents' => [],  // Vacío
    ]);
    ?>

    <?= $form->field($model, 'serie')->textInput() ?>

    <?= $form->field($model, 'observacion')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'baja')->checkbox() ?>

    <?= $form->field($model, 'fechabaja')->input('date',['max'=> date('Y-m-d')]) ?> <!-- con calendario -->


	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>

</div>
