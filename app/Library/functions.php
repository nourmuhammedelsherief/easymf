<?php

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Poster;
use App\Models\Restaurant;
use App\Models\RestaurantEmployee;
use App\Models\RestaurantSensitivity;
use App\Models\RestaurantSlider;
use App\Models\Sensitivity;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use LaravelFCM\Facades\FCM;
use App\Models\Setting;
use App\Models\TableOrder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;

const myfatooraTokenTest = "rLtt6JWvbUHDDhsZnfpAhpYk4dxYDQkbcPTyGaKp2TYqQgG7FGZ5Th_WD53Oq8Ebz6A53njUoo1w3pjU1D4vs_ZMqFiz_j0urb_BH9Oq9VZoKFoJEDAbRZepGcQanImyYrry7Kt6MnMdgfG5jn4HngWoRdKduNNyP4kzcp3mRv7x00ahkm9LAK7ZRieg7k1PDAnBIOG3EyVSJ5kK4WLMvYr7sCwHbHcu4A5WwelxYK0GMJy37bNAarSJDFQsJ2ZvJjvMDmfWwDVFEVe_5tOomfVNt6bOg9mexbGjMrnHBnKnZR1vQbBtQieDlQepzTZMuQrSuKn-t5XZM7V6fCW7oP-uXGX-sMOajeX65JOf6XVpk29DP6ro8WTAflCDANC193yof8-f5_EYY-3hXhJj7RBXmizDpneEQDSaSz5sFk0sV5qPcARJ9zGG73vuGFyenjPPmtDtXtpx35A-BVcOSBYVIWe9kndG3nclfefjKEuZ3m4jL9Gg1h2JBvmXSMYiZtp9MR5I6pvbvylU_PP5xJFSjVTIz7IQSjcVGO41npnwIxRXNRxFOdIUHn0tjQ-7LwvEcTXyPsHXcMD8WtgBh-wxR8aKX7WPSsT1O8d8reb2aR7K3rkV3K82K_0OgawImEpwSvp9MNKynEAJQS6ZHe_J_l77652xwPNxMRTMASk1ZsJL";
const myfatooraUrlTest  = 'https://apitest.myfatoorah.com';
//use FCM;
function SetUserName($id)
{
    \Illuminate\Support\Facades\Session::forget('pid');
    \Illuminate\Support\Facades\Session::put('pid', $id);
}

function explodeByComma($str)
{
    return explode(",", $str);
}

function explodeByDash($str)
{
    return explode("-", $str);
}

function imgPath($folderName)
{

    //عشان ال sub domain  بس هيشها مؤقتا
//    return '/uploads/' . $folderName . '/';
    return '/public/uploads/' . $folderName . '/';
}

function settings()
{

    return Setting::where('id', 1)->first();
}

function validateRules($errors, $rules)
{

    $error_arr = [];

    foreach ($rules as $key => $value) {

        if ($errors->get($key)) {

            array_push($error_arr, array('key' => $key, 'value' => $errors->first($key)));
        }
    }

    return $error_arr;
}

//function randNumber($userId, $length) {
//
//    $seed = str_split('0123456789');
//
//    shuffle($seed);
//
//    $rand = '';
//
//    foreach (array_rand($seed, $length) as $k) $rand .= $seed[$k];
//
////    return $userId * $userId . $rand;
//    return $userId . $rand;
//}

function randNumber($length)
{

    $seed = str_split('0123456789');

    shuffle($seed);

    $rand = '';

    foreach (array_rand($seed, $length) as $k) $rand .= $seed[$k];

    return $rand;
}

function generateApiToken($userId, $length)
{

    $seed = str_split('abcdefghijklmnopqrstuvwxyz' . 'ABCDEFGHIJKLMNOPQRSTUVWXYZ' . '0123456789');

    shuffle($seed);

    $rand = '';

    foreach (array_rand($seed, $length) as $k) $rand .= $seed[$k];

    return $userId * $userId . $rand;
}

function UploadBase64Image($base64Str, $prefix, $folderName)
{

    $image = base64_decode($base64Str);
    $image_name = $prefix . '_' . time() . '.png';
    $path = public_path('uploads') . DIRECTORY_SEPARATOR . $folderName . DIRECTORY_SEPARATOR . $image_name;

    $saved = file_put_contents($path, $image);

    return $saved ? $image_name : NULL;
}


function gold_services($id , $service_id , $end_at)
{
    $restaurant = Restaurant::find($id);
    $service = \App\Models\Service::find($service_id);
    $check_subscription = \App\Models\ServiceSubscription::whereRestaurantId($restaurant->id)
        ->whereServiceId($service->id)
        ->first();
    if ($check_subscription == null)
    {
        \App\Models\ServiceSubscription::create([
            'restaurant_id' => $restaurant->id,
            'service_id' => $service->id,
            'restaurant_name' => $restaurant->name_ar,
            'restaurant_phone' => $restaurant->phone_number,
            'price'  => $service->price,
            'paid_at' => \Carbon\Carbon::now(),
            'type'  => 'online',
            'end_at' => $end_at,
            'status' => 'active',
        ]);
    }else{
        $check_subscription->update([
            'end_at' => $end_at,
            'status' => 'active',
        ]);
    }
}


function UploadImage($inputRequest, $prefix, $folderNam)
{

    if(in_array($inputRequest->getClientOriginalExtension() , ['gif'])):
        return basename(Storage::disk('public_storage')->put($folderNam , $inputRequest ));
    endif;
    $image = time() . '' . rand(11111, 99999) . '.' . $inputRequest->getClientOriginalExtension();
    $destinationPath = public_path('/' . $folderNam);
    $img = Image::make($inputRequest->getRealPath());
    $img->resize(500, 500, function ($constraint) {
        $constraint->aspectRatio();
    })->save($destinationPath . '/' . $image);

    return $image ? $image : false;

}

function copyImage($filename, $prefix, $folderNam)
{
    if(!Storage::disk('public_storage')->exists($filename)) return '';
    $temp = explode('.' , $filename);
    $ext = $temp[count($temp) - 1];
    $image = 'copy_' . time() . '' . rand(11111, 99999) . '.' . $ext;
    $destinationPath = public_path('/' . $folderNam);
    if(!Storage::disk('public_storage')->exists($folderNam)):
        File::isDirectory($destinationPath) or File::makeDirectory($destinationPath, 0777, true, true);
    endif;
    $img = Image::make(public_path($filename));
    $img->save($destinationPath . '/' . $image);

    return $image ? $image : false;

}

function UploadFile($inputRequest, $prefix, $folderNam)
{

    $imageName = $prefix . '_' . time() . '.' . $inputRequest->getClientOriginalExtension();

    $destinationPath = public_path('/' . $folderNam);
    $inputRequest->move($destinationPath, $imageName);
    // dd($destinationPath);

    return $imageName ? $imageName : false;
}

function UploadFileEdit($inputRequest, $prefix, $folderNam, $old = null)
{
    if ($old) {
        @unlink(public_path('/uploads/files/' . $old));
    }

    $imageName = $prefix . '_' . time() . '.' . $inputRequest->getClientOriginalExtension();

    $destinationPath = public_path('/' . $folderNam);
    $inputRequest->move($destinationPath, $imageName);
    // dd($destinationPath);

    return $imageName ? $imageName : false;
}

function UploadVideo($file)
{
    if ($file) {
        $filename = $file->getClientOriginalName();
        $path = public_path() . '/uploads/videos';
        $file->move($path, $filename);
        return $filename;
    }
}

function UploadVideoEdit($file, $old)
{
    if ($old) {
        @unlink(public_path('/uploads/videos/' . $old));
    }
    if ($file) {
        $filename = $file->getClientOriginalName();
        $path = public_path() . '/uploads/videos';
        $file->move($path, $filename);
        return $filename;
    }
}

function UploadImageEdit($inputRequest, $prefix, $folderNam, $oldImage , $height = 700 , $width = 700)
{
    if ($oldImage != 'logo.png' && $oldImage != 'slider2.png' && $oldImage != 'slider1.png' && $oldImage != 'fish.png' && $oldImage != 'egg.png' && $oldImage != 'hop.png' && $oldImage != 'aqra.png' && $oldImage != 'milk.png' && $oldImage != 'kardal.png' && $oldImage != 'raky.png' && $oldImage != 'butter.png' && $oldImage != 'capret.png' && $oldImage != 'rfs.png' && $oldImage != 'kago.png' && $oldImage != 'smsm.png' && $oldImage != 'soia.png' && $oldImage != 'terms.png')
    {
        @unlink(public_path('/' . $folderNam . '/' . $oldImage));
    }
    $path = public_path().$folderNam;


    if(!file_exists($path)):
        File::isDirectory($path) or File::makeDirectory($path, 0777, true, true);
    endif;
    if(in_array($inputRequest->getClientOriginalExtension() , ['gif'])){
        return basename(Storage::disk('public_storage')->put($folderNam , $inputRequest));
    }
    $image = time() . '' . rand(11111, 99999) . '.' . $inputRequest->getClientOriginalExtension();
    $destinationPath = public_path('/' . $folderNam);
    $img = Image::make($inputRequest->getRealPath());
    $img->resize($height , $width, function ($constraint) {
        $constraint->aspectRatio();
        // $constraint->upsize();
    })->save($destinationPath . '/' . $image);
    return $image ? $image : false;
}



function sendNotification($notificationTitle, $notificationBody, $deviceToken)
{

    $optionBuilder = new OptionsBuilder();
    $optionBuilder->setTimeToLive(60 * 20);

    $notificationBuilder = new PayloadNotificationBuilder($notificationTitle);
    $notificationBuilder->setBody($notificationBody)
        ->setSound('default');

    $dataBuilder = new PayloadDataBuilder();
    $dataBuilder->addData(['a_data' => 'my_data']);

    $option = $optionBuilder->build();
    $notification = $notificationBuilder->build();
    $data = $dataBuilder->build();

    $token = $deviceToken;

    $downstreamResponse = FCM::sendTo($token, $option, $notification, $data);

    $downstreamResponse->numberSuccess();
    $downstreamResponse->numberFailure();
    $downstreamResponse->numberModification();

//return Array - you must remove all this tokens in your database
    $downstreamResponse->tokensToDelete();

//return Array (key : oldToken, value : new token - you must change the token in your database )
    $downstreamResponse->tokensToModify();

//return Array - you should try to resend the message to the tokens in the array
    $downstreamResponse->tokensToRetry();

// return Array (key:token, value:errror) - in production you should remove from your database the tokens
}

function sendMultiNotification($notificationTitle, $notificationBody, $devicesTokens)
{

    $optionBuilder = new OptionsBuilder();
    $optionBuilder->setTimeToLive(60 * 20);

    $notificationBuilder = new PayloadNotificationBuilder($notificationTitle);
    $notificationBuilder->setBody($notificationBody)
        ->setSound('default');

    $dataBuilder = new PayloadDataBuilder();
    $dataBuilder->addData(['a_data' => 'my_data']);

    $option = $optionBuilder->build();
    $notification = $notificationBuilder->build();
    $data = $dataBuilder->build();

// You must change it to get your tokens
    $tokens = $devicesTokens;

    $downstreamResponse = FCM::sendTo($tokens, $option, $notification, $data);

    $downstreamResponse->numberSuccess();
    $downstreamResponse->numberFailure();
    $downstreamResponse->numberModification();

//return Array - you must remove all this tokens in your database
    $downstreamResponse->tokensToDelete();

//return Array (key : oldToken, value : new token - you must change the token in your database )
    $downstreamResponse->tokensToModify();

//return Array - you should try to resend the message to the tokens in the array
    $downstreamResponse->tokensToRetry();

// return Array (key:token, value:errror) - in production you should remove from your database the tokens present in this array
    $downstreamResponse->tokensWithError();

    return ['success' => $downstreamResponse->numberSuccess(), 'fail' => $downstreamResponse->numberFailure()];
}

function saveNotification($userId, $title, $message, $type, $order_id = null, $device_token = null)
{

    $created = \App\UserNotification::create([
        'user_id' => $userId,
        'title' => $title,
        'type' => $type,
        'message' => $message,
        'order_id' => $order_id,
        'device_token' => $device_token,
    ]);
    return $created;
}

