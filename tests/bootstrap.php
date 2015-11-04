<?php
use Engine\DI\Factory as DI;
use Phalcon\Events\Manager as EventsManager;
use Engine\Config\Factory as Config;

session_start();

date_default_timezone_set('Europe/London');
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../src/helpers.php';

$em = new EventsManager();

$di = new DI();
$di->setEventsManager($em);
$di->setShared('eventsManager', $em);
DI::setDefault($di);

$config = new Config();
$di->setShared('config', $config);