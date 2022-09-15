<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostTest extends TestCase
{
  use RefreshDatabase;
  public function testNoBlogPostsWhenDatabaseEmpty()
  {
    $response = $this->get('/posts');

    $response->assertStatus(200);
    $response->assertSeeText('No posts found!');
  }

  public function testSee1BlogPostWhenThereIs1WithNoComments()
  {
    // Arrange
    $post = $this->createDummyBlogPost();

    // Act
    $response = $this->get('/posts');

    // Assert
    $response->assertSeeText('New title');
    $response->assertSeeText('No comments yet!');

    $this->assertDatabaseHas('blog_posts', [
      'title' => 'New title'
    ]);
  }

  public function testSee1BlogPostWhenThereIs1WithComments()
  {

    // Arrange
    $post = $this->createDummyBlogPost();
    Comment::factory()->count(4)->create([
      'blog_post_id' => $post->id
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
    $post = $this->createDummyBlogPost();

    $this->assertDatabaseHas('blog_posts', [
      'title' => 'New title',
      'content' => 'Content of the blog post'
    ]);

    $params = [
      'title' => 'Updated title',
      'content' => 'Updated content of the blog post'
    ];

    // Act
    $this->actingAs($this->user())
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
    $post = $this->createDummyBlogPost();

    $this->assertDatabaseHas('blog_posts', [
      'title' => 'New title',
      'content' => 'Content of the blog post'
    ]);

    $this->actingAs($this->user())
      ->delete("/posts/{$post->id}")
      ->assertStatus(302)
      ->assertSessionHas('status');

    $this->assertEquals(session('status'), 'The blog post was deleted!');
    $this->assertDatabaseMissing('blog_posts', [
      'title' => 'New title',
      'content' => 'Content of the blog post'
    ]);
  }

  private function createDummyBlogPost(): BlogPost
  {
    // Arrange
    $post = BlogPost::factory()->newTitle()->create();

    return $post;
  }
}
