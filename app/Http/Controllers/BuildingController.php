<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests;
use DB;
use Session;

class BuildingController extends Controller
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
    // add building
    public function addBuilding()
    {
    	return view('building.addBuilding');
    }
    // add building info
    public function addBuildingInfo(Request $request)
    {
    $this->validate($request, [
    'name'                    => 'required'
    ]);
     $name              = trim($request->name);
     $remarks           = trim($request->address);
     // duplicate check
     $count = DB::table('tbl_building')->where('branch_id',$this->branch_id)->where('building_name',$name)->count();
     if($count > 0){
     	Session::put('failed','Sorry ! Building Name Already Added. Try Again');
        return Redirect::to('addBuilding');  
        exit();    
    }
    $data     				= array();
    $data['branch_id']  	= $this->branch_id ;
    $data['building_name']  = $name ;
    $data['remarks']        = $remarks ;
    $data['created_at']     = $this->rcdate ;
    DB::table('tbl_building')->insert($data);
    Session::put('succes','Thanks , Building Added Sucessfully');
    return Redirect::to('addBuilding');
     }
    // manage building
    public function manageBuilding()
    {
      $result = DB::table('tbl_building')->where('branch_id',$this->branch_id)->get();
      return view('building.manageBuilding')->with('result',$result);
    }
    // add building floor
    public function addBuildingFloor()
    {
    	// get all building of this branch
    	$building = DB::table('tbl_building')->where('branch_id',$this->branch_id)->get();
    	return view('building.addBuildingFloor')->with('building',$building);
    }
    // add building floor info
    public function addBuildingFloorInfo(Request $request)
    {
    $this->validate($request, [
    'building_id'             => 'required',
    'name'                    => 'required'
    ]);
     $building_id       = trim($request->building_id);
     $name              = trim($request->name);
     $remarks           = trim($request->remarks);
     // duplicate check
     $count = DB::table('tbl_building_floor')->where('branch_id',$this->branch_id)->where('building_id',$building_id)->where('floor_name',$name)->count();
     if($count > 0){
     	Session::put('failed','Sorry ! Floor No Already Added. Try Again');
        return Redirect::to('addBuildingFloor');  
        exit();    
    }
    $data     				= array();
    $data['branch_id'] 		= $this->branch_id ;
    $data['building_id'] 	= $building_id ;
    $data['floor_name'] 	= $name ;
    $data['remarks'] 		= $remarks ;
    $data['added_id'] 		= $this->loged_id ;
    $data['created_at'] 	= $this->rcdate ;
    DB::table('tbl_building_floor')->insert($data);
    Session::put('succes','Thanks , New Floor Added Sucessfully');
    return Redirect::to('addBuildingFloor');
    }
    // manage building floor
    public function manageBuildingFloor()
    {
      // join  query
    $result = DB::table('tbl_building_floor')
    ->join('tbl_building', 'tbl_building_floor.building_id', '=', 'tbl_building.id')
    ->select('tbl_building_floor.*','tbl_building.building_name')
    ->where('tbl_building_floor.branch_id',$this->branch_id)
    ->get();
     return view('building.manageBuildingFloor')->with('result',$result);
    }
    // add cabin type
    public function addCabinType()
    {
      return view('building.addCabinType');  
    }
    // add cabin type info
    public function addCabinTypeInfo(Request $request)
    {
    $this->validate($request, [
    'name'               => 'required',
    'amount'             => 'required',
    'confirm_amount'     => 'required',
    ]);
     $name              = trim($request->name);
     $amount            = trim($request->amount);
     $confirm_amount    = trim($request->confirm_amount);
     $remarks           = trim($request->remarks);
    if($amount != $confirm_amount){
        Session::put('failed','Sorry ! Charge Amount And Confirm Charge Amount Did Not Match');
        return Redirect::to('addCabinType');
        exit();
     }
     // duplicate check
     $count = DB::table('tbl_cabin_type')->where('branch_id',$this->branch_id)->where('cabin_type_name',$name)->count();
     if($count > 0){
        Session::put('failed','Sorry ! Cabin Type Alredy Added');
        return Redirect::to('addCabinType');  
        exit();
     }
     $data                    = array();
     $data['branch_id']       = $this->branch_id ;
     $data['cabin_type_name'] = $name ;
     $data['charge_amount']   = $amount ;
     $data['remarks']         = $remarks ;
     $data['added_id']        = $this->loged_id ;
     $data['created_at']      = $this->rcdate ;
     DB::table('tbl_cabin_type')->insert($data);
    Session::put('succes','Thanks , New Cabin Type Added Sucessfully');
    return Redirect::to('addCabinType');
    }
    // manage cabin type
    public function manageCabinType()
    {
        $result = DB::table('tbl_cabin_type')->where('branch_id',$this->branch_id)->get();
        return view('building.manageCabinType')->with('result',$result);
    }
    // add cabin rooom
    public function addCabinRoom()
    {
         // building
        $building = DB::table('tbl_building')->where('branch_id',$this->branch_id)->get();
        // cabin type
        $cabin_type = DB::table('tbl_cabin_type')->where('branch_id',$this->branch_id)->get();
        return view('building.addCabinRoom')->with('cabin_type',$cabin_type)->with('building',$building);
    }

    // get floor by building id
    public function getFloorByBuildingId(Request $request)
    {
      $building_id = trim($request->building_id);
      $query = DB::table('tbl_building_floor')->where('branch_id',$this->branch_id)->where('building_id',$building_id)->get();
      echo "<option value=''>Select Floor </option>";
      foreach ($query as $value) {
          echo "<option value=".$value->id.">".$value->floor_name."</option>";
      }
    }
    // add cabin room info
    public function addCabinRoomInfo(Request $request)
    {
     $this->validate($request, [
    'building_id'      => 'required',
    'floor_id'        => 'required',
    'cabin_type_id'    => 'required',
    'name'             => 'required'
    ]);
     $building_id       = trim($request->building_id);
     $floor_id          = trim($request->floor_id);
     $name              = trim($request->name);
     $cabin_type_id     = trim($request->cabin_type_id);
     $remarks           = trim($request->remarks);
     // check duplicate room number
     $count = DB::table('tbl_cabin_room')->where('branch_id',$this->branch_id)->where('building_id',$building_id)->where('room_no',$name)->count();
     if($count > '0'){
        Session::put('failed','Sorry ! Room Number Already Exits');
        return Redirect::to('addCabinRoom');
        exit();
     }
    $data                   = array();
    $data['branch_id']      = $this->branch_id;
    $data['building_id']    = $building_id ;
    $data['floor_id']       = $floor_id ;
    $data['cabin_type_id']  = $cabin_type_id ;
    $data['room_no']        = $name ;
    $data['remarks']        = $remarks;
    $data['created_at']     = $this->rcdate ;
    DB::table('tbl_cabin_room')->insert($data);
    Session::put('succes','Thanks , New Cabin Room Added Sucessfully');
    return Redirect::to('addCabinRoom');
    }
    // manage cabin room
    public function manageCabinRoom()
    {
    $result =  DB::table('tbl_cabin_room')
    ->join('tbl_building', 'tbl_cabin_room.building_id', '=', 'tbl_building.id')
    ->join('tbl_building_floor', 'tbl_cabin_room.floor_id', '=', 'tbl_building_floor.id')
    ->join('tbl_cabin_type', 'tbl_cabin_room.cabin_type_id', '=', 'tbl_cabin_type.id')
    ->select('tbl_cabin_room.*','tbl_building.building_name','tbl_building_floor.floor_name','tbl_cabin_type.cabin_type_name')
    ->where('tbl_cabin_room.branch_id',$this->branch_id)
    ->get();
    return view('building.manageCabinRoom')->with('result',$result);
    }
    // add ward
    public function addWard()
    {
      $building = DB::table('tbl_building')->where('branch_id',$this->branch_id)->get();
      return view('building.addWard')->with('building',$building);    
    }
    // add ward info
    public function addWardInfo(Request $request)
    {
    $this->validate($request, [
    'building_id'      => 'required',
    'floor_id'         => 'required',
    'name'             => 'required',
    'nic_name'         => 'required',
    'bed_number'       => 'required',
    'amount'           => 'required',
    'confirm_amount'   => 'required' 
    ]);
     $building_id       = trim($request->building_id);
     $floor_id          = trim($request->floor_id);
     $name              = trim($request->name);
     $nic_name          = trim($request->nic_name);
     $bed_number        = trim($request->bed_number);
     $amount            = trim($request->amount);
     $confirm_amount    = trim($request->confirm_amount);
     $remarks           = trim($request->remarks);
     // duplicate check
     $count = DB::table('tbl_ward')->where('branch_id',$this->branch_id)->where('building_id',$building_id)->where('ward_number',$name)->count();
     if($count > 0){
        Session::put('failed','Sorry ! Ward Number Already Exits');
        return Redirect::to('addWard');
        exit();
     }
     if($amount != $confirm_amount){
        Session::put('failed','Sorry ! Charge Amount And Confirm Charge Amount Did Not Match');
        return Redirect::to('addWard');
        exit();
     }
     //data insert into ward table
     $data                  = array();
     $data['branch_id']     = $this->branch_id ;
     $data['building_id']   = $building_id ;
     $data['floor_id']      = $floor_id ;
     $data['ward_number']   = $name ;
     $data['nic_name']      = $nic_name ;
     $data['remarks']       = $remarks ;
     $data['creatd_at']     = $this->rcdate ;
     DB::table('tbl_ward')->insert($data);
     // get last ward number
     $ward_query   = DB::table('tbl_ward')->orderBy('id','desc')->limit(1)->first();
     $last_ward_id = $ward_query->id ;
    // insert class test list info
      for ($bed_numbers = 1;  $bed_numbers<= $bed_number; $bed_numbers++) {
    // insert data class test
         $count1 = DB::table('tbl_ward_bed')->where('branch_id',$this->branch_id)->count();
             if($count1 == '0'){
              $global_ward_no = 0 ;
             }else{
              $global_ward_info =DB::table('tbl_ward_bed')->where('branch_id',$this->branch_id)->orderBy('global_bed_number','desc')->first();
              $global_ward_no = $global_ward_info->global_bed_number;
             }
          $global_bed_number                  = $global_ward_no + 1 ;
          $data_bed_list                      = array();
          $data_bed_list['branch_id']         = $this->branch_id ;
          $data_bed_list['building_id']       = $building_id;
          $data_bed_list['floor_id']          = $floor_id ;
          $data_bed_list['ward_id']           = $last_ward_id ;
          $data_bed_list['global_bed_number'] = $global_bed_number ;
          $data_bed_list['bed_no']            = $bed_numbers ;
          $data_bed_list['charge_amount']     = $amount ;
          $data_bed_list['created_at']        = $this->rcdate;
          DB::table('tbl_ward_bed')->insert($data_bed_list);
         }
        Session::put('succes','Thanks , New Ward Added Sucessfully');
        return Redirect::to('addWard');

    }
    // manage ward
    public function manageWard()
    {
     $result =  DB::table('tbl_ward')
    ->join('tbl_building', 'tbl_ward.building_id', '=', 'tbl_building.id')
    ->join('tbl_building_floor', 'tbl_ward.floor_id', '=', 'tbl_building_floor.id')
    ->select('tbl_ward.*','tbl_building.building_name','tbl_building_floor.floor_name')
    ->where('tbl_ward.branch_id',$this->branch_id)
    ->get();
    return view('building.manageWard')->with('result',$result);  
    }
    // get avaiable room for ipd admission
    public function getAvailableRoomForIpdAdmission(Request $request)
    {
    $cabin_type = trim($request->cabin_type);
    $result =  DB::table('tbl_cabin_room')
    ->join('tbl_building', 'tbl_cabin_room.building_id', '=', 'tbl_building.id')
    ->join('tbl_building_floor', 'tbl_cabin_room.floor_id', '=', 'tbl_building_floor.id')
    ->join('tbl_cabin_type', 'tbl_cabin_room.cabin_type_id', '=', 'tbl_cabin_type.id')
    ->select('tbl_cabin_room.*','tbl_building.building_name','tbl_building_floor.floor_name','tbl_cabin_type.cabin_type_name')
    ->where('tbl_cabin_room.branch_id',$this->branch_id)
    ->where('tbl_cabin_room.status',0)
    ->where('tbl_cabin_room.booked_status',0)
    ->where('tbl_cabin_room.cabin_type_id',$cabin_type)
    ->get();
    echo "<option value=''>Select Cabin Room</option>";
    foreach ($result as $value) {
        echo "<option value=".$value->id.">".$value->building_name." - ".$value->floor_name." - ".$value->room_no."</option>";
        
    }

    }
    // get availaable bed for ipd admission
    public function getAvailableWardForIpdAdmission(Request $request)
    {
    $ward_no = trim($request->ward_no);
    // get bed name of this ward
    $row = DB::table('tbl_ward')->where('branch_id',$this->branch_id)->where('id',$ward_no)->first();;
    $result =  DB::table('tbl_ward_bed')
    ->where('branch_id',$this->branch_id)
    ->where('ward_id',$ward_no)
    ->where('status',0)
    ->where('booked_status',0)
    ->get();
    echo "<option value=''>Select Bed</option>";
    foreach ($result as $value) {
        echo "<option value=".$value->id.">".$row->nic_name." - ".$value->bed_no."</option>";
        
    } 
    }
 

}