function check_time_between($start_at , $end_at)
{
    if ($start_at == null and $end_at == null)
    {
        return true;
    }
    $now = \Carbon\Carbon::now()->format('H:i:s');
    if ($start_at > $end_at)
    {
        // the end at another day
        if ($start_at < $now)
        {
            $start = \Carbon\Carbon::now()->format('Y-m-d'.' '.$start_at);
            $end  = \Carbon\Carbon::now()->addDay()->format('Y-m-d'.' '.$end_at);
            $check = \Carbon\Carbon::now()->between($start, $end, true);
        }else{
            $start = \Carbon\Carbon::now()->addDays(-1)->format('Y-m-d'.' '.$start_at);
            $end  = \Carbon\Carbon::now()->format('Y-m-d'.' '.$end_at);
            $check = \Carbon\Carbon::now()->between($start, $end, true);
        }
    }
    else{
        $start = \Carbon\Carbon::now()->format('Y-m-d'.' '.$start_at);
        $end  = \Carbon\Carbon::now()->format('Y-m-d'.' '.$end_at);
        $check = \Carbon\Carbon::now()->between($start, $end, true);
    }
    return $check;
}


####### Check Payment Status ######
function MyFatoorahStatus($api, $PaymentId)
{
    // dd($PaymentId);
    $token = $api;
    $basURL = "https://api-sa.myfatoorah.com";
    if(env('APP_PAYMENT_TEST' , false)){
        $basURL = myfatooraUrlTest;
        $token = myfatooraTokenTest;
    }
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "$basURL/v2/GetPaymentStatus",
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "{\"Key\": \"$PaymentId\",\"KeyType\": \"PaymentId\"}",
        CURLOPT_HTTPHEADER => array("Authorization: Bearer $token", "Content-Type: application/json"),
    ));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
   
    if ($err) {
       
        return $err;
    } else {
        return $response;
    }
}

// ===============================  MyFatoorah public  function  =========================
function MyFatoorah($api, $userData)
{
    $token = $api;
    // $token = Controller
    $basURL = "https://api-sa.myfatoorah.com";
    if(env('APP_PAYMENT_TEST' , false)){
        $basURL = myfatooraUrlTest;
        $token = myfatooraTokenTest;
    }
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "$basURL/v2/ExecutePayment",
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $userData,
        CURLOPT_HTTPHEADER => array("Authorization: Bearer $token", "Content-Type: application/json"),
    ));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        return $err;
    } else {
        return $response;
    }
}

/**
 * calculate the distance between tow places on the earth
 *
 * @param latitude $latitudeFrom
 * @param longitude $longitudeFrom
 * @param latitude $latitudeTo
 * @param longitude $longitudeTo
 * @return double distance in KM
 */
function distanceBetweenTowPlaces($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo)
{
    $long1 = deg2rad($longitudeFrom);
    $long2 = deg2rad($longitudeTo);
    $lat1 = deg2rad($latitudeFrom);
    $lat2 = deg2rad($latitudeTo);
    //Haversine Formula
    $dlong = $long2 - $long1;
    $dlati = $lat2 - $lat1;
    $val = pow(sin($dlati / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($dlong / 2), 2);
    $res = 2 * asin(sqrt($val));
    $radius = 6367.756;
    return ($res * $radius);
}


/**
 *  Taqnyat sms to send message
 */
function taqnyatSms($msgBody, $reciver)
{
    $setting = Setting::find(1);
    $bearer = $setting->bearer_token;
    $sender = $setting->sender_name;
    $taqnyt = new TaqnyatSms($bearer);

    $body = $msgBody;
    $recipients = $reciver;
    $message = $taqnyt->sendMsg($body, $recipients, $sender);
    return $message;
}

function checkOrderService($restaurant_id, $service_id , $branch_id = null)
{
    if ($branch_id)
    {
        $service = \App\Models\ServiceSubscription::whereRestaurantId($restaurant_id)
            ->where('service_id', $service_id)
            ->whereIn('status',['active' , 'tentative'])
            ->whereBranchId($branch_id)
            ->first();
    }else{
        $service = \App\Models\ServiceSubscription::whereRestaurantId($restaurant_id)
            ->where('service_id', $service_id)
            ->whereIn('status',['active' , 'tentative'])
            ->first();
    }
    return !($service == null) ? true : false;
}
function checkOrderSetting($restaurant_id, $type , $branch_id = null)
{
    if ($branch_id)
    {
        $setting = \App\Models\RestaurantOrderSetting::whereRestaurantId($restaurant_id)
            ->where('order_type', $type)
            ->whereBranchId($branch_id)
            ->first();
    }else{
        $setting = \App\Models\RestaurantOrderSetting::whereRestaurantId($restaurant_id)
            ->where('order_type', $type)
            ->first();
    }
    return !($setting == null) ? true : false;
}


function check_branch_periods($id)
{
    $branch = \App\Models\Branch::find($id);
    // check if the branch has periods or not
    $current_day = \Carbon\Carbon::now()->format('l');
    $day = \App\Models\Day::whereNameEn($current_day)->first()->id;
    $periods = \App\Models\RestaurantPeriod::with('days')
        ->whereHas('days' , function ($q) use ($day){
            $q->where('day_id' , $day);
        })
        ->where('restaurant_id' , $branch->restaurant_id)
        ->where('branch_id' , $branch->id)
        ->get();
    if ($periods->count() > 0)
    {
        foreach ($periods as $period) {
            $state = check_time_between($period->start_at , $period->end_at);
            if ($state == true)
            {
                return $state;
            }
        }
        return false;
    }else{
        $check_period = \App\Models\RestaurantPeriod::where('restaurant_id' , $branch->restaurant_id)
            ->where('branch_id' , $branch->id)
            ->count();
        return $check_period > 0 ? false : true;
    }
}

function check_restaurant_permission($res_id , $permission_id){
    $permission = \App\Models\RestaurantPermission::whereRestaurantId($res_id)
        ->wherePermissionId($permission_id)
        ->first();
    return !($permission == null) ? true : false;
}


function auth_paymob()
{
    $basURL = "https://accept.paymob.com/api/auth/tokens";
    $headers = array(
        'Content-type: application/json',
        'Accept: application/json',
    );

    $order = array(
        "api_key" => "ZXlKaGJHY2lPaUpJVXpVeE1pSXNJblI1Y0NJNklrcFhWQ0o5LmV5SmpiR0Z6Y3lJNklrMWxjbU5vWVc1MElpd2libUZ0WlNJNklqRTJOVGd6TWpjeE9URXVNamczT1RreElpd2ljSEp2Wm1sc1pWOXdheUk2TWpRd05UWTRmUS5Zb1lOY3ZOenN6aVltLS1WaDlnalFVdzR5dDk4N3U0Q0hwalZJVVpoallvNmdST1lPVlpBNW1feDQzLWZjdlY2ME1VejhCTXM0VFdLaWtvQmN4UWZLQQ=="
    );
    $order = json_encode($order);
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $basURL,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $order,
        CURLOPT_HTTPHEADER => $headers,
    ));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        return $err;
    } else {
        dd($response);
        return $response;
    }
}

function paymob()
{
    $basURL = "https://accept.paymob.com/api/ecommerce/orders";
    $headers = array(
        'Content-type: application/json',
        'Accept: application/json',
    );

    $order = array(
        "auth_token" => "ZXlKaGJHY2lPaUpJVXpVeE1pSXNJblI1Y0NJNklrcFhWQ0o5LmV5SmpiR0Z6Y3lJNklrMWxjbU5vWVc1MElpd2ljR2hoYzJnaU9pSmpZelV5TTJFNVptVXdZVFEzTmpCbFlqTm1NRFkyWkRReU9XSXdabVppWXpabU1EUXlOemhqWWpoak16SXhOV0ZqTkdSaFpUUTJNV0ZqTldFd1lUVmpJaXdpWlhod0lqb3hOalU0TXpNM05qSTJMQ0p3Y205bWFXeGxYM0JySWpveU5EQTFOamg5LjhxTDUxV0VIeUsxNUZHZWxMd09rWU9mTHJFSEdsdU1jY2JmYjNCcjZuNG5HY21rT0NuaWpYU3lWdHhSZFl6RFc4QW9nRnlIeENVT2J4WGtvcEZPVG1R",
        "delivery_needed" => "false",
        "amount_cents" => "100",
        "currency" => "EGP",
        "merchant_order_id" => 10045,
        "items" => array(
            array(
                "name" => "ASC1515",
                "amount_cents" => "500000",
                "description" => "Smart Watch",
                "quantity" => "1"
            ),
        ),
        "shipping_data" => array(
            "apartment" => "803",
            "email" => "claudette09@exa.com",
            "floor" => "42",
            "first_name" => "Clifford",
            "street" => "Ethan Land",
            "building" => "8028",
            "phone_number" => "+86(8)9135210487",
            "postal_code" => "01898",
            "extra_description" => "8 Ram , 128 Giga",
            "city" => "Jaskolskiburgh",
            "country" => "CR",
            "last_name" => "Nicolas",
            "state" => "Utah"
        ),
        "shipping_details" => array(
            "notes" => " test",
            "number_of_packages" => 1,
            "weight" => 1,
            "weight_unit" => "Kilogram",
            "length" => 1,
            "width" => 1,
            "height" => 1,
            "contents" => "product of some sorts"
        )
    );
    $order = json_encode($order);
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $basURL,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $order,
        CURLOPT_HTTPHEADER => $headers,
    ));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        return $err;
    } else {
        $response = array_values(json_decode($response, true));
        dd($response);
        return $response;
    }
}

function meal_accessories($id, $product)
{
    if (get_meals_photos($id) != [] && get_meals_photos($id) != null) {
        if (is_array(get_meals_photos($id))) {
            if (is_countable(get_meals_photos($id))) {
                foreach (get_meals_photos($id) as $photo) {
                    $infoM = pathinfo('https://old.easymenu.site/uploads/meals/' . $photo['image']);
                    if ($infoM['extension'] != 'jpg_1700Wx1700H' && $infoM['extension'] != 'crdownload' && $infoM['extension'] != 'application/x-empty')
                    {
                        if ($infoM['extension'] == 'JPG' || $infoM['extension'] == 'JPEG' || $infoM['extension'] == 'PNG' || $infoM['extension'] == 'wepb' || $infoM['extension'] == 'png' || $infoM['extension'] == 'jpg' || $infoM['extension'] == 'gif' || $infoM['extension'] == 'tif') {
                            $contentsM = file_get_contents('https://old.easymenu.site/uploads/meals/' . $photo['image']);
                            $fileM = '/tmp/' . $infoM['basename'];
                            file_put_contents($fileM, $contentsM);
                            $imageM = $infoM['basename'];
                            $destinationPathM = public_path('/' . 'uploads/products');
                            $imgM = Image::make($fileM);
                            $imgM->resize(500, 500, function ($constraint) {
                                $constraint->aspectRatio();
                            })->save($destinationPathM . '/' . $imageM);
                            // \App\Models\ProductPhoto::create([
                            //     'product_id' => $product->id,
                            //     'photo' => $imageM,
                            // ]);
                            $product->update([
                                'photo' => $imageM,
                            ]);


                        }
                    }

                }
            }
        }
    }
    if (get_meals_sizes($id) != []) {
        if (is_array(get_meals_sizes($id)) || is_object(get_meals_sizes($id)) && count(get_meals_sizes($id)) > 0) {
            foreach (get_meals_sizes($id) as $size) {
                // create meal sizes
                \App\Models\ProductSize::create([
                    'name_ar' => $size['size_ar'],
                    'name_en' => $size['size'],
                    'price' => $size['price'],
                    'calories' => $size['calories'],
                    'product_id' => $product->id,
                ]);
            }

        }
    }
    if (get_meals_modifiers($id) != []) {
        if (is_array(get_meals_modifiers($id)) || is_object(get_meals_modifiers($id)) && count(get_meals_modifiers($id)) > 0) {

            foreach (get_meals_modifiers($id) as $pm) {
                // create meal sizes
                \App\Models\ProductModifier::create([
                    'product_id' => $product->id,
                    'modifier_id' => $pm['main_addition_id']
                ]);
            }

        }
    }
    if (get_meals_options($id) != []) {
        if (is_array(get_meals_options($id)) || is_object(get_meals_options($id)) && get_meals_options($id) > 0) {
            foreach (get_meals_options($id) as $po) {
                // create meal sizes
                \App\Models\ProductOption::create([
                    'product_id' => $product->id,
                    'modifier_id' => \App\Models\Option::where('old_id', $po['addition_id'])->first() == null ? null : \App\Models\Option::where('old_id', $po['addition_id'])->first()->modifier_id,
                    'option_id' => \App\Models\Option::where('old_id', $po['addition_id'])->first() == null ? null : \App\Models\Option::where('old_id', $po['addition_id'])->first()->id,
                    'min' => '1',
                    'max' => '5',
                ]);
            }
        }
    }
    if (get_meals_days($id) != []) {
        if (is_array(get_meals_days($id)) || is_object(get_meals_days($id))) {
            if (count(get_meals_days($id)) > 0) {
                foreach (get_meals_days($id) as $md) {
                    // create meal days
                    \App\Models\ProductDay::create([
                        'product_id' => $product->id,
                        'day_id' => $md['day_id']
                    ]);
                }
            }
        }
    }
}

