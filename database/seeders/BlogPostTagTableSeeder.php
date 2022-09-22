<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class BlogPostTagTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $tagCount = Tag::all()->count();
    if ($tagCount === 0) {
      $this->command->info('No tags found, skipping assigning tags to blog posts');
      return;
    }

    $minTags = (int)$this->command->ask('Minimum tags on blog post?', 0);
    $maxTags = min((int)$this->command->ask('Maximum tags on blog post?', $tagCount), $tagCount);

    BlogPost::all()->each(function (BlogPost $post) use ($minTags, $maxTags) {
      $take = random_int($minTags, $maxTags);
      $tags = Tag::inRandomOrder()->take($take)->get()->pluck('id');
      $post->tags()->sync($tags);
    });
  }
}
