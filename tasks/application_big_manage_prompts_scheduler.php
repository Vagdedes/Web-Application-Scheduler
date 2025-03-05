<?php

function application_big_manage_prompts_scheduler(): bool
{
    return BigManagePromptsAsyncScheduler::run();
}