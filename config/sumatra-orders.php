<?php

use App\Models\Transaction;

return [
    // Confirm the actual Eloquent model used by CheckoutController before deployment.
    'model' => env('SUMATRA_ORDER_MODEL', Transaction::class),
    // Only authenticated Laravel users whose emails are explicitly allowed may access order administration.
    'admin_emails' => array_values(array_filter(array_map('trim', explode(',', (string) env('SUMATRA_ORDER_ADMIN_EMAILS', ''))))),
    // Check these names against your transactions table / CheckoutController implementation.
    'proof_column' => env('SUMATRA_ORDER_PROOF_COLUMN', 'payment_proof'),
    'proof_disk' => env('SUMATRA_ORDER_PROOF_DISK', 'public'),
];
