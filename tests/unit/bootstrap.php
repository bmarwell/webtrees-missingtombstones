<?php

include __DIR__ . '/../../vendor/autoload.php';

$kernel = \AspectMock\Kernel::getInstance();
$kernel->init([
    'appDir' => __DIR__ . '/../../vendor/fisharebest/webtrees/app',
    'debug' => true,
    'cacheDir' => __DIR__ . '/../../data/cache',
    'includePaths' => [
      __DIR__ . '/../../src',
      __DIR__ . '/../../vendor/fisharebest/webtrees',
      __DIR__ . '/../../vendor/psr/http-message',
    ],
    //'excludePaths' => [__DIR__ . '/../../vendor'],
]);