function create_restaurant_sensitivity($restaurant)
{
    RestaurantSensitivity::create([
        'restaurant_id' => $restaurant,
        'name_ar' => 'الأسماك ومنتجاتها',
        'name_en' => 'Fish and its products',
        'photo' => 'fish.png',
        'details_ar' => 'مثل لحوم الأسماك وزيت السمك',
        'details_en' => 'Like fish and fish oil',
    ]);
    RestaurantSensitivity::create([
        'restaurant_id' => $restaurant,
        'name_ar' => 'البيض ومنتجاته',
        'name_en' => 'eggs and its products',
        'photo' => 'egg.png',
        'details_ar' => 'مثل المايونيز',
        'details_en' => 'Like mayonnaise',
    ]);
    RestaurantSensitivity::create([
        'restaurant_id' => $restaurant,
        'name_ar' => 'الحبوب التي تحتوي على مادة الجلوتين',
        'name_en' => 'Cereals that contain gluten',
        'photo' => 'hop.png',
        'details_ar' => 'مثل (القمح والشعير والشوفان والشيلم ســـواء الأنواع الأصلية منها أو المهجنة أو منتجاتها).',
        'details_en' => 'Such as (wheat, barley, oats and rye, whether original or hybrid types or their products).',
    ]);
    RestaurantSensitivity::create([
        'restaurant_id' => $restaurant,
        'name_ar' => 'القشـــريات ومنتجاتها',
        'name_en' => 'Crustaceans and their products',
        'photo' => 'aqra.png',
        'details_ar' => 'مثل (ربيان، ســـرطان البحر أو ما يعرف بالسلطعون، جراد البحر أو ما يعرف باللوبستر).',
        'details_en' => 'Such as (prawns, crabs or what is known as crab, lobster or what is known as lobster).',
    ]);
    RestaurantSensitivity::create([
        'restaurant_id' => $restaurant,
        'name_ar' => 'الحليب ومنتجاته (التـــي تحتوي على ال?كتوز)',
        'name_en' => 'Milk and milk products (containing lactose)',
        'photo' => 'milk.png',
        'details_ar' => 'مثل الحليب والحليب المنكه',
        'details_en' => 'Like milk and flavored milk',
    ]);
    RestaurantSensitivity::create([
        'restaurant_id' => $restaurant,
        'name_ar' => 'الخردل ومنتجاته',
        'name_en' => 'Mustard and its products',
        'photo' => 'kardal.png',
        'details_ar' => 'مثل بـــذور الخردل، زيـــتالخردل، صلصة الخردل',
        'details_en' => 'Like mustard seeds, mustard oil, mustard sauce',
    ]);
    RestaurantSensitivity::create([
        'restaurant_id' => $restaurant,
        'name_ar' => 'الرخويات ومنتجاتها',
        'name_en' => 'Mollusks and their products',
        'photo' => 'raky.png',
        'details_ar' => 'مثل (الحبار، الحلـــزون البحري، بلح البحر، واأسكالوب)',
        'details_en' => 'Such as (squid, sea snail, mussels, and scallops)',
    ]);
    RestaurantSensitivity::create([
        'restaurant_id' => $restaurant,
        'name_ar' => 'الفول السوداني ومنتجاته',
        'name_en' => 'Peanut and its products',
        'photo' => 'butter.png',
        'details_ar' => 'مثل زبدة الـفول السوداني',
        'details_en' => 'Like peanut butter',
    ]);
    RestaurantSensitivity::create([
        'restaurant_id' => $restaurant,
        'name_ar' => 'الكبريتيت',
        'name_en' => 'sulfites',
        'photo' => 'capret.png',
        'details_ar' => 'بتركيز 10 جزء في المليون أو أكثر',
        'details_en' => 'At a concentration of 10 ppm or more',
    ]);
    RestaurantSensitivity::create([
        'restaurant_id' => $restaurant,
        'name_ar' => 'الكرفس ومنتجاته',
        'name_en' => 'Celery and its products',
        'photo' => 'rfs.png',
        'details_ar' => 'مثل بذور الكرفس وملح الكرفس',
        'details_en' => 'Like celery seeds and celery salt',
    ]);
    RestaurantSensitivity::create([
        'restaurant_id' => $restaurant,
        'name_ar' => 'المكسرات ومنتجاتها',
        'name_en' => 'Nuts and their products',
        'photo' => 'kago.png',
        'details_ar' => 'مثـــل الكاجو والفســـتق وغيرها',
        'details_en' => 'Like cashews, pistachios, etc',
    ]);
    RestaurantSensitivity::create([
        'restaurant_id' => $restaurant,
        'name_ar' => 'بذور السمسم ومتجاتها',
        'name_en' => 'Sesame seeds and their products',
        'photo' => 'smsm.png',
        'details_ar' => 'مثل زيت السمسم',
        'details_en' => 'like sesame oil',
    ]);
    RestaurantSensitivity::create([
        'restaurant_id' => $restaurant,
        'name_ar' => 'فول الصويا ومنتجاته',
        'name_en' => 'Soybean and its products',
        'photo' => 'soia.png',
        'details_ar' => 'مثل حليب الصويا ',
        'details_en' => 'like soy milk',
    ]);
    RestaurantSensitivity::create([
        'restaurant_id' => $restaurant,
        'name_ar' => 'لوبين (الترمس ومنتجاتها)',
        'name_en' => 'Lupine (lupine and its products)',
        'photo' => 'terms.png',
        'details_ar' => 'مثل زيت الترمس',
        'details_en' => 'like lupine oil',
    ]);

}
function check_restaurant_branches($id)
{
    $restaurant = \App\Models\Restaurant::find($id);
    $chech_branches = \App\Models\Subscription::whereRestaurantId($restaurant->id)
        ->where('type' , 'branch')
        ->where('package_id' , '!=' , 1)
        ->count();
    return $chech_branches;
}
function check_restaurant_amount($id , $amount)
{
    $branch = \App\Models\Branch::find($id);
    if ($branch->country->name_ar == 'مصر')
    {
        return $amount * 0.20;
    }elseif ($branch->country->name_ar == 'السعودية')
    {
        return $amount * 1;
    }elseif ($branch->country->name_ar == 'البحرين')
    {
        return $amount * 10;
    }elseif ($branch->country->name_ar == 'الكويت')
    {
        return $amount * 13;
    }elseif ($branch->country->name_ar == 'عمان')
    {
        return $amount * 10;
    }elseif ($branch->country->name_ar == 'الامارات')
    {
        return $amount * 1.3;
    }elseif ($branch->country->name_ar == 'اليمن')
    {
        return $amount * 0.10;
    }else
    {
        return $amount * 1;
    }
}

function checkRestaurantPackageId($restaurant = null, $type = 'restaurant', $packageId = null)
{
    $packageId = is_array($packageId) ? $packageId : [$packageId];
    if ($check = $restaurant->subscription()->where('type', $type)->orderBy('created_at', 'desc')->whereIn('package_id', $packageId)->first()) return true;

    return false;
}

function restaurantPackageId($restaurant){

    if($check = $restaurant->subscription()->orderBy('created_at' , 'desc')->first() and isset($check->subscription->package_id)) return $check->subscription->package_id;

    return false;
}
function employeeGetPackageId(RestaurantEmployee $employee = null){
    $employee = $employee == null ? auth('employee')->user() : $employee;
    if(!isset($employee->id)) return false;
    $branch = $employee->branch()->with('subscription')->first();
    if(isset($branch->subscription->id)) return $branch->subscription->package_id;
    return false;
}


function isUrlActive($url , $checkFull = false , $data = []){
    $check = true;
    $path = Request::path();
    if(count($data) > 0){
        foreach($data as $key => $value){
            if(!Request::has($key) or Request::get($key) != $value) $check = false;
        }
    }
    if(!$checkFull and (!strpos( $path , $url, 0) and $path != $url)) return false;
    elseif($checkFull and $path != $url) return false;
    return $check;
}

function defaultResturantData(Restaurant $restaurant){
    // update data
    
    $restaurant->update([
        'logo' =>'logo.png' ,
        
        'status' => 'tentative',
        'menu_arrange'  => 'false',
        'product_arrange' => 'false',
        'logo' => 'logo.png',
        'menu' =>'vertical',
        'description_ar' => 'نبذة عن المطعم . محتوي  يتم تغييره من خلال لوحة تحكم المطعم',
        'description_en' => 'A Brief About Restaurant Can Be Changed From Restaurant Control Panel',
        'information_ar' => ' يحتاج البالغون الى 2000 سعر حراري في المتوسط يومياً
يحتاج غير البالغون الى 1400 سعر حراري في المتوسط يومياً',
        'information_en' => 'Adults need an average of 2,000 calories per day',
    ]);
    // slider
    RestaurantSlider::create([
        'restaurant_id' => $restaurant->id ,
        'photo' => 'slider2.png'
    ]);
    RestaurantSlider::create([
        'restaurant_id' => $restaurant->id ,
        'photo' => 'slider1.png'
    ]);
    // sensitivities
    defaultPostersAndSens($restaurant);

    return true;
}
function defaultPostersAndSens($restaurant){
    $sent = Sensitivity::all();
    foreach($sent as $temp):
        $data = $temp->only([
            'name_en' , 'name_ar' , 'details_en' , 'details_ar' , 'photo'
        ]);
        $image = copyImage($temp->image_path , 'sensitivities' , 'uploads/sensitivities');
        $data['photo'] = $image;
        $data['restaurant_id'] = $restaurant->id;
        RestaurantSensitivity::create($data);
    endforeach;

    $posters = Poster::all();
    foreach($posters as $poster):
        $image = copyImage($poster->image_path , 'poster' , 'uploads/posters');
        $restaurant->posters()->create([
            'name_en' => $poster->name_en , 
            'name_ar' => $poster->name_ar , 
            'poster' => $image , 
        ]);
    endforeach;
}





function foodics_url()
{
    return 'https://api.foodics.com/v5/';
}
function foodics_token($user_id = null)
{
    return \App\Models\Restaurant::find($user_id)->foodics_access_token;
}

function get_auth_code()
{
    $ch = curl_init('https://console-sandbox.foodics.com/authorize?client_id=9420d9c2-b5bb-4feb-8c26-72526c3e572b&state=state15535');

// Execute
    curl_exec($ch);

// Check if any error occurred
    if (!curl_errno($ch)) {
        $info = curl_getinfo($ch);
//        dd($info);
        echo 'Took ', $info['total_time'], ' seconds to send a request to ', $info['url'], "\n";
    }
// Close handle
    curl_close($ch);

}

