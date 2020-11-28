<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests;
use DB;
use Session;

class IpdController extends Controller
{
  private $rcdate ;
	private $current_time ;
	private $loged_id ;
	private $branch_id ;
	private $current_year ;
  public function __construct() {
	date_default_timezone_set('Asia/Dhaka');
	$this->rcdate 		= date('Y-m-d');
	$this->current_time = date('H:i:s');
  $this->loged_id     = Session::get('admin_id');
  $this->branch_id    = Session::get('branch_id');
  $this->current_year = date('Y');
	}
	// add ipd admission fee amount by manager
	public function addIPDAdmissinFeeAmount()
	{
		return view('ipd.addIPDAdmissinFeeAmount');
	}
	// add ipd admission fee
	public function addIpdAdmissionFee(Request $request)
	{
    $this->validate($request, [
    'fee_amount'        		=> 'required',
    'confirm_fee_amount'        => 'required',
    ]);
     $fee_amount          	= trim($request->fee_amount);
     $confirm_fee_amount    = trim($request->confirm_fee_amount);
     $remarks        	    = trim($request->remarks);
     if($fee_amount != $confirm_fee_amount){
     	Session::put('failed','Sorry ! Fee Amount And Confirm Fee Amount Did Not Match. Try Again');
        return Redirect::to('addIPDAdmissinFeeAmount');
     	exit();
     }
     // count duplicate entry
     $count = DB::table('tbl_ipd_admission_fee')->where('branch_id',$this->branch_id)->count();
     if($count > 0){
     	Session::put('failed','Sorry ! Sorry IPD Admission Fee Already Added. Try Again');
     	return Redirect::to('addIPDAdmissinFeeAmount');
     	exit();
     }
     // inser ipd fee
     $data 									= array();
     $data['branch_id'] 					= $this->branch_id ;
     $data['admission_fee_amount'] 			= $fee_amount ;
     $data['remarks'] 		    			= $remarks ;
     $data['created_at'] 		    		= $this->rcdate ;
     DB::table('tbl_ipd_admission_fee')->insert($data);
    Session::put('succes','Thanks , IPD Admission Fee Added Sucessfully');
    return Redirect::to('addIPDAdmissinFeeAmount');
	}
    // manage ipd fee amount
    public function manageIPDAdmissinFeeAmount()
    {
        // get reuslt
        $result = DB::table('tbl_ipd_admission_fee')->where('branch_id',$this->branch_id)->get();
        return view('ipd.manageIPDAdmissinFeeAmount')->with('result',$result);
    }
	// ipd admission
	public function ipdAdmission()
	{
    // get ipd amount
    $ipd_fee    = DB::table('tbl_ipd_admission_fee')->where('branch_id',$this->branch_id)->first();
    // cabin type
    $cabin_type = DB::table('tbl_cabin_type')->where('branch_id',$this->branch_id)->get();
    // ward
     $ward =  DB::table('tbl_ward')
    ->join('tbl_building', 'tbl_ward.building_id', '=', 'tbl_building.id')
    ->join('tbl_building_floor', 'tbl_ward.floor_id', '=', 'tbl_building_floor.id')
    ->select('tbl_ward.*','tbl_building.building_name','tbl_building_floor.floor_name')
    ->where('tbl_ward.branch_id',$this->branch_id)
    ->get();
    $patient = DB::table('tbl_patient')->where('branch_id',$this->branch_id)->orderBy('id','desc')->get();
	return view('ipd.ipdAdmission')->with('ipd_fee',$ipd_fee)->with('cabin_type',$cabin_type)->with('ward',$ward)->with('patient',$patient);
	}
  // add ipd service
  public function addIPDService()
  {
    $count = DB::table('tbl_ipd_service')->where('branch_id',$this->branch_id)->count();
    if($count == '0'){
     $service_code = '' ;
    }else{
    $query    = DB::table('tbl_ipd_service')->orderBy('service_code','desc')->limit(1)->first();
    $service_code = $query->service_code + 1 ;
    }
    $service_unit = DB::table('unit')->get();

    return view('ipd.addIPDService')->with('service_code',$service_code)->with('service_unit',$service_unit);

  }
  // patient ipd information
  public function addIpdServiceInfo(Request $request)
  {
    $this->validate($request, [
    'name'                  => 'required',
    'service_code'          => 'required',
    'unit'                  => 'required',
    'service_price'         => 'required',
    'confirm_service_price' => 'required',
    'service_price'         => 'required',
    'confirm_service_price' => 'required'
    ]);
     $name                  = trim($request->name);
     $service_code          = trim($request->service_code);
     $unit                  = trim($request->unit);
     $service_price         = trim($request->service_price);
     $confirm_service_price = trim($request->confirm_service_price);
     $remarks               = trim($request->remarks);
     // test price and confirm test price did not match
     if($service_price != $confirm_service_price)
     {
        Session::put('failed','Sorry ! Service Price And Confirm Service Price Did Not Match');
        return Redirect::to('addIPDService');
        exit();
     }
     #------------------------- duplicate test name / code check -------------------------------#
     $count = DB::table('tbl_ipd_service')->where('branch_id',$this->branch_id)->where('service_name',$name)->count();
     if($count > 0){
      Session::put('failed','Sorry ! Service Name Already Exits');
      return Redirect::to('addIPDService');
      exit();
     }
     // test code count
     $count1 = DB::table('tbl_ipd_service')->where('branch_id',$this->branch_id)->where('service_code',$service_code)->count();
     if($count1 > 0){
      Session::put('failed','Sorry ! Service Code Already Exits');
      return Redirect::to('addIPDService');
      exit();
     }
    #------------------------- end duplicate test name / code check ---------------------------#
    #------------------------- insert into test table -----------------------------------------#
    $data                    = array();
    $data['branch_id']       = $this->branch_id ;
    $data['service_name']    = $name ;
    $data['service_code']    = $service_code ;
    $data['service_price']   = $service_price ;
    $data['unit']            = $unit ;
    $data['remarks']         = $remarks ;
    $data['added_id']        = $this->loged_id ;
    $data['created_at']      = $this->rcdate ;
    DB::table('tbl_ipd_service')->insert($data);
    Session::put('succes','Thanks , New IPD Service Added Sucessfully');
    return Redirect::to('addIPDService');

  }
  // manage ipd service
  public function manageIPDService()
  {
    $result = DB::table('tbl_ipd_service')->where('branch_id',$this->branch_id)->orderBy('id','desc')->get();
    return view('ipd.manageIPDService')->with('result',$result);
  }

