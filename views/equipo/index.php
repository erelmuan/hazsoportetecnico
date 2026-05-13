
<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap4\Modal;
use kartik\grid\GridView;
use hoaaah\ajaxcrud\CrudAsset;
use hoaaah\ajaxcrud\BulkButtonWidget;
use app\components\MyActionColumn;
use kartik\grid\ExpandRowColumn;

/* @var $this yii\web\View */
/* @var $searchModel app\models\EquipoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Equipos';
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);
?>
<?php
$this->registerJs("
    // Fix para permitir escribir en Select2 dentro de modales
    $('#ajaxCrudModal').removeAttr('tabindex');

    // ✅ Fix tamaño modal: resetear a modal-lg cada vez que se cierra
    $('#ajaxCrudModal').on('hidden.bs.modal', function () {
        $(this).find('.modal-dialog')
            .removeClass('modal-sm modal-xl modal-dialog-centered')
            .addClass('modal-lg');
    });
");


$this->registerJsVar('appConfig', [
    'listDetalleUrl' => Url::to(['listdetalle']),
    'desvincularComponenteUrl' => Url::to([
        '/equipo/desvcomponente'
    ]),
    'addComponenteUrl' => Url::to([
        'addcomponente'
    ]),
    'reasigComponenteUrl' => Url::to([
        'reasigcomponente'
    ]),
]);

$this->registerJsFile(
    '@web/js/equipo-componente.js',
    ['depends' => [\yii\web\JqueryAsset::class]]
);

// 1. Expandir fila (PRIMERO)
array_unshift($columns, [
    'class' => 'kartik\grid\ExpandRowColumn',
    'width' => '50px',
    'value' => function ($model, $key, $index, $column) {
        return GridView::ROW_COLLAPSED;
    },
    'detailUrl' => Url::to(['/equipo/listdetalle']),
    'expandOneOnly' => true,
    // 🔥 IMPORTANTE
    'enableCache' => false,
]);

// 4. Acciones
$columns[] = [
    'class' => MyActionColumn::class,
    'template' => '{view} {update} {delete} {attachments} {log}',
    'buttons' => [
        'attachments' => function($url, $model, $key) {
            $icon = '<i class="fas fa-paperclip"></i>';
            return Html::a($icon, Url::to(['adjunto/index', 'id_equipo' => $model->id]), [
                'class' => 'btn btn-secondary btn-circle btn-sm',
                'title' => 'Archivos adjuntos',
                'data-pjax' => '0'
            ]);
        },
        'log' => function($url, $model, $key) {
            $icon = '<i class="fas fa-file-alt"></i>';
            return Html::a($icon, Url::to(['log/index', 'id_equipo' => $model->id]), [
                'class' => 'btn btn-info btn-circle btn-sm',
                'title' => 'Ver log',
                'data-pjax' => '0'
            ]);
        },
    ],
];

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

          <?= Html::a('<i class="far fa-object-ungroup"> Personalizar Vista</i>', ['vista/select','modelo' => 'app\models\Equipo'],
          ['role'=>'modal-remote','title'=> 'Personalizar','class'=>'btn btn-default'])
          ?>
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

          <?= Html::a('<i class="fas fa-arrow-left mr-1"></i>Atrás', ['/site/index'], [
              'class' => 'btn btn-secondary btn-sm',
              'title' => Yii::t('app','Volver')
          ]) ?>
      </div>
  </div>


  <!-- Body: Grid dentro del mismo card -->
  <div class="card-body pt-3 pb-2">

    <div class="rol-index">
        <div id="ajaxCrudDatatable">
            <?=GridView::widget([
                'id'=>'crud-datatable',
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'pjax'=>true,
                //Para que no busque automaticamente, sino que espere a que se teclee ENTER
                'filterOnFocusOut'=>false,
                'columns' => $columns,

                'exportConfig'=> [
                             GridView::CSV=>[
                                 'label' => 'CSV',
                                 'icon' => '',
                                 // 'iconOptions' => '',
                                 'showHeader' => false,
                                 'showPageSummary' => false,
                                 'showFooter' => false,
                                 'showCaption' => false,
                                 'filename' => 'yii',
                                 'alertMsg' => 'created',
                                 'options' => ['title' => 'Semicolon -  Separated Values'],
                                 'mime' => 'application/csv',
                                 'config' => [
                                     'colDelimiter' => ";",
                                     'rowDelimiter' => "\r\n",
                                 ],
                             ],
                         ],

                'striped' => true,
                'condensed' => true,
                //Adaptacion para moviles
                'responsiveWrap' => false,
                'panel' => [
                    'type' => 'primary',
                    'heading' => '<i class="glyphicon glyphicon-list"></i> Lista de roles',
                    'before'=>'<em>* Para buscar algún registro tipear en el filtro y presionar ENTER </em>',
                    '<div class="clearfix"></div>',
                ]
            ])?>
        </div>
    </div>
    </div>
  </div>

  <div class="card-footer text-muted small">
      <?= Html::encode('Listado de equipos') ?>
  </div>
</div>

<?php Modal::begin([
    "id" => "ajaxCrudModal",
    "size"=> Modal::SIZE_LARGE,
    "footer" => "", // necesario para ajaxcrud
]) ?>
<?php Modal::end(); ?>
