<style>
.equipo-header td {
    padding: 6px 10px;          /* un poco más de aire */
    font-size: 14px;            /* intermedio */
    vertical-align: middle;
    background-color: #fdfdfd;  /* fondo suave */
    color: black;
}
.mb-0{
    color: black            /* tono acorde al tema AdminLTE */
}
.bg-navy {
  background-color:white !important;
}
</style>

<div class="card card-primary shadow-sm">
    <div class="card-header py-2 px-3 bg-navy text-white">
        <h6 class="mb-0"> Encabezado del equipo</h6>
    </div>
    <div class="card-body p-2">
        <table class="table table-bordered table-hover equipo-header mb-0">
            <tr>
               <td><b>Id:</b> <?= $model->id?></td>
                <td><b>Marca:</b> <?= $model->marca ? $model->marca->nombre : '' ?></td>
                <td><b>Modelo:</b> <?= $model->modelo ? $model->modelo->nombre : '' ?></td>
                <td><b>Servicio:</b> <?= $model->servicio ? $model->servicio->nombre : '' ?></td>
            </tr>
            <tr>
               <td><b>Tipo de Equipo:</b> <?= $model->tipoequipo ? $model->tipoequipo->nombre : '' ?></td>
                <td><b>Código:</b> <?= $model->codigo ?></td>
                <td><b>F. Fabricación:</b> <?= Yii::$app->formatter->asDate($model->fechafabricacion, 'php:d/m/Y') ?></td>
                <td><b>F. Registro:</b> <?= Yii::$app->formatter->asDate($model->fecharegistro, 'php:d/m/Y') ?></td>
                <td></td>
            </tr>
        </table>
    </div>
</div>
