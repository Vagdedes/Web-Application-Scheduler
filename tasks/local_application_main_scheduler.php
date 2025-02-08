<?php

function local_application_main_scheduler(): bool
{
    return BigManageHistoryAsyncScheduler::run();
}