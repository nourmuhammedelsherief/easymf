<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use function Matrix\trace;
use Validator;
use App;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\PhoneVerification;
use App\ResetPassword;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class AuthController extends Controller
{
    public function registerMobile(Request $request)
    {
        $rules = [
            'phone_number' => 'required|unique:users',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));

        $code = mt_rand(1000, 9999);
        $check = substr($request->input('phone_number'), 0, 2) === "05";
        if ($check == true) {
            $phone = '00966' . ltrim($request->phone_number, '0');
        } else {
            $phone = '002' . $request->phone_number;
        }
        $user = User::wherePhone_number($request->phone_number)->first();
        if ($user == null) {
            $body = 'كود التأكيد الخاص بك في منصه الأحتفالات هو ' . $code;
            taqnyatSms($body , $phone);
        } else {
            $errors = [
                'key' => 'user_register_mobile',
                'value' => trans('messages.uRegisteredBefore')
            ];
            return ApiController::respondWithErrorArray(array($errors));
        }
        $created = PhoneVerification::create([
            'code' => $code,
            'phone_number' => $request->phone_number
        ]);
        $success = [
            'message' => 'تم أرسال الكود بنجاح',
            'code'    => $code
        ];
        return ApiController::respondWithSuccess($success);
    }

    public function register_phone_post(Request $request)
    {
        $rules = [
            'code' => 'required',
            'phone_number' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));

        $user = PhoneVerification::where('phone_number', $request->phone_number)->orderBy('id', 'desc')->first();

        if ($user) {

            if ($user->code == $request->code) {
                $successLogin = [
                    'message' => trans('messages.activation_code_success')
                ];
                return ApiController::respondWithSuccess($successLogin);
            } else {
                $errorsLogin = [
                    'message' => trans('messages.error_code')
                ];
                return ApiController::respondWithErrorClient($errorsLogin);
            }

        } else {

            $errorsLogin = [
                'message' => trans('messages.error_code')
            ];
            return ApiController::respondWithErrorClient($errorsLogin);
        }
    }

    public function resend_code(Request $request)
    {

        $rules = [
            'phone_number' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));

        $check = substr($request->input('phone_number'), 0, 2) === "05";
        if ($check == true) {
            $phone = '00966' . ltrim($request->phone_number, '0');
        } else {
            $phone = '002' . $request->phone_number;
        }
        $code = mt_rand(1000, 9999);
        $body = 'كود التأكيد الخاص بك في منصه الأحتفالات هو ' . $code;
        taqnyatSms($body, $phone);
        $created = PhoneVerification::create([
            'code' => $code,
            'phone_number' => $request->phone_number
        ]);
        $success = [
            'message' => 'تم أرسال الكود بنجاح',
            'code'    => $code
        ];
        return $created
            ? ApiController::respondWithSuccess($success)
            : ApiController::respondWithServerErrorObject();

    }

    public function register(Request $request)
    {
        $rules = [
            'phone_number' => 'required|unique:users',
            'name' => 'required|max:255',
            'email' => 'required|max:255',
            'password' => 'required|string|min:6',
            'password_confirmation' => 'required|same:password',
//            'device_token' => 'required',
            'photo'     => 'nullable|mimes:jpg,jpeg,png,gif,tif,psd,bmp|max:5000'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));

        $user = User::create([
            'phone_number' => $request->phone_number,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
//            'photo'    => $request->file('photo') == null ? 'default.png' : UploadImage($request->file('photo'), 'photo', '/uploads/users'),
        ]);

        $user->update(['api_token' => generateApiToken($user->id, 10)]);

        PhoneVerification::where('phone_number', $request->phone_number)->orderBy('id', 'desc')->delete();

        //save_device_token....
