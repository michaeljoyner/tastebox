<?php


namespace Tests\Feature\Blog;


use App\Blog\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use Tests\TestsMedia;

class UploadBlogTitleImageTest extends TestCase
{
    use RefreshDatabase, TestsMedia;

    /**
     *@test
     */
    public function upload_the_title_image_for_a_post()
    {
        $this->fakeMedia();
        $this->withoutExceptionHandling();

        $post = factory(Post::class)->create();

        $response = $this->asAdmin()->postJson("/admin/api/blog/{$post->id}/title-image", [
            'image' => UploadedFile::fake()->image('test.jpg'),
        ]);
        $response->assertSuccessful();

        $this->assertCount(1, $post->fresh()->getMedia(Post::TITLE_IMAGES));
        $image = $post->getFirstMedia(Post::TITLE_IMAGES);

        $this->assertStorageHasImage($image);
    }
}
