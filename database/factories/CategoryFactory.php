<?php

namespace Database\Factories;

use App\Enums\CategoryType;
use App\Enums\StatusType;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

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
        return [
            'name' => $this->faker->sentence,
            'category_type' => CategoryType::PRODUCT,
            'status' => StatusType::ACTIVE
        ];
    }
}
