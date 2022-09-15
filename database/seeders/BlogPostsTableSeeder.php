<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Database\Seeder;

class BlogPostsTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $postsCount = (int) $this->command->ask('How many blog posts would you like?', 50);

    $users = User::all();
    BlogPost::factory()->count($postsCount)->make()->each(function ($post) use ($users) {
      $post->user_id = $users->random()->id;
      $post->save();
    });
  }
}