function OAuth2($code = null , $state = null , $user_id = null)
{
    if ($code != null && $state != null && $user_id != null)
    {
        $user = \App\Models\Restaurant::find($user_id);
        if ($state = $user->foodics_status)
        {
            $jsonObj = array(
                "grant_type" => "authorization_code",
                "code" => $code,
                "client_id" => "94a7eeac-5881-4b19-ae6a-024debd9ac05",
                "client_secret" => "P6LeZgtB2JLwwzxvzDeu82AMPSZrqVvH0x7rvEX7",
                "redirect_uri" => "https://easymenu.site/redirect_back"
            );
            // Make Post Fields Array
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.foodics.com/oauth/token",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30000,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode($jsonObj),
                CURLOPT_HTTPHEADER => array(
                    "accept: /",
                    "content-type: application/json",
                ),
            ));
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            if ($err) {
                echo "cURL Error #:" . $err;
            }
            else {
                // store the token to restaurant
                $res = array_values(json_decode($response, true));
                $user->update([
                    'foodics_access_token' => $res[2]
                ]);
                // create_menu($user->id);
                flash('تمت عمليه الربط بنجاح ')->success();
                return redirect()->route('RestaurantIntegration');
//                header( "Location: https://easymenu.site/admin/foodics_integrations" );
            }

        }else{
            flash('حدث خطأ ما')->error();
            header( "Location: https://easymenu.site/restaurant/integrations" );
        }
    }else{
        flash('حدث خطأ ما')->error();
        header( "Location: https://easymenu.site/restaurant/integrations" );
    }

}


function calculate_order($restaurant_id, $order)
{
    $basURL = foodics_url()."orders_calculator";
    $headers = array(
        'Content-type: application/json',
        'Accept: application/json',
        'Authorization: Bearer ' . foodics_token($restaurant_id),
    );
    $order = json_encode($order);
    file_put_contents(storage_path('app/order.txt') , $order);
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $basURL,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $order,
        CURLOPT_HTTPHEADER => $headers,
    ));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    $res = json_decode($response, true);
    if ($err or !isset($res['total_price'])) {
        return $err;
    } else {
        file_put_contents(storage_path('app/test.txt') , $response);
        $res = json_decode($response, true);
        
        return $res['total_price'];
    }
}

/**
 * @check order discount
 * @checkProductDiscount
 */
function apply_discount($order, $discount)
{
    if ($discount->minimum_order_price != null) {
        if ($discount->minimum_order_price >= $order->order_price) {
            if ($discount->minimum_product_price != null) {
                if ($discount->minimum_product_price >= $order->product->price) {
                    if ($discount->is_percentage == 'true') {
                        $discount_value = ($order->order_price * $discount->amount) / 100;
                        if ($discount_value < $order->order_price) {
                            $order->update([
                                'discount_id' => $discount->id,
                                'discount_value' => $discount_value,
                            ]);
                        }
                    } else {
                        if ($discount->amount < $order->order_price) {
                            $order->update([
                                'discount_id' => $discount->id,
                                'discount_value' => $discount->amount,
                            ]);
                        }
                    }
                }
            } else {
                if ($discount->is_percentage == 'true') {
                    $discount_value = ($order->order_price * $discount->amount) / 100;
                    if ($discount_value < $order->order_price) {
                        $order->update([
                            'discount_id' => $discount->id,
                            'discount_value' => $discount_value,
                        ]);
                    }
                } else {
                    if ($discount->amount < $order->order_price) {
                        $order->update([
                            'discount_id' => $discount->id,
                            'discount_value' => $discount->amount,
                        ]);
                    }
                }
            }
        }
    } else {
        if ($discount->minimum_product_price != null) {
            if ($discount->minimum_product_price >= $order->product->price) {
                if ($discount->is_percentage == 'true') {
                    $discount_value = ($order->order_price * $discount->amount) / 100;
                    if ($discount_value < $order->order_price) {
                        $order->update([
                            'discount_id' => $discount->id,
                            'discount_value' => $discount_value,
                        ]);
                    }
                } else {
                    if ($discount->amount < $order->order_price) {
                        $order->update([
                            'discount_id' => $discount->id,
                            'discount_value' => $discount->amount,
                        ]);
                    }
                }
            }
        } else {
            if ($discount->is_percentage == 'true') {
                $discount_value = ($order->order_price * $discount->amount) / 100;
                if ($discount_value < $order->order_price) {
                    $order->update([
                        'discount_id' => $discount->id,
                        'discount_value' => $discount_value,
                    ]);
                }
            } else {
                if ($discount->amount < $order->order_price) {
                    $order->update([
                        'discount_id' => $discount->id,
                        'discount_value' => $discount->amount,
                    ]);
                }
            }
        }
    }
}

function apply_table_discount($order, $discount)
{
    if ($discount->minimum_order_price != null) {
        if ($discount->minimum_order_price >= $order->price) {
            if ($discount->minimum_product_price != null) {
                if ($discount->minimum_product_price >= $order->product->price) {
                    if ($discount->is_percentage == 'true') {
                        $discount_value = ($order->price * $discount->amount) / 100;
                        if ($discount_value < $order->price) {
                            $discount_value = $order->table_order->discount_value + $discount_value;
                            $order->table_order->update([
                                'discount_id' => $discount->id,
                                'discount_value' => $discount_value,
                            ]);
                        }
                    } else {
                        if ($discount->amount < $order->price) {
                            $discount_value = $order->table_order->discount_value + $discount->amount;
                            $order->table_order->update([
                                'discount_id' => $discount->id,
                                'discount_value' => $discount_value,
                            ]);
                        }
                    }
                }
            } else {
                if ($discount->is_percentage == 'true') {
                    $discount_value = ($order->price * $discount->amount) / 100;
                    if ($discount_value < $order->price) {
                        $discount_value = $order->table_order->discount_value + $discount_value;
                        $order->table_order->update([
                            'discount_id' => $discount->id,
                            'discount_value' => $discount_value,
                        ]);
                    }
                } else {
                    if ($discount->amount < $order->price) {
                        $discount_value = $order->table_order->discount_value + $discount->amount;
                        $order->table_order->update([
                            'discount_id' => $discount->id,
                            'discount_value' => $discount_value,
                        ]);
                    }
                }
            }
        }
    } else {
        if ($discount->minimum_product_price != null) {
            if ($discount->minimum_product_price >= $order->product->price) {
                if ($discount->is_percentage == 'true') {
                    $discount_value = ($order->price * $discount->amount) / 100;
                    if ($discount_value < $order->price) {
                        $discount_value = $order->table_order->discount_value + $discount_value;
                        $order->table_order->update([
                            'discount_id' => $discount->id,
                            'discount_value' => $discount_value,
                        ]);
                    }
                } else {
                    if ($discount->amount < $order->price) {
                        $discount_value = $order->table_order->discount_value + $discount->amount;
                        $order->table_order->update([
                            'discount_id' => $discount->id,
                            'discount_value' => $discount_value,
                        ]);
                    }
                }
            }
        } else {
            if ($discount->is_percentage == 'true') {
                $discount_value = ($order->price * $discount->amount) / 100;
                if ($discount_value < $order->price) {
                    $discount_value = $order->table_order->discount_value + $discount_value;
                    $order->table_order->update([
                        'discount_id' => $discount->id,
                        'discount_value' => $discount_value,
                    ]);
                }
            } else {
                if ($discount->amount < $order->price) {
                    $discount_value = $order->table_order->discount_value + $discount->amount;
                    $order->table_order->update([
                        'discount_id' => $discount->id,
                        'discount_value' => $discount_value,
                    ]);
                }
            }
        }
    }
}

function checkProductDiscount($order_id, $discount_id)
{
    $order = \App\Models\SilverOrder::find($order_id);
    $discount = \App\Models\FoodicsDiscount::find($discount_id);
    // 1 - check if the branch at discount
    if ($discount->associate_to_all_branches == 'true') {
        // check categories
        if ($discount->categories != null and count(json_decode($discount->categories)) > 0) {
            foreach (json_decode($discount->categories) as $category) {
                if ($category == $order->product->menu_category->foodics_id) {
                    // check product
                    if ($discount->products != null and count(json_decode($discount->products)) > 0) {
                        foreach (json_decode($discount->products) as $product) {
                            if ($product == $order->product->foodics_id) {
                                apply_discount($order, $discount);
                            }
                        }
                    } else {
                        apply_discount($order, $discount);
                    }
                }
            }
        } else {
            // all categories at discount
            // check product
            if ($discount->products != null and count(json_decode($discount->products)) > 0) {
                foreach (json_decode($discount->products) as $product) {
                    if ($product == $order->product->foodics_id) {
                        apply_discount($order, $discount);
                    }
                }
            } else {
                apply_discount($order, $discount);
            }
        }
    } else {
        // check the selected branch
        if ($discount->branches != null and $discount->branches->count() > 0) {
            foreach ($discount->branches as $branch) {
                if ($branch == $order->branch->foodics_id) {
                    // check categories
                    if ($discount->categories != null and count(json_decode($discount->categories)) > 0) {
                        foreach (json_decode($discount->categories) as $category) {
                            if ($category == $order->product->menu_category->foodics_id) {
                                // check product
                                if ($discount->products != null and count(json_decode($discount->products)) > 0) {
                                    foreach (json_decode($discount->products) as $product) {
                                        if ($product == $order->product->foodics_id) {
                                            apply_discount($order, $discount);
                                        }
                                    }
                                } else {
                                    apply_discount($order, $discount);
                                }
                            }
                        }
                    } else {
                        // all categories at discount
                        // check product
                        if ($discount->products != null and count(json_decode($discount->products)) > 0) {
                            foreach (json_decode($discount->products) as $product) {
                                if ($product == $order->product->foodics_id) {
                                    apply_discount($order, $discount);
                                }
                            }
                        } else {
                            apply_discount($order, $discount);
                        }
                    }
                }
            }
        }
    }
}

function checkTableProductDiscount($order_id, $discount_id)
{
    $order = \App\Models\TableOrderItem::find($order_id);
    $discount = \App\Models\FoodicsDiscount::find($discount_id);
    // 1 - check if the branch at discount
    if ($discount->associate_to_all_branches == 'true') {
        // check categories
        if ($discount->categories != null and count(json_decode($discount->categories)) > 0) {
            foreach (json_decode($discount->categories) as $category) {
                if ($category == $order->product->menu_category->foodics_id) {
                    // check product
                    if ($discount->products != null and count(json_decode($discount->products)) > 0) {
                        foreach (json_decode($discount->products) as $product) {
                            if ($product == $order->product->foodics_id) {
                                apply_table_discount($order, $discount);
                            }
                        }
                    } else {
                        apply_table_discount($order, $discount);
                    }
                }
            }
        } else {
            // all categories at discount
            // check product
            if ($discount->products != null and count(json_decode($discount->products)) > 0) {
                foreach (json_decode($discount->products) as $product) {
                    if ($product == $order->product->foodics_id) {
                        apply_table_discount($order, $discount);
                    }
                }
            } else {
                apply_table_discount($order, $discount);
            }
        }
    } else {
        // check the selected branch
        if ($discount->branches != null and $discount->branches->count() > 0) {
            foreach ($discount->branches as $branch) {
                if ($branch == $order->branch->foodics_id) {
                    // check categories
                    if ($discount->categories != null and count(json_decode($discount->categories)) > 0) {
                        foreach (json_decode($discount->categories) as $category) {
                            if ($category == $order->product->menu_category->foodics_id) {
                                // check product
                                if ($discount->products != null and count(json_decode($discount->products)) > 0) {
                                    foreach (json_decode($discount->products) as $product) {
                                        if ($product == $order->product->foodics_id) {
                                            apply_table_discount($order, $discount);
                                        }
                                    }
                                } else {
                                    apply_table_discount($order, $discount);
                                }
                            }
                        }
                    } else {
                        // all categories at discount
                        // check product
                        if ($discount->products != null and count(json_decode($discount->products)) > 0) {
                            foreach (json_decode($discount->products) as $product) {
                                if ($product == $order->product->foodics_id) {
                                    apply_table_discount($order, $discount);
                                }
                            }
                        } else {
                            apply_table_discount($order, $discount);
                        }
                    }
                }
            }
        }
    }
}

