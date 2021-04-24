<?php


namespace Tests\Feature\Blog;


use App\Blog\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RetractPostTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function retract_a_post()
    {
        $this->withoutExceptionHandling();

        $post = factory(Post::class)->state('public')->create();

        $response = $this->asAdmin()->deleteJson("/admin/api/published-posts/{$post->id}");
        $response->assertSuccessful();

        $this->assertFalse($post->fresh()->is_public);
    }
}
