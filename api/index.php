<?php

require 'Config/config.php';

use \Phalcon\Loader,
    \Phalcon\DI\FactoryDefault,
    \Phalcon\Db\Adapter\Pdo\Mysql,
    \Phalcon\Mvc\Collection\Manager,
    \Phalcon\Mvc\Micro;

/*
  .____    ________  _________     _____  .____     ___ ___ ________    ____________________
  |    |   \_____  \ \_   ___ \   /  _  \ |    |   /   |   \\_____  \  /   _____/\__    ___/
  |    |    /   |   \/    \  \/  /  /_\  \|    |  /    ~    \/   |   \ \_____  \   |    |
  |    |___/    |    \     \____/    |    \    |__\    Y    /    |    \/        \  |    |
  |_______ \_______  /\______  /\____|__  /_______ \___|_  /\_______  /_______  /  |____|
  \/       \/        \/         \/        \/     \/         \/        \/
 */

/**
 * index.php
 * 
 * This file is a app handler for a Phalcon PHP - API REST
 * 
 * PHP version 5.3
 * 
 * @author     Diego Luis Restrepo <diegoluisr@gmail.com>
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    GIT: $Id$
 * @since      0.0.1
 */
$loader = new Loader();

$loader->registerNamespaces(array(
        'Api\Models' => __DIR__ . '/models/',
        'Api\Controllers' => __DIR__ . '/controllers/',
        'Api\Modules' => __DIR__ . '/modules',
    )
);

$loader->register();

$di = new FactoryDefault();

$di->set('db', function() {
    return new Mysql(array(
        "dsn" => DB_DSN,
        "username" => DB_USER,
        "password" => DB_PASS,
    ));
});


$di->set('collectionManager', function() {
    return new Manager();
});

$app = new Micro($di);

$di->set('collections', function() {
    return include('./config/routes.php');
});

foreach ($di->get('collections') as $collection) {
    $app->mount($collection);
}

$app->before(function() use ($app) {
    $app->response->setContentType('application/json');

    $origin = $app->request->getHeader("ORIGIN") ? $app->request->getHeader("ORIGIN") : '*';

    $app->response->setHeader("Access-Control-Allow-Origin", $origin)
        ->setHeader("Access-Control-Allow-Credentials", 'true');
});

$app->after(function() use ($app) {
    
});

$app->options('/{catch:(.*)}', function() use ($app) {
    $app->response->setStatusCode(200, "Ok")
            ->setContentType('application/json')
            ->send();
});

$app->notFound(function () use ($app) {
    $app->response->setStatusCode(404, "Not Found")
            ->setContent(json_encode(array('message' => 'not found')))
            ->send();
});

$app->handle();