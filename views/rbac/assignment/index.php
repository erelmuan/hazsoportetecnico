<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use app\components\MyActionColumn;

/* @var $this \yii\web\View */
/* @var $gridViewColumns array */
/* @var $dataProvider \yii\data\ArrayDataProvider */
/* @var $searchModel \yii2mod\rbac\models\search\AssignmentSearch */

$this->title = Yii::t('yii2mod.rbac', 'Assignments');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="card">
  <div class="card-header d-flex align-items-center">
    <div>
      <i class="fas fa-user-tag mr-2"></i>
      <span class="h6 mb-0"><?= Html::encode($this->title) ?></span>
    </div>

    <div class="ml-auto">
      <!-- Botones: volver, refrescar -->
      <?= Html::a('<i class="fas fa-sync-alt"></i> Refrescar', ['index'], ['class'=>'btn btn-info btn-sm', 'data-pjax'=>1]) ?>
      <?= Html::a('<i class="fas fa-arrow-left"></i> Atrás', ['/site/controlacceso'], ['class'=>'btn btn-secondary btn-sm']) ?>
    </div>
  </div>

  <div class="card-body pt-3 pb-2">
    <?php Pjax::begin(['timeout' => 5000]); ?>

    <?= \kartik\grid\GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => ['class' => 'table table-striped table-sm'],
        'summaryOptions' => ['class' => 'text-muted small mb-2'],
        'columns' => \yii\helpers\ArrayHelper::merge($gridViewColumns, [
          [
              'class' => MyActionColumn::class,
              'template' => '{view}',
          ],
        ]),
    ]); ?>

    <?php Pjax::end(); ?>
  </div>

  <div class="card-footer text-muted small">
    <?= Html::encode('Asignaciones de usuarios a roles') ?>
  </div>
</div>
