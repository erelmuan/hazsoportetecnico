<?php

use yii\helpers\Html;
?>
<div class="p-3">
    <div style="font-size:21px;">
        ¿Desea desvincular este componente del equipo?
    </div>
    <hr>
    <div class="text-right">

        <?= Html::button(
            'Cancelar',
            [
                'class' => 'btn btn-secondary',
                'data-dismiss' => 'modal'
            ]
        ) ?>
        <?= Html::button(
            '<i class="fas fa-unlink mr-1"></i> Desvincular',
            [
                'class' => 'btn btn-danger',
                'onclick' => "
                    desvincularComponente(
                        $id_componente,
                        $id_maestro
                    )
                "
            ]
        ) ?>

    </div>

</div>