function create_foodics_order($restaurant_id, $branch_id, $products, $user, $order_type, $payment_method, $latitude = null, $longitude = null, $period = null, $day_id = null, $previous_type = null)
{
    $basURL = foodics_url() . "orders";
    $headers = array(
        'Content-type: application/json',
        'Accept: application/json',
        'Authorization: Bearer ' . foodics_token($restaurant_id),
    );
    /**
     *  1- get customer and address
     */
    $customers = array_values(json_decode(get_customer_and_address(ltrim($user->phone_number, '0'), $restaurant_id), true));
    $customer_address_id = null;
    $address = 'Address - ' . mt_rand(1000, 9999);
    $restaurant = Restaurant::find($restaurant_id);
//    dd($customers[0]);
    if ($customers[0] == []) {
        // create new customer
        $customer_id = create_customer($user->phone_number, $user->phone_number, $restaurant_id);
        $customers = array_values(json_decode(get_customer_and_address(ltrim($user->phone_number, '0'), $restaurant_id), true));
        if ($order_type == 'delivery' || $previous_type == 'delivery') {
            if ($customers[0][0]['addresses'] == []) {
                // create new address
                $customer_address_id = create_addresses($customer_id, null, $address, $latitude, $longitude, $restaurant_id);
            }
        }
    } else {
        $customer_id = $customers[0][0]['id'];
        if ($order_type == 'delivery' || $previous_type == 'delivery') {
            if ($customers[0][0]['addresses'] == []) {
                // create new address
                $customer_address_id = create_addresses($customer_id, null, $address, $latitude, $longitude, $restaurant_id);
            } else {
                foreach ($customers[0][0]['addresses'] as $address_check) {
                    if ($address_check['customer_id'] == $customer_id && $address_check['latitude'] == $latitude && $address_check['longitude'] == $longitude && $address_check['deleted_at'] == null) {
                        $customer_address_id = $address_check['id'];
                    }
                }
                if ($customer_address_id == null) {
                    $customer_address_id = create_addresses($customer_id, null, $address, $latitude, $longitude, $restaurant_id);
                }
            }
        }
    }
    /**
     *  prepare the products and modifiers and options
     */
    $product_object = array();
    $discount_id = null;
    $maximum_amount = null;
    $taxable = null;
    $discount_value = 0;
    if ($products->count() > 0) {
        foreach ($products as $order) {
            $order = \App\Models\SilverOrder::find($order->id);
            if ($order->discount) {
                $discount_id = $order->discount->foodics_id;
                $maximum_amount = $order->discount->maximum_amount == null ? null : $order->discount->maximum_amount;
                $discount_value += $order->discount_value;
                $taxable = $order->discount->is_taxable == 'true' ? 2 : 1;
                $taxable_value = $order->discount->amount;
                if (($maximum_amount != null and $maximum_amount >= $discount_value and $discount_id != null) or ($maximum_amount == null and $discount_id != null)) {
                } else {
                    $order->update([
                        'discount_id' => null,
                        'discount_value' => 0,
                    ]);
                    $discount_id = null;
                    $discount_value = 0;
                }
            }
            $options = [];
            if ($order->silver_order_options->count() > 0) {
                foreach ($order->silver_order_options as $option) {
                    $option_tax = [];
                    if ($restaurant->tax == 'true') {
                        $option_tax = array(array(
                            "id" => $restaurant->tax_foodics_id,
                            "amount" => $option->quantity,
                            "rate" => $restaurant->tax_value,
                        ));
                    }
                    array_push($options, array(
                        "modifier_option_id" => $option->option->foodics_id,
                        "quantity" => $option->quantity,
                        "unit_price" => $option->option->price,
                        "taxes" => $option_tax
                    ));
                }
            }
            $product_tax = [];
            if ($restaurant->tax == 'true' and $restaurant->tax_foodics_id != null) {
                $product_tax = array(
                    array(
                        "id" => $restaurant->tax_foodics_id,
                        "amount" => $order->product_count,
                        "rate" => $restaurant->tax_value,
                    )
                );
            }
            array_push($product_object, array(
                "options" => $options,
                "product_id" => $order->product->foodics_id,
                "quantity" => $order->product_count,
                "unit_price" => $order->product->price,
                "taxes" => $product_tax,
            ));
        }
    }
    /**
     * get order charges
     */
    $delivery_price = $restaurant->delivery_price;
    $charge = [];
    if (($order_type == 'delivery' && $restaurant->delivery_price > 0 && $restaurant->foodics_charge_id != null) || ($previous_type == 'delivery' && $restaurant->delivery_price > 0 && $restaurant->foodics_charge_id != null)) {
        $charge = array(array(
            'amount' => $delivery_price,
            'charge_id' => $restaurant->foodics_charge_id,
            'taxes' => []
        ));
    }
    /**
     *  prepare the payments methods
     */

    $payment_methods = array_values(json_decode(payment_methods($restaurant_id), true));
    $payments = [];

    if ($taxable and $taxable == 1 and ($maximum_amount != null and $maximum_amount >= $discount_value and $discount_id != null) or ($maximum_amount == null and $discount_id != null)) {
        $order_calculate = array(
            'branch_id' => $branch_id,
            'type' => 3,
            "discount_type" => 1,
            "discount_percent" => intval($taxable_value),
            'customer_id' => $customer_id,
            'meta' => array(
                'external_number' => mt_rand(1000, 9999),
            ),
            'products' => $product_object,
            'charges' => $charge,
        );
    } else {
        $order_calculate = array(
            'branch_id' => $branch_id,
            'type' => 3,
            'customer_id' => $customer_id,
            'meta' => array(
                'external_number' => mt_rand(1000, 9999),
            ),
            'products' => $product_object,
            'charges' => $charge,
        );
    }
    $totalPrice = calculate_order($restaurant_id, $order_calculate);
    if ($payment_methods[0] != []) {
        foreach ($payment_methods[0] as $value) {
            if ($value['deleted_at'] == null && $value['is_active'] == true) {
                if ($value['name'] == $payment_method) {
                    $payment_method_id = $value['id'];
                    if ($taxable and $taxable == 1) {
                        $payments = array(array(
                            'amount' => $totalPrice,
                            'tendered' => $totalPrice,
                            'payment_method_id' => $payment_method_id,
                            'tips' => 0,
                            'meta' => array(
                                'online_payment' => '3rd Party'
                            )
                        ));
                    } else {
                        $payments = array(array(
                            'amount' => $discount_id == null ? $totalPrice : $totalPrice - $discount_value,
                            'tendered' => $totalPrice,
                            'payment_method_id' => $payment_method_id,
                            'tips' => 0,
                            'meta' => array(
                                'online_payment' => '3rd Party'
                            )
                        ));
                    }
                }
            }
        }
    }

//    dd($discount_id);
    if ($order_type == 'delivery') {
        if (($maximum_amount != null and $maximum_amount >= $discount_value and $discount_id != null) or ($maximum_amount == null and $discount_id != null)) {
            $order = array(
                'branch_id' => $branch_id,
                'type' => 3,
                'subtotal_price' => $totalPrice,
                "discount_amount" => $discount_value,
                "discount_type" => $taxable,
                "discount_id" => $discount_id,
                'total_price' => $totalPrice - $discount_value,
                'delivery_status' => 1,
                'customer_address_id' => $customer_address_id,
                'customer_id' => $customer_id,
                'meta' => array(
                    'external_number' => mt_rand(1000, 9999),
                ),
                'products' => $product_object,
                'charges' => $charge,
                'payments' => $payments,
            );
        } else {
            $order = array(
                'branch_id' => $branch_id,
                'type' => 3,
                'subtotal_price' => $totalPrice,
                'total_price' => $totalPrice,
                'delivery_status' => 1,
                'customer_address_id' => $customer_address_id,
                'customer_id' => $customer_id,
                'meta' => array(
                    'external_number' => mt_rand(1000, 9999),
                ),
                'products' => $product_object,
                'charges' => $charge,
                'payments' => $payments,
            );
        }
    } elseif ($order_type == 'previous') {
        $day = \App\Models\Day::find($day_id)->name_en;
        $time = $period;
        $date = \Carbon\Carbon::now()->addDays(get_Date($day));
        $date->hour = substr($time, 0, 2) - 3;
        $date->minute = 0;
        $date->second = 0;
        if ($previous_type == 'delivery') {
            if (($maximum_amount != null and $maximum_amount >= $discount_value and $discount_id != null) or ($maximum_amount == null and $discount_id != null)) {
                $order = array(
                    'branch_id' => $branch_id,
                    'type' => 3,
                    'subtotal_price' => $totalPrice,
                    "discount_amount" => $discount_value,
                    "discount_type" => $taxable,
                    "discount_id" => $discount_id,
                    'total_price' => $totalPrice - $discount_value,
                    'delivery_status' => 1,
                    'customer_address_id' => $customer_address_id,
                    'customer_id' => $customer_id,
                    'due_at' => $date->format('Y-m-d H:i:s'),
                    'meta' => array(
                        'external_number' => mt_rand(1000, 9999),
                    ),
                    'products' => $product_object,
                    'charges' => $charge,
                    'payments' => $payments,
                );
            } else {
                $order = array(
                    'branch_id' => $branch_id,
                    'type' => 3,
                    'subtotal_price' => $totalPrice,
                    'total_price' => $totalPrice,
                    'delivery_status' => 1,
                    'customer_address_id' => $customer_address_id,
                    'customer_id' => $customer_id,
                    'due_at' => $date->format('Y-m-d H:i:s'),
                    'meta' => array(
                        'external_number' => mt_rand(1000, 9999),
                    ),
                    'products' => $product_object,
                    'charges' => $charge,
                    'payments' => $payments,
                );
            }
        } else {
            if (($maximum_amount != null and $maximum_amount >= $discount_value and $discount_id != null) or ($maximum_amount == null and $discount_id != null)) {
                $order = array(
                    'branch_id' => $branch_id,
                    'type' => 2,
                    "discount_amount" => $discount_value,
                    "discount_type" => $taxable,
                    "discount_id" => $discount_id,
                    'customer_id' => $customer_id,
                    'due_at' => $date->format('Y-m-d H:i:s'),
                    'meta' => array(
                        'external_number' => mt_rand(1000, 9999),
                    ),
                    'products' => $product_object,
                    'payments' => $payments,
                );
            } else {
                $order = array(
                    'branch_id' => $branch_id,
                    'type' => 2,
                    'customer_id' => $customer_id,
                    'due_at' => $date->format('Y-m-d H:i:s'),
                    'meta' => array(
                        'external_number' => mt_rand(1000, 9999),
                    ),
                    'products' => $product_object,
                    'payments' => $payments,
                );
            }
        }
    } else {
        if (($maximum_amount != null and $maximum_amount >= $discount_value and $discount_id != null) or ($maximum_amount == null and $discount_id != null)) {
            $order = array(
                'branch_id' => $branch_id,
                'type' => 2,
                "discount_amount" => $discount_value,
                "discount_type" => $taxable,
                "discount_id" => $discount_id,
                'customer_id' => $customer_id,
                'meta' => array(
                    'external_number' => mt_rand(1000, 9999),
                ),
                'products' => $product_object,
                'payments' => $payments,
            );
        } else {
            $order = array(
                'branch_id' => $branch_id,
                'type' => 2,
                'customer_id' => $customer_id,
                'meta' => array(
                    'external_number' => mt_rand(1000, 9999),
                ),
                'products' => $product_object,
                'payments' => $payments,
            );
        }
    }

    $order = json_encode($order);
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $basURL,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $order,
        CURLOPT_HTTPHEADER => $headers,
    ));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        return $err;
    } else {
        $res = array_values(json_decode($response, true));
//        $user->silver_orders()->delete();
        return $response;
    }
}

