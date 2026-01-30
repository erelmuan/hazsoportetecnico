<?php
use yii\helpers\Html;
use yii\bootstrap4\Modal;
use kartik\grid\GridView;
use hoaaah\ajaxcrud\CrudAsset;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AdjuntoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $model_equipo app\models\Equipo */

$this->title = Yii::t('app', 'Adjuntos');
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

$columns = require(__DIR__ . '/_columns.php');
$columnsBlibiografia = require(__DIR__ . '/_columnsBlibiografia.php');

?>

<div class="card">

  <div class="card-header d-flex align-items-center">
      <h5 class="mb-0 mr-3"><i class="fas fa-paperclip mr-2"></i> <?= Html::encode($this->title) ?></h5>
      <div class="ml-auto">
          <?= Html::a('<i class="fas fa-plus mr-1"></i> Operativo', ['adjunto/create', 'id_equipo'=>$model_equipo->id,'tipocategoria'=>'operativo'], [
              'role' => 'modal-remote',
              'class' => 'btn btn-success btn-sm',
              'title' => 'Agregar adjunto operativo'
          ]) ?>
          <?= Html::a('<i class="fas fa-plus mr-1"></i> Bibliografía', ['adjunto/createbibliografia', 'id_equipo'=>$model_equipo->id], [
              'role' => 'modal-remote',
              'class' => 'btn btn-primary btn-sm',
              'title' => 'Agregar adjunto bibliografía'
          ]) ?>

          <?= Html::a('<i class="fas fa-sync-alt mr-1"></i> Refrescar', ['index', 'id_equipo' => $model_equipo->id], [
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

  <div class="card-body pt-3 pb-2">
      <!-- Encabezado del equipo -->
      <div class="card card-encabezado mb-3">
          <div class="card-body p-2">
              <?= $this->render('/equipo/_encabezado', ['model' => $model_equipo]) ?>
          </div>
      </div>

      <!-- pestañas -->
      <ul class="nav nav-tabs mb-3" id="adjuntoTabs" role="tablist">
          <li class="nav-item">
            <a class="nav-link active" id="tab-op-link" data-toggle="tab" href="#tab-op" role="tab">Operativo</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="tab-bib-link" data-toggle="tab" href="#tab-bib" role="tab">Bibliografía</a>
          </li>
      </ul>

      <div class="tab-content">
          <!-- PESTAÑA OPERATIVO -->
          <div class="tab-pane fade show active" id="tab-op" role="tabpanel">
            <div class="adjunto-index">
              <div id="ajaxCrudDatatableOp">
                <?= GridView::widget([
                    'id' => 'crud-datatable-op',
                    'dataProvider' => $dataConfig['dataProviderOp'],
                    'filterModel' =>$dataConfig['searchModel'] ,
                    'pjax' => true,
                    'toolbar'=> [
                        ['content'=>
                            Html::a('<i class="fas fa-plus"></i>', ['adjunto/create','id_equipo'=>$model_equipo->id],
                                ['class'=>'btn btn-success','title'=>'Crear adjunto operativo']).
                            Html::a('<i class="fas fa-sync-alt"></i>', ['adjunto/index','id_equipo'=>$model_equipo->id],
                                ['data-pjax'=>1, 'class'=>'btn btn-default', 'title'=>'Refrescar']).
                            '{toggleData}'.
                            '{export}'
                        ],
                    ],
                    'striped' => true,
                    'condensed' => true,
                    'responsive' => true,
                    'columns' => $columns,
                    'summaryOptions' => ['class' => 'text-muted small mb-2'],
                ]) ?>
                <div class="card-footer text-muted small">
                    <?= Html::encode('Listado de adjuntos') ?>
                </div>
            </div>
          </div>
        </div>
          <!-- PESTAÑA BIBLIOGRAFIA -->
          <div class="tab-pane fade" id="tab-bib" role="tabpanel">
            <div class="adjunto-index">
                <div id="ajaxCrudDatatableBib">
                  <?= GridView::widget([
                      'id' => 'crud-datatable-bib',
                      'dataProvider' => $dataConfig['dataProviderBib'] ,
                      'filterModel' => $dataConfig['searchModel'] ,
                      'pjax' => true,
                      'toolbar'=> [
                          ['content'=>
                              Html::button('<i class="fas fa-plus"></i> Bibliografía', [
                                  'class' => 'btn btn-primary',
                                  'data-toggle' => 'modal',
                                  'data-target' => '#modal-bibliografia'
                              ]).
                              Html::a('<i class="fas fa-sync-alt"></i>', ['adjunto/index','id_equipo'=>$model_equipo->id],
                                  ['data-pjax'=>1, 'class'=>'btn btn-default', 'title'=>'Refrescar']).
                              '{toggleData}'.
                              '{export}'
                          ],
                      ],
                      'striped' => true,
                      'condensed' => true,
                      'responsive' => true,
                      'columns' => $columnsBlibiografia,
                      'summaryOptions' => ['class' => 'text-muted small mb-2'],
                  ]) ?>
                  </div>
            </div>
          </div>
      </div>
  </div> <!-- /card-body -->

</div> <!-- /card -->

<!-- Modal genérico de ajaxcrud -->
<?php Modal::begin([
  "id"=>"ajaxCrudModal",
  "size"=> Modal::SIZE_LARGE,
  "footer"=>"",
  "options" => [
    "class" => "modal-header-primary", // clase personalizada
],
])?>
<?php Modal::end(); ?>
