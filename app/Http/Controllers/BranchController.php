<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests;
use DB;
use Session;

class BranchController extends Controller
{
	 private $rcdate ;
     private $current_time ;
     private $loged_id ;
     private $branch_id ;
     /**
     * BRANCH CLASS costructor 
     *
     */
    public function __construct()
    {

    date_default_timezone_set('Asia/Dhaka');
    $this->rcdate         = date('Y-m-d');
    $this->current_time = date('H:i:s');
    $this->loged_id     = Session::get('admin_id');
    $this->branch_id    = Session::get('branch_id');
    }
    /**
     * load barnch form
     *
     * @return \Illuminate\Http\Response
     */
    public function addBranch()
    {
     return view('info.addBranch');	
    }
    /**
    * new brach information added .
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function addBrancInfo(Request $request)
    {
    $this->validate($request, [
    'name'                    => 'required',
    'pettycash'               => 'required',
    'confirm_pettycash'       => 'required',
    'vat'                     => 'required',
    'tr_date'                 => 'required'
    ]);
     $name              = trim($request->name);
     $mobile            = trim($request->mobile);
     $pettycash         = trim($request->pettycash);
     $confirm_pettycash = trim($request->confirm_pettycash);
     $mobile            = trim($request->mobile);
     $address           = trim($request->address);
     $vat               = trim($request->vat);
     $tr_date           = trim($request->tr_date);
     $trDate            = date('Y-m-d',strtotime($tr_date)) ;
    #---------------------- DATE VALIDATION----------------------#
     if($trDate > $this->rcdate){
        Session::put('failed','Sorry ! Enter Invalid Date. Please Enter Valid Date And Try Again ');
        return Redirect::to('addBranchManager');
        exit();
     }
     #--------------------- END DATE VALIDATIN--------------------#
     // check pettycash and confirm petty cash
     if($pettycash != $confirm_pettycash){
      Session::put('failed','Sorry ! Pettycash And Confirm Pettycahs Did Not Match. Try Again');
        return Redirect::to('addBranch');  
        exit();
     }
     //check duplicatet branch name
     $count = DB::table('branch')
     ->where('name', $name)
     ->count();
      if($count > 0){
        Session::put('failed','Sorry ! '.$name. ' Branch Name Already Exits. Try To Add New Branch');
        return Redirect::to('addBranch');
        exit();
      }
     $data=array();
     $data['name']          = $name;
     $data['mobile']        = $mobile;
     $data['address']       = $address;
     $data['vat']           = $vat;
     $data['start_date']    = $trDate;
     $data['creatd_at']     = $this->rcdate ;
     $query = DB::table('branch')->insert($data);
        // get last branch id to add petty cash
        $last_id  = DB::table('branch')->orderBy('id','desc')->first();;
        $last_idd = $last_id->id ;
        // insert pettycash table
        $data1 = array();
        $data1['branch_id']        = $last_idd ;
        $data1['type']             = 2 ;
        $data1['pettycash_amount'] = $pettycash ;
        $data1['created_at']       = $this->rcdate ;
        $query1 = DB::table('pettycash')->insert($data1);
        // insert into cahsbook opening balance
        // status 2 = branch opening balance
        $data_cashbook                        = array();
        $data_cashbook['overall_branch_id']   = $last_idd ;
        $data_cashbook['branch_id']           = $last_idd ;
        $data_cashbook['admin_id']            = '' ;
        $data_cashbook['admin_type']          = 2 ;
        $data_cashbook['earn']                = $pettycash ;
        $data_cashbook['status']              = 2 ;
        $data_cashbook['tr_status']           = 1 ;
        $data_cashbook['purpose']             = 'Branch Managers Opening Balance';
        $data_cashbook['added_id']            = $this->loged_id;
        $data_cashbook['created_time']        = $this->current_time;
        $data_cashbook['created_at']          = $trDate;
        $data_cashbook['on_created_at']       = $this->rcdate;
        DB::table('cashbook')->insert($data_cashbook);
        Session::put('succes','Thanks , Branch Added Sucessfully');
        return Redirect::to('addBranch');
        

    }
     /**
     * Display the all branch.
     *
     * @return \Illuminate\Http\Response
     */
    public function manageBranch()
    {
       $result = DB::table('branch')->get();
       return view('info.manageBranch')->with('result',$result);
    }

}
