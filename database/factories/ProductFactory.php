<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\Category;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $cat_ids = Category::pluck('id')->toArray();
        return [
            'name' => $this->faker->name(),
            'category_id'=>$this->faker->randomElement($cat_ids),
            'description'=>$this->faker->text(),
            'price'=>$this->faker->numberBetween(500,1200),
            'qty'=>$this->faker->numberBetween(1,20),
        ];
    }
}
