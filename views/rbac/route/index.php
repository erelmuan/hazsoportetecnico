<?php
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii2mod\rbac\RbacRouteAsset;

RbacRouteAsset::register($this);

/* @var $this yii\web\View */
/* @var $routes array */

$this->title = Yii::t('yii2mod.rbac', 'Routes');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="card">
  <div class="card-header d-flex align-items-center">
    <div>
      <i class="fas fa-route mr-2"></i>
      <span class="h6 mb-0"><?= Html::encode($this->title) ?></span>
    </div>
    <div class="ml-auto">
      <?= Html::a('<i class="fas fa-sync-alt"></i> Refrescar', ['index'], ['class'=>'btn btn-info btn-sm', 'data-pjax'=>1]) ?>
      <?= Html::a('<i class="fas fa-arrow-left"></i> Atrás', ['/site/controlacceso'], ['class'=>'btn btn-secondary btn-sm']) ?>
    </div>
  </div>

  <div class="card-body pt-3 pb-2">
    <?php Pjax::begin(['timeout' => 5000]); ?>

    <?= $this->render('../_dualListBox', [
        'opts' => Json::htmlEncode([
            'items' => $routes,
        ]),
        'assignUrl' => ['assign'],
        'removeUrl' => ['remove'],
    ]); ?>

    <?php Pjax::end(); ?>
  </div>

  <div class="card-footer text-muted small">
    <?= Html::encode('Asignar rutas a roles/permiso') ?>
  </div>
</div>
