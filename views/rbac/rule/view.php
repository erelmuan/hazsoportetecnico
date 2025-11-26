<?php
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model yii2mod\rbac\models\BizRuleModel */

$this->title = Yii::t('yii2mod.rbac', 'Rule : {0}', $model->name);
$this->params['breadcrumbs'][] = ['label' => Yii::t('yii2mod.rbac', 'Rules'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->name;
?>
<div class="card">
  <div class="card-header d-flex align-items-center">
    <div>
      <i class="fas fa-info-circle mr-2"></i>
      <span class="h6 mb-0"><?= Html::encode($this->title) ?></span>
    </div>
    <div class="ml-auto">
      <?= Html::a('<i class="fas fa-edit"></i> ' . Yii::t('yii2mod.rbac','Update'), ['update', 'id' => $model->name], ['class' => 'btn btn-primary btn-sm']) ?>
      <?= Html::a('<i class="fas fa-trash"></i> ' . Yii::t('yii2mod.rbac','Delete'), ['delete', 'id' => $model->name], [
          'class' => 'btn btn-danger btn-sm',
          'data-confirm' => Yii::t('yii2mod.rbac', 'Are you sure to delete this item?'),
          'data-method' => 'post',
      ]) ?>
      <?= Html::a('<i class="fas fa-arrow-left"></i> ' . Yii::t('app','Volver'), ['index'], ['class'=>'btn btn-secondary btn-sm ml-1']) ?>
    </div>
  </div>

  <div class="card-body">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            'className',
        ],
        'options' => ['class' => 'table table-borderless'],
    ]) ?>
  </div>

  <div class="card-footer text-muted small">
    <?= Html::encode('Detalles de la regla') ?>
  </div>
</div>
