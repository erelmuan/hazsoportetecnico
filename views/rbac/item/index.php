<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;
use app\components\MyActionColumn;

/* @var $this yii\web\View */
/* @var $dataProvider \yii\data\ArrayDataProvider */
/* @var $searchModel yii2mod\rbac\models\search\AuthItemSearch */

$labels = $this->context->getLabels();
$this->title = Yii::t('yii2mod.rbac', $labels['Items']);
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="card">
  <div class="card-header d-flex align-items-center">
    <div>
      <i class="fas fa-key mr-2"></i>
      <span class="h6 mb-0"><?= Html::encode($this->title) ?></span>
    </div>

    <div class="ml-auto">
      <?= Html::a('<i class="fas fa-plus"></i> ' . Yii::t('yii2mod.rbac', 'Create ' . $labels['Item']), ['create'], ['class' => 'btn btn-success btn-sm']) ?>
      <?= Html::a('<i class="fas fa-sync-alt"></i>', ['index'], ['class' => 'btn btn-info btn-sm', 'data-pjax'=>1, 'title'=>'Refrescar']) ?>
      <?= Html::a('<i class="fas fa-arrow-left"></i> Atrás', ['/site/controlacceso'], ['class'=>'btn btn-secondary btn-sm']) ?>
    </div>
  </div>

  <div class="card-body pt-3 pb-2">
    <?php Pjax::begin(['timeout' => 5000, 'enablePushState' => false]); ?>

    <?= \kartik\grid\GridView::widget([
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
                'attribute' => 'ruleName',
                'label' => Yii::t('yii2mod.rbac', 'Rule Name'),
                'filter' => ArrayHelper::map(Yii::$app->getAuthManager()->getRules(), 'name', 'name'),
                'filterInputOptions' => ['class' => 'form-control form-control-sm', 'prompt' => Yii::t('yii2mod.rbac', 'Select Rule')],
            ],
            [
                'attribute' => 'description',
                'format' => 'ntext',
                'label' => Yii::t('yii2mod.rbac', 'Description'),
            ],
            [
                'class' => MyActionColumn::class,
                'template' => '{view} {update} {delete}',
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>
  </div>

  <div class="card-footer text-muted small">
    <?= Html::encode('Gestión de roles y permisos') ?>
  </div>
</div>
