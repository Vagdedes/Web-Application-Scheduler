<?php
ini_set('memory_limit', '-1');
require_once '/root/schedulers/utilities/utilities.php';
require_once '/root/schedulers/utilities/sql.php';
require_once '/root/schedulers/utilities/communication.php';
require_once '/root/schedulers/utilities/evaluator.php';
require_once '/root/schedulers/utilities/AbstractMethodReply.php';
$files = evaluator::run(
    array(
        "/var/www/.structure/library/hetzner/init.php",
        "/var/www/.structure/library/cloudflare/init.php",
         "/var/www/.structure/library/base/objects/AbstractMethodReply.php"
    )
);

if (!empty($files)) {
    foreach ($files as $path) {
        require $path;
    }
    unset($argv[0]);
    $function = explode("/", array_shift($argv));
    $function = array_pop($function);
    $refreshSeconds = array_shift($argv);
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
        sleep($refreshSeconds);
    }
} else {
    echo "files\n";
}