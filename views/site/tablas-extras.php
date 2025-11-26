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
                <i class="fas fa-cogs mr-2" style="color: #6c757d;"></i>Tablas extras
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

      <div class="row justify-content-center">

        <!-- Card Modelo -->
        <div class="col-md-3 col-sm-6 mb-4">
          <div class="card shadow border-0 h-100 hover-card">
            <div class="card-body text-center">
              <div class="icon-circle mb-3 text-primary">
                <i class="fas fa-cube fa-2x animated-icon"></i>
              </div>
              <h6 class="font-weight-bold text-dark">Modelos</h6>
              <p class="text-muted small">Gestión de modelos</p>
              <a href="<?= Url::to(['/modelo/index']) ?>" class="btn btn-sm btn-primary btn-block">Ingresar</a>
            </div>
          </div>
        </div>

        <!-- Card Marca -->
        <div class="col-md-3 col-sm-6 mb-4">
          <div class="card shadow border-0 h-100 hover-card">
            <div class="card-body text-center">
              <div class="icon-circle mb-3 text-danger">
                <i class="fas fa-tag fa-2x animated-icon"></i>
              </div>
              <h6 class="font-weight-bold text-dark">Marcas</h6>
              <p class="text-muted small">Administrar marcas</p>
              <a href="<?= Url::to(['/marca/index']) ?>" class="btn btn-sm btn-danger btn-block">Ingresar</a>
            </div>
          </div>
        </div>

        <!-- Card Servicio -->
        <div class="col-md-3 col-sm-6 mb-4">
          <div class="card shadow border-0 h-100 hover-card">
            <div class="card-body text-center">
              <div class="icon-circle mb-3 text-success">
                <i class="fas fa-tools fa-2x animated-icon"></i>
              </div>
              <h6 class="font-weight-bold text-dark">Servicios</h6>
              <p class="text-muted small">Gestión de servicios</p>
              <a href="<?= Url::to(['/servicio/index']) ?>" class="btn btn-sm btn-success btn-block">Ingresar</a>
            </div>
          </div>
        </div>

        <!-- Card Tipo de Equipo -->
        <div class="col-md-3 col-sm-6 mb-4">
          <div class="card shadow border-0 h-100 hover-card">
            <div class="card-body text-center">
              <div class="icon-circle mb-3 text-warning">
                <i class="fas fa-desktop fa-2x animated-icon"></i>
              </div>
              <h6 class="font-weight-bold text-dark">Tipos de Equipo</h6>
              <p class="text-muted small">Categorías de equipos</p>
              <a href="<?= Url::to(['/tipoequipo/index']) ?>" class="btn btn-sm btn-warning btn-block text-white">Ingresar</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>
