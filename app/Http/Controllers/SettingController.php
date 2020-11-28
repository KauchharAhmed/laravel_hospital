<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests;
use DB;
use Session;

class SettingController extends Controller
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
   // get opd setting
    public function opdSetting()
    {
    	$count  = DB::table('tbl_opd_setting')->where('branch_id',$this->branch_id)->count();
    	$result = DB::table('tbl_opd_setting')->where('branch_id',$this->branch_id)->first();
    	return view('setting.opdSetting')->with('count',$count)->with('result',$result);
    }
    // opd setting info
    public function opdSettingInfo(Request $request)
    {
    $this->validate($request, [
    'opd_status'            => 'required'
    ]);
     $opd_status            = trim($request->opd_status);
     // get opd status count
     $count = DB::table('tbl_opd_setting')->where('branch_id',$this->branch_id)->count();
     if($count == '0'){
     	// insert query
     	$data_insert     = array();
     	$data_insert['branch_id']       = $this->branch_id ;
     	$data_insert['current_status'] = $opd_status ;
     	$data_insert['created_at']      = $this->rcdate ;
     	DB::table('tbl_opd_setting')->insert($data_insert);
     	// insset tbl_opd_setting_history
     	$data_insert     = array();
     	$data_insert['branch_id']       = $this->branch_id ;
     	$data_insert['status']         = $opd_status ;
     	$data_insert['created_time']   = $this->current_time ;
     	$data_insert['created_at']      = $this->rcdate ;
     	DB::table('tbl_opd_setting_history')->insert($data_insert);

     }else{
     	$data_update = array();
        $data_update['current_status'] = $opd_status;
        DB::table('tbl_opd_setting')->where('branch_id',$this->branch_id)->update($data_update);
         $data_insert     = array();
     	$data_insert['branch_id']       = $this->branch_id ;
     	$data_insert['status']         = $opd_status ;
     	$data_insert['created_time']   = $this->current_time ;
     	$data_insert['created_at']      = $this->rcdate ;
     	DB::table('tbl_opd_setting_history')->insert($data_insert);
     }
        Session::put('succes','Thanks , OPD Setting Chanage Sucessfully');
        return Redirect::to('opdSetting');

    }
    // manager delete setting
    public function managerDeleteSetting()
    {
        $count  = DB::table('tbl_manager_delete_setting')->where('branch_id',$this->branch_id)->count();
        $result = DB::table('tbl_manager_delete_setting')->where('branch_id',$this->branch_id)->first();
        return view('setting.managerDeleteSetting')->with('count',$count)->with('result',$result);
    }
    // manager delete setting info
    public function managerDeleteSettingInfo(Request $request)
    {
    $this->validate($request, [
    'opd_status'            => 'required'
    ]);
     $opd_status            = trim($request->opd_status);
     // get opd status count
     $count = DB::table('tbl_manager_delete_setting')->where('branch_id',$this->branch_id)->count();
     if($count == '0'){
        // insert query
        $data_insert     = array();
        $data_insert['branch_id']       = $this->branch_id ;
        $data_insert['current_status'] = $opd_status ;
        $data_insert['created_at']      = $this->rcdate ;
        DB::table('tbl_manager_delete_setting')->insert($data_insert);
        // insset tbl_opd_setting_history
        $data_insert     = array();
        $data_insert['branch_id']       = $this->branch_id ;
        $data_insert['status']          = $opd_status ;
        $data_insert['added_id']        = $this->loged_id ;
        $data_insert['created_time']    = $this->current_time ;
        $data_insert['created_at']      = $this->rcdate ;
        DB::table('tbl_manager_delete_setting_history')->insert($data_insert);

     }else{
        $data_update = array();
        $data_update['current_status'] = $opd_status;
        DB::table('tbl_manager_delete_setting')->where('branch_id',$this->branch_id)->update($data_update);
         $data_insert     = array();
        $data_insert['branch_id']       = $this->branch_id ;
        $data_insert['status']         = $opd_status ;
        $data_insert['added_id']        = $this->loged_id ;
        $data_insert['created_time']   = $this->current_time ;
        $data_insert['created_at']      = $this->rcdate ;
        DB::table('tbl_manager_delete_setting_history')->insert($data_insert);
     }
        Session::put('succes','Thanks , Cashier Delete Transaction Setting Chanage Sucessfully');
        return Redirect::to('managerDeleteSetting');
    }
    // admin delete setting
    public function adminDeleteSetting()
    {
        $branch  = DB::table('branch')->get();
        return view('setting.adminDeleteSetting')->with('branch',$branch); 
    }
    // change admin delete status
    public function changeAdminDeleteStatus($branch_id)
    {
      $count  = DB::table('tbl_admin_delete_setting')->where('branch_id',$branch_id)->count();
      $result = DB::table('tbl_admin_delete_setting')->where('branch_id',$branch_id)->first();
      return view('setting.changeAdminDeleteStatus')->with('count',$count)->with('result',$result)->with('branch_id',$branch_id);
    }
    // admin delete setting info
    public function adminDeleteSettingInfo(Request $request)
    {
    $this->validate($request, [
    'opd_status'            => 'required',
    'branch_id'             => 'required'
    ]);
     $opd_status            = trim($request->opd_status);
     $branch_id             = trim($request->branch_id);
     // get opd status count
     $count = DB::table('tbl_admin_delete_setting')->where('branch_id',$branch_id)->count();
     if($count == '0'){
        // insert query
        $data_insert     = array();
        $data_insert['branch_id']       = $branch_id ;
        $data_insert['current_status'] = $opd_status ;
        $data_insert['created_at']      = $this->rcdate ;
        DB::table('tbl_admin_delete_setting')->insert($data_insert);
        // insset tbl_opd_setting_history
        $data_insert     = array();
        $data_insert['branch_id']       = $branch_id ;
        $data_insert['status']          = $opd_status ;
        $data_insert['added_id']        = $this->loged_id ;
        $data_insert['created_time']    = $this->current_time ;
        $data_insert['created_at']      = $this->rcdate ;
        DB::table('tbl_admin_delete_setting_history')->insert($data_insert);

     }else{
        $data_update = array();
        $data_update['current_status'] = $opd_status;
        DB::table('tbl_admin_delete_setting')->where('branch_id',$branch_id )->update($data_update);
         $data_insert     = array();
        $data_insert['branch_id']       = $branch_id ;
        $data_insert['status']         = $opd_status ;
        $data_insert['added_id']        = $this->loged_id ;
        $data_insert['created_time']   = $this->current_time ;
        $data_insert['created_at']      = $this->rcdate ;
        DB::table('tbl_admin_delete_setting_history')->insert($data_insert);
     }
        Session::put('succes','Thanks , Manager Delete Transaction Setting Chanage Sucessfully');
        return Redirect::to('adminDeleteSetting');
    }
}
