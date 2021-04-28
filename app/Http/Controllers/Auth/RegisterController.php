<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Models\Admin;
use App\Models\Customre;


 
    use Illuminate\Http\Request;

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
		$this->middleware('guest:admin');
        $this->middleware('guest:Customre');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
       /* return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:4', 'confirmed'],
        ]);*/
		
		
		
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
	
	
	 protected function createAdmin(Request $request)
    {
		$input = $request->all();
        //$validator = $this->validator($request->all());
		$rules = array(
             'name' => 'required | string | max:255',
             'email' => 'required | string |email | max:255 |unique:admins',
             'password'=> 'required | string | min:4 | confirmed' 			 
        );
         $validator = Validator::make($input, $rules);
	    
		if ($validator->fails()) {
			 
			$arr = array("status" => 400, "message1" => $validator->errors()->first(), "data" => array());
        
		}else{
			 
        $arr = Admin::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
        ]);
        }
		
		return \Response::json($arr);
    }
	
	
	protected function createCustomer(Request $request)
    {
		$input = $request->all();
        //$validator = $this->validator($request->all());
		$rules = array(
             'name' => 'required | string | max:255',
             'email' => 'required | string |email | max:255 |unique:customres',
             'password'=> 'required | string | min:4 | confirmed' 			 
        );
         $validator = Validator::make($input, $rules);
	    
		if ($validator->fails()) {
			 
			$arr = array("status" => 400, "message1" => $validator->errors()->first(), "data" => array());
        
		}else{
			 $arr = Customre::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
        ]);
		}
       
		
		
		 
        return \Response::json($arr);
    }
}
