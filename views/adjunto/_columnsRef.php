<?php
use yii\helpers\Url;
use yii\helpers\Html;
use app\components\MyActionColumn;

return [
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'id',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'nombreoriginal',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'observacion',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'tipocategoria',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'tipoarchivo',
    ],
    [
        'class' => MyActionColumn::class,
        'template' => '{view} {update} {deletereferencia} {unlink}',
        'buttons' => [
            'deletereferencia' => function ($url, $model, $key) {
                // Obtener el equipo_id desde la URL o desde alguna variable de sesión/parámetro
                $equipoId = Yii::$app->request->get('id_equipo');
                // Contar cuántos equipos están relacionados con este adjunto
                $cantidadRelaciones = \app\models\AdjuntoEquipo::find()
                    ->where(['id_adjunto' => $model->id])
                    ->count();
                // Si solo hay una relación, mostrar el botón de eliminar
                if ($cantidadRelaciones == 1) {
                  return Html::a(
                      '<span class="btn btn-danger btn-circle btn-sm"><i class="fas fa-trash"></i></span>',
                      ['deletereferencia', 'id' => $model->id, 'id_equipo' => $equipoId],
                      [
                          'title' => Yii::t('app', 'Eliminar'),
                          'role' => 'modal-remote',
                          'data-request-method' => 'post',
                          'data-toggle' => 'tooltip',
                          'data-method' => false ,// Clase específica
                          'data-confirm-title'=>'Confirmar',
                          'data-confirm-message'=> '¿Esta seguro de eliminar este elemento?',
                          'data-confirm-cancel'=>'Cancelar',
                          'data-confirm-ok'=>'Aceptar',

                      ]
                  );
                }
                // Si hay más de una relación, no mostrar el botón delete
                return '';
            },
            'unlink' => function ($url, $model, $key) {
                // Obtener el equipo_id desde la URL
                $equipoId = Yii::$app->request->get('id_equipo');
                // Contar cuántos equipos están relacionados con este adjunto
                $cantidadRelaciones = \app\models\AdjuntoEquipo::find()
                    ->where(['id_adjunto' => $model->id])
                    ->count();

                // Si hay más de una relación, mostrar el botón de desvincular
                if ($cantidadRelaciones > 1) {
                  return Html::a(
                      '<span class="btn btn-info btn-circle btn-sm"><i class="fas fa-unlink"></i></span>',
                      ['unlink', 'id' => $model->id, 'id_equipo' => $equipoId],
                      [
                          'title' => Yii::t('app', 'Desvincular'),
                          'role' => 'modal-remote',
                          'data-request-method' => 'post',
                          'data-toggle' => 'tooltip',
                          'data-method' => false ,// Clase específica
                          'data-confirm-title'=>'Confirmar',
                          'data-confirm-message'=> '¿Esta seguro de desvincular este elemento?',
                          'data-confirm-cancel'=>'Cancelar',
                          'data-confirm-ok'=>'Aceptar',

                          // 'data-confirm' => Yii::t('app', '¿Está seguro de desvincular este adjunto del equipo?'),
                      ]
                  );
                }

                // Si solo hay una relación, no mostrar el botón unlink
                return '';
            },
        ],
    ],
];
