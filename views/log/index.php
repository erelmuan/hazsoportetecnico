<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap4\Modal;
use kartik\grid\GridView;
use hoaaah\ajaxcrud\CrudAsset;
use hoaaah\ajaxcrud\BulkButtonWidget;

/* @var $this yii\web\View */
/* @var $searchModel app\models\LogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $model_equipo app\models\Equipo */

$this->title = Yii::t('app', 'Logs');
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

// CSS local: garantiza que el header sea flex y el bloque de acciones quede a la derecha
$this->registerCss(<<<'CSS'
/* Header principal en flex (título a la izquierda, acciones a la derecha) */
.card .card-header.d-flex {
  display: flex !important;
  align-items: center;
  padding: .5rem .75rem;
}

/* Contenedor de acciones que se empuja al extremo derecho */
.header-actions {
  margin-left: auto;
  display: flex;
  align-items: center;
  gap: .4rem;
}

/* Estética botones del header */
.header-actions .btn {
  padding: .35rem .6rem;
  font-size: .90rem;
}

/* Encabezado del mini-card del encabezado del equipo */
.card-encabezado .card-header {
  background-color: #263238;
  color: #fff;
  padding: .45rem .75rem;
  font-weight: 600;
}

/* Compactar tablas del GridView ligeramente */
.kv-grid-table .table th, .kv-grid-table .table td {
  padding: .45rem .6rem;
  font-size: .92rem;
}

/* Asegura que los mensajes de validacion se vean (AdminLTE a veces los oculta) */
.invalid-feedback { display:block !important; }
CSS
);
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

          <?= Html::a('<i class="fas fa-plus mr-1"></i>Crear', ['create', 'id_equipo' => $model_equipo->id], [
              'role' => 'modal-remote',
              'class' => 'btn btn-success btn-sm',
              'title' => Yii::t('app','Crear registro')
          ]) ?>

          <!-- Actualizar / Refrescar (pjax) -->
          <?= Html::a('<i class="fas fa-sync-alt mr-1"></i>Refrescar', ['index', 'id_equipo' => $model_equipo->id], [
              'data-pjax' => 1,
              'class' => 'btn btn-info btn-sm',
              'title' => Yii::t('app','Refrescar')
          ]) ?>

          <!-- Atrás -->
          <?= Html::a('<i class="fas fa-arrow-left mr-1"></i>Atrás', ['/equipo/index'], [
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
              <?= $this->render('/equipo/_encabezado', ['model' => $model_equipo]) ?>
          </div>
      </div>

      <!-- Grid: Logs -->
      <div class="log-index">
          <div id="ajaxCrudDatatable">
              <?= GridView::widget([
                  'id' => 'crud-datatable',
                  'dataProvider' => $dataProvider,
                   'filterModel' => $searchModel,
                  'pjax' => true,
                  // evitamos panel de kartik porque ya usamos card externo
                  'panel' => false,
                  'toolbar' => [
                      ['content' =>
                          // botones adicionales discretos (opcional)
                          Html::a('<i class="fas fa-file-export"></i>', ['export','id_equipo'=>$model_equipo->id], [
                              'class'=>'btn btn-light btn-sm', 'title' => Yii::t('app','Exportar')
                          ]) .
                          Html::a('<i class="fas fa-trash"></i>', ['bulkdelete'], [
                              'class'=>'btn btn-danger btn-sm',
                              'role'=>'modal-remote-bulk',
                              'data-confirm'=>false, 'data-method'=>false,
                              'data-request-method'=>'post',
                              'data-confirm-title'=>Yii::t('app','¿Seguro?'),
                              'data-confirm-message'=>Yii::t('app','¿Eliminar seleccionados?')
                          ])
                      ],
                  ],
                  'striped' => true,
                  'condensed' => true,
                  'responsive' => true,
                  'columns' => require(__DIR__.'/_columns.php'),
                  'summaryOptions' => ['class' => 'text-muted small mb-2'],
              ]); ?>
          </div>
      </div>
  </div>

  <!-- Footer del card -->
  <div class="card-footer text-muted small">
      <?= Html::encode('Equipo: ' . $model_equipo->codigo . ($model_equipo->marca ? ' — ' . $model_equipo->marca->nombre : '')) ?>
  </div>
</div>

<?php Modal::begin([
    "id" => "ajaxCrudModal",
    "footer" => "", // necesario para ajaxcrud
]) ?>
<?php Modal::end(); ?>