    // patient ipd admission
    public function patientIpdAdmission(Request $request)
    {
    $this->validate($request, [
    'bill_date'                => 'required',
    'patient_id'               => 'required',
    'room_type'                => 'required',
    'paid_amount'              => 'required',
    'confirm_paid_amount'      => 'required',
    'admit_time'               => 'required',
    'admission_fee_amount'     => 'required',
    ]);
     $branch                = $this->branch_id ;
     $bill_date             = trim($request->bill_date);
     $billDate              = date('Y-m-d',strtotime($bill_date)) ;
     $patient_id            = trim($request->patient_id);
     $patient_name          = trim($request->patient_name);
     $mobile_number         = trim($request->mobile_number);
     $age                   = trim($request->age);
     $sex                   = trim($request->sex);
     $care_of               = trim($request->care_of);
     $address               = trim($request->address);
     // ipd info
     $admission_fee_amount    = trim($request->admission_fee_amount);
     $room_type               = trim($request->room_type);
     $paid_amount             = trim($request->paid_amount);
     $confirm_paid_amount     = trim($request->confirm_paid_amount);

     $cabin_type              = trim($request->cabin_type);
     $cabin_room              = trim($request->cabin_room);
     $ward_no                 = trim($request->ward_no);
     $ward_bed                = trim($request->ward_bed);
     $remarks                 = trim($request->remarks);

     $twenty_four_hour_admit_time = trim($request->admit_time); 
     $admitTime                   = date("H:i:s", strtotime($twenty_four_hour_admit_time));
     #---------------------------- validation -------------------------------------#
     // patient
     if($patient_id == '0'){
        if($patient_name == ''){
        Session::put('failed','Sorry ! Please Enter Patient Name And Try Again');
        return Redirect::to('ipdAdmission');
        exit();
        }
     }
     // room validation
     if($room_type == '1'){
        // cabin room validation
        if($cabin_type == ''){
        Session::put('failed','Sorry ! Please  Select Cabin Type And Try Again');
        return Redirect::to('ipdAdmission');
        exit();
        }
        if($cabin_room == ''){
        Session::put('failed','Sorry ! Please  Select Cabin Room And Try Again');
        return Redirect::to('ipdAdmission');
        exit();
        }
        // checked not available
    $count_available_room =  DB::table('tbl_cabin_room')
    ->where('branch_id',$this->branch_id)
    ->where('status',1)
    ->where('booked_status',1)
    ->where('id',$cabin_room)
    ->count();
    if($count_available_room > 0){
        Session::put('failed','Sorry ! Cabin Room Is Not Available');
        return Redirect::to('ipdAdmission');
        exit();
    }

     }elseif($room_type == '2'){
        // bed romm validation
        if($ward_no == ''){
        Session::put('failed','Sorry ! Please  Select Ward And Try Again');
        return Redirect::to('ipdAdmission');
        exit();
        }
        if($ward_bed == ''){
        Session::put('failed','Sorry ! Please  Select Bed And Try Again');
        return Redirect::to('ipdAdmission');
        exit();
        }
      // check not available
    $count_available_bed =  DB::table('tbl_ward_bed')
    ->where('branch_id',$this->branch_id)
    ->where('status',1)
    ->where('booked_status',1)
    ->where('id',$ward_bed)
    ->count();
    if($count_available_bed > 0){
        Session::put('failed','Sorry ! Bed Is Not Available');
        return Redirect::to('ipdAdmission');
        exit();
    }
     }
     #---------------------------- end validation ----------------------------------#
     #---------------------- DATE VALIDATION----------------------#
     if($billDate > $this->rcdate){
        Session::put('failed','Sorry ! Enter Wrong Admit Date . Please Enter Valid Admit Date And Try Again');
        return Redirect::to('ipdAdmission');
        exit();
     }
     #--------------------- END DATE VALIDATIN--------------------#
     if($paid_amount != $confirm_paid_amount){
        Session::put('failed','Sorry ! Paid Amount And Confirm Paid Amount Did Not Match . Try Again');
        return Redirect::to('ipdAdmission');
        exit(); 
     }
    #------------------- PATIENT INFO ------------------------------------------#
     if($patient_id == '0'){
      // for new patient
      $patinent_number_count = DB::table('tbl_patient')->where('year',$this->current_year)->where('branch_id',$branch)->orderBy('pn_number','desc')->count();
      if($patinent_number_count == '0'){
        $patient_id_number = 1 ;
      }else{
      $patinent_number_query = DB::table('tbl_patient')->where('year',$this->current_year)->where('branch_id',$branch)->orderBy('pn_number','desc')->first();
      $patient_id_number = $patinent_number_query->pn_number + 1 ;
      }
      // insert into patient table
      $data_patient_insert             = array();
      $data_patient_insert['branch_id']      = $branch ;
      $data_patient_insert['year']       = $this->current_year ;
      $data_patient_insert['pn_number']      = $patient_id_number;
      $data_patient_insert['patient_name']   = $patient_name;
      $data_patient_insert['c_o_name']       = $care_of ;
      $data_patient_insert['patient_mobile'] = $mobile_number ;
      $data_patient_insert['patient_age']    = $age;
      $data_patient_insert['patient_sex']    = $sex ;
      $data_patient_insert['address']        = $address ;
      $data_patient_insert['created_time']   = $this->current_time ;
      $data_patient_insert['created_at']     = $this->rcdate ;
      DB::table('tbl_patient')->insert($data_patient_insert);
      // get last id of tbl patient table
      $patient_last_id_query = DB::table('tbl_patient')->orderBy('id','desc')->limit(1)->first();
      $patient_primary_id_is = $patient_last_id_query->id ;
      // create patient number is
      $patient_number_create_for_patient = $this->current_year.$patient_id_number.'-'.$patient_primary_id_is ; 

     $salt      = 'a123A321';
     $password  = trim(sha1($patient_number_create_for_patient.$salt));
      // update query
      $data_patinent_number_update = array();
      $data_patinent_number_update['patient_number'] = $patient_number_create_for_patient ;
      $data_patinent_number_update['password']       = $password ;
      DB::table('tbl_patient')->where('id',$patient_primary_id_is)->update($data_patinent_number_update);
     }else{
      // old patient
      $patient_primary_id_is = $patient_id ;
     }
     #------------------- END PATIENT INFO ------------------------------------------#
     $purpose = "IPD Admission Fee";
        // status = 12
        // tr status = 1 by cash transaction
        $data_cashbook                        = array();
        $data_cashbook['overall_branch_id']   = $this->branch_id ;
        $data_cashbook['branch_id']           = $this->branch_id ;
        $data_cashbook['admin_id']            = $this->loged_id  ;
        $data_cashbook['admin_type']          = 3 ;
        $data_cashbook['earn']                = $paid_amount ;
        $data_cashbook['profit_earn']         = $paid_amount ;
        $data_cashbook['status']              = 12 ;
        $data_cashbook['tr_status']           = 1 ;
        $data_cashbook['purpose']             = $purpose;
        $data_cashbook['added_id']            = $this->loged_id;
        $data_cashbook['created_time']        = $this->current_time;
        $data_cashbook['created_at']          = $billDate;
        $data_cashbook['on_created_at']       = $this->rcdate;
        DB::table('cashbook')->insert($data_cashbook);
      #---------------------------------- END INSERT INTO CASHBOOK --------------------#
      #--------------------- GET LAST CASH BOOK ID  -----------------------------------#
     $last_cashbook_id_query = DB::table('cashbook')->orderBy('id','desc')->limit(1)->first();
     $last_cashbook_id       = $last_cashbook_id_query->id ; 
     #-------------------- GET LAST CASH BOOK ID --------------------------------------#
     
         #------------------- GET INVOICE NUMBER-------------------------------------------#
    // branch wise invoice
     $branch_invoice_count = DB::table('tbl_ipd_admission')->where('branch_id',$branch)->orderBy('invoice','desc')->count();
     if($branch_invoice_count == '0'){
      $branch_invoice_number = 1 ;
     }else{
      $branch_invoice_query = DB::table('tbl_ipd_admission')->where('branch_id',$branch)->orderBy('invoice','desc')->limit(1)->first();
      $branch_invoice_number = $branch_invoice_query->invoice + 1 ;
     }
    #-------------------- get year invoice------------------------------------#
    $branch_year_invoice_count = DB::table('tbl_ipd_admission')->where('year',$this->current_year)->where('branch_id',$branch)->orderBy('year_invoice','desc')->count();
     if($branch_year_invoice_count == '0'){
      $branch_invoice_year_number = 1 ;
     }else{
      $branch_year_invoice_query = DB::table('tbl_ipd_admission')->where('year',$this->current_year)->where('branch_id',$branch)->orderBy('year_invoice','desc')->limit(1)->first();
      $branch_invoice_year_number = $branch_year_invoice_query->year_invoice + 1 ;
     }
     #--------------------- daily invoice -------------------------------------#
      $branch_daily_invoice_count = DB::table('tbl_ipd_admission')->where('admit_date',$this->rcdate)->where('branch_id',$branch)->orderBy('daily_invoice','desc')->count();
     if($branch_daily_invoice_count == '0'){
      $branch_daily_invoice_number = 1 ;
     }else{
      $branch_daily_invoice_query = DB::table('tbl_ipd_admission')->where('admit_date',$this->rcdate)->where('branch_id',$branch)->orderBy('daily_invoice','desc')->limit(1)->first();
      $branch_daily_invoice_number = $branch_daily_invoice_query->daily_invoice + 1 ;
     }
    #------------------ END GET INVIOCE NUMBER ----------------------------------#
    #-------------------- INSERT INTO IPD ADMISSION TABLE -----------------------------#
     $data_ipd_insert                 = array();
     $data_ipd_insert['cashbook_id']  = $last_cashbook_id ;
     $data_ipd_insert['year']         = $this->current_year ;
     $data_ipd_insert['invoice']      = $branch_invoice_number;
     $data_ipd_insert['year_invoice'] = $branch_invoice_year_number;
     $data_ipd_insert['daily_invoice']= $branch_daily_invoice_number;
     $data_ipd_insert['branch_id']    = $branch ;
     $data_ipd_insert['patient_id']   = $patient_primary_id_is;
     $data_ipd_insert['purpose']      = $purpose;
     $data_ipd_insert['remarks']      = $remarks;
     $data_ipd_insert['added_id']     = $this->loged_id;
     $data_ipd_insert['admit_time']   = $admitTime ;
     $data_ipd_insert['admit_date']   = $billDate ;
     $data_ipd_insert['created_time'] = $this->current_time;
     $data_ipd_insert['created_at']   = $this->rcdate ;
     DB::table('tbl_ipd_admission')->insert($data_ipd_insert);
     // last ipd admission id
     $last_ipd_admission_query = DB::table('tbl_ipd_admission')->orderBy('id','desc')->limit(1)->first();
     $last_ipd_admission_id    = $last_ipd_admission_query->id ;  
   #------------------------- END INSERT INTO IPD ADMISSION TABLE-------------------------#
    #------------------------  GET CHARGE AMOUNT ------------------------------------------#
     // get charge amount 
     if($room_type == '1'){
      // cabin charge
      $cabin_charge   = DB::table('tbl_cabin_type')->where('branch_id',$this->branch_id)->where('id',$cabin_type)->first();
      $charege_amount = $cabin_charge->charge_amount ; 
      $cabin_type_id_is_used = $cabin_type ;
      $cabin_room_id_is_used = $cabin_room ;

      $ward_id_is_used       = '' ;
      $ward_bed_id_is_used   = '' ;
     }elseif($room_type == '2'){
      $bed_charge   = DB::table('tbl_ward_bed')->where('branch_id',$this->branch_id)->where('id',$ward_bed)->first();
      $charege_amount = $bed_charge->charge_amount ; 
      $ward_id_is_used       = $ward_no ;
      $ward_bed_id_is_used   = $ward_bed ;

      $cabin_type_id_is_used = '';
      $cabin_room_id_is_used =  '';
     }
    #------------------------------ END GET CHARGE AMOUNT---------------------------------#
    #------------------------- INSERT DATA INTO IPD CABIN BED HISTORY---------------------#
     $data_ipd_cabin_bed_history                     = array();
     $data_ipd_cabin_bed_history['branch_id']        = $branch ;
     $data_ipd_cabin_bed_history['ipd_admission_id'] = $last_ipd_admission_id;
     $data_ipd_cabin_bed_history['patient_id']       = $patient_primary_id_is;
     $data_ipd_cabin_bed_history['invoice_number']   = $branch_invoice_number ;
     $data_ipd_cabin_bed_history['room_type']        = $room_type ;
     $data_ipd_cabin_bed_history['cabin_type_id']    = $cabin_type_id_is_used;
     $data_ipd_cabin_bed_history['cabin_id']         = $cabin_room_id_is_used ;
     $data_ipd_cabin_bed_history['ward_id']          = $ward_id_is_used ;
     $data_ipd_cabin_bed_history['ward_bed_id']      = $ward_bed_id_is_used ;
     $data_ipd_cabin_bed_history['charge_amount']    = $charege_amount;
     $data_ipd_cabin_bed_history['status']           = 1 ;
     $data_ipd_cabin_bed_history['booked_status']    = 0 ;
     $data_ipd_cabin_bed_history['purpose']          = $purpose ;
     $data_ipd_cabin_bed_history['remarks']          = $remarks ;
     $data_ipd_cabin_bed_history['added_id']         = $this->loged_id;
     $data_ipd_cabin_bed_history['admit_time']       = $admitTime ;
     $data_ipd_cabin_bed_history['admit_date']       = $billDate ;
     $data_ipd_cabin_bed_history['created_time']     = $this->current_time ;
     $data_ipd_cabin_bed_history['created_at']       = $this->rcdate ;
     DB::table('tbl_ipd_cabin_bed_history')->insert($data_ipd_cabin_bed_history);
   #------------------------- END INSERT DATA INTO IPD CABIN BED HISTORY------------------#
   #------------------------- Insert into ipd ledger--------------------------------------#
   $data_ipd_ledger                       = array();
   $data_ipd_ledger['branch_id']          = $branch ;
   $data_ipd_ledger['ipd_admission_id']   = $last_ipd_admission_id;
   $data_ipd_ledger['service_type']       = 1;
   $data_ipd_ledger['service_id']         = $last_ipd_admission_id; ;
   $data_ipd_ledger['service_invoice']    = $branch_invoice_number ; 
   $data_ipd_ledger['patient_id']         = $patient_primary_id_is;
   $data_ipd_ledger['payable_amount']     = $admission_fee_amount ;
   $data_ipd_ledger['payment_amount']     = $paid_amount;
   $data_ipd_ledger['purpose']            = $purpose ;
   $data_ipd_ledger['remarks']            = $remarks ;
   $data_ipd_ledger['added_id']           = $this->loged_id;
   $data_ipd_ledger['created_time']       = $this->current_time;
   $data_ipd_ledger['service_created_at'] = $billDate;
   $data_ipd_ledger['created_at']         = $this->rcdate;
   DB::table('tbl_ipd_ledger')->insert($data_ipd_ledger);
  #------------------------ end insert into ipd ledger ----------------------------------#
  #-------------------------------- incress pettycash amount ------------------------------#
      $pettycash_amount = DB::table('pettycash')->where('branch_id',$branch)->where('type',3)->limit(1)->first();
      $current_pettycash_amt = $pettycash_amount->pettycash_amount ;
      $now_pettycash_amt     = $current_pettycash_amt + $paid_amount ;
      // update pettycash amount
      $data_update_pettycash                      = array();
      $data_update_pettycash['pettycash_amount']  = $now_pettycash_amt; 
      DB::table('pettycash')->where('branch_id',$branch)->where('type',3)->update($data_update_pettycash);
  #-------------------------------- end incress pettycash amount---------------------------#
  #-------------------------------- room booked update ------------------------------------#
    $data_room_boocked                  = array();
    $data_room_boocked['booked_status'] = 1 ;

    if($room_type == '1'){
      DB::table('tbl_cabin_room')->where('branch_id',$this->branch_id)->where('id',$cabin_room)->update($data_room_boocked);
    }elseif($room_type == '2'){
     DB::table('tbl_ward_bed')->where('branch_id',$this->branch_id)->where('id',$ward_bed)->update($data_room_boocked);
    }
   #-------------------------------- end room booked update ---------------------------------#
     return Redirect::to('/printIpdAdmissionInvoice/'.$last_ipd_admission_id.'/'.$branch_invoice_number); 

    }// function closse
    // ipd pathoolgy bill
    public function ipdPathologyBillCreate()
    {
    $result  = DB::table('tbl_test')->where('status',0)->get();
    $patient = DB::table('tbl_patient')->where('branch_id',$this->branch_id)->orderBy('id','desc')->get();
    $doctor  = DB::table('admin')->where('branch_id',$this->branch_id)->where('type',4)->where('status',1)->get();
    $pc      = DB::table('tbl_pc')->where('branch_id',$this->branch_id)->where('status',1)->get();

      $cabin_type = DB::table('tbl_cabin_type')->where('branch_id',$this->branch_id)->get();
    // ward
     $ward =  DB::table('tbl_ward')
    ->join('tbl_building', 'tbl_ward.building_id', '=', 'tbl_building.id')
    ->join('tbl_building_floor', 'tbl_ward.floor_id', '=', 'tbl_building_floor.id')
    ->select('tbl_ward.*','tbl_building.building_name','tbl_building_floor.floor_name')
    ->where('tbl_ward.branch_id',$this->branch_id)
    ->get();
    return view('ipd.ipdPathologyBillCreate')->with('result',$result)->with('patient',$patient)->with('doctor',$doctor)->with('pc',$pc)->with('cabin_type',$cabin_type)->with('ward',$ward);
    }
    // get all booking cabin room
    public function getAllBokkingCabinRoom(Request $request)
    {
    $cabin_type = trim($request->cabin_type);
    $result =  DB::table('tbl_cabin_room')
    ->join('tbl_building', 'tbl_cabin_room.building_id', '=', 'tbl_building.id')
    ->join('tbl_building_floor', 'tbl_cabin_room.floor_id', '=', 'tbl_building_floor.id')
    ->join('tbl_cabin_type', 'tbl_cabin_room.cabin_type_id', '=', 'tbl_cabin_type.id')
    ->select('tbl_cabin_room.*','tbl_building.building_name','tbl_building_floor.floor_name','tbl_cabin_type.cabin_type_name')
    ->where('tbl_cabin_room.branch_id',$this->branch_id)
    ->where('tbl_cabin_room.booked_status',1)
    ->where('tbl_cabin_room.cabin_type_id',$cabin_type)
    ->get();
    echo "<option value=''>Select Cabin Room</option>";
    foreach ($result as $value) {
        echo "<option value=".$value->id.">".$value->building_name." - ".$value->floor_name." - ".$value->room_no."</option>";
    }

  }
     // get booked bed room
    public function getAllBokkingBedRoom(Request $request)
    {
    $ward_no = trim($request->ward_no);
    // get bed name of this ward
    $row = DB::table('tbl_ward')->where('branch_id',$this->branch_id)->where('id',$ward_no)->first();;
    $result =  DB::table('tbl_ward_bed')
    ->where('branch_id',$this->branch_id)
    ->where('ward_id',$ward_no)
    ->where('booked_status',1)
    ->get();
    echo "<option value=''>Select Bed</option>";
    foreach ($result as $value) {
        echo "<option value=".$value->id.">".$row->nic_name." - ".$value->bed_no."</option>";
        
    } 
    }
    // get ipd patient info
    public function getCabinRoomPatientInfoForAddIpdPathology(Request $request)
    {
    $cabin_room = trim($request->cabin_room);
     if($cabin_room == ''){
      echo '';
      exit();
     }
    // get patient id by invoice
    $cabin_book_info = DB::table('tbl_ipd_cabin_bed_history')->where('branch_id',$this->branch_id)->where('cabin_id',$cabin_room)->where('booked_status',0)->first();

    $admit_date = date('d M Y',strtotime($cabin_book_info->admit_date)) ;
    $admit_time = date('h:i:s a',strtotime($cabin_book_info->admit_time)) ;
    $patient_id = $cabin_book_info->patient_id ;
    $ipd_admission_id      = $cabin_book_info->ipd_admission_id ;
    $ipd_admission_invoice = $cabin_book_info->invoice_number ;

    $value =  DB::table('tbl_cabin_room')
    ->join('tbl_building', 'tbl_cabin_room.building_id', '=', 'tbl_building.id')
    ->join('tbl_building_floor', 'tbl_cabin_room.floor_id', '=', 'tbl_building_floor.id')
    ->join('tbl_cabin_type', 'tbl_cabin_room.cabin_type_id', '=', 'tbl_cabin_type.id')
    ->select('tbl_cabin_room.*','tbl_building.building_name','tbl_building_floor.floor_name','tbl_cabin_type.cabin_type_name')
    ->where('tbl_cabin_room.branch_id',$this->branch_id)
    ->where('tbl_cabin_room.booked_status',1)
    ->where('tbl_cabin_room.id',$cabin_room)
    ->first();

    $patient_info         = DB::table('tbl_patient')->where('branch_id',$this->branch_id)->where('id',$patient_id)->first();
    // calculation by ipd ledger
    echo  "Patient Id : ".$patient_info->patient_number." Name : ".$patient_info->patient_name." Mobile : ".$patient_info->patient_mobile." Admit Date / Time : ".$admit_date." / ".$admit_time." Details :".$value->building_name." - ".$value->floor_name." - Room ".$value->room_no ;
    }
    // get ward patient info
    public function getWardBedPatientInfoForAddIpdPathology(Request $request)
    {
     $ward_bed = trim($request->ward_bed);
     if($ward_bed == ''){
      echo '';
      exit();
     }

    $cabin_book_info = DB::table('tbl_ipd_cabin_bed_history')->where('branch_id',$this->branch_id)->where('ward_bed_id',$ward_bed)->where('booked_status',0)->first();

    $ward_no = $cabin_book_info->ward_id ;

    $admit_date = date('d M Y',strtotime($cabin_book_info->admit_date)) ;
    $admit_time = date('h:i:s a',strtotime($cabin_book_info->admit_time)) ;
    $patient_id = $cabin_book_info->patient_id ;

    $row = DB::table('tbl_ward')->where('branch_id',$this->branch_id)->where('id',$ward_no)->first();
    $value =  DB::table('tbl_ward_bed')
    ->where('branch_id',$this->branch_id)
    ->where('ward_id',$ward_no)
    ->where('id',$ward_bed)
    ->where('booked_status',1)
    ->first();

    $result =  DB::table('tbl_ward')
    ->join('tbl_building', 'tbl_ward.building_id', '=', 'tbl_building.id')
    ->join('tbl_building_floor', 'tbl_ward.floor_id', '=', 'tbl_building_floor.id')
    ->select('tbl_ward.*','tbl_building.building_name','tbl_building_floor.floor_name')
    ->where('tbl_ward.branch_id',$this->branch_id)
    ->where('tbl_ward.id',$ward_no)
    ->first();

    $patient_info  = DB::table('tbl_patient')->where('branch_id',$this->branch_id)->where('id',$patient_id)->first();

     echo  "Patient Id : ".$patient_info->patient_number." Name : ".$patient_info->patient_name." Mobile : ".$patient_info->patient_mobile." Admit Date / Time : ".$admit_date." / ".$admit_time." Details :".$result->building_name." - ".$result->ward_number." - ".$result->floor_name." - ".$row->nic_name." - ".$value->bed_no ;

    }
    // create ipd pathology bill
    public function createIPDPathologyBill(Request $request)
    {
      $room_type             = trim($request->room_type);
      $cabin_type            = trim($request->cabin_type);
      $cabin_room            = trim($request->cabin_room);
      $ward_no               = trim($request->ward_no);
      $ward_bed              = trim($request->ward_bed);
     $branch                 = $this->branch_id ;
     $bill_date              = trim($request->bill_date);
     $billDate               = date('Y-m-d',strtotime($bill_date)) ;
     $report_date_and_time   = trim($request->report_date_and_time);
     $reportDate             = date('Y-m-d',strtotime($report_date_and_time)) ;
     $doctor_id              = trim($request->doctor_id);
     $pc_id                  = trim($request->pc_id);
     $total_amount           = trim($request->total_amount);
     $total_discount         = trim($request->total_discount);
     $total_paid             = trim($request->total_paid);
     $pc_amount              = trim($request->pc_amount);

     $array_invoice_data    = $request->arr; 
     $invoice_datas         = json_decode($array_invoice_data);
     $payableAmount         = $total_amount - $total_discount ;
     $total_due             = $payableAmount - $total_paid ;
     #------------------------ validation-----------------------------#
            if($room_type  == ""){
             echo 'r1';
             return false ;
            }

            if($room_type == '1'){
              if($cabin_type == ''){
              echo 'r2';
             return false ;
              }
              if($cabin_room == ''){
                  echo 'r3';
                  return false ;
              }

            }

            if($room_type == '2'){
              if($ward_no == ''){
                  echo 'r4';
                  return false ;
              }
              if($ward_bed == ''){
                  echo 'r5';
                  return false ;
              }

            }

          if($billDate > $this->rcdate){
          echo "d1";
          exit();
          }

      if($payableAmount < $total_paid){
       // paid amount not big than total amount
       echo "p2";
       exit();
        }
     #------------------------ end validation-----------------------------------------#
     #------------------------ get ipd admission info --------------------------------#
      if($room_type == '1'){
        $cabin_book_info = DB::table('tbl_ipd_cabin_bed_history')->where('branch_id',$this->branch_id)->where('cabin_id',$cabin_room)->where('booked_status',0)->first();
        $ipd_admisison_id_is      = $cabin_book_info->ipd_admission_id ;
        $ipd_admisison_invoice_is = $cabin_book_info->invoice_number ;
        $patient_id               = $cabin_book_info->patient_id ;
        $patient_primary_id_is    = $patient_id ;

      }elseif($room_type == '2'){
         $ward_book_info = DB::table('tbl_ipd_cabin_bed_history')->where('branch_id',$this->branch_id)->where('ward_bed_id',$ward_bed)->where('booked_status',0)->first();
            $ipd_admisison_id_is      = $ward_book_info->ipd_admission_id ;
            $ipd_admisison_invoice_is = $ward_book_info->invoice_number ;
            $patient_id               = $ward_book_info->patient_id ;
            $patient_primary_id_is    = $patient_id ;
      }
     #----------------------- end ipd admissiin info ---------------------------------#
     #----------------------------------- INSERT INTO CASHBOOK -----------------------#
     if($total_discount > 0){
      $purpose = "IPD Pathology Bill Create With Discount";
     }else{
      $purpose = "IPD Pathology Bill Create";
     } 
        // status = 13
        // tr status = 1 by cash transaction
        $data_cashbook                        = array();
        $data_cashbook['overall_branch_id']   = $this->branch_id ;
        $data_cashbook['branch_id']           = $this->branch_id ;
        $data_cashbook['admin_id']            = $this->loged_id  ;
        $data_cashbook['admin_type']          = 3 ;
        $data_cashbook['earn']                = $total_paid + $total_discount ;
        $data_cashbook['cost']                = $total_discount ;
        $data_cashbook['profit_earn']         = $total_paid + $total_discount ;
        $data_cashbook['profit_cost']         = $total_discount ;
        $data_cashbook['status']              = 13 ;
        $data_cashbook['tr_status']           = 1 ;
        $data_cashbook['purpose']             = $purpose;
        $data_cashbook['added_id']            = $this->loged_id;
        $data_cashbook['created_time']        = $this->current_time;
        $data_cashbook['created_at']          = $billDate;
        $data_cashbook['on_created_at']       = $this->rcdate;
        DB::table('cashbook')->insert($data_cashbook);
      #---------------------------------- END INSERT INTO CASHBOOK --------------------#
      #--------------------- GET LAST CASH BOOK ID  -----------------------------------#
     $last_cashbook_id_query = DB::table('cashbook')->orderBy('id','desc')->limit(1)->first();
     $last_cashbook_id       = $last_cashbook_id_query->id ; 
    #-------------------- GET LAST CASH BOOK ID --------------------------------------#
    #------------------- GET INVOICE NUMBER-------------------------------------------#
    // branch wise invoice
     $branch_invoice_count = DB::table('tbl_ipd_pathology_bill')->where('branch_id',$branch)->orderBy('invoice','desc')->count();
     if($branch_invoice_count == '0'){
      $branch_invoice_number = 1 ;
     }else{
      $branch_invoice_query = DB::table('tbl_ipd_pathology_bill')->where('branch_id',$branch)->orderBy('invoice','desc')->limit(1)->first();
      $branch_invoice_number = $branch_invoice_query->invoice + 1 ;
     }
    #-------------------- get year invoice------------------------------------#
    $branch_year_invoice_count = DB::table('tbl_ipd_pathology_bill')->where('year',$this->current_year)->where('branch_id',$branch)->orderBy('year_invoice','desc')->count();
     if($branch_year_invoice_count == '0'){
      $branch_invoice_year_number = 1 ;
     }else{
      $branch_year_invoice_query = DB::table('tbl_ipd_pathology_bill')->where('year',$this->current_year)->where('branch_id',$branch)->orderBy('year_invoice','desc')->limit(1)->first();
      $branch_invoice_year_number = $branch_year_invoice_query->year_invoice + 1 ;
     }
     #--------------------- daily invoice -------------------------------------#
      $branch_daily_invoice_count = DB::table('tbl_ipd_pathology_bill')->where('bill_date',$this->rcdate)->where('branch_id',$branch)->orderBy('daily_invoice','desc')->count();
     if($branch_daily_invoice_count == '0'){
      $branch_daily_invoice_number = 1 ;
     }else{
      $branch_daily_invoice_query = DB::table('tbl_ipd_pathology_bill')->where('bill_date',$this->rcdate)->where('branch_id',$branch)->orderBy('daily_invoice','desc')->limit(1)->first();
      $branch_daily_invoice_number = $branch_daily_invoice_query->daily_invoice + 1 ;
     }
    #------------------ END GET INVIOCE NUMBER ----------------------------------#
    #---------------------------- INSERT INTO PATHOLOGY BILL TABLE---------------#
    $data_pathology_bill_insert                      = array();
    $data_pathology_bill_insert['cashbook_id']       = $last_cashbook_id ;
    $data_pathology_bill_insert['ipd_admission_id']  = $ipd_admisison_id_is ;
    $data_pathology_bill_insert['ipd_invoice_no']    = $ipd_admisison_invoice_is ;
    $data_pathology_bill_insert['year']              = $this->current_year;
    $data_pathology_bill_insert['invoice']           = $branch_invoice_number;
    $data_pathology_bill_insert['year_invoice']      = $branch_invoice_year_number;
    $data_pathology_bill_insert['daily_invoice']     = $branch_daily_invoice_number ;
    $data_pathology_bill_insert['branch_id']         = $branch ;
    $data_pathology_bill_insert['doctor_id']         = $doctor_id ;
    $data_pathology_bill_insert['pc_id']             = $pc_id ;
    $data_pathology_bill_insert['patient_id']        = $patient_primary_id_is;
    $data_pathology_bill_insert['purpose']           = 'IPD Pathology Bill Create';   
    $data_pathology_bill_insert['report_date']       = $reportDate ;
    $data_pathology_bill_insert['bill_time']         = $this->current_time ;
    $data_pathology_bill_insert['bill_date']         = $billDate ;
    $data_pathology_bill_insert['added_id']          = $this->loged_id;
    $data_pathology_bill_insert['created_at']        = $this->rcdate ;
    DB::table('tbl_ipd_pathology_bill')->insert($data_pathology_bill_insert);
   #--------------------------- END INSERT INTO PATHYOLOGY BILL TABLE-------------#
    #------------------------ get last id of pathology bill-----------------------#
    $last_ipd_id_query = DB::table('tbl_ipd_pathology_bill')->orderBy('id','desc')->limit(1)->first();
    $last_ipd_id       = $last_ipd_id_query->id ; 
    #------------------------ end get last id of patylogy bill --------------------#
    #--------- CREATE THE BILL ITEAM (INSERT IPD PATHLOGY BILL ITEAM TABEL)------#
    foreach ($invoice_datas as $product_info) {
    $test_id            = $product_info[0]; 
    $sale_price         = $product_info[3];
    $sub_quantity       = $product_info[1];
    $sub_total_price    = $product_info[4];

     $data_pathology_bill_item_insert                         = array();
     $data_pathology_bill_item_insert['cashbook_id']          = $last_cashbook_id ;
     $data_pathology_bill_item_insert['ipd_admission_id']     = $ipd_admisison_id_is ;
     $data_pathology_bill_item_insert['ipd_invoice_no']       = $ipd_admisison_invoice_is ;
     $data_pathology_bill_item_insert['invoice_number']       = $branch_invoice_number;
     $data_pathology_bill_item_insert['year_invoice_number']  = $branch_invoice_year_number;
     $data_pathology_bill_item_insert['daily_invoice_number'] = $branch_daily_invoice_number;
     $data_pathology_bill_item_insert['branch_id']            = $branch;
     $data_pathology_bill_item_insert['test_id']              = $test_id ;
     $data_pathology_bill_item_insert['test_price']           = $sale_price;
     $data_pathology_bill_item_insert['total_quantity']       = $sub_quantity;
     $data_pathology_bill_item_insert['total_price']          = $sub_total_price ;
     $data_pathology_bill_item_insert['bill_date']            = $billDate ;
     $data_pathology_bill_item_insert['added_id']             = $this->loged_id;
     $data_pathology_bill_item_insert['created_time']         = $this->current_time ;
     $data_pathology_bill_item_insert['created_at']           = $this->rcdate;
     DB::table('tbl_ipd_pathology_bill_item')->insert($data_pathology_bill_item_insert);
     }
   #------------------------- Insert into ipd ledger--------------------------------------#
     // service type = 2 (ipd pathlogy bill)
   $data_ipd_ledger                       = array();
   $data_ipd_ledger['branch_id']          = $branch ;
   $data_ipd_ledger['ipd_admission_id']   = $ipd_admisison_id_is ;
   $data_ipd_ledger['service_type']       = 2;
   $data_ipd_ledger['service_id']         = $last_ipd_id ;
   $data_ipd_ledger['service_invoice']    = $branch_invoice_number ; 
   $data_ipd_ledger['patient_id']         = $patient_primary_id_is;
   $data_ipd_ledger['payable_amount']     = $total_amount ;
   $data_ipd_ledger['payment_amount']     = $total_paid;
   $data_ipd_ledger['discount']           = $total_discount;
   $data_ipd_ledger['purpose']            = $purpose ;
   //$data_ipd_ledger['remarks']            = $remarks ;
   $data_ipd_ledger['added_id']           = $this->loged_id;
   $data_ipd_ledger['created_time']       = $this->current_time;
   $data_ipd_ledger['service_created_at'] = $billDate;
   $data_ipd_ledger['created_at']         = $this->rcdate;
   DB::table('tbl_ipd_ledger')->insert($data_ipd_ledger);
  #------------------------ end insert into ipd ledger ----------------------------------#
  #-------------------------------- incress pettycash amount ------------------------------#
      $pettycash_amount = DB::table('pettycash')->where('branch_id',$branch)->where('type',3)->limit(1)->first();
      $current_pettycash_amt = $pettycash_amount->pettycash_amount ;
      $now_pettycash_amt     = $current_pettycash_amt + $total_paid ;
      // update pettycash amount
      $data_update_pettycash                      = array();
      $data_update_pettycash['pettycash_amount']  = $now_pettycash_amt; 
      DB::table('pettycash')->where('branch_id',$branch)->where('type',3)->update($data_update_pettycash);
     #-------------------------------- end incress pettycash amount---------------------------#
     echo $last_ipd_id.'/'.$branch_invoice_number.'/'.$last_cashbook_id.'/'.$ipd_admisison_id_is ;
    }

