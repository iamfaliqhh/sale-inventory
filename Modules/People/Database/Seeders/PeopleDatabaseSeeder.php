<?php

namespace Modules\People\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\People\Entities\Customer;
use Modules\People\Entities\Supplier;
use Modules\People\Entities\SalesPerson;

class PeopleDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Customer::factory()->count(15)->create();
        Supplier::factory()->count(6)->create();
        SalesPerson::factory()->count(6)->create();
    }
}
