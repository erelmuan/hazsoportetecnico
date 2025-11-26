<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],

    'modules' => [
      'rbac' => [
         'class' => 'yii2mod\rbac\Module',
         // opcional: tu layout AdminLTE
        'layout' => '@app/views/layouts/main.php', // usa tu layout existente
       ],

      'datecontrol' =>  [
         'class' => '\kartik\datecontrol\Module',
     ],
    'api' => [
            'class' => 'yii\base\Module',
            'controllerNamespace' => 'app\controllers\api',
        ],
    'gridview' => [  // Agregar este módulo
        'class' => '\kartik\grid\Module',
        // enter optional module parameters below - only if you need to
        // use your own export download action or custom translation
        // message source
         'downloadAction' => 'gridview/export/download',
         'i18n' => [],
        'bsVersion' => '4.x', // si estás usando Bootstrap 5, asegúrate de que la versión de Bootstrap esté configurada en params.php
    ],
    ],
    'language' => 'es-ES',          // Idioma actual (puede cambiarse dinámicamente)
    'sourceLanguage' => 'en-US',    // Idioma original de las cadenas

    'components' => [
      'view' => [
       'theme' => [
           'pathMap' => [
               // ruta del paquete vendor hacia tu carpeta de vistas
               '@vendor/yii2mod/yii2-rbac/views' => '@app/views/rbac',
               // por si hay otra ubicación en el paquete
               '@vendor/yii2mod/yii2-rbac/src/views' => '@app/views/rbac',
           ],
       ],
   ],
      'i18n' => [
             'translations' => [
                 'yii2mod.rbac' => [
                     'class' => 'yii\i18n\PhpMessageSource',
                     // Ajustá la ruta según donde quieras guardar los archivos de traducción:
                     'basePath' => '@app/messages',
                     'sourceLanguage' => 'en-US',
                     'fileMap' => [
                         'yii2mod.rbac' => 'yii2mod.rbac.php',
                     ],
                 ],
                 // opcional: para cubrir otras categorías del módulo
                 'yii2mod.rbac.*' => [
                     'class' => 'yii\i18n\PhpMessageSource',
                     'basePath' => '@app/messages',
                     'sourceLanguage' => 'en-US',
                 ],
             ],
         ],

      'assetManager' => [

    ],
         'db' => require __DIR__ . '/db.php',
'urlManager' => [
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'enableStrictParsing' => false, // Puedes mantenerlo en true si defines bien las reglas
    'rules' => [
        // Reglas REST API (mantenerlas primero)
        [
            'class' => 'yii\rest\UrlRule',
            'controller' => [
                'api/equipo','api/marca','api/modelo','api/servicio',
                'api/tipoequipo','api/color','api/tipoalerta',
                'api/parametrizacion','api/adjunto','api/log','api/usuario'
            ],
            'pluralize' => true, // Opcional: evita pluralización automática
        ],

        // 🔥 REGLAS FIJAS PARA AUTH (importante mantener el orden)
        'POST auth/login' => 'auth/login',
        'POST auth/logout' => 'auth/logout',
        'POST auth/request-reset' => 'auth/request-reset',
        'POST auth/reset-password' => 'auth/reset-password',

        // 🔥 NUEVAS REGLAS PARA CONTROLADORES WEB
        // Para URLs como: /equipo, /equipo/index
        '<controller:\w+>' => '<controller>/index',
        '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
        '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',

        // Regla específica para equipo si es necesario
        'equipo' => 'equipo/index',
        'equipo/<action:\w+>' => 'equipo/<action>',
        'equipo/<action:\w+>/<id:\d+>' => 'equipo/<action>',
    ],
],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'PGW2lkPvXGnMn4XGR39UWVeljsNgNLPJ',
                    'parsers' => [
            'application/json' => 'yii\web\JsonParser',
        ],
        ],

        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\Usuario',
            'enableAutoLogin' => true, // activar "recordar sesión"
            'loginUrl' => ['site/login'], // URL del login
            'identityCookie' => ['name' => '_identity', 'httpOnly' => true],
        ],


        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@app/mail',
            // send all mails to a file by default.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'authManager' => [
                'class' => 'yii\rbac\DbManager',
                'defaultRoles' => ['guest', 'user'],
            ],


    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
