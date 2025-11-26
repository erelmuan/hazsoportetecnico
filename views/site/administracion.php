<?php
use yii\helpers\Url;
use yii\helpers\Html;
?>


<div class="content">
  <div class="container-fluid">
    <!-- Panel Principal que hace contraste con el fondo -->
    <div class="main-panel">
      <!-- Cabecera con título y botón -->
      <div class="card shadow-sm mb-4" style="border: 1px solid #e0e0e0; border-radius: 4px;">
        <div class="card-body" style="background-color: #f8f9fa; padding: 20px;">
          <div class="row align-items-center">
            <div class="col-md-10">
              <h4 class="mb-0 font-weight-bold text-dark">
                <i class="fas fa-cogs mr-2" style="color: #6c757d;"></i>Administración
              </h4>
            </div>
            <div class="col-md-2 text-right">
              <?= Html::a('<i class="fas fa-arrow-left"></i> Atrás', ['site/index'], [
                  'class' => 'btn btn-secondary btn-sm'
              ]) ?>
            </div>
          </div>
        </div>
      </div>

      <!-- Cards de Módulos -->
      <div class="row justify-content-center">
        <!-- Card Usuarios -->
        <div class="col-md-3 col-sm-6 mb-4">
          <div class="card shadow-sm border h-100 hover-card" style="border-color: #dee2e6;">
            <div class="card-body text-center">
              <div class="icon-circle mb-3 text-primary">
                <i class="fas fa-user fa-2x animated-icon"></i>
              </div>
              <h6 class="font-weight-bold text-dark">Usuarios</h6>
              <p class="text-muted small">Administración de usuarios</p>
              <a href="<?= Url::to(['/usuario/index']) ?>" class="btn btn-sm btn-primary btn-block">Ingresar</a>
            </div>
          </div>
        </div>

        <!-- Card Control de Accesos -->
        <div class="col-md-3 col-sm-6 mb-4">
          <div class="card shadow-sm border h-100 hover-card" style="border-color: #dee2e6;">
            <div class="card-body text-center">
              <div class="icon-circle mb-3 text-success">
                <i class="fas fa-key fa-2x animated-icon"></i>
              </div>
              <h6 class="font-weight-bold text-dark">Control de Accesos</h6>
              <p class="text-muted small">Roles y permisos de acceso</p>
              <a href="<?= Url::to(['/site/controlacceso']) ?>" class="btn btn-sm btn-success btn-block">Ingresar</a>
            </div>
          </div>
        </div>

        <!-- Card Auditoría -->
        <div class="col-md-3 col-sm-6 mb-4">
          <div class="card shadow-sm border h-100 hover-card" style="border-color: #dee2e6;">
            <div class="card-body text-center">
              <div class="icon-circle mb-3 text-danger">
                <i class="fas fa-clipboard-list fa-2x animated-icon"></i>
              </div>
              <h6 class="font-weight-bold text-dark">Auditoría</h6>
              <p class="text-muted small">Registros y trazabilidad</p>
              <a href="<?= Url::to(['/auditoria/index']) ?>" class="btn btn-sm btn-danger btn-block">Ingresar</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
