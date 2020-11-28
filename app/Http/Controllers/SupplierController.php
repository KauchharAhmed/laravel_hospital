<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests;
use DB;
use Session;

class SupplierController extends Controller
{
    private $rcdate ;
    private $current_time ;
    private $loged_id ;
    private $branch_id ;
    public function __construct() {
    date_default_timezone_set('Asia/Dhaka');
    $this->rcdate         = date('Y-m-d');
    $this->current_time = date('H:i:s');
    $this->loged_id     = Session::get('admin_id');
    $this->branch_id    = Session::get('branch_id');
    }
    /**
     * load supplier form
     *
     * @return \Illuminate\Http\Response
     */
    public function addSupplier()
    {
     // with branch
     return view('users.addSupplier') ;	
    }
    /**
    * new supplier information added .
    * with current due
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function addSupplierInfo(Request $request)
    {
    $this->validate($request, [
    'name'     			=> 'required',
    'mobile'            => 'required',
    'address'      		=> 'required',
    'due'				=>	'required',	
    'confirm_due'       =>  'required',
     'image'         	=>  'mimes:jpeg,jpg,png|max:100',
     'tr_date'          => 'required'
    ]);
     $name       			= trim($request->name);
     $mobile          		= trim($request->mobile);
     $email        			= trim($request->email);
     $contact_person_name   = trim($request->contact_person_name);
     $contact_person_mobile = trim($request->contact_person_mobile);
     $address     			= trim($request->address);
     $remarks        		= trim($request->remarks);
     $due        			= trim($request->due);
     $confirm_due           = trim($request->confirm_due);
     $tr_date               = trim($request->tr_date);
     $trDate                = date('Y-m-d',strtotime($tr_date)) ;
    #---------------------- DATE VALIDATION----------------------#
     if($trDate > $this->rcdate){
        Session::put('failed','Sorry ! Enter Invalid Date. Please Enter Valid Date And Try Again ');
        return Redirect::to('addSupplier');
        exit();
     }
     #--------------------- END DATE VALIDATIN--------------------#
     // check duplicate due amount
     if($due != $confirm_due){
        Session::put('failed','Sorry ! Due And Previous Due Amount Did Not Match');
        return Redirect::to('addSupplier');
        exit();
     }
     //check duplicate supplier name
     $count = DB::table('supplier')
     ->where('mobile',$mobile)
     ->count();
     if($count > 0){
     	Session::put('failed','Sorry ! Supplier Already Exits. Try Again To Add New Supplier');
        return Redirect::to('addSupplier');
     	exit();
     }
     $data=array();
     $data['branch_id']             = $this->branch_id ;
     $data['supplier_name']    		= $name ;
     $data['mobile']    			= $mobile ;
     $data['email']    				= $email ;
     $data['contact_person_name']   = $contact_person_name;
     $data['contact_mobile']    	= $contact_person_mobile;
     $data['status']   				= '';
     $data['address']    			= $address;
     $data['supplier_due']          = $due ;
     $data['remarks']       		= $remarks ;
     $data['added_id']       		= $this->loged_id ;
     $data['created_at']    		= $trDate ;
     $data['on_created_at']         = $this->rcdate ;
     $image                   		= $request->file('image');
         if($image){
         $image_name        = str_random(20);
         $ext               = strtolower($image->getClientOriginalExtension());
         $image_full_name   ='supplier-'.$image_name.'.'.$ext;
         $upload_path       = "images/";
         $image_url         = $upload_path.$image_full_name;
         $success           = $image->move($upload_path,$image_full_name);
         if($success){
         	// with image
             $data['image'] = $image_url;
             DB::table('supplier')->insert($data);
             // get last supplier id 
             $supplier_id = DB::table('supplier')
             ->orderBy('id', 'desc')->take(1)->first();
             // insert due into supplier due list
             $data1['supplier_id'] 		   = $supplier_id->id ;
             $data1['total_due_amount']    = $due;
             $data1['created_at']  		   = $this->rcdate;
             DB::table('supplier_due')->insert($data1);
             // supplier ledger previous due
            $dataledger['branch_id']    = $this->branch_id ;
            $dataledger['supplier_id']    = $supplier_id->id ;
            $dataledger['payable_amount'] = $due ;
            $dataledger['status'] = 0 ;
            $dataledger['purpose'] = 'Previous Due' ;
            $dataledger['added_id'] = $this->loged_id ;
            $dataledger['created_time'] = $this->current_time ;
            $dataledger['created_at'] = $trDate ;
            $dataledger['on_created_at'] = $this->rcdate ;
            DB::table('payment_ledger')->insert($dataledger);
             Session::put('succes','New Supplier Added Sucessfully');
             return Redirect::to('addSupplier');
        }
     }else{
     	// without image
             DB::table('supplier')->insert($data);
             // get last supplier id 
             $supplier_id = DB::table('supplier')
             ->orderBy('id', 'desc')->take(1)->first();
             // insert due into supplier due list
             $data1['supplier_id'] 		   = $supplier_id->id ;
             $data1['total_due_amount']    = $due;
             $data1['created_at']  		   = $this->rcdate;
             DB::table('supplier_due')->insert($data1);
            // supplier ledger previous due
            $dataledger['branch_id']      = $this->branch_id ;
            $dataledger['supplier_id']    = $supplier_id->id ;
            $dataledger['payable_amount'] = $due ;
            $dataledger['status'] = 0 ;
            $dataledger['purpose'] = 'Previous Due' ;
            $dataledger['added_id'] = $this->loged_id ;
            $dataledger['created_time'] = $this->current_time ;
            $dataledger['created_at'] = $trDate ;
            $dataledger['on_created_at'] = $this->rcdate ; 
            DB::table('payment_ledger')->insert($dataledger);
             Session::put('succes','New Supplier Added Sucessfully');
             return Redirect::to('addSupplier');
    }

    }
     /**
     * Display the all supplier.
     *
     * @return \Illuminate\Http\Response
     */
    public function manageSupplier()
    {
       $result = DB::table('supplier')->get();
       return view('user.manageSupplier')->with('result',$result);
    }
 
}
