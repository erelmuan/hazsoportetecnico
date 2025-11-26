<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap4\Modal;
use kartik\grid\GridView;
use hoaaah\ajaxcrud\CrudAsset;
use hoaaah\ajaxcrud\BulkButtonWidget;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TipoalertaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Tipoalertas');
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

?>
<div class="tipoalerta-index">
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
                    ['role'=>'modal-remote','title'=> 'Crear nuevo Tipo de alertas','class'=>'btn btn-default']).
                    Html::a('<i class="fas fa-sync-alt"></i>', [''],
                    ['data-pjax'=>1, 'class'=>'btn btn-default', 'title'=>'Refrescar']).
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
<div class="card-footer text-muted small">
<?= Html::encode('Listado de tipo de alerta') ?>
</div>
</div>
<?php Modal::begin([
"id"=>"ajaxCrudModal",
"footer"=>"",// always need it for jquery plugin
])?>
<?php Modal::end(); ?>
