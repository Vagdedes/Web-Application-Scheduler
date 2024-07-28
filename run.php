<?php
ini_set('memory_limit', '-1');
require_once '/root/schedulers/utilities/utilities.php';
require_once '/root/schedulers/utilities/sql.php';
require_once '/root/schedulers/utilities/memory/init.php';
require_once '/root/schedulers/utilities/communication.php';
require_once '/root/schedulers/utilities/evaluator.php';
$files = evaluator::run();

if (!empty($files)) {
    foreach ($files as $file => $contents) {
        try {
            @eval($contents);
        } catch (Throwable $error) {
            var_dump($file . ": " . $error->getMessage());
        }
    }
    unset($argv[0]);
    $function = explode("/", array_shift($argv));
    $function = array_pop($function);
    $refreshSeconds = array_shift($argv);
    require_once '/root/schedulers/tasks/' . $function . ".php";

    while (true) {
        try {
            clear_memory();
            echo call_user_func_array($function, $argv) . "\n";
        } catch (Throwable $exception) {
            $trace = $exception->getTraceAsString();
            $file = fopen(
                "/root/schedulers/errors/exception_" . string_to_integer($trace, true) . ".txt",
                "w"
            );
            echo $trace . "\n";

            if ($file !== false) {
                fwrite($file, $trace);
                fclose($file);
            }
        }
        sleep($refreshSeconds);
    }
} else {
    echo "files\n";
}