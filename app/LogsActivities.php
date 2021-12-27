<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\MorphOne;

trait LogsActivities
{
    public function activityLogs(): MorphOne
    {
        return $this->morphOne(ActivityLog::class, 'actionable');
    }

    public function logActivity(string $actor, string $activity, string $url): ActivityLog
    {
        return $this->activityLogs()->create([
            'actor' => $actor,
            'activity' => $activity,
            'url' => $url,
        ]);
    }
}