function create_foodics_table_order($restaurant_id, $branch_id, $products, $payment_method, $table_id )
{
    $basURL = foodics_url() . "orders";
    $headers = array(
        'Content-type: application/json',
        'Accept: application/json',
        'Authorization: Bearer ' . foodics_token($restaurant_id),
    );
    $restaurant = Restaurant::find($restaurant_id);
    /**
     *  prepare the products and modifiers and options
     */
    $product_object = array();
    $discount_id = null;
    $maximum_amount = null;
    $taxable = null;
    $discount_value = 0;
    if ($products->count() > 0) {
        foreach ($products as $product) {
            $order = TableOrder::find($product->table_order_id);
            $tableOrder = $order;
            if ($order->foodics_discount) {
                $discount_id = $order->foodics_discount->foodics_id;
                $maximum_amount = $order->foodics_discount->maximum_amount == null ? null : $order->foodics_discount->maximum_amount;
                $discount_value = $order->discount_value;
                $taxable = $order->foodics_discount->is_taxable == 'true' ? 2 : 1;
                $taxable_value = $order->foodics_discount->amount;
                if (($maximum_amount != null and $maximum_amount >= $discount_value and $discount_id != null) or ($maximum_amount == null and $discount_id != null)) {
                } else {
                    $order->update([
                        'discount_id' => null,
                        'discount_value' => 0,
                    ]);
                    $discount_id = null;
                    $discount_value = 0;
                }
            }
            $options = [];
            if ($product->order_item_options->count() > 0) {
                foreach ($product->order_item_options as $option) {
                    $option_tax = [];
                    if ($restaurant->tax == 'true' and $restaurant->tax_foodics_id != null) {
                        $option_tax = array(array(
                            "id" => $restaurant->tax_foodics_id,
                            "amount" => $option->option_count,
                            "rate" => $restaurant->tax_value,
                        ));
                    }
                    array_push($options, array(
                        "modifier_option_id" => $option->option->foodics_id,
                        "quantity" => $option->option_count,
                        "unit_price" => $option->option->price,
                        "taxes" => $option_tax
                    ));
                }
            }
            $product_tax = [];
            if ($restaurant->tax == 'true' and $restaurant->tax_foodics_id != null) {
                $product_tax = array(
                    array(
                        "id" => $restaurant->tax_foodics_id,
                        "amount" => $product->product_count,
                        "rate" => $restaurant->tax_value,
                    )
                );
            }
            array_push($product_object, array(
                "options" => $options,
                "product_id" => $product->product->foodics_id,
                "quantity" => $product->product_count,
                "unit_price" => $product->product->price,
                "taxes" => $product_tax,
            ));
        }
    }

    /**
     *  prepare the payments methods
     */

    $payment_methods = array_values(json_decode(payment_methods($restaurant_id), true));
    $payments = [];
    if ($taxable and $taxable == 1 and ($maximum_amount != null and $maximum_amount >= $discount_value and $discount_id != null) or ($maximum_amount == null and $discount_id != null)) {
        $order_calculate = array(
            'branch_id' => $branch_id,
            'type' => 1,
            "discount_type" => 1,
            "discount_percent" => intval($taxable_value),
            'meta' => array(
                'external_number' => mt_rand(1000, 9999),
            ),
            'products' => $product_object,
        );
    } else {
        $order_calculate = array(
            'branch_id' => $branch_id,
            'type' => 1,
            'meta' => array(
                'external_number' => mt_rand(1000, 9999),
            ),
            'products' => $product_object,
        );
    }
    $totalPrice = calculate_order($restaurant_id, $order_calculate);
//    $discount_amount = ($totalPrice * 15) / 100;
    if ((is_array($payment_methods[0]) or is_object($payment_methods[0])) and $payment_methods[0] != []) {
        foreach ($payment_methods[0] as $value) {
            if ($value['deleted_at'] == null && $value['is_active'] == true) {
                if ($value['name'] == $payment_method) {
                    $payment_method_id = $value['id'];
                    if ($taxable and $taxable == 1) {
                        $payments = array(array(
//                        'amount' => $totalPrice - $discount_amount, // with discount
                            'amount' => $totalPrice,
                            'tendered' => $totalPrice,
                            'payment_method_id' => $payment_method_id,
                            'tips' => 0,
                            'meta' => array(
                                'online_payment' => '3rd Party'
                            )
                        ));
                    } else {
                        $payments = array(array(
                            'amount' => $discount_id == null ? $totalPrice : $totalPrice - $discount_value,
                            'tendered' => $totalPrice,
                            'payment_method_id' => $payment_method_id,
                            'tips' => 0,
                            'meta' => array(
                                'online_payment' => '3rd Party'
                            )
                        ));
                    }
                }
            }
        }
    }
    $table = \App\Models\Table::find($table_id);
    if (($maximum_amount != null and $maximum_amount >= $discount_value and $discount_id != null) or ($maximum_amount == null and $discount_id != null)) {
        $order = array(
            'branch_id' => $branch_id,
            'type' => 1,
            'subtotal_price' => $totalPrice,
            "discount_amount" => $discount_value,
            "discount_type" => $taxable,
            "discount_id" => $discount_id,
            'total_price' => $totalPrice - $discount_value,
            'table_id' => $table->foodics_id,
            'meta' => array(
                'external_number' => $order->id,
            ),
            'products' => $product_object,
            'payments' => $payments,
        );
    } else {
        $order = array(
            'branch_id' => $branch_id,
            'type' => 1,
            'table_id' => $table->foodics_id,
            'meta' => array(
                 'external_number' => $order->id,
            ),
            'products' => $product_object,
            'payments' => $payments,
        );
    }
    // dd($order);
    $order = json_encode($order);
    $curl = curl_init();
    
    curl_setopt_array($curl, array(
        CURLOPT_URL => $basURL,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $order,
        CURLOPT_HTTPHEADER => $headers,
    ));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        file_put_contents(storage_path('app/create_foodics_order.txt') , $response , FILE_APPEND);
        return $err;
    } else {
        $data = json_decode($response, true);
        $res = array_values(json_decode($response, true));
        // dd($res);
        if(isset($data['data']['id'])) {
            $tableOrder->update([
                'foodics_order_id' => $data['data']['id'] , 
            ]);
        }
        // file_put_contents(storage_path('app/create_foodics_order.txt') , $response , FILE_APPEND);
        return $response;
    }
}

function getFoodicsOrder($id , $token){
    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $token , 
        'Content-Type' => 'application/json' , 
    ])->get(foodics_url().'orders/' . $id);
    return $response;
}

/**
 *  get @foodics @discounts
 *  GET /discounts?include=products,combos,product_tags,categories,branches,customer_tags
 */


/**
 * @pay @foodics Online order
 * @pay_online_order
*/
function pay_online_order()
{

}

function create_customer($name, $phone, $restaurant_id)
{
    $basURL = foodics_url() . "customers";
    $headers = array(
        'Content-type: application/json',
        'Accept: application/json',
        'Authorization: Bearer ' . foodics_token($restaurant_id),
    );
    $customer = array(
        'name' => $name,
        'dial_code' => mb_substr($phone,1, 1) === "5" ? '966' : '20',
        'phone' => substr($phone, 1),
    );
    $customer = json_encode($customer);
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $basURL,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $customer,
        CURLOPT_HTTPHEADER => $headers,
    ));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        return $err;
    } else {
        $res = json_decode($response, true);
        return $res['data']['id'];
    }
}

function create_addresses($customer_id, $delivery_zone_id, $address, $lat, $long, $user_id)
{
    $basURL = foodics_url() . "addresses";
    $headers = array(
        'Content-type: application/json',
        'Accept: application/json',
        'Authorization: Bearer ' . foodics_token($user_id),
    );

    $address = array(
        'name' => $address,
        'description' => 'Current Location',
        'latitude' => $lat,
        'longitude' => $long,
        'customer_id' => $customer_id,
        'delivery_zone_id' => $delivery_zone_id,
    );
    $address = json_encode($address);
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $basURL,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $address,
        CURLOPT_HTTPHEADER => $headers,
    ));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        return $err;
    } else {
        $response = array_values(json_decode($response, true));
        return $response[0]['id'];
    }

}

function get_customers($user_id)
{
    $basURL = foodics_url() . "customers?include=addresses,tags";
    $headers = array(
        'Content-type: application/json',
        'Accept: application/json',
        'Authorization: Bearer ' . foodics_token($user_id),
    );
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $basURL,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => $headers,
    ));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        return $err;
    } else {
        dd($response);
//        return $response;
    }
}

function get_customer_and_address($phone = 966580491109, $user_id = 276)
{
    $basURL = foodics_url() . "customers?filter[phone]=$phone&include=addresses";
    $headers = array(
        'Content-type: application/json',
        'Accept: application/json',
        'Authorization: Bearer ' . foodics_token($user_id),
    );
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $basURL,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => $headers,
    ));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        return $err;
    } else {
        return $response;
    }
}

function delivery_zones($user_id)
{
    $basURL = foodics_url() . "delivery_zones?include=branches,tags";
    $headers = array(
        'Content-type: application/json',
        'Accept: application/json',
        'Authorization: Bearer ' . foodics_token($user_id),
    );
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $basURL,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => $headers,
    ));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        return $err;
    } else {
//        dd($response);
        return $response;
    }
}

/**
 *  End @foodics @orders
 */

// get branches from foodics
function get_branches_with_taxes($user_id = null)
{
    $basURL = foodics_url()."branches?include=users,tags,tax_group,delivery_zones,discounts,timed_events,promotions";
    $headers = array(
        'Content-type: application/json',
        'Accept: application/json',
        'Authorization: Bearer ' . foodics_token($user_id),
    );
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $basURL,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => $headers,
    ));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        return $err;
    } else {
//        dd($response);
        return $response;
    }
}
// get the foodics settings
function foodics_settings($user_id = null)
{
    $basURL = foodics_url()."settings";
    $headers = array(
        'Content-type: application/json',
        'Accept: application/json',
        'Authorization: Bearer ' . foodics_token($user_id),
    );
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $basURL,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => $headers,
    ));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        return $err;
    } else {
        return $response;
    }
}
// get foodics taxes
function tax_groups($user_id = null)
{
    $basURL = foodics_url()."tax_groups?include=taxes";
    $headers = array(
        'Content-type: application/json',
        'Accept: application/json',
        'Authorization: Bearer ' . foodics_token($user_id),
    );
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $basURL,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => $headers,
    ));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        return $err;
    } else {
//        dd($response);
        return $response;
    }
}

