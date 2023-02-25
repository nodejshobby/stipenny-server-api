<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class UserDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $phoneNumberPrepender = ['091','090','081','070','080'];
        return [
            'phone_number' => $this->faker->randomElement($phoneNumberPrepender).$this->faker->randomNumber(8, true)
        ];
    }
}
