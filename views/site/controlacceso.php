<?php
use yii\helpers\Url;
use yii\helpers\Html;

?>
  <div class="content">
    <div class="container-fluid">
      <!-- Botón de Atrás -->
      <div class="main-panel">
      <div class="card shadow-sm mb-4" style="border: 1px solid #e0e0e0; border-radius: 4px;">
        <div class="card-body" style="background-color: #f8f9fa; padding: 20px;">
          <div class="row align-items-center">
            <div class="col-md-10">
              <h4 class="mb-0 font-weight-bold text-dark">
                <i class="fas fa-cogs mr-2" style="color: #6c757d;"></i>Control de acceso
              </h4>
            </div>
            <div class="col-md-2 text-right">
              <?= Html::a('<i class="fas fa-arrow-left"></i> Atrás', ['site/administracion'], [
                  'class' => 'btn btn-secondary btn-sm'
              ]) ?>
            </div>
          </div>
        </div>
      </div>
      <div class="row justify-content-center">

        <!-- Card Routes -->
        <div class="col-md-3 col-sm-6 mb-4">
          <div class="card shadow border-0 h-100 hover-card">
            <div class="card-body text-center">
              <div class="icon-circle mb-3 text-info">
                <i class="fas fa-route fa-2x animated-icon"></i>
              </div>
              <h6 class="font-weight-bold text-dark">Rutas</h6>
              <p class="text-muted small">Definición de accesos a controladores/acciones</p>
              <a href="<?= Url::to(['/rbac/route']) ?>" class="btn btn-sm btn-info btn-block">Ingresar</a>
            </div>
          </div>
        </div>

        <!-- Card Permisos -->
        <div class="col-md-3 col-sm-6 mb-4">
          <div class="card shadow border-0 h-100 hover-card">
            <div class="card-body text-center">
              <div class="icon-circle mb-3 text-success">
                <i class="fas fa-key fa-2x animated-icon"></i>
              </div>
              <h6 class="font-weight-bold text-dark">Permisos</h6>
              <p class="text-muted small">Gestión de permisos individuales</p>
              <a href="<?= Url::to(['/rbac/permission']) ?>" class="btn btn-sm btn-success btn-block">Ingresar</a>
            </div>
          </div>
        </div>

        <!-- Card Roles -->
        <div class="col-md-3 col-sm-6 mb-4">
          <div class="card shadow border-0 h-100 hover-card">
            <div class="card-body text-center">
              <div class="icon-circle mb-3 text-primary">
                <i class="fas fa-users-cog fa-2x animated-icon"></i>
              </div>
              <h6 class="font-weight-bold text-dark">Roles</h6>
              <p class="text-muted small">Agrupación de permisos y jerarquías</p>
              <a href="<?= Url::to(['/rbac/role']) ?>" class="btn btn-sm btn-primary btn-block">Ingresar</a>
            </div>
          </div>
        </div>

        <!-- Card Asignaciones -->
        <div class="col-md-3 col-sm-6 mb-4">
          <div class="card shadow border-0 h-100 hover-card">
            <div class="card-body text-center">
              <div class="icon-circle mb-3 text-warning">
                <i class="fas fa-user-tag fa-2x animated-icon"></i>
              </div>
              <h6 class="font-weight-bold text-dark">Asignaciones</h6>
              <p class="text-muted small">Asignar roles a usuarios</p>
              <a href="<?= Url::to(['/rbac/assignment']) ?>" class="btn btn-sm btn-warning btn-block">Ingresar</a>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>
