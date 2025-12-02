<?php
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm; //used to enable bootstrap layout options
use kartik\datecontrol\DateControl;
use kartik\widgets\DatePicker;
use kartik\select2\Select2;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
use yii\widgets\MaskedInput;
/* @var $this yii\web\View */
/* @var $model app\models\Equipo */
/* @var $form yii\widgets\ActiveForm */

?>


  <div class="equipo-form">

      <?php $form = ActiveForm::begin(); ?>
      <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'id_tipoequipo')->widget(Select2::classname(), [
                'data' => $arrayHelper['tipoequipos'],
                'options' => ['placeholder' => 'Seleccione un tipo de equipo...'],
                'pluginOptions' => ['allowClear' => true],
                'pluginEvents' => [],  // Vacío

            ])->label("Tipo de equipo");

             ?>
        </div>
          <div class="col-md-6">
            <?= $form->field($model, 'id_marca')->dropDownList(
                $arrayHelper['marcas'],
                ['id'=>'id_marca', 'prompt'=>'- Seleccionar marca']
            )->label('Marca'); ?>

          </div>
      </div>

      <div class="row">
          <div class="col-md-6">
            <?= $form->field($model, 'id_modelo')->widget(DepDrop::classname(), [
                'data'=> $arrayHelper['modelos'],
                'options'=>['id'=>'id_modelo',    'placeholder' => 'Seleccionar modelo...'],
                'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                'pluginOptions'=>[
                    'depends'=>['id_marca'],
                    'placeholder'=>'Seleccionar modelo...',
                    'url'=>Url::to(['/equipo/subcat'])
                ]
            ])->label('Modelo'); ?>
          </div>
          <div class="col-md-6">
              <?= $form->field($model, 'codigo')->textInput() ?>
          </div>
      </div>

      <div class="row">
          <div class="col-md-6">
            <?= $form->field($model, 'fechafabricacion')->input('date',['max'=> date('Y-m-d')]) ?> <!-- con calendario -->
          </div>
          <div class="col-md-6">
            <?= $form->field($model, 'fecharegistro')->input('date',['max'=> date('Y-m-d')]) ?> <!-- con calendario -->
          </div>
      </div>

      <div class="row">
          <div class="col-md-6">
            <?= $form->field($model, 'nserie')->textInput() ?>
          </div>
          <div class="col-md-6">
            <?= $form->field($model, 'id_servicio')->widget(Select2::classname(), [
                'data' => $arrayHelper['servicios'],
                'options' => ['placeholder' => 'Seleccione un servicio...'],
                'pluginOptions' => ['allowClear' => true],
                'pluginEvents' => [],  // Vacío
            ])->label("Servicio/Sector") ?>
          </div>
      </div>
       <?= $form->field($model, 'observacion')->textInput() ?>

      <?php if (!Yii::$app->request->isAjax){ ?>
          <div class="form-group mt-3">
              <?= Html::submitButton(
                  $model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'),
                  ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']
              ) ?>
          </div>
      <?php } ?>

      <?php ActiveForm::end(); ?>

  </div>
