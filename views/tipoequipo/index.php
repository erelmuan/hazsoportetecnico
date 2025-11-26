<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap4\Modal;
use kartik\grid\GridView;
use hoaaah\ajaxcrud\CrudAsset;
use hoaaah\ajaxcrud\BulkButtonWidget;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TipoequipoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Tipo de equipos');
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

?>
<div class="card">
  <!-- Header: título a la izquierda y acciones a la derecha -->
  <div class="card-header d-flex">
      <!-- Título (opcional y discreto) -->
      <div class="d-flex align-items-center">
          <i class="fas fa-desktop mr-2" aria-hidden="true"></i>
      </div>

      <!-- Acciones empujadas a la derecha -->
      <div class="header-actions">
          <?= Html::a('<i class="fas fa-plus mr-1"></i>Crear', ['create'], [
              'role' => 'modal-remote',
              'class' => 'btn btn-success btn-sm',
              'title' => Yii::t('app','Crear equipo')
          ]) ?>

          <?= Html::a('<i class="fas fa-sync-alt mr-1"></i>Refrescar', ['index'], [
              'data-pjax' => 1,
              'class' => 'btn btn-info btn-sm',
              'title' => Yii::t('app','Refrescar')
          ]) ?>

          <?= Html::a('<i class="fas fa-arrow-left"></i> Atrás', Yii::$app->request->referrer ?: ['site/index'], [
              'class' => 'btn btn-secondary btn-sm',
              'title' => Yii::t('app','Volver')
          ]) ?>
      </div>
  </div>
<div class="tipoequipo-index">
    <div id="ajaxCrudDatatable">
        <?=GridView::widget([
            'id'=>'crud-datatable',
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'pjax'=>true,
            'columns' => require(__DIR__.'/_columns.php'),
            'toolbar'=> [
                ['content'=>
                    Html::a('<i class="fas fa-plus"></i>', ['create'],
                    ['role'=>'modal-remote','title'=> 'Create new Tipoequipos','class'=>'btn btn-default']).
                    Html::a('<i class="fas fa-sync-alt"></i>', [''],
                    ['data-pjax'=>1, 'class'=>'btn btn-default', 'title'=>'Reset Grid']).
                    '{toggleData}'.
                    '{export}'
                ],
            ],
              'striped' => true,
              'condensed' => true,
              'responsive' => true,
              'striped' => true,
              'condensed' => true,
              'responsive' => true,
              'columns' => require(__DIR__.'/_columns.php'),
              'summaryOptions' => ['class' => 'text-muted small mb-2'],
          ])?>
      </div>
  </div>
  </div>
  <div class="card-footer text-muted small">
  <?= Html::encode('Listado de tipos de equipo') ?>
  </div>
  </div>
  <?php Modal::begin([
  "id"=>"ajaxCrudModal",
  "footer"=>"",// always need it for jquery plugin
  ])?>
  <?php Modal::end(); ?>
