<?php

function refresh_transactions(): void
{
    set_time_limit(300);
    update_paypal_storage(0, 1, true);
    update_stripe_storage();
    $account = new Account();
    $account->getPaymentProcessor()->run();
}