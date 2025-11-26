<?php

use yii\widgets\DetailView;
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $model app\models\Parametrizacion */
?>
<div class="parametrizacion-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'tiempo',
            'tipotiempo',
            'condicion',
            'modelo',
            'descripcion',
            [
                'attribute' => 'color',
                'format' => 'raw',
                'value' => function($model) {
                    $c = $model->color ?: '#000';
                    if (strpos($c, '#') !== 0) $c = '#'.$c;
                    return Html::tag('span', '', [
                        'style' => "display:inline-block; width:18px; height:18px; border:1px solid #ccc; margin-right:6px; vertical-align:middle; background:{$c};"
                    ]) . Html::encode($c);
                },
            ],
        ],
    ]) ?>

</div>