    // ipd bill clearence
    public function ipdBillClearence()
    {
    $result  = DB::table('tbl_ipd_service')->where('branch_id',$this->branch_id)->where('status',0)->get();
    $patient = DB::table('tbl_patient')->where('branch_id',$this->branch_id)->orderBy('id','desc')->get();
    $doctor  = DB::table('admin')->where('branch_id',$this->branch_id)->where('type',4)->where('status',1)->get();
    $pc         = DB::table('tbl_pc')->where('branch_id',$this->branch_id)->where('status',1)->get();
    $cabin_type = DB::table('tbl_cabin_type')->where('branch_id',$this->branch_id)->get();
    // ward
    $ward =  DB::table('tbl_ward')
    ->join('tbl_building', 'tbl_ward.building_id', '=', 'tbl_building.id')
    ->join('tbl_building_floor', 'tbl_ward.floor_id', '=', 'tbl_building_floor.id')
    ->select('tbl_ward.*','tbl_building.building_name','tbl_building_floor.floor_name')
    ->where('tbl_ward.branch_id',$this->branch_id)
    ->get();
    return view('ipd.ipdBillClearence')->with('result',$result)->with('patient',$patient)->with('doctor',$doctor)->with('pc',$pc)->with('cabin_type',$cabin_type)->with('ward',$ward);

    }
    // get ipd service price for clearence ipd service
    public function getIPDServicePrice(Request $request)
    {
     $service_id = trim($request->service_id);
     $query      = DB::table('tbl_ipd_service')->where('branch_id',$this->branch_id)->where('id',$service_id)->take(1)->first();
     $price      = $query->service_price;
     echo $price ;

    }
    // ipd service bill
    public function ipdServiceBill()
    {
    $result     = DB::table('tbl_ipd_service')->where('branch_id',$this->branch_id)->where('status',0)->get();
    $patient    = DB::table('tbl_patient')->where('branch_id',$this->branch_id)->orderBy('id','desc')->get();
    $doctor     = DB::table('admin')->where('branch_id',$this->branch_id)->where('type',4)->where('status',1)->get();
    $pc         = DB::table('tbl_pc')->where('branch_id',$this->branch_id)->where('status',1)->get();
    $cabin_type = DB::table('tbl_cabin_type')->where('branch_id',$this->branch_id)->get();
    // ward
    $ward =  DB::table('tbl_ward')
    ->join('tbl_building', 'tbl_ward.building_id', '=', 'tbl_building.id')
    ->join('tbl_building_floor', 'tbl_ward.floor_id', '=', 'tbl_building_floor.id')
    ->select('tbl_ward.*','tbl_building.building_name','tbl_building_floor.floor_name')
    ->where('tbl_ward.branch_id',$this->branch_id)
    ->get();
    return view('ipd.ipdServiceBill')->with('result',$result)->with('patient',$patient)->with('doctor',$doctor)->with('pc',$pc)->with('cabin_type',$cabin_type)->with('ward',$ward);
    }
    // create ipd service bill
    public function createIPDServiceBill(Request $request)
    {
      $room_type             = trim($request->room_type);
      $cabin_type            = trim($request->cabin_type);
      $cabin_room            = trim($request->cabin_room);
      $ward_no               = trim($request->ward_no);
      $ward_bed              = trim($request->ward_bed);
     $branch                 = $this->branch_id ;
     $bill_date              = trim($request->bill_date);
     $billDate               = date('Y-m-d',strtotime($bill_date)) ;
     $total_amount           = trim($request->total_amount);
     $total_discount         = trim($request->total_discount);
     $total_paid             = trim($request->total_paid);

     $array_invoice_data    = $request->arr; 
     $invoice_datas         = json_decode($array_invoice_data);
     $payableAmount         = $total_amount - $total_discount ;
     $total_due             = $payableAmount - $total_paid ;
    #------------------------ validation-----------------------------#
            if($room_type  == ""){
             echo 'r1';
             return false ;
            }

            if($room_type == '1'){
              if($cabin_type == ''){
              echo 'r2';
             return false ;
              }
              if($cabin_room == ''){
                  echo 'r3';
                  return false ;
              }

            }

            if($room_type == '2'){
              if($ward_no == ''){
                  echo 'r4';
                  return false ;
              }
              if($ward_bed == ''){
                  echo 'r5';
                  return false ;
              }

            }

          if($billDate > $this->rcdate){
          echo "d1";
          exit();
          }

      if($payableAmount < $total_paid){
       // paid amount not big than total amount
       echo "p2";
       exit();
        }
     #------------------------ end validation-----------------------------------------#
     #------------------------ get ipd admission info --------------------------------#
      if($room_type == '1'){
        $cabin_book_info = DB::table('tbl_ipd_cabin_bed_history')->where('branch_id',$this->branch_id)->where('cabin_id',$cabin_room)->where('booked_status',0)->first();
        $ipd_admisison_id_is      = $cabin_book_info->ipd_admission_id ;
        $ipd_admisison_invoice_is = $cabin_book_info->invoice_number ;
        $patient_id               = $cabin_book_info->patient_id ;
        $patient_primary_id_is    = $patient_id ;

      }elseif($room_type == '2'){
         $ward_book_info = DB::table('tbl_ipd_cabin_bed_history')->where('branch_id',$this->branch_id)->where('ward_bed_id',$ward_bed)->where('booked_status',0)->first();
            $ipd_admisison_id_is      = $ward_book_info->ipd_admission_id ;
            $ipd_admisison_invoice_is = $ward_book_info->invoice_number ;
            $patient_id               = $ward_book_info->patient_id ;
            $patient_primary_id_is    = $patient_id ;
      }
     #----------------------- end ipd admissiin info ---------------------------------#
     #----------------------------------- INSERT INTO CASHBOOK -----------------------#
     if($total_discount > 0){
      $purpose = "IPD Service Bill Create With Discount";
     }else{
      $purpose = "IPD Service Bill Create";
     } 
        // status = 14
        // tr status = 1 by cash transaction
        $data_cashbook                        = array();
        $data_cashbook['overall_branch_id']   = $this->branch_id ;
        $data_cashbook['branch_id']           = $this->branch_id ;
        $data_cashbook['admin_id']            = $this->loged_id  ;
        $data_cashbook['admin_type']          = 3 ;
        $data_cashbook['earn']                = $total_paid + $total_discount ;
        $data_cashbook['cost']                = $total_discount ;
        $data_cashbook['profit_earn']         = $total_paid + $total_discount ;
        $data_cashbook['profit_cost']         = $total_discount ;
        $data_cashbook['status']              = 14 ;
        $data_cashbook['tr_status']           = 1 ;
        $data_cashbook['purpose']             = $purpose;
        $data_cashbook['added_id']            = $this->loged_id;
        $data_cashbook['created_time']        = $this->current_time;
        $data_cashbook['created_at']          = $billDate;
        $data_cashbook['on_created_at']       = $this->rcdate;
        DB::table('cashbook')->insert($data_cashbook);
      #---------------------------------- END INSERT INTO CASHBOOK --------------------#
      #--------------------- GET LAST CASH BOOK ID  -----------------------------------#
     $last_cashbook_id_query = DB::table('cashbook')->orderBy('id','desc')->limit(1)->first();
     $last_cashbook_id       = $last_cashbook_id_query->id ; 
    #-------------------- GET LAST CASH BOOK ID --------------------------------------#
          #--------------------- GET LAST CASH BOOK ID  -----------------------------------#
     $last_cashbook_id_query = DB::table('cashbook')->orderBy('id','desc')->limit(1)->first();
     $last_cashbook_id       = $last_cashbook_id_query->id ; 
    #-------------------- GET LAST CASH BOOK ID --------------------------------------#
    #------------------- GET INVOICE NUMBER-------------------------------------------#
    // branch wise invoice
     $branch_invoice_count = DB::table('tbl_ipd_service_bill')->where('branch_id',$branch)->orderBy('invoice','desc')->count();
     if($branch_invoice_count == '0'){
      $branch_invoice_number = 1 ;
     }else{
      $branch_invoice_query = DB::table('tbl_ipd_service_bill')->where('branch_id',$branch)->orderBy('invoice','desc')->limit(1)->first();
      $branch_invoice_number = $branch_invoice_query->invoice + 1 ;
     }
    #-------------------- get year invoice------------------------------------#
    $branch_year_invoice_count = DB::table('tbl_ipd_service_bill')->where('year',$this->current_year)->where('branch_id',$branch)->orderBy('year_invoice','desc')->count();
     if($branch_year_invoice_count == '0'){
      $branch_invoice_year_number = 1 ;
     }else{
      $branch_year_invoice_query = DB::table('tbl_ipd_service_bill')->where('year',$this->current_year)->where('branch_id',$branch)->orderBy('year_invoice','desc')->limit(1)->first();
      $branch_invoice_year_number = $branch_year_invoice_query->year_invoice + 1 ;
     }
     #--------------------- daily invoice -------------------------------------#
      $branch_daily_invoice_count = DB::table('tbl_ipd_service_bill')->where('bill_date',$this->rcdate)->where('branch_id',$branch)->orderBy('daily_invoice','desc')->count();
     if($branch_daily_invoice_count == '0'){
      $branch_daily_invoice_number = 1 ;
     }else{
      $branch_daily_invoice_query = DB::table('tbl_ipd_service_bill')->where('bill_date',$this->rcdate)->where('branch_id',$branch)->orderBy('daily_invoice','desc')->limit(1)->first();
      $branch_daily_invoice_number = $branch_daily_invoice_query->daily_invoice + 1 ;
     }
    #------------------ END GET INVIOCE NUMBER ----------------------------------#
    #---------------------------- INSERT INTO PATHOLOGY BILL TABLE---------------#
    $data_pathology_bill_insert                      = array();
    $data_pathology_bill_insert['cashbook_id']       = $last_cashbook_id ;
    $data_pathology_bill_insert['ipd_admission_id']  = $ipd_admisison_id_is ;
    $data_pathology_bill_insert['ipd_invoice_no']    = $ipd_admisison_invoice_is ;
    $data_pathology_bill_insert['year']              = $this->current_year;
    $data_pathology_bill_insert['invoice']           = $branch_invoice_number;
    $data_pathology_bill_insert['year_invoice']      = $branch_invoice_year_number;
    $data_pathology_bill_insert['daily_invoice']     = $branch_daily_invoice_number ;
    $data_pathology_bill_insert['branch_id']         = $branch ;;
    $data_pathology_bill_insert['patient_id']        = $patient_primary_id_is;
    $data_pathology_bill_insert['purpose']           = 'IPD Service Bill Create';   
    $data_pathology_bill_insert['bill_time']         = $this->current_time ;
    $data_pathology_bill_insert['bill_date']         = $billDate ;
    $data_pathology_bill_insert['added_id']          = $this->loged_id;
    $data_pathology_bill_insert['created_at']        = $this->rcdate ;
    DB::table('tbl_ipd_service_bill')->insert($data_pathology_bill_insert);
   #--------------------------- END INSERT INTO PATHYOLOGY BILL TABLE-------------#
   #------------------------ get last id of pathology bill-----------------------#
    $last_ipd_id_query = DB::table('tbl_ipd_service_bill')->orderBy('id','desc')->limit(1)->first();
    $last_ipd_id       = $last_ipd_id_query->id ; 
   #------------------------ end get last id of patylogy bill --------------------#
   #--------- CREATE THE BILL ITEAM (INSERT IPD PATHLOGY BILL ITEAM TABEL)------#
    foreach ($invoice_datas as $product_info) {
    $test_id            = $product_info[0]; 
    $sale_price         = $product_info[3];
    $sub_quantity       = $product_info[1];
    $sub_total_price    = $product_info[4];

     $data_pathology_bill_item_insert                         = array();
     $data_pathology_bill_item_insert['cashbook_id']          = $last_cashbook_id ;
     $data_pathology_bill_item_insert['ipd_admission_id']     = $ipd_admisison_id_is ;
     $data_pathology_bill_item_insert['ipd_invoice_no']       = $ipd_admisison_invoice_is ;
     $data_pathology_bill_item_insert['invoice_number']       = $branch_invoice_number;
     $data_pathology_bill_item_insert['year_invoice_number']  = $branch_invoice_year_number;
     $data_pathology_bill_item_insert['daily_invoice_number'] = $branch_daily_invoice_number;
     $data_pathology_bill_item_insert['branch_id']            = $branch;
     $data_pathology_bill_item_insert['service_id']           = $test_id ;
     $data_pathology_bill_item_insert['service_price']        = $sale_price;
     $data_pathology_bill_item_insert['total_quantity']       = $sub_quantity;
     $data_pathology_bill_item_insert['total_price']          = $sub_total_price ;
     $data_pathology_bill_item_insert['bill_date']            = $billDate ;
     $data_pathology_bill_item_insert['added_id']             = $this->loged_id;
     $data_pathology_bill_item_insert['created_time']         = $this->current_time ;
     $data_pathology_bill_item_insert['created_at']           = $this->rcdate;
     DB::table('tbl_ipd_service_bill_item')->insert($data_pathology_bill_item_insert);
     }
    #------------------------- Insert into ipd ledger--------------------------------------#
     // service type = 3 (ipd service bill)
   $data_ipd_ledger                       = array();
   $data_ipd_ledger['branch_id']          = $branch ;
   $data_ipd_ledger['ipd_admission_id']   = $ipd_admisison_id_is ;
   $data_ipd_ledger['service_type']       = 3;
   $data_ipd_ledger['service_id']         = $last_ipd_id ;
   $data_ipd_ledger['service_invoice']    = $branch_invoice_number ; 
   $data_ipd_ledger['patient_id']         = $patient_primary_id_is;
   $data_ipd_ledger['payable_amount']     = $total_amount ;
   $data_ipd_ledger['payment_amount']     = $total_paid;
   $data_ipd_ledger['discount']           = $total_discount;
   $data_ipd_ledger['purpose']            = $purpose ;
   //$data_ipd_ledger['remarks']            = $remarks ;
   $data_ipd_ledger['added_id']           = $this->loged_id;
   $data_ipd_ledger['created_time']       = $this->current_time;
   $data_ipd_ledger['service_created_at'] = $billDate;
   $data_ipd_ledger['created_at']         = $this->rcdate;
   DB::table('tbl_ipd_ledger')->insert($data_ipd_ledger);
  #------------------------ end insert into ipd ledger ----------------------------------#
  #-------------------------------- incress pettycash amount ------------------------------#
      $pettycash_amount = DB::table('pettycash')->where('branch_id',$branch)->where('type',3)->limit(1)->first();
      $current_pettycash_amt = $pettycash_amount->pettycash_amount ;
      $now_pettycash_amt     = $current_pettycash_amt + $total_paid ;
      // update pettycash amount
      $data_update_pettycash                      = array();
      $data_update_pettycash['pettycash_amount']  = $now_pettycash_amt; 
      DB::table('pettycash')->where('branch_id',$branch)->where('type',3)->update($data_update_pettycash);
  #-------------------------------- end incress pettycash amount---------------------------#
     echo $last_ipd_id.'/'.$branch_invoice_number.'/'.$last_cashbook_id.'/'.$ipd_admisison_id_is ;
    }
    // ipd clearence first step
    public function ipdClearenceLedgerPayment(Request $request)
    {
    $this->validate($request, [
    'room_type'                => 'required',
    'ipd_end_date'             => 'required',
    'end_time'                 => 'required',
    ]);
     $branch                = $this->branch_id ;
     $ipd_end_date          = trim($request->ipd_end_date);
     $ipdEndDate            = date('Y-m-d',strtotime($ipd_end_date)) ;
     $ipd_end_time          = trim($request->end_time);
     $room_type             = trim($request->room_type);
     $cabin_type            = trim($request->cabin_type);
     $cabin_room            = trim($request->cabin_room);
     $ward_no               = trim($request->ward_no);
     $ward_bed              = trim($request->ward_bed);
     #---------------------------- validation -------------------------------------#
     // room validation
     if($room_type == '1'){
        // cabin room validation
        if($cabin_type == ''){
        Session::put('failed','Sorry ! Please  Select Cabin Type And Try Again');
        return Redirect::to('ipdBillClearence');
        exit();
        }
        if($cabin_room == ''){
        Session::put('failed','Sorry ! Please  Select Cabin Room And Try Again');
        return Redirect::to('ipdBillClearence');
        exit();
        }
        // checked not available
        }elseif($room_type == '2'){
        // bed romm validation
        if($ward_no == ''){
        Session::put('failed','Sorry ! Please  Select Ward And Try Again');
        return Redirect::to('ipdBillClearence');
        exit();
        }
        if($ward_bed == ''){
        Session::put('failed','Sorry ! Please  Select Bed And Try Again');
        return Redirect::to('ipdBillClearence');
        exit();
        }
     }
     #---------------------------- end validation ----------------------------------#
     #---------------------- DATE VALIDATION----------------------#
     if($ipdEndDate > $this->rcdate){
        Session::put('failed','Sorry ! Enter Wrong End Date . Please Enter Valid End Date And Try Again');
        return Redirect::to('ipdBillClearence');
        exit();
     }
     #--------------------- END DATE VALIDATIN---------------------------------#
     #--------------------- ipd patient information----------------------------#
     if($room_type == '1'){
     $cabin_book_info = DB::table('tbl_ipd_cabin_bed_history')->where('branch_id',$this->branch_id)->where('cabin_id',$cabin_room)->where('booked_status',0)->first();
        $ipd_admisison_id_is      = $cabin_book_info->ipd_admission_id ;
        $ipd_admisison_invoice_is = $cabin_book_info->invoice_number ;
        $patient_id               = $cabin_book_info->patient_id ;
        $cabin_type               = $cabin_book_info->cabin_type_id ;

        $cabin_room_amount_query  = DB::table('tbl_cabin_type')->where('branch_id',$this->branch_id)->where('id',$cabin_type)->limit(1)->first();
        $charge_amount_is         = $cabin_room_amount_query->charge_amount ;

     }elseif($room_type == '2'){
      $cabin_book_info = DB::table('tbl_ipd_cabin_bed_history')->where('branch_id',$this->branch_id)->where('ward_bed_id',$ward_bed )->where('booked_status',0)->first();
        $ipd_admisison_id_is      = $cabin_book_info->ipd_admission_id ;
        $ipd_admisison_invoice_is = $cabin_book_info->invoice_number ;
        $patient_id               = $cabin_book_info->patient_id ;
        $booked_ward_bed_id       = $cabin_book_info->ward_bed_id ; 
        $cabin_room_amount_query  = DB::table('tbl_ward_bed')->where('branch_id',$this->branch_id)->where('id',$booked_ward_bed_id)->limit(1)->first();
        $charge_amount_is         = $cabin_room_amount_query->charge_amount ;
     }

    // first check
    $fisrt_ipd_duplicate_chcek = DB::table('tbl_ipd_admission')->where('branch_id',$this->branch_id)->where('id',$ipd_admisison_id_is)->where('invoice',$ipd_admisison_invoice_is)->where('patient_id',$patient_id)->where('status',1)->count();
    if($fisrt_ipd_duplicate_chcek > 0){
        Session::put('failed','Sorry ! Bill Already Created Of This Patient');
        return Redirect::to('ipdBillClearence');
        exit();
    }
    // second check
    $second_ipd_duplicate_chcek = DB::table('tbl_ipd_clear_bill')->where('branch_id',$this->branch_id)->where('id',$ipd_admisison_id_is)->where('ipd_invoice_no',$ipd_admisison_invoice_is)->where('patient_id',$patient_id)->count();
    if($second_ipd_duplicate_chcek > 0){
        Session::put('failed','Sorry ! Bill Already Created Of This Patient');
        return Redirect::to('ipdBillClearence');
        exit();
    }
     // get ipd admission info
     $ipd_admit_query = DB::table('tbl_ipd_admission')->where('branch_id',$this->branch_id)->where('id',$ipd_admisison_id_is)->where('invoice',$ipd_admisison_invoice_is)->where('patient_id',$patient_id)->limit(1)->first();
     $patient_admit_date = $ipd_admit_query->admit_date ;
     $patient_admit_time = $ipd_admit_query->admit_time ;

     if($patient_admit_date > $ipdEndDate){
        Session::put('failed','Sorry ! Patient End Date Will Not Be Small Than Patient Admit Date');
        return Redirect::to('ipdBillClearence');
        exit();
     }

    //calculation by ipd ledger
    $ipd_ledger_info      = DB::table('tbl_ipd_ledger')->where('branch_id',$this->branch_id)->where('ipd_admission_id',$ipd_admisison_id_is)->where('patient_id',$patient_id)->orderBy('service_created_at','asc')->get();
  
    $start_date = date_create($patient_admit_date);
    $end_date   = date_create($ipdEndDate);
    //difference between two dates
    $diff = date_diff($start_date,$end_date);

   $cabin_or_bed_rent_days = $diff->format("%a") ;
   // total room charge amount
   if($room_type == '1'){
    $total_room_bed_charge_amount = $charge_amount_is * $cabin_or_bed_rent_days ;
   }elseif($room_type == '2'){
     $total_room_bed_charge_amount = $charge_amount_is * $cabin_or_bed_rent_days;

   }

    $cabin_room_details =  DB::table('tbl_cabin_room')
    ->join('tbl_building', 'tbl_cabin_room.building_id', '=', 'tbl_building.id')
    ->join('tbl_building_floor', 'tbl_cabin_room.floor_id', '=', 'tbl_building_floor.id')
    ->join('tbl_cabin_type', 'tbl_cabin_room.cabin_type_id', '=', 'tbl_cabin_type.id')
    ->select('tbl_cabin_room.*','tbl_building.building_name','tbl_building_floor.floor_name','tbl_cabin_type.cabin_type_name')
    ->where('tbl_cabin_room.branch_id',$this->branch_id)
    ->where('tbl_cabin_room.booked_status',1)
    ->where('tbl_cabin_room.id',$cabin_room)
    ->first();

    $ward_bed_details =  DB::table('tbl_ward_bed')
    ->where('branch_id',$this->branch_id)
    ->where('ward_id',$ward_no)
    ->where('id',$ward_bed)
    ->where('booked_status',1)
    ->first();

    $ward_no_details =  DB::table('tbl_ward')
    ->join('tbl_building', 'tbl_ward.building_id', '=', 'tbl_building.id')
    ->join('tbl_building_floor', 'tbl_ward.floor_id', '=', 'tbl_building_floor.id')
    ->select('tbl_ward.*','tbl_building.building_name','tbl_building_floor.floor_name')
    ->where('tbl_ward.branch_id',$this->branch_id)
    ->where('tbl_ward.id',$ward_no)
    ->first();

    $patient_info         = DB::table('tbl_patient')->where('branch_id',$this->branch_id)->where('id',$patient_id)->first();
    $pc      = DB::table('tbl_pc')->where('branch_id',$this->branch_id)->where('status',1)->get();

    return view('ipd.ipdClearenceLedgerPayment')->with('ipd_admit_query',$ipd_admit_query)->with('ipd_ledger_info',$ipd_ledger_info)->with('cabin_or_bed_rent_days',$cabin_or_bed_rent_days)->with('charge_amount_is',$charge_amount_is)->with('total_room_bed_charge_amount',$total_room_bed_charge_amount)->with('cabin_room_details',$cabin_room_details)->with('ward_no_details',$ward_no_details)->with('ward_bed_details',$ward_bed_details)->with('patient_info',$patient_info)->with('ipdEndDate',$ipdEndDate)->with('ipd_end_time',$ipd_end_time)->with('pc',$pc)->with('room_type',$room_type);
     #--------------------- end ipd patient information-------------------------#
    }
    // create ipd clearence bill
    public function createIpdClearenceBill(Request $request)
    {
    $branch                   = $this->branch_id ;
    $ipd_admission_id         = trim($request->ipd_admission_id) ;
    $ipd_admission_invoice    = trim($request->ipd_admission_invoice) ;
    $patient_id_is            = trim($request->patient_id_is) ;
    $bill_date                = trim($request->bill_date) ;
    $billDate                 = date('Y-m-d',strtotime($bill_date)) ;
    $ipd_end_date             = trim($request->ipd_end_date) ;
    $total_payable            = trim($request->total_payable) ;
    $total_discount           = trim($request->total_discount) ;
    $pc_id                    = trim($request->pc_id) ;
    $pc_amount                = trim($request->pc_amount) ;
    $total_bed_rent           = trim($request->total_bed_rent) ;
    $twenty_four_hour_end_time = trim($request->ipd_end_time); 
    $ipdEndTime                 = date("H:i:s", strtotime($twenty_four_hour_end_time));
    $now_payable_from_patient = $total_payable - $total_discount ; 
    #---------------------------- date validation ---------------------------#
    // bill date small than end date
    if($billDate < $ipd_end_date){
        echo "d1";
        exit();

    }
     if($billDate > $this->rcdate){
        echo "d2";
        exit();

    }
   #--------------------------- date validation-----------------------------#
   #--------------------------- ipd info validaton -------------------------#
    if($ipd_admission_id == ''){
      echo "i1";
      exit();
    }
      if($ipd_admission_invoice == ''){
      echo "i2";
      exit();
    }
      if($patient_id_is == ''){
      echo "i3";
      exit();
    }
   #--------------------------- end ipd info validation----------------------#
    if($now_payable_from_patient < 0){
      echo "i4";
      exit();
    }
    if($pc_id != 0){
      if($pc_amount > $now_payable_from_patient){
        echo "i5";
        exit();
      }
    }

    #----------------------------- alrady bill clearence----------------------------#
    // first check
    $fisrt_ipd_duplicate_chcek = DB::table('tbl_ipd_admission')->where('branch_id',$this->branch_id)->where('id',$ipd_admission_id)->where('invoice',$ipd_admission_invoice)->where('patient_id',$patient_id_is)->where('status',1)->count();
    if($fisrt_ipd_duplicate_chcek > 0){
      echo "dup1";
      exit();
    }
    // second check
    $second_ipd_duplicate_chcek = DB::table('tbl_ipd_clear_bill')->where('branch_id',$this->branch_id)->where('ipd_admission_id',$ipd_admission_id)->where('ipd_invoice_no',$ipd_admission_invoice)->where('patient_id',$patient_id_is)->count();
    if($second_ipd_duplicate_chcek > 0){
      echo "dup2";
      exit();
    }

    #----------------------------- end already bill clearnce-------------------------#
    // get admission info
    $admission_info = DB::table('tbl_ipd_admission')->where('branch_id',$this->branch_id)->where('id',$ipd_admission_id)->where('invoice',$ipd_admission_invoice)->where('patient_id',$patient_id_is)->where('status',0)->first();
    $admit_start_time = $admission_info->admit_time ;
    $admit_start_date = $admission_info->admit_date ;
    #----------------------------------- INSERT INTO CASHBOOK -----------------------#
     if($total_discount > 0){
      $purpose = "IPD Clearence Bill Create With Discount";
     }else{
      $purpose = "IPD Clearence Bill Create";
     } 
        // status = 15
        // tr status = 1 by cash transaction
        $data_cashbook                        = array();
        $data_cashbook['overall_branch_id']   = $this->branch_id ;
        $data_cashbook['branch_id']           = $this->branch_id ;
        $data_cashbook['admin_id']            = $this->loged_id  ;
        $data_cashbook['admin_type']          = 3 ;
        $data_cashbook['earn']                = $total_payable ;
        $data_cashbook['cost']                = $total_discount ;
        $data_cashbook['profit_earn']         = $total_payable ;
        $data_cashbook['profit_cost']         = $total_discount ;
        $data_cashbook['status']              = 15 ;
        $data_cashbook['tr_status']           = 1 ;
        $data_cashbook['purpose']             = $purpose;
        $data_cashbook['added_id']            = $this->loged_id;
        $data_cashbook['created_time']        = $this->current_time;
        $data_cashbook['created_at']          = $billDate;
        $data_cashbook['on_created_at']       = $this->rcdate;
        DB::table('cashbook')->insert($data_cashbook);
      #---------------------------------- END INSERT INTO CASHBOOK --------------------#
      #--------------------- GET LAST CASH BOOK ID  -----------------------------------#
     $last_cashbook_id_query = DB::table('cashbook')->orderBy('id','desc')->limit(1)->first();
     $last_cashbook_id       = $last_cashbook_id_query->id ; 
     #-------------------- GET LAST CASH BOOK ID --------------------------------------#
    #------------------- GET INVOICE NUMBER-------------------------------------------#
    // branch wise invoice
     $branch_invoice_count = DB::table('tbl_ipd_clear_bill')->where('branch_id',$branch)->orderBy('invoice','desc')->count();
     if($branch_invoice_count == '0'){
      $branch_invoice_number = 1 ;
     }else{
      $branch_invoice_query = DB::table('tbl_ipd_clear_bill')->where('branch_id',$branch)->orderBy('invoice','desc')->limit(1)->first();
      $branch_invoice_number = $branch_invoice_query->invoice + 1 ;
     }
    #-------------------- get year invoice------------------------------------#
    $branch_year_invoice_count = DB::table('tbl_ipd_clear_bill')->where('year',$this->current_year)->where('branch_id',$branch)->orderBy('year_invoice','desc')->count();
     if($branch_year_invoice_count == '0'){
      $branch_invoice_year_number = 1 ;
     }else{
      $branch_year_invoice_query = DB::table('tbl_ipd_clear_bill')->where('year',$this->current_year)->where('branch_id',$branch)->orderBy('year_invoice','desc')->limit(1)->first();
      $branch_invoice_year_number = $branch_year_invoice_query->year_invoice + 1 ;
     }
     #--------------------- daily invoice -------------------------------------#
      $branch_daily_invoice_count = DB::table('tbl_ipd_clear_bill')->where('bill_date',$this->rcdate)->where('branch_id',$branch)->orderBy('daily_invoice','desc')->count();
     if($branch_daily_invoice_count == '0'){
      $branch_daily_invoice_number = 1 ;
     }else{
      $branch_daily_invoice_query = DB::table('tbl_ipd_clear_bill')->where('bill_date',$this->rcdate)->where('branch_id',$branch)->orderBy('daily_invoice','desc')->limit(1)->first();
      $branch_daily_invoice_number = $branch_daily_invoice_query->daily_invoice + 1 ;
     }
    #------------------ END GET INVIOCE NUMBER ----------------------------------#
    #---------------------------- INSERT INTO IPD CLEAR BILL TABLE---------------#
    $data_clear_bill_insert                      = array();
    $data_clear_bill_insert['cashbook_id']       = $last_cashbook_id ;
    $data_clear_bill_insert['ipd_admission_id']  = $ipd_admission_id ;
    $data_clear_bill_insert['ipd_invoice_no']    = $ipd_admission_invoice ;
    $data_clear_bill_insert['year']              = $this->current_year;
    $data_clear_bill_insert['invoice']           = $branch_invoice_number;
    $data_clear_bill_insert['year_invoice']      = $branch_invoice_year_number;
    $data_clear_bill_insert['daily_invoice']     = $branch_daily_invoice_number ;
    $data_clear_bill_insert['branch_id']         = $branch ;
    $data_clear_bill_insert['patient_id']        = $patient_id_is;
    $data_clear_bill_insert['pc_id']             = $pc_id;
    $data_clear_bill_insert['purpose']           = 'IPD Clearence Bill Create'; 
    $data_clear_bill_insert['admit_time']        = $admit_start_time;
    $data_clear_bill_insert['end_time']          = $ipdEndTime ;
    $data_clear_bill_insert['admit_date']        = $admit_start_date  ;
    $data_clear_bill_insert['end_date']          = $ipd_end_date ;
    $data_clear_bill_insert['bill_time']         = $this->current_time ;
    $data_clear_bill_insert['bill_date']         = $billDate ;
    $data_clear_bill_insert['added_id']          = $this->loged_id;
    $data_clear_bill_insert['created_at']        = $this->rcdate ;
    DB::table('tbl_ipd_clear_bill')->insert($data_clear_bill_insert);
   #--------------------------- END INSERT INTO PATHYOLOGY BILL TABLE-------------#
   #------------------------ get last id of pathology bill-----------------------#
    $last_ipd_id_query = DB::table('tbl_ipd_clear_bill')->orderBy('id','desc')->limit(1)->first();
    $last_ipd_id       = $last_ipd_id_query->id ; 
   #------------------------ end get last id of patylogy bill --------------------#

   #--------------------------- INSERT IPD LEDGER BILL----------------------------#
    // insert bed info
   $data_ipd_ledger_rent                       = array();
   $data_ipd_ledger_rent['branch_id']          = $branch ;
   $data_ipd_ledger_rent['ipd_admission_id']   = $ipd_admission_id ;
   $data_ipd_ledger_rent['service_type']       = 5;
   $data_ipd_ledger_rent['service_id']         = $last_ipd_id ;
   $data_ipd_ledger_rent['service_invoice']    = $branch_invoice_number ; 
   $data_ipd_ledger_rent['patient_id']         = $patient_id_is;
   $data_ipd_ledger_rent['payable_amount']     = $total_bed_rent  ;
   $data_ipd_ledger_rent['payment_amount']     = 0;
   $data_ipd_ledger_rent['discount']           = 0;
   $data_ipd_ledger_rent['purpose']            = "Cabin / Bed Rent" ;
   //$data_ipd_ledger['remarks']            = $remarks ;
   $data_ipd_ledger_rent['added_id']           = $this->loged_id;
   $data_ipd_ledger_rent['created_time']       = $this->current_time;
   $data_ipd_ledger_rent['service_created_at'] = $billDate;
   $data_ipd_ledger_rent['created_at']         = $this->rcdate;
   DB::table('tbl_ipd_ledger')->insert($data_ipd_ledger_rent);
   // ipd collection 
   $data_ipd_ledger_clear                       = array();
   $data_ipd_ledger_clear['branch_id']          = $branch ;
   $data_ipd_ledger_clear['ipd_admission_id']   = $ipd_admission_id ;
   $data_ipd_ledger_clear['service_type']       = 6;
   $data_ipd_ledger_clear['service_id']         = $last_ipd_id ;
   $data_ipd_ledger_clear['service_invoice']    = $branch_invoice_number ; 
   $data_ipd_ledger_clear['patient_id']         = $patient_id_is;
   $data_ipd_ledger_clear['payable_amount']     = 0;
   $data_ipd_ledger_clear['payment_amount']     = $now_payable_from_patient;
   $data_ipd_ledger_clear['discount']           = $total_discount;
   $data_ipd_ledger_clear['purpose']            = $purpose ;
   //$data_ipd_ledger['remarks']            = $remarks ;
   $data_ipd_ledger_clear['added_id']           = $this->loged_id;
   $data_ipd_ledger_clear['created_time']       = $this->current_time;
   $data_ipd_ledger_clear['service_created_at'] = $billDate;
   $data_ipd_ledger_clear['created_at']         = $this->rcdate;
   DB::table('tbl_ipd_ledger')->insert($data_ipd_ledger_clear);
   #--------------------------- END INSERT IPD LEDGER BILL-------------------------#
   #--------------------------- STATUS CHANGE--------------------------------------#
   // Update admit status
   $data_admit_status_update             = array();
   $data_admit_status_update['end_time'] = $ipdEndTime ;
   $data_admit_status_update['end_date'] = $ipd_end_date ;
   $data_admit_status_update['status'] = 1 ;
   DB::table('tbl_ipd_admission')->where('branch_id',$this->branch_id)->where('id',$ipd_admission_id)->where('invoice',$ipd_admission_invoice)->where('patient_id',$patient_id_is)->update($data_admit_status_update);

    $room_type_query = DB::table('tbl_ipd_cabin_bed_history')->where('branch_id',$this->branch_id)->where('ipd_admission_id',$ipd_admission_id)->where('patient_id',$patient_id_is)->where('booked_status',0)->first();
     $room_type = $room_type_query->room_type ;

   // bed cabin histoty updat
  $data_bed_cabin_status_update = array();
  $data_bed_cabin_status_update['end_time'] = $ipdEndTime ;
  $data_bed_cabin_status_update['end_date'] = $ipd_end_date ;
   $data_bed_cabin_status_update['booked_status'] = 1 ;
    if($room_type == '1'){
      // get cabin room
      $booked_cabin_query = DB::table('tbl_ipd_cabin_bed_history')->where('branch_id',$this->branch_id)->where('ipd_admission_id',$ipd_admission_id)->where('patient_id',$patient_id_is)->where('room_type',1)->where('booked_status',0)->first();
      $cabin_room = $booked_cabin_query->cabin_id ;
      DB::table('tbl_ipd_cabin_bed_history')->where('branch_id',$this->branch_id)->where('ipd_admission_id',$ipd_admission_id)->where('cabin_id',$cabin_room)->where('patient_id',$patient_id_is)->where('room_type',1)->where('booked_status',0)->update($data_bed_cabin_status_update);
      // update cabin room table
      $data_cabin_room_update_status = array();
      $data_cabin_room_update_status['booked_status'] = 0 ;
      DB::table('tbl_cabin_room')->where('id',$cabin_room)->where('booked_status',1)->update($data_cabin_room_update_status);

     }elseif($room_type == '2'){
       $booked_bed_query = DB::table('tbl_ipd_cabin_bed_history')->where('branch_id',$this->branch_id)->where('ipd_admission_id',$ipd_admission_id)->where('patient_id',$patient_id_is)->where('room_type',2)->where('booked_status',0)->first();
       $ward_bed = $booked_bed_query->ward_bed_id ;
       DB::table('tbl_ipd_cabin_bed_history')->where('branch_id',$this->branch_id)->where('ipd_admission_id',$ipd_admission_id)->where('ward_bed_id',$ward_bed)->where('patient_id',$patient_id_is)->where('room_type',2)->where('booked_status',0)->update($data_bed_cabin_status_update); 
       // update bed room table
      $data_ward_bed_update_status = array();;
      $data_ward_bed_update_status['booked_status'] = 0 ;
      DB::table('tbl_ward_bed')->where('id',$ward_bed)->where('booked_status',1)->update($data_ward_bed_update_status);
     }

       if($pc_id != '0'){
      // insert the data into pc ledger
      $data_pc_ledger_insert                      = array();
      $data_pc_ledger_insert['cashbook_id']       = $last_cashbook_id ;
      $data_pc_ledger_insert['branch_id']         = $branch ;
      $data_pc_ledger_insert['invoice']           = $branch_invoice_number ;
      $data_pc_ledger_insert['year_invoice']      = $branch_invoice_year_number;
      $data_pc_ledger_insert['daily_invoice_number'] = $branch_daily_invoice_number;
      $data_pc_ledger_insert['invoice_type']      = 2;
      $data_pc_ledger_insert['pc_id']             = $pc_id ;
      $data_pc_ledger_insert['payable_amount']    = $pc_amount ;
      $data_pc_ledger_insert['status']            = 2 ; 
      $data_pc_ledger_insert['purpose']           = 'IPD Clearence Bill Create';
      $data_pc_ledger_insert['added_id']          = $this->loged_id; 
      $data_pc_ledger_insert['created_time']      = $this->current_time ;
      $data_pc_ledger_insert['created_at']        = $billDate ;
      $data_pc_ledger_insert['on_created_at']     = $this->rcdate;
      DB::table('pc_ledger')->insert($data_pc_ledger_insert);
      // update pc due
      $pc_due_query = DB::table('pc_due')->where('pc_id',$pc_id)->limit(1)->first();
      $pc_due_amount = $pc_due_query->total_due_amount ;
      $now_pc_due_amount = $pc_due_amount + $pc_amount ;
      // updte pc due amount
      $data_pc_due_update     = array();
      $data_pc_due_update['total_due_amount'] = $now_pc_due_amount; 
      DB::table('pc_due')->where('pc_id',$pc_id)->update($data_pc_due_update);
     }
     #-------------------------------- incress pettycash amount ------------------------------#
      $pettycash_amount = DB::table('pettycash')->where('branch_id',$branch)->where('type',3)->limit(1)->first();
      $current_pettycash_amt = $pettycash_amount->pettycash_amount ;
      $now_pettycash_amt     = $current_pettycash_amt + $now_payable_from_patient ;
      // update pettycash amount
      $data_update_pettycash                      = array();
      $data_update_pettycash['pettycash_amount']  = $now_pettycash_amt; 
      DB::table('pettycash')->where('branch_id',$branch)->where('type',3)->update($data_update_pettycash);
  #-------------------------------- end incress pettycash amount---------------------------#
     echo $last_ipd_id.'/'.$branch_invoice_number.'/'.$last_cashbook_id.'/'.$ipd_admission_id ;
   #--------------------------- END STATUS CHANGE----------------------------------#

    }

}
