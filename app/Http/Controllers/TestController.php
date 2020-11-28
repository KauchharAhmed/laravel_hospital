<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests;
use DB;
use Session;

class TestController extends Controller
{
	   private $rcdate ;
     private $current_time ;
     private $loged_id ;
     private $branch_id ;
    public function __construct() {
		date_default_timezone_set('Asia/Dhaka');
		$this->rcdate 		  = date('Y-m-d');
		$this->current_time = date('H:i:s');
    $this->loged_id     = Session::get('admin_id');
    $this->branch_id    = Session::get('branch_id');
	}
   // add medical test
   public function addMedicalTest()
   {
   	// test code
   	$count = DB::table('tbl_test')->where('branch_id',$this->branch_id)->count();
   	if($count == '0'){
     $test_code = '' ;
   	}else{
    $query 		= DB::table('tbl_test')->where('branch_id',$this->branch_id)->orderBy('test_code','desc')->limit(1)->first();
    $test_code = $query->test_code + 1 ;
   	}
   	return view('test.addMedicalTest')->with('test_code',$test_code);
   }
   // add test info
   public function addTestInfo(Request $request)
   {
   	 $this->validate($request, [
    'name'      			=> 'required',
    'test_code' 			=> 'required',
    'test_price' 			=> 'required',
    'confirm_test_price'    => 'required',
    ]);
     $name        		 = trim($request->name);
     $test_code   		 = trim($request->test_code);
     $test_price 		 = trim($request->test_price);
     $confirm_test_price = trim($request->confirm_test_price);
     $remarks        	 = trim($request->remarks);
     // test price and confirm test price did not match
     if($test_price != $confirm_test_price)
     {
        Session::put('failed','Sorry ! Test Price And Confirm Test Price Did Not Match');
        return Redirect::to('addMedicalTest');
        exit();
     }
     #------------------------- duplicate test name / code check -------------------------------#
     $count = DB::table('tbl_test')->where('branch_id',$this->branch_id)->where('test_name',$name)->count();
     if($count > 0){
     	Session::put('failed','Sorry ! Test Name Already Exits');
        return Redirect::to('addMedicalTest');
        exit();
     }
     // test code count
     $count1 = DB::table('tbl_test')->where('branch_id',$this->branch_id)->where('test_code',$test_code)->count();
     if($count1 > 0){
     	Session::put('failed','Sorry ! Test Code Already Exits');
        return Redirect::to('addMedicalTest');
        exit();
     }
    #------------------------- end duplicate test name / code check ---------------------------#
    #------------------------- insert into test table -----------------------------------------#
    $data 					= array();
    $data['branch_id']    = $this->branch_id ;
    $data['test_name']   	= $name ;
    $data['test_code']   	= $test_code ;
    $data['test_price']   	= $test_price ;
    $data['remarks']   		= $remarks ;
    $data['added_id']   	= $this->loged_id ;
    $data['created_at']   	= $this->rcdate ;
    DB::table('tbl_test')->insert($data);
    Session::put('succes','Thanks , New Test Added Sucessfully');
    return Redirect::to('addMedicalTest');

    #------------------------- end insert into test table -------------------------------------# 
   }
   // manage medical test
   public function manageMedicalTest()
   {
   	$result = DB::table('tbl_test')->where('branch_id',$this->branch_id)->orderBy('id','desc')->get();
   	return view('test.manageMedicalTest')->with('result',$result);
   }
   // get test price to crate pathology bill
   public function getTestPrice(Request $request)
   {
    $test_id     = trim($request->test_id);
     $query      = DB::table('tbl_test')->where('id',$test_id)->take(1)->first();
     $price      = $query->test_price;
     echo $price ;
   }

}
