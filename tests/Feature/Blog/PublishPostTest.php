<?php


namespace Tests\Feature\Blog;


use App\Blog\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublishPostTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function publish_a_post()
    {
        $this->withoutExceptionHandling();

        $post = factory(Post::class)->state('draft')->create();

        $response = $this->asAdmin()->postJson("/admin/api/published-posts", [
            'post_id' => $post->id,
        ]);
        $response->assertSuccessful();

        $this->assertTrue($post->fresh()->is_public);
        $this->assertTrue($post->fresh()->first_published->isToday());

    }
}
