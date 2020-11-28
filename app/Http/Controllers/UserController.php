<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests;
use DB;
use Session;
class UserController extends Controller
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
	// add casier by manager
	public function addCashier()
	{
		return view('users.addCashier');
	}
	// add cassier info
	public function addCashierInfo(Request $request)
	{
     $this->validate($request, [
    'name'              => 'required',
    'mobile'            => 'required',
    'address'           => 'required',
    'image'             => 'mimes:jpeg,jpg,png|max:100'
    ]);
     $name                  = trim($request->name);
     $father_name           = trim($request->father_name);
     $mobile                = trim($request->mobile);
     $email                 = trim($request->email);
     $nid                   = trim($request->nid);
     $address               = trim($request->address);
     #--------------------- DUPLIATE ENTRY CHECK------------------#
     // by mobile number
     $check_count = DB::table('admin')->where('mobile',$mobile)->count();
     if($check_count > 0){
        Session::put('failed','Sorry ! This Mobile Number Already Exits');
        return Redirect::to('addCashier');
        exit();
     }
     #-------------------- END DUPLICATE ENTRY CHECK---------------#
     $salt      = 'a123A321';
     $password  = trim(sha1($mobile.$salt));
    
     $data=array();
     $data['branch_id']       = $this->branch_id ;
     $data['name']            = $name ;
     $data['nid']             = $nid ;
     $data['father_name']     = $father_name ;
     $data['email']           = $email ;
     $data['mobile']          = $mobile ;
     $data['type']            = 3 ;
     $data['password']        =  $password;
     $data['address']         = $address;
     $data['status']          = 1;
     $data['added_id']        = $this->loged_id;
     $data['creatd_at']      =  $this->rcdate ;
     $image                  = $request->file('image');
         if($image){
         $image_name        = str_random(20);
         $ext               = strtolower($image->getClientOriginalExtension());
         $image_full_name   ='cashier-'.$image_name.'.'.$ext;
         $upload_path       = "images/";
         $image_url         = $upload_path.$image_full_name;
         $success           = $image->move($upload_path,$image_full_name);
         $data['image']     = $image_url;
        }else{
         $data['image']     = '';
        }
        DB::table('admin')->insert($data);
        Session::put('succes','Thanks , Branch Manager Added Sucessfully');
        return Redirect::to('addCashier');
	}
	// manage cashier
	public function manageCashier()
	{
	$result = DB::table('admin')->where('branch_id',$this->branch_id)->where('type',3)->get();
	return view('users.manageCashier')->with('result',$result);
    }
    #------------------------------- ADD DOCTOR -------------------------------------#
    public function addDoctorByCashier()
    {
        return view('users.addDoctorByCashier');
    }
    // add doctor info
    public function addDoctorInfo(Request $request)
    {
    $this->validate($request, [
    'name'              => 'required',
    'specialist'        => 'required',
    'mobile'            => 'required',
    'doctor_type'       => 'required',
    'image'             => 'mimes:jpeg,jpg,png|max:100'
    ]);
     $name                  = trim($request->name);
     $specialist            = trim($request->specialist);
     $father_name           = trim($request->father_name);
     $mobile                = trim($request->mobile);
     $doctor_type           = trim($request->doctor_type);
     $email                 = trim($request->email);
     $nid                   = trim($request->nid);
     $edu_qualification     = trim($request->edu_qualification);
     $address               = trim($request->address);
     #--------------------- DUPLIATE ENTRY CHECK------------------#
     // by mobile number
     $check_count = DB::table('admin')->where('mobile',$mobile)->count();
     if($check_count > 0){
        Session::put('failed','Sorry ! This Mobile Number Already Exits');
        return Redirect::to('addDoctorByCashier');
        exit();
     }
     #-------------------- END DUPLICATE ENTRY CHECK---------------#
     $salt      = 'a123A321';
     $password  = trim(sha1($mobile.$salt));
     $data=array();
     $data['branch_id']       = $this->branch_id ;
     $data['name']            = $name ;
     $data['nid']             = $nid ;
     $data['father_name']     = $father_name ;
     $data['email']           = $email ;
     $data['mobile']          = $mobile ;
     $data['type']            = 4 ;
     $data['password']        = $password;
     $data['address']         = $address;
     $data['status']          = 1;
     $data['added_id']        = $this->loged_id;
     $data['creatd_at']      =  $this->rcdate ;
     $image                  = $request->file('image');
         if($image){
         $image_name        = str_random(20);
         $ext               = strtolower($image->getClientOriginalExtension());
         $image_full_name   ='doctor-'.$image_name.'.'.$ext;
         $upload_path       = "images/";
         $image_url         = $upload_path.$image_full_name;
         $success           = $image->move($upload_path,$image_full_name);
         $data['image']     = $image_url;
        }else{
         $data['image']     = '';
        }
        DB::table('admin')->insert($data);
       // get last doctor id
       $last_id_query = DB::table('admin')->orderBy('id','desc')->limit(1)->first();
       $last_admin_id = $last_id_query->id ;
       // insert data into doctor table
       $data_doctor                 = array();
       $data_doctor['branch_id']    = $this->branch_id ;
       $data_doctor['admin_id']     = $last_admin_id ;
       $data_doctor['doctor_type']  = $doctor_type ;
       $data_doctor['speialist']    = $specialist ;
       $data_doctor['educational_qualification'] = $edu_qualification;
       $data_doctor['added_id']     = $this->loged_id ;
       $data_doctor['created_at']   = $this->rcdate;
       DB::table('tbl_doctor')->insert($data_doctor);
        Session::put('succes','Thanks , Doctor Added Sucessfully');
        return Redirect::to('addDoctorByCashier');
    }
    // manage doctor
    public function manageDoctorByCashier()
    {
        $result = DB::table('admin')->where('branch_id',$this->branch_id)->where('type',4)->get();
        return view('users.manageDoctorByCashier')->with('result',$result);
    }
    #------------------------------ END DOCTOR----------------------------------------#
    #------------------------------ START PC------------------------------------------#
    public function addPCByCashier()
    {
        return view('users.addPCByCashier');
    }
    // add pc info
    public function addPCInfo(Request $request)
    {
     $this->validate($request, [
    'name'              => 'required',
    'mobile'            => 'required',
    'due'               =>  'required', 
    'confirm_due'       =>  'required',
    'image'             => 'mimes:jpeg,jpg,png|max:100',
     'tr_date'          => 'required'
    ]);
     $name                  = trim($request->name);
     $father_name           = trim($request->father_name);
     $mobile                = trim($request->mobile);
     $email                 = trim($request->email);
     $nid                   = trim($request->nid);
     $address               = trim($request->address);
      $due                  = trim($request->due);
     $confirm_due           = trim($request->confirm_due);
     $tr_date               = trim($request->tr_date);
     $trDate                = date('Y-m-d',strtotime($tr_date)) ;
     #--------------------- DUPLIATE ENTRY CHECK------------------#
      #---------------------- DATE VALIDATION----------------------#
     if($trDate > $this->rcdate){
        Session::put('failed','Sorry ! Enter Invalid Date. Please Enter Valid Date And Try Again ');
        return Redirect::to('addPCByCashier');
        exit();
     }
     #--------------------- END DATE VALIDATIN--------------------#
     // check duplicate due amount
     if($due != $confirm_due){
        Session::put('failed','Sorry ! Due And Previous Due Amount Did Not Match');
        return Redirect::to('addPCByCashier');
        exit();
     }

    $check_count1 = DB::table('admin')->where('mobile',$mobile)->count();
     if($check_count1 > 0){
        Session::put('failed','Sorry ! This Mobile Number Already Exits');
        return Redirect::to('addPCByCashier');
        exit();
     }
     // by mobile number
     $check_count = DB::table('tbl_pc')->where('mobile',$mobile)->count();
     if($check_count > 0){
        Session::put('failed','Sorry ! This Mobile Number Already Exits');
        return Redirect::to('addPCByCashier');
        exit();
     }
     #-------------------- END DUPLICATE ENTRY CHECK---------------#
     $salt      = 'a123A321';
     $password  = trim(sha1($mobile.$salt));
    
     $data=array();
     $data['branch_id']       = $this->branch_id ;
     $data['name']            = $name ;
     $data['nid']             = $nid ;
     $data['father_name']     = $father_name ;
     $data['email']           = $email ;
     $data['mobile']          = $mobile ;
     $data['password']        = $password;
     $data['address']         = $address;
     $data['status']          = 1;
     $data['added_id']        = $this->loged_id;
     $data['creatd_at']      =  $this->rcdate ;
     $image                  = $request->file('image');
         if($image){
         $image_name        = str_random(20);
         $ext               = strtolower($image->getClientOriginalExtension());
         $image_full_name   ='pc-'.$image_name.'.'.$ext;
         $upload_path       = "images/";
         $image_url         = $upload_path.$image_full_name;
         $success           = $image->move($upload_path,$image_full_name);
         $data['image']     = $image_url;
        }else{
         $data['image']     = '';
        }
        DB::table('tbl_pc')->insert($data);
        // get last supplier id 
        $pc_id = DB::table('tbl_pc')
             ->orderBy('id', 'desc')->take(1)->first();
             // insert due into supplier due list
             $data1['pc_id']               = $pc_id->id ;
             $data1['total_due_amount']    = $due;
             $data1['created_at']          = $this->rcdate;
             DB::table('pc_due')->insert($data1);
             // supplier ledger previous due
            $dataledger['branch_id']      = $this->branch_id ;
            $dataledger['pc_id']    = $pc_id->id ;
            $dataledger['payable_amount'] = $due ;
            $dataledger['status'] = 0 ;
            $dataledger['purpose'] = 'Previous Due' ;
            $dataledger['added_id'] = $this->loged_id ;
            $dataledger['created_time'] = $this->current_time ;
            $dataledger['created_at'] = $trDate ;
            $dataledger['on_created_at'] = $this->rcdate ;
            DB::table('pc_ledger')->insert($dataledger);
            Session::put('succes','Thanks , PC Added Sucessfully');
            return Redirect::to('addPCByCashier');
    }
    // manage pc
    public function managePcByCashier()
    {
        $result = DB::table('tbl_pc')->where('branch_id',$this->branch_id)->get();
        return view('users.managePcByCashier')->with('result',$result);
    }
    #------------------------------ END PC --------------------------------------------#
    #------------------------------ OT STAFF-------------------------------------------#
    public function addOTStaff()
    {
        return view('users.addOTStaff');

    }
    // add ot staff
    public function addOTstaffInfo(Request $request)
    {
    $this->validate($request, [
    'name'              => 'required',
    'mobile'            => 'required',
    'type'              => 'required',
    'image'             => 'mimes:jpeg,jpg,png|max:100',
    ]);
     $name                  = trim($request->name);
     $father_name           = trim($request->father_name);
     $mobile                = trim($request->mobile);
     $type                  = trim($request->type);
     $email                 = trim($request->email);
     $nid                   = trim($request->nid);
     $address               = trim($request->address);
     #--------------------- DUPLIATE ENTRY CHECK------------------#
     // by mobile number
     $check_count = DB::table('tbl_ot_staff')->where('mobile',$mobile)->count();
     if($check_count > 0){
        Session::put('failed','Sorry ! This Mobile Number Already Exits');
        return Redirect::to('addOTStaff');
        exit();
     }
     #-------------------- END DUPLICATE ENTRY CHECK---------------#
     $salt      = 'a123A321';
     $password  = trim(sha1($mobile.$salt));
    
     $data=array();
     $data['branch_id']       = $this->branch_id ;
     $data['name']            = $name ;
     $data['nid']             = $nid ;
     $data['father_name']     = $father_name ;
     $data['email']           = $email ;
     $data['mobile']          = $mobile ;
     $data['type']            = $type ;
     $data['password']        = $password;
     $data['address']         = $address;
     $data['status']          = 1;
     $data['added_id']        = $this->loged_id;
     $data['creatd_at']      =  $this->rcdate ;
     $image                  = $request->file('image');
         if($image){
         $image_name        = str_random(20);
         $ext               = strtolower($image->getClientOriginalExtension());
         $image_full_name   ='pc-'.$image_name.'.'.$ext;
         $upload_path       = "images/";
         $image_url         = $upload_path.$image_full_name;
         $success           = $image->move($upload_path,$image_full_name);
         $data['image']     = $image_url;
        }else{
         $data['image']     = '';
        }
        DB::table('tbl_ot_staff')->insert($data);
        Session::put('succes','Thanks , OT Staff Added Sucessfully');
        return Redirect::to('addOTStaff');

    }
    // manage ot staff
    public function manageOTStaff()
    {
        $result = DB::table('tbl_ot_staff')->where('branch_id',$this->branch_id)->get();
        return view('users.manageOTStaff')->with('result',$result);
    }

    #------------------------------ END OT STAFF---------------------------------------#

}
