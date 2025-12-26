<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Proveedor */

$this->title = $model->razonsocial;
$this->params['breadcrumbs'][] = ['label' => 'Proveedores', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="proveedor-view">

    <!-- ================= PROVEEDOR ================= -->
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4><i class="glyphicon glyphicon-briefcase"></i> Proveedor</h4>
        </div>
        <div class="panel-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'razonsocial',
                    'sitioweb:url',
                    'telefono',
                    'email:email',
                    'observacion:ntext',
                ],
            ]) ?>
        </div>
    </div>

    <!-- ================= CONTACTOS ================= -->
    <div class="panel panel-info">
        <div class="panel-heading">
            <h4>
                <i class="fas fa-users"></i> Contactos
            </h4>
        </div>

        <div class="panel-body">
          <?= Html::a(
          '<i class="fas fa-users-cog"></i> Gestionar contactos',
          Url::to(['contacto/index', 'id_proveedor' => $model->id]),
          [
              'class' => 'btn btn-primary',
              'title' => 'Gestionar contactos del proveedor'
          ]
      ) ?>


            <?php if (!empty($model->contactos)): ?>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Cargo</th>
                                <th>Teléfono</th>
                                <th>Email</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($model->contactos as $contacto): ?>
                                <tr>
                                    <td><?= Html::encode($contacto->nombre) ?></td>
                                    <td><?= Html::encode($contacto->cargo) ?></td>
                                    <td><?= Html::encode($contacto->telefono) ?></td>
                                    <td><?= Html::a(Html::encode($contacto->email),  'mailto:' . $contacto->email) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

            <?php else: ?>

                <div class="alert alert-warning">
                    <i class="glyphicon glyphicon-info-sign"></i>
                    Este proveedor no tiene contactos cargados.
                </div>

            <?php endif; ?>

        </div>
    </div>

</div>
