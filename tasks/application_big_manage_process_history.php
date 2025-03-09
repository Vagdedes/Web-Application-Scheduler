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
    if (is_numeric($argv[1] ?? null)
        && is_numeric($argv[2] ?? null)
        && is_numeric($argv[3] ?? null)) {
        try {
            $team = (int)$argv[1];
            $account = (int)$argv[2];
            $history = (int)$argv[3];

            $object = new stdClass();
            $object->team_id = $team;
            $object->account_id = $account;
            $object->history_id = $history;

            $historyAsync = new BigManageHistoryAsync($object);
            $historyAsync->complete();
        } catch (Throwable $e) {
            BigManageError::storeThrowable(
                null,
                null,
                $e
            );
        }
    }
}