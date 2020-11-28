<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests;
use DB;
use Session;

class OpdController extends Controller
{
    private $rcdate ;
    private $current_time ;
    private $loged_id ;
    private $branch_id ;
    public function __construct() {
    date_default_timezone_set('Asia/Dhaka');
    $this->rcdate         = date('Y-m-d');
    $this->current_time   = date('H:i:s');
    $this->loged_id       = Session::get('admin_id');
    $this->branch_id      = Session::get('branch_id');
    }
    // add opd category
    public function addOpdCategory()
    {
	$count  = DB::table('tbl_opd_setting')->where('branch_id',$this->branch_id)->count();
    	if($count == '0'){
        Session::put('failed','Sorry ! Please First Insert OPD Setting');
        return Redirect::to('opdSetting');  
        exit();
    	}
      return view('opd.addOpdCategory');
    }
    // add opd category
    public function addOPDCategoryInfo(Request $request)
    {
    $this->validate($request, [
    'name'        => 'required'
    ]);
     $name          	= trim($request->name);
     $remarks        	= trim($request->remarks);
     //check duplicatet brand name
     $count = DB::table('tbl_opd_category')
     ->where('branch_id',$this->branch_id)
     ->where('opd_name', $name)
     ->count();
      if($count > 0){
        Session::put('failed','Sorry ! '.$name. ' OPD Category Already Exits. Try To Add New OPD Category');
        return Redirect::to('addOpdCategory');
        exit();
      }
     $data=array();
     $data['branch_id']    	    = $this->branch_id;
     $data['opd_name']    	    = $name;
     $data['remarks']       	= $remarks ;
     $data['added_id']          = $this->loged_id ;
     $data['created_at']    	= $this->rcdate ;
     $query = DB::table('tbl_opd_category')->insert($data);
     if($query){
        Session::put('succes','Thanks , OPD Category Added Sucessfully');
        return Redirect::to('addOpdCategory');
    }else{
        Session::put('failed','Sorry ! Error Occued. Try Again');
        return Redirect::to('addOpdCategory');
    }
    }
    // manage opd category
    public function manageOpdCategory()
    {
    	$result = DB::table('tbl_opd_category')->where('branch_id',$this->branch_id)->get();
    	return view('opd.manageOpdCategory')->with('result',$result);
    }
    // add opd fee
    public function addOpdFee()
    {
    // with doctor
    $doctor = DB::table('admin')
    ->join('tbl_doctor', 'admin.id', '=', 'tbl_doctor.admin_id')
    ->select('admin.*','tbl_doctor.speialist')
    ->where('admin.branch_id',$this->branch_id)
    ->get();
    // with opd category
    $opd_category = DB::table('tbl_opd_category')->where('branch_id',$this->branch_id)->get();
    return view('opd.addOpdFee')->with('doctor',$doctor)->with('opd_category',$opd_category);
    }
    // add opd fee amount
    public function addOpdFeeAmt(Request $request)
    {
    $this->validate($request, [
    'doctor_id'        => 'required',
    'opd_id'           => 'required',
    'fee_amount'       => 'required',
    'confirm_fee_amount' => 'required'
    ]);
     $doctor_id           = trim($request->doctor_id);
     $opd_id              = trim($request->opd_id);
     $fee_amount          = trim($request->fee_amount);
     $confirm_fee_amount  = trim($request->confirm_fee_amount);
     $remarks             = trim($request->remarks);
     if($fee_amount != $confirm_fee_amount){
        Session::put('failed','Sorry !Fee Amount And Confirm Fee Amount Did Not Match');
        return Redirect::to('addOpdFee');
        exit();
     }
     // check duplicate
     $count = DB::table('tbl_opd_fee')->where('branch_id',$this->branch_id)->where('doctor_id',$doctor_id)->where('opd_cat_id',$opd_id)->count();
     if($count > 0){
        Session::put('failed','Sorry !OPD Fee Amount Already Added Of This Doctor');
        return Redirect::to('addOpdFee');
        exit();
     }
     //insert
     $data_insert                   = array();
     $data_insert['branch_id']      = $this->branch_id ;
     $data_insert['doctor_id']      = $doctor_id ;
     $data_insert['opd_cat_id']     = $opd_id ;
     $data_insert['fee_amount']     = $fee_amount ;
     $data_insert['remarks']        = $remarks ;
     $data_insert['added_id']       = $this->loged_id ;
     $data_insert['created_time']   = $this->current_time ;
     $data_insert['created_at']     = $this->rcdate ;
     DB::table('tbl_opd_fee')->insert($data_insert) ;
    Session::put('succes','Thanks , OPD Fee Added Sucessfully');
    return Redirect::to('addOpdFee');
    }
    // manage opd fee
    public function manageOpdFee()
    {
     $result = DB::table('tbl_opd_fee')
    ->join('admin', 'tbl_opd_fee.doctor_id', '=', 'admin.id')
    ->join('tbl_opd_category', 'tbl_opd_fee.opd_cat_id', '=', 'tbl_opd_category.id')
    ->select('tbl_opd_fee.*','admin.name as doctor_name','tbl_opd_category.opd_name')
    ->where('tbl_opd_fee.branch_id',$this->branch_id)
    ->get();
     return view('opd.manageOpdFee')->with('result',$result);
    }
    // get opd category of this doctor
    public function getOpdCategoryOfThisDoctor(Request $request)
    {
     $doctor_id = trim($request->doctor_id);
     $result    = DB::table('tbl_opd_fee')
    ->join('tbl_opd_category', 'tbl_opd_fee.opd_cat_id', '=', 'tbl_opd_category.id')
    ->select('tbl_opd_fee.*','tbl_opd_category.opd_name')
    ->where('tbl_opd_fee.branch_id',$this->branch_id)
    ->where('tbl_opd_fee.doctor_id',$doctor_id)
    ->get();
     echo "<option value=''>Select OPD Category</option>" ;
    foreach ($result as $value) {
     echo "<option value=".$value->id.">".$value->opd_name."</option>" ;
    }
    }
  // get opd fee price
    public function getOpdFeePrice(Request $request)
    {
       $fee_id = trim($request->fee_id);
       // get fee amount of this fee id
       $query = DB::table('tbl_opd_fee')->where('id',$fee_id)->first();
       echo $query->fee_amount ;
    }
}
