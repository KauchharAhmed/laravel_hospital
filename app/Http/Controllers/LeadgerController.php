<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests;
use DB;
use Session;

class LeadgerController extends Controller
{
    private $rcdate ;
    private $current_time ;
    private $loged_id ;
    private $branch_id ;
    private $current_year ;
    public function __construct() {
    date_default_timezone_set('Asia/Dhaka');
    $this->rcdate       = date('Y-m-d');
    $this->current_time = date('H:i:s');
    $this->current_year = date('Y');
    $this->loged_id     = Session::get('admin_id');
    $this->branch_id    = Session::get('branch_id');
   }
   // supplier ledger
    public function supplierLedger()
    {
     $result = DB::table('supplier')->where('branch_id',$this->branch_id)->get();
     return view('leadger.supplierLedger')->with('result',$result);  
    }
    // supplier full ledger
    public function supplierFullLeadger($id)
    {
      $supplier = DB::table('supplier')->where('branch_id',$this->branch_id)->where('id',$id)->first();
      $result   = DB::table('payment_ledger')->where('branch_id',$this->branch_id)->where('supplier_id',$id)->orderBy('created_at','asc')->get();
      return view('leadger.supplierFullLeadger')->with('result',$result)->with('supplier',$supplier)->with('branch_id',$this->branch_id);
    }
    // pc ledger
    public function pcLedger()
    {
     $result = DB::table('tbl_pc')->where('branch_id',$this->branch_id)->get();
     return view('leadger.pcLedger')->with('result',$result);
    }
    // pc full ledger
    public function pcFullLeadger($id)
    {
      $pc = DB::table('tbl_pc')->where('branch_id',$this->branch_id)->where('id',$id)->first();
      $result   = DB::table('pc_ledger')->where('branch_id',$this->branch_id)->where('pc_id',$id)->orderBy('created_at','asc')->get();
      return view('leadger.pcFullLeadger')->with('result',$result)->with('pc',$pc)->with('branch_id',$this->branch_id);
    }
    #-------------------------------------- CASHIER INFO ---------------------------------------------#
    // cashier current ipd bill ledger
    public function cashierCurrentIPDLedger()
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
    return view('leadger.cashierCurrentIPDLedger')->with('result',$result)->with('patient',$patient)->with('doctor',$doctor)->with('pc',$pc)->with('cabin_type',$cabin_type)->with('ward',$ward);
    }
    // ipd patient ledger
    public function ipdPatientLedger(Request $request)
    {
    $this->validate($request, [
    'room_type'                => 'required',
    ]);
     $branch                = $this->branch_id ;
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

    // // first check
    // $fisrt_ipd_duplicate_chcek = DB::table('tbl_ipd_admission')->where('branch_id',$this->branch_id)->where('id',$ipd_admisison_id_is)->where('invoice',$ipd_admisison_invoice_is)->where('patient_id',$patient_id)->where('status',1)->count();
    // if($fisrt_ipd_duplicate_chcek > 0){
    //     Session::put('failed','Sorry ! Bill Already Created Of This Patient');
    //     return Redirect::to('ipdBillClearence');
    //     exit();
    // }
    // second check
    // $second_ipd_duplicate_chcek = DB::table('tbl_ipd_clear_bill')->where('branch_id',$this->branch_id)->where('id',$ipd_admisison_id_is)->where('ipd_invoice_no',$ipd_admisison_invoice_is)->where('patient_id',$patient_id)->count();
    // if($second_ipd_duplicate_chcek > 0){
    //     Session::put('failed','Sorry ! Bill Already Created Of This Patient');
    //     return Redirect::to('ipdBillClearence');
    //     exit();
    // }
     //get ipd admission info
     $ipd_admit_query = DB::table('tbl_ipd_admission')->where('branch_id',$this->branch_id)->where('id',$ipd_admisison_id_is)->where('invoice',$ipd_admisison_invoice_is)->where('patient_id',$patient_id)->limit(1)->first();
     $patient_admit_date = $ipd_admit_query->admit_date ;
     $patient_admit_time = $ipd_admit_query->admit_time ;

     // if($patient_admit_date > $ipdEndDate){
     //    Session::put('failed','Sorry ! Patient End Date Will Not Be Small Than Patient Admit Date');
     //    return Redirect::to('ipdBillClearence');
     //    exit();
     // }

    //calculation by ipd ledger
   
   //  $start_date = date_create($patient_admit_date);
   //  $end_date   = date_create($ipdEndDate);
   //  //difference between two dates
   //  $diff = date_diff($start_date,$end_date);

   // $cabin_or_bed_rent_days = $diff->format("%a") ;
   // // total room charge amount
   // if($room_type == '1'){
   //  $total_room_bed_charge_amount = $charge_amount_is * $cabin_or_bed_rent_days ;
   // }elseif($room_type == '2'){
   //   $total_room_bed_charge_amount = $charge_amount_is * $cabin_or_bed_rent_days;

   // }

      $ipd_ledger_info      = DB::table('tbl_ipd_ledger')->where('branch_id',$this->branch_id)->where('ipd_admission_id',$ipd_admisison_id_is)->where('patient_id',$patient_id)->orderBy('service_created_at','asc')->get();

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
    $pc                   = DB::table('tbl_pc')->where('branch_id',$this->branch_id)->where('status',1)->get();

    return view('leadger.ipdPatientLedger')->with('ipd_admit_query',$ipd_admit_query)->with('ipd_ledger_info',$ipd_ledger_info)->with('cabin_room_details',$cabin_room_details)->with('ward_no_details',$ward_no_details)->with('ward_bed_details',$ward_bed_details)->with('patient_info',$patient_info)->with('pc',$pc)->with('room_type',$room_type);
    }
    // cashier current ot ledger
    public function cashierCurrentOTLedger()
    {
    // get running 
    $running_ot =  DB::table('tbl_ot_booking')
    ->join('tbl_patient', 'tbl_ot_booking.patient_id', '=', 'tbl_patient.id')
    ->join('tbl_ot_type', 'tbl_ot_booking.ot_type', '=', 'tbl_ot_type.id')
    ->where('tbl_ot_booking.branch_id',$this->branch_id)
    ->where('tbl_ot_booking.status',0)
    ->select('tbl_ot_booking.*','tbl_patient.patient_name','tbl_patient.patient_number','tbl_patient.patient_mobile','tbl_patient.patient_age','tbl_patient.patient_sex','tbl_patient.address as patient_address','tbl_patient.c_o_name','tbl_ot_type.ot_type')
    ->get();
    return view('leadger.cashierCurrentOTLedger')->with('running_ot',$running_ot);
    }
    // ot patient ledger
    public function otPatientLedger(Request $request)
    {
      $ot_booking_id = trim($request->ot_booking_id);
      $ot_booking_info_query = DB::table('tbl_ot_booking')->where('branch_id',$this->branch_id)->where('id',$ot_booking_id)->where('status',0)->limit(1)->first();

      $ot_book_invoice = $ot_booking_info_query->invoice ;
      $patient_id = $ot_booking_info_query->patient_id ;
      $ot_ledger = DB::table('tbl_ot_ledger')->where('branch_id',$this->branch_id)->where('ot_booking_id',$ot_booking_id)->where('patient_id',$patient_id)->get();
     $patient_info         = DB::table('tbl_patient')->where('branch_id',$this->branch_id)->where('id',$patient_id)->first();
      return view('leadger.otPatientLedger')->with('ot_ledger',$ot_ledger)->with('ot_booking_id',$ot_booking_id)->with('patient_id',$patient_id)->with('ot_book_invoice',$ot_book_invoice)->with('patient_info',$patient_info)->with('ot_booking_info_query',$ot_booking_info_query);
    }



    #-------------------------------------- END CASHIER INFO -----------------------------------------#
}
