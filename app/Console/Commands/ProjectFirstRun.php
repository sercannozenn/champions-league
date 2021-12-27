<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class ProjectFirstRun extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'project:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run the project for the first time.';

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
        Artisan::call('migrate');
        Artisan::call('db:seed --class=ClubSeeder');

        $this->info('The command was successful!');
        return 1;
    }
}
