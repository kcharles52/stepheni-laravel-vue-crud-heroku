<?php

namespace App\Http\Controllers\Auth;

use Auth;

use App\User;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }


    /**
     * Authenticates Users...
     *
     * @return JSON
     */
    public function login(Request $request)
    {
      // Do Some Validation....
      $validator = Validator::make($request->all(), [
        'email' => ['email', 'required'],
        'password' => ['string', 'required']
      ]);
      $Response = array();

      // check if the validator fails....
      if ($validator->fails()) {
        $Response['data'] = [];
        $Response['token'] = [];
        $Response['status'] = 400;
        $Response['errors'] = $validator->errors();
        $Response['message'] = 'Sorry, Some Errors Occured And Your Request Could Not Be Completed.';

        // send back a json response...
        return response()->json($Response, 400);
      }

      // Since the check was passed, we can check the users email address and password and validate the user...
      $emailAddress = stripcslashes(strip_tags($request->input('email')));
      $password = stripcslashes(strip_tags($request->input('password')));

      if (Auth::attempt(['email' =>$emailAddress, 'password' => $password])) {
        $user = User::where('email', $emailAddress)->first();
        $Response['data'] = $user;
        $Response['token'] = Auth::user()->createToken('authentication')->accessToken;
        $Response['status'] = 200;
        $Response['errors'] = '';
        $Response['message'] = 'Login Successfull.';

        // dont forget to update the user updated_at field...
        $user->updated_at = date('Y-m-d H:i:s');
        $user->save();

        // send back a valid json response....
        return response()->json($Response, 200);
      }

      // If there was an email or password mismatch...
      $Response['data'] = [];
      $Response['token'] = [];
      $Response['status'] = 401;
      $Response['errors'] = array(
        'Email Password' => [
            'Failed To Login. Please, Check The Email Address Or Password And Try Again.'
          ]
      ); //This was an array of errors in the first conditional... don't reinvent the wheel....

      // send back a response...
      return response()->json($Response, 401);
    }

    /**
    * Logout user (Revoke the token)
    *
    * @return [string] message
    */
     public function logout(Request $request)
     {
         $request->user()->token()->revoke();
         return response()->json([
             'message' => 'Successfully logged out'
         ]);
     }

}
