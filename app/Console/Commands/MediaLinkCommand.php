<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MediaLinkCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'media:link';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a symbolic link from \r
        "public/photo/profile" to "storage/app/profile"\r
        "public/photo/product" to "storage/app/product"';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (!file_exists(public_path('photo/profile'))) {
            $this->laravel->make('files')->link(
                storage_path('app/profile'), public_path('photo/profile')
            );

            $this->info('The [public/photo/profile/] directory has been linked.');
        } else {
            $this->error('The [public/photo/profile/] directory already linked.');
        }

        if (!file_exists(public_path('photo/product'))) {
            $this->laravel->make('files')->link(
                storage_path('app/product'), public_path('photo/product')
            );

            $this->info('The [public/photo/product/] directory has been linked.');
        } else {
            $this->error('The [public/photo/product/] directory already linked.');
        }
    }
}
