<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap4\Modal;
use kartik\grid\GridView;
use hoaaah\ajaxcrud\CrudAsset;
use hoaaah\ajaxcrud\BulkButtonWidget;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ContactoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Contactos');
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

?>

<div class="card">

  <!-- Header: título (opcional) y acciones a la derecha -->
  <div class="card-header d-flex">
    <!-- Título (opcional y discreto) -->
    <div class="d-flex align-items-center">
        <i class="fas fa-file-alt" aria-hidden="true"></i>
    </div>
      <!-- Acciones empujadas a la derecha -->
      <div class="header-actions">
          <!-- Crear (abre modal remoto) -->

          <?= Html::a('<i class="fas fa-plus mr-1"></i>Crear', ['create', 'id_proveedor' => $model_proveedor->id], [
              'role' => 'modal-remote',
              'class' => 'btn btn-success btn-sm',
              'title' => Yii::t('app','Crear registro')
          ]) ?>

          <!-- Actualizar / Refrescar (pjax) -->
          <?= Html::a('<i class="fas fa-sync-alt mr-1"></i>Refrescar', ['index', 'id_proveedor' => $model_proveedor->id], [
              'data-pjax' => 1,
              'class' => 'btn btn-info btn-sm',
              'title' => Yii::t('app','Refrescar')
          ]) ?>

          <!-- Atrás -->
          <?= Html::a('<i class="fas fa-arrow-left mr-1"></i>Atrás', ['/proveedor/index'], [
              'class' => 'btn btn-secondary btn-sm',
              'title' => Yii::t('app','Volver')
          ]) ?>
      </div>
  </div>

    <!-- Body: interior del card -->
<div class="card-body pt-3 pb-2">
<!-- Mini-card con el encabezado del equipo -->
<div class="card card-encabezado mb-3">
    <div class="card-body p-2">
        <?= $this->render('/proveedor/_encabezado', ['model' => $model_proveedor]) ?>
    </div>
</div>
<!-- Body: interior del card -->

<div class="contacto-index">
    <div id="ajaxCrudDatatable">
        <?=GridView::widget([
            'id'=>'crud-datatable',
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'pjax'=>true,
            'columns' => require(__DIR__.'/_columns.php'),
            'toolbar'=> [
                ['content'=>
                    Html::a('<i class="glyphicon glyphicon-plus"></i>', ['create'],
                    ['role'=>'modal-remote','title'=> 'Create new Contactos','class'=>'btn btn-default']).
                    Html::a('<i class="glyphicon glyphicon-repeat"></i>', [''],
                    ['data-pjax'=>1, 'class'=>'btn btn-default', 'title'=>'Reset Grid']).
                    '{toggleData}'.
                    '{export}'
                ],
            ],
            'striped' => true,
            'condensed' => true,
            'responsive' => true,
            'columns' => require(__DIR__.'/_columns.php'),
            'summaryOptions' => ['class' => 'text-muted small mb-2'],
        ])?>
    </div>
</div>
</div>
</div>
<?php Modal::begin([
    "id"=>"ajaxCrudModal",
    "footer"=>"",// always need it for jquery plugin
])?>
<?php Modal::end(); ?>
