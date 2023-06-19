<?php

namespace App\Http\Controllers\Api;

use App\About;
use App\AppAdd;
use App\ContactUs;
use App\Education;
use App\Field;
use App\Group;
use App\GroupUser;
use App\Http\Resources\Meal;
use App\Http\Resources\NotificationCollection;
use App\Http\Resources\Order;
use App\Notification;
use App\Rating;
use App\SawaqUserDevice;
use App\TermsCondition;
use App\UserDevice;
use App\UserNotification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class ApiController extends Controller
{
    public function getImagesPath() {

        $data['user'] = imgPath('users');
        $data['foods'] = imgPath('foods');
        $data['trade_photo'] = imgPath('trads');
        $data['app_adds'] = imgPath('app_adds');

        return $this->respondWithSuccess($data);
    }



    public function getAbout(Request $request) {

        $rules = [
            'lang'   => 'required|in:ar,en'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return $this->respondWithError(validateRules($validator->errors(), $rules));

        $data = About::where('id', 1)->select($request->lang .'_content')->first();

        return $this->respondWithSuccess($data);
    }

    public function getTermsConditions(Request $request) {

        $rules = [
            'lang'   => 'required|in:ar,en'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return $this->respondWithError(validateRules($validator->errors(), $rules));

        $data = TermsCondition::where('id', 1)->select($request->lang .'_content')->first();

        return $this->respondWithSuccess($data);
    }

    public function contactUs(Request $request) {

        $rules = [
            'name'      => 'required|max:255',
            'email'     => 'required|max:194',
            'message'   => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return $this->respondWithError(validateRules($validator->errors(), $rules));

        $created = ContactUs::create($request->all());

        return $created
            ? $this->respondWithSuccess($created)
            : $this->respondWithServerError();
    }

    public function listNotifications(Request $request) {

        $notifications = UserNotification::Where('user_id', $request->user()->id)
//            ->where('is_read' , '0')
            ->orderBy('id','desc')
            ->get();
        if ($notifications->count() > 0)
        {
            return $this->respondWithSuccessData(new NotificationCollection($notifications));
        }else{
            $errors = [
                'key'    => 'list_notifications',
                'value'  => trans('messages.no_notifications')
            ];
            return $this->respondWithErrorArray(array($errors));
        }

    }
    public function read_all_notification(Request $request)
    {
        $notifications = Notification::where('user_id',$request->user()->id)->get();
        if ($notifications->count() > 0)
        {
            foreach($notifications as $notification)
            {
                $notification->update([
                    'is_read'  => '1',
                ]);
            }
            return ApiController::respondWithSuccess([
                'key'     =>  'read_all_notification',
                'value'   => 'success'
            ]);
        }else{
            self::respondWithErrorArray(array([
                'key'   => 'read_all_notification',
                'value' => 'something went wrong',
            ]));
        }
    }
    public function read_notification(Request $request , $id)
    {
        $notification = Notification::Where('id', $id)->where('user_id',$request->user()->id)->first();
        if ($notification)
        {
            $notification->update([
                'is_read'  => '1',
            ]);
            return ApiController::respondWithSuccess([
                'key'     =>  'read_notification',
                'value'   => 'success'
            ]);
        }else{
            return ApiController::respondWithErrorArray(array([
                'key'   => 'read_notification',
                'value' => 'something went wrong',
            ]));
        }
    }
    public function delete_Notifications( $id , Request $request) {

        $data = UserNotification::Where('id', $id)->where('user_id',$request->user()->id)->delete();
        return $data
            ? $this->respondWithSuccess([
                'message'=> trans('messages.notification_deleted')
            ])
            :$this->respondWithServerErrorArray();
    }

    public static function createUserDeviceToken($userId, $deviceToken, $deviceType) {

        $created = UserDevice::updateOrCreate(
            ['user_id' => $userId]
            ,['device_token' => $deviceToken]);
        return $created;
    }
    public static function createDeviceToken($deviceToken) {

        $created = UserDevice::updateOrCreate([
            'device_token' => $deviceToken
        ]);
        return $created;
    }
    public static function respondWithSuccess($data) {
        http_response_code(200);
        return response()->json($data)->setStatusCode(200);
    }
    public static function respondWithSuccessData($data) {
        http_response_code(200);
        return response()->json(['data' => $data])->setStatusCode(200);
    }

    public static function respondWithErrorArray($errors) {
        http_response_code(422);  // set the code
        return response()->json(['errors' => $errors])->setStatusCode(422);
    }public static function respondWithErrorObject($errors) {
    http_response_code(422);  // set the code
    return response()->json(['data' => $errors])->setStatusCode(422);
}
    public static function respondWithErrorNOTFoundObject($errors) {
        http_response_code(404);  // set the code
        return response()->json($errors)->setStatusCode(404);
    }
    public static function respondWithErrorNOTFoundArray($errors) {
        http_response_code(404);  // set the code
        return response()->json(['mainCode'=> 0,'code' =>  http_response_code()  , $errors])->setStatusCode(404);
    }
    public static function respondWithErrorClient($errors) {
        http_response_code(400);  // set the code
        return response()->json($errors)->setStatusCode(400);
    }
    public static function respondWithErrorAuthObject($errors) {
        http_response_code(401);  // set the code
        return response()->json($errors)->setStatusCode(401);
    }
    public static function respondWithErrorAuthArray($errors) {
        http_response_code(401);  // set the code
        return response()->json($errors)->setStatusCode(401);
    }


    public static function respondWithServerErrorArray() {
        $errors = 'Sorry something went wrong, please try again';
        http_response_code(500);
        return response()->json(['mainCode'=> 0,'code' =>  http_response_code()  , 'data' => null, 'error' => $errors])->setStatusCode(500);
    }
    public static function respondWithServerErrorObject() {
        $errors = 'Sorry something went wrong, please try again';
        http_response_code(500);
        return response()->json(['mainCode'=> 0,'code' =>  http_response_code()  , 'data' => null, 'error' => $errors])->setStatusCode(500);
    }



}
