<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;

?>



<div class="detalle-expand" style="padding-left: 20px;padding-right: 20px;">

    <div style="font-size: 16px;padding-top: 4px;padding-bottom: 4px;"><b>Componentes</b></div>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'id'=>'crud-detail',
        'layout' => '{items}',

        'columns' => [
            'id',
            'nombre',
            [
              'label'=>'Tipo de componente',
              'attribute'=>'tipocomponente.nombre',
              ],
              'serie',
              'observacion',
            [
                'class' => 'kartik\grid\ActionColumn',
                'vAlign'=>'middle',
                'contentOptions' =>['width'=>'75px', 'style'=>'text-align:center;'],
                'header' => Html::a('Agregar',Yii::$app->urlManager->baseUrl.'/equipo/addcomponente?id_maestro='.$id_maestro, [
                    'title' => 'Agregar componente',
                    'class' => 'btn btn-success',
                    'style' => ['width'=>'75px', 'height'=>'30px','padding-top'=>'4px'],
                    'role' => 'modal-remote' ,
                    ]),

                'template' => '{reasigComponente}  {desvComponente}',
                'buttons' => [
                    'reasigComponente' => function ($url) {
                        return Html::a('<span class="fas fa-external-link-square-alt">&nbsp;</span>', $url,
                                ['role'=>'modal-remote','title'=> 'Reasignar el componente a otro equipo',]);
                        },
                        'desvComponente' => function ($url, $searchModel) {
                          return Html::a('<span class="fas fa-unlink">&nbsp;</span>', $url,
                                  ['role'=>'modal-remote','title'=> 'Desvincular el componente del equipo',]);
                        },
                    ],
                    'urlCreator' => function ($action, $searchModel) {

                    if ($action === 'reasigComponente') {
                        $url =Yii::$app->urlManager->baseUrl.'/equipo/reasigcomponente?id_componente='.$searchModel->id.'&id_maestro='.$searchModel->id_equipo;
                        return $url;
                    }
                    if ($action === 'desvComponente') {
                        return Url::to([
                            '/equipo/desvcomponente',
                            'id_componente' =>$searchModel->id,
                            'id_maestro' => $searchModel->id_equipo,
                        ]);
                    }
                },
            ],
        ],

        'striped' => true,
        'condensed' => true,
        //Adaptacion para moviles
        'responsiveWrap' => false,
    ]);

    ?>
</div>
