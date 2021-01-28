<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SendSmsNotification extends Command
{

    protected $signature = 'sms:send';


    protected $description = 'Send an SMS';



    public function handle()
    {
        $message = Http::post("https://rest.nexmo.com/sms/json", [
            'from' => 'TASTEBOX',
            'text' => 'Hello darkness my old friend.',
            'to' => '27726703544',
            'api_key' => config('services.nexmo.key'),
            'api_secret' => config('services.nexmo.secret'),
        ]);
        return 0;
    }
}
