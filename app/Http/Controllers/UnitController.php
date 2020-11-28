<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests;
use DB;
use Session;


class UnitController extends Controller
{
   	 private $rcdate ;
     private $loged_id ;
     private $current_time ;
     private $branch_id ;
     /**
     * UNIT CLASS costructor 
     *
     */
    public function __construct()
    {
        date_default_timezone_set('Asia/Dhaka');
        $this->rcdate       = date('Y-m-d');
        $this->loged_id     = Session::get('admin_id');
        $this->current_time = date("H:i:s");
        $this->branch_id    = Session::get('branch_id');
    }
    /**
     * load unit form
     *
     * @return \Illuminate\Http\Response
     */
    public function addUnit()
    {
     return view('info.addUnit');	
    }

    /**
    * new unit information added .
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
     
    public function addUnitInfo(Request $request)
    {
     $this->validate($request, [
    'name'        => 'required',
    ]);
     $name          	= trim($request->name);
     $remarks        	= trim($request->remarks);
     //check duplicatet branch name
     $count = DB::table('unit')
     ->where('branch_id', $this->branch_id)
     ->where('unit_name', $name)
     ->count();
      if($count > 0){
        Session::put('failed','Sorry ! '.$name. ' Unit Already Exits. Try To Add New Unit');
        return Redirect::to('addUnit');
        exit();
      }
     $data=array();
     $data['branch_id']     = $this->branch_id;
     $data['unit_name']     = $name;
     $data['remarks']       = $remarks;
     $data['created_at']    = $this->rcdate ;
     $query = DB::table('unit')->insert($data);
     if($query){
        Session::put('succes','Thanks , Unit Added Sucessfully');
        return Redirect::to('addUnit');
    }else{
        Session::put('failed','Sorry ! Error Occued. Try Again');
        return Redirect::to('addUnit');
    }
    }
     /**
     * Display the all unit.
     *
     * @return \Illuminate\Http\Response
     */
    public function manageUnit()
    {
       $result = DB::table('unit')->where('branch_id',$this->branch_id)->get();
       return view('info.manageUnit')->with('result',$result);
    }
}