//        $created = ApiController::createUserDeviceToken($user->id, $request->device_token, $request->device_type);

        return $user
            ? ApiController::respondWithSuccess('created')
            : ApiController::respondWithServerErrorArray();

    }

    public function login(Request $request)
    {

        $rules = [
            'phone_number' => 'required',
            'password' => 'required',
            'device_token' => 'required',
//            'lang' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));


        if (Auth::attempt(['phone_number' => $request->phone_number, 'password' => $request->password])) {

            if (Auth::user()->active == 'notActive') {
                $errors = ['key' => 'message',
                    'value' => trans('messages.Sorry_your_membership_was_stopped_by_Management')
                ];
                return ApiController::respondWithErrorArray(array($errors));
            }

            //save_device_token....
            $created = ApiController::createUserDeviceToken(Auth::user()->id, $request->device_token, $request->device_type);

            $all = User::where('phone_number', $request->phone_number)->first();
            $all->update([
                'api_token' => generateApiToken($all->id, 10),
//                'lang' => $request->lang,
            ]);
            $user = User::where('phone_number', $request->phone_number)->first();

            return $created
                ? ApiController::respondWithSuccess(new App\Http\Resources\User($user))
                : ApiController::respondWithServerErrorArray();
        } else {
            $user = User::wherePhone_number($request->phone_number)->first();
            if ($user == null) {
                $errors = [
                    'key' => 'message',
                    'value' => trans('messages.Wrong_phone'),
                ];
                return ApiController::respondWithErrorArray(array($errors));
            } else {
                $errors = [
                    'key' => 'message',
                    'value' => trans('messages.error_password'),
                ];
                return ApiController::respondWithErrorArray(array($errors));
            }
        }

    }

    public function forgetPassword(Request $request)
    {
        $rules = [
            'phone_number' => 'required|numeric',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));

        $user = User::where('phone_number', $request->phone_number)->first();
        if ($user != null) {
            $code = mt_rand(1000, 9999);
            $check = substr($request->input('phone_number'), 0, 2) === "05";
            if ($check == true) {
                $phone = '00966' . ltrim($request->phone_number, '0');
            } else {
                $phone = '002' . $request->phone_number;
            }
            $body = 'د التأكيد  الخاص بك  في  منصه الأحتفالات هو ' . $code;
            taqnyatSms($body, $phone);
            $updated = App\User::where('phone_number', $request->phone_number)
                ->update([
                    'verification_code' => $code,
                ]);
            $success = [
                'message' => 'تم أرسال الكود بنجاح',
                'code'    => $code
            ];

            return $updated
                ? ApiController::respondWithSuccess($success)
                : ApiController::respondWithServerErrorObject();
        } else {
            $errorsLogin = [
                'message' => trans('messages.Wrong_phone')
            ];
            return ApiController::respondWithErrorClient($errorsLogin);
        }
    }

    public function confirmResetCode(Request $request)
    {

        $rules = [
            'phone_number' => 'required',
            'code' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));

        $user = App\User::where('phone_number', $request->phone_number)
            ->where('verification_code', $request->code)
            ->first();
        if ($user) {
            $updated = App\User::where('phone_number', $request->phone_number)
                ->where('verification_code', $request->code)
                ->update([
                    'verification_code' => null
                ]);
            $success = [
                'message' => trans('messages.code_success')
            ];
            return $updated
                ? ApiController::respondWithSuccess($success)
                : ApiController::respondWithServerErrorObject();
        } else {

            $errorsLogin = [
                'message' => trans('messages.error_code')
            ];
            return ApiController::respondWithErrorClient($errorsLogin);
        }


    }

    public function resetPassword(Request $request)
    {
        $rules = [
            'phone_number' => 'required|numeric',
//            'phone'                 => 'required',
            'password' => 'required',
            'password_confirmation' => 'required|same:password',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));

        $user = User::where('phone_number', $request->phone_number)->first();
//        $user = User::wherePhone($request->phone)->first();

        if ($user)
            $updated = $user->update(['password' => Hash::make($request->password)]);
        else {
            $errorsLogin = [
                'message' => trans('messages.Wrong_phone')
            ];
            return ApiController::respondWithErrorClient(array($errorsLogin));
        }
        $success = [
            'message' => trans('messages.Password_reset_successfully')
        ];
        return $updated
            ? ApiController::respondWithSuccess($success)
            : ApiController::respondWithServerErrorObject();
    }

    public function changePassword(Request $request)
    {

        $rules = [
            'current_password' => 'required',
            'new_password' => 'required',
            'password_confirmation' => 'required|same:new_password',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));

        $error_old_password = [
            'message' => trans('messages.error_old_password')
        ];
        if (!(Hash::check($request->current_password, $request->user()->password)))
            return ApiController::respondWithErrorNOTFoundObject($error_old_password);
