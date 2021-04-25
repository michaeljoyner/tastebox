<?php


namespace Tests\Unit\Blog;


use App\Blog\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use Tests\TestsMedia;

class PostImagesTest extends TestCase
{
    use RefreshDatabase, TestsMedia;

    /**
     *@test
     */
    public function can_attach_body_image_to_post()
    {
        $this->fakeMedia();

        $post = factory(Post::class)->create();
        $upload = UploadedFile::fake()->image('test.png');

        $image = $post->attachImage($upload);

        $this->assertStorageHasImage($image);
        $this->assertSame($upload->hashName(), $image->file_name);
        $this->assertTrue($image->fresh()->hasGeneratedConversion('web'));
    }

    /**
     *@test
     */
    public function can_set_the_title_image_of_a_post()
    {
        $this->fakeMedia();

        $post = factory(Post::class)->create();
        $upload = UploadedFile::fake()->image('test.png');

        $image = $post->setTitleImage($upload);

        $this->assertStorageHasImage($image);
        $this->assertSame($upload->hashName(), $image->file_name);
        $this->assertTrue($image->fresh()->hasGeneratedConversion('web'));
        $this->assertTrue($image->fresh()->hasGeneratedConversion('sharing'));
    }

    /**
     *@test
     */
    public function can_clear_the_title_image()
    {
        $this->fakeMedia();

        $post = factory(Post::class)->create();
        $upload = UploadedFile::fake()->image('test.png');

        $image = $post->setTitleImage($upload);
        $this->assertStorageHasImage($image);

        $post->clearTitleImage();

        $this->assertCount(0, $post->getMedia(Post::TITLE_IMAGES));
        $this->assertStorageDoesNotHaveImage($image);
    }

    /**
     *@test
     */
    public function setting_the_title_image_clears_any_existing_title_images()
    {
        $this->fakeMedia();

        $post = factory(Post::class)->create();

        $old = $post->setTitleImage(UploadedFile::fake()->image('test.png'));

        $this->assertCount(1, $post->getMedia(Post::TITLE_IMAGES));
        $this->assertStorageHasImage($old);

        $new = $post->setTitleImage(UploadedFile::fake()->image('test2.png'));

        $this->assertCount(1, $post->fresh()->getMedia(Post::TITLE_IMAGES));
        $this->assertStorageHasImage($new);
        $this->assertStorageDoesNotHaveImage($old);
    }
}
