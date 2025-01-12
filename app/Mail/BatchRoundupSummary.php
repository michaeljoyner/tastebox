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
    private bool $has_pending;

    public function __construct(Batch $batch, string $shopping_list, bool $has_pending)
    {
        $this->batch = $batch;
        $this->shopping_list = $shopping_list;
        $this->has_pending = $has_pending;
    }


    public function build()
    {
        return $this
            ->from('tastebox@tastebox.co.za', 'TasteBox HQ')
            ->subject($this->batch->name() . ": Roundup")
            ->markdown('email.admin.batch-summary', [
                'total_kits'  => $this->batch->totalKits(),
                'total_meals' => $this->batch->totalPackedMeals(),
                'total_add_ons' => collect($this->batch->addOnList())->sum(fn ($addOn) => $addOn['qty']),
                'pending' => $this->has_pending,
            ])
            ->attach($this->shopping_list);
    }
}
