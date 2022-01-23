<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Database\Seeders\CategorySeeder;
use Database\Seeders\ProductSeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use WithFaker;

    public function setUp():void {
        parent::setUp();
        Artisan::call('migrate:refresh');
        Artisan::call('passport:install',['--uuids' => true, '--no-interaction' => true]);
    }

    /**
     * @test
     */
    public function a_user_can_view_all_the_products()
    {
        $this->seed(RoleSeeder::class);
        $this->seed(UserSeeder::class);
        $this->seed(CategorySeeder::class);
        $this->seed(ProductSeeder::class);

        $user = (new User())->whereHas('role', function($query) {
            $query->where('name', 'Admin');
        })->first();

        $response = $this->actingAs($user, 'api')->withHeaders([
            "Content-Type" => "application/json",
            "Accept" => "application/json"
        ])->get(route('api.products'));

        $response->assertStatus(200);
        $response->assertJsonStructure(['data']);
    }

    /**
     * @test
     */
    public function a_user_can_view_all_the_products_by_list_search()
    {
        $this->seed(RoleSeeder::class);
        $this->seed(UserSeeder::class);
        $this->seed(CategorySeeder::class);
        $this->seed(ProductSeeder::class);

        $user = (new User())->whereHas('role', function($query) {
            $query->where('name', 'Admin');
        })->first();

        $response = $this->actingAs($user, 'api')->post(route('api.products_list_search'),
            ['schedule' => '2022-01-01', 'search' => 'anang']);

        $response->assertStatus(200);
        $response->assertJsonStructure(['data']);
    }

    /**
     * @test
     */
    public function a_user_can_view_all_the_products_by_page_search()
    {
        $this->seed(RoleSeeder::class);
        $this->seed(UserSeeder::class);
        $this->seed(CategorySeeder::class);
        $this->seed(ProductSeeder::class);

        $user = (new User())->whereHas('role', function($query) {
            $query->where('name', 'Admin');
        })->first();

        $response = $this->actingAs($user, 'api')->post(route('api.products_page_search'),
            ['schedule' => '2022-01-01', 'search' => 'anang']);

        $response->assertStatus(200);
        $response->assertJsonStructure(['data']);
    }

    /**
     * @test
     */
    public function a_user_can_create_a_product()
    {
        $this->seed(RoleSeeder::class);
        $this->seed(UserSeeder::class);
        $this->seed(CategorySeeder::class);
        $this->seed(ProductSeeder::class);

        $categories = (new Category())->all();

        $user = (new User())->whereHas('role', function($query) {
            $query->where('name', 'Admin');
        })->first();

        $schedule_start = Carbon::now()->addDay(1)->format('Y-m-d H:i:s');

        $response = $this->actingAs($user, 'api')->post(route('api.product_store'), [
            'category_id' => $categories->random(1)->first()->id,
            'user_id' => $user->id,
            'name' => $this->faker->name(),
            'destination' => $this->faker->name(),
            'schedule_start' => $schedule_start,
            'schedule_end' => Carbon::createFromFormat('Y-m-d H:i:s', $schedule_start)->addDays(rand(1, 5))->format('Y-m-d H:i:s'),
            'description' => $this->faker->text()
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['data']);
    }

    /**
     * @test
     */
    public function a_user_can_view_detail_a_product()
    {
        $this->seed(RoleSeeder::class);
        $this->seed(UserSeeder::class);
        $this->seed(CategorySeeder::class);
        $this->seed(ProductSeeder::class);

        $user = (new User())->whereHas('role', function($query) {
            $query->where('name', 'Admin');
        })->first();

        $product = Product::all()->first();

        $response = $this->actingAs($user, 'api')->get(route('api.product', ["id" => $product->id]));

        $response->assertStatus(200);
        $response->assertJsonStructure(['data']);
    }

    /**
     * @test
     */
    public function a_user_can_update_a_product()
    {
        $this->seed(RoleSeeder::class);
        $this->seed(UserSeeder::class);
        $this->seed(CategorySeeder::class);
        $this->seed(ProductSeeder::class);

        $user = (new User())->whereHas('role', function($query) {
            $query->where('name', 'Admin');
        })->first();

        $categories = Category::all();
        $product = Product::all()->first();

        $schedule_start = Carbon::now()->addDay(1)->format('Y-m-d H:i:s');

        $response = $this->actingAs($user, 'api')->put(route('api.product_update', ['id' => $product->id]),
            [
                'category_id' => $categories->random(1)->first()->id,
                'user_id' => $user->id,
                'name' => $this->faker->name(),
                'destination' => $this->faker->name(),
                'schedule_start' => $schedule_start,
                'schedule_end' => Carbon::createFromFormat('Y-m-d H:i:s', $schedule_start)->addDays(rand(1, 5))->format('Y-m-d H:i:s'),
                'description' => $this->faker->text()
            ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['data']);
    }

    /**
     * @test
     */
    public function a_user_can_delete_a_product()
    {
        $this->seed(RoleSeeder::class);
        $this->seed(UserSeeder::class);
        $this->seed(CategorySeeder::class);
        $this->seed(ProductSeeder::class);

        $user = (new User())->whereHas('role', function($query) {
            $query->where('name', 'Admin');
        })->first();

        $category = Category::all()->first();

        $response = $this->actingAs($user, 'api')->delete(route('api.category_delete', ["id" => $category->id]));

        $response->assertStatus(200);
    }
}
