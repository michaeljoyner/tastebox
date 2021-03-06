<?php

namespace App\Console\Commands;

use App\DatePresenter;
use App\Mail\BatchRoundupSummary;
use App\Orders\Menu;
use App\Purchases\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
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
        $file_name = $batch->createShoppingListPdf();

        collect(['joyner.michael@gmail.com', 'alexandra.joyner@gmail.com', 'stephjoyner18@gmail.com'])
            ->each(function($recipient) use ($batch, $file_name) {
                $message = new BatchRoundupSummary($batch, Storage::disk('admin_stuff')->path($file_name), Order::hasCurrentPending());
                Mail::to($recipient)->queue($message);
            });

    }
}
