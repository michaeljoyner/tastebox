<?php


namespace Tests\Feature\Blog;


use App\Blog\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use Tests\TestsMedia;

class UploadPostBodyImageTest extends TestCase
{
    use RefreshDatabase, TestsMedia;

    /**
     *@test
     */
    public function upload_an_image_for_a_blog_post()
    {
        $this->withoutExceptionHandling();
        $this->fakeMedia();

        $post = factory(Post::class)->create();

        $response = $this->asAdmin()->postJson("/admin/api/blog/{$post->id}/images", [
            'image' => UploadedFile::fake()->image('test.png'),
        ]);
        $response->assertSuccessful();

        $this->assertCount(1, $post->fresh()->getMedia(Post::BODY_IMAGES));
        $image = $post->getFirstMedia(Post::BODY_IMAGES);

        $this->assertStorageHasImage($image);

        $this->assertSame($image->getUrl('web'), $response->json('src'));
    }

    /**
     *@test
     */
    public function the_image_must_be_a_valid_image_file()
    {
        $this->fakeMedia();

        $post = factory(Post::class)->create();

        $response = $this->asAdmin()->postJson("/admin/api/blog/{$post->id}/images", [
            'image' => null,
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors('image');

        $response = $this->asAdmin()->postJson("/admin/api/blog/{$post->id}/images", [
            'image' => 'not-a-file',
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors('image');

        $response = $this->asAdmin()->postJson("/admin/api/blog/{$post->id}/images", [
            'image' => UploadedFile::fake()->create('not-an-image.txt'),
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors('image');
    }
}
