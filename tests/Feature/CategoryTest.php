<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Database\Seeders\CategorySeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class CategoryTest extends TestCase
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
    public function a_user_admin_can_view_all_the_categories()
    {
        $this->seed(RoleSeeder::class);
        $this->seed(UserSeeder::class);
        $this->seed(CategorySeeder::class);

        $user = (new User())->whereHas('role', function($query) {
            $query->where('name', 'Admin');
        })->first();

        $response = $this->actingAs($user, 'api')->withHeaders([
            "Content-Type" => "application/json",
            "Accept" => "application/json"
        ])->get(route('api.categories'));

        $response->assertStatus(200);
        $response->assertJsonStructure(['data']);
    }

    /**
     * @test
     */
    public function a_user_admin_can_view_all_the_categories_by_list_search()
    {
        $this->seed(RoleSeeder::class);
        $this->seed(UserSeeder::class);
        $this->seed(CategorySeeder::class);

        $user = (new User())->whereHas('role', function($query) {
            $query->where('name', 'Admin');
        })->first();

        $response = $this->actingAs($user, 'api')->post(route('api.categories_list_search'),
            ['search' => 'anang']);

        $response->assertStatus(200);
        $response->assertJsonStructure(['data']);
    }

    /**
     * @test
     */
    public function a_user_admin_can_view_all_the_categories_by_page_search()
    {
        $this->seed(RoleSeeder::class);
        $this->seed(UserSeeder::class);
        $this->seed(CategorySeeder::class);

        $user = (new User())->whereHas('role', function($query) {
            $query->where('name', 'Admin');
        })->first();

        $response = $this->actingAs($user, 'api')->post(route('api.categories_page_search', ['page' => 2]),
            ['search' => 'anang']);

        $response->assertStatus(200);
        $response->assertJsonStructure(['data']);
    }

    /**
     * @test
     */
    public function a_user_admin_can_create_a_category()
    {
        $this->seed(RoleSeeder::class);
        $this->seed(UserSeeder::class);
        $this->seed(CategorySeeder::class);

        $user = (new User())->whereHas('role', function($query) {
            $query->where('name', 'Admin');
        })->first();

        $response = $this->actingAs($user, 'api')->post(route('api.category_store'), [
            'name' => $this->faker->name()
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['data']);
    }

    /**
     * @test
     */
    public function a_user_admin_can_view_detail_a_category()
    {
        $this->seed(RoleSeeder::class);
        $this->seed(UserSeeder::class);
        $this->seed(CategorySeeder::class);

        $user = (new User())->whereHas('role', function($query) {
            $query->where('name', 'Admin');
        })->first();

        $category = Category::all()->first();

        $response = $this->actingAs($user, 'api')->get(route('api.category', ["id" => $category->id]));

        $response->assertStatus(200);
        $response->assertJsonStructure(['data']);
    }

    /**
     * @test
     */
    public function a_user_admin_can_update_a_category()
    {
        $this->seed(RoleSeeder::class);
        $this->seed(UserSeeder::class);
        $this->seed(CategorySeeder::class);

        $user = (new User())->whereHas('role', function($query) {
            $query->where('name', 'Admin');
        })->first();

        $category = Category::all()->first();

        $response = $this->actingAs($user, 'api')->put(route('api.category_update', ['id' => $category->id]),
            ["name" => $this->faker->name()]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['data']);
    }

    /**
     * @test
     */
    public function a_user_admin_can_delete_a_category()
    {
        $this->seed(RoleSeeder::class);
        $this->seed(UserSeeder::class);
        $this->seed(CategorySeeder::class);

        $user = (new User())->whereHas('role', function($query) {
            $query->where('name', 'Admin');
        })->first();

        $category = Category::all()->first();

        $response = $this->actingAs($user, 'api')->delete(route('api.category_delete', ["id" => $category->id]));

        $response->assertStatus(200);
    }
}
