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
        return $this->get('status') === 'success';
    }

    public function site(): string
    {
        return $this->get('site.name') ?? 'Unknown site';
    }

    public function repoLink(): string
    {
        return $this->get('commit_url');
    }

    public function commitMessage(): string
    {
        return $this->get('commit_message');
    }
}
