<?php use yii\helpers\Html; ?>

<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/hazsoportetec/backend/web/" class="brand-link">
        <img src="<?=$assetDir?>/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Haz soporte tecnico</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="<?=$assetDir?>/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block"><?= Yii::$app->user->identity->nombreapellido ?></a>
            </div>
        </div>

        <!-- Sidebar (reemplazar el <nav class="mt-2"> anterior) -->
  <!-- Search (sidebar) -->
  <div class="form-inline mt-2 mb-2 px-3">
      <form action="<?= \yii\helpers\Url::to(['site/search']) ?>" method="get" class="input-group" style="width:100%;">
          <input class="form-control form-control-sidebar" type="search" name="q" placeholder="Buscar..." aria-label="Buscar">
          <div class="input-group-append">
              <button class="btn btn-sidebar" type="submit">
                  <i class="fas fa-search fa-fw"></i>
              </button>
          </div>
      </form>
  </div>

  <nav class="mt-2">
  <?php
  echo \hail812\adminlte\widgets\Menu::widget([
      'items' => [
          // Dashboard / Inicio
          [
              'label' => 'Inicio',
              'icon' => 'tachometer-alt',
              'url' => ['site/index'],
          ],

          // Modelos principales (tu listado)
          ['label' => 'Panel principal', 'header' => true],
          ['label' => 'Equipos',   'icon' => 'cogs', 'iconClassAdded' => 'text-primary', 'url' => ['/equipo']],
          ['label' => 'Tablas extras','icon' => 'table', 'iconClassAdded' => 'text-success',
          'items' => [
            ['label' => 'Marcas',           'icon' => 'tag',              'url' => ['/marca']],
            ['label' => 'Modelos (equipos)','icon' => 'layer-group',     'url' => ['/modelo']],
            ['label' => 'Servicios/Sectores',        'icon' => 'concierge-bell',   'url' => ['/servicio']],
            ['label' => 'Tipo de equipo',   'icon' => 'tools',            'url' => ['/tipoequipo']],
            ['label' => 'Proveedor',   'icon' => 'truck',            'url' => ['/proveedor']],
            ],
          ],
          ['label' => 'Parametrización', 'icon' => 'sliders-h','iconClassAdded' => 'text-warning', 'url' => ['/parametrizacion']
          ],
          ['label' => 'Administración', 'icon' => 'users-cog', 'iconClassAdded' => 'text-danger',
          'items' => [
            ['label' => 'Usuarios',           'icon' => 'tag',              'url' => ['/usuario']],
            ['label' => 'controlacceso','icon' => 'layer-group',     'url' => ['/site/controlacceso']],
            ['label' => 'Auditoria',        'icon' => 'concierge-bell',   'url' => ['/auditoria']],
          ],
        ],

          // Reportes y recursos
          ['label' => 'Recursos', 'header' => true],
          [
              'label' => 'Reportes',
              'icon' => 'chart-line',
              'items' => [
                  ['label' => 'Reportes generales', 'icon' => 'file-alt', 'url' => ['/reportes/index']],
                  ['label' => 'Reporte de estados',  'icon' => 'exclamation-circle', 'url' => ['/reportes/estados']],
                  ['label' => 'Exportar (PDF/Excel)', 'icon' => 'file-export', 'url' => ['/reportes/export']],
              ],
          ],
          [
              'label' => 'Herramientas',
              'icon' => 'wrench',
              'items' => [
                  ['label' => 'Buscador de archivos', 'icon' => 'search', 'url' => ['/tools/file-search']],
                  ['label' => 'Calendario',           'icon' => 'calendar-alt', 'url' => ['/calendar/index']],
                  ['label' => 'Explorador (File Manager)', 'icon' => 'folder-open', 'url' => ['/tools/file-manager']],
                  ['label' => 'Importar / Exportar', 'icon' => 'upload', 'url' => ['/tools/import-export']],
              ],
          ],

          // Ayuda / doc
          ['label' => 'Ayuda', 'header' => true],
          ['label' => 'Documentación', 'icon' => 'book', 'url' => ['/site/documentacion']],
          ['label' => 'Acerca de',     'icon' => 'info-circle', 'url' => ['/site/about']],

      ],
  ]);
  ?>

  <div class="mt-3 px-2 sidebar-logout-wrapper">
      <?= Html::beginForm(['/site/logout'], 'post', ['class' => 'd-block'])
          . Html::submitButton(
              '<i class="fas fa-sign-out-alt"></i><span class="logout-label"> Salir</span>',
              [
                  'class' => 'btn btn-danger btn-block sidebar-logout-btn',
                  'title' => 'Salir',                 // tooltip nativo
                  'aria-label' => 'Salir',            // accesibilidad
              ]
          )
          . Html::endForm(); ?>
  </div>
  </nav>
        <!-- /.sidebar-menu -->
    </div>

    <!-- /.sidebar -->
</aside>
