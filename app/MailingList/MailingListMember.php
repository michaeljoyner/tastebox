<?php

namespace App\MailingList;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\Newsletter\NewsletterFacade;

class MailingListMember extends Model
{

    protected $fillable = ['name', 'email'];

    public static function subscribe(string $email, string $name)
    {
        return static::updateOrCreate(['email' => $email], ['name' => $name]);
    }

    public function scopeUnsynced(Builder $query)
    {
        return $query->whereNull('synced_at');
    }

    public function syncToMailChimp()
    {
        $firstName = Str::before($this->name, ' ');
        $lastName = Str::after($this->name, ' ');

        NewsletterFacade::subscribeOrUpdate($this->email, [
            'FNAME' => $firstName,
            'LNAME' => $lastName,
        ]);
    }
}
