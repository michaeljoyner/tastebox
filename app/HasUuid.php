<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;

trait HasUuid
{

    public static function bootHasUuid()
    {
        static::creating(function (Model $model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    public static function findByUuid(?string $uuid): ?self
    {
        return static::where('uuid', $uuid ?? "")->first();
    }

    public static function findOrFailByUuid(?string $uuid): static
    {
        $model = static::where('uuid', $uuid ?? "")->first();

        if(!$model) {
            throw (new ModelNotFoundException())->setModel(static::class, [$uuid]);
        }

        return $model;
    }
}
