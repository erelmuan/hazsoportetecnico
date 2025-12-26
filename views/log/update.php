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

<!-- ================= PROVEEDOR + CONTACTOS ================= -->
<div id="bloque-proveedor" style="<?= $mostrarBloque ? '' : 'display:none' ?>; margin-top:10px">

    <div class="row">

        <!-- PROVEEDOR -->
        <div class="col-md-4">
            <?= $form->field($model, 'id_proveedor')->dropDownList(
                $proveedores,
                [
                    'prompt'=>'Seleccione proveedor',
                    'id'=>'proveedor-select'
                ]
            ) ?>
        </div>

        <!-- CONTACTOS -->
        <div class="col-md-8">
            <label class="control-label">Contactos</label>
            <div id="contactos-container"
                 class="well well-sm"
                 style="max-height:220px; overflow:auto"
                 data-locked="<?= !empty($historicos) ? 1 : 0 ?>">
                <?= $contactosHtml ?>
            </div>
        </div>

    </div>
</div>


<?php if (!Yii::$app->request->isAjax): ?>
    <div class="form-group" style="margin-top:10px">
        <?= Html::submitButton(
            $model->isNewRecord ? 'Crear' : 'Actualizar',
            ['class'=>$model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']
        ) ?>
    </div>
<?php endif; ?>

<?php ActiveForm::end(); ?>
</div>

<?php
$js = <<<JS

// guardamos el estado inicial al cargar la vista
var prevState = $('#estado-log').val();

// ===================
// CAMBIO DE ESTADO
// ===================
$(document).on('change', '#estado-log', function () {

    var newState = $(this).val();

    // comparaciones CORRECTAS (sin llaves)
    var cameFromEnviado = (prevState == {$estadoEnviadoId});
    var toReparado      = (newState == {$estadoReparadoId});
    var toIrreparable      = (newState == {$estadoIrreparableId});

    // CASO ESPECIAL:
    // ENVIADO → REPARADO
    // NO ocultar proveedor ni contactos
    if (cameFromEnviado && (toReparado || toIrreparable)) {
        $('#bloque-proveedor').slideDown();
        return;
    }

    // comportamiento normal
    if (newState == {$estadoEnviadoId}) {
        $('#bloque-proveedor').slideDown();
    } else {
        $('#bloque-proveedor').slideUp();
        $('#proveedor-select').val('');
        $('#contactos-container').html('');
        $('#contactos-container').data('locked', 0);
    }
});


// ===================
// CAMBIO DE PROVEEDOR
// ===================
$(document).on('change', '#proveedor-select', function () {

    // si hay históricos, no permitir modificar
    if ($('#contactos-container').data('locked') == 1) {
        return;
    }

    var proveedorId = $(this).val();
    if (!proveedorId) return;

    $.getJSON('{$urlContactos}', {id: proveedorId}, function (data) {

        if (!data || !data.length) {
            $('#contactos-container').html('<em>Este proveedor no tiene contactos</em>');
            return;
        }

        var html = '';
        data.forEach(function (c) {
            html += `
                <div class="checkbox" style="margin-bottom:6px">
                    <label>
                        <input type="checkbox" name="Log[contactos][]" value="\${c.id}">
                        <strong>\${c.nombre}</strong>
                        \${c.cargo ? ' — ' + c.cargo : ''}
                        \${c.email ? ' <small>(' + c.email + ')</small>' : ''}
                    </label>
                </div>
            `;
        });

        $('#contactos-container').html(html);
    });
});

JS;

$this->registerJs($js);
?>
