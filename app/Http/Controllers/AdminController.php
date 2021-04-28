<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Customer;
use DB;
 
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    function index(Request $request)
    {
        $admin= Admin::where('email', $request->email)->first();
        // print_r($data);
            if (!$admin || !Hash::check($request->password, $admin->password)) {
                return response([
                    'message' => ['These credentials do not match our records.']
                ], 404);
            }
        
             $token = $admin->createToken('my-app-token')->plainTextToken;
        
            $response = [
                'admin' => $admin,
                'token' => $token
            ];
        
             return response($response, 201);
    }
	
	
	
	function customre(){
		 return $customer = DB::table('customres')->paginate(10);
		
	}
}
