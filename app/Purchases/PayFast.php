<?php


namespace App\Purchases;


use Illuminate\Support\Facades\Log;

class PayFast
{

    const KNOWN_DOMAINS = [
        'www.payfast.co.za',
        'sandbox.payfast.co.za',
        'w1w.payfast.co.za',
        'w2w.payfast.co.za',
    ];

    public static function checkoutForm(Order $order)
    {
        $fields = [
            'merchant_id'        => config('payfast.merchant_id'),
            'merchant_key'       => config('payfast.merchant_key'),
            'return_url'         => config('payfast.return_url') . "/{$order->order_key}",
            'cancel_url'         => config('payfast.cancel_url') . "/{$order->order_key}",
            'notify_url'         => config('payfast.notify_url') . "/{$order->order_key}",
//            'notify_url'         => "https://5d09-122-118-46-189.ngrok.io/payfast/notify/{$order->order_key}",
            'name_first'         => $order->first_name,
            'name_last'          => $order->last_name,
            'email_address'      => $order->email,
            'cell_number'        => $order->phone,
            'm_payment_id'       => $order->order_key,
            'amount'             => $order->price_in_cents / 100,
            'item_name'          => 'Tastebox order',
            'item_description'   => $order->orderedKits->count() . ' x Mealkits',
            'email_confirmation' => 0,
        ];

        if(!self::recognizesCellNumber($fields['cell_number'])) {
            unset($fields['cell_number']);
        }

        return array_merge($fields, static::sign($fields));
    }

    private static function recognizesCellNumber(string $number): bool
    {
        return !! preg_match('/(079|082){1}[\d]{7}/', $number);
    }

    private static function sign(array $data)
    {
        $signature = collect($data)
            ->map(fn($value, $key) => sprintf("%s=%s", $key, urlencode(trim($value))))
            ->join("&");

        return [
            'signature' => md5($signature . "&passphrase=" . urlencode(trim(config('payfast.passphrase')))),
        ];
    }

    public static function checkSignature(array $data)
    {
        $expected = collect($data)
            ->reject(fn($value, $key) => $key === 'signature')
            ->map(fn($value, $key) => sprintf("%s=%s", $key, urlencode(trim($value))))
            ->join("&");

        $expected = $expected . "&passphrase=" . urlencode(trim(config('payfast.passphrase')));

        return md5($expected) === $data['signature'];
    }

    public static function recognizesIP($ip)
    {
        $result =  collect(self::KNOWN_DOMAINS)
            ->map(fn($domain) => gethostbynamel($domain))
            ->filter(fn($ip_list) => $ip_list)
            ->flatMap(fn($ip_list) => $ip_list)
            ->unique()
            ->contains($ip);

        if(!$result) {
            Log::info("{$ip} not recognized as payfast ip");
        }

        return $result;
    }
}
