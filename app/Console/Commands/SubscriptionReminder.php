<?php

namespace App\Console\Commands;

use App\Models\Admin;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SubscriptionReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscription:reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'check restaurants subscriptions and send email to admins with this status';

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
        // $subscriptions = Subscription::where('end_at' , '>=' , now()->addDays(30))
        //   ->where('status' , 'active')
        //   ->where('type' , 'restaurant')
        //   ->get();
        // if ($subscriptions->count() > 0) {
        //     foreach ($subscriptions as $subscription) {
        //         $details = [
        //             'title' => 'عميل اقل من ٣٠ يوم',
        //             'name' => $subscription->restaurant->name_ar,
        //             'phone' => $subscription->restaurant->phone_number,
        //             'email' => $subscription->restaurant->email,
        //             'package' => $subscription->package->name_ar,
        //             'country' => $subscription->restaurant->country->name_ar
        //         ];
        //         $admins = Admin::all();
        //         foreach ($admins as $admin)
        //         {
        //             if ($admin->email != null)
        //             {
        //                 \Mail::to($admin->email)->send(new \App\Mail\MyTestMail($details));
        //             }
        //         }
        //     }
        // }
    }
}
