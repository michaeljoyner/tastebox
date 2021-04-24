<?php


namespace Tests\Feature\Blog;


use App\Blog\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeletePostTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function delete_an_existing_post()
    {
        $this->withoutExceptionHandling();

        $post = factory(Post::class)->create();

        $response = $this->asAdmin()->deleteJson("/admin/api/blog/{$post->id}");
        $response->assertSuccessful();

        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }
}
