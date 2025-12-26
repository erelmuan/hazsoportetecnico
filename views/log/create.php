<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Proveedor;
use app\models\Contactohistorico;

/* @var $this yii\web\View */
/* @var $model app\models\Log */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="log-form">

<?php $form = ActiveForm::begin(); ?>

<?= $this->render('_form', [
    'form' => $form,
    'model' => $model,
    'stateOptions'=>$stateOptions
]) ?>

<?php if (!Yii::$app->request->isAjax): ?>
    <div class="form-group" style="margin-top:10px">
        <?= Html::submitButton(
            'Crear' ,
            ['class'=>$model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']
        ) ?>
    </div>
<?php endif; ?>

<?php ActiveForm::end(); ?>
</div>
