<?php

namespace app\commands;

use Yii;
use yii\console\Controller;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;

        // Limpiar todo antes de crear
        $auth->removeAll();

        // 1️⃣ Crear permisos
        $createEquipo = $auth->createPermission('createEquipo');
        $createEquipo->description = 'Crear un equipo';
        $auth->add($createEquipo);

        $updateEquipo = $auth->createPermission('updateEquipo');
        $updateEquipo->description = 'Actualizar un equipo';
        $auth->add($updateEquipo);

        $deleteEquipo = $auth->createPermission('deleteEquipo');
        $deleteEquipo->description = 'Eliminar un equipo';
        $auth->add($deleteEquipo);

        $viewEquipo = $auth->createPermission('viewEquipo');
        $viewEquipo->description = 'Ver equipos';
        $auth->add($viewEquipo);

        // 2️⃣ Crear roles
        $lector = $auth->createRole('lector');
        $auth->add($lector);
        $auth->addChild($lector, $viewEquipo);

        $editor = $auth->createRole('editor');
        $auth->add($editor);
        $auth->addChild($editor, $viewEquipo);
        $auth->addChild($editor, $createEquipo);
        $auth->addChild($editor, $updateEquipo);

        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $editor);
        $auth->addChild($admin, $deleteEquipo);

        echo "RBAC inicializado ✅\n";
    }
}
