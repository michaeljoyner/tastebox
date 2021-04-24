<?php

namespace Tests\Feature\Blog;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class CreatePostTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function create_a_new_blog_post()
    {
        $this->withoutExceptionHandling();

        $response = $this->asAdmin()->postJson("/admin/api/blog", [
            'title' => 'test title',
            'description' => 'test description',
            'intro' => 'test intro',
            'body' => 'test body',
        ]);
        $response->assertSuccessful();

        $this->assertDatabaseHas('posts', [
            'title' => 'test title',
            'description' => 'test description',
            'intro' => 'test intro',
            'body' => 'test body',
        ]);
    }

    /**
     *@test
     */
    public function the_title_is_required()
    {
        $response = $this->asAdmin()->postJson("/admin/api/blog", [
            'title' => '',
            'description' => 'test description',
            'intro' => 'test intro',
            'body' => 'test body',
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors('title');
    }
}
