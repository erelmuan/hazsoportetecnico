<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\AdjuntoEquipo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="adjunto-equipo-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_equipo')->textInput() ?>

    <?= $form->field($model, 'id_adjunto')->textInput() ?>

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
