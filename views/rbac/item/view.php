<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Json;
use yii2mod\rbac\RbacAsset;

/* @var $this yii\web\View */
/* @var $model yii2mod\rbac\models\AuthItemModel */

RbacAsset::register($this);

$labels = $this->context->getLabels();
$this->title = Yii::t('yii2mod.rbac', $labels['Item'] . ' : {0}', $model->name);
$this->params['breadcrumbs'][] = ['label' => Yii::t('yii2mod.rbac', $labels['Items']), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->name;
?>
<div class="card">
  <div class="card-header d-flex align-items-center">
    <div>
      <i class="fas fa-info-circle mr-2"></i>
      <span class="h6 mb-0"><?= Html::encode($this->title) ?></span>
    </div>

    <div class="ml-auto">
      <?= Html::a('<i class="fas fa-edit"></i> ' . Yii::t('yii2mod.rbac', 'Update'), ['update', 'id' => $model->name], ['class' => 'btn btn-primary btn-sm']) ?>
      <?= Html::a('<i class="fas fa-trash"></i> ' . Yii::t('yii2mod.rbac', 'Delete'), ['delete', 'id' => $model->name], [
          'class' => 'btn btn-danger btn-sm',
          'data-confirm' => Yii::t('yii2mod.rbac', 'Are you sure to delete this item?'),
          'data-method' => 'post',
      ]) ?>
      <?= Html::a('<i class="fas fa-plus"></i> ' . Yii::t('yii2mod.rbac', 'Create'), ['create'], ['class' => 'btn btn-success btn-sm ml-1']) ?>
      <?= Html::a('<i class="fas fa-arrow-left"></i> ' . Yii::t('app','Volver'), ['index'], ['class'=>'btn btn-secondary btn-sm ml-1']) ?>
    </div>
  </div>

  <div class="card-body">
    <div class="row mb-3">
      <div class="col-sm-12">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'name',
                'description:ntext',
                'ruleName',
                'data:ntext',
            ],
            'options' => ['class' => 'table table-borderless'],
        ]) ?>
      </div>
    </div>

    <div class="row">
      <div class="col-sm-12">
        <?= $this->render('../_dualListBox', [
            'opts' => Json::htmlEncode([
                'items' => $model->getItems(),
            ]),
            'assignUrl' => ['assign', 'id' => $model->name],
            'removeUrl' => ['remove', 'id' => $model->name],
        ]); ?>
      </div>
    </div>
  </div>

  <div class="card-footer text-muted small">
    <?= Html::encode('Detalles del rol / permiso') ?>
  </div>
</div>
