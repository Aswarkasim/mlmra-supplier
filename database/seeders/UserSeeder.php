<?php

namespace Database\Seeders;

use App\Enums\StatusType;
use App\Models\Customer;
use App\Models\Reseller;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'username'      => 'cordova',
            'full_name'     => 'Cordova Holding',
            'email'         => 'cordovaholding@gmail.com',
            'phone_number'  => '085341770638',
            'isAdmin'       => 1,
            'status'        => StatusType::ACTIVE,
            'password'      => bcrypt('admin')
        ]);

        User::create([
            'username'      => 'umar_s28',
            'full_name'     => 'Umar Sabirin',
            'email'         => 'umarsabirin369@gmail.com',
            'phone_number'  => '085341770639',
            'isAdmin'       => 0,
            'status'        => StatusType::ACTIVE,
            'password'      => bcrypt('supplier')
        ]);

        Reseller::create([
            'username'      => 'reseller1',
            'full_name'     => 'Rifky Mubaraqqq',
            'email'         => 'rifky@gmail.com',
            'phone_number'  => '085341770631',
            'status'        => StatusType::ACTIVE,
            'password'      => bcrypt('reseller1')
        ]);

        Reseller::create([
            'username'      => 'reseller2',
            'full_name'     => 'Mursidin',
            'email'         => 'mursidin@gmail.com',
            'phone_number'  => '085341770632',
            'status'        => StatusType::ACTIVE,
            'password'      => bcrypt('reseller2')
        ]);

        Customer::create([
            'username'      => 'customer1',
            'full_name'     => 'Fadly',
            'email'         => 'fadly@gmail.com',
            'phone_number'  => '0853417706352',
            'status'        => StatusType::ACTIVE,
            'password'      => bcrypt('customer1')
        ]);

        Customer::create([
            'username'      => 'customer2',
            'full_name'     => 'Didin',
            'email'         => 'didin@gmail.com',
            'phone_number'  => '085341770635452',
            'status'        => StatusType::ACTIVE,
            'password'      => bcrypt('customer2')
        ]);

    }
}
