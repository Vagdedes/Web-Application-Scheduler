<?php

function refresh_transactions(): string
{
    set_time_limit(300);
    $bool = update_paypal_storage(0, 1, true);
    $bool |= update_stripe_storage();
    $account = new Account();
    $account->getPaymentProcessor()->run();
    return $bool ? "true" : "false";
}