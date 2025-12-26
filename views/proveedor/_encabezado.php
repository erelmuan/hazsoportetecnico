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
        <h6 class="mb-0"> Encabezado del proveedor  </h6>
    </div>
    <div class="card-body p-2">
        <table class="table table-bordered table-hover equipo-header mb-0">
            <tr>
               <td><b>Id:</b> <?= $model->id?></td>
                <td><b>razonsocial:</b> <?= $model->razonsocial ?></td>
                <td><b>sitioweb:</b> <?= $model->sitioweb  ?></td>
                <td><b>telefono:</b> <?= $model->telefono ?></td>
            </tr>
            <tr>
               <td><b>email:</b> <?= $model->email ?></td>
                <td><b>observacion:</b> <?= $model->observacion ?></td>
                <td></td>
            </tr>
        </table>
    </div>
</div>
