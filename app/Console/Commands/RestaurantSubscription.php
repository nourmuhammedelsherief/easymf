<?php

namespace App\Console\Commands;

use App\Models\Branch;
use App\Models\Restaurant;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Console\Command;

class RestaurantSubscription extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'restaurants:subscription';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check The Subscription Date And Status';

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
        \Log::info('handle the finished restaurants');
        $date = today()->format('Y-m-d');
        \Log::info('today is' . $date);
        $tentative_subscriptions = Subscription::where('end_at' , '<' , $date)
            ->whereStatus('tentative')
            ->get();
        if ($tentative_subscriptions->count() > 0)
        {
            foreach ($tentative_subscriptions as $subscription) {
                $subscription->update([
                    'status'  => 'tentative_finished',
                ]);
                $subscription->branch->update([
                    'status'  => 'tentative_finished',
                ]);
            }
        }
        $active_subscriptions = Subscription::where('end_at' , '<' , $date)
            ->whereStatus('active')
            ->get();
        if ($active_subscriptions->count() > 0)
        {
            foreach ($active_subscriptions as $subscription) {
                $subscription->update([
                    'status'  => 'finished',
                ]);
                if ($subscription->type == 'restaurant')
                {
                    $subscription->restaurant->update([
                        'status'  => 'finished',
                    ]);
                }
                $subscription->branch->update([
                    'status'  => 'finished',
                ]);
            }
        }


//        $branches = Branch::whereStatus('finished')->get();
//        if ($branches->count() > 0)
//        {
//            foreach ($branches as $branch)
//            {
//                if ($branch->main == 'true')
//                {
//                    $branch->restaurant->update([
//                        'status' => 'finished'
//                    ]);
//                }
//            }
//        }
    }
}
