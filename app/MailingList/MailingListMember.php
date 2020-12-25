<?php

namespace App\MailingList;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MailingListMember extends Model
{

    protected $fillable = ['name', 'email'];

    public static function subscribe(string $email, string $name)
    {
        return static::updateOrCreate(['email' => $email], ['name' => $name]);
    }
}
