<?php

namespace Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        ini_set('memory_limit','512M');

        if (env("DB_CONNECTION") === 'mysql')
            DB::statement('SET FOREIGN_KEY_CHECKS=0');

        DB::table('roles')->truncate();
        DB::table('users')->truncate();
        DB::table('contacts')->truncate();
        DB::table('categories')->truncate();
        DB::table('products')->truncate();

        if (env("DB_CONNECTION") === 'mysql')
            DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(ProductSeeder::class);
    }
}
