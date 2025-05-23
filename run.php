<?php
ini_set('memory_limit', '-1');
require '/root/vendor/autoload.php';
require_once '/root/schedulers/utilities/utilities.php';
require_once '/root/schedulers/utilities/sql.php';
require_once '/root/schedulers/utilities/communication.php';
require_once '/root/schedulers/utilities/evaluator.php';
$files = evaluator::run(
    array(
        "/var/www/.structure/library/hetzner/init.php",
        "/var/www/.structure/library/bigmanage/init.php"
    )
);

if (!empty($files)) {
    foreach ($files as $path) {
        require $path;
    }
    unset($argv[0]);
    $function = explode("/", array_shift($argv));
    $function = array_pop($function);
    $refreshSeconds = round(array_shift($argv) * 1_000_000);
    require_once '/root/schedulers/tasks/' . $function . ".php";
    clear_memory();

    while (true) {
        try {
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
        if ($refreshSeconds > 0) {
            usleep($refreshSeconds);
        }
    }
} else {
    echo "files\n";
}