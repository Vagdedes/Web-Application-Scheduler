<?php

function local_application_big_manage_main_scheduler(): bool
{
    return BigManageHistoryAsyncScheduler::run();
}