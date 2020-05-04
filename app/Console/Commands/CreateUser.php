<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateUser extends Command
{
    protected $signature = 'users:add {--name=} {--email=} {--password=}';

    protected $description = 'Add a new admin user';

    public function handle()
    {
        User::addAdmin([
            'name' => $this->option('name'),
            'email' => $this->option('email'),
            'password' => Hash::make($this->option('password')),
        ]);
    }
}
