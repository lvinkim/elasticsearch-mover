#!/usr/bin/env php
<?php
/**
 * Created by PhpStorm.
 * User: kingmax
 * Date: 13/11/2017
 * Time: 2:52 PM
 */

require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Console\Application;

$application = new Application();

$application->add(new App\Command\MoveCommand());

try {
    $application->run();
} catch (Exception $exception) {
    exit($exception->getMessage() . PHP_EOL);
}
