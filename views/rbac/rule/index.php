<?php
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider \yii\data\ArrayDataProvider */
/* @var $searchModel yii2mod\rbac\models\search\BizRuleSearch */

$this->title = Yii::t('yii2mod.rbac', 'Rules');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="card">
  <div class="card-header d-flex align-items-center">
    <div>
      <i class="fas fa-gavel mr-2"></i>
      <span class="h6 mb-0"><?= Html::encode($this->title) ?></span>
    </div>

    <div class="ml-auto">
      <?= Html::a('<i class="fas fa-plus"></i> ' . Yii::t('yii2mod.rbac', 'Create Rule'), ['create'], ['class' => 'btn btn-success btn-sm']) ?>
      <?= Html::a('<i class="fas fa-sync-alt"></i>', ['index'], ['class' => 'btn btn-info btn-sm', 'data-pjax'=>1]) ?>
      <?= Html::a('<i class="fas fa-arrow-left"></i> Atrás', ['/rbac'], ['class'=>'btn btn-secondary btn-sm']) ?>
    </div>
  </div>

  <div class="card-body pt-3 pb-2">
    <?php Pjax::begin(['timeout' => 5000]); ?>

    <?= \yii\grid\GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => ['class' => 'table table-striped table-sm'],
        'summaryOptions' => ['class' => 'text-muted small mb-2'],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'name',
                'label' => Yii::t('yii2mod.rbac', 'Name'),
            ],
            [
                'header' => Yii::t('yii2mod.rbac', 'Action'),
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' => ['style' => 'width:120px; text-align:center;'],
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>
  </div>

  <div class="card-footer text-muted small">
    <?= Html::encode('Reglas de negocio (RBAC)') ?>
  </div>
</div>
