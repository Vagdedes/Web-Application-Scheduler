<?php
require_once '/root/schedulers/utilities/utilities.php';
require_once '/root/schedulers/utilities/sql.php';
require_once '/root/schedulers/utilities/sql_connection.php';
require_once '/root/schedulers/utilities/memory/init.php';
require_once '/root/schedulers/utilities/communication.php';
require_once '/root/schedulers/utilities/LoadBalancer.php';
$files = LoadBalancer::getFiles(
    array(
        "/var/www/.structure/library/account",
        "/var/www/.structure/library/polymart",
        "/var/www/.structure/library/patreon",
        "/var/www/.structure/library/paypal",
        "/var/www/.structure/library/discord",
        "/var/www/.structure/library/stripe",
        "/var/www/.structure/library/builtbybit",
        "/var/www/.structure/library/phone",
        "/var/www/.structure/library/email",
        "/var/www/.structure/library/gameCloud",
        "/var/www/.structure/library/base/placeholder.php",
        "/var/www/.structure/library/base/minecraft.php",
        "/var/www/.structure/library/base/encrypt.php",
        "/var/www/.structure/library/base/objects"
    )
);
if (!empty($files)) {
    $email_credentials_directory = "/root/schedulers/private/credentials/email_credentials";
    $patreon2_credentials_directory = "/root/schedulers/private/credentials/patreon_2_credentials";

    foreach ($files as $file) {
        eval($file);
    }
    unset($argv[0]);
    $function = array_shift($argv);

    if (start_memory_process($function)) {
        require_once '/root/schedulers/tasks/' . $function . ".php";

        try {
            echo call_user_func_array($function, $argv) . "\n";
        } catch (Throwable $exception) {
            // todo
        }
    } else {
        echo "process\n";
    }
} else {
    echo "files\n";
}