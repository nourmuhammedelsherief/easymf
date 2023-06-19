<?php

namespace App\Console\Commands;

use App\Models\Poster;
use App\Models\Restaurant;
use App\Models\RestaurantPoster;
use App\Models\RestaurantSensitivity;
use App\Models\Sensitivity;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CopyPosterAndSensitivityToOldRestaurantsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'restaurant:copy_poster_and_sensitivity';

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
        // step1 : copy in database and image is spcial place
        // Poster::query()->delete();
        // Storage::disk('public_storage')->deleteDirectory('uploads/static_posters');
        // $poster = RestaurantPoster::where('restaurant_id' , 276)->get();
        // foreach($poster as $item):
        //     $image = copyImage($item->image_path , 'poster' , 'uploads/static_posters');
        //     Poster::create([
        //         'name_en' => $item->name_en , 
        //         'name_ar' => $item->name_ar, 
        //         'poster' => $image , 
        //     ]);
        // endforeach;
        // Sensitivity::query()->delete();
        // Storage::disk('public_storage')->deleteDirectory('uploads/static_sensitivities');
        // $sens = RestaurantSensitivity::where('restaurant_id' , 276)->get();
        // foreach($sens as $item):
        //     $image = copyImage($item->image_path , 'poster' , 'uploads/static_sensitivities');
        //     Sensitivity::create([
        //         'name_en' => $item->name_en , 
        //         'name_ar' => $item->name_ar, 
        //         'details_ar' => $item->details_ar , 
        //         'details_en' => $item->details_en , 
        //         'photo' => $image , 
        //     ]);
        // endforeach;
        //step2 : copy in old restaurants
        $rest = Restaurant::where('id' , '=' , 276)->get();
        $posters = Poster::all();
        $sens = Sensitivity::all();
        // Storage::disk('public_storage')->deleteDirectory('uploads/posters');
        // Storage::disk('public_storage')->deleteDirectory('uploads/sensitivities');
        foreach($rest as $restaurant):
            $restaurant->sensitivities()->delete();
            foreach($sens as $s):
                // dd($s->image_path);
                $image = copyImage($s->image_path , 'sensitivities' , 'uploads/sensitivities');
                $restaurant->sensitivities()->create([
                    'name_en' => $s->name_en , 
                    'name_ar' => $s->name_ar , 
                    'details_ar'  => $s->details_ar , 
                    'details_en' => $s->details_en,
                    'photo' => $image , 
                ]);
            endforeach;
            $restaurant->posters()->delete();
            foreach($posters as $poster):
                
                $image = copyImage($poster->image_path , 'poster' , 'uploads/posters');
                $restaurant->posters()->create([
                    'name_en' => $poster->name_en , 
                    'name_ar' => $poster->name_ar , 
                    'poster' => $image , 
                ]);
            endforeach;
           
        endforeach;
        return 0;
    }
}
