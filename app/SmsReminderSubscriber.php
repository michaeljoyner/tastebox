<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsReminderSubscriber extends Model
{
    protected $fillable = ['name', 'cell_number'];

    public static function addOrUpdate(string $name, string $number): static
    {
        return static::updateOrCreate(['cell_number' => $number], ['name' => $name]);
    }
}
