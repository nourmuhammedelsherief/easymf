<?php

namespace App\Console\Commands;

use App\Models\Branch;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Models\ServiceSubscription;

class ServiceSubscriptionPeriod extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'service:period';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'check the service subscription period ';

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
        $services = ServiceSubscription::whereIn('status' , ['active' , 'tentative'])
            ->where('end_at' , '<' , Carbon::now())
            ->get();
        if ($services->count() > 0)
        {
            foreach ($services as $service)
            {
                $service->update([
                    'status'  => $service->status == 'tentative' ? 'tentative_finished':'finished',
                    'paid_at' => null,
                ]);
                if($service->service_id == 4)
                {
                    $service->restaurant->update([
                        'foodics_status'  => 'false',
                        'foodics_access_token' => null
                    ]);
                    $branch = Branch::whereRestaurantId($service->restaurant->id)
                        ->where('foodics_status' , 'true')
                        ->first();
                    if ($branch != null)
                    {
                        $branch->update([
                            'foodics_status' => 'false',
                        ]);
                    }
                }elseif ($service->service_id == 1)
                {
                    $service->restaurant->update([
                        'reservation_service'  => 'false',
                    ]);
                }
            }
        }
    }
}
