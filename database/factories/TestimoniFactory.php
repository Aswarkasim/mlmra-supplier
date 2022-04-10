<?php

namespace Database\Factories;

use App\Enums\StatusType;
use App\Models\Testimoni;
use Illuminate\Database\Eloquent\Factories\Factory;

class TestimoniFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Testimoni::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'description' => $this->faker->sentence,
            'status' => StatusType::ACTIVE,
            'customer_id' => $this->faker->numberBetween(1,43)
        ];
    }
}
