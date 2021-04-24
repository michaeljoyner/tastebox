<?php


namespace App\Blog;


class PostInfo
{
    private string $title;
    private string $description;
    private string $intro;
    private string $body;

    public function __construct(array $info)
    {
        $this->title = $info['title'] ?? '';
        $this->description = $info['description'] ?? '';
        $this->intro = $info['intro'] ?? '';
        $this->body = $info['body'] ?? '';
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'intro' => $this->intro,
            'body' => $this->body,
        ];
    }
}
