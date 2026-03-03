<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ForgeDeploymentHookRequest extends FormRequest
{
    public function rules(): array
    {
        return [
        ];
    }

    public function successful(): bool
    {
        return $this->input('status') === 'success';
    }

    public function site(): string
    {
        return $this->input('site.name') ?? 'Unknown site';
    }

    public function repoLink(): string
    {
        return $this->input('commit_url');
    }

    public function commitMessage(): string
    {
        return $this->input('commit_message');
    }
}
