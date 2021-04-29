<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Resources\CategoryResources;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cat = Category::paginate(10);
		return   CategoryResources::collection($cat);
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
        
		
		
		$input = $request->all();
			 $rules = array(
             'title' => 'required|unique:categories',
             'status'=> 'required' 			 		 
        );
        $validator = Validator::make($input, $rules);
		 
		
        if ($validator->fails()) {
			
			$arr = array("status" => 400, "message" => $validator->errors()->first(), "data" => array());
		}else{
			
			try {
			$cat = new Category();
		$cat->title = $request->title;
		$cat->status = $request->status;
		if($cat->save()){
			$arr = new CategoryResources($cat);
		}
		
			}catch (\Exception $ex) {
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
		$cate = Category::where('id', '=', $id)->first();
       if ($cate === null) {
        $arr = array("status" => 400, "message" => "Not Found Data", "data" => array());
      }else{
	    $cat =  Category::findorFail($id);
		  
		if($cat){
		$arr = new CategoryResources($cat);
		}
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
        //
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
        
		
		
		$input = $request->all();
			 $rules = array(
             'status'=> 'required' ,
            'title' => 'required|unique:categories,title,'.$id			 
        );
        $validator = Validator::make($input, $rules);
		 
		
        if ($validator->fails()) {
			
			$arr = array("status" => 400, "message" => $validator->errors()->first(), "data" => array());
		}else{
			
			try {
			$cat =   Category::findorFail($id);
		$cat->title = $request->title;
		$cat->status = $request->status;
		if($cat->save()){
			$arr = new CategoryResources($cat);
		}
		
			}catch (\Exception $ex) {
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
		$cate = Category::where('id', '=', $id)->first();
       if ($cate === null) {
        $arr = array("status" => 400, "message" => "Not Found Data", "data" => array());
      }else{
		$cat = Category::findorFail($id);
		if($cat->delete()){
			 $arr = array("status" => 400, "message" => "Successfully Deleted", "data" => array());
		}  
	  }
       return \Response::json($arr); 
    }
	
	
	
	
	
	
}
