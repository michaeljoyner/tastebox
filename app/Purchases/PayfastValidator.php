<?php


namespace App\Purchases;


use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PayfastValidator implements ITNValidator
{

    const SANDBOX_URL = 'https://sandbox.payfast.co.za/eng/query/validate';
    const URL = 'https://www.payfast.co.za/eng/query/validate';

    public function isValid(array $data = []): bool
    {
        $url = config('payfast.sandbox', true) ? self::SANDBOX_URL : self::URL;
        $data = collect($data)->reject(fn ($val, $key) => $key === 'signature')->all();

        $response = Http::asForm()->post($url, $data);
        return trim($response->body()) === 'VALID';
    }
}
