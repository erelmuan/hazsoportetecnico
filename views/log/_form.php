
<!-- ================= FECHAS + ESTADO ================= -->
<div class="row">
    <div class="col-md-3">
        <?= $form->field($model, 'fechaingreso')->input('date', ['max'=>date('Y-m-d')]) ?>
    </div>
    <div class="col-md-3">
        <?= $form->field($model, 'fechaegreso')->input('date', ['max'=>date('Y-m-d')]) ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'id_estado')->dropDownList(
            $stateOptions,
            ['id'=>'estado-log', 'prompt'=>'Seleccione estado']
        ) ?>
    </div>
</div>

<!-- ================= FALLA / OBS ================= -->
<div class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'falla')->textarea(['rows'=>3]) ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'observacion')->textarea(['rows'=>3]) ?>
    </div>
</div>

<?= $form->field($model, 'id_equipo')->hiddenInput()->label(false) ?>
