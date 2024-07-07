<?php
unset($argv[0]);
$function = array_shift($argv);
require_once '/root/schedulers/tasks/' . $function . ".php";
call_user_func_array($function, $argv);