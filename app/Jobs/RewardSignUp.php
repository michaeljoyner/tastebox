<?php

namespace App\Jobs;

use App\Orders\DiscountCodeInfo;
use App\Purchases\Discount;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RewardSignUp implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public function __construct(public User $member)
    {}


    public function handle()
    {
        $discountInfo = new DiscountCodeInfo([
            'code' => 'WELCOME!',
            'type' => Discount::LUMP,
            'valid_from' => now(),
            'valid_until' => now()->addMonth(),
            'value' => 100,
        ]);

        $this->member->awardDiscount($discountInfo);
    }
}
