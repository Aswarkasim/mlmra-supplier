<?php

namespace Database\Seeders;

use App\Models\BankAccount;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Reseller;
use App\Models\Supplier;
use App\Models\Testimoni;
use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserSeeder::class,
            HomepageSeeder::class,
            SettingSeeder::class
//            User::factory()->count(100)->create(),
//            Reseller::factory(300)->create(),
//            Customer::factory(100)->create(),
//            Category::factory(50)->create(),
//            BankAccount::factory(42)->create(),
//            Testimoni::factory(21)->create(),
//            Product::factory(240)->create()
        ]);
    }
}
