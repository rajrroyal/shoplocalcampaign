<?php

namespace App\Console\Commands\Utilities;

use Illuminate\Console\Command;

class Supervisord extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'supervisord';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '[CUSTOM] reload supervisord.conf and restart affected programs';

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
     * @return mixed
     */
    public function handle()
    {
        exec('sudo supervisord -c '.base_path('supervisord.conf'));
        exec('sudo supervisorctl reread');
        exec('sudo supervisorctl update');

        $this->info("Supervisord restarted\n\n");
    }
}

// console.stub
