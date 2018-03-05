<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Eureka\Kernel\Http\Application\ApplicationStatic;
use Eureka\Kernel\Http\Kernel;

//~ Start session
session_start();

//~ Define Loader
require_once __DIR__ . '/../vendor/autoload.php';

//~ Init kernel
$kernel = new Kernel(
    __DIR__ . '/..',
    getenv('EKA_ENV') ?: 'dev'
);

//~ Run application
(new ApplicationStatic($kernel->getContainer(), $kernel->getConfig()))->run();
