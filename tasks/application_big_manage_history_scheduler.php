<?php

function application_big_manage_history_scheduler(): bool
{
    return BigManageHistoryAsyncScheduler::run();
}