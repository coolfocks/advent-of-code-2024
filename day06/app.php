<?php

define('APP_ROOT_DIR', __DIR__);

ini_set('max_execution_time', '0');

require APP_ROOT_DIR . '/vendor/autoload.php';

$start = microtime(true);

try {
    $container = new \Stane\Day06\Container;
    $container->run();
    echo 'number of distinct positions player was on: ' . $container->provideActivePlayer()->getNumberOfDistinctStepsFromHistory() . PHP_EOL;
}
catch (Exception $e) {
    echo $e->getMessage() . PHP_EOL;
}

$finish = microtime(true);

$runTime = round(($finish - $start) * 1000, 0);

echo 'run time was ' . $runTime . ' ms (' . ($runTime / 1000) . ' s)' . PHP_EOL;
