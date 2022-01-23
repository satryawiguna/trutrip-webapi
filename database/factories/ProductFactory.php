<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $categories = Category::all();
        $user = User::all();

        $schedule_start = Carbon::now()->addDay(1)->format('Y-m-d H:i:s');

        return [
            'category_id' => $categories->random(1)->first()->id,
            'user_id' => $user->random(1)->first()->id,
            'name' => $this->faker->name(),
            'destination' => $this->faker->name(),
            'schedule_start' => $schedule_start,
            'schedule_end' => Carbon::createFromFormat('Y-m-d H:i:s', $schedule_start)->addDays(rand(1, 5))->format('Y-m-d H:i:s'),
            'description' => $this->faker->text()
        ];
    }
}
