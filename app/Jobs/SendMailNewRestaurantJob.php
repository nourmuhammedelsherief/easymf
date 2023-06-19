<?php

namespace App\Jobs;

use App\Models\Admin;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendMailNewRestaurantJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $restaurant;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct( $restaurant)
    {
        $this->restaurant = $restaurant;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $details = [
            'title' => 'عميل غير مكتمل التسجيل',
            'name' => $this->restaurant->name_ar,
            'phone' => $this->restaurant->phone_number,
            'email' => $this->restaurant->email,
            'package' => $this->restaurant->package->name_ar,
            'country' => $this->restaurant->country->name_ar
        ];
        $admins = Admin::all();
        foreach ($admins as $admin)
        {
            if ($admin->email != null)
            {
                \Mail::to($admin->email)->send(new \App\Mail\MyTestMail($details));
            }
        }
        \Mail::to('nourmuhammed20121994@gmail.com')->send(new \App\Mail\MyTestMail($details));
    }
}
