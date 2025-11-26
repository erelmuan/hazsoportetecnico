<?php
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii2mod\rbac\RbacAsset;

/* @var $this yii\web\View */
/* @var $model \yii2mod\rbac\models\AssignmentModel */
/* @var $usernameField string */

RbacAsset::register($this);

$userName = $model->user->{$usernameField} ?? ($model->user->username ?? 'Usuario');
$this->title = Yii::t('yii2mod.rbac', 'Assignment : {0}', $userName);
$this->params['breadcrumbs'][] = ['label' => Yii::t('yii2mod.rbac', 'Assignments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $userName;
?>
<div class="card">
  <div class="card-header d-flex align-items-center">
    <div>
      <i class="fas fa-user-tag mr-2"></i>
      <span class="h6 mb-0"><?= Html::encode($this->title) ?></span>
      <div class="small text-muted d-block"><?= Html::encode($model->user->{$usernameField} ?? '') ?></div>
    </div>

    <div class="ml-auto">
      <?= Html::a('<i class="fas fa-sync-alt"></i> Refrescar', Url::current(), ['class' => 'btn btn-info btn-sm ml-1', 'data-pjax' => 1, 'title' => 'Refrescar']) ?>
      <?= Html::a('<i class="fas fa-user-cog"></i> Ver usuario', ['/usuario/view', 'id' => $model->userId], ['class' => 'btn btn-outline-primary btn-sm ml-1']) ?>
      <?= Html::a('<i class="fas fa-arrow-left"></i> Volver', ['index'], ['class' => 'btn btn-secondary btn-sm']) ?>
    </div>
  </div>

  <div class="card-body">
    <?php Pjax::begin(['id' => 'pjax-assignment-' . $model->userId, 'timeout' => 5000]); ?>

    <div class="mb-3">
      <p class="text-muted small">
        Asigne o quite Roles/Permisos al usuario. Seleccione elementos en la lista izquierda y presione los botones para moverlos.
      </p>
    </div>

    <?= $this->render('../_dualListBox', [
        'opts'      => Json::htmlEncode([
            'items' => $model->getItems(),
        ]),
        'assignUrl' => ['assign', 'id' => $model->userId],
        'removeUrl' => ['remove', 'id' => $model->userId],
    ]); ?>

    <?php Pjax::end(); ?>
  </div>

  <div class="card-footer text-muted small d-flex justify-content-between">
    <div>Última actualización: <?= date('Y-m-d H:i') ?></div>
    <div>
      <?= Html::a('<i class="fas fa-question-circle"></i> Ayuda', ['/site/help#rbac'], ['class'=>'text-muted']) ?>
    </div>
  </div>
</div>
