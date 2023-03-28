<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Category::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $a = $this->faker->name;
        return [
        'name'=> $a,
        'slug' => Str::slug($a, '-'),
        'parent_id' => $this->faker->numberBetween(0,10),

        ];
    }
}
