<?php

namespace Tests;

use App\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Storage;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function asAdmin(): self
    {
        $user = factory(User::class)->state('admin')->create();

        $this->actingAs($user);

        return $this;
    }

    public function asGuest(): self
    {
        $this->assertGuest();

        return $this;
    }

    public function fakeMedia()
    {
        Storage::fake('media', config('filesystems.disks.media'));
    }

    public function asJson($array, $column)
    {
        return function ($query) use ($column, $array) {
            $query->select($column);
            foreach ($array as $value) {
                $query->whereJsonContains($column, $value);
            }
        };
    }
}
