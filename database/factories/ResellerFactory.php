<?php

namespace Database\Factories;

use App\Enums\StatusType;
use App\Models\Reseller;
use Illuminate\Database\Eloquent\Factories\Factory;

class ResellerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Reseller::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'username' => $this->faker->userName,
            'full_name' => $this->faker->name,
            'phone_number' => $this->faker->phoneNumber,
            'email' => $this->faker->email,
            'password' => bcrypt('reseller'),
            'status' => StatusType::ACTIVE,
            'point' => $this->faker->numberBetween(47, 100),
            'commision_total' => $this->faker->numberBetween(76000, 430000)
        ];
    }
}
