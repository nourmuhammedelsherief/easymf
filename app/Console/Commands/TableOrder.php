<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;

class TableOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tableOrder:time';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'check if the order on the table are expired';

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
        \App\Models\TableOrder::whereStatus('in_reservation')
            ->where('created_at' , '<=' , Carbon::now()->subHours(2))
            ->delete();
    }
}
