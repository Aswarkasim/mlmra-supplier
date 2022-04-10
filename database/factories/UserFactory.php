<?php

namespace Database\Factories;

use App\Enums\StatusType;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

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
            'whatsapp' => $this->faker->phoneNumber,
            'password' => bcrypt('supplier'),
            'isAdmin' => false,
            'status' => StatusType::ACTIVE
        ];
    }
}
