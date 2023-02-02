<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\Api\Emperica\EmpericaController;
use App\Http\Controllers\Api\Emperica\PayoutsController;

class mastercron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'master:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
       $master = new EmpericaController;
       $master->MasterCron();
       $payout = new PayoutsController;
       $payout->PayoutsCron();
        \Log::info("MasterCron is working fine!");
    }
}
