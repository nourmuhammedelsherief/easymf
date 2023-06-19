<?php

namespace App\Console\Commands;

use App\Models\Reservation\ReservationTable;
use App\Models\Reservation\ReservationTablePeriod;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ReservationTableTime extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reservation:table';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check the reservation tables time';

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
        $tables = ReservationTable::with('dates' , 'periods')
            
            ->where('is_available' , 1)
            ->get();
            foreach($tables as $table):
                if($table->isExpire()):
                    $table->update([
                        'is_available' => 0  ,
                    ]);
                endif;
            endforeach;
    //     if ($tables->count() > 0)
    //     {
    //         $now = Carbon::now();
    //         foreach ($tables  as $table)
    //         {
    //             // check table periods
    //             $pastDatesCount = 0;
    //             $newDate = Carbon::createFromFormat('Y-m-d' , (date('Y-m-d')));
    //             // return date('Y-m-d');
    //             foreach($table->dates as $temp):
    //                 $cDate = Carbon::createFromFormat('Y-m-d' , ($temp->date));
                    
    //                 if($newDate->greaterThan($cDate)) $pastDatesCount++;
    //                 elseif($newDate->equalTo($cDate)){
                        
    //                     if ($table->periods->count() > 0)
    //                     {
    //                         $pastPeriodsCount = 0;
    //                         foreach ($table->periods as $period) {
    //                             $cNow = Carbon::createFromFormat('H:i:s' , (date('H:i:s')));
    //                             $cFrom = Carbon::createFromFormat('H:i:s' , ($period->from));
                                
    //                             if ($cNow->greaterThanOrEqualTo($cFrom)) $pastPeriodsCount++;
                                
    //                         }
    //                         if($pastPeriodsCount == $table->periods->count()) $pastDatesCount++;
    //                     }
    //                 }
    //             endforeach;
                
    //             if($table->dates->count() == $pastDatesCount):
    //                 $table->update([
    //                     'status' => 'not_available'
    //                 ]);
    //             endif;
                
    //         }
    //     }
    }
}
