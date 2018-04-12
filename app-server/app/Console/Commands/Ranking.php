<?php

namespace App\Console\Commands;

use App\Services\Moker\MokerCommService;
use Illuminate\Console\Command;

class Ranking extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:ranking';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '富豪榜';

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
        $service = new MokerCommService();
        $service->ranking_list('all',0,1);
        $service->ranking_list('month',0,1);
        $service->ranking_list('week',0,1);
    }
}
