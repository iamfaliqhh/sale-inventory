<?php
namespace Modules\People\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SalesPersonFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\People\Entities\SalesPerson::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'sales_person_name' => $this->faker->name(),
            'sales_person_email' => $this->faker->safeEmail(),
            'sales_person_phone' => $this->faker->phoneNumber(),
            'city' => $this->faker->city(),
            'country' => $this->faker->country(),
            'address' => $this->faker->streetAddress()
        ];
    }
}

