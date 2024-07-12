<?php

function local_transactions(): string
{
    $bool = update_paypal_storage(0, 1, true);
    $bool |= update_stripe_storage();
    $account = new Account();
    $account->getPaymentProcessor()->run();
    return $bool ? "true" : "false";
}