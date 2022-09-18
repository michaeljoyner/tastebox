<?php

namespace App\Console\Commands;

use App\Memberships\MemberProfile;
use Illuminate\Console\Command;

class UpdateMemberAddresses extends Command
{

    protected $signature = 'members:addresses';


    protected $description = 'Update addresses';


    public function handle()
    {
        MemberProfile::all()
                     ->each(function (MemberProfile $profile) {
                         $new_address = collect([
                             $profile->address_line_one,
                             $profile->address_line_two
                         ])
                             ->join(", ");
                         $profile->update(['address_line_one' => $new_address, 'address_line_two' => '']);
                     });

        return 0;
    }
}
