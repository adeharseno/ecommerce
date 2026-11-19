<?php

namespace App\Http\Requests\Front;

use Illuminate\Foundation\Http\FormRequest;

class StoreCheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('customer_phone')) {
            $phone = trim((string) $this->input('customer_phone'));
            if (str_starts_with($phone, '0')) {
                $phone = '+62'.substr($phone, 1);
            } elseif (str_starts_with($phone, '8')) {
                $phone = '+62'.$phone;
            } elseif (str_starts_with($phone, '62') && ! str_starts_with($phone, '+62')) {
                $phone = '+'.$phone;
            }

            $this->merge([
                'customer_phone' => $phone,
            ]);
        }
    }

    /** @return array<string, list<string>> */
    public function rules(): array
    {
        return [
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:20'],
            'shipping_address' => ['required', 'string', 'max:1000'],
        ];
    }

    /** @return array<string, string> */
    public function attributes(): array
    {
        return [
            'customer_name' => 'nama',
            'customer_phone' => 'nomor telepon',
            'shipping_address' => 'alamat pengiriman',
        ];
    }
}
