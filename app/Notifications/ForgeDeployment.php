<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\SlackAttachment;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class ForgeDeployment extends Notification
{
    use Queueable;

    public function __construct(
        public bool $success,
        public string $siteName,
        public string $commitMessage,
        public string $repoUrl,
    ) {}

    public function via(object $notifiable): array
    {
        return ['slack'];
    }

    public function toSlack(object $notifiable): SlackMessage
    {
        $title = $this->success ? '✅ Deployed Succesfully' : '❌ Deployment Failed';

        return (new SlackMessage)
            ->content($title)
            ->attachment(function (SlackAttachment $attachment) {
                $attachment->title('Deployement')
                    ->content('Details:')
                    ->color($this->success ? 'good' : 'bad')
                    ->fields([
                        'Site' => $this->siteName,
                        'Commit' => "[{$this->commitMessage}]({$this->repoUrl})",
                    ]);
            });
    }
}
