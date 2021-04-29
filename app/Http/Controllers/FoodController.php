<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Food;
use App\Http\Resources\FoodResource;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FoodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	
		 $food = DB::table('food')
		->select('categories.title as cat_name',"food.*") 
		->leftJoin("categories",\DB::raw("FIND_IN_SET(categories.id,food.category_id)"),">",\DB::raw("'0'"))->paginate(10);
		
		return $food;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {    
	
	
			
			
	         $filename = null;
	         $input = $request->all();
			 $rules = array(
             'image_name' => 'mimes:jpeg,png,jpg',
             'title' => 'required',
             'amount'=> 'required',
			 'category'=> 'required',
             'product_code'=> 'required|unique:food|max:6',			 
        );
        $validator = Validator::make($input, $rules);
		 
		
        if ($validator->fails()) {
            $arr = array("status" => 400, "message" => $validator->errors()->first(), "data" => array());
        
		} else {
			
			try {
				
				if($request->file('image_name')){
				$file = $request->file('image_name');
			    $input['image_name'] = time() . '.' . $file->getClientOriginalExtension();
                $destinationPath = public_path('/api_images/');
                $file->move($destinationPath, $input['image_name']);
				$filename =   $input['image_name'];
				}
				
				
				$food = new Food();
		        $food->title = $request->title;
		        $food->details = $request->details;
		        $food->amount = $request->amount;
		        $food->image_name =  $filename;
		        $food->category_id = $request->category;
				$food->product_code = $request->product_code;
		        if($food->save()){
			       $arr = new FoodResource($food);
		        }
		
		
		
				
			  } catch (\Exception $ex) {
                if (isset($ex->errorInfo[2])) {
                    $msg = $ex->errorInfo[2];
                } else {
                    $msg = $ex->getMessage();
                }
                $arr = array("status" => 400, "message" => $msg, "data" => array());
            }
			
			
		
		
		
		}
			
			
		 
		
		
        return \Response::json($arr);

		}

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
		$check = Food::where('id', '=', $id)->first();
       if ($check === null) {
        $arr = array("status" => 400, "message" => "Not Found Data", "data" => array());
      
	  }else{
		  
		 $arr = DB::table('food')
	     ->where('food.id','=',$id)
		//->select('categories.title as cat_name',"food.*") 
		->select("food.id","food.title","food.details","food.amount",\DB::raw("GROUP_CONCAT(categories.title) as Category"))
		
		->leftJoin("categories",\DB::raw("FIND_IN_SET(categories.id,food.category_id)"),">",\DB::raw("'0'"))
		->GroupBy("food.id","food.title","food.details","food.amount"  )->get(); 
	  }
		
       
		
		return \Response::json($arr);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
         
		     $filename = null;
	         $input = $request->all(); 
			 
			 
			 $rules = array(
             'image_name' => 'mimes:jpeg,png,jpg',
             'title' => 'required',
             'amount'=> 'required',
			 'category'=> 'required',
             'product_code'=> 'required|max:6|unique:food,product_code,'.$id,			 
        );
        $validator = Validator::make($input, $rules);
		 
		
        if ($validator->fails()) {
            $arr = array("status" => 400, "message1" => $validator->errors()->first(), "data" => array());
        
		} else {
			
			try {
				
				  
				 
				
				if ($request->hasFile('image_name')) {
				$file = $request->file('image_name');
			    $input['image_name'] = time() . '.' . $file->getClientOriginalExtension();
                $destinationPath = public_path('/api_images/');
                $file->move($destinationPath, $input['image_name']);
				$filename =   $input['image_name'];
				}
				
				
				$food = Food::findorFail($id);
		        $food->title = $request->title;
		        $food->details = $request->details;
		        $food->amount = $request->amount;
		        $food->image_name =  $filename;
		        $food->category_id = $request->category;
				$food->product_code = $request->product_code;
		        if($food->save()){
			       $arr = new FoodResource($food);
		        }
		
		 
			  } catch (\Exception $ex) {
                if (isset($ex->errorInfo[2])) {
                    $msg = $ex->errorInfo[2];
                } else {
                    $msg = $ex->getMessage();
                }
                $arr = array("status" => 400, "message" => $msg, "data" => array());
            }
			
		}
		
        return \Response::json($arr);
		 
		
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
		$check = Food::where('id', '=', $id)->first();
       if ($check === null) {
        $arr = array("status" => 400, "message" => "Not Found Data", "data" => array());
      }else{
		 $food = Food::findorFail($id);
	    if($food->delete())
		{
			$arr = array("status" => 400, "message" => "Successfully Deleted", "data" => array());
		} 
	  }
		  
       return \Response::json($arr); 
    }
	
	
	
}
