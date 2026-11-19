<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Official Bank Transfer Account Configuration
    |--------------------------------------------------------------------------
    |
    | Centralized bank account details for manual transfer payments.
    | Fallback defaults ensure the official company account is always present
    | even if environment variables are not yet defined.
    |
    */
    'bank' => [
        'name' => env('SUMATRA_BANK_NAME', 'BRI'),
        'account_number' => env('SUMATRA_BANK_ACCOUNT', '20601010804563'),
        'account_holder' => env('SUMATRA_BANK_HOLDER', 'AKRATAMA SADAWIRA GRUP'),
    ],
];
