<?php

namespace Tests\Unit\Blog;

use App\Blog\Post;
use App\Blog\PostInfo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostsTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function creating_a_new_post_generates_a_slug()
    {
        $postInfo = new PostInfo([
            'title' => 'test title',
            'description' => 'test description',
            'intro' => 'test intro',
            'body' => 'test body',
        ]);

        $post = Post::new($postInfo);

        $this->assertSame('test-title', $post->slug);
        $this->assertSame('test title', $post->title);
    }

    /**
     *@test
     */
    public function a_slug_will_be_unique()
    {
        $old = factory(Post::class)->create(['title' => 'test title']);

        $postInfo = new PostInfo([
            'title' => 'test title',
            'description' => 'test description',
            'intro' => 'test intro',
            'body' => 'test body',
        ]);

        $post = Post::new($postInfo);

        $this->assertSame('test-title-1', $post->slug);
        $this->assertSame('test title', $post->title);
    }

    /**
     *@test
     */
    public function updating_a_draft_post_will_update_the_slug()
    {
        $postInfo = new PostInfo([
            'title' => 'test title',
            'description' => 'test description',
            'intro' => 'test intro',
            'body' => 'test body',
        ]);

        $post = Post::new($postInfo);
        $this->assertSame('test-title', $post->slug);

        $post->save();
        $this->assertSame('test-title', $post->slug);

        $post->update(['title' => 'a new title']);
        $this->assertSame('a-new-title', $post->slug);

        $post->title = 'constant rebirth';
        $post->save();
        $this->assertSame('constant-rebirth', $post->slug);
    }

    /**
     *@test
     */
    public function updating_a_published_post_will_not_update_the_slug()
    {
        $post = factory(Post::class)->state('public')->create(['title' => 'test title']);
        $this->assertSame('test-title', $post->slug);

        $post->update(['title' => 'a late change']);
        $this->assertSame('test-title', $post->slug);
    }

    /**
     *@test
     */
    public function can_publish_a_draft_post()
    {
        $post = factory(Post::class)->state('draft')->create();

        $post->publish();

        $this->assertTrue($post->fresh()->is_public);
        $this->assertTrue($post->fresh()->first_published->isToday());
    }

    /**
     *@test
     */
    public function republishing_does_not_update_first_published()
    {
        $post = factory(Post::class)->state('draft')->create();
        $original_day = now();

        $post->publish();

        $this->travel(3)->days();

        $post->publish();

        $this->assertTrue($post->fresh()->is_public);
        $this->assertTrue($post->fresh()->first_published->isSameDay($original_day));
    }

    /**
     *@test
     */
    public function can_retract_a_post()
    {
        $post = factory(Post::class)->state('public')->create();

        $post->retract();

        $this->assertFalse($post->fresh()->is_public);
        $this->assertNotNull($post->fresh()->first_published);
    }
}
