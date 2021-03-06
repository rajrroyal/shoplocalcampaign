<?php

namespace App\Console\Commands\Utilities;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class FlushRedis extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'flush-redis';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '[CUSTOM] Flush Redis DB of queued jobs, etc';

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
        $this->info("\nFlushing Redis database");
        Redis::command('flushdb');
        $this->info("Complete\n");
    }
}
