<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Eureka\Kernel\Http\Application\Application;
use Eureka\Kernel\Http\Kernel;

//~ Start session
session_start();

//~ Define Loader & add main classes for config
require_once __DIR__ . '/../vendor/autoload.php';

//~ Init kernel
$kernel = new Kernel(
    __DIR__ . '/..',
    getenv('EKA_ENV') ?: 'dev',
    true
);

//~ Run application
(new Application($kernel->getContainer(), $kernel->getConfig()))->run();
