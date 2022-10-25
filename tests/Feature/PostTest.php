<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    public function testNoBlogPostsWhenDatabaseEmpty()
    {
        $response = $this->get('/posts');

        $response->assertStatus(200);
        $response->assertSeeText('No blog posts yet!');
    }

    public function testSee1BlogPostWhenThereIs1WithNoComments()
    {
        // Arrange
        $post = $this->createDummyBlogPost();

        // Act
        $response = $this->get('/posts');

        // Assert
        $response->assertSeeText('New title');
        $response->assertSeeText('No comments yet');

        $this->assertDatabaseHas('blog_posts', [
          'title' => 'New title'
        ]);
    }

    public function testSee1BlogPostWhenThereIs1WithComments()
    {
        $user = $this->user();

        // Arrange
        $post = $this->createDummyBlogPost();
        Comment::factory()->count(4)->create([
          'commentable_id' => $post->id,
          'commentable_type' => BlogPost::class,
          'user_id' => $user->id
        ]);

        // Act
        $response = $this->get('/posts');

        // Assert
        $response->assertSeeText('4 comments');
    }

    public function testStoreValid()
    {
        // Arrange
        $params = [
          'title' => 'Valid title',
          'content' => 'At least 10 characters'
        ];

        // Act
        $this->actingAs($this->user())
          ->post('/posts', $params)
          ->assertStatus(302)
          ->assertSessionHas('status');

        // Assert
        $this->assertEquals(session('status'), 'The blog post was created!');
    }

    public function testStoreFailLessThanMinLenght()
    {
        $params = [
          'title' => 'x',
          'content' => 'x'
        ];

        $this->actingAs($this->user())
          ->post('/posts', $params)
          ->assertStatus(302)
          ->assertSessionHas('errors');

        $messages = session('errors')->getMessages();

        $this->assertEquals(
            $messages['title'][0],
            'The title must be at least 5 characters.'
        );

        $this->assertEquals(
            $messages['content'][0],
            'The content must be at least 10 characters.'
        );
    }

    public function testStoreFailRequiredFieldsNotFilled()
    {
        $params = [
          'title' => '',
          'content' => ''
        ];

        $this->actingAs($this->user())
          ->post('/posts', $params)
          ->assertStatus(302)
          ->assertSessionHas('errors');

        $messages = session('errors')->getMessages();

        $this->assertEquals(
            $messages['title'][0],
            'The title field is required.'
        );

        $this->assertEquals(
            $messages['content'][0],
            'The content field is required.'
        );
    }

    public function testStoreFailTitleLongerThanMaxLength()
    {
        $params = [
          'title' => 'This is a test title, which is supposed to have more than 100 characters. Because that is the limit of characters.',
          'content' => 'At least 10 characters'
        ];

        $this->actingAs($this->user())
          ->post('/posts', $params)
          ->assertStatus(302)
          ->assertSessionHas('errors');

        $messages = session('errors')->getMessages();

        $this->assertEquals(
            $messages['title'][0],
            'The title may not be greater than 100 characters.'
        );
    }

    public function testUpdateValid()
    {
        // Arrange
        $user = $this->user();
        $post = $this->createDummyBlogPost($user->id);

        $this->assertDatabaseHas('blog_posts', [
          'title' => 'New title',
          'content' => 'Content of the blog post'
        ]);

        $params = [
          'title' => 'Updated title',
          'content' => 'Updated content of the blog post'
        ];

        // Act
        $this->actingAs($user)
          ->put("/posts/{$post->id}", $params)
          ->assertStatus(302)
          ->assertSessionHas('status');

        // Assert
        $this->assertEquals(session('status'), 'The blog post was updated!');

        $this->assertDatabaseMissing('blog_posts', [
          'title' => 'New title',
          'content' => 'Content of the blog post'
        ]);

        $this->assertDatabaseHas('blog_posts', [
          'title' => 'Updated title',
          'content' => 'Updated content of the blog post'
        ]);
    }

    public function testDelete()
    {
        $user = $this->user();
        $post = $this->createDummyBlogPost($user->id);

        $this->assertDatabaseHas('blog_posts', [
          'title' => 'New title',
          'content' => 'Content of the blog post'
        ]);

        $this->actingAs($user)
          ->delete("/posts/{$post->id}")
          ->assertStatus(302)
          ->assertSessionHas('status');

        $this->assertEquals(session('status'), 'The blog post was deleted!');
        $this->assertSoftDeleted('blog_posts', [
          'title' => 'New title',
          'content' => 'Content of the blog post'
        ]);
    }

    public function testDeleteNotAuthorized()
    {
        $users = User::factory()->count(2)->create();
        $post = $this->createDummyBlogPost($users[0]->id);

        $this->assertDatabaseHas('blog_posts', [
          'title' => 'New title',
          'content' => 'Content of the blog post'
        ]);

        $this->actingAs($users[1])->delete("/posts/{$post->id}")->assertStatus(403);

        $this->assertDatabaseHas('blog_posts', [
          'title' => 'New title',
          'content' => 'Content of the blog post'
        ]);
    }

    public function testUpdateNotAuthorized()
    {
        // Arrange
        $users = User::factory()->count(2)->create();
        $post = $this->createDummyBlogPost($users[0]->id);

        $this->assertDatabaseHas('blog_posts', [
          'title' => 'New title',
          'content' => 'Content of the blog post'
        ]);

        $params = [
          'title' => 'Updated title',
          'content' => 'Updated content of the blog post'
        ];

        // Act
        $this->actingAs($users[1])
          ->put("/posts/{$post->id}", $params)
          ->assertStatus(403);

        // Assert
        $this->assertDatabaseHas('blog_posts', [
          'title' => 'New title',
          'content' => 'Content of the blog post'
        ]);

        $this->assertDatabaseMissing('blog_posts', [
          'title' => 'Updated title',
          'content' => 'Updated content of the blog post'
        ]);
    }

    private function createDummyBlogPost($userId = null): BlogPost
    {
        $post = BlogPost::factory()->newTitle()->create([
          'user_id' => $userId ?? $this->user()->id,
        ]);

        return $post;
    }
}
