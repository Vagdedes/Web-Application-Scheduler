<?php
ini_set('memory_limit', '-1');
require_once '/root/schedulers/utilities/utilities.php';
require_once '/root/schedulers/utilities/sql.php';
require_once '/root/schedulers/utilities/communication.php';
require_once '/root/schedulers/utilities/evaluator.php';
$files = evaluator::run(
    array(
        "/var/www/.structure/library/bigmanage/init.php"
    )
);

if (!empty($files)) {
    foreach ($files as $path) {
        require $path;
    }
    if (is_numeric($argv[1] ?? null)) {
        $identifier = (int)$argv[1];
        BigManagePromptsAsyncScheduler::process($identifier);
    }
}