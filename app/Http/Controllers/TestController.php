<?php

namespace App\Http\Controllers;

use App\Models\History;
use App\Models\MenuCategory;
use App\Models\Poster;
use App\Models\Product;
use App\Models\Reservation\ReservationOrder;
use App\Models\Reservation\ReservationTable;
use App\Models\Reservation\ReservationTablePeriod;
use App\Models\Restaurant;
use App\Models\RestaurantPoster;
use App\Models\RestaurantSensitivity;
use App\Models\Sensitivity;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class TestController extends Controller
{
    //

    public function index(Request $request)
    {
        
         $restaurant = auth('restaurant')->user();

        $d = '{"data":{"branch":{"id":"987b3651-3efc-4b63-a3f1-0a5091467e85","name":"Branch 1","name_localized":null,"reference":"B01","type":1,"latitude":null,"longitude":null,"phone":null,"opening_from":"16:00","opening_to":"04:00","inventory_end_of_day_time":"04:00","receipt_header":null,"receipt_footer":null,"settings":{"sa_zatca_branch_address":[],"display_background_image":null},"created_at":"2023-02-16 12:13:16","updated_at":"2023-05-31 14:08:35","deleted_at":null,"receives_online_orders":true,"accepts_reservations":true,"reservation_duration":30,"reservation_times":null,"address":null},"promotion":null,"original_order":null,"table":{"section":{"id":"989340a6-7e3a-4f89-adf5-333e52079996","name":"outdoor","name_localized":null,"created_at":"2023-02-28 11:02:02","updated_at":"2023-02-28 11:02:02","deleted_at":null},"id":"989345f2-5f01-452f-82a6-00dbcd12886d","name":"OUT View 7","status":1,"seats":2,"created_at":"2023-02-28 11:16:51","updated_at":"2023-06-05 17:36:24","deleted_at":null,"accepts_reservations":false},"creator":null,"closer":{"pin":"*****","is_owner":false,"id":"994b79af-ce61-4ace-8777-4311fd889b4d","name":"\u0627\u0644\u0645\u0634\u0631\u0641","number":null,"email":null,"phone":null,"lang":"ar","display_localized_names":false,"email_verified":false,"must_use_fingerprint":false,"last_console_login_at":null,"last_cashier_login_at":null,"associate_to_all_branches":false,"created_at":"2023-05-31 00:53:51","updated_at":"2023-05-31 00:53:51","deleted_at":null},"driver":null,"customer":null,"customer_address":null,"discount":null,"tags":[],"coupon":null,"gift_card":null,"charges":[],"payments":[],"products":[{"product":{"category":{"id":"988d4252-bf17-4246-9058-c916230d9265","name":"\u0645\u0634\u0631\u0648\u0628\u0627\u062a","name_localized":null,"reference":"drinks","image":null,"created_at":"2023-02-25 11:31:45","updated_at":"2023-02-25 11:31:45","deleted_at":null},"ingredients":[],"id":"989b4a83-9ef5-47c7-89f3-220b2cf63fbf","sku":"sk-0061","barcode":null,"name":"\u0645\u064a\u0627\u0647","name_localized":null,"description":null,"description_localized":null,"image":"https:\/\/foodics-console-production.s3.eu-west-1.amazonaws.com\/images\/597612_1678204238_98a1bcc0-c942-4c71-8fcd-d23919650f63.jpeg","is_active":true,"is_stock_product":false,"is_ready":true,"pricing_method":1,"selling_method":1,"costing_method":2,"preparation_time":null,"price":5,"cost":null,"calories":null,"created_at":"2023-03-04 10:56:14","updated_at":"2023-03-17 16:59:39","deleted_at":null,"meta":null},"promotion":null,"discount":null,"options":[],"taxes":[],"timed_events":[],"void_reason":{"id":"987b3651-4ee3-4d3a-bbdc-b75887574869","type":1,"name":"Customer Cancelled","name_localized":"Customer Cancelled","created_at":"2023-02-16 12:13:16","updated_at":"2023-02-16 12:13:16","deleted_at":null},"creator":null,"voider":{"pin":"*****","is_owner":false,"id":"994b79af-ce61-4ace-8777-4311fd889b4d","name":"\u0627\u0644\u0645\u0634\u0631\u0641","number":null,"email":null,"phone":null,"lang":"ar","display_localized_names":false,"email_verified":false,"must_use_fingerprint":false,"last_console_login_at":null,"last_cashier_login_at":null,"associate_to_all_branches":false,"created_at":"2023-05-31 00:53:51","updated_at":"2023-05-31 00:53:51","deleted_at":null},"id":"99501a8b-36d6-44e2-b6f4-3a2064018d50","discount_type":null,"quantity":1,"returned_quantity":0,"unit_price":5,"discount_amount":0,"total_price":5,"total_cost":0,"tax_exclusive_discount_amount":0,"tax_exclusive_unit_price":4.34783,"tax_exclusive_total_price":4.34783,"status":5,"is_ingredients_wasted":0,"delay_in_seconds":null,"kitchen_notes":null,"meta":{"foodics":{"uuid":"706de30a-b72d-4eee-878c-f3a1f491bd8b","void_approver_id":"994b79af-ce61-4ace-8777-4311fd889b4d"}},"added_at":"2023-06-02 08:06:57","closed_at":"2023-06-02 17:35:09"}],"combos":[],"device":{"id":"987b3651-4654-42d4-9188-66d0b45665b3","name":"Cashier 1","code":"55409","reference":"C01","type":1},"id":"99501a8b-3313-433a-84a7-d44133db1c16","app_id":"94a7eeac-5881-4b19-ae6a-024debd9ac05","promotion_id":null,"discount_type":null,"reference_x":null,"number":2,"type":1,"source":2,"status":7,"delivery_status":1,"guests":1,"kitchen_notes":null,"customer_notes":null,"business_date":"2023-06-02","subtotal_price":0,"discount_amount":0,"rounding_amount":0,"total_price":0,"tax_exclusive_discount_amount":0,"delay_in_seconds":null,"meta":{"foodics":{"device_id":"987b3651-4654-42d4-9188-66d0b45665b3","auto_closed":false,"void_approver_id":"994b79af-ce61-4ace-8777-4311fd889b4d","cashier_received_at":"2023-06-02 14:59:30","kitchen_received_at":"2023-06-02 14:59:39"},"external_number":1636},"opened_at":"2023-06-02 08:06:57","accepted_at":"2023-06-02 14:59:30","due_at":null,"driver_assigned_at":null,"dispatched_at":null,"driver_collected_at":null,"delivered_at":null,"closed_at":"2023-06-02 17:35:09","created_at":"2023-06-02 08:06:57","updated_at":"2023-06-02 17:37:08","reference":9012,"check_number":108999}}';
        return json_decode($d , true);

        $code =rand(1000 , 9999);
        
        $country = '2';
        $country = $user->country->code;
            // send code to phone_number
            $msg = 'كود تحقق ايزي مينو \n  ' .$code;
            $msg = app()->getLocale() == 'ar' ?  $code . ' كود التحقق الخاص بك في أيزي منيو مؤسسة تقني '  : 'EasyMenu verification code is : ' . $code. '  ' . 'مؤسسة تقني';
            $check = substr($request->phone_number, 0, 2) === '05';
            if ($check == true) {
                $phone = $country . ltrim($request->phone_number, '0');
            } else {
                $phone = $country . $request->phone_number;
            }
        // send code to phone_number
        
        taqnyatSms($msg, $phone);
        return view('test');
        return var_dump(env('APP_SMS_TEST' , false));
        return $this->foodicsTest();
       
        return Session::getId();
        
    }
    private function foodicsTest(){
        $token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjZlZWY1ODA4Yzk3YjhlYzVlMTZhYTZmMWNiYzdlZjk4ZDc1M2I5ZTZjOTZiNDk2MDc3OTgxOWQ5ZWFhOWU1MWJhM2FiMTYxM2Y3NDRlM2QzIn0.eyJhdWQiOiI5NGE3ZWVhYy01ODgxLTRiMTktYWU2YS0wMjRkZWJkOWFjMDUiLCJqdGkiOiI2ZWVmNTgwOGM5N2I4ZWM1ZTE2YWE2ZjFjYmM3ZWY5OGQ3NTNiOWU2Yzk2YjQ5NjA3Nzk4MTlkOWVhYTllNTFiYTNhYjE2MTNmNzQ0ZTNkMyIsImlhdCI6MTY4NDc4NTAxMSwibmJmIjoxNjg0Nzg1MDExLCJleHAiOjE4NDI2Mzc4MTEsInN1YiI6Ijk4N2IzNjBjLTRhMTItNDA5Zi04NTc2LWE4ZjRkODQ0NjBhMiIsInNjb3BlcyI6W10sImJ1c2luZXNzIjoiOTg3YjM2MGMtNTIyYS00NTA3LThiMGQtOTE5ODNhZWU3ZTFmIiwicmVmZXJlbmNlIjoiNTk3NjEyIn0.YXQXUQXqy1TnDhvS4WOb6yfv5WCh7iqIQ4LdmbA2ZysMxAKOhNM1WlAyX8-DRm4DVLACzDeMNbom_s7OEC7NLiMlAFpLTn0BJv83sRoPsJdRFaHS3l2QNiuDNpajfb6d1IhACVotdln8Et5etZ7fKRTA5YwxuRILc6HMg540CnSly6oJR0fZjZ7ck74bmKODhpUvrUHiWhYrtucKiU7SRT3qsJb24Ec0-TE9VrASZPwLTcALhhddhwCVQU5bFnKJBMdcxa8Mhu2ciHU3Xi8AAHV3U5IhpS8SgiLwsDRlNK27j082HsqrIZCIvY09W8BNCAPDed-0yN4cdVMxEDEacuSId6J1FXSTXl7Eg4V6mpsB5OfBipXOK5pxVlLnQAz5enwIUb2Dsvfh7d5Nqla7DHB3DY6D2auz2rp8ScHMFIPtj-0lja2MeGFlLbm_rD9zTvcJv5tT_pW6dgvL1Aruv3pNSMu6fpHHiJMuMzA_fKi0Bj2hs04PHhIkT0-HPRG28qAps5Rtbqax1kVYNTTho7UboQ3NA2VXpRDOHtOL5PvHVUyeh2RzHJeMR-EsGOI9sABcmdlRLhLFqSzoQEKpuo9mk-hpgJKZirqIavVIa3rsELhokWUqMejFOzFTI8UUxXskEZl7Njj7GE5DkG3k1EE2xa1eYf7hoBdRTDi1hRY";
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token , 
            'Content-Type' => 'application/json' , 
            // 'Accept' => 'application/json' , 
        ])->get('https://api.foodics.com/v5/orders/98f1c668-0231-46d4-ab28-6c496c675c72');
        $response = getFoodicsOrder('98f1c668-0231-46d4-ab28-6c496c675c72' , $token);
        return $response->json();
    }




    private function storeDefaultSenistive(){
        $restaurant_sensitivities = array(
            array('id' => '9504', 'restaurant_id' => '976', 'name_ar' => 'الأسماك ومنتجاتها', 'name_en' => 'Fish and its products', 'photo' => 'fish.png', 'details_ar' => 'مثل لحوم الأسماك وزيت السمك', 'details_en' => 'Like fish and fish oil', 'created_at' => '2022-11-09 20:22:13', 'updated_at' => '2022-11-09 20:22:13'),
            array('id' => '9505', 'restaurant_id' => '976', 'name_ar' => 'البيض ومنتجاته', 'name_en' => 'eggs and its products', 'photo' => 'egg.png', 'details_ar' => 'مثل المايونيز', 'details_en' => 'Like mayonnaise', 'created_at' => '2022-11-09 20:22:13', 'updated_at' => '2022-11-09 20:22:13'),
            array('id' => '9506', 'restaurant_id' => '976', 'name_ar' => 'الحبوب التي تحتوي على مادة الجلوتين', 'name_en' => 'Cereals that contain gluten', 'photo' => 'hop.png', 'details_ar' => 'مثل (القمح والشعير والشوفان والشيلم ســـواء الأنواع الأصلية منها أو المهجنة أو منتجاتها).', 'details_en' => 'Such as (wheat, barley, oats and rye, whether original or hybrid types or their products).', 'created_at' => '2022-11-09 20:22:13', 'updated_at' => '2022-11-09 20:22:13'),
            array('id' => '9507', 'restaurant_id' => '976', 'name_ar' => 'القشـــريات ومنتجاتها', 'name_en' => 'Crustaceans and their products', 'photo' => 'aqra.png', 'details_ar' => 'مثل (ربيان، ســـرطان البحر أو ما يعرف بالسلطعون، جراد البحر أو ما يعرف باللوبستر).', 'details_en' => 'Such as (prawns, crabs or what is known as crab, lobster or what is known as lobster).', 'created_at' => '2022-11-09 20:22:13', 'updated_at' => '2022-11-09 20:22:13'),
            array('id' => '9508', 'restaurant_id' => '976', 'name_ar' => 'الحليب ومنتجاته (التـــي تحتوي على ال?كتوز)', 'name_en' => 'Milk and milk products (containing lactose)', 'photo' => 'milk.png', 'details_ar' => 'مثل الحليب والحليب المنكه', 'details_en' => 'Like milk and flavored milk', 'created_at' => '2022-11-09 20:22:13', 'updated_at' => '2022-11-09 20:22:13'),
            array('id' => '9509', 'restaurant_id' => '976', 'name_ar' => 'الخردل ومنتجاته', 'name_en' => 'Mustard and its products', 'photo' => 'kardal.png', 'details_ar' => 'مثل بـــذور الخردل، زيـــتالخردل، صلصة الخردل', 'details_en' => 'Like mustard seeds, mustard oil, mustard sauce', 'created_at' => '2022-11-09 20:22:13', 'updated_at' => '2022-11-09 20:22:13'),
            array('id' => '9510', 'restaurant_id' => '976', 'name_ar' => 'الرخويات ومنتجاتها', 'name_en' => 'Mollusks and their products', 'photo' => 'raky.png', 'details_ar' => 'مثل (الحبار، الحلـــزون البحري، بلح البحر، واأسكالوب)', 'details_en' => 'Such as (squid, sea snail, mussels, and scallops)', 'created_at' => '2022-11-09 20:22:13', 'updated_at' => '2022-11-09 20:22:13'),
            array('id' => '9511', 'restaurant_id' => '976', 'name_ar' => 'الفول السوداني ومنتجاته', 'name_en' => 'Peanut and its products', 'photo' => 'butter.png', 'details_ar' => 'مثل زبدة الـفول السوداني', 'details_en' => 'Like peanut butter', 'created_at' => '2022-11-09 20:22:13', 'updated_at' => '2022-11-09 20:22:13'),
            array('id' => '9512', 'restaurant_id' => '976', 'name_ar' => 'الكبريتيت', 'name_en' => 'sulfites', 'photo' => 'capret.png', 'details_ar' => 'بتركيز 10 جزء في المليون أو أكثر', 'details_en' => 'At a concentration of 10 ppm or more', 'created_at' => '2022-11-09 20:22:13', 'updated_at' => '2022-11-09 20:22:13'),
            array('id' => '9513', 'restaurant_id' => '976', 'name_ar' => 'الكرفس ومنتجاته', 'name_en' => 'Celery and its products', 'photo' => 'rfs.png', 'details_ar' => 'مثل بذور الكرفس وملح الكرفس', 'details_en' => 'Like celery seeds and celery salt', 'created_at' => '2022-11-09 20:22:13', 'updated_at' => '2022-11-09 20:22:13'),
            array('id' => '9514', 'restaurant_id' => '976', 'name_ar' => 'المكسرات ومنتجاتها', 'name_en' => 'Nuts and their products', 'photo' => 'kago.png', 'details_ar' => 'مثـــل الكاجو والفســـتق وغيرها', 'details_en' => 'Like cashews, pistachios, etc', 'created_at' => '2022-11-09 20:22:13', 'updated_at' => '2022-11-09 20:22:13'),
            array('id' => '9515', 'restaurant_id' => '976', 'name_ar' => 'بذور السمسم ومتجاتها', 'name_en' => 'Sesame seeds and their products', 'photo' => 'smsm.png', 'details_ar' => 'مثل زيت السمسم', 'details_en' => 'like sesame oil', 'created_at' => '2022-11-09 20:22:13', 'updated_at' => '2022-11-09 20:22:13'),
            array('id' => '9516', 'restaurant_id' => '976', 'name_ar' => 'فول الصويا ومنتجاته', 'name_en' => 'Soybean and its products', 'photo' => 'soia.png', 'details_ar' => 'مثل حليب الصويا ', 'details_en' => 'like soy milk', 'created_at' => '2022-11-09 20:22:13', 'updated_at' => '2022-11-09 20:22:13'),
            array('id' => '9517', 'restaurant_id' => '976', 'name_ar' => 'لوبين (الترمس ومنتجاتها)', 'name_en' => 'Lupine (lupine and its products)', 'photo' => 'terms.png', 'details_ar' => 'مثل زيت الترمس', 'details_en' => 'like lupine oil', 'created_at' => '2022-11-09 20:22:13', 'updated_at' => '2022-11-09 20:22:13')
        );
        foreach ($restaurant_sensitivities as $temp) :
            Sensitivity::create($temp);
        endforeach;
        return 'done';
    }
}
