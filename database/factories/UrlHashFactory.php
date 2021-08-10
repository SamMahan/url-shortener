<?php

namespace Database\Factories;

use App\Models\UrlHash;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UrlHashFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UrlHash::class;

    /**
     * Define the model's default state.
     * used primarily for unit testing
     * @return array
     */
    public function definition()
    {
        return [
            'hash_key' => $this->faker->text(),
            'url' => $this->faker->unique()->url(),
            'times_accessed' => $this->faker->numberBetween(0, 10000),
        ];
    }

}
