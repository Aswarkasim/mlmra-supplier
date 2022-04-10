<?php

namespace Database\Factories;

use App\Enums\RoleType;
use App\Enums\StatusUser;
use App\Models\BankAccount;
use App\Models\BankAcoount;
use App\Models\Model;
use Illuminate\Database\Eloquent\Factories\Factory;

class BankAccountFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BankAccount::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_type' => RoleType::RESELLER,
            'account_name' => $this->faker->name,
            'account_number' => $this->faker->numberBetween(424112342124242424, 4241123421242424241),
            'bank_name' => 'BNI',
            'reseller_id' => $this->faker->numberBetween(1,40)
        ];
    }
}
