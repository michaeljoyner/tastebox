<?php


namespace App\Purchases;


use Illuminate\Support\Facades\Log;

class PayfastITN
{
    const COMPLETE = 'COMPLETE';

    public string $order_key;
    public int $payment_id;
    public bool $complete;
    public string $item;
    public string $description;
    public int $amount_gross;
    public int $amount_fee;
    public int $amount_net;
    public string $name_first;
    public string $name_last;
    public string $email;
    public int $merchant_id;
    public bool $signed;
    private array $original;

    public function __construct($data)
    {

        $this->order_key = $data['m_payment_id'];
        $this->payment_id = $data['pf_payment_id'];
        $this->complete = $data['payment_status'] === self::COMPLETE;
        $this->item = $data['item_name'];
        $this->description = $data['item_description'];
        $this->amount_gross = $data['amount_gross'] * 100;
        $this->amount_fee = abs($data['amount_fee'] * 100);
        $this->amount_net = $data['amount_net'] * 100;
        $this->name_first = $data['name_first'] ?? 'not provided';
        $this->name_last = $data['name_last'] ?? 'not provided';
        $this->email = $data['email'] ?? 'not provided';
        $this->merchant_id = $data['merchant_id'];
        $this->signed = $this->checkSignature($data);
        $this->original = $data;
    }

    private function checkSignature(array $data)
    {
        $expected = collect($data)
            ->reject(fn ($value, $key) => $key === 'signature')
            ->map(fn ($value, $key) => sprintf("%s=%s", $key, urlencode(trim($value))))
            ->join("&");

        $expected = $expected . "&passphrase=" . urlencode(trim(config('payfast.passphrase')));

        return md5($expected) === $data['signature'];
    }

    public function matchesOrderAmount(Order $order): bool
    {
        return abs($this->amount_gross - $order->price_in_cents) < 10;
    }

    public function validated()
    {
        return app(ITNValidator::class)->isValid($this->original);
    }

    public function isTrusted(Order $order)
    {
        if(!$this->signed) {
            Log::info('itn not signed');
            return false;
        }

        if(!$this->matchesOrderAmount($order)) {
            Log::info('itn amount does not match order');
            return false;
        }

        if(!$this->validated()) {
            Log::info('itn data not validated');
            return false;
        }

        return true;
//        return $this->signed && $this->matchesOrderAmount($order) && $this->validated();
    }
}
