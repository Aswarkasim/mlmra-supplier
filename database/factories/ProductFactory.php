<?php

namespace Database\Factories;

use App\Enums\StatusType;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $no = 1;
        return [
            'title' => 'Product '. $no++,
            'stock' => $this->faker->numberBetween(1,190),
            'reseller_price' =>$this->faker->numberBetween(15000, 75000),
            'customer_price' => $this->faker->numberBetween(15000, 75000),
            'discount' => $this->faker->numberBetween(2000, 14000),
            'isCommisionRupiah' => 1,
            'commision_rp' => $this->faker->numberBetween( 2500, 4500),
            'point' => $this->faker->numberBetween(1, 4),
            'description' => $this->faker->sentence,
            'status' => StatusType::ACTIVE,
            'slug' => $this->faker->slug('7'),
            'user_id' => $this->faker->numberBetween(2,11),
            'category_id' => $this->faker->numberBetween(2,10)
        ];
    }
}
