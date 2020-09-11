<?php

namespace App\Http\Controllers\Auth;

use App\User;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

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

    use RegistersUsers;

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
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
      // Let's leave this here so it can be accessible in all of the blocks...
      $Response = array();
        try {
          $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
          ]);

          // Prepare and send back a response...
          $Response['data'] = $user;
          $Response['token'] = Auth::user()->createToken('authentication');
          $Response['status'] = 200;
          $Response['errors'] = '';
          $Response['message'] = 'Your Account Has Been Created Successfully.';

          // send back a valid json response....
          return response()->json($Response, 200);
        } catch (Exception $e) {
          // Prepare and send back a response.......
          $Response['data'] = [];
          $Response['status'] = 500;
          $Response['errors'] = array(
            'title' => 'Sorry, An Unexpected Error Occurred And Your Request Could Not Be Completed.',
            'message' => $e->getMessage()
          );
          $Response['token'] = '';
          $Response['message'] = 'Failed To Create Your Account. Please, Try Again Later.';

          return response()->json($Response, 500);
        }
    }
}
