<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests;
use DB;
use Session;

class OtController extends Controller
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
   // add ot room
   public function addOTRoom()
   {
   	return view('ot.addOTRoom');
   }
   // add ot room
   public function addOTRoomInfo(Request $request)
   {
   	 $this->validate($request, [
    'name'                    => 'required'
    ]);
     $name              = trim($request->name);
     $remarks           = trim($request->address);
     // duplicate check
     $count = DB::table('tbl_ot_room')->where('branch_id',$this->branch_id)->where('ot_room',$name)->count();
     if($count > 0){
     	Session::put('failed','Sorry ! OT Room Already Added. Try Again');
        return Redirect::to('addOTRoom');  
        exit();    
    }
    $data     				= array();
    $data['branch_id']  	= $this->branch_id ;
    $data['ot_room']        = $name ;
    $data['remarks']        = $remarks ;
    $data['created_at']     = $this->rcdate ;
    DB::table('tbl_ot_room')->insert($data);
    Session::put('succes','Thanks , OT Room Added Sucessfully');
    return Redirect::to('addOTRoom');
   }
   // manage ot room
   public function manageOTRoom()
   {
   	 $result = DB::table('tbl_ot_room')->where('branch_id',$this->branch_id)->get();
   	 return view('ot.manageOTRoom')->with('result',$result);

   }

   // add ot type by manager
   public function addOTtype()
   {
   	return view('ot.addOTtype');
   }
   // add ot type info
   public function addOTtypeInfo(Request $request)
   {
   	 $this->validate($request, [
    'name'                    => 'required'
    ]);
     $name              = trim($request->name);
     $remarks           = trim($request->address);
     // duplicate check
     $count = DB::table('tbl_ot_type')->where('branch_id',$this->branch_id)->where('ot_type',$name)->count();
     if($count > 0){
     	Session::put('failed','Sorry ! OT Type Name Already Added. Try Again');
        return Redirect::to('addOTtype');  
        exit();    
    }
    $data     				= array();
    $data['branch_id']  	= $this->branch_id ;
    $data['ot_type']        = $name ;
    $data['remarks']        = $remarks ;
    $data['created_at']     = $this->rcdate ;
    DB::table('tbl_ot_type')->insert($data);
    Session::put('succes','Thanks , OT Type Name Added Sucessfully');
    return Redirect::to('addOTtype');
   }
   // manage ot type
   public function manageOTtype()
   {
   	 $result = DB::table('tbl_ot_type')->where('branch_id',$this->branch_id)->get();
   	 return view('ot.manageOTtype')->with('result',$result);
   }
   // add ot service 
   public function addOTService()
   {
   	 $count = DB::table('tbl_ot_service')->where('branch_id',$this->branch_id)->count();
    if($count == '0'){
     $service_code = '' ;
    }else{
    $query    = DB::table('tbl_ot_service')->orderBy('service_code','desc')->limit(1)->first();
    $service_code = $query->service_code + 1 ;
    }
    $service_unit = DB::table('unit')->get();
   	 return view('ot.addOTService')->with('service_code',$service_code)->with('service_unit',$service_unit);
   }
   // add ot  service
   public function addOTServiceInfo(Request $request)
   {
   	 $this->validate($request, [
    'name'                  => 'required',
    'service_code'          => 'required',
    'unit'                  => 'required',
    'service_price'         => 'required',
    'confirm_service_price' => 'required',
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
        return Redirect::to('addOTService');
        exit();
     }
     #------------------------- duplicate test name / code check -------------------------------#
     $count = DB::table('tbl_ot_service')->where('branch_id',$this->branch_id)->where('service_name',$name)->count();
     if($count > 0){
      Session::put('failed','Sorry ! OT Service Name Already Exits');
      return Redirect::to('addOTService');
      exit();
     }
     // test code count
     $count1 = DB::table('tbl_ot_service')->where('branch_id',$this->branch_id)->where('service_code',$service_code)->count();
     if($count1 > 0){
      Session::put('failed','Sorry ! OT Service Code Already Exits');
      return Redirect::to('addOTService');
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
    DB::table('tbl_ot_service')->insert($data);
    Session::put('succes','Thanks , New OT Service Added Sucessfully');
    return Redirect::to('addOTService');
   }
   // manage ot serveice
   public function manageOTService()
   {
   	$result = DB::table('tbl_ot_service')->where('branch_id',$this->branch_id)->orderBy('id','desc')->get();
    return view('ot.manageOTService')->with('result',$result);
   }
   // ot booking
   public function OTBooking()
   {
   	 // get opd type
    $ot_type = DB::table('tbl_ot_type')->where('branch_id',$this->branch_id)->where('status',0)->get();
    $patient = DB::table('tbl_patient')->where('branch_id',$this->branch_id)->orderBy('id','desc')->get();
	return view('ot.OTBooking')->with('ot_type',$ot_type)->with('patient',$patient);
   }
   // patient ot admission
   public function patientOTAdmission(Request $request)
   {
   	 $this->validate($request, [
    'ot_type'                  => 'required',
    'bill_date'                => 'required',
    'patient_id'               => 'required',
    'paid_amount'              => 'required',
    'confirm_paid_amount'      => 'required',
    ]);
     $branch                = $this->branch_id ;
     $ot_type               = trim($request->ot_type);
     $bill_date             = trim($request->bill_date);
     $billDate              = date('Y-m-d',strtotime($bill_date)) ;
     $patient_id            = trim($request->patient_id);
     $patient_name          = trim($request->patient_name);
     $mobile_number         = trim($request->mobile_number);
     $age                   = trim($request->age);
     $sex                   = trim($request->sex);
     $care_of               = trim($request->care_of);
     $address               = trim($request->address);
     $paid_amount           = trim($request->paid_amount);
     $confirm_paid_amount   = trim($request->confirm_paid_amount);
     $remarks               = trim($request->remarks);
     #---------------------------- validation -------------------------------------#
     // patient
     if($patient_id == '0'){
        if($patient_name == ''){
        Session::put('failed','Sorry ! Please Enter Patient Name And Try Again');
        return Redirect::to('OTBooking');
        exit();
        }
     }
      #---------------------- DATE VALIDATION----------------------#
     if($billDate > $this->rcdate){
        Session::put('failed','Sorry ! Enter Wrong Booking Date . Please Enter Valid Booking Date And Try Again');
        return Redirect::to('OTBooking');
        exit();
     }
     #--------------------- END DATE VALIDATIN--------------------#
     if($paid_amount != $confirm_paid_amount){
        Session::put('failed','Sorry ! Paid Amount And Confirm Paid Amount Did Not Match . Try Again');
        return Redirect::to('OTBooking');
        exit(); 
     }
    #------------------- PATIENT INFO ------------------------------------------#
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
         $purpose = "OT Booking";
        // status = 16
        // tr status = 1 by cash transaction
        $data_cashbook                        = array();
        $data_cashbook['overall_branch_id']   = $this->branch_id ;
        $data_cashbook['branch_id']           = $this->branch_id ;
        $data_cashbook['admin_id']            = $this->loged_id  ;
        $data_cashbook['admin_type']          = 3 ;
        $data_cashbook['earn']                = $paid_amount ;
        $data_cashbook['profit_earn']         = $paid_amount ;
        $data_cashbook['status']              = 16 ;
        $data_cashbook['tr_status']           = 1 ;
        $data_cashbook['purpose']             = "Advance Payment Of OT";
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
     $branch_invoice_count = DB::table('tbl_ot_booking')->where('branch_id',$branch)->orderBy('invoice','desc')->count();
     if($branch_invoice_count == '0'){
      $branch_invoice_number = 1 ;
     }else{
      $branch_invoice_query = DB::table('tbl_ot_booking')->where('branch_id',$branch)->orderBy('invoice','desc')->limit(1)->first();
      $branch_invoice_number = $branch_invoice_query->invoice + 1 ;
     }
    #-------------------- get year invoice------------------------------------#
    $branch_year_invoice_count = DB::table('tbl_ot_booking')->where('year',$this->current_year)->where('branch_id',$branch)->orderBy('year_invoice','desc')->count();
     if($branch_year_invoice_count == '0'){
      $branch_invoice_year_number = 1 ;
     }else{
      $branch_year_invoice_query = DB::table('tbl_ot_booking')->where('year',$this->current_year)->where('branch_id',$branch)->orderBy('year_invoice','desc')->limit(1)->first();
      $branch_invoice_year_number = $branch_year_invoice_query->year_invoice + 1 ;
     }
     #--------------------- daily invoice -------------------------------------#
      $branch_daily_invoice_count = DB::table('tbl_ot_booking')->where('booking_date',$this->rcdate)->where('branch_id',$branch)->orderBy('daily_invoice','desc')->count();
     if($branch_daily_invoice_count == '0'){
      $branch_daily_invoice_number = 1 ;
     }else{
      $branch_daily_invoice_query = DB::table('tbl_ot_booking')->where('booking_date',$this->rcdate)->where('branch_id',$branch)->orderBy('daily_invoice','desc')->limit(1)->first();
      $branch_daily_invoice_number = $branch_daily_invoice_query->daily_invoice + 1 ;
     }
    #------------------ END GET INVIOCE NUMBER ----------------------------------#
    #-------------------- INSERT OT TABLE -----------------------------#
     $data_ot_insert                 = array();
     $data_ot_insert['cashbook_id']  = $last_cashbook_id ;
     $data_ot_insert['year']         = $this->current_year ;
     $data_ot_insert['invoice']      = $branch_invoice_number;
     $data_ot_insert['year_invoice'] = $branch_invoice_year_number;
     $data_ot_insert['daily_invoice']= $branch_daily_invoice_number;
     $data_ot_insert['branch_id']    = $branch ;
     $data_ot_insert['patient_id']   = $patient_primary_id_is;
     $data_ot_insert['ot_type']   	 = $ot_type;
     $data_ot_insert['purpose']      = $purpose;
     $data_ot_insert['remarks']      = $remarks;
     $data_ot_insert['added_id']     = $this->loged_id;
     $data_ot_insert['booking_date'] = $billDate ;
     $data_ot_insert['created_time'] = $this->current_time;
     $data_ot_insert['created_at']   = $this->rcdate ;
     DB::table('tbl_ot_booking')->insert($data_ot_insert);
      // last ipd admission id
     $last_ot_booking_query    = DB::table('tbl_ot_booking')->orderBy('id','desc')->limit(1)->first();
     $last_ot_booking_id       = $last_ot_booking_query->id ; 

   #------------------------- Insert into ipd ledger--------------------------------------#
   $data_ot_ledger                        = array();
   $data_ot_ledger['branch_id']          = $branch ;
   $data_ot_ledger['ot_booking_id']      = $last_ot_booking_id;
   $data_ot_ledger['service_type']       = 1;
   $data_ot_ledger['service_id']         = $last_ot_booking_id; ;
   $data_ot_ledger['service_invoice']    = $branch_invoice_number ; 
   $data_ot_ledger['patient_id']         = $patient_primary_id_is;
   $data_ot_ledger['payable_amount']     = 0 ;
   $data_ot_ledger['payment_amount']     = $paid_amount;
   $data_ot_ledger['purpose']            = $purpose ;
   $data_ot_ledger['remarks']            = $remarks ;
   $data_ot_ledger['added_id']           = $this->loged_id;
   $data_ot_ledger['created_time']       = $this->current_time;
   $data_ot_ledger['service_created_at'] = $billDate;
   $data_ot_ledger['created_at']         = $this->rcdate;
   DB::table('tbl_ot_ledger')->insert($data_ot_ledger);
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
   return Redirect::to('/printOTBookingInvoice/'.$last_ot_booking_id.'/'.$branch_invoice_number.'/'.$last_cashbook_id); 

   }
   #------------------------------------------ OT SURGICCAL AND CLINICAL POSTING -----------------------------#
   // OT SURJIECAL FORM 
   public function OTSurgeryClinicalPosting()
   {
   	// GET RUNNING OT
   	$running_ot =  DB::table('tbl_ot_booking')
    ->join('tbl_patient', 'tbl_ot_booking.patient_id', '=', 'tbl_patient.id')
    ->join('tbl_ot_type', 'tbl_ot_booking.ot_type', '=', 'tbl_ot_type.id')
    ->where('tbl_ot_booking.branch_id',$this->branch_id)
    ->where('tbl_ot_booking.status',0)
    ->select('tbl_ot_booking.*','tbl_patient.patient_name','tbl_patient.patient_number','tbl_patient.patient_mobile','tbl_patient.patient_age','tbl_patient.patient_sex','tbl_patient.address as patient_address','tbl_patient.c_o_name','tbl_ot_type.ot_type')
    ->get();
    // get ot room
    $ot_room = DB::table('tbl_ot_room')->where('branch_id',$this->branch_id)->get();
    // get main surjon
     $main_surjon =  DB::table('admin')
    ->join('tbl_doctor', 'admin.id', '=', 'tbl_doctor.admin_id')
    ->where('admin.branch_id',$this->branch_id)
    ->where('admin.status',1)
    ->where('tbl_doctor.doctor_type',2)
    ->select('admin.*','tbl_doctor.speialist')
    ->get();
      // get anesthia
     $anes_surjon =  DB::table('admin')
    ->join('tbl_doctor', 'admin.id', '=', 'tbl_doctor.admin_id')
    ->where('admin.branch_id',$this->branch_id)
    ->where('admin.status',1)
    ->where('tbl_doctor.doctor_type',4)
    ->select('admin.*','tbl_doctor.speialist')
    ->get();
    $assistant_surjon =  DB::table('admin')
    ->join('tbl_doctor', 'admin.id', '=', 'tbl_doctor.admin_id')
    ->where('admin.branch_id',$this->branch_id)
    ->where('admin.status',1)
    ->where('tbl_doctor.doctor_type',3)
    ->select('admin.*','tbl_doctor.speialist')
    ->get();
    // ot assistant
    $ot_assistant = DB::table('tbl_ot_staff')->where('branch_id',$this->branch_id)->get();

   	 return view('ot.OTSurgeryClinicalPosting')->with('running_ot',$running_ot)->with('ot_room',$ot_room)->with('main_surjon',$main_surjon)->with('anes_surjon',$anes_surjon)->with('assistant_surjon',$assistant_surjon)->with('ot_assistant',$ot_assistant);
   }
   // patient ot surgery staff bill
   public function patientOTSuergeryStaffBill(Request $request)
   {
    $this->validate($request, [
    'ot_booking_id'          => 'required',
    'ot_room'                => 'required',
    'bill_date'              => 'required',
    'ot_time'                => 'required',
    'main_surjon_fee'        => 'required',
    'anes_fee'               => 'required',
    'assistant_surjon_fee'   => 'required',
    'ot_assistant_fee'       => 'required',
    'grand_total_charge_amount'   => 'required',
    'grand_total_discount_amount' => 'required',
    'now_payable'                 => 'required',
    ]);
     $branch                = $this->branch_id ;
     $ot_booking_id         = trim($request->ot_booking_id);
     $ot_room               = trim($request->ot_room);
     $bill_date             = trim($request->bill_date);
     $billDate              = date('Y-m-d',strtotime($bill_date)) ;
     $main_surjon_fee       = trim($request->main_surjon_fee);
     $anes_fee              = trim($request->anes_fee);
     $assistant_surjon_fee  = trim($request->assistant_surjon_fee);
     $ot_assistant_fee      = trim($request->ot_assistant_fee);
     $grand_total_discount_amount = trim($request->grand_total_discount_amount);
     $main_surjon           = $request->main_surjon;
     $anes_surjon           = $request->anes_surjon;
     $assistant_surjon      = $request->assistant_surjon;
     $ot_assistant          = $request->ot_assistant;
     $other_ot_info         = $request->other_ot_info;
     $remarks               = trim($request->remarks);
     $twenty_four_hour_ot_time = trim($request->ot_time); 
     $otTime                   = date("H:i:s", strtotime($twenty_four_hour_ot_time));
    #------------------------------- GET OT INFO --------------------------------------#
     $ot_booking_info    = DB::table('tbl_ot_booking')->where('id',$ot_booking_id)->first();
     $patient_id         = $ot_booking_info->patient_id ;
     $ot_booked_date     = $ot_booking_info->booking_date ;
     $ot_booked_invoice  = $ot_booking_info->invoice ;
    #------------------------------- END GET OT INFO------------------------------------#
    #------------------------------- payable amount ------------------------------------#
    $grand_toatl_payable_amount_sum = $main_surjon_fee + $anes_fee + $assistant_surjon_fee + $ot_assistant_fee ;

    $get_payble_without_discount = $grand_toatl_payable_amount_sum - $grand_total_discount_amount ;
    if($get_payble_without_discount < 0){
        Session::put('failed','Sorry ! Subtotal Amount Will Not Be Mininus Figure');
        return Redirect::to('OTSurgeryClinicalPosting');
        exit();
    }

    #------------------------------- end payable amount ---------------------------------#
    #------------------------- date validation -----------------------------------------#
    if($billDate > $this->rcdate){
        Session::put('failed','Sorry ! Enter Wrong OT Date . Please Enter Valid OT Date And Try Again');
        return Redirect::to('OTSurgeryClinicalPosting');
        exit();
     }
       if($billDate < $ot_booked_date){
        Session::put('failed','Sorry ! OT Date Will Not Be Small Than OT Booking Date. Please Given Correct Info And Try Again ');
        return Redirect::to('OTSurgeryClinicalPosting');
        exit();
     }
     #------------------------- end date validation -----------------------------------------------#
     #------------------------- duplicate check ---------------------------------------------------#
     $count_dup =  DB::table('tbl_ot_serjeun_staff_bill')->where('id',$ot_booking_id)->where('patient_id',$patient_id)->count() ;
     if($count_dup > 0){
        Session::put('failed','Sorry ! Suergery And Othere Staff Already Posting. No Need To Again Posting. You Can Assign OT Service');
        return Redirect::to('OTSurgeryClinicalPosting');
        exit();
     }
     #------------------------- end duplicate check-------------------------------------------------#
     #------------------------- array value check --------------------------------------------------#
     if(count($main_surjon) == '0'){
        Session::put('failed','Sorry ! Please Select At Least One Main Sujeon ');
        return Redirect::to('OTSurgeryClinicalPosting');
        exit();
     }
      if(count($anes_surjon) == '0'){
        Session::put('failed','Sorry ! Please Select At Least One Anesthesia');
        return Redirect::to('OTSurgeryClinicalPosting');
        exit();
     }
        if(count($assistant_surjon) == '0'){
        Session::put('failed','Sorry ! Please Select At Least One Assistant Surjeon');
        return Redirect::to('OTSurgeryClinicalPosting');
        exit();
     }
      if(count($ot_assistant) == '0'){
        Session::put('failed','Sorry ! Please Select At Least OT Assistant');
        return Redirect::to('OTSurgeryClinicalPosting');
        exit();
     }
     $purpose = "OT Suergery Staff Charge Amount";

     #-------------------------- end array value check ----------------------------------------------#
      #------------------- GET INVOICE NUMBER-------------------------------------------#
    // branch wise invoice
     $branch_invoice_count = DB::table('tbl_ot_serjeun_staff_bill')->where('branch_id',$branch)->orderBy('invoice','desc')->count();
     if($branch_invoice_count == '0'){
      $branch_invoice_number = 1 ;
     }else{
      $branch_invoice_query = DB::table('tbl_ot_serjeun_staff_bill')->where('branch_id',$branch)->orderBy('invoice','desc')->limit(1)->first();
      $branch_invoice_number = $branch_invoice_query->invoice + 1 ;
     }
    #-------------------- get year invoice------------------------------------#
    $branch_year_invoice_count = DB::table('tbl_ot_serjeun_staff_bill')->where('year',$this->current_year)->where('branch_id',$branch)->orderBy('year_invoice','desc')->count();
     if($branch_year_invoice_count == '0'){
      $branch_invoice_year_number = 1 ;
     }else{
      $branch_year_invoice_query = DB::table('tbl_ot_serjeun_staff_bill')->where('year',$this->current_year)->where('branch_id',$branch)->orderBy('year_invoice','desc')->limit(1)->first();
      $branch_invoice_year_number = $branch_year_invoice_query->year_invoice + 1 ;
     }
     #--------------------- daily invoice -------------------------------------#
      $branch_daily_invoice_count = DB::table('tbl_ot_serjeun_staff_bill')->where('ot_date',$this->rcdate)->where('branch_id',$branch)->orderBy('daily_invoice','desc')->count();
     if($branch_daily_invoice_count == '0'){
      $branch_daily_invoice_number = 1 ;
     }else{
      $branch_daily_invoice_query = DB::table('tbl_ot_serjeun_staff_bill')->where('ot_date',$this->rcdate)->where('branch_id',$branch)->orderBy('daily_invoice','desc')->limit(1)->first();
      $branch_daily_invoice_number = $branch_daily_invoice_query->daily_invoice + 1 ;
     }
    #------------------ END GET INVIOCE NUMBER ------------------------------------------------------#

    #----------------------------tbl_ot_serjeun_staff_bill-------------------------------------------#
     $data_serjun_staff_bill                          = array();
     $data_serjun_staff_bill['ot_booked_id']          = $ot_booking_id  ;
     $data_serjun_staff_bill['ot_booked_invoice_no']  = $ot_booked_invoice ;
     $data_serjun_staff_bill['year']                  = $this->current_year ;
     $data_serjun_staff_bill['invoice']               = $branch_invoice_number ;
     $data_serjun_staff_bill['year_invoice']          = $branch_invoice_year_number ;
     $data_serjun_staff_bill['daily_invoice']         = $branch_daily_invoice_number;
     $data_serjun_staff_bill['branch_id']             = $branch ;
     $data_serjun_staff_bill['patient_id']            = $patient_id ;
     $data_serjun_staff_bill['purpose']               = $purpose;
     $data_serjun_staff_bill['remarks']               = $remarks ;
     $data_serjun_staff_bill['ot_staff_info']         = $other_ot_info ;
     $data_serjun_staff_bill['ot_time']               = $otTime ;
     $data_serjun_staff_bill['ot_date']               = $billDate ;
     $data_serjun_staff_bill['added_id']              = $this->loged_id;
     $data_serjun_staff_bill['created_at']            = $this->rcdate;
     DB::table('tbl_ot_serjeun_staff_bill')->insert($data_serjun_staff_bill); 
     // get last id
     $last_ot_serjeun_staff_query = DB::table('tbl_ot_serjeun_staff_bill')->orderBy('id','desc')->limit(1)->first();
     $last_ot_serjeun_staff_id    = $last_ot_serjeun_staff_query->id ;
     #---------------------------- end tbl _ot_serjeun_staff_bill -------------------------------------#
     #-------------------------------- tbl _ot _staff_type_ledget_amount-------------------------------#
     // main surjon
     $data_ot_staff_type_main_sujon_ledger                          = array();
     $data_ot_staff_type_main_sujon_ledger['ot_booked_id']          = $ot_booking_id ;
     $data_ot_staff_type_main_sujon_ledger['ot_booked_invoice_no']  = $ot_booked_invoice;
     $data_ot_staff_type_main_sujon_ledger['branch_id']             = $branch ;;
     $data_ot_staff_type_main_sujon_ledger['patient_id']            = $patient_id ;
     $data_ot_staff_type_main_sujon_ledger['staff_type']            = 2 ;
     $data_ot_staff_type_main_sujon_ledger['total_charge_amount']   = $main_surjon_fee ;
     $data_ot_staff_type_main_sujon_ledger['purpose']               = "Total Charge Amount" ;
     $data_ot_staff_type_main_sujon_ledger['ot_time']               = $otTime ;
     $data_ot_staff_type_main_sujon_ledger['ot_date']               = $billDate ;
     $data_ot_staff_type_main_sujon_ledger['added_id']              = $this->loged_id;
     $data_ot_staff_type_main_sujon_ledger['created_at']            = $this->rcdate;
     DB::table('tbl_ot_staff_type_amount')->insert($data_ot_staff_type_main_sujon_ledger);

     // assistant serjoun
     $data_ot_staff_type_assistant_sujon_ledger                          = array();
     $data_ot_staff_type_assistant_sujon_ledger['ot_booked_id']          = $ot_booking_id ;
     $data_ot_staff_type_assistant_sujon_ledger['ot_booked_invoice_no']  = $ot_booked_invoice;
     $data_ot_staff_type_assistant_sujon_ledger['branch_id']             = $branch ;;
     $data_ot_staff_type_assistant_sujon_ledger['patient_id']            = $patient_id ;
     $data_ot_staff_type_assistant_sujon_ledger['staff_type']            = 3 ;
     $data_ot_staff_type_assistant_sujon_ledger['total_charge_amount']   = $assistant_surjon_fee ;
     $data_ot_staff_type_assistant_sujon_ledger['purpose']               = "Total Charge Amount" ;
     $data_ot_staff_type_assistant_sujon_ledger['ot_time']               = $otTime ;
     $data_ot_staff_type_assistant_sujon_ledger['ot_date']               = $billDate ;
     $data_ot_staff_type_assistant_sujon_ledger['added_id']              = $this->loged_id;
     $data_ot_staff_type_assistant_sujon_ledger['created_at']            = $this->rcdate;
     DB::table('tbl_ot_staff_type_amount')->insert($data_ot_staff_type_assistant_sujon_ledger);
      // anesthisia
     $data_ot_staff_type_anesthisia_sujon_ledger                          = array();
     $data_ot_staff_type_anesthisia_sujon_ledger['ot_booked_id']          = $ot_booking_id ;
     $data_ot_staff_type_anesthisia_sujon_ledger['ot_booked_invoice_no']  = $ot_booked_invoice;
     $data_ot_staff_type_anesthisia_sujon_ledger['branch_id']             = $branch ;;
     $data_ot_staff_type_anesthisia_sujon_ledger['patient_id']            = $patient_id ;
     $data_ot_staff_type_anesthisia_sujon_ledger['staff_type']            = 4 ;
     $data_ot_staff_type_anesthisia_sujon_ledger['total_charge_amount']   = $anes_fee ;
     $data_ot_staff_type_anesthisia_sujon_ledger['purpose']               = "Total Charge Amount" ;
     $data_ot_staff_type_anesthisia_sujon_ledger['ot_time']               = $otTime ;
     $data_ot_staff_type_anesthisia_sujon_ledger['ot_date']               = $billDate ;
     $data_ot_staff_type_anesthisia_sujon_ledger['added_id']              = $this->loged_id;
     $data_ot_staff_type_anesthisia_sujon_ledger['created_at']            = $this->rcdate;
     DB::table('tbl_ot_staff_type_amount')->insert($data_ot_staff_type_anesthisia_sujon_ledger);
     // ot assistant
      $data_ot_staff_type_ot_assistant_ledger                          = array();
     $data_ot_staff_type_ot_assistant_ledger['ot_booked_id']          = $ot_booking_id ;
     $data_ot_staff_type_ot_assistant_ledger['ot_booked_invoice_no']  = $ot_booked_invoice;
     $data_ot_staff_type_ot_assistant_ledger['branch_id']             = $branch ;
     $data_ot_staff_type_ot_assistant_ledger['patient_id']            = $patient_id ;
     $data_ot_staff_type_ot_assistant_ledger['staff_type']            = 5 ;
     $data_ot_staff_type_ot_assistant_ledger['total_charge_amount']   = $ot_assistant_fee ;
     $data_ot_staff_type_ot_assistant_ledger['purpose']               = "Total Charge Amount" ;
     $data_ot_staff_type_ot_assistant_ledger['ot_time']               = $otTime ;
     $data_ot_staff_type_ot_assistant_ledger['ot_date']               = $billDate ;
     $data_ot_staff_type_ot_assistant_ledger['added_id']              = $this->loged_id;
     $data_ot_staff_type_ot_assistant_ledger['created_at']            = $this->rcdate;
     DB::table('tbl_ot_staff_type_amount')->insert($data_ot_staff_type_ot_assistant_ledger);
   #-------------------------------- end tbl _ot staff type ledget amount-----------------------------#
   #-------------------------------- ot staff info ---------------------------------------------------#
     // main surjjon
     foreach ($main_surjon as $main_sujon_value) {
      $data_main_surjon_inf                         = array();
      $data_main_surjon_inf['ot_booked_id']         = $ot_booking_id ;
      $data_main_surjon_inf['ot_booked_invoice_no'] = $ot_booked_invoice;
      $data_main_surjon_inf['branch_id']            = $branch ;
      $data_main_surjon_inf['patient_id']           = $patient_id ;
      $data_main_surjon_inf['staff_type']           = 2 ;
      $data_main_surjon_inf['staff_id']             = $main_sujon_value ;
      $data_main_surjon_inf['ot_time']              = $otTime ;
      $data_main_surjon_inf['ot_date']              = $billDate ;
      $data_main_surjon_inf['added_id']             = $this->loged_id;
      $data_main_surjon_inf['created_at']           = $this->rcdate;
       DB::table('tbl_ot_staff_info')->insert($data_main_surjon_inf);
   
     }
     // assistant surjon
      foreach ($assistant_surjon as $assistant_surjon_value) {
      $data_asssiatant_surjon_inf                         = array();
      $data_asssiatant_surjon_inf['ot_booked_id']         = $ot_booking_id ;
      $data_asssiatant_surjon_inf['ot_booked_invoice_no'] = $ot_booked_invoice;
      $data_asssiatant_surjon_inf['branch_id']            = $branch ;
      $data_asssiatant_surjon_inf['patient_id']           = $patient_id ;
      $data_asssiatant_surjon_inf['staff_type']           = 3 ;
      $data_asssiatant_surjon_inf['staff_id']             = $assistant_surjon_value ;
      $data_asssiatant_surjon_inf['ot_time']              = $otTime ;
      $data_asssiatant_surjon_inf['ot_date']              = $billDate ;
      $data_asssiatant_surjon_inf['added_id']             = $this->loged_id;
      $data_asssiatant_surjon_inf['created_at']           = $this->rcdate;
       DB::table('tbl_ot_staff_info')->insert($data_asssiatant_surjon_inf);
   
     }
     // anesting
       foreach ($anes_surjon as $anes_surjon_value) {
      $data_anes_surjon_inf                         = array();
      $data_anes_surjon_inf['ot_booked_id']         = $ot_booking_id ;
      $data_anes_surjon_inf['ot_booked_invoice_no'] = $ot_booked_invoice;
      $data_anes_surjon_inf['branch_id']            = $branch ;
      $data_anes_surjon_inf['patient_id']           = $patient_id ;
      $data_anes_surjon_inf['staff_type']           = 4 ;
      $data_anes_surjon_inf['staff_id']             = $anes_surjon_value ;
      $data_anes_surjon_inf['ot_time']              = $otTime ;
      $data_anes_surjon_inf['ot_date']              = $billDate ;
      $data_anes_surjon_inf['added_id']             = $this->loged_id;
      $data_anes_surjon_inf['created_at']           = $this->rcdate;
       DB::table('tbl_ot_staff_info')->insert($data_anes_surjon_inf);
   
     }
     //ot assistant
      foreach ($ot_assistant as $ot_assistant_value) {
      $data_ot_assistnat_inf                         = array();
      $data_ot_assistnat_inf['ot_booked_id']         = $ot_booking_id ;
      $data_ot_assistnat_inf['ot_booked_invoice_no'] = $ot_booked_invoice;
      $data_ot_assistnat_inf['branch_id']            = $branch ;
      $data_ot_assistnat_inf['patient_id']           = $patient_id ;
      $data_ot_assistnat_inf['staff_type']           = 5 ;
      $data_ot_assistnat_inf['staff_id']             = $ot_assistant_value ;
      $data_ot_assistnat_inf['ot_time']              = $otTime ;
      $data_ot_assistnat_inf['ot_date']              = $billDate ;
      $data_ot_assistnat_inf['added_id']             = $this->loged_id;
      $data_ot_assistnat_inf['created_at']           = $this->rcdate;
      DB::table('tbl_ot_staff_info')->insert($data_ot_assistnat_inf);
   
     }
    #-------------------------------- end ot staff info------------------------------------------------#
    #------------------------------- inseet ot ledger--------------------------------------------------#
   $data_ot_ledger                       = array();
   $data_ot_ledger['branch_id']          = $branch ;
   $data_ot_ledger['ot_booking_id']      = $ot_booking_id;
   $data_ot_ledger['service_type']       = 2;
   $data_ot_ledger['service_id']         = $last_ot_serjeun_staff_id ;
   $data_ot_ledger['service_invoice']    = $branch_invoice_number ; 
   $data_ot_ledger['patient_id']         = $patient_id;
   $data_ot_ledger['payable_amount']     = $grand_toatl_payable_amount_sum ;
   $data_ot_ledger['payment_amount']     = 0 ;
   $data_ot_ledger['discount']           = $grand_total_discount_amount ;
   $data_ot_ledger['purpose']            = 'Total Surjeon And Staff Charge Amount' ;
   $data_ot_ledger['remarks']            = $remarks ;
   $data_ot_ledger['added_id']           = $this->loged_id;
   $data_ot_ledger['created_time']       = $this->current_time;
   $data_ot_ledger['service_created_at'] = $billDate;
   $data_ot_ledger['created_at']         = $this->rcdate;
   DB::table('tbl_ot_ledger')->insert($data_ot_ledger);
  #------------------------------- end insert ot ledger ------------------------------------------------------#
    Session::put('succes','Thanks , OT Surjeon And Posting Added Sucessfully');
    return Redirect::to('OTSurgeryClinicalPosting');
   }
   #------------------------------------------ END OT SURGICAL AND CLINICAL POSTING ---------------------------#
   #------------------------------------------ OT SERVICE -------------------------- --------------------------#
    public function OTserviceBill()
    {
    // get running 
    $running_ot =  DB::table('tbl_ot_booking')
    ->join('tbl_patient', 'tbl_ot_booking.patient_id', '=', 'tbl_patient.id')
    ->join('tbl_ot_type', 'tbl_ot_booking.ot_type', '=', 'tbl_ot_type.id')
    ->where('tbl_ot_booking.branch_id',$this->branch_id)
    ->where('tbl_ot_booking.status',0)
    ->select('tbl_ot_booking.*','tbl_patient.patient_name','tbl_patient.patient_number','tbl_patient.patient_mobile','tbl_patient.patient_age','tbl_patient.patient_sex','tbl_patient.address as patient_address','tbl_patient.c_o_name','tbl_ot_type.ot_type')
    ->get();
    $result  = DB::table('tbl_ot_service')->where('branch_id',$this->branch_id)->where('status',0)->get();
    return view('ot.OTserviceBill')->with('running_ot',$running_ot)->with('result',$result);
    }
    // get ot service price for create bill
    public function getOTServicePrice(Request $request)
    {
     $service_id = trim($request->service_id);
     $query      = DB::table('tbl_ot_service')->where('branch_id',$this->branch_id)->where('id',$service_id)->take(1)->first();
     $price      = $query->service_price;
     echo $price ;
    }
    // create ot service bill
    public function createOTServiceBill(Request $request)
    {
     $branch                 = $this->branch_id ;
     $ot_booking_id          = trim($request->ot_booking_id);
     $bill_date              = trim($request->bill_date);
     $billDate               = date('Y-m-d',strtotime($bill_date)) ;
     $total_amount           = trim($request->total_amount);
     $total_discount         = trim($request->total_discount);
     $array_invoice_data     = $request->arr; 
     $invoice_datas          = json_decode($array_invoice_data);
     $payableAmount          = $total_amount - $total_discount ;
     #------------------------ ot booking info ----------------------------#
     $ot_booking_query = DB::table('tbl_ot_booking')->where('id',$ot_booking_id)->where('branch_id',$branch)->where('status',0)->limit(1)->first();
     $booking_invoice   = $ot_booking_query->invoice ;
     $ot_booked_date    = $ot_booking_query->booking_date ;
     $patient_id        = $ot_booking_query->patient_id ;

     #------------------------ end ot booking info--------------------------#

     #------------------------- date validation ----------------------------#
       if($billDate > $this->rcdate){
          echo "d1";
          exit();
          }
      if($billDate < $ot_booked_date){
        echo "d2";
        exit();
     }
     #------------------------- end date validation -------------------------#

     #------------------------- amoun validation-----------------------------#
     if($payableAmount < 0){
       // paid amount not big than total amount
       echo "p1";
       exit();
        }
     #------------------------- end amount validation ------------------------#

     #------------------------- duplicate check -------------------------------#
      $count_dup = DB::table('tbl_ot_service_bill')->where('branch_id',$branch)->where('ot_booking_id',$ot_booking_id)->where('ot_invoice_no',$booking_invoice)->where('patient_id',$patient_id)->count();
      if($count_dup > 0){
        echo "dup1";
        exit();
      }
     #------------------------- end duplicate  check ---------------------------#
    #------------------- GET INVOICE NUMBER-------------------------------------------#
    // branch wise invoice
     $branch_invoice_count = DB::table('tbl_ot_service_bill')->where('branch_id',$branch)->orderBy('invoice','desc')->count();
     if($branch_invoice_count == '0'){
      $branch_invoice_number = 1 ;
     }else{
      $branch_invoice_query = DB::table('tbl_ot_service_bill')->where('branch_id',$branch)->orderBy('invoice','desc')->limit(1)->first();
      $branch_invoice_number = $branch_invoice_query->invoice + 1 ;
     }
    #-------------------- get year invoice------------------------------------#
    $branch_year_invoice_count = DB::table('tbl_ot_service_bill')->where('year',$this->current_year)->where('branch_id',$branch)->orderBy('year_invoice','desc')->count();
     if($branch_year_invoice_count == '0'){
      $branch_invoice_year_number = 1 ;
     }else{
      $branch_year_invoice_query = DB::table('tbl_ot_service_bill')->where('year',$this->current_year)->where('branch_id',$branch)->orderBy('year_invoice','desc')->limit(1)->first();
      $branch_invoice_year_number = $branch_year_invoice_query->year_invoice + 1 ;
     }
     #--------------------- daily invoice -------------------------------------#
      $branch_daily_invoice_count = DB::table('tbl_ot_service_bill')->where('bill_date',$this->rcdate)->where('branch_id',$branch)->orderBy('daily_invoice','desc')->count();
     if($branch_daily_invoice_count == '0'){
      $branch_daily_invoice_number = 1 ;
     }else{
      $branch_daily_invoice_query = DB::table('tbl_ot_service_bill')->where('bill_date',$this->rcdate)->where('branch_id',$branch)->orderBy('daily_invoice','desc')->limit(1)->first();
      $branch_daily_invoice_number = $branch_daily_invoice_query->daily_invoice + 1 ;
     }
    #------------------ END GET INVIOCE NUMBER ----------------------------------#
    #---------------------------- INSERT INTO PATHOLOGY BILL TABLE---------------#
    $data_ot_service_bill_insert                      = array();
    $data_ot_service_bill_insert['ot_booking_id']     = $ot_booking_id ;
    $data_ot_service_bill_insert['ot_invoice_no']     = $booking_invoice ;
    $data_ot_service_bill_insert['year']              = $this->current_year;
    $data_ot_service_bill_insert['invoice']           = $branch_invoice_number;
    $data_ot_service_bill_insert['year_invoice']      = $branch_invoice_year_number;
    $data_ot_service_bill_insert['daily_invoice']     = $branch_daily_invoice_number ;
    $data_ot_service_bill_insert['branch_id']         = $branch ;;
    $data_ot_service_bill_insert['patient_id']        = $patient_id ;
    $data_ot_service_bill_insert['purpose']           = 'OT Service Bill Create';   
    $data_ot_service_bill_insert['bill_time']         = $this->current_time ;
    $data_ot_service_bill_insert['bill_date']         = $billDate ;
    $data_ot_service_bill_insert['added_id']          = $this->loged_id;
    $data_ot_service_bill_insert['created_at']        = $this->rcdate ;
    DB::table('tbl_ot_service_bill')->insert($data_ot_service_bill_insert);
   #--------------------------- END INSERT INTO PATHYOLOGY BILL TABLE-------------#
    #------------------------ get last id of pathology bill-----------------------#
    $last_ot_id_query = DB::table('tbl_ot_service_bill')->orderBy('id','desc')->limit(1)->first();
    $last_ot_id       = $last_ot_id_query->id ; 
   #------------------------ end get last id of patylogy bill --------------------#
    foreach ($invoice_datas as $product_info) {
    $test_id            = $product_info[0]; 
    $sale_price         = $product_info[3];
    $sub_quantity       = $product_info[1];
    $sub_total_price    = $product_info[4];

     $data_ot_bill_item_insert                                = array();
     $data_ot_bill_item_insert['ot_booking_id']        = $ot_booking_id ;
     $data_ot_bill_item_insert['ot_booked_invoice_no'] = $booking_invoice ;
     $data_ot_bill_item_insert['invoice_number']       = $branch_invoice_number;
     $data_ot_bill_item_insert['year_invoice_number']  = $branch_invoice_year_number;
     $data_ot_bill_item_insert['daily_invoice_number'] = $branch_daily_invoice_number;
     $data_ot_bill_item_insert['branch_id']            = $branch;
     $data_ot_bill_item_insert['service_id']           = $test_id ;
     $data_ot_bill_item_insert['service_price']        = $sale_price;
     $data_ot_bill_item_insert['total_quantity']       = $sub_quantity;
     $data_ot_bill_item_insert['total_price']          = $sub_total_price ;
     $data_ot_bill_item_insert['bill_date']            = $billDate ;
     $data_ot_bill_item_insert['added_id']             = $this->loged_id;
     $data_ot_bill_item_insert['created_time']         = $this->current_time ;
     $data_ot_bill_item_insert['created_at']           = $this->rcdate;
     DB::table('tbl_ot_service_bill_item')->insert($data_ot_bill_item_insert);
     }
     // ot ledger
   $data_ot_ledger                       = array();
   $data_ot_ledger['branch_id']          = $branch ;
   $data_ot_ledger['ot_booking_id']      = $ot_booking_id ;
   $data_ot_ledger['service_type']       = 3;
   $data_ot_ledger['service_id']         = $last_ot_id ;
   $data_ot_ledger['service_invoice']    = $branch_invoice_number ; 
   $data_ot_ledger['patient_id']         = $patient_id;
   $data_ot_ledger['payable_amount']     = $total_amount ;
   $data_ot_ledger['discount']           = $total_discount;
   $data_ot_ledger['purpose']            = 'OT Service Bill Create' ;
   $data_ot_ledger['added_id']           = $this->loged_id;
   $data_ot_ledger['created_time']       = $this->current_time;
   $data_ot_ledger['service_created_at'] = $billDate;
   $data_ot_ledger['created_at']         = $this->rcdate;
   DB::table('tbl_ot_ledger')->insert($data_ot_ledger);
   echo "ok";
    }
   #------------------------------------------ END OT SEVICE ---------------------------- -----------------------#
   #------------------------------------------ START OT BILL CLEARENCE ------------------------------------------#
    public function OTBillClearence()
    {
    // get running 
    $running_ot =  DB::table('tbl_ot_booking')
    ->join('tbl_patient', 'tbl_ot_booking.patient_id', '=', 'tbl_patient.id')
    ->join('tbl_ot_type', 'tbl_ot_booking.ot_type', '=', 'tbl_ot_type.id')
    ->where('tbl_ot_booking.branch_id',$this->branch_id)
    ->where('tbl_ot_booking.status',0)
    ->select('tbl_ot_booking.*','tbl_patient.patient_name','tbl_patient.patient_number','tbl_patient.patient_mobile','tbl_patient.patient_age','tbl_patient.patient_sex','tbl_patient.address as patient_address','tbl_patient.c_o_name','tbl_ot_type.ot_type')
    ->get();
      return view('ot.OTBillClearence')->with('running_ot',$running_ot);
    }
    // get ot ledger
    public function otClearenceLedgerPayment(Request $request)
    {
      $ot_booking_id = trim($request->ot_booking_id);
      // check ot staff bill
      $ot_staff_bill_count = DB::table('tbl_ot_serjeun_staff_bill')->where('ot_booked_id',$ot_booking_id)->count();
      if($ot_staff_bill_count == '0'){
        Session::put('failed','Sorry ! Please First Given Suergery Staff Posting ');
        return Redirect::to('OTSurgeryClinicalPosting');
        exit();

      }
      // check ot service completed
      $ot_service_bill_count = DB::table('tbl_ot_service_bill')->where('ot_booking_id',$ot_booking_id)->count();

       if($ot_service_bill_count == '0'){
        Session::put('failed','Sorry ! Please Given OT Service Information ');
        return Redirect::to('OTserviceBill');
        exit();

      }
      // ot booking info
      $ot_booking_info_query = DB::table('tbl_ot_booking')->where('branch_id',$this->branch_id)->where('id',$ot_booking_id)->where('status',0)->limit(1)->first();

      $ot_book_invoice = $ot_booking_info_query->invoice ;
      $patient_id = $ot_booking_info_query->patient_id ;

      // duplicate  OT Invoice check
         // second check
    $second_ot_duplicate_chcek = DB::table('tbl_ot_clear_bill')->where('branch_id',$this->branch_id)->where('ot_booking_id',$ot_booking_id)->where('ot_booking_invoice_no',$ot_book_invoice)->where('patient_id',$patient_id)->count();
    if($second_ot_duplicate_chcek > 0){
         Session::put('failed','Sorry ! OT Clearence Bill Already Created Of This Patient ');
        return Redirect::to('collectBill');
        exit();
    }

      // get ot ledger
      $ot_ledger = DB::table('tbl_ot_ledger')->where('branch_id',$this->branch_id)->where('ot_booking_id',$ot_booking_id)->where('patient_id',$patient_id)->get();
     $patient_info         = DB::table('tbl_patient')->where('branch_id',$this->branch_id)->where('id',$patient_id)->first();
     $pc                    = DB::table('tbl_pc')->where('branch_id',$this->branch_id)->where('status',1)->get();
      return view('ot.otClearenceLedgerPayment')->with('ot_ledger',$ot_ledger)->with('ot_booking_id',$ot_booking_id)->with('patient_id',$patient_id)->with('ot_book_invoice',$ot_book_invoice)->with('patient_info',$patient_info)->with('pc',$pc)->with('ot_booking_info_query',$ot_booking_info_query);
    
    }
   #----------------------------------------- END OT BILL CLEARENCE ----------------------------------------------#

   #----------------------------------------- OT CLEARENCE PAYMENT BILL -------------------------------------------#
   public function createOTClearenceBill(Request $request)
   {
    $branch                   = $this->branch_id ;
    $ot_booking_id            = trim($request->ot_booking_id) ;
    $ot_booking_invoice       = trim($request->ot_booking_invoice) ;
    $patient_id_is            = trim($request->patient_id_is) ;
    $bill_date                = trim($request->bill_date) ;
    $billDate                 = date('Y-m-d',strtotime($bill_date)) ;
    $total_payable            = trim($request->total_payable) ;
    $total_discount           = trim($request->total_discount) ;
    $pc_id                    = trim($request->pc_id) ;
    $pc_amount                = trim($request->pc_amount) ;
    $now_payable_from_patient = $total_payable - $total_discount ; 

      $ot_booking_info_query = DB::table('tbl_ot_booking')->where('branch_id',$this->branch_id)->where('id',$ot_booking_id)->where('patient_id',$patient_id_is)->where('status',0)->limit(1)->first();

      $ot_book_invoice = $ot_booking_info_query->invoice ;
      $ot_booked_date  = $ot_booking_info_query->booking_date ;

    #----------------------------- DATE VALIDATION ---------------------------#

     // bill date small than end date
    if($billDate < $ot_booked_date){
        echo "d1";
        exit();

    }
     if($billDate > $this->rcdate){
        echo "d2";
        exit();

    }
    #---------------------------- END DATE VALIDATION -----------------------------#
    #--------------------------- ipd info validaton -------------------------#
    if($ot_booking_id == ''){
      echo "i1";
      exit();
    }
      if($ot_booking_invoice == ''){
      echo "i2";
      exit();
    }
      if($patient_id_is == ''){
      echo "i3";
      exit();
    }
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
   #--------------------------- end ipd info validation----------------------#

    #----------------------------- alrady bill clearence----------------------------#
    // first check
    $fisrt_ot_duplicate_chcek = DB::table('tbl_ot_booking')->where('branch_id',$this->branch_id)->where('id',$ot_booking_id)->where('invoice',$ot_booking_invoice)->where('patient_id',$patient_id_is)->where('status',1)->count();
    if($fisrt_ot_duplicate_chcek > 0){
      echo "dup1";
      exit();
    }
    // second check
    $second_ot_duplicate_chcek = DB::table('tbl_ot_clear_bill')->where('branch_id',$this->branch_id)->where('ot_booking_id',$ot_booking_id)->where('ot_booking_invoice_no',$ot_booking_invoice)->where('patient_id',$patient_id_is)->count();
    if($second_ot_duplicate_chcek > 0){
      echo "dup2";
      exit();
    }
    #----------------------------- end already bill clearnce-------------------------#
    if($total_discount > 0){
      $purpose = "OT Clearence Bill Create With Discount";
     }else{
      $purpose = "OT Clearence Bill Create";
     } 
        // status = 17
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
        $data_cashbook['status']              = 17 ;
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
     $branch_invoice_count = DB::table('tbl_ot_clear_bill')->where('branch_id',$branch)->orderBy('invoice','desc')->count();
     if($branch_invoice_count == '0'){
      $branch_invoice_number = 1 ;
     }else{
      $branch_invoice_query = DB::table('tbl_ot_clear_bill')->where('branch_id',$branch)->orderBy('invoice','desc')->limit(1)->first();
      $branch_invoice_number = $branch_invoice_query->invoice + 1 ;
     }
    #-------------------- get year invoice------------------------------------#
    $branch_year_invoice_count = DB::table('tbl_ot_clear_bill')->where('year',$this->current_year)->where('branch_id',$branch)->orderBy('year_invoice','desc')->count();
     if($branch_year_invoice_count == '0'){
      $branch_invoice_year_number = 1 ;
     }else{
      $branch_year_invoice_query = DB::table('tbl_ot_clear_bill')->where('year',$this->current_year)->where('branch_id',$branch)->orderBy('year_invoice','desc')->limit(1)->first();
      $branch_invoice_year_number = $branch_year_invoice_query->year_invoice + 1 ;
     }
     #--------------------- daily invoice -------------------------------------#
      $branch_daily_invoice_count = DB::table('tbl_ot_clear_bill')->where('bill_date',$this->rcdate)->where('branch_id',$branch)->orderBy('daily_invoice','desc')->count();
     if($branch_daily_invoice_count == '0'){
      $branch_daily_invoice_number = 1 ;
     }else{
      $branch_daily_invoice_query = DB::table('tbl_ot_clear_bill')->where('bill_date',$this->rcdate)->where('branch_id',$branch)->orderBy('daily_invoice','desc')->limit(1)->first();
      $branch_daily_invoice_number = $branch_daily_invoice_query->daily_invoice + 1 ;
     }
    #------------------ END GET INVIOCE NUMBER ----------------------------------#
    #---------------------------- INSERT INTO OT CLEAR BILL TABLE---------------#
    $data_clear_bill_insert                      = array();
    $data_clear_bill_insert['cashbook_id']       = $last_cashbook_id ;
    $data_clear_bill_insert['ot_booking_id']            = $ot_booking_id  ;
    $data_clear_bill_insert['ot_booking_invoice_no']    = $ot_booking_invoice ;
    $data_clear_bill_insert['year']              = $this->current_year;
    $data_clear_bill_insert['invoice']           = $branch_invoice_number;
    $data_clear_bill_insert['year_invoice']      = $branch_invoice_year_number;
    $data_clear_bill_insert['daily_invoice']     = $branch_daily_invoice_number ;
    $data_clear_bill_insert['branch_id']         = $branch ;
    $data_clear_bill_insert['patient_id']        = $patient_id_is;
    $data_clear_bill_insert['pc_id']             = $pc_id;
    $data_clear_bill_insert['purpose']           = 'OT Clearence Bill Create'; 
    $data_clear_bill_insert['bill_time']         = $this->current_time ;
    $data_clear_bill_insert['bill_date']         = $billDate ;
    $data_clear_bill_insert['added_id']          = $this->loged_id;
    $data_clear_bill_insert['created_at']        = $this->rcdate ;
    DB::table('tbl_ot_clear_bill')->insert($data_clear_bill_insert);
   #--------------------------- END INSERT INTO PATHYOLOGY BILL TABLE-------------#
    #------------------------ get last id of ot clear bill-----------------------#
    $last_ot_id_query = DB::table('tbl_ot_clear_bill')->orderBy('id','desc')->limit(1)->first();
    $last_ot_id       = $last_ot_id_query->id ; 
   #------------------------ end get last id of ot clear bill --------------------#
    // ipd collection 
   $data_ot_ledger_clear                        = array();
   $data_ot_ledger_clear['branch_id']          = $branch ;
   $data_ot_ledger_clear['ot_booking_id']      = $ot_booking_id ;
   $data_ot_ledger_clear['service_type']       = 4;
   $data_ot_ledger_clear['service_id']         = $last_ot_id ;
   $data_ot_ledger_clear['service_invoice']    = $branch_invoice_number ; 
   $data_ot_ledger_clear['patient_id']         = $patient_id_is;
   $data_ot_ledger_clear['payable_amount']     = 0;
   $data_ot_ledger_clear['payment_amount']     = $now_payable_from_patient;
   $data_ot_ledger_clear['discount']           = $total_discount;
   $data_ot_ledger_clear['purpose']            = $purpose ;
   $data_ot_ledger_clear['added_id']           = $this->loged_id;
   $data_ot_ledger_clear['created_time']       = $this->current_time;
   $data_ot_ledger_clear['service_created_at'] = $billDate;
   $data_ot_ledger_clear['created_at']         = $this->rcdate;
   DB::table('tbl_ot_ledger')->insert($data_ot_ledger_clear);
   #--------------------------- END INSERT IPD LEDGER BILL-------------------------#
   #--------------------------- UPDAT OT BOOKING STATUS----------------------------#
   $data_ot_booking_update              = array();
   $data_ot_booking_update['status']    = 1 ;
   $data_ot_booking_update['end_date']  = $billDate ;
   DB::table('tbl_ot_booking')->where('branch_id',$branch)->where('id',$ot_booking_id)->where('invoice',$ot_book_invoice)->where('patient_id',$patient_id_is)->where('status',0)->update($data_ot_booking_update );
   #--------------------------- END UPDATE OT BOOKING STATUS------------------------#
   #--------------------------- PC INFO---------------------------------------------#
       if($pc_id != '0'){
      // insert the data into pc ledger
      $data_pc_ledger_insert                      = array();
      $data_pc_ledger_insert['cashbook_id']       = $last_cashbook_id ;
      $data_pc_ledger_insert['branch_id']         = $branch ;
      $data_pc_ledger_insert['invoice']           = $branch_invoice_number ;
      $data_pc_ledger_insert['year_invoice']      = $branch_invoice_year_number;
      $data_pc_ledger_insert['daily_invoice_number'] = $branch_daily_invoice_number;
      $data_pc_ledger_insert['invoice_type']      = 3;
      $data_pc_ledger_insert['pc_id']             = $pc_id ;
      $data_pc_ledger_insert['payable_amount']    = $pc_amount ;
      $data_pc_ledger_insert['status']            = 3 ; 
      $data_pc_ledger_insert['purpose']           = 'OT Clearence Bill Create';
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
    #----------------------END PC INFO -------------------------------------------#
    #-------------------------------- incress pettycash amount ------------------------------#
      $pettycash_amount = DB::table('pettycash')->where('branch_id',$branch)->where('type',3)->limit(1)->first();
      $current_pettycash_amt = $pettycash_amount->pettycash_amount ;
      $now_pettycash_amt     = $current_pettycash_amt + $now_payable_from_patient ;
      // update pettycash amount
      $data_update_pettycash                      = array();
      $data_update_pettycash['pettycash_amount']  = $now_pettycash_amt; 
      DB::table('pettycash')->where('branch_id',$branch)->where('type',3)->update($data_update_pettycash);
  #-------------------------------- end incress pettycash amount---------------------------#
     echo $last_ot_id.'/'.$branch_invoice_number.'/'.$last_cashbook_id.'/'.$ot_booking_id ;

   }
  #----------------------------------------- END OT CLEARENCE PAYAMENT BILL ---------------------------------------#

  #----------------------------------------- OT AMOUNT DISTRIBUTION -----------------------------------------------#
  // ot amount distribution by manager
   public function OTamountDistribution()
   {
    $result = DB::table('tbl_ot_clear_bill')->where('branch_id',$this->branch_id)->where('ot_amount_distribution',0)->get();
    return view('ot.OTamountDistribution')->with('result',$result);
   }
  // OT staff amount distribution
   public function otStaffAmountDistribution($booking_id , $patient_id)
   {
    // get staff type
    $result = DB::table('tbl_ot_staff_type_amount')->where('ot_booked_id',$booking_id)->where('patient_id',$patient_id)->first();
    // main surjon
    $main_surjon_query = DB::table('tbl_ot_staff_type_amount')->where('ot_booked_id',$booking_id)->where('patient_id',$patient_id)->where('staff_type',2)->first();
    // main surjon
     $main_surjon =  DB::table('admin')
    ->join('tbl_doctor', 'admin.id', '=', 'tbl_doctor.admin_id')
    ->join('tbl_ot_staff_info', 'admin.id', '=', 'tbl_ot_staff_info.staff_id')
    ->where('tbl_ot_staff_info.branch_id',$this->branch_id)
    ->where('tbl_ot_staff_info.ot_booked_id',$booking_id)
    ->where('tbl_ot_staff_info.patient_id',$patient_id)
    ->where('tbl_ot_staff_info.staff_type',2)
    ->select('admin.*','tbl_doctor.speialist')
    ->get();

    // anesthisia
    $anes_surjon_query = DB::table('tbl_ot_staff_type_amount')->where('ot_booked_id',$booking_id)->where('patient_id',$patient_id)->where('staff_type',4)->first();
    // anes surjon
    $anes_surjon =  DB::table('admin')
    ->join('tbl_doctor', 'admin.id', '=', 'tbl_doctor.admin_id')
    ->join('tbl_ot_staff_info', 'admin.id', '=', 'tbl_ot_staff_info.staff_id')
    ->where('tbl_ot_staff_info.branch_id',$this->branch_id)
    ->where('tbl_ot_staff_info.ot_booked_id',$booking_id)
    ->where('tbl_ot_staff_info.patient_id',$patient_id)
    ->where('tbl_ot_staff_info.staff_type',4)
    ->select('admin.*','tbl_doctor.speialist')
    ->get();

     // anesthisia
    $assistant_surjon_query = DB::table('tbl_ot_staff_type_amount')->where('ot_booked_id',$booking_id)->where('patient_id',$patient_id)->where('staff_type',3)->first();
    // anes surjon
    $assistant_surjon =  DB::table('admin')
    ->leftJoin('tbl_doctor', 'admin.id', '=', 'tbl_doctor.admin_id')
    ->leftJoin('tbl_ot_staff_info', 'admin.id', '=', 'tbl_ot_staff_info.staff_id')
    ->where('tbl_ot_staff_info.branch_id',$this->branch_id)
    ->where('tbl_ot_staff_info.ot_booked_id',$booking_id)
    ->where('tbl_ot_staff_info.patient_id',$patient_id)
    ->where('tbl_ot_staff_info.staff_type',3)
    ->select('admin.*','tbl_doctor.speialist')
    ->get();
    // ot assistant
    $ot_assistant_query = DB::table('tbl_ot_staff_type_amount')->where('ot_booked_id',$booking_id)->where('patient_id',$patient_id)->where('staff_type',5)->first(); 
    // ot assistan info
    $ot_assistant = DB::table('tbl_ot_staff')
    ->leftJoin('tbl_ot_staff_info', 'tbl_ot_staff.id', '=', 'tbl_ot_staff_info.staff_id')
    ->where('tbl_ot_staff_info.branch_id',$this->branch_id)
    ->where('tbl_ot_staff_info.ot_booked_id',$booking_id)
    ->where('tbl_ot_staff_info.patient_id',$patient_id)
    ->where('tbl_ot_staff_info.staff_type',5)
    ->select('tbl_ot_staff.*')
    ->get();

    return view('ot.otStaffAmountDistribution')->with('result',$result)->with('main_surjon_query',$main_surjon_query)->with('main_surjon',$main_surjon)->with('anes_surjon_query',$anes_surjon_query)->with('anes_surjon',$anes_surjon)->with('assistant_surjon_query',$assistant_surjon_query)->with('assistant_surjon',$assistant_surjon)->with('ot_assistant_query',$ot_assistant_query)->with('ot_assistant',$ot_assistant)->with('booking_id',$booking_id)->with('patient_id',$patient_id);
    
   }

   // ot staff bill distribution
   public function patientOTSuergeryStaffBillDistribution(Request $request)
   {
    $this->validate($request, [
    'booking_id'            => 'required',
    'patient_id'            => 'required',
    'main_surjon_fee'       => 'required',
    'anes_fee'              => 'required',
    'assistant_surjon_fee'  => 'required',
    'ot_assistant_fee'      => 'required',
    'tr_date'               => 'required',
    ]);
     $booking_id            = trim($request->booking_id);
     $patient_id            = trim($request->patient_id);
     $main_surjon_fee       = trim($request->main_surjon_fee);
     $anes_fee              = trim($request->anes_fee);
     $assistant_surjon_fee  = trim($request->assistant_surjon_fee);
     $ot_assistant_fee      = trim($request->ot_assistant_fee);

     $main_surjon           = array_map('trim',$request->main_surjon);
     $main_surjon_payable   = array_map('trim',$request->main_surjon_payable);
     $anes_surjon           = array_map('trim',$request->anes_surjon);
     $anes_surjon_payable   = array_map('trim',$request->anes_surjon_payable);
     $assistant_surjon      = $request->assistant_surjon;
     $assistant_surjon_payable = $request->assistant_surjon_payable;
     $ot_assistant          = $request->ot_assistant;
     $ot_assistant_payable  = $request->ot_assistant_payable;

     $remarks               = trim($request->remarks);
     $tr_date               = trim($request->tr_date);
     $trDate                = date('Y-m-d',strtotime($tr_date));
     #----------------------- DATE VALIDATION -----------------------------------#
    if($trDate > $this->rcdate){
      Session::put('failed','Sorry ! Wrong Distribution Date. Try Again');
      return Redirect::to('otStaffAmountDistribution/'.$booking_id.'/'.$patient_id);  
      exit();
    }
     #---------------------- END DATE VALIDATION ---------------------------------#

     #--------------------- amount big than total amount--------------------------#
     // main surjon
    $total_main_surjon_payable = 0 ;
    foreach ($main_surjon_payable as $main_surjon_payablevalue) {
     $total_main_surjon_payable = $total_main_surjon_payable + $main_surjon_payablevalue ;
    }
    if($total_main_surjon_payable > $main_surjon_fee){
      Session::put('failed','Sorry ! Distribution Amount Big Than Total Main Surjeon Amount Did Not Match');
      return Redirect::to('otStaffAmountDistribution/'.$booking_id.'/'.$patient_id);  
      exit();
    }

     $total_anes_surjon_payable = 0 ;
    foreach ($anes_surjon_payable as $anes_surjon_payablevalue) {
     $total_anes_surjon_payable = $anes_surjon_payablevalue + $total_anes_surjon_payable ;
    }
    if($total_anes_surjon_payable > $anes_fee){
      Session::put('failed','Sorry ! Distribution Amount Big Than Total Anesthesia Amount Did Not Match');
      return Redirect::to('otStaffAmountDistribution/'.$booking_id.'/'.$patient_id);  
      exit();
    }
    $total_assistant_surjon_payable = 0 ;
    foreach ($assistant_surjon_payable as $assistant_surjon_payablevalue) {
     $total_assistant_surjon_payable = $assistant_surjon_payablevalue + $total_assistant_surjon_payable ;
    }
    if($total_assistant_surjon_payable >  $assistant_surjon_fee){
      Session::put('failed','Sorry ! Distribution Amount Big Than Total Assistant Surjeon Amount Did Not Match');
      return Redirect::to('otStaffAmountDistribution/'.$booking_id.'/'.$patient_id);  
      exit();
    }
    $total_ot_assistant_surjon_payable = 0 ;
    foreach ($ot_assistant_payable as $ot_assistant_payablevalue) {
     $total_ot_assistant_surjon_payable = $ot_assistant_payablevalue + $total_ot_assistant_surjon_payable ;
    }
    if($total_ot_assistant_surjon_payable >  $ot_assistant_fee){
      Session::put('failed','Sorry ! Distribution Amount Big Than Total OT Assistant Amount Did Not Match');
      return Redirect::to('otStaffAmountDistribution/'.$booking_id.'/'.$patient_id);  
      exit();
    }
    #---------------------- end amount big than total amount ---------------------#
    #---------------------- already added ----------------------------------------#
   $count = DB::table('tbl_ot_distribution_amount')->where('branch_id',$this->branch_id)->where('ot_booked_id',$booking_id)->where('patient_id',$patient_id)->count();
   if($count > 0){
      Session::put('failed','Sorry ! Already OT Amount Distribution Completed Sucessfully');
      return Redirect::to('otStaffAmountDistribution/'.$booking_id.'/'.$patient_id);  
      exit();
   }
    #---------------------- end alread added -------------------------------------#
      // main surjon info
     foreach ($main_surjon as $key => $main_surjon_value) {
       $main_surjon_payable_indi      = $main_surjon_payable[$key];
       // mains surjon data insert
       $data_main_surjon                    = array();
       $data_main_surjon['ot_booked_id']    = $booking_id;
       $data_main_surjon['branch_id']       = $this->branch_id;
       $data_main_surjon['patient_id']      = $patient_id;
       $data_main_surjon['staff_type']      = 2 ;
       $data_main_surjon['staff_id']        = $main_surjon_value;
       $data_main_surjon['amount']          = $main_surjon_payable_indi;
       $data_main_surjon['due_status']      = 0 ;
       $data_main_surjon['purpose']         = 'OT Amount Distribution' ;
       $data_main_surjon['remarks']         = $remarks;
       $data_main_surjon['created_time']    = $this->current_time ;
       $data_main_surjon['di_date']         = $trDate ;
       $data_main_surjon['added_id']        = $this->loged_id;
       $data_main_surjon['created_at']      = $this->rcdate;
       DB::table('tbl_ot_distribution_amount')->insert($data_main_surjon);
       // ot staff payment ledger
   $data_ot_ledger_main_surjon                      = array();
   $data_ot_ledger_main_surjon['branch_id']          = $this->branch_id;
   $data_ot_ledger_main_surjon['ot_booking_id']      = $booking_id ;
   $data_ot_ledger_main_surjon['service_type']       = 1;
   $data_ot_ledger_main_surjon['staff_type']         = 2 ;
   $data_ot_ledger_main_surjon['staff_id']           = $main_surjon_value ;
   $data_ot_ledger_main_surjon['patient_id']         = $patient_id;
   $data_ot_ledger_main_surjon['payable_amount']     = $main_surjon_payable_indi;
   $data_ot_ledger_main_surjon['purpose']            = 'OT Amount Distribution' ;
   $data_ot_ledger_main_surjon['added_id']           = $this->loged_id;
   $data_ot_ledger_main_surjon['created_time']       = $this->current_time;
   $data_ot_ledger_main_surjon['service_created_at'] = $trDate;
   $data_ot_ledger_main_surjon['created_at']         = $this->rcdate;
   DB::table('tbl_ot_staff_ledger')->insert($data_ot_ledger_main_surjon);

     }

     // anesthis fee
      foreach ($anes_surjon as $key => $anes_surjon_value) {
       $anes_surjon_payable_indi      = $anes_surjon_payable[$key];
       // mains surjon data insert
       $data_anes_surjon                    = array();
       $data_anes_surjon['ot_booked_id']    = $booking_id;
       $data_anes_surjon['branch_id']       = $this->branch_id;
       $data_anes_surjon['patient_id']      = $patient_id;
       $data_anes_surjon['staff_type']      = 4 ;
       $data_anes_surjon['staff_id']        = $anes_surjon_value;
       $data_anes_surjon['amount']          = $anes_surjon_payable_indi;
       $data_anes_surjon['due_status']      = 0 ;
       $data_anes_surjon['purpose']         = 'OT Amount Distribution' ;
       $data_anes_surjon['remarks']         = $remarks;
       $data_anes_surjon['created_time']    = $this->current_time ;
       $data_anes_surjon['di_date']         = $trDate ;
       $data_anes_surjon['added_id']        = $this->loged_id;
       $data_anes_surjon['created_at']      = $this->rcdate;
       DB::table('tbl_ot_distribution_amount')->insert($data_anes_surjon);
       // ot staff payment ledger
   $data_ot_ledger_anes_surjon                      = array();
   $data_ot_ledger_anes_surjon['branch_id']          = $this->branch_id;
   $data_ot_ledger_anes_surjon['ot_booking_id']      = $booking_id ;
   $data_ot_ledger_anes_surjon['service_type']       = 1;
   $data_ot_ledger_anes_surjon['staff_type']         = 4 ;
   $data_ot_ledger_anes_surjon['staff_id']           = $anes_surjon_value ;
   $data_ot_ledger_anes_surjon['patient_id']         = $patient_id;
   $data_ot_ledger_anes_surjon['payable_amount']     = $anes_surjon_payable_indi;
   $data_ot_ledger_anes_surjon['purpose']            = 'OT Amount Distribution' ;
   $data_ot_ledger_anes_surjon['added_id']           = $this->loged_id;
   $data_ot_ledger_anes_surjon['created_time']       = $this->current_time;
   $data_ot_ledger_anes_surjon['service_created_at'] = $trDate;
   $data_ot_ledger_anes_surjon['created_at']         = $this->rcdate;
   DB::table('tbl_ot_staff_ledger')->insert($data_ot_ledger_anes_surjon);

     }
        // assistant surjon fee
      foreach ($assistant_surjon as $key => $assistant_surjon_value) {
       $assistant_surjon_payable_indi      = $assistant_surjon_payable[$key];
       // mains surjon data insert
       $data_assistant_surjon                    = array();
       $data_assistant_surjon['ot_booked_id']    = $booking_id;
       $data_assistant_surjon['branch_id']       = $this->branch_id;
       $data_assistant_surjon['patient_id']      = $patient_id;
       $data_assistant_surjon['staff_type']      = 3 ;
       $data_assistant_surjon['staff_id']        = $assistant_surjon_value;
       $data_assistant_surjon['amount']          = $assistant_surjon_payable_indi;
       $data_assistant_surjon['due_status']      = 0 ;
       $data_assistant_surjon['purpose']         = 'OT Amount Distribution' ;
       $data_assistant_surjon['remarks']         = $remarks;
       $data_assistant_surjon['created_time']    = $this->current_time ;
       $data_assistant_surjon['di_date']         = $trDate ;
       $data_assistant_surjon['added_id']        = $this->loged_id;
       $data_assistant_surjon['created_at']      = $this->rcdate;
       DB::table('tbl_ot_distribution_amount')->insert($data_assistant_surjon);
       // ot staff payment ledger
    $data_ot_ledger_assistant_surjon                      = array();
   $data_ot_ledger_assistant_surjon['branch_id']          = $this->branch_id;
   $data_ot_ledger_assistant_surjon['ot_booking_id']      = $booking_id ;
   $data_ot_ledger_assistant_surjon['service_type']       = 1;
   $data_ot_ledger_assistant_surjon['staff_type']         = 3 ;
   $data_ot_ledger_assistant_surjon['staff_id']           = $assistant_surjon_value ;
   $data_ot_ledger_assistant_surjon['patient_id']         = $patient_id;
   $data_ot_ledger_assistant_surjon['payable_amount']     = $assistant_surjon_payable_indi;
   $data_ot_ledger_assistant_surjon['purpose']            = 'OT Amount Distribution' ;
   $data_ot_ledger_assistant_surjon['added_id']           = $this->loged_id;
   $data_ot_ledger_assistant_surjon['created_time']       = $this->current_time;
   $data_ot_ledger_assistant_surjon['service_created_at'] = $trDate;
   $data_ot_ledger_assistant_surjon['created_at']         = $this->rcdate;
   DB::table('tbl_ot_staff_ledger')->insert($data_ot_ledger_assistant_surjon);
     }
     // ot assistant
        foreach ($ot_assistant as $key => $ot_assistant_value) {
       $ot_assistant_payable_indi  = $ot_assistant_payable[$key];
       // mains surjon data insert
       $data_ot_assistant_surjon                    = array();
       $data_ot_assistant_surjon['ot_booked_id']    = $booking_id;
       $data_ot_assistant_surjon['branch_id']       = $this->branch_id;
       $data_ot_assistant_surjon['patient_id']      = $patient_id;
       $data_ot_assistant_surjon['staff_type']      = 5 ;
       $data_ot_assistant_surjon['staff_id']        = $ot_assistant_value;
       $data_ot_assistant_surjon['amount']          = $ot_assistant_payable_indi;
       $data_ot_assistant_surjon['due_status']      = 0 ;
       $data_ot_assistant_surjon['purpose']         = 'OT Amount Distribution' ;
       $data_ot_assistant_surjon['remarks']         = $remarks;
       $data_ot_assistant_surjon['created_time']    = $this->current_time ;
       $data_ot_assistant_surjon['di_date']         = $trDate ;
       $data_ot_assistant_surjon['added_id']        = $this->loged_id;
       $data_ot_assistant_surjon['created_at']      = $this->rcdate;
       DB::table('tbl_ot_distribution_amount')->insert($data_ot_assistant_surjon);
       // ot staff payment ledger
    $data_ot_ledger_ot_assistant_surjon                      = array();
   $data_ot_ledger_ot_assistant_surjon['branch_id']          = $this->branch_id;
   $data_ot_ledger_ot_assistant_surjon['ot_booking_id']      = $booking_id ;
   $data_ot_ledger_ot_assistant_surjon['service_type']       = 1;
   $data_ot_ledger_ot_assistant_surjon['staff_type']         = 5 ;
   $data_ot_ledger_ot_assistant_surjon['staff_id']           = $ot_assistant_value ;
   $data_ot_ledger_ot_assistant_surjon['patient_id']         = $patient_id;
   $data_ot_ledger_ot_assistant_surjon['payable_amount']     = $ot_assistant_payable_indi;
   $data_ot_ledger_ot_assistant_surjon['purpose']            = 'OT Amount Distribution' ;
   $data_ot_ledger_ot_assistant_surjon['added_id']           = $this->loged_id;
   $data_ot_ledger_ot_assistant_surjon['created_time']       = $this->current_time;
   $data_ot_ledger_ot_assistant_surjon['service_created_at'] = $trDate;
   $data_ot_ledger_ot_assistant_surjon['created_at']         = $this->rcdate;
   DB::table('tbl_ot_staff_ledger')->insert($data_ot_ledger_ot_assistant_surjon);
     }
     // update query of ot booking
     $data_update_booking                           = array();
     $data_update_booking['ot_amount_distribution'] = 1 ;
     DB::table('tbl_ot_clear_bill')->where('branch_id',$this->branch_id)->where('ot_booking_id',$booking_id)->where('patient_id',$patient_id)->update($data_update_booking);
    Session::put('succes','Thanks , OT Amount Distribution Sucessfully');
    return Redirect::to('OTamountDistribution');

   }
  #----------------------------------------- END OT AMOUNT DISTRIBUTION -------------------------------------------#

  #----------------------------------------- OT PAYMENT -----------------------------------------------------------#
   public function otPayment()
   {
    return view('ot.otPayment');
   }
   // get staff by staff type
   public function getStaffForOTPayment(Request $request)
   {
    $ot_staff_type = trim($request->ot_staff_type);
    if($ot_staff_type == '2'){
    $result =  DB::table('admin')
    ->join('tbl_doctor', 'admin.id', '=', 'tbl_doctor.admin_id')
    ->where('admin.branch_id',$this->branch_id)
    ->where('admin.status',1)
    ->where('tbl_doctor.doctor_type',2)
    ->select('admin.*','tbl_doctor.speialist')
    ->get();
  }elseif($ot_staff_type == '4'){
      // get anesthia
     $result =  DB::table('admin')
    ->join('tbl_doctor', 'admin.id', '=', 'tbl_doctor.admin_id')
    ->where('admin.branch_id',$this->branch_id)
    ->where('admin.status',1)
    ->where('tbl_doctor.doctor_type',4)
    ->select('admin.*','tbl_doctor.speialist')
    ->get();
  }elseif($ot_staff_type == '3'){
    $result =  DB::table('admin')
    ->join('tbl_doctor', 'admin.id', '=', 'tbl_doctor.admin_id')
    ->where('admin.branch_id',$this->branch_id)
    ->where('admin.status',1)
    ->where('tbl_doctor.doctor_type',3)
    ->select('admin.*','tbl_doctor.speialist')
    ->get();
  }elseif($ot_staff_type == '5'){
    // ot assistant
    $result = DB::table('tbl_ot_staff')->where('branch_id',$this->branch_id)->get();
    
   }
   echo "<option value=''>Select Staff</option>";
   foreach ($result as $value) {
    echo "<option value=".$value->id.">".$value->name.'-'.$value->mobile."</option>";
  }
 }
   // get ot payment ledger fot ot payment
   public function getOTpaymentLedger(Request $request)
   {
        $ot_staff_type = trim($request->ot_staff_type) ;
        $staff         = trim($request->staff) ; 
      if($ot_staff_type == '5'){
      $count = DB::table('tbl_ot_staff')
    ->join('tbl_ot_distribution_amount', 'tbl_ot_staff.id', '=', 'tbl_ot_distribution_amount.staff_id')
    ->where('tbl_ot_distribution_amount.branch_id',$this->branch_id)
    ->where('tbl_ot_distribution_amount.staff_type',$ot_staff_type)
    ->where('tbl_ot_distribution_amount.staff_id',$staff)
    ->where('tbl_ot_distribution_amount.due_status',0)
    ->select('tbl_ot_distribution_amount.*','tbl_ot_staff.name','tbl_ot_staff.mobile')
    ->count();
    if($count == '0'){
      echo 'f1';
      exit();
    }
    }else{
    $count =  DB::table('admin')
    ->join('tbl_doctor', 'admin.id', '=', 'tbl_doctor.admin_id')
    ->join('tbl_ot_distribution_amount', 'admin.id', '=', 'tbl_ot_distribution_amount.staff_id')
    ->where('tbl_ot_distribution_amount.branch_id',$this->branch_id)
    ->where('tbl_ot_distribution_amount.staff_type',$ot_staff_type)
    ->where('tbl_ot_distribution_amount.staff_id',$staff)
    ->where('tbl_ot_distribution_amount.due_status',0)
    ->select('tbl_ot_distribution_amount.*','admin.name','admin.mobile')
    ->count();
        if($count == '0'){
      echo 'f1';
      exit();
    }

        }

      if($ot_staff_type == '2'){
      $result =  DB::table('admin')
    ->join('tbl_doctor', 'admin.id', '=', 'tbl_doctor.admin_id')
    ->join('tbl_ot_distribution_amount', 'admin.id', '=', 'tbl_ot_distribution_amount.staff_id')
    ->where('tbl_ot_distribution_amount.branch_id',$this->branch_id)
    ->where('tbl_ot_distribution_amount.staff_type',2)
    ->where('tbl_ot_distribution_amount.staff_id',$staff)
    ->where('tbl_ot_distribution_amount.due_status',0)
    ->select('tbl_ot_distribution_amount.*','admin.name','admin.mobile')
    ->get();
  }elseif($ot_staff_type == '3'){
   $result =  DB::table('admin')
    ->join('tbl_doctor', 'admin.id', '=', 'tbl_doctor.admin_id')
    ->join('tbl_ot_distribution_amount', 'admin.id', '=', 'tbl_ot_distribution_amount.staff_id')
    ->where('tbl_ot_distribution_amount.branch_id',$this->branch_id)
    ->where('tbl_ot_distribution_amount.staff_type',3)
    ->where('tbl_ot_distribution_amount.staff_id',$staff)
    ->where('tbl_ot_distribution_amount.due_status',0)
    ->select('tbl_ot_distribution_amount.*','admin.name','admin.mobile')
    ->get();
  }elseif($ot_staff_type == '4'){
    $result =  DB::table('admin')
    ->join('tbl_doctor', 'admin.id', '=', 'tbl_doctor.admin_id')
    ->join('tbl_ot_distribution_amount', 'admin.id', '=', 'tbl_ot_distribution_amount.staff_id')
    ->where('tbl_ot_distribution_amount.branch_id',$this->branch_id)
    ->where('tbl_ot_distribution_amount.staff_type',4)
    ->where('tbl_ot_distribution_amount.staff_id',$staff)
    ->where('tbl_ot_distribution_amount.due_status',0)
    ->select('tbl_ot_distribution_amount.*','admin.name','admin.mobile')
    ->get();
  }elseif($ot_staff_type =='5'){
    $result = DB::table('tbl_ot_staff')
    ->join('tbl_ot_distribution_amount', 'tbl_ot_staff.id', '=', 'tbl_ot_distribution_amount.staff_id')
    ->where('tbl_ot_distribution_amount.branch_id',$this->branch_id)
    ->where('tbl_ot_distribution_amount.staff_type',5)
    ->where('tbl_ot_distribution_amount.staff_id',$staff)
    ->where('tbl_ot_distribution_amount.due_status',0)
    ->select('tbl_ot_distribution_amount.*','tbl_ot_staff.name','tbl_ot_staff.mobile')
    ->get();
  }
  return view('ot.getOTpaymentLedger')->with('result',$result)->with('ot_staff_type',$ot_staff_type)->with('staff',$staff)->with('branch_id',$this->branch_id);

   }

   // ot staff payment
   public function otStaffPayment($ot_booking_id , $staff_type , $staff , $patient_id)
   {

    if($staff_type == '5'){
       $result = DB::table('tbl_ot_staff')
    ->join('tbl_ot_distribution_amount', 'tbl_ot_staff.id', '=', 'tbl_ot_distribution_amount.staff_id')
    ->where('tbl_ot_distribution_amount.branch_id',$this->branch_id)
    ->where('tbl_ot_distribution_amount.ot_booked_id',$ot_booking_id)
    ->where('tbl_ot_distribution_amount.staff_type',$staff_type)
    ->where('tbl_ot_distribution_amount.staff_id',$staff)
    ->where('tbl_ot_distribution_amount.patient_id',$patient_id)
    ->where('tbl_ot_distribution_amount.due_status',0)
    ->select('tbl_ot_distribution_amount.*','tbl_ot_staff.name','tbl_ot_staff.mobile')
    ->first();
    }else{
    $result =  DB::table('admin')
    ->join('tbl_doctor', 'admin.id', '=', 'tbl_doctor.admin_id')
    ->join('tbl_ot_distribution_amount', 'admin.id', '=', 'tbl_ot_distribution_amount.staff_id')
    ->where('tbl_ot_distribution_amount.branch_id',$this->branch_id)
    ->where('tbl_ot_distribution_amount.ot_booked_id',$ot_booking_id)
    ->where('tbl_ot_distribution_amount.staff_type',$staff_type)
    ->where('tbl_ot_distribution_amount.staff_id',$staff)
    ->where('tbl_ot_distribution_amount.patient_id',$patient_id)
    ->where('tbl_ot_distribution_amount.due_status',0)
    ->select('tbl_ot_distribution_amount.*','admin.name','admin.mobile')
    ->first();
  }
    $bank       = DB::table('bank')->where('status',1)->get();
    // payment amount

    $payment_ot = DB::table('ot_staff_payment')->where('branch_id',$this->branch_id)->where('ot_booking_id',$ot_booking_id)->where('staff_type',$staff_type)->where('staff_id',$staff)->where('patient_id',$patient_id)->get();
    $total_payment = 0 ;
    foreach ($payment_ot as $payment_value) {
      $total_payment = $total_payment + $payment_value->payment_amount ;
    }

    return view('payment.otStaffPayment')->with('result',$result)->with('bank',$bank)->with('ot_booking_id',$ot_booking_id)->with('patient_id',$patient_id)->with('staff_type',$staff_type)->with('staff',$staff)->with('total_payment',$total_payment);
   }
   // ot payment
   public function otPaymentAmt(Request $request)
   {
    $this->validate($request, [
    'payable_amount'            => 'required',
    'previous_payment_amount'   => 'required',
    'due'                       => 'required',
    'payment_amount'            => 'required',
    'confirm_payment_amount'    => 'required',
    'payment_date'              => 'required',
    'ot_booking_id'             => 'required',
    'staff_type'                => 'required',
    'staff_id'                  => 'required',
    'patient_id'                => 'required',
    ]);
    $branch                   = $this->branch_id;
    $payable_amount           = trim($request->payable_amount);
    $previous_payment_amount  = trim($request->previous_payment_amount);
    $due                      = trim($request->due);
    $payment_amount        = trim($request->payment_amount);
    $confirm_payment_amount= trim($request->confirm_payment_amount);
    $remarks               = trim($request->remarks);
    $payment_method        = trim($request->payment_method);
    $bank_account_number   = trim($request->bank_account_number);
    $bank_paper            = trim($request->bank_paper);
    $payment_date          = trim($request->payment_date);
    $paymentDate           = date('Y-m-d',strtotime($payment_date)) ;
    $ot_booking_id         = trim($request->ot_booking_id);
    $staff_type            = trim($request->staff_type);
    $staff_id              = trim($request->staff_id);
    $patient_id            = trim($request->patient_id);
    if($payment_method == '3'){
        if($bank_account_number == ''){
        Session::put('failed','Sorry ! Please Select Bank. Try Again');
        return Redirect::to('otStaffPayment/'.$ot_booking_id.'/'.$staff_type.'/'.$staff_id.'/'.$patient_id); 
        exit(); 
        }
        if($bank_paper == ''){
        Session::put('failed','Sorry ! Please Select Bank T T Number. Try Again');
        return Redirect::to('otStaffPayment/'.$ot_booking_id.'/'.$staff_type.'/'.$staff_id.'/'.$patient_id);  
        exit(); 
        }
    }
    #---------------------- DATE VALIDATION----------------------#
    /// ot distribution date 
    $distributio_query = DB::table('tbl_ot_distribution_amount')->where('branch_id',$this->branch_id)->where('ot_booked_id',$ot_booking_id)->where('staff_type',$staff_type)->where('staff_id',$staff_id)->limit(1)->first();
    $dis_date = $distributio_query->di_date ;

     if($paymentDate > $this->rcdate){
        Session::put('failed','Sorry ! Enter Invalid Date. Please Enter Valid Date And Try Again ');
        return Redirect::to('otStaffPayment/'.$ot_booking_id.'/'.$staff_type.'/'.$staff_id.'/'.$patient_id);  
        exit();
     }
        if($dis_date > $this->rcdate){
        Session::put('failed','Sorry ! Payment Date Small Than OT Amount Distribution Date. Please Correct The Date Then Try Again ');
        return Redirect::to('otStaffPayment/'.$ot_booking_id.'/'.$staff_type.'/'.$staff_id.'/'.$patient_id);  
        exit();
     }
    #-------------------------- payment amount and confirm payment match---------------#
      if($payment_amount != $confirm_payment_amount){
        Session::put('failed','Sorry ! Payment Amount And Confirm Payment Amount Did Not Match. Try Again');
        return Redirect::to('otStaffPayment/'.$ot_booking_id.'/'.$staff_type.'/'.$staff_id.'/'.$patient_id);  
        exit();
      }
     #----------------------- end payment amount and confirm payment match---------------#
    if($due < $payment_amount){
         Session::put('failed','Sorry ! Enter Payment Amount Big Than Due Amount. Please Adjust The Payment Amount');
         return Redirect::to('otStaffPayment/'.$ot_booking_id.'/'.$staff_type.'/'.$staff_id.'/'.$patient_id); 
        exit();
     }
          #----------------- pettycash check------------------------#
     if($payment_method == '1'){
     $pettryCashAmt1 = DB::table('pettycash')->where('branch_id', $branch)->where('type',2)->first();
     $available_pettyCash1 =  $pettryCashAmt1->pettycash_amount;
     if($available_pettyCash1 < $payment_amount)
     {
        Session::put('failed','Sorry ! Insufficient Balance Of Your Petty Cash. Try Again After Available Petty Cash');
         return Redirect::to('otStaffPayment/'.$ot_booking_id.'/'.$staff_type.'/'.$staff_id.'/'.$patient_id);
        exit();
     }
 }
  #----------------- end petty cash-------------------------#
   #----------------------- check bank balance------------------#
 if($payment_method == '3'){
    $BankBalance = DB::table('bank_balance')->where('branch_id',$branch)->where('bank_id',$bank_account_number)->first();
    $avilableBankBalance = $BankBalance->total_balance ;
    $nowBankBalance      = $avilableBankBalance  ;
    if($nowBankBalance < $payment_amount){
        Session::put('failed','Sorry ! Insufficient Balance Of Your Bank Cash. Try Again After Available Bank Cash');
       return Redirect::to('otStaffPayment/'.$ot_booking_id.'/'.$staff_type.'/'.$staff_id.'/'.$patient_id);
        exit();  
    }
}

// write purpose
if($payment_method == '1'){
  $payment_method_type_is = "Cash";
}else{
 // get bank info by bank id
    $bank_info_query = DB::table('bank',$bank_account_number)->where('system_branch_id',$branch)->first();
    $payment_method_type_is =  $bank_info_query->bank_name." A/C No ".$bank_info_query->account_no." And Recipt Paper No ".$bank_paper;
}

$purpose ='OT Staff Payment By '.$payment_method_type_is;
 #------------------------- end check bank balnce------------------#
 #-------------------------- insert into collection table--------------------------#
     // get memo number by 
     // brach id , memo_no 
     $check_memo_number = DB::table('ot_staff_payment')->where('branch_id',$branch)->count();
     if($check_memo_number > 0){
        // get memo number
        $last_memo_number_query = DB::table('ot_staff_payment')->where('branch_id',$branch)->orderBy('memo_no','desc')->take(1)->first();
        $memo_number = $last_memo_number_query->memo_no + 1 ;
     }else{
        $memo_number = 1 ;
     }
     #------------------------ end insert into collection table------------------------#

     #----------------------- year memo------------------------------------------------#
    $check_year_memo_number = DB::table('ot_staff_payment')->where('branch_id',$branch)->where('year',$this->current_year)->count();
     if($check_year_memo_number > 0){
        // get year memo number
        $last_year_memo_number_query = DB::table('ot_staff_payment')->where('branch_id',$branch)->where('year',$this->current_year)->orderBy('year_memo','desc')->take(1)->first();
        $year_memo_number = $last_year_memo_number_query->year_memo + 1 ;
     }else{
        $year_memo_number = 1 ;
     }
     #----------------------- end year memo--------------------------------------------#
        #-------------------------- account memo number-----------------------------------#
     // get account holder  memo number by 
     $check_account_memo_number = DB::table('ot_staff_payment')->where('branch_id',$this->branch_id)->where('staff_type',$staff_type)->where('staff_id',$staff_id)->count();
     if($check_account_memo_number > 0){
        // get memo number
        $last_account_memo_number_query = DB::table('ot_staff_payment')->where('branch_id',$this->branch_id)->where('staff_type',$staff_type)->where('staff_id',$staff_id)->orderBy('id','desc')->take(1)->first();
        $account_memo_number = $last_account_memo_number_query->account_memo_no + 1 ;
     }else{
        $account_memo_number = 1 ;
     }
     #--------------------end account memo number--------------------------------------#
         #------------------- insert into cashbook-----------------------------------------#
   if($payment_method == '1')
   {
     $cost_in_cashbook    = $payment_amount ;
     $cashbook_status_is  = 22 ; 
     $cashbook_tr_status = 1 ;
   }elseif($payment_method == '3'){
    $cashbook_status_is = 23 ;
     $cost_in_cashbook = 0 ;
     $cashbook_tr_status = 2 ;
   }
   if($payment_method == '3'){
    $payment_by_bank = $payment_amount ;
    $bank_status_is_being = 2 ;
   }else{
     $payment_by_bank = 0 ;
     $bank_status_is_being = 0 ;
   }
   // insert cashbbok
    $data8 = array(); 
    $data8['overall_branch_id'] = $branch ;
    $data8['branch_id']         = $branch ;
    $data8['admin_id']          = $this->loged_id ;
    $data8['admin_type']        = 2 ;
    $data8['cost']              = $cost_in_cashbook ;
    $data8['status']            = $cashbook_status_is ;
    $data8['profit_cost']       = $payment_amount  ;
    $data8['tr_status']         = $cashbook_tr_status ;
    $data8['payment_by_bank']   = $payment_by_bank ;
    $data8['bank_status']       = $bank_status_is_being ;
    $data8['purpose']           = $purpose;
    $data8['added_id']          = $this->loged_id;
    $data8['created_time']      = $this->current_time;
    $data8['created_at']        = $paymentDate;
    $data8['on_created_at']     = $this->rcdate;
    DB::table('cashbook')->insert($data8);
    #--------------------- GET LAST CASH BOOK ID  ---------------------------#
     $last_cashbook_id_query = DB::table('cashbook')->orderBy('id','desc')->limit(1)->first();
     $last_cashbook_id       = $last_cashbook_id_query->id ; 
    #-------------------- GET LAST CASH BOOK ID -----------------------------#
    #-------------------------- insert payment table-----------------------------------#
     $data = array();
     $data['cashbook_id']       = $last_cashbook_id ;
     $data['ot_booking_id']     = $ot_booking_id ;
     $data['payment_method']    = $payment_method ;
     $data['bank_id']           = $bank_account_number ;
     $data['branch_id']         = $branch ;
     $data['memo_no']           = $memo_number ;
     $data['year']              = $this->current_year ;
     $data['year_memo']         = $year_memo_number ;
     $data['account_memo_no']   = $account_memo_number ;
     $data['staff_type']        = $staff_type ;
     $data['staff_id']          = $staff_id ;
     $data['patient_id']        = $patient_id ;
     $data['payment_amount']    = $payment_amount ;
     $data['purpose']           = $purpose;
     $data['remarks']           = $remarks ;
     $data['added_id']          = $this->loged_id ;
     $data['payment_time']      = $this->current_time ;
     $data['created_at']        = $paymentDate ;
     $data['on_created_at']     = $this->rcdate ;
     DB::table('ot_staff_payment')->insert($data);

     $last_payment_id_query = DB::table('ot_staff_payment')->where('branch_id',$this->branch_id)->orderBy('id','desc')->limit(1)->first();
     $last_id               = $last_payment_id_query->id ;

    #--------------------- end insert payment table---------------------------#
   $data_ot_staff_ledger                       = array();
   $data_ot_staff_ledger['cashbook_id']        = $last_cashbook_id;
   $data_ot_staff_ledger['branch_id']          = $this->branch_id;
   $data_ot_staff_ledger['ot_booking_id']      = $ot_booking_id ;
   $data_ot_staff_ledger['service_type']       = 2;
   $data_ot_staff_ledger['service_id']         = $last_id ;
   $data_ot_staff_ledger['service_invoice']    = $memo_number;
   $data_ot_staff_ledger['staff_type']         = $staff_type ;
   $data_ot_staff_ledger['staff_id']           = $staff_id ;
   $data_ot_staff_ledger['patient_id']         = $patient_id;
   $data_ot_staff_ledger['payment_amount']     = $payment_amount;
   $data_ot_staff_ledger['purpose']            = $purpose ;
   $data_ot_staff_ledger['added_id']           = $this->loged_id;
   $data_ot_staff_ledger['created_time']       = $this->current_time;
   $data_ot_staff_ledger['service_created_at'] = $paymentDate;
   $data_ot_staff_ledger['created_at']         = $this->rcdate;
   DB::table('tbl_ot_staff_ledger')->insert($data_ot_staff_ledger);
      #-------------------- reduce peety cash ---------------------------------------------#
   if($payment_method == '1'){
     $pettryCashAmt = DB::table('pettycash')->where('branch_id', $branch)->where('type',2)->first();
     $available_pettyCash =  $pettryCashAmt->pettycash_amount;
     $data2 = array();
     $data2['pettycash_amount']     = $available_pettyCash - $payment_amount ;
     $update_peetycash_query        = DB::table('pettycash')->where('branch_id', $branch)->where('type',2)->update($data2) ;
 }
 if($payment_method == '3')
 {
   $BankBalance1 = DB::table('bank_balance')->where('branch_id',$branch)->where('bank_id',$bank_account_number)->first();
    $avilableBankBalance1 = $BankBalance1->total_balance ;
    $nowBankBalance1      = $avilableBankBalance1 - $payment_amount ;
    // update mobile bank balance
    $data_bank_balace_update = array();
    $data_bank_balace_update['total_balance'] = $nowBankBalance1 ; 
    DB::table('bank_balance')->where('branch_id',$branch)->where('bank_id',$bank_account_number)->update($data_bank_balace_update);
 }
 if($payment_method == '3')
 {
    $bank_payment_data_insert     = array();
    $bank_payment_data_insert['overall_branch_id']  = $branch ;
    $bank_payment_data_insert['branch_id']          = $branch ;
    $bank_payment_data_insert['admin_type']         = 2 ;
    $bank_payment_data_insert['bank_id']            = $bank_account_number ;
    $bank_payment_data_insert['cashbook_id']        = $last_cashbook_id ;
    $bank_payment_data_insert['send_amount']        = $payment_amount;
    $bank_payment_data_insert['status']             = 4 ;
    $bank_payment_data_insert['added_id']           = $this->loged_id ;
    $bank_payment_data_insert['remarks']            = $purpose;
    $bank_payment_data_insert['created_time']       = $this->current_time ;
    $bank_payment_data_insert['transaction_date']   = $paymentDate ;
    $bank_payment_data_insert['created_at']         = $this->rcdate ;
    DB::table('bank_transaction')->insert($bank_payment_data_insert);
 }
 // update due status
$now_due_amount = $due - $payment_amount ;
 if($now_due_amount > 0){
  $due_status = 0 ;
 }else{
  $due_status = 1 ;
 }
 $data_due_status_update               = array();
 $data_due_status_update['due_status'] = $due_status ;
 DB::table('tbl_ot_distribution_amount')->where('branch_id',$this->branch_id)->where('ot_booked_id',$ot_booking_id)->where('staff_type',$staff_type)->where('staff_id',$staff_id)->where('patient_id',$patient_id)->update($data_due_status_update);
 Session::put('succes','Thanks , Payment Completed Successfully');
return Redirect::to('otPayment');
}// end funtion bracket

  #----------------------------------------- END OT PAYEMNT -------------------------------------------------------#

   
}