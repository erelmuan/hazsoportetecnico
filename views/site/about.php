<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $info array */

?>

<div class="site-about">

    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-info-circle"></i> Acerca del sistema</h3>
        </div>

        <div class="card-body">
            <p class="lead"><?= Html::encode($info['appName']) ?></p>

            <div class="row">
                <div class="col-md-6">
                    <dl class="row">
                        <dt class="col-sm-4">Versión</dt>
                        <dd class="col-sm-8"><?= Html::encode($info['version']) ?></dd>

                        <dt class="col-sm-4">Fecha de lanzamiento</dt>
                        <dd class="col-sm-8"><?= Html::encode($info['releaseDate']) ?></dd>

                        <dt class="col-sm-4">Autor / Equipo</dt>
                        <dd class="col-sm-8"><?= Html::encode($info['author']) ?></dd>

                        <dt class="col-sm-4">Contacto</dt>
                        <dd class="col-sm-8"><?= Html::mailto(Html::encode($info['contact']), $info['contact']) ?></dd>

                        <dt class="col-sm-4">Licencia</dt>
                        <dd class="col-sm-8"><?= Html::encode($info['license']) ?></dd>
                    </dl>
                </div>

                <div class="col-md-6">
                    <h5>Stack técnico</h5>
                    <ul>
                        <?php foreach ($info['tech'] as $t): ?>
                            <li><?= Html::encode($t) ?></li>
                        <?php endforeach; ?>
                    </ul>

                    <h5 class="mt-3">Enlaces rápidos</h5>
                    <ul>
                        <?php foreach ($info['links'] as $link): ?>
                            <li>
                                <?= Html::a(Html::encode($link['label']), $link['url'], ['target' => (strpos($link['url'], 'mailto:') === 0 ? '_self' : '_blank')]) ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <hr>

            <h5>Descripción</h5>
            <p>
                <?= Html::encode("{$info['appName']} es un sistema de gestión de soporte técnico orientado a gestionar equipos, logs de reparación y comunicaciones con proveedores. Permite trazabilidad de intervenciones, gestión de inventario, y generación de reportes.") ?>
            </p>

            <h6>Objetivo</h6>
            <p class="small text-muted">
                Brindar a los equipos técnicos la capacidad de registrar estados del equipo, gestionar envíos a proveedores y conservar un histórico de contactos utilizados por cada intervención.
            </p>
        </div>

        <div class="card-footer text-muted">
            Última actualización: <?= Html::encode($info['releaseDate']) ?>
        </div>
    </div>

</div>