//        if( strcmp($request->current_password, $request->new_password) == 0 )
//            return response()->json(['status' => 'error', 'code' => 404, 'message' => 'New password cant be the same as the old one.']);

        //update-password-finally ^^
        $updated = $request->user()->update(['password' => Hash::make($request->new_password)]);

        $success_password = [
            'message' => trans('messages.Password_reset_successfully')
        ];

        return $updated
            ? ApiController::respondWithSuccess($success_password)
            : ApiController::respondWithServerErrorObject();
    }

    public function change_phone_number(Request $request)
    {
        $rules = [
            'phone_number' => 'required|numeric|unique:users,phone_number,' . $request->user()->id,
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));

        $code = mt_rand(1000, 9999);
        $check = substr($request->input('phone_number'), 0, 2) === "05";
        if ($check == true) {
            $phone = '00966' . ltrim($request->phone_number, '0');
        } else {
            $phone = '002' . $request->phone_number;
        }
        $body = 'كود التأكيد  الخاص بك في منصة الأحتفالات هو ' . $code;
        taqnyatSms($body, $phone);
        $updated = App\User::where('id', Auth::user()->id)->update([
            'verification_code' => $code,
        ]);
        $success = [
            'message' => trans('messages.success_send_code'),
            'code'  => $code
        ];
        return $updated
            ? ApiController::respondWithSuccess($success)
            : ApiController::respondWithServerErrorObject();
    }

    public function check_code_changeNumber(Request $request)
    {
        $rules = [
            'code' => 'required',
            'phone_number' => 'required|numeric|unique:users,phone_number,' . $request->user()->id,
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));

        $user = App\User::where('id', Auth::user()->id)->where('verification_code', $request->code)->first();
        if ($user) {
            $updated = $user->update([
                'verification_code' => null,
                'phone_number' => $request->phone_number,
            ]);

            $success = [
                'message' => trans('messages.phone_changed_successfully')
            ];
            return $updated
                ? ApiController::respondWithSuccess(new App\Http\Resources\User($user))
                : ApiController::respondWithServerErrorObject();
        } else {

            $errorsLogin = [
                'key'     => 'code',
                'message' => trans('messages.error_code')
            ];
            return ApiController::respondWithErrorObject(array($errorsLogin));
        }
    }

    public function user_edit_account(Request $request)
    {
        $rules = [
//            'email' => 'nullable|email|unique:users,email,' . $request->user()->id,
            'name' => 'nullable|max:255',
            'city_id' => 'nullable|exists:cities,id',
//            'country_id' => 'sometimes|exists:countries,id',
            'photo' => 'nullable|mimes:jpeg,bmp,png,jpg|max:5000',
//            'driving_licence'       => 'nullable|mimes:jpeg,bmp,png,jpg|max:5000',
//            'identity_number'       => 'nullable',
//            'device_token'          => 'nullable',
//            'latitude'              => 'nullable',
//            'longitude'             => 'nullable',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));

        $user = User::where('id', $request->user()->id)->first();

        if ($request->city_id != null && $request->city_id != $user->city_id)
        {
            $cart = Cart::whereUserId($request->user()->id)
                ->where('status' , 'opened')
                ->orderBy('id' , 'desc')
                ->first();
            if ($cart)
            {
                $cart->delete();
            }
        }
        $updated = $user->update([
            'city_id' => $request->city_id == null ? $user->city_id : $request->city_id,
            'name' => $request->name == null ? $user->name : $request->name,
            'photo' => $request->photo == null ? $user->photo : UploadImageEdit($request->file('photo'), 'photo', '/uploads/users', $request->user()->photo),
        ]);
        return ApiController::respondWithSuccess(new \App\Http\Resources\User($user));
    }

    public function logout(Request $request)
    {

        $rules = [
            'device_token' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));

        $exists = App\UserDevice::where('user_id', $request->user()->id)->get();

        if (count($exists) !== 0) {
            foreach ($exists as $new) {
                $new->delete();
            }

        }
        $users = User::where('id', $request->user()->id)->first()->update(
            [
                'api_token' => null
            ]
        );
        $success = [
            'message' => 'تم تسجيل الخروج بنجاح'
        ];
        return $users
            ? ApiController::respondWithSuccess($success)
            : ApiController::respondWithServerErrorArray();
    }

    public function user_data($id)
    {
        $user = User::find($id);
        if ($user)
        {
            return ApiController::respondWithSuccess(new App\Http\Resources\User($user));
        }else{
            $errors = [
                'message' => 'لا يوجد هذا المستخدم'
            ];
            return ApiController::respondWithErrorArray(array($errors));
        }
    }
}
