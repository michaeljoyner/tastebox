<?php

namespace App\Mail;

use App\Orders\Batch;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BatchRoundupSummary extends Mailable
{
    use Queueable, SerializesModels;

    public Batch $batch;
    private string $shopping_list;

    public function __construct(Batch $batch, string $shopping_list)
    {
        $this->batch = $batch;
        $this->shopping_list = $shopping_list;
    }


    public function build()
    {
        return $this
            ->from('the-all-seeing-eye@testbox.co.za', 'Tastebox HQ')
            ->subject($this->batch->name() . ": Roundup")
            ->markdown('email.admin.batch-summary', [
                'total_kits'  => $this->batch->totalKits(),
                'total_meals' => $this->batch->totalPackedMeals(),
            ])
            ->attach($this->shopping_list);
    }
}
