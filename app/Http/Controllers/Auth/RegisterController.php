<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    // use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(Request $request)
    {
      // Let's leave this here so it can be accessible in all of the blocks...
      $Response = array();
        try {
          // Validate the user account......
          $validation = Validator::make($request->all(),[
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:7'],
          ]);

          if ($validation->fails()) {
            $Response['data'] = [];
            $Response['token'] = [];
            $Response['status'] = 400;
            $Response['errors'] = $validation->errors();
            $Response['message'] = 'Sorry, Some Errors Occured And Your Request Could Not Be Completed.';

            // send back a json response...
            return response()->json($Response, 400);
          }

          // create the user....
          $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'email_verified_at' => date('Y-m-d H:i:s'),
            'password' => Hash::make($request->input('password')),
          ]);

          // Prepare and send back a response...
          $Response['data'] = $user;
          $Response['token'] = $user->createToken('authentication')->accessToken;
          $Response['status'] = 201;
          $Response['errors'] = '';
          $Response['message'] = 'Your Account Has Been Created Successfully.';

          // send back a valid json response....
          return response()->json($Response, 201);
        } catch (Exception $e) {
          // Prepare and send back a response.......
          $Response['data'] = [];
          $Response['status'] = 500;
          $Response['errors'] = array(
            'message' => $e->getMessage()
          );
          $Response['token'] = '';
          $Response['message'] = 'Failed To Create Your Account. Please, Try Again Later.';

          return response()->json($Response, 500);
        }
    }
}