// create branches that returned form foodics
function create_branches($restaurant_id, $data, $foodics_branch)
{
    $restaurant = \App\Models\Restaurant::find($restaurant_id);
    foreach ($data[0] as $key => $value) {
        if ($value['deleted_at'] == null && $value['receives_online_orders'] == true) {
            $branch = \App\Models\RestaurantFoodicsBranch::where('restaurant_id', $restaurant_id)
                ->where('branch_id', $foodics_branch)
                ->where('foodics_id', $value['id'])
                ->first();
            if ($branch == null) {
                // create new Branch
                \App\Models\RestaurantFoodicsBranch::create([
                    'restaurant_id' => $restaurant->id,
                    'branch_id' => $foodics_branch,
                    'name_ar' => $value['name_localized'] == null ? $value['name'] : $value['name_localized'],
                    'name_en' => $value['name'] == null ? $value['name_localized'] : $value['name'],
                    'phone_number' => '0' . $value['phone'],
                    'longitude' => $value['longitude'],
                    'latitude' => $value['latitude'],
                    'foodics_id' => $value['id'],
                    'active' => 'true',
                ]);
                \App\Models\Branch::find($foodics_branch)->update([
                    'longitude' => $value['longitude'],
                    'latitude' => $value['latitude'],
                ]);

            }
            $tax_groups = array_values(json_decode(tax_groups($restaurant_id), true));
            if (count($tax_groups) > 0) {
                foreach ($tax_groups[0] as $tk => $tv) {
                    // update user tax
                    $branch = \App\Models\Branch::find($foodics_branch);
                    if ($tv['deleted_at'] == null) {
                        if ($tv['taxes'] != []) {
                            if ($tv['taxes'][0]['rate'] != null) {
                                $settings = array_values(json_decode(foodics_settings($restaurant_id), true));

                                if (count($settings) > 0) {
                                    if ($settings[0]['tax_inclusive_pricing'] == false) {
                                        $restaurant->update([
                                            'tax_foodics_id' => $tv['taxes'][0]['id'],
                                        ]);
                                        $branch->update([
                                            'tax' => 'true',
                                            'tax_value' => $tv['taxes'][0]['rate'],
                                        ]);
                                    } else {
                                        $branch->update([
                                            'tax' => 'false',
                                            'tax_value' => 0,
                                        ]);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}

// get sections from foodics that have tables
function get_sections($user_id =null)
{
    $basURL = foodics_url()."sections?include=tables,branch";
    $headers = array(
        'Content-type: application/json',
        'Accept: application/json',
        'Authorization: Bearer ' . foodics_token($user_id),
    );
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $basURL,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => $headers,
    ));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        return $err;
    } else {
        return $response;
    }
}
// get categories from foodics
function get_categories($user_id=null , $page=1)
{
    $basURL = foodics_url().'categories?page='.$page;
    $headers = array(
        'Content-type: application/json',
        'Accept: application/json',
        'Authorization: Bearer ' . foodics_token($user_id),
    );
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $basURL,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => $headers,
    ));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        return $err;
    } else {
        return $response;
    }
}
// get charges from foodics
function get_charges($user_id = null)
{
    $basURL = foodics_url()."charges?include=tax_group";
    $headers = array(
        'Content-type: application/json',
        'Accept: application/json',
        'Authorization: Bearer ' . foodics_token($user_id),
    );
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $basURL,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => $headers,
    ));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        return $err;
    } else {
//        dd($response);
        return $response;
    }
}
// create restaurant payment method for foodics payments
function payment_methods($user_id =null)
{
    $basURL = foodics_url()."payment_methods";
    $headers = array(
        'Content-type: application/json',
        'Accept: application/json',
        'Authorization: Bearer ' . foodics_token($user_id),
    );
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $basURL,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => $headers,
    ));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        return $err;
    } else {
        return $response;
    }
}
// create tables
function create_tables($restaurant_id , $data ,$foodics_branch){
    $restaurant = \App\Models\Restaurant::find($restaurant_id);
    foreach ($data[0] as $sKey => $sValue) {
        if (count($sValue['tables']) > 0) {
            if($sValue['deleted_at'] == null)
            {
                foreach ($sValue['tables'] as $tKey => $tValue) {
                    if($tValue['deleted_at'] == null )
                    {
                        $branch = \App\Models\RestaurantFoodicsBranch::where('foodics_id', $sValue['branch']['id'])
                            ->where('branch_id' , $foodics_branch)
                            ->first();
                        // check table
                        if($branch)
                        {
                            $check_table = \App\Models\Table::where('foodics_id', $tValue['id'])
                                ->where('branch_id' , $foodics_branch)
                                ->where('foodics_branch_id' , $branch->id)
                                ->first();
                            if ($check_table == null) {
                                // create new table
                                \App\Models\Table::create([
                                    'restaurant_id'      => $restaurant->id,
                                    'branch_id'          => $foodics_branch,
                                    'foodics_branch_id'  => $branch->id,
                                    'name_ar'       => $tValue['name'],
                                    'name_en'       => $tValue['name'],
                                    'name_barcode'  => $tValue['name'],
                                    'foodics_id'    => $tValue['id'],
                                ]);
                            }
                        }
                    }

                }
            }

        }
    }
}
function create_payment_method($user_id , $name)
{
    $basURL = foodics_url()."payment_methods";
    $headers = array(
        'Content-type: application/json',
        'Accept: application/json',
        'Authorization: Bearer ' . foodics_token($user_id),
    );
    $payment_methods = array_values(json_decode(payment_methods($user_id), true));
    if(count($payment_methods) > 0)
    {
        if (is_array($payment_methods[0]) || is_object($payment_methods[0]))
        {
            foreach ($payment_methods[0] as $key => $value)
            {
                if ($value['name'] == $name && $value['deleted_at'] == null && $value['is_active'] == true)
                {
                    return null;
                }
            }
        }
    }
    $method = array(
        'name' => $name,
        'code' => 'easymenu',
        'name_localized' => $name,
        'type' => 7,
        'auto_open_drawer' => true,
        'is_active' => true
    );
    $method = json_encode($method);
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $basURL,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $method,
        CURLOPT_HTTPHEADER => $headers,
    ));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        return $err;
    } else {
        return $response;
    }

}
// create foodics categories
function create_categories($restaurant_id, $categories, $foodics_branch)
{
    if (count($categories[0]) > 0) {
        if (is_array($categories[0]) || is_object($categories[0])) {
            foreach ($categories[0] as $key => $value) {
                if ($value['deleted_at'] == null) {
                    // check if category  are found or not
                    $check_cat = \App\Models\MenuCategory::where('foodics_id', $value['id'])
                        ->where('branch_id', $foodics_branch)
                        ->first();
                    if ($check_cat == null) {
                        // create new category
                        $cat = \App\Models\MenuCategory::create([
                            'restaurant_id' => $restaurant_id,
                            'branch_id' => $foodics_branch,
                            'name_ar' => $value['name_localized'] == null ? $value['name'] : $value['name_localized'],
                            'name_en' => $value['name'] == null ? $value['name_localized'] : $value['name'],
                            'photo' => null,
                            'foodics_image' => $value['image'],
                            'foodics_id' => $value['id'],
                            'active' => 'true',
                            'arrange' => null,
                            'start_at' => null,
                            'end_at' => null,
                            'time' => 'false',
                        ]);
                    }else{
                        $check_cat->update([
                            'name_ar' => $value['name_localized'] == null ? $value['name'] : $value['name_localized'],
                            'name_en' => $value['name'] == null ? $value['name_localized'] : $value['name'],
                            'foodics_image' => $value['image'],
                        ]);
                    }
                }
            }
        }
    }
    if (isset($categories[1])) {
        if ($categories[1]['next'] != null) {
            $categories = array_values(json_decode(get_categories($restaurant_id, substr($categories[1]['next'], -1)), true));
            // dd($products_modifiers);
            create_categories($restaurant_id, $categories, $foodics_branch);
        }
    }
}

// create foodics charges
function create_charges($restaurant_id , $data)
{
    $restaurant = \App\Models\Restaurant::find($restaurant_id);
    $restaurant->update([
        'delivery_price' => 0
    ]);
    if ($data[0] == []) {
        $restaurant->update([
            'delivery_price' => 0
        ]);
    }
    else {
        foreach ($data[0] as $value) {
            if ($value['deleted_at'] == null && $value['is_auto_applied'] == true) {
                $restaurant->update([
                    'delivery_price' => $value['value'],
                    'foodics_charge_id' => $value['id']
                ]);
            }
        }
    }
}
// create menu from foodics
// get foodics products and modifiers
function get_products_with_modifiers($user_id , $page=1)
{
    $basURL = foodics_url().'products?page='.$page.'&&include=category,modifiers.options,tax_group,branches,modifiers';
    // /products?include=modifiers.options
//    dd(($basURL));
    $headers = array(
        'Content-type: application/json',
        'Accept: application/json',
        'Authorization: Bearer ' . foodics_token($user_id),
    );
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $basURL,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => $headers,
    ));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        return $err;
    } else {
        return $response;
    }
}
// get foodics modifiers and options
function get_modifiers_options($user_id)
{
    $basURL = foodics_url()."modifiers?include=options,options.tax_group,options.branches";
    $headers = array(
        'Content-type: application/json',
        'Accept: application/json',
        'Authorization: Bearer ' . foodics_token($user_id),
    );
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $basURL,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => $headers,
    ));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        return $err;
    } else {
        return $response;
    }
}

