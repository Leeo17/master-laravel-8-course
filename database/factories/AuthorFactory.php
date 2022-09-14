<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Profile;

class AuthorFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array
   */
  public function definition()
  {
    return [
      //
    ];
  }

  public function newProfile()
  {
    return $this->afterCreating(function ($author) {
      $author->profile()->save(Profile::factory()->make());
    });
  }
}
