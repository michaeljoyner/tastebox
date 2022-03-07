<?php

namespace App\Console\Commands;

use App\MailingList\MailingListMember;
use Illuminate\Console\Command;

class SyncNewsletter extends Command
{

    protected $signature = 'newsletter:sync';


    protected $description = 'Sync mailing list members with MailChimp';


    public function __construct()
    {
        parent::__construct();
    }


    public function handle()
    {

        MailingListMember::unsynced()
             ->get()
            ->each->syncToMailChimp();

        return 0;
    }
}