function productAndModifierCreation($value, $restaurant_id, $foodics_branch)
{
    if ($value['deleted_at'] == null && $value['is_active'] == true && $value['is_stock_product'] == false) {
        //  check if product found or not
        $check_product = \App\Models\Product::where('foodics_id', $value['id'])
            ->where('branch_id', $foodics_branch)
            ->first();
        if ($check_product == null) {
            // create new  product
            $category = \App\Models\MenuCategory::where('foodics_id', $value['category']['id'])
                ->where('branch_id', $foodics_branch)
                ->first();
            if ($category) {
                // create new Product
                $product = \App\Models\Product::create([
                    'restaurant_id' => $restaurant_id,
                    'branch_id' => $foodics_branch,
                    'menu_category_id' => $category->id,
                    'name_ar' => $value['name_localized'] == null ? $value['name'] : $value['name_localized'],
                    'name_en' => $value['name'] == null ? $value['name_localized'] : $value['name'],
                    'description_ar' => $value['description_localized'],
                    'description_en' => $value['description'],
                    'price' => $value['price'],
                    'calories' => $value['calories'],
                    'active' => 'true',
                    'time' => 'false',
                    'foodics_image' => $value['image'],
                    'foodics_id' => $value['id'],
                ]);
                // create product modifiers
                if ($value['modifiers'] != []) {
                    foreach ($value['modifiers'] as $k => $v) {
                        if ($v['is_ready'] == true) {
                            // check if modifier exists before
                            $check_modifier = \App\Models\Modifier::where('foodics_id', $v['id'])->first();
                            if ($check_modifier == null) {
                                $modifier = \App\Models\Modifier::create([
                                    'name_ar' => $v['name_localized'] == null ? $v['name'] : $v['name_localized'],
                                    'name_en' => $v['name'] == null ? $v['name_localized'] : $v['name'],
                                    'restaurant_id' => $restaurant_id,
                                    'is_ready' => 'true',
                                    'foodics_id' => $v['id']
                                ]);
                            } else {
                                $modifier = $check_modifier;
                            }
                            if ($v['pivot'] != null) {
                                // create new product modifier
                                \App\Models\ProductModifier::create([
                                    'product_id' => $product->id,
                                    'modifier_id' => $modifier->id
                                ]);
                            }
                            if (count($v['options']) > 0) {
                                foreach ($v['options'] as $op_key => $op_value) {
                                    // check if this option are found in our app or not
                                    if ($op_value['is_active'] == true) {
                                        $check_option = \App\Models\Option::where('foodics_id', $op_value['id'])->first();
                                        if ($check_option == null) {
                                            $option = \App\Models\Option::create([
                                                'name_ar' => $op_value['name_localized'] == null ? $op_value['name'] : $op_value['name_localized'],
                                                'name_en' => $op_value['name'] == null ? $op_value['name_localized'] : $op_value['name'],
                                                'modifier_id' => $modifier->id,
                                                'restaurant_id' => $restaurant_id,
                                                'is_active' => 'true',
                                                'price' => $op_value['price'],
                                                'calories' => $op_value['calories'],
                                                'foodics_id' => $op_value['id'],
                                            ]);
                                        } else {
                                            $option = $check_option;
                                        }
                                        \App\Models\ProductOption::create([
                                            'option_id' => $option->id,
                                            'product_id' => $product->id,
                                            'modifier_id' => $modifier->id,
                                            'max' => $v['pivot']['maximum_options'],
                                            'min' => $v['pivot']['minimum_options'],
                                        ]);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }else{
            $check_product->update([
                'name_ar' => $value['name_localized'] == null ? $value['name'] : $value['name_localized'],
                'name_en' => $value['name'] == null ? $value['name_localized'] : $value['name'],
                'price' => $value['price'],
                'calories' => $value['calories'],
                'foodics_image' => $value['image'],
            ]);
            // create product modifiers
            if ($value['modifiers'] != []) {
                foreach ($value['modifiers'] as $k => $v) {
                    if ($v['is_ready'] == true) {
                        // check if modifier exists before
                        $check_modifier = \App\Models\Modifier::where('foodics_id', $v['id'])->first();
                        if ($check_modifier == null) {
                            $modifier = \App\Models\Modifier::create([
                                'name_ar' => $v['name_localized'] == null ? $v['name'] : $v['name_localized'],
                                'name_en' => $v['name'] == null ? $v['name_localized'] : $v['name'],
                                'restaurant_id' => $restaurant_id,
                                'is_ready' => 'true',
                                'foodics_id' => $v['id']
                            ]);
                        } else {
                            $modifier = $check_modifier;
                        }
                        if ($v['pivot'] != null) {
                            // create new product modifier
                            $check_product_modifier = \App\Models\ProductModifier::whereProductId($check_product->id)
                                ->where('modifier_id' , $modifier->id)
                                ->first();
                            if ($check_product_modifier == null)
                            {
                                \App\Models\ProductModifier::create([
                                    'product_id'  => $check_product->id,
                                    'modifier_id' => $modifier->id
                                ]);
                            }
                        }
                        if (count($v['options']) > 0) {
                            foreach ($v['options'] as $op_key => $op_value) {
                                // check if this option are found in our app or not
                                if ($op_value['is_active'] == true) {
                                    $check_option = \App\Models\Option::where('foodics_id', $op_value['id'])->first();
                                    if ($check_option == null) {
                                        $option = \App\Models\Option::create([
                                            'name_ar' => $op_value['name_localized'] == null ? $op_value['name'] : $op_value['name_localized'],
                                            'name_en' => $op_value['name'] == null ? $op_value['name_localized'] : $op_value['name'],
                                            'modifier_id' => $modifier->id,
                                            'restaurant_id' => $restaurant_id,
                                            'is_active' => 'true',
                                            'price' => $op_value['price'],
                                            'calories' => $op_value['calories'],
                                            'foodics_id' => $op_value['id'],
                                        ]);
                                    } else {
                                        $option = $check_option;
                                    }
                                    $check_product_option = \App\Models\ProductOption::whereProductId($check_product->id)
                                        ->where('modifier_id' , $modifier->id)
                                        ->where('option_id' , $option->id)
                                        ->first();
                                    if ($check_product_option == null)
                                    {
                                        \App\Models\ProductOption::create([
                                            'option_id' => $option->id,
                                            'product_id' => $check_product->id,
                                            'modifier_id' => $modifier->id,
                                            'max' => $v['pivot']['maximum_options'],
                                            'min' => $v['pivot']['minimum_options'],
                                        ]);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
// create foodics products and modifiers and options
function create_product_and_modifiers($restaurant_id ,$products_modifiers , $foodics_branch)
{
    if ($products_modifiers[0] != []) {
        if (is_array($products_modifiers[0]) || is_object($products_modifiers[0]))
        {
            foreach ($products_modifiers[0] as $key => $value) {
                if ($value['branches'] != null) {
                    foreach ($value['branches'] as $bkey => $bvalue) {
                        //$bvalue['pivot']['is_active'] == true && $bvalue['pivot']['is_in_stock'] == true  &&
                        if ($bvalue['receives_online_orders'] = true) {
                            productAndModifierCreation($value , $restaurant_id , $foodics_branch);
                        }
                    }
                }
                else {
                    productAndModifierCreation($value , $restaurant_id , $foodics_branch);
                }
            }
        }
    }
    if ( isset($products_modifiers[1])) {
        if ($products_modifiers[1]['next'] != null)
        {
            $products_modifiers = array_values(json_decode(get_products_with_modifiers($restaurant_id , substr($products_modifiers[1]['next'], strpos($products_modifiers[1]['next'], "=") + 1)), true));
            create_product_and_modifiers($restaurant_id , $products_modifiers , $foodics_branch);
        }
    }


}

function get_discounts($user_id)
{
    $basURL = foodics_url() . "discounts?include=products,combos,product_tags,categories,branches,customer_tags";
    $headers = array(
        'Content-type: application/json',
        'Accept: application/json',
        'Authorization: Bearer ' . foodics_token($user_id),
    );
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $basURL,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => $headers,
    ));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        return $err;
    } else {
//        dd($response);
        return $response;
    }
}


function create_menu($restaurant_id = null)
{
    $user = \App\Models\Restaurant::find($restaurant_id);
    if ($user->foodics_status == 'true' && $user->foodics_access_token != null && strlen($user->foodics_access_token) > 1200) {
        $foodics_branch = \App\Models\Branch::whereRestaurantId($restaurant_id)
            ->where('foodics_status', 'true')
            ->first();
        // delete all restaurants branches and tables and categories and products and modifiers and options
        \App\Models\Table::whereRestaurantId($restaurant_id)
            ->where('branch_id', $foodics_branch->id)
            ->delete();
//        \App\Models\Modifier::whereRestaurantId($restaurant_id)->delete();
//        \App\Models\Option::whereRestaurantId($restaurant_id)->delete();
//        \App\Models\Product::whereRestaurantId($restaurant_id)
//            ->where('branch_id', $foodics_branch->id)
//            ->delete();
        // \App\Models\Branch::whereRestaurantId($restaurant_id)->where('main' , 'false')->delete();
//        \App\Models\MenuCategory::whereRestaurantId($restaurant_id)
//            ->where('branch_id', $foodics_branch->id)
//            ->delete();
        \App\Models\RestaurantFoodicsBranch::whereRestaurantId($restaurant_id)
            ->where('branch_id', $foodics_branch->id)
            ->delete();
        \App\Models\FoodicsDiscount::whereBranchId($foodics_branch->id)->delete();
        // create payment method
        create_payment_method($restaurant_id, 'EasyMenu-online');
        create_payment_method($restaurant_id, 'EasyMenu-cash');

        /**
         * 1  -> Branches Created
         */
        $restaurant = \App\Models\Restaurant::find($restaurant_id);

        $restaurant->update([
            'tax' => 'true',
            'tax_value' => 0,
        ]);

        $get_branches_with_taxes = array_values(json_decode(get_branches_with_taxes($restaurant_id), true));
        if (count($get_branches_with_taxes) > 0) {
            // create branches
            create_branches($restaurant_id, $get_branches_with_taxes, $foodics_branch->id);
        }
        $get_sections = array_values(json_decode(get_sections($restaurant_id), true));
        if (count($get_sections) > 0) {
            // create tables
            create_tables($restaurant_id, $get_sections, $foodics_branch->id);
        }
        /**
         *  create restaurant category
         */
        $categories = array_values(json_decode(get_categories($restaurant_id), true));
        if (count($categories) > 0) {
            create_categories($restaurant_id, $categories, $foodics_branch->id);
        }
        // charges
        $charges = array_values(json_decode(get_charges($restaurant_id), true));
        if (count($charges) > 0) {
            create_charges($restaurant_id, $charges);
        }
        $products_modifiers = array_values(json_decode(get_products_with_modifiers($restaurant_id), true));
        $modifiers_options = array_values(json_decode(get_modifiers_options($restaurant_id), true));
        if (count($products_modifiers) > 0) {
            create_product_and_modifiers($restaurant_id, $products_modifiers, $foodics_branch->id);
        }
        // create discounts
        $get_discounts = array_values(json_decode(get_discounts($restaurant_id), true));
        if (count($get_discounts) > 0) {
            create_discounts($restaurant_id, $get_discounts, $foodics_branch->id);
        }
    }
}


function create_discounts($restaurant_id , $data , $branch_id)
{
    if ($data[0] != []) {
        if (is_array($data[0]) || is_object($data[0])) {
            foreach ($data[0] as $key => $value) {
                // create discounts
                if ($value['deleted_at'] == null)
                {
                    $branches = [];
                    if ($value['branches'] != [])
                    {
                        foreach ($value['branches'] as $branch)
                        {
                            array_push($branches , $branch['id']);
                        }
                    }
                    $categories = [];
                    if ($value['categories'] != [])
                    {
                        foreach ($value['categories'] as $category)
                        {
                            array_push($categories , $category['id']);
                        }
                    }
                    $products = [];
                    if ($value['products'] != [])
                    {
                        foreach ($value['products'] as $product)
                        {
                            array_push($products , $product['id']);
                        }
                    }
                    $discount = \App\Models\FoodicsDiscount::create([
                        'branch_id' => $branch_id,
                        'foodics_id' => $value['id'],
                        'name_ar'  => $value['name_localized'],
                        'name_en'  => $value['name'],
                        'amount'   => $value['amount'],
                        'is_percentage'   => $value['is_percentage'] == true ? 'true' : 'false',
                        'minimum_product_price' => $value['minimum_product_price'],
                        'minimum_order_price'   => $value['minimum_order_price'],
                        'maximum_amount'        => $value['maximum_amount'],
                        'is_taxable'      => $value['is_taxable'] == true ? 'true' : 'false',
                        'order_types'     => json_encode($value['order_types']),
                        'associate_to_all_branches' => $value['associate_to_all_branches'] == true ? 'true' : 'false',
                        'branches'   => !empty($branches) ? json_encode($branches) : null,
                        'categories' => !empty($categories) ? json_encode($categories) : null,
                        'products'   => !empty($products) ? json_encode($products) : null,
                    ]);
                }
            }
        }
    }
    if (isset($data[1])) {
        if ($data[1]['next'] != null) {
            $get_discounts = array_values(json_decode(get_discounts($restaurant_id, substr($data[1]['next'], strpos($data[1]['next'], "=") + 1)), true));
            if (count($get_discounts) > 0) {
                create_discounts($restaurant_id, $get_discounts  , $branch_id);
            }
        }
    }
}


 function updateCurrency(){
    $currecyCode = Country::whereNotNull('currency_code')->get()->pluck('currency_code')->toArray();

    $dataCurrency = implode('%2C' , $currecyCode);
    $curl = curl_init();
    curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.apilayer.com/exchangerates_data/latest?symbols=".$dataCurrency."&base=SAR",
    CURLOPT_HTTPHEADER => array(
        "Content-Type: text/plain",
        "apikey: JwbjW97j0ViX6LYdjzFbvhNsYEudSOMw"
    ),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET"
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    $data =  json_decode($response , true);
    if(isset($data['rates']) and is_array($data['rates']) and count($data['rates'])):
        foreach($data['rates'] as $key => $value):
            if($country = Country::where('currency_code' , $key)->first()):
                $country->update([
                    'riyal_value' => $value
                ]);
            endif;
        endforeach;
    endif;
 }

 
 function checkWordsCount($string , $count , $isTag = false){
    $words  = $isTag == true ? explode(' ' , strip_tags($string)) : explode(' ' , $string);
    if(count($words) > $count) return true;
    return false;
}
function getShortDescription($string , $start , $last =  0, $isTag = false){
    $words  = $isTag == true ? explode(' ' , strip_tags($string)) : explode(' ' , $string);
    $results = '';
    foreach($words as $index => $temp):
        if($index >= $start and ($last == 0 or $index <= $last)) $results .= $temp . ' ';
    endforeach;

    return $results;
}

function tap_payment($token , $amount , $user_name , $email , $country_code , $phone , $callBackUrl , $order_id)
{
    $basURL = "https://api.tap.company/v2/charges";
    $headers = array(
        'Content-type: application/json',
        'Accept: application/json',
        'Authorization: Bearer ' . $token,
    );

    $data = array(
        "amount" => $amount,
        "currency" => "SAR",
        "customer" => array(
            "first_name" => $user_name,
            "email" => $email,
            "phone" => array(
                "country_code" => $country_code,
                "number" => $phone
            ),
        ),
        "source" => array(
            "id" => "src_card"
        ),
        "redirect" => array(
            "url" => route($callBackUrl , [$order_id , $token]),
        )
    );
    $order = json_encode($data);
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $basURL,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $order,
        CURLOPT_HTTPHEADER => $headers,
    ));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        return $err;
    } else {
        $response = json_decode($response);
        return $response->transaction->url;
    }
}

function express_payment($merchant_key,$password,$amount,$success_url , $orderId , $user_name = null, $email = null)
{
    $basURL = "https://pay.expresspay.sa/api/v1/session";
    $headers = array(
        'Content-type: application/json',
        'Accept: application/json',
    );
    $order_id = 'order-'.mt_rand(1000, 9999);
    $hash = array(
        "number" => $order_id,
        "amount" =>$amount,
        "currency" => "SAR",
        "description" => "pay order value",
        'password' => $password);
    $hash = implode($hash);
    $hash = strtoupper($hash);
    $hash = md5($hash);
    $hash = sha1($hash);
//    dd($hash);
    $data = array(
        "merchant_key" => $merchant_key,
        "operation" => "purchase",
        "methods" => array(
            "card"
        ),
        "order" => array(
            "number" => $order_id,
            "amount" =>$amount,
            "currency" => "SAR",
            "description" => "pay order value",
        ),
        "cancel_url" => route('express_error'),
        "success_url" => route($success_url , $orderId),
        "customer" => array(
            "name" => $user_name == null ? "John Doe" : $user_name,
            "email" => $email == null ? "test@email.com" : $email,
        ),
        "billing_address" => array(
            "country" => "SA",
            "state" => "CA",
            "city" => "Los Angeles",
            "address" => "Moor Building 35274",
            "zip" => "123456",
            "phone" => "347771112233"
        ),
        "recurring_init" => "true",
        "hash" => $hash
    );

    $order = json_encode($data);
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $basURL,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $order,
        CURLOPT_HTTPHEADER => $headers,
    ));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        return $err;
    } else {
        $response = json_decode($response);
        return $response->redirect_url;
    }
}


function checkUrlAdsType(){
            
    if(auth('restaurant')->check()):
        if(isUrlActive('restaurant/home' , true)):
            return 'home' ;
        elseif(isUrlActive('restaurant/profile' , true)):
            return 'profile' ;
        elseif(isUrlActive('services_store' , false) or isUrlActive('integration' , false)):
            return 'service' ;
        elseif(isUrlActive('reservation' , false)):
            return 'reservation' ;
            
        elseif(isUrlActive('product' , false)):
            return 'product' ;
        elseif(isUrlActive('menu_categor' , false) or isUrlActive('sub_categor' , false)):
            return 'menu_category' ;
        else:
            return 'all';
        endif;
    endif;
    
    return '';
}