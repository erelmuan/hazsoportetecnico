<?php

use yii\widgets\DetailView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Log */

$contactosHistoricos = $model->contactohistoricos ?? [];
?>

<div class="log-view">

    <!-- ================= DATOS DEL LOG ================= -->
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'label' => 'Fecha de ingreso',
                'value' => $model->fechaingreso
                    ? date('d/m/Y', strtotime($model->fechaingreso))
                    : null,
            ],
            [
                'label' => 'Fecha de egreso',
                'value' => $model->fechaegreso
                    ? date('d/m/Y', strtotime($model->fechaegreso))
                    : null,
            ],
            'falla:ntext',
            'observacion:ntext',
            [
                'label' => 'Estado',
                'value' => $model->estado->nombre ?? null,
            ],
          ],
    ]) ?>


        <!-- ================= PROVEEDOR ================= -->
        <?php if ( !empty($model->proveedor)): ?>
            <div class="panel panel-info">
                <div class="panel-heading">
                    <strong>Proveedor</strong>
                </div>

                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Razón social:</strong><br>
                            <?= Html::encode($model->proveedor->razonsocial) ?>
                        </div>

                        <div class="col-md-6">


                          <strong>Sitio web:</strong><br>
                          <? $web = $model->proveedor->sitioweb;
                             if ($web && !preg_match('~^https?://~i', $web)) {
                                 $web = 'https://' . $web;
                             }

                           echo $web
                              ? Html::a($web, $web, ['target' => '_blank', 'rel' => 'noopener'])
                              : '-' ?>
                      </div>
                    </div>

                    <hr style="margin:8px 0">

                    <div class="row">
                        <div class="col-md-4">
                            <strong>Teléfono:</strong><br>
                            <?= $model->proveedor->telefono ?: '<em>No informado</em>' ?>
                        </div>

                        <div class="col-md-4">
                            <strong>Email:</strong><br>
                            <?= $model->proveedor->email
                                ? Html::mailto($model->proveedor->email)
                                : '<em>No informado</em>' ?>
                        </div>

                        <div class="col-md-4">
                            <strong>Observación:</strong><br>
                            <?= $model->proveedor->observacion ?: '<em>—</em>' ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    <?php if (!empty($contactosHistoricos)): ?>
      <!-- ================= CONTACTOS HISTÓRICOS ================= -->
      <h5 style="margin-top:25px;">Contactos asociados</h5>
        <div class="well well-sm">

            <?php foreach ($contactosHistoricos as $c): ?>

                <div style="margin-bottom:8px;">
                    <strong><?= Html::encode($c->nombre ?: '(sin nombre)') ?></strong>
                    <?= $c->cargo ? ' — '.Html::encode($c->cargo) : '' ?>
                    <?= $c->email ? ' <small>('.Html::encode($c->email).')</small>' : '' ?>
                    <?= $c->telefono ? '<br><small>📞 '.Html::encode($c->telefono).'</small>' : '' ?>
                </div>

            <?php endforeach; ?>

        </div>
    <?php endif; ?>

</div>
