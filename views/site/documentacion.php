<?php
// views/site/tutorials.php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */

// Array hardcodeado de tutoriales (modificalo a gusto)
$items = [
    [
        'title' => 'Equipos',
        'desc'  => 'Cómo crear, editar y dar de baja equipos. Gestión de operatividad.',
        'url'   =>  Url::to(['/uploads/documentacion/GUIA-SOBRE-EQUIPOS.pdf']),
        'icon'  => 'fas fa-cogs',
    ],
    [
        'title' => 'Logs',
        'desc'  => 'Registrar entradas y salidas de equipos; manejo de estados y envíos a proveedores.',
        'url'   =>  Url::to(['/uploads/documentacion/GUIA-SOBRE-LOS-LOGS.pdf']),
        'icon'  => 'fas fa-book-open',
    ],
    [
        'title' => 'Proveedores/Contactos',
        'desc'  => 'Agregar proveedores y contactos; uso en envíos y snapshots históricos.',
        'url'   =>  Url::to(['/uploads/documentacion/GUIA-SOBRE-PROVEEDORES-CONTACTOS.pdf']),
        'icon'  => 'fas fa-truck',
    ],

    [
        'title' => 'Adjuntos',
        'desc'  => 'Subir/visualizar archivos relacionados a equipos y logs.',
        'url'   =>  Url::to(['/uploads/documentacion/GUIA-SOBRE-ADJUNTOS.pdf']),
        'icon'  => 'fas fa-paperclip',
    ],
    [
        'title' => 'Parametrización',
        'desc'  => 'Colores, tiempos y reglas que afectan visualización y validaciones.',
        'url'   => Url::to(['']),
        'icon'  => 'fas fa-sliders-h',
    ],
    [
        'title' => 'Reportes',
        'desc'  => 'Exportes a PDF/Excel y reportes por estado, equipo o proveedor.',
        'url'   => Url::to(['']),
        'icon'  => 'fas fa-chart-line',
    ],
    [
        'title' => 'Usuarios y RBAC',
        'desc'  => 'Gestión de usuarios, roles y permisos.',
        'url'   => Url::to(['']),
        'icon'  => 'fas fa-users-cog',
    ],
];
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
              <i class="fas fa-cogs mr-2" style="color: #6c757d;"></i>
              Documentacion / Guía rápida
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

<div class="tutorials-index">

    <div class="row mb-3">
        <div class="col-md-8">
            <h1 class="h3"><?//= Html::encode($this->title) ?></h1>
            <p class="text-muted">Guía rápida con enlaces a las secciones más relevantes del sistema. Más adelante se podrá administrar desde la base de datos.</p>
        </div>
        <div class="col-md-4 text-right align-self-center">
            <?= Html::a('Añadir tutorial (admin)', ['tutorial/create'], ['class' => 'btn btn-outline-primary btn-sm']) ?>
        </div>
    </div>

    <div class="row">
        <?php foreach ($items as $it): ?>
            <div class="col-md-6 col-lg-4 mb-3">
                <div class="card h-100 shadow-sm">
                    <div class="card-body d-flex">
                        <div class="pr-3" style="min-width:48px; display:flex; align-items:center; justify-content:center;">
                            <div style="width:48px; height:48px; border-radius:8px; display:flex; align-items:center; justify-content:center; background:#f5f7fa;">
                                <i class="<?= Html::encode($it['icon']) ?>" style="font-size:20px;"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="card-title mb-1"><?= Html::encode($it['title']) ?></h5>
                            <p class="card-text small text-muted mb-2"><?= Html::encode($it['desc']) ?></p>
                            <p class="mb-0">
                                <?= Html::a('Ver instructivo', $it['url'], ['class' => 'btn btn-sm btn-primary', 'target' => '_blank', 'rel' => 'noopener']) ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="row mt-4">
        <div class="col-12 text-muted small">
            <strong>Nota:</strong> Esta lista es temporal (hardcodeada). Más adelante podrás gestionar los manuales desde un módulo de documentación.
        </div>
    </div>
</div>
  </div>
    </div>
