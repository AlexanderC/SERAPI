<?php
/**
 * Created by PhpStorm.
 * User: AlexanderC <alexanderc@pycoding.biz>
 * Date: 4/2/15
 * Time: 11:41
 */

require __DIR__ . '/../vendor/autoload.php';

$application = new Silex\Application();

// If you don't want to setup permissions the proper way, just uncomment the following PHP line
//umask(0000);

// This check prevents access to debug front controllers that are deployed by accident to production servers.
// Feel free to remove this, extend it, or make something more sophisticated.
if (!isset($_SERVER['HTTP_CLIENT_IP'])
    && !isset($_SERVER['HTTP_X_FORWARDED_FOR'])
    && (in_array(@$_SERVER['REMOTE_ADDR'], ['127.0.0.1', 'fe80::1', '::1']) || php_sapi_name() === 'cli-server')
) {
    $application['debug'] = true;
}

/** @var \SERAPI\Controller\AbstractController[] $controllers */
$controllers = [
    new \SERAPI\Controller\MainController()
];

foreach ($controllers as $controller) {
    $controller->setApplication($application);
    $controller->register();
}

$application->run();