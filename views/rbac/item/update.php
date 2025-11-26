<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model yii2mod\rbac\models\AuthItemModel */

$labels = $this->context->getLabels();
$this->title = Yii::t('yii2mod.rbac', 'Update ' . $labels['Item'] . ' : {0}', $model->name);
$this->params['breadcrumbs'][] = ['label' => Yii::t('yii2mod.rbac', $labels['Items']), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->name]];
$this->params['breadcrumbs'][] = Yii::t('yii2mod.rbac', 'Update');
?>
<div class="card">
  <div class="card-header d-flex align-items-center">
    <div>
      <i class="fas fa-edit mr-2"></i>
      <span class="h6 mb-0"><?= Html::encode($this->title) ?></span>
    </div>
    <div class="ml-auto">
      <?= Html::a('<i class="fas fa-eye"></i> ' . Yii::t('yii2mod.rbac', 'View'), ['view', 'id' => $model->name], ['class'=>'btn btn-outline-primary btn-sm']) ?>
      <?= Html::a('<i class="fas fa-arrow-left"></i> ' . Yii::t('app','Volver'), ['index'], ['class'=>'btn btn-secondary btn-sm ml-1']) ?>
    </div>
  </div>

  <div class="card-body">
    <?= $this->render('_form', ['model' => $model]) ?>
  </div>

  <div class="card-footer text-muted small">
    <?= Html::encode('Edición de rol / permiso') ?>
  </div>
</div>
