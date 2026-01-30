<?php
//$db = require __DIR__ . '/db.php';
// test database! Important not to run tests on production or development databases
//$db['dsn'] = 'mysql:host=localhost;dbname=yii2basic_test';

//return $db;


return [
    'class' => 'yii\db\Connection',
    'dsn' => 'pgsql:host=localhost;port=5432;dbname=hazsoportetec',
    'username' => 'postgres',
    'password' => 'omega',
    'charset' => 'utf8',
];
