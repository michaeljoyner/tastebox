<?php

namespace Tests\Feature\ActivityLogs;

use App\ActivityLog;
use App\Blog\Post;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use function factory;

class ActivityLogTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function starting_a_new_blog_post_creates_an_activity_log()
    {
        $this->withoutExceptionHandling();

        $admin = factory(User::class)->state('admin')->create();

        $response = $this->actingAs($admin)->postJson("/admin/api/blog", ['title' => 'test title']);
        $response->assertSuccessful();

        $post = Post::first();

        $this->assertCount(1, ActivityLog::all());
        $log = ActivityLog::first();

        $this->assertSame($admin->name, $log->actor);
        $this->assertTrue($log->created_at->isToday());
        $this->assertSame("/blog/posts/{$post->id}/edit", $log->url);
    }
}
