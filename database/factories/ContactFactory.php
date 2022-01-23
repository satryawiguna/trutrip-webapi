<?php

namespace Database\Factories;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContactFactory extends Factory
{
    protected $model = Contact::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $contactable = $this->contactable();

        return [
            'contactable_id' => $contactable::factory(),
            'contactable_type' => $contactable,
            'nick_name' => $this->faker->firstName(),
            'full_name' => $this->faker->firstName() . ' ' . $this->faker->lastName(),
            'post_code' => $this->faker->postcode(),
            'country' => $this->faker->country(),
            'state' => $this->faker->name(),
            'city' => $this->faker->city(),
            'address' => $this->faker->streetAddress(),
            'mobile' => $this->faker->phoneNumber()
        ];
    }

    public function contactable()
    {
        return $this->faker->randomElement([
            User::class
        ]);
    }
}
