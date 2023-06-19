<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
            // $this->renderable(function (ValidationException $e, $request) {
            //     if (true) {
            //         $message = $e->getMessage();
            //         foreach($errors = $e->errors() as $index => $value){
            //             $message = $value[0];
            //             break;
            //         }
            //         return response([
            //             'status' => false, 
            //             'message' => $message , 
            //             'errors' => $e->errors()
            //         ] , 422);
            //     }
            // });
        });
    }
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()){
//            return response()->json(['error' => 'Unauthenticated.'], 401);
            $errors = [
                'key'=>'token',
                'value'=>trans('messages.token_is_required'),
            ];

            http_response_code(401);  // set the code
            return response()->json($errors)->setStatusCode(401);

        }
//        $guard = array_get($exception->guards(),0);
        $guard = Arr::get($exception->guards(), 0);

        switch ($guard){
            case 'admin':
                $login = 'admin.login';
                break;
            case 'restaurant':
                $login = 'restaurant.login';
                break;
            case 'marketer':
                $login = 'marketer.login';
                break;
            case 'employee':
                $login = 'employee.login';
                break;
            case 'web':
                $login = 'showUserLogin';
                break;
//            case 'provider':
//                $login = 'provider.login';
//                break;
            default:
                $login = 'showUserLogin';
        }
        // dd(Route::current()->getName());
        // dd($guard);
        return redirect()->guest(route($login))->with('error', trans('messages.You_should_login_first'));
    }
}
