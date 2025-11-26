<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datecontrol\DateControl;

/* @var $this yii\web\View */
/* @var $model app\models\Adjunto */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="adjunto-form">

    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data']
    ]); ?>
    <!-- Campo de subida de archivo -->
    <?= $form->field($model, 'file')->fileInput(['id' => 'input-file']) ?>

    <!-- Vista previa -->
    <div id="preview" style="margin-top:10px;"></div>
    <? if(!$model->isNewRecord): ?>
    <?= $form->field($model, 'nombreoriginal')->textInput() ?>
  <?  endif;?>
    <?= $form->field($model, 'tipocategoria')->textInput(['readonly' => true, 'value'=>$model->tipocategoria]) ?>

    <?= $form->field($model, 'tipoarchivo')->dropDownList([ 'video' => 'Video', 'foto' => 'Foto', 'documento_pdf' => 'Documento pdf', 'documento_texto' => 'Documento texto', 'planilla_calculo' => 'Planilla calculo', 'otros' => 'Otros', ], ['prompt' => '',]) ?>

    <?= $form->field($model, 'observacion')->textarea(['rows' => 6]) ?>
    <!-- Fecha y hora (solo lectura) -->
        <?php
      // Establecer la zona horaria de Buenos Aires
      $zonaHoraria = new DateTimeZone('America/Argentina/Buenos_Aires');
      $fechaActual = new DateTime('now', $zonaHoraria);
      ?>

      <?= $form->field($model, 'fechahora')->textInput([
          'value' => $model->isNewRecord ?
              $fechaActual->format('d/m/Y H:i:s') :
              Yii::$app->formatter->asDatetime($model->fechahora, 'php:d/m/Y H:i:s'),
          'readonly' => true,
          'placeholder' => 'Fecha y hora actual'
      ]) ?>

    <?php ActiveForm::end(); ?>
</div>

<?php
$script = <<<JS
document.getElementById('input-file').addEventListener('change', function(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('preview');
    preview.innerHTML = "";

    if (!file) return;

    if (file.type.startsWith('image/')) {
        let img = document.createElement('img');
        img.src = URL.createObjectURL(file);
        img.style.maxWidth = "200px";
        preview.appendChild(img);
    } else if (file.type.startsWith('video/')) {
        let video = document.createElement('video');
        video.src = URL.createObjectURL(file);
        video.controls = true;
        video.style.maxWidth = "300px";
        preview.appendChild(video);
    } else {
        let span = document.createElement('span');
        span.textContent = "Archivo seleccionado: " + file.name;
        preview.appendChild(span);
    }
});
JS;
$this->registerJs($script);
?>
