<?php


namespace Tests\Feature\Blog;


use App\Blog\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class UpdatePostTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function update_an_existing_post()
    {
        $this->withoutExceptionHandling();

        $post = factory(Post::class)->create();

        $response = $this->asAdmin()->postJson("/admin/api/blog/{$post->id}", [
            'title' => 'new title',
            'description' => 'new description',
            'intro' => 'new intro',
            'body' => 'new body',
        ]);
        $response->assertSuccessful();

        $this->assertDatabaseHas('posts', [
            'title' => 'new title',
            'description' => 'new description',
            'intro' => 'new intro',
            'body' => 'new body',
        ]);
    }

    /**
     *@test
     */
    public function the_title_is_required()
    {
        $post = factory(Post::class)->create();

        $response = $this->asAdmin()->postJson("/admin/api/blog/{$post->id}", [
            'title' => '',
            'description' => 'new description',
            'intro' => 'new intro',
            'body' => 'new body',
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors('title');
    }
}
