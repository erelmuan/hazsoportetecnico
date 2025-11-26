<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model yii2mod\rbac\models\AuthItemModel */

$labels = $this->context->getLabels();
$this->title = Yii::t('yii2mod.rbac', 'Create ' . $labels['Item']);
$this->params['breadcrumbs'][] = ['label' => Yii::t('yii2mod.rbac', $labels['Items']), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card">
  <div class="card-header d-flex align-items-center">
    <div>
      <i class="fas fa-plus-circle mr-2"></i>
      <span class="h6 mb-0"><?= Html::encode($this->title) ?></span>
    </div>
    <div class="ml-auto">
      <?= Html::a('<i class="fas fa-arrow-left"></i> ' . Yii::t('app','Volver'), ['index'], ['class'=>'btn btn-secondary btn-sm']) ?>
    </div>
  </div>

  <div class="card-body">
    <?= $this->render('_form', ['model' => $model]) ?>
  </div>

  <div class="card-footer text-muted small">
    <?= Html::encode('Crear un nuevo rol / permiso') ?>
  </div>
</div>
