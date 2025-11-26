<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\models\Adjunto;
use yii\widgets\ActiveForm;

$this->title = 'Vincular adjunto referencial';

// lista de adjuntos referenciales existentes
$referenciales = ArrayHelper::map(
    Adjunto::find()->where(['tipocategoria' => 'referencia'])->orderBy('nombreoriginal')->all(),
    'id','nombreoriginal'
);

// valor seleccionado del modo (permite mantener la elección tras POST/validación)
$modoSelected = Yii::$app->request->post('modo', 'buscar'); // 'buscar' o 'subir'

// instancia vacía para los campos del adjunto (los names serán Adjunto[...])
$adjModel = new \app\models\Adjunto();
?>

<?php $form = ActiveForm::begin([
    'action' => ['adjunto-equipo/create','id_equipo' => $id_equipo],
    'options' => [
        'enctype' => 'multipart/form-data',
        'id' => 'form-referencial',
    ],
    'enableAjaxValidation' => true,
    'enableClientValidation' => false,
]); ?>

    <?= Html::hiddenInput('AdjuntoEquipo[id_equipo]', $id_equipo) ?>

    <div class="form-group">
        <label>
            <input type="radio" name="modo" value="buscar" <?= $modoSelected === 'buscar' ? 'checked' : '' ?>> Buscar adjunto existente
        </label>
        &nbsp;&nbsp;
        <label>
            <input type="radio" name="modo" value="subir" <?= $modoSelected === 'subir' ? 'checked' : '' ?>> Subir nuevo archivo
        </label>
    </div>

    <!-- BLOQUE BUSCAR -->
    <div id="bloque-buscar" class="mb-3" style="<?= $modoSelected === 'buscar' ? '' : 'display:none;' ?>">
        <?= Html::dropDownList('AdjuntoEquipo[id_adjunto]',
            Yii::$app->request->post('AdjuntoEquipo')['id_adjunto'] ?? null,
            $referenciales, [
            'class' => 'form-control',
            'required' => true , // ← Esto hace el campo obligatorio en HTML5
            'prompt' => '--- seleccionar adjunto referencial existente ---'
        ]) ?>
        <div class="small text-muted mt-1">Si seleccionás uno existente se creará la relación con el equipo.</div>
        <div class="text-right mt-3">
            <?= Html::button('Cancelar', ['class'=>'btn btn-secondary','data-dismiss'=>'modal']) ?>
            <?= Html::submitButton('Vincular', ['class'=>'btn btn-primary', 'id'=>'btn-vincular']) ?>
        </div>
        <?php ActiveForm::end(); ?>

    </div>

    <!-- BLOQUE SUBIR -->
    <div id="bloque-subir" style="<?= $modoSelected === 'subir' ? '' : 'display:none;' ?>">
        <!-- Campos del Adjunto (sin abrir otro ActiveForm) -->
        <?= Html::button('Subir nuevo adjunto', [
            'class' => 'btn btn-primary btn-open-create-adjunto',
            'role'=>'modal-remote',
            'data-url' => Url::to(['adjunto/create', 'id_equipo' => $id_equipo ,'tipocategoria'=>'referencia']),
        ]) ?>
        <div id="preview" style="margin-top:10px;"></div>
    </div>




<!-- JS puro: alterna bloques, preview y validación básica antes de enviar -->
<script>
(function(){
  // helpers
  var qs = function(sel){ return document.querySelector(sel); };
  var qsa = function(sel){ return Array.prototype.slice.call(document.querySelectorAll(sel)); };

  var radios = qsa('input[name="modo"]');
  var bloqueBuscar = qs('#bloque-buscar');
  var bloqueSubir = qs('#bloque-subir');
  var fileInput = qs('#adjunto-file');
  var preview = qs('#preview');
  var form = qs('#form-referencial');

  function actualizarModo(){
    var modo = qsa('input[name="modo"]').filter(function(r){ return r.checked; })[0].value;
    if(modo === 'buscar'){
      bloqueBuscar.style.display = '';
      bloqueSubir.style.display = 'none';
    } else {
      bloqueBuscar.style.display = 'none';
      bloqueSubir.style.display = '';
    }
  }

  qsa('input[name="modo"]').forEach(function(r){ r.addEventListener('change', actualizarModo); });
  // inicial (en caso de re-render server-side)
  actualizarModo();

  // preview
  if(fileInput){
    fileInput.addEventListener('change', function(){
      preview.innerHTML = '';
      var f = this.files[0];
      if(!f) return;
      var t = f.type || '';
      if(t.indexOf('image') === 0){
        var img = document.createElement('img');
        img.src = URL.createObjectURL(f);
        img.style.maxWidth = '100%';
        img.style.maxHeight = '220px';
        preview.appendChild(img);
      } else if(t.indexOf('video') === 0){
        var v = document.createElement('video');
        v.controls = true;
        v.src = URL.createObjectURL(f);
        v.style.maxWidth = '100%';
        v.style.maxHeight = '220px';
        preview.appendChild(v);
      } else {
        preview.textContent = 'Archivo seleccionado: ' + f.name;
      }
    });
  }

})();
</script>
