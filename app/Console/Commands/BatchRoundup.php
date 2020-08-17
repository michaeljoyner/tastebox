<?php

namespace App\Console\Commands;

use App\DatePresenter;
use App\Mail\BatchRoundupSummary;
use App\Orders\Menu;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Spatie\Browsershot\Browsershot;

class BatchRoundup extends Command
{

    protected $signature = 'batch:round-up';


    protected $description = 'Send summary of current batch';


    public function __construct()
    {
        parent::__construct();
    }


    public function handle()
    {
        $batch = Menu::nextUp()->getBatch();
        $file_name = sprintf("shopping-lists/shopping_list_batch_%s.pdf", $batch->week);

        $html = view('admin.batches.shopping-list', [
            'batch_number' => $batch->week,
            'delivery_date' => DatePresenter::pretty($batch->deliveryDate()),
            'ingredients'  => $batch->ingredientList(),
        ])->render();

        Browsershot::html($html)
                   ->margins(25, 0, 25, 25)
                   ->setNodeBinary(config('browsershot.node_path'))
                   ->setNpmBinary(config('browsershot.npm_path'))
                   ->savePdf(storage_path("app/public/{$file_name}"));

        $message = new BatchRoundupSummary($batch, storage_path("app/public/{$file_name}"));

        Mail::to('test@test.test')->queue($message);
    }
}
