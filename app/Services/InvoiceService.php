<?php

namespace App\Services;

use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

/**
 * Generates unique invoice numbers in the format: INV/YYYYMMDD/XXXX
 *
 * The sequence (XXXX) resets each day and is padded to 4 digits.
 * Uses a DB-level lock to prevent race conditions on concurrent requests.
 */
class InvoiceService
{
    public function generate(): string
    {
        return DB::transaction(function (): string {
            $today = now()->format('Ymd');
            $prefix = "INV/{$today}/";

            // Lock the relevant rows so concurrent requests wait
            $lastInvoice = Transaction::where('invoice_number', 'like', $prefix.'%')
                ->lockForUpdate()
                ->orderByDesc('id')
                ->value('invoice_number');

            $sequence = 1;

            if ($lastInvoice !== null) {
                $lastSequence = (int) substr($lastInvoice, strrpos($lastInvoice, '/') + 1);
                $sequence = $lastSequence + 1;
            }

            return $prefix.str_pad((string) $sequence, 4, '0', STR_PAD_LEFT);
        });
    }
}
