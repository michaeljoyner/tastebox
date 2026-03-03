<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Slack\BlockKit\Blocks\SectionBlock;
use Illuminate\Notifications\Slack\SlackMessage;

class ForgeDeployment extends Notification
{
    use Queueable;

    public function __construct(
        public bool $success,
        public string $siteName,
        public ?string $repoUrl = null,
    ) {}

    public function via(object $notifiable): array
    {
        return ['slack'];
    }

    public function toSlack(object $notifiable): SlackMessage
    {
        $title = $this->success ? '✅ Deployed Succesfully' : '❌ Deployment Failed';

        return (new SlackMessage)
            ->text($title)
            ->sectionBlock(function (SectionBlock $block) {
                $block->text('Details');
                $block->field("*Site:*\n{$this->siteName}")->markdown();
                if ($this->repoUrl) {
                    $block->field("*Commit deployed:*\n[GitHub]({$this->repoUrl})")->markdown();
                }
            });
    }
}
