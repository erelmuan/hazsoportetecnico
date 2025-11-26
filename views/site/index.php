<?php
use yii\helpers\Url;
use yii\helpers\HtmlPurifier;
?>

<div class="content">
  <div class="container-fluid">
    <div class="main-panel" style="padding-bottom:0; margin-bottom:0">

      <!-- Encabezado -->
      <div class="card shadow-sm mb-3" style="border: 1px solid #e0e0e0; border-radius: 4px;">
        <div class="card-body" style="background-color: #f8f9fa; padding: 15px;">
          <div class="row align-items-center">
            <div class="col-md-10">
              <h4 class="mb-0 font-weight-bold text-dark">
                <i class="fas fa-cogs mr-2" style="color: #6c757d;"></i>Panel principal
              </h4>
            </div>
          </div>
        </div>
      </div>

      <!-- Cards principales -->
      <div class="row justify-content-center cards-row" style="margin-bottom:0.5rem">
        <!-- Equipos -->
        <div class="col-md-3 col-sm-6 mb-2">
          <div class="card shadow border-0 h-100 text-center hover-card">
            <div class="card-body pb-3">
              <div class="icon-circle mb-2 text-primary">
                <i class="fas fa-cogs fa-2x animated-icon"></i>
              </div>
              <h5 class="font-weight-bold text-dark">Equipos</h5>
              <p class="small text-muted">Gestione sus equipos</p>
              <a href="<?= Url::to(['/equipo/index']) ?>" class="btn btn-primary btn-sm btn-block">Acceder</a>
            </div>
          </div>
        </div>

        <!-- Tablas Extras -->
        <div class="col-md-3 col-sm-6 mb-2">
          <div class="card shadow border-0 h-100 text-center hover-card">
            <div class="card-body pb-3">
              <div class="icon-circle mb-2 text-success">
                <i class="fas fa-table fa-2x animated-icon"></i>
              </div>
              <h5 class="font-weight-bold text-dark">Tablas Extras</h5>
              <p class="small text-muted">Configurar tablas adicionales</p>
              <a href="<?= Url::to(['/site/tablas-extras']) ?>" class="btn btn-success btn-sm btn-block">Acceder</a>
            </div>
          </div>
        </div>

        <!-- Parametrización -->
        <div class="col-md-3 col-sm-6 mb-2">
          <div class="card shadow border-0 h-100 text-center hover-card">
            <div class="card-body pb-3">
              <div class="icon-circle mb-2 text-warning">
                <i class="fas fa-sliders-h fa-2x animated-icon"></i>
              </div>
              <h5 class="font-weight-bold text-dark">Parametrización</h5>
              <p class="small text-muted">Gestione catálogos y tipos</p>
              <a href="<?= Url::to(['/parametrizacion/index']) ?>" class="btn btn-warning btn-sm btn-block text-white">Acceder</a>
            </div>
          </div>
        </div>

        <!-- Administración -->
        <div class="col-md-3 col-sm-6 mb-2">
          <div class="card shadow border-0 h-100 text-center hover-card">
            <div class="card-body pb-3">
              <div class="icon-circle mb-2 text-danger">
                <i class="fas fa-users-cog fa-2x animated-icon"></i>
              </div>
              <h5 class="font-weight-bold text-dark">Administración</h5>
              <p class="small text-muted">Usuarios, roles y permisos</p>
              <a href="<?= Url::to(['/site/administracion']) ?>" class="btn btn-danger btn-sm btn-block">Acceder</a>
            </div>
          </div>
        </div>
      </div>

      <div class="row mt-1">
        <!-- Últimos abiertos -->
        <div class="col-md-6">
          <div class="card shadow border-0 h-100">
            <div class="card-header bg-light py-2">
              <h6 class="mb-0"><i class="fa fa-plus mr-2"></i> Últimos creados</h6>
            </div>
            <div class="card-body p-0">
              <ul class="list-group list-group-flush">
                <?php foreach ($ultimosCreados as $item): ?>
                  <li class="list-group-item d-flex justify-content-between align-items-start py-2">
                    <div class="mr-3"><i class="fas fa-file-alt"></i></div>
                    <div class="flex-grow-1">
                      <div class="record-title"><?= \yii\helpers\Html::encode($item['titulo']) ?></div>
                      <div class="record-sub small text-muted"><?= 'Usuario: '. \yii\helpers\Html::encode($item['usuario'] . ' - Ip:  ' .HtmlPurifier::process($item['ip']) ) ?></div>
                    </div>
                    <div class="text-right">
                      <div class="record-badge small"><?= $item['fecha'] ?></div>
                      <a href=<?=strtolower($item['tabla'])."/view/".$item['registro']  ?> target= "_blank" class="btn btn-sm btn-outline-primary mt-1">Abrir</a>
                    </div>
                  </li>
                <?php endforeach; ?>
              </ul>
            </div>
          </div>
        </div>

        <!-- Últimos modificados -->
        <div class="col-md-6">
          <div class="card shadow border-0 h-100">
            <div class="card-header bg-light py-2">
              <h6 class="mb-0"><i class="fas fa-edit mr-2"></i> Últimos modificados</h6>
            </div>
            <div class="card-body p-0">
              <ul class="list-group list-group-flush">
                <?php foreach ($ultimosModificados as $item): ?>
                  <li class="list-group-item d-flex justify-content-between align-items-start py-2">
                    <div class="mr-3"><i class="fas fa-sync-alt"></i></div>
                    <div class="flex-grow-1">
                      <div class="record-title"><?= \yii\helpers\Html::encode($item['titulo']) ?></div>
                      <div class="record-sub small text-muted"><?= 'Usuario: '. \yii\helpers\Html::encode($item['usuario'] . ' - Ip:  ' .HtmlPurifier::process($item['ip']) ) ?></div>
                    </div>
                    <div class="text-right">
                      <div class="record-badge small"><?= $item['fecha'] ?></div>
                      <a href=<?=strtolower($item['tabla'])."/view/".$item['registro']  ?> target= "_blank" class="btn btn-sm btn-outline-primary mt-1">Abrir</a>
                    </div>
                  </li>
                <?php endforeach; ?>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Estilos adicionales para compactar aún más -->
<style>
  .main-panel { padding-bottom: 10px !important; margin-bottom: 10px !important; }
  .cards-row { margin-bottom: 0.25rem !important; }
  .col-md-3.col-sm-6.mb-2 { margin-bottom: 0.5rem !important; }
  .card-body.pb-3 { padding-bottom: .75rem !important; }
  .icon-circle.mb-2 { margin-bottom: .5rem !important; }
  .list-group-item { padding-top: .5rem !important; padding-bottom: .5rem !important; }
</style>
