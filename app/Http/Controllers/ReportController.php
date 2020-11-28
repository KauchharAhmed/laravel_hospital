<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests;
use DB;
use Session;

class ReportController extends Controller
{
     private $rcdate ;
     private $loged_id ;
     private $current_time ;
     private $branch_id ;
     /**
     * ReportController CLASS costructor 
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
    // cashier pathlogy bill report
    public function cashierPathologyBillReport()
    {
    	return view('report.cashierPathologyBillReport');
    }
    // cashier pathlogy bill report view
    public function cashierPathologyBillReportView(Request $request)
    {
      $from_date = trim($request->from_date);
      $to_date   = trim($request->to_date);
      $from      = date('Y-m-d',strtotime($from_date)) ;
      $to        = date('Y-m-d',strtotime($to_date)) ;
     $count = DB::table('pathology_bill')
    ->join('tbl_patient', 'pathology_bill.patient_id', '=', 'tbl_patient.id')
    ->join('admin', 'pathology_bill.added_id', '=', 'admin.id')
    ->select('pathology_bill.*','tbl_patient.patient_number','tbl_patient.patient_name','tbl_patient.patient_number','tbl_patient.patient_mobile','tbl_patient.patient_age','tbl_patient.patient_sex','tbl_patient.address as patient_address','tbl_patient.c_o_name','admin.name as admin_name')
    ->where('pathology_bill.branch_id',$this->branch_id)
    ->whereBetween('pathology_bill.bill_date', [$from, $to])
    ->count();
    if($count == '0'){
    	echo 'f1';
    	exit();
    }
     $result = DB::table('pathology_bill')
    ->join('tbl_patient', 'pathology_bill.patient_id', '=', 'tbl_patient.id')
    ->join('admin', 'pathology_bill.added_id', '=', 'admin.id')
    ->select('pathology_bill.*','tbl_patient.patient_number','tbl_patient.patient_name','tbl_patient.patient_number','tbl_patient.patient_mobile','tbl_patient.patient_age','tbl_patient.patient_sex','tbl_patient.address as patient_address','tbl_patient.c_o_name','admin.name as admin_name')
    ->where('pathology_bill.branch_id',$this->branch_id)
    ->whereBetween('pathology_bill.bill_date', [$from, $to])
    ->get();

     $pathology_tr_transaction = DB::table('pathology_bill_transaction')->where('branch_id',$this->branch_id)->where('invoice_tr_id',1)->where('status',0)->whereBetween('tr_date', [$from, $to])->get();
     $total_payable_amt  = 0 ;
     $total_discount_amt = 0 ;
     $total_rebate_amt   = 0 ; 
     $total_payment_amt  = 0 ;
     foreach ($pathology_tr_transaction as $pathology_tr_value) {
     	$total_payable_amt = $total_payable_amt + $pathology_tr_value->total_payable ;
     	$total_discount_amt = $total_discount_amt + $pathology_tr_value->total_discount ;
     	$total_rebate_amt = $total_rebate_amt + $pathology_tr_value->total_rebate ;
     	$total_payment_amt = $total_payment_amt + $pathology_tr_value->total_payment ;
     }

    return view('view_report.cashierPathologyBillReportView')->with('result',$result)->with('branch_id',$this->branch_id)->with('from_date',$from_date)->with('to_date',$to_date)->with('total_payable_amt',$total_payable_amt)->with('total_discount_amt',$total_discount_amt)->with('total_rebate_amt',$total_rebate_amt)->with('total_payment_amt',$total_payment_amt);

    }
    // cashier opd bill report
    public function cashierOPDBillReport()
    {
    	// with doctor
    	$doctor  = DB::table('admin')->where('branch_id',$this->branch_id)->where('type',4)->get();
    	return view('report.cashierOPDBillReport')->with('doctor',$doctor);
    }
    // cashier opd report view
    public function cashierOPDBillReportView(Request $request)
    {
      $from_date = trim($request->from_date);
      $to_date   = trim($request->to_date);
      $from      = date('Y-m-d',strtotime($from_date)) ;
      $to        = date('Y-m-d',strtotime($to_date)) ;
      $doctor    = trim($request->doctor);
      if($doctor == ''){
      	// for all doctor
      $count = DB::table('opd_bill')
    ->join('tbl_patient', 'opd_bill.patient_id', '=', 'tbl_patient.id')
    ->join('admin', 'opd_bill.added_id', '=', 'admin.id')
    ->select('opd_bill.*','tbl_patient.patient_number','tbl_patient.patient_name','tbl_patient.patient_number','tbl_patient.patient_mobile','tbl_patient.patient_age','tbl_patient.patient_sex','tbl_patient.address as patient_address','tbl_patient.c_o_name','admin.name as admin_name')
    ->where('opd_bill.branch_id',$this->branch_id)
    ->whereBetween('opd_bill.bill_date', [$from, $to])
    ->count();
    if($count == '0'){
    	echo 'f1';
    	exit();
    }

      }else{
      // for individually doctor
      $count = DB::table('opd_bill')
    ->join('tbl_patient', 'opd_bill.patient_id', '=', 'tbl_patient.id')
    ->join('admin', 'opd_bill.added_id', '=', 'admin.id')
    ->select('opd_bill.*','tbl_patient.patient_number','tbl_patient.patient_name','tbl_patient.patient_number','tbl_patient.patient_mobile','tbl_patient.patient_age','tbl_patient.patient_sex','tbl_patient.address as patient_address','tbl_patient.c_o_name','admin.name as admin_name')
    ->where('opd_bill.branch_id',$this->branch_id)
    ->where('opd_bill.doctor_id',$doctor)
    ->whereBetween('opd_bill.bill_date', [$from, $to])
    ->count();
    if($count == '0'){
    	echo 'f1';
    	exit();
    }

     }// count else ended
     // result value
       if($doctor == ''){
      	// for all doctor
      $result = DB::table('opd_bill')
    ->join('tbl_patient', 'opd_bill.patient_id', '=', 'tbl_patient.id')
    ->join('admin', 'opd_bill.added_id', '=', 'admin.id')
    ->select('opd_bill.*','tbl_patient.patient_number','tbl_patient.patient_name','tbl_patient.patient_number','tbl_patient.patient_mobile','tbl_patient.patient_age','tbl_patient.patient_sex','tbl_patient.address as patient_address','tbl_patient.c_o_name','admin.name as admin_name')
    ->where('opd_bill.branch_id',$this->branch_id)
    ->whereBetween('opd_bill.bill_date', [$from, $to])
    ->get();
      }else{
      // for individually doctor
      $result = DB::table('opd_bill')
    ->join('tbl_patient', 'opd_bill.patient_id', '=', 'tbl_patient.id')
    ->join('admin', 'opd_bill.added_id', '=', 'admin.id')
    ->select('opd_bill.*','tbl_patient.patient_number','tbl_patient.patient_name','tbl_patient.patient_number','tbl_patient.patient_mobile','tbl_patient.patient_age','tbl_patient.patient_sex','tbl_patient.address as patient_address','tbl_patient.c_o_name','admin.name as admin_name')
    ->where('opd_bill.branch_id',$this->branch_id)
    ->where('opd_bill.doctor_id',$doctor)
    ->whereBetween('opd_bill.bill_date', [$from, $to])
    ->get();
     }
     $pathology_tr_transaction = DB::table('opd_bill_transaction')->where('branch_id',$this->branch_id)->where('invoice_tr_id',1)->where('status',0)->whereBetween('tr_date', [$from, $to])->get();
     $total_payable_amt  = 0 ;
     $total_discount_amt = 0 ;
     $total_rebate_amt   = 0 ; 
     $total_payment_amt  = 0 ;
     foreach ($pathology_tr_transaction as $pathology_tr_value) {
     	$total_payable_amt = $total_payable_amt + $pathology_tr_value->total_payable ;
     	$total_discount_amt = $total_discount_amt + $pathology_tr_value->total_discount ;
     	$total_rebate_amt = $total_rebate_amt + $pathology_tr_value->total_rebate ;
     	$total_payment_amt = $total_payment_amt + $pathology_tr_value->total_payment ;
     }
     return view('view_report.cashierOPDBillReportView')->with('result',$result)->with('branch_id',$this->branch_id)->with('from_date',$from_date)->with('to_date',$to_date)->with('total_payable_amt',$total_payable_amt)->with('total_discount_amt',$total_discount_amt)->with('total_rebate_amt',$total_rebate_amt)->with('total_payment_amt',$total_payment_amt)->with('doctor',$doctor);
    }
    // cashier ipd admission bill
    public function cashierIpdAdmissionBillReport()
    {
    	return view('report.cashierIpdAdmissionBillReport');
    }
    // cashier ip admission bill
    public function cashierIpdAdmissionBillReportView(Request $request)
    { 
      $from_date = trim($request->from_date);
      $to_date   = trim($request->to_date);
      $from      = date('Y-m-d',strtotime($from_date)) ;
      $to        = date('Y-m-d',strtotime($to_date)) ;
     $count      = DB::table('tbl_ipd_admission')
    ->join('tbl_patient', 'tbl_ipd_admission.patient_id', '=', 'tbl_patient.id')
    ->join('admin', 'tbl_ipd_admission.added_id', '=', 'admin.id')
    ->join('branch', 'tbl_ipd_admission.branch_id', '=', 'branch.id')
    ->select('tbl_ipd_admission.*','tbl_patient.patient_name','tbl_patient.patient_number','tbl_patient.patient_mobile','tbl_patient.patient_age','tbl_patient.patient_sex','tbl_patient.address as patient_address','tbl_patient.c_o_name','admin.name as admin_name','branch.name','branch.address','branch.mobile','branch.image as logo')
    ->where('tbl_ipd_admission.branch_id',$this->branch_id)
    ->whereBetween('tbl_ipd_admission.admit_date', [$from, $to])
    ->count();
    if($count == '0'){
    	echo 'f1';
    	exit();
    }
     $result      = DB::table('tbl_ipd_admission')
    ->join('tbl_patient', 'tbl_ipd_admission.patient_id', '=', 'tbl_patient.id')
    ->join('admin', 'tbl_ipd_admission.added_id', '=', 'admin.id')
    ->join('branch', 'tbl_ipd_admission.branch_id', '=', 'branch.id')
    ->select('tbl_ipd_admission.*','tbl_patient.patient_name','tbl_patient.patient_number','tbl_patient.patient_mobile','tbl_patient.patient_age','tbl_patient.patient_sex','tbl_patient.address as patient_address','tbl_patient.c_o_name','admin.name as admin_name','branch.name','branch.address','branch.mobile','branch.image as logo')
    ->where('tbl_ipd_admission.branch_id',$this->branch_id)
    ->whereBetween('tbl_ipd_admission.admit_date', [$from, $to])
    ->get();
     $ipd_admission_tr_transaction = DB::table('tbl_ipd_ledger')->where('branch_id',$this->branch_id)->where('service_type',1)->whereBetween('service_created_at', [$from, $to])->get();
     $total_payable_amt  = 0 ;
     $total_discount_amt = 0 ;
     $total_rebate_amt   = 0 ; 
     $total_payment_amt  = 0 ;
     foreach ($ipd_admission_tr_transaction as $ipd_admission_tr_value) {
     	$total_payable_amt = $total_payable_amt + $ipd_admission_tr_value->payable_amount ;
     	$total_discount_amt = $total_discount_amt + $ipd_admission_tr_value->discount ;
     	$total_rebate_amt = $total_rebate_amt + $ipd_admission_tr_value->rebate ;
     	$total_payment_amt = $total_payment_amt + $ipd_admission_tr_value->payment_amount ;
     }

      return view('view_report.cashierIpdAdmissionBillReportView')->with('result',$result)->with('branch_id',$this->branch_id)->with('from_date',$from_date)->with('to_date',$to_date)->with('total_payable_amt',$total_payable_amt)->with('total_discount_amt',$total_discount_amt)->with('total_rebate_amt',$total_rebate_amt)->with('total_payment_amt',$total_payment_amt);
    }
    // cashier ipd pathlogy bill print
    public function cashierIpdPathologyBillReport()
    {
       return view('report.cashierIpdPathologyBillReport');
    }
    // cashier ipd pathlogy bill report
    public function cashierIpdPathologyBillReportView(Request $request)
    {
      $from_date = trim($request->from_date);
      $to_date   = trim($request->to_date);
      $from      = date('Y-m-d',strtotime($from_date)) ;
      $to        = date('Y-m-d',strtotime($to_date)) ;
     $count      = DB::table('tbl_ipd_pathology_bill')
    ->join('tbl_patient', 'tbl_ipd_pathology_bill.patient_id', '=', 'tbl_patient.id')
    ->join('admin', 'tbl_ipd_pathology_bill.added_id', '=', 'admin.id')
    ->join('branch', 'tbl_ipd_pathology_bill.branch_id', '=', 'branch.id')
    ->select('tbl_ipd_pathology_bill.*','tbl_patient.patient_name','tbl_patient.patient_number','tbl_patient.patient_mobile','tbl_patient.patient_age','tbl_patient.patient_sex','tbl_patient.address as patient_address','tbl_patient.c_o_name','admin.name as admin_name','branch.name','branch.address','branch.mobile','branch.image as logo')
    ->where('tbl_ipd_pathology_bill.branch_id',$this->branch_id)
    ->whereBetween('tbl_ipd_pathology_bill.bill_date', [$from, $to])
    ->count();
    if($count == '0'){
    	echo 'f1';
    	exit();
    }
     $result      = DB::table('tbl_ipd_pathology_bill')
    ->join('tbl_patient', 'tbl_ipd_pathology_bill.patient_id', '=', 'tbl_patient.id')
    ->join('admin', 'tbl_ipd_pathology_bill.added_id', '=', 'admin.id')
    ->join('branch', 'tbl_ipd_pathology_bill.branch_id', '=', 'branch.id')
    ->select('tbl_ipd_pathology_bill.*','tbl_patient.patient_name','tbl_patient.patient_number','tbl_patient.patient_mobile','tbl_patient.patient_age','tbl_patient.patient_sex','tbl_patient.address as patient_address','tbl_patient.c_o_name','admin.name as admin_name','branch.name','branch.address','branch.mobile','branch.image as logo')
    ->where('tbl_ipd_pathology_bill.branch_id',$this->branch_id)
    ->whereBetween('tbl_ipd_pathology_bill.bill_date', [$from, $to])
    ->get();

     $ipd_admission_tr_transaction = DB::table('tbl_ipd_ledger')->where('branch_id',$this->branch_id)->where('service_type',2)->whereBetween('service_created_at', [$from, $to])->get();
     $total_payable_amt  = 0 ;
     $total_discount_amt = 0 ;
     $total_rebate_amt   = 0 ; 
     $total_payment_amt  = 0 ;
     foreach ($ipd_admission_tr_transaction as $ipd_admission_tr_value) {
     	$total_payable_amt = $total_payable_amt + $ipd_admission_tr_value->payable_amount ;
     	$total_discount_amt = $total_discount_amt + $ipd_admission_tr_value->discount ;
     	$total_rebate_amt = $total_rebate_amt + $ipd_admission_tr_value->rebate ;
     	$total_payment_amt = $total_payment_amt + $ipd_admission_tr_value->payment_amount ;
     }
      return view('view_report.cashierIpdPathologyBillReportView')->with('result',$result)->with('branch_id',$this->branch_id)->with('from_date',$from_date)->with('to_date',$to_date)->with('total_payable_amt',$total_payable_amt)->with('total_discount_amt',$total_discount_amt)->with('total_rebate_amt',$total_rebate_amt)->with('total_payment_amt',$total_payment_amt);

    }
    // cashier ipd service bill report
    public function cashierIpdServiceBillReport()
    {
       return view('report.cashierIpdServiceBillReport'); 
    }
    // cashier ipd service bill report view
    public function cashierIpdServiceBillReportView(Request  $request)
    {
      $from_date = trim($request->from_date);
      $to_date   = trim($request->to_date);
      $from      = date('Y-m-d',strtotime($from_date)) ;
      $to        = date('Y-m-d',strtotime($to_date)) ;
     $count      = DB::table('tbl_ipd_service_bill')
    ->join('tbl_patient', 'tbl_ipd_service_bill.patient_id', '=', 'tbl_patient.id')
    ->join('admin', 'tbl_ipd_service_bill.added_id', '=', 'admin.id')
    ->join('branch', 'tbl_ipd_service_bill.branch_id', '=', 'branch.id')
    ->select('tbl_ipd_service_bill.*','tbl_patient.patient_name','tbl_patient.patient_number','tbl_patient.patient_mobile','tbl_patient.patient_age','tbl_patient.patient_sex','tbl_patient.address as patient_address','tbl_patient.c_o_name','admin.name as admin_name','branch.name','branch.address','branch.mobile','branch.image as logo')
    ->where('tbl_ipd_service_bill.branch_id',$this->branch_id)
    ->whereBetween('tbl_ipd_service_bill.bill_date', [$from, $to])
    ->count();
    if($count == '0'){
        echo 'f1';
        exit();
    }
     $result      = DB::table('tbl_ipd_service_bill')
    ->join('tbl_patient', 'tbl_ipd_service_bill.patient_id', '=', 'tbl_patient.id')
    ->join('admin', 'tbl_ipd_service_bill.added_id', '=', 'admin.id')
    ->join('branch', 'tbl_ipd_service_bill.branch_id', '=', 'branch.id')
    ->select('tbl_ipd_service_bill.*','tbl_patient.patient_name','tbl_patient.patient_number','tbl_patient.patient_mobile','tbl_patient.patient_age','tbl_patient.patient_sex','tbl_patient.address as patient_address','tbl_patient.c_o_name','admin.name as admin_name','branch.name','branch.address','branch.mobile','branch.image as logo')
    ->where('tbl_ipd_service_bill.branch_id',$this->branch_id)
    ->whereBetween('tbl_ipd_service_bill.bill_date', [$from, $to])
    ->get();

     $ipd_admission_tr_transaction = DB::table('tbl_ipd_ledger')->where('branch_id',$this->branch_id)->where('service_type',3)->whereBetween('service_created_at', [$from, $to])->get();
     $total_payable_amt  = 0 ;
     $total_discount_amt = 0 ;
     $total_rebate_amt   = 0 ; 
     $total_payment_amt  = 0 ;
     foreach ($ipd_admission_tr_transaction as $ipd_admission_tr_value) {
        $total_payable_amt = $total_payable_amt + $ipd_admission_tr_value->payable_amount ;
        $total_discount_amt = $total_discount_amt + $ipd_admission_tr_value->discount ;
        $total_rebate_amt = $total_rebate_amt + $ipd_admission_tr_value->rebate ;
        $total_payment_amt = $total_payment_amt + $ipd_admission_tr_value->payment_amount ;
     }
      return view('view_report.cashierIpdServiceBillReportView')->with('result',$result)->with('branch_id',$this->branch_id)->with('from_date',$from_date)->with('to_date',$to_date)->with('total_payable_amt',$total_payable_amt)->with('total_discount_amt',$total_discount_amt)->with('total_rebate_amt',$total_rebate_amt)->with('total_payment_amt',$total_payment_amt);
    }
    // ipd clearence 
    public function cashierIpdClearanceBillReport()
    {
        return view('report.cashierIpdClearanceBillReport');  
    }
    // ipd clearece bill report view
    public function cashierIpdClearanceBillReportView(Request $request)
    {
      $from_date = trim($request->from_date);
      $to_date   = trim($request->to_date);
      $from      = date('Y-m-d',strtotime($from_date)) ;
      $to        = date('Y-m-d',strtotime($to_date)) ;
     $count      = DB::table('tbl_ipd_clear_bill')
    ->join('tbl_patient', 'tbl_ipd_clear_bill.patient_id', '=', 'tbl_patient.id')
    ->join('admin', 'tbl_ipd_clear_bill.added_id', '=', 'admin.id')
    ->join('branch', 'tbl_ipd_clear_bill.branch_id', '=', 'branch.id')
    ->select('tbl_ipd_clear_bill.*','tbl_patient.patient_name','tbl_patient.patient_number','tbl_patient.patient_mobile','tbl_patient.patient_age','tbl_patient.patient_sex','tbl_patient.address as patient_address','tbl_patient.c_o_name','admin.name as admin_name','branch.name','branch.address','branch.mobile','branch.image as logo')
    ->where('tbl_ipd_clear_bill.branch_id',$this->branch_id)
    ->whereBetween('tbl_ipd_clear_bill.bill_date', [$from, $to])
    ->count();
    if($count == '0'){
        echo 'f1';
        exit();
    }
     $result      = DB::table('tbl_ipd_clear_bill')
    ->join('tbl_patient', 'tbl_ipd_clear_bill.patient_id', '=', 'tbl_patient.id')
    ->join('admin', 'tbl_ipd_clear_bill.added_id', '=', 'admin.id')
    ->join('branch', 'tbl_ipd_clear_bill.branch_id', '=', 'branch.id')
    ->select('tbl_ipd_clear_bill.*','tbl_patient.patient_name','tbl_patient.patient_number','tbl_patient.patient_mobile','tbl_patient.patient_age','tbl_patient.patient_sex','tbl_patient.address as patient_address','tbl_patient.c_o_name','admin.name as admin_name','branch.name','branch.address','branch.mobile','branch.image as logo')
    ->where('tbl_ipd_clear_bill.branch_id',$this->branch_id)
    ->whereBetween('tbl_ipd_clear_bill.bill_date', [$from, $to])
    ->get();

     $ipd_admission_tr_transaction = DB::table('tbl_ipd_ledger')->where('branch_id',$this->branch_id)->where('service_type',6)->whereBetween('service_created_at', [$from, $to])->get();
     $total_payable_amt  = 0 ;
     $total_discount_amt = 0 ;
     $total_rebate_amt   = 0 ; 
     $total_payment_amt  = 0 ;
     foreach ($ipd_admission_tr_transaction as $ipd_admission_tr_value) {
        $total_payable_amt = $total_payable_amt + $ipd_admission_tr_value->payable_amount ;
        $total_discount_amt = $total_discount_amt + $ipd_admission_tr_value->discount ;
        $total_rebate_amt = $total_rebate_amt + $ipd_admission_tr_value->rebate ;
        $total_payment_amt = $total_payment_amt + $ipd_admission_tr_value->payment_amount ;
     }
      return view('view_report.cashierIpdClearanceBillReportView')->with('result',$result)->with('branch_id',$this->branch_id)->with('from_date',$from_date)->with('to_date',$to_date)->with('total_payable_amt',$total_payable_amt)->with('total_discount_amt',$total_discount_amt)->with('total_rebate_amt',$total_rebate_amt)->with('total_payment_amt',$total_payment_amt);
    }
    // cashier ot booking
    public function cashierOTBookingBillReport()
    {
        // get ot type
        $ot_type = DB::table('tbl_ot_type')->where('branch_id',$this->branch_id)->get();
        return view('report.cashierOTBookingBillReport')->with('ot_type',$ot_type);  
    }
    // cahsier ot booking bill report
    public function cashierOTBookingBillReportView(Request $request)
    {
      $from_date = trim($request->from_date);
      $to_date   = trim($request->to_date);
      $from      = date('Y-m-d',strtotime($from_date)) ;
      $to        = date('Y-m-d',strtotime($to_date)) ;
      $ot_type   = trim($request->ot_type);
      if($ot_type == ''){
        // all ot type
     $count      = DB::table('tbl_ot_booking')
    ->join('tbl_patient', 'tbl_ot_booking.patient_id', '=', 'tbl_patient.id')
    ->join('admin', 'tbl_ot_booking.added_id', '=', 'admin.id')
    ->join('branch', 'tbl_ot_booking.branch_id', '=', 'branch.id')
    ->select('tbl_ot_booking.*','tbl_patient.patient_name','tbl_patient.patient_number','tbl_patient.patient_mobile','tbl_patient.patient_age','tbl_patient.patient_sex','tbl_patient.address as patient_address','tbl_patient.c_o_name','admin.name as admin_name','branch.name','branch.address','branch.mobile','branch.image as logo')
    ->where('tbl_ot_booking.branch_id',$this->branch_id)
    ->whereBetween('tbl_ot_booking.booking_date', [$from, $to])
    ->count();
    if($count == '0'){
        echo 'f1';
        exit();
    }
   }else{
    // individual ot type
    $count      = DB::table('tbl_ot_booking')
    ->join('tbl_patient', 'tbl_ot_booking.patient_id', '=', 'tbl_patient.id')
    ->join('admin', 'tbl_ot_booking.added_id', '=', 'admin.id')
    ->join('branch', 'tbl_ot_booking.branch_id', '=', 'branch.id')
    ->select('tbl_ot_booking.*','tbl_patient.patient_name','tbl_patient.patient_number','tbl_patient.patient_mobile','tbl_patient.patient_age','tbl_patient.patient_sex','tbl_patient.address as patient_address','tbl_patient.c_o_name','admin.name as admin_name','branch.name','branch.address','branch.mobile','branch.image as logo')
    ->where('tbl_ot_booking.branch_id',$this->branch_id)
    ->where('tbl_ot_booking.ot_type',$ot_type)
    ->whereBetween('tbl_ot_booking.booking_date', [$from, $to])
    ->count();
    if($count == '0'){
        echo 'f1';
        exit();
    }
   }
   if($ot_type == ''){
        // all ot type
     $result      = DB::table('tbl_ot_booking')
    ->join('tbl_patient', 'tbl_ot_booking.patient_id', '=', 'tbl_patient.id')
    ->join('admin', 'tbl_ot_booking.added_id', '=', 'admin.id')
    ->join('branch', 'tbl_ot_booking.branch_id', '=', 'branch.id')
    ->select('tbl_ot_booking.*','tbl_patient.patient_name','tbl_patient.patient_number','tbl_patient.patient_mobile','tbl_patient.patient_age','tbl_patient.patient_sex','tbl_patient.address as patient_address','tbl_patient.c_o_name','admin.name as admin_name','branch.name','branch.address','branch.mobile','branch.image as logo')
    ->where('tbl_ot_booking.branch_id',$this->branch_id)
    ->whereBetween('tbl_ot_booking.booking_date', [$from, $to])
    ->get();
   }else{
    // individual ot type
    $result      = DB::table('tbl_ot_booking')
    ->join('tbl_patient', 'tbl_ot_booking.patient_id', '=', 'tbl_patient.id')
    ->join('admin', 'tbl_ot_booking.added_id', '=', 'admin.id')
    ->join('branch', 'tbl_ot_booking.branch_id', '=', 'branch.id')
    ->select('tbl_ot_booking.*','tbl_patient.patient_name','tbl_patient.patient_number','tbl_patient.patient_mobile','tbl_patient.patient_age','tbl_patient.patient_sex','tbl_patient.address as patient_address','tbl_patient.c_o_name','admin.name as admin_name','branch.name','branch.address','branch.mobile','branch.image as logo')
    ->where('tbl_ot_booking.branch_id',$this->branch_id)
    ->where('tbl_ot_booking.ot_type',$ot_type)
    ->whereBetween('tbl_ot_booking.booking_date', [$from, $to])
    ->get();
   }

     $ot_booking_tr_transaction = DB::table('tbl_ot_ledger')->where('branch_id',$this->branch_id)->where('service_type',1)->whereBetween('service_created_at', [$from, $to])->get();
     $total_payable_amt  = 0 ;
     $total_discount_amt = 0 ;
     $total_rebate_amt   = 0 ; 
     $total_payment_amt  = 0 ;
     foreach ($ot_booking_tr_transaction as $ot_booking_tr_value) {
        $total_payable_amt = $total_payable_amt + $ot_booking_tr_value->payable_amount ;
        $total_discount_amt = $total_discount_amt + $ot_booking_tr_value->discount ;
        $total_rebate_amt = $total_rebate_amt + $ot_booking_tr_value->rebate ;
        $total_payment_amt = $total_payment_amt + $ot_booking_tr_value->payment_amount ;
     }

      return view('view_report.cashierOTBookingBillReportView')->with('result',$result)->with('branch_id',$this->branch_id)->with('from_date',$from_date)->with('to_date',$to_date)->with('total_payable_amt',$total_payable_amt)->with('total_discount_amt',$total_discount_amt)->with('total_rebate_amt',$total_rebate_amt)->with('total_payment_amt',$total_payment_amt)->with('ot_type',$ot_type);

    }
    // cashier ot clearnce bill report
    public function cashierOTClearanceBillReport()
    {
      return view('report.cashierOTClearanceBillReport'); 
    }
    // cashier ot clearnce bill report view
    public function cashierOTClearnceBillReportView(Request $request)
    {
      $from_date = trim($request->from_date);
      $to_date   = trim($request->to_date);
      $from      = date('Y-m-d',strtotime($from_date)) ;
      $to        = date('Y-m-d',strtotime($to_date)) ;
     $count      = DB::table('tbl_ot_clear_bill')
    ->join('tbl_patient', 'tbl_ot_clear_bill.patient_id', '=', 'tbl_patient.id')
    ->join('admin', 'tbl_ot_clear_bill.added_id', '=', 'admin.id')
    ->join('branch', 'tbl_ot_clear_bill.branch_id', '=', 'branch.id')
    ->select('tbl_ot_clear_bill.*','tbl_patient.patient_name','tbl_patient.patient_number','tbl_patient.patient_mobile','tbl_patient.patient_age','tbl_patient.patient_sex','tbl_patient.address as patient_address','tbl_patient.c_o_name','admin.name as admin_name','branch.name','branch.address','branch.mobile','branch.image as logo')
    ->where('tbl_ot_clear_bill.branch_id',$this->branch_id)
    ->whereBetween('tbl_ot_clear_bill.bill_date', [$from, $to])
    ->count();
    if($count == '0'){
        echo 'f1';
        exit();
    }
    $result      = DB::table('tbl_ot_clear_bill')
    ->join('tbl_patient', 'tbl_ot_clear_bill.patient_id', '=', 'tbl_patient.id')
    ->join('admin', 'tbl_ot_clear_bill.added_id', '=', 'admin.id')
    ->join('branch', 'tbl_ot_clear_bill.branch_id', '=', 'branch.id')
    ->select('tbl_ot_clear_bill.*','tbl_patient.patient_name','tbl_patient.patient_number','tbl_patient.patient_mobile','tbl_patient.patient_age','tbl_patient.patient_sex','tbl_patient.address as patient_address','tbl_patient.c_o_name','admin.name as admin_name','branch.name','branch.address','branch.mobile','branch.image as logo')
    ->where('tbl_ot_clear_bill.branch_id',$this->branch_id)
    ->whereBetween('tbl_ot_clear_bill.bill_date', [$from, $to])
    ->get();
    $ot_booking_tr_transaction = DB::table('tbl_ot_clear_bill')
                                ->join('tbl_ot_ledger', 'tbl_ot_clear_bill.ot_booking_id', '=', 'tbl_ot_ledger.ot_booking_id')
                                ->select('tbl_ot_ledger.*')
                                ->where('tbl_ot_ledger.branch_id',$this->branch_id)
                                ->whereBetween('tbl_ot_ledger.service_created_at', [$from, $to])
                                ->get();

     $total_payable_amt  = 0 ;
     $total_discount_amt = 0 ;
     $total_rebate_amt   = 0 ; 
     $total_payment_amt  = 0 ;
     foreach ($ot_booking_tr_transaction as $ot_booking_tr_value) {
        $total_payable_amt = $total_payable_amt + $ot_booking_tr_value->payable_amount ;
        $total_discount_amt = $total_discount_amt + $ot_booking_tr_value->discount ;
        $total_rebate_amt = $total_rebate_amt + $ot_booking_tr_value->rebate ;
        $total_payment_amt = $total_payment_amt + $ot_booking_tr_value->payment_amount ;
     }
     // total advance payment
     $advance_payment_query = DB::table('tbl_ot_clear_bill')
                                ->join('tbl_ot_ledger', 'tbl_ot_clear_bill.ot_booking_id', '=', 'tbl_ot_ledger.ot_booking_id')
                                ->select('tbl_ot_ledger.*')
                                ->where('service_type',1)
                                ->whereBetween('tbl_ot_ledger.service_created_at', [$from, $to])
                                ->get();
     $total_advance_payment = 0 ;
     foreach ($advance_payment_query as $value_advance_payment) {
      $total_advance_payment = $total_advance_payment + $value_advance_payment->payment_amount ;
     }
    return view('view_report.cashierOTClearnceBillReportView')->with('result',$result)->with('branch_id',$this->branch_id)->with('from_date',$from_date)->with('to_date',$to_date)->with('total_payable_amt',$total_payable_amt)->with('total_discount_amt',$total_discount_amt)->with('total_rebate_amt',$total_rebate_amt)->with('total_payment_amt',$total_payment_amt)->with('total_advance_payment',$total_advance_payment);
    }
    // cashier cash tranfer report
    public function cashierCashTransferReport()
    {
        return view('report.cashierCashTransferReport');
    }
    // cashisr cash transfer report view
    public function cashierCashTransferReportView(Request $request)
    {
      $from_date      = trim($request->from_date);
      $to_date        = trim($request->to_date);
      $from           = date('Y-m-d',strtotime($from_date)) ;
      $to             = date('Y-m-d',strtotime($to_date)) ;
      $transfer_type  = trim($request->transfer_type);
      if($transfer_type == ''){
        // all cash transfer
        $count = DB::table('balance_transfer')
        ->where('branch_id',$this->branch_id)
        ->whereBetween('transfer_date', [$from, $to])
        ->count();
        if($count == '0'){
        echo 'f1';
        exit();
    }
      }else{
        // individualy trnasfer type
        $count = DB::table('balance_transfer')
        ->where('branch_id',$this->branch_id)
        ->where('status',$transfer_type)
        ->whereBetween('transfer_date', [$from, $to])
        ->count();
        if($count == '0'){
        echo 'f1';
        exit();
      }
      }
    if($transfer_type == ''){
        // all cash transfer
        $result = DB::table('balance_transfer')
        ->where('branch_id',$this->branch_id)
        ->whereBetween('transfer_date', [$from, $to])
        ->get();
      }else{
        // individualy trnasfer type
        $result = DB::table('balance_transfer')
        ->where('branch_id',$this->branch_id)
        ->where('status',$transfer_type)
        ->whereBetween('transfer_date', [$from, $to])
        ->get();
      }
      return view('view_report.cashierCashTransferReportView')->with('result',$result)->with('transfer_type',$transfer_type)->with('from_date',$from_date)->with('to_date',$to_date)->with('branch_id',$this->branch_id);
    }
    #--------------------------------- MANAGER REPORT START---------------------------------------#
    // manager purchase report 
    public function managerPurchaseReport()
    {
       // with supplier
       $supplier = DB::table('supplier')->where('branch_id',$this->branch_id)->get(); 
       return view('report.managerPurchaseReport')->with('supplier',$supplier);  
    }
    // manager purchase report view
    public function managerPurchaseReportView(Request $request)
    {
      $from_date = trim($request->from_date);
      $to_date   = trim($request->to_date);
      $from      = date('Y-m-d',strtotime($from_date)) ;
      $to        = date('Y-m-d',strtotime($to_date)) ;
      $supplier  = trim($request->supplier);
      if($supplier == ''){
    // all supplier purchase
     $count      = DB::table('purchase')
    ->join('supplier', 'purchase.supplier_id', '=', 'supplier.id')
    ->join('admin', 'purchase.added_id', '=', 'admin.id')
    ->join('branch', 'purchase.branch_id', '=', 'branch.id')
    ->select('purchase.*','supplier.supplier_name','supplier.supplier_name','supplier.mobile as supplier_mobile','supplier.address as supplier_address','admin.name as admin_name','branch.name','branch.address','branch.mobile','branch.image as logo')
    ->where('purchase.branch_id',$this->branch_id)
    ->whereBetween('purchase.purchase_date', [$from, $to])
    ->count();
    if($count == '0'){
        echo 'f1';
        exit();
    }
    }else{
    // individual supplier purchase
    $count      = DB::table('purchase')
    ->join('supplier', 'purchase.supplier_id', '=', 'supplier.id')
    ->join('admin', 'purchase.added_id', '=', 'admin.id')
    ->join('branch', 'purchase.branch_id', '=', 'branch.id')
    ->select('purchase.*','supplier.supplier_name','supplier.supplier_name','supplier.mobile as supplier_mobile','supplier.address as supplier_address','admin.name as admin_name','branch.name','branch.address','branch.mobile','branch.image as logo')
    ->where('purchase.branch_id',$this->branch_id)
    ->where('purchase.supplier_id',$supplier)
    ->whereBetween('purchase.purchase_date', [$from, $to])
    ->count();
    if($count == '0'){
        echo 'f1';
        exit();
    }

    }// end count

    if($supplier == ''){
    // all supplier purchase
     $result      = DB::table('purchase')
    ->join('supplier', 'purchase.supplier_id', '=', 'supplier.id')
    ->join('admin', 'purchase.added_id', '=', 'admin.id')
    ->join('branch', 'purchase.branch_id', '=', 'branch.id')
    ->select('purchase.*','supplier.supplier_name','supplier.supplier_name','supplier.mobile as supplier_mobile','supplier.address as supplier_address','admin.name as admin_name','branch.name','branch.address','branch.mobile','branch.image as logo')
    ->where('purchase.branch_id',$this->branch_id)
    ->whereBetween('purchase.purchase_date', [$from, $to])
    ->get();
    }else{
    // individual supplier purchase
    $result      = DB::table('purchase')
    ->join('supplier', 'purchase.supplier_id', '=', 'supplier.id')
    ->join('admin', 'purchase.added_id', '=', 'admin.id')
    ->join('branch', 'purchase.branch_id', '=', 'branch.id')
    ->select('purchase.*','supplier.supplier_name','supplier.supplier_name','supplier.mobile as supplier_mobile','supplier.address as supplier_address','admin.name as admin_name','branch.name','branch.address','branch.mobile','branch.image as logo')
    ->where('purchase.branch_id',$this->branch_id)
    ->where('purchase.supplier_id',$supplier)
    ->whereBetween('purchase.purchase_date', [$from, $to])
    ->get();
    }
     return view('view_report.managerPurchaseReportView')->with('result',$result)->with('supplier',$supplier)->with('from_date',$from_date)->with('to_date',$to_date)->with('branch_id',$this->branch_id);
    }
    // manager supplier payment report
    public function managerSupplierPaymentReport()
    {
       // with supplier
       $supplier = DB::table('supplier')->where('branch_id',$this->branch_id)->get(); 
       return view('report.managerSupplierPaymentReport')->with('supplier',$supplier);  
    }
    // manager supplier payment report view
    public function managerSupplierPaymentReportView(Request $request)
    {
      $from_date = trim($request->from_date);
      $to_date   = trim($request->to_date);
      $from      = date('Y-m-d',strtotime($from_date)) ;
      $to        = date('Y-m-d',strtotime($to_date)) ;
      $supplier  = trim($request->supplier);
      if($supplier == ''){
    // all supplier purchase
     $count      = DB::table('payment')
    ->join('supplier', 'payment.supplier_id', '=', 'supplier.id')
    ->join('admin', 'payment.added_id', '=', 'admin.id')
    ->join('branch', 'payment.branch_id', '=', 'branch.id')
    ->select('payment.*','supplier.supplier_name','supplier.mobile as supplier_mobile','supplier.address as supplier_address','admin.name as admin_name','branch.name','branch.address','branch.mobile','branch.image as logo')
    ->where('payment.branch_id',$this->branch_id)
    ->whereBetween('payment.created_at', [$from, $to])
    ->count();
    if($count == '0'){
        echo 'f1';
        exit();
    }
    }else{
    // individual supplier purchase
    $count      = DB::table('payment')
    ->join('supplier', 'payment.supplier_id', '=', 'supplier.id')
    ->join('admin', 'payment.added_id', '=', 'admin.id')
    ->join('branch', 'payment.branch_id', '=', 'branch.id')
    ->select('payment.*','supplier.supplier_name','supplier.mobile as supplier_mobile','supplier.address as supplier_address','admin.name as admin_name','branch.name','branch.address','branch.mobile','branch.image as logo')
    ->where('payment.branch_id',$this->branch_id)
    ->where('payment.supplier_id',$supplier)
    ->whereBetween('payment.created_at', [$from, $to])
    ->count();
    if($count == '0'){
        echo 'f1';
        exit();
    }

    }// end count

    if($supplier == ''){
    // all supplier purchase
     $result      = DB::table('payment')
    ->join('supplier', 'payment.supplier_id', '=', 'supplier.id')
    ->join('admin', 'payment.added_id', '=', 'admin.id')
    ->join('branch', 'payment.branch_id', '=', 'branch.id')
    ->select('payment.*','supplier.supplier_name','supplier.mobile as supplier_mobile','supplier.address as supplier_address','admin.name as admin_name','branch.name','branch.address','branch.mobile','branch.image as logo')
    ->where('payment.branch_id',$this->branch_id)
    ->whereBetween('payment.created_at', [$from, $to])
    ->get();
    }else{
    // individual supplier purchase
    $result      = DB::table('payment')
    ->join('supplier', 'payment.supplier_id', '=', 'supplier.id')
    ->join('admin', 'payment.added_id', '=', 'admin.id')
    ->join('branch', 'payment.branch_id', '=', 'branch.id')
    ->select('payment.*','supplier.supplier_name','supplier.mobile as supplier_mobile','supplier.address as supplier_address','admin.name as admin_name','branch.name','branch.address','branch.mobile','branch.image as logo')
    ->where('payment.branch_id',$this->branch_id)
    ->where('payment.supplier_id',$supplier)
    ->whereBetween('payment.created_at', [$from, $to])
    ->get();
    }
     return view('view_report.managerSupplierPaymentReportView')->with('result',$result)->with('supplier',$supplier)->with('from_date',$from_date)->with('to_date',$to_date);
    }
    // manager pc report
    public function managerPCPaymentReport()
    {
       $pc = DB::table('tbl_pc')->where('branch_id',$this->branch_id)->get(); 
       return view('report.managerPCPaymentReport')->with('pc',$pc); 
    }
    // manager pc report view
    public function managerPCPaymentReportView(Request $request)
    {
      $from_date = trim($request->from_date);
      $to_date   = trim($request->to_date);
      $from      = date('Y-m-d',strtotime($from_date)) ;
      $to        = date('Y-m-d',strtotime($to_date)) ;
      $pc        = trim($request->pc);
      if($pc == ''){
    // all supplier purchase
     $count      = DB::table('pc_payment')
    ->join('tbl_pc', 'pc_payment.pc_id', '=', 'tbl_pc.id')
    ->join('admin', 'pc_payment.added_id', '=', 'admin.id')
    ->join('branch', 'pc_payment.branch_id', '=', 'branch.id')
    ->select('pc_payment.*','tbl_pc.name as pc_name','tbl_pc.mobile as pc_mobile','tbl_pc.address as pc_address','admin.name as admin_name','branch.name','branch.address','branch.mobile','branch.image as logo')
    ->where('pc_payment.branch_id',$this->branch_id)
    ->whereBetween('pc_payment.created_at', [$from, $to])
    ->count();
    if($count == '0'){
        echo 'f1';
        exit();
    }
    }else{
    // individual supplier purchase
    $count      = DB::table('pc_payment')
    ->join('tbl_pc', 'pc_payment.pc_id', '=', 'tbl_pc.id')
    ->join('admin', 'pc_payment.added_id', '=', 'admin.id')
    ->join('branch', 'pc_payment.branch_id', '=', 'branch.id')
    ->select('pc_payment.*','tbl_pc.name as pc_name','tbl_pc.mobile as pc_mobile','tbl_pc.address as pc_address','admin.name as admin_name','branch.name','branch.address','branch.mobile','branch.image as logo')
    ->where('pc_payment.branch_id',$this->branch_id)
    ->where('pc_payment.pc_id',$pc)
    ->whereBetween('pc_payment.created_at', [$from, $to])
    ->count();
    if($count == '0'){
        echo 'f1';
        exit();
    }

    }// end count

    if($pc == ''){
    // all supplier purchase
     $result      = DB::table('pc_payment')
    ->join('tbl_pc', 'pc_payment.pc_id', '=', 'tbl_pc.id')
    ->join('admin', 'pc_payment.added_id', '=', 'admin.id')
    ->join('branch', 'pc_payment.branch_id', '=', 'branch.id')
    ->select('pc_payment.*','tbl_pc.name as pc_name','tbl_pc.mobile as pc_mobile','tbl_pc.address as pc_address','admin.name as admin_name','branch.name','branch.address','branch.mobile','branch.image as logo')
    ->where('pc_payment.branch_id',$this->branch_id)
    ->whereBetween('pc_payment.created_at', [$from, $to])
    ->get();
    }else{
    // individual supplier purchase
    $result      = DB::table('pc_payment')
    ->join('tbl_pc', 'pc_payment.pc_id', '=', 'tbl_pc.id')
    ->join('admin', 'pc_payment.added_id', '=', 'admin.id')
    ->join('branch', 'pc_payment.branch_id', '=', 'branch.id')
    ->select('pc_payment.*','tbl_pc.name as pc_name','tbl_pc.mobile as pc_mobile','tbl_pc.address as pc_address','admin.name as admin_name','branch.name','branch.address','branch.mobile','branch.image as logo')
    ->where('pc_payment.branch_id',$this->branch_id)
    ->where('pc_payment.pc_id',$pc)
    ->whereBetween('pc_payment.created_at', [$from, $to])
    ->get();
    }
    return view('view_report.managerPCPaymentReportView')->with('result',$result)->with('pc',$pc)->with('from_date',$from_date)->with('to_date',$to_date);
    }
    // manager expense report
    public function managerExpenseReport()
    {
       $expense = DB::table('expense_category')->get(); 
       return view('report.managerExpenseReport')->with('expense',$expense);  
    }
    // manager expense report view
    public function managerExpenseReportView(Request $request)
    {
      $from_date = trim($request->from_date);
      $to_date   = trim($request->to_date);
      $from      = date('Y-m-d',strtotime($from_date)) ;
      $to        = date('Y-m-d',strtotime($to_date)) ;
      $expense   = trim($request->expense);
      if($expense == ''){
    // all expenxe 
     $count      = DB::table('expense_history')
    ->join('expense_category', 'expense_history.category', '=', 'expense_category.id')
    ->join('admin', 'expense_history.added_id', '=', 'admin.id')
    ->join('branch', 'expense_history.branch_id', '=', 'branch.id')
    ->select('expense_history.*','expense_category.expense_name','admin.name as admin_name','branch.name','branch.address','branch.mobile','branch.image as logo')
    ->where('expense_history.branch_id',$this->branch_id)
    ->where('expense_history.status','2')
    ->whereBetween('expense_history.created_at', [$from, $to])
    ->count();
    if($count == '0'){
        echo 'f1';
        exit();
    }
    }else{
    // individual expense
      $count      = DB::table('expense_history')
    ->join('expense_category', 'expense_history.category', '=', 'expense_category.id')
    ->join('admin', 'expense_history.added_id', '=', 'admin.id')
    ->join('branch', 'expense_history.branch_id', '=', 'branch.id')
    ->select('expense_history.*','expense_category.expense_name','admin.name as admin_name','branch.name','branch.address','branch.mobile','branch.image as logo')
    ->where('expense_history.branch_id',$this->branch_id)
    ->where('expense_history.category',$expense)
    ->where('expense_history.status','2')
    ->whereBetween('expense_history.created_at', [$from, $to])
    ->count();
    if($count == '0'){
        echo 'f1';
        exit();
    }

    }// end count

    if($expense == ''){
    $result      = DB::table('expense_history')
    ->join('expense_category', 'expense_history.category', '=', 'expense_category.id')
    ->join('admin', 'expense_history.added_id', '=', 'admin.id')
    ->join('branch', 'expense_history.branch_id', '=', 'branch.id')
    ->select('expense_history.*','expense_category.expense_name','admin.name as admin_name','branch.name','branch.address','branch.mobile','branch.image as logo')
    ->where('expense_history.branch_id',$this->branch_id)
    ->where('expense_history.status','2')
    ->whereBetween('expense_history.created_at', [$from, $to])
    ->get();
    }else{
    // individual expense
      $result      = DB::table('expense_history')
    ->join('expense_category', 'expense_history.category', '=', 'expense_category.id')
    ->join('admin', 'expense_history.added_id', '=', 'admin.id')
    ->join('branch', 'expense_history.branch_id', '=', 'branch.id')
    ->select('expense_history.*','expense_category.expense_name','admin.name as admin_name','branch.name','branch.address','branch.mobile','branch.image as logo')
    ->where('expense_history.branch_id',$this->branch_id)
    ->where('expense_history.status','2')
    ->where('expense_history.category',$expense)
    ->whereBetween('expense_history.created_at', [$from, $to])
    ->get();
    }
    return view('view_report.managerExpenseReportView')->with('result',$result)->with('expense',$expense)->with('from_date',$from_date)->with('to_date',$to_date);
    }
    // manager bank statetment
    public function managerBankStatetmentReport()
    {
       $bank = DB::table('bank')->where('system_branch_id',$this->branch_id)->get(); 
       return view('report.managerBankStatetmentReport')->with('bank',$bank);    
    }
    // manager bank statement report view
    public function managerBankStatetmentReportView(Request $request)
    {
      $from_date = trim($request->from_date);
      $to_date   = trim($request->to_date);
      $from      = date('Y-m-d',strtotime($from_date)) ;
      $to        = date('Y-m-d',strtotime($to_date)) ;
      $bank      = trim($request->bank);
      if($bank == ''){
    // all expenxe 
     $count      = DB::table('bank_transaction')
    ->join('bank', 'bank_transaction.bank_id', '=', 'bank.id')
    ->join('admin', 'bank_transaction.added_id', '=', 'admin.id')
    ->join('branch', 'bank_transaction.branch_id', '=', 'branch.id')
    ->select('bank_transaction.*','bank.bank_name','bank.branch as bank_branch','bank.account_name','bank.account_no','admin.name as admin_name','branch.name','branch.address','branch.mobile','branch.image as logo')
    ->where('bank_transaction.branch_id',$this->branch_id)
    ->where('bank_transaction.admin_type','2')
    ->whereBetween('bank_transaction.transaction_date', [$from, $to])
    ->count();
    if($count == '0'){
        echo 'f1';
        exit();
    }
    }else{
    // individual expense
       $count      = DB::table('bank_transaction')
    ->join('bank', 'bank_transaction.bank_id', '=', 'bank.id')
    ->join('admin', 'bank_transaction.added_id', '=', 'admin.id')
    ->join('branch', 'bank_transaction.branch_id', '=', 'branch.id')
    ->select('bank_transaction.*','bank.bank_name','bank.branch as bank_branch','bank.account_name','bank.account_no','admin.name as admin_name','branch.name','branch.address','branch.mobile','branch.image as logo')
    ->where('bank_transaction.branch_id',$this->branch_id)
    ->where('bank_transaction.admin_type','2')
    ->where('bank_transaction.bank_id',$bank)
    ->whereBetween('bank_transaction.transaction_date', [$from, $to])
    ->count();
    if($count == '0'){
        echo 'f1';
        exit();
    }

    }// end count
    if($bank == ''){
    // all expenxe 
     $result      = DB::table('bank_transaction')
    ->join('bank', 'bank_transaction.bank_id', '=', 'bank.id')
    ->join('admin', 'bank_transaction.added_id', '=', 'admin.id')
    ->join('branch', 'bank_transaction.branch_id', '=', 'branch.id')
    ->select('bank_transaction.*','bank.bank_name','bank.branch as bank_branch','bank.account_name','bank.account_no','admin.name as admin_name','branch.name','branch.address','branch.mobile','branch.image as logo')
    ->where('bank_transaction.branch_id',$this->branch_id)
    ->where('bank_transaction.admin_type','2')
    ->whereBetween('bank_transaction.transaction_date', [$from, $to])
    ->get();
    }else{
    // individual expense
    $result      = DB::table('bank_transaction')
    ->join('bank', 'bank_transaction.bank_id', '=', 'bank.id')
    ->join('admin', 'bank_transaction.added_id', '=', 'admin.id')
    ->join('branch', 'bank_transaction.branch_id', '=', 'branch.id')
    ->select('bank_transaction.*','bank.bank_name','bank.branch as bank_branch','bank.account_name','bank.account_no','admin.name as admin_name','branch.name','branch.address','branch.mobile','branch.image as logo')
    ->where('bank_transaction.branch_id',$this->branch_id)
    ->where('bank_transaction.admin_type','2')
    ->where('bank_transaction.bank_id',$bank)
    ->whereBetween('bank_transaction.transaction_date', [$from, $to])
    ->get();
    }
    return view('view_report.managerBankStatetmentReportView')->with('result',$result)->with('bank',$bank)->with('from_date',$from_date)->with('to_date',$to_date);
    }
    // manager cash to bank report
    public function managerCashToBankReport()
    {
       $bank = DB::table('bank')->where('system_branch_id',$this->branch_id)->get(); 
       return view('report.managerCashToBankReport')->with('bank',$bank);     
    }
    // manager cash to bank report view
    public function managerCashToBankReportView(Request $request)
    {
      $from_date = trim($request->from_date);
      $to_date   = trim($request->to_date);
      $from      = date('Y-m-d',strtotime($from_date)) ;
      $to        = date('Y-m-d',strtotime($to_date)) ;
      $bank      = trim($request->bank);
      if($bank == ''){
    // all expenxe 
     $count      = DB::table('bank_transaction')
    ->join('bank', 'bank_transaction.bank_id', '=', 'bank.id')
    ->join('admin', 'bank_transaction.added_id', '=', 'admin.id')
    ->join('branch', 'bank_transaction.branch_id', '=', 'branch.id')
    ->select('bank_transaction.*','bank.bank_name','bank.branch as bank_branch','bank.account_name','bank.account_no','admin.name as admin_name','branch.name','branch.address','branch.mobile','branch.image as logo')
    ->where('bank_transaction.branch_id',$this->branch_id)
    ->where('bank_transaction.admin_type','2')
    ->where('bank_transaction.status','5')
    ->whereBetween('bank_transaction.transaction_date', [$from, $to])
    ->count();
    if($count == '0'){
        echo 'f1';
        exit();
    }
    }else{
    // individual expense
       $count      = DB::table('bank_transaction')
    ->join('bank', 'bank_transaction.bank_id', '=', 'bank.id')
    ->join('admin', 'bank_transaction.added_id', '=', 'admin.id')
    ->join('branch', 'bank_transaction.branch_id', '=', 'branch.id')
    ->select('bank_transaction.*','bank.bank_name','bank.branch as bank_branch','bank.account_name','bank.account_no','admin.name as admin_name','branch.name','branch.address','branch.mobile','branch.image as logo')
    ->where('bank_transaction.branch_id',$this->branch_id)
    ->where('bank_transaction.admin_type','2')
    ->where('bank_transaction.status','5')
    ->where('bank_transaction.bank_id',$bank)
    ->whereBetween('bank_transaction.transaction_date', [$from, $to])
    ->count();
    if($count == '0'){
        echo 'f1';
        exit();
    }

    }// end count
    if($bank == ''){
    // all expenxe 
     $result      = DB::table('bank_transaction')
    ->join('bank', 'bank_transaction.bank_id', '=', 'bank.id')
    ->join('admin', 'bank_transaction.added_id', '=', 'admin.id')
    ->join('branch', 'bank_transaction.branch_id', '=', 'branch.id')
    ->select('bank_transaction.*','bank.bank_name','bank.branch as bank_branch','bank.account_name','bank.account_no','admin.name as admin_name','branch.name','branch.address','branch.mobile','branch.image as logo')
    ->where('bank_transaction.branch_id',$this->branch_id)
    ->where('bank_transaction.admin_type','2')
    ->where('bank_transaction.status','5')
    ->whereBetween('bank_transaction.transaction_date', [$from, $to])
    ->get();
    }else{
    // individual expense
    $result      = DB::table('bank_transaction')
    ->join('bank', 'bank_transaction.bank_id', '=', 'bank.id')
    ->join('admin', 'bank_transaction.added_id', '=', 'admin.id')
    ->join('branch', 'bank_transaction.branch_id', '=', 'branch.id')
    ->select('bank_transaction.*','bank.bank_name','bank.branch as bank_branch','bank.account_name','bank.account_no','admin.name as admin_name','branch.name','branch.address','branch.mobile','branch.image as logo')
    ->where('bank_transaction.branch_id',$this->branch_id)
    ->where('bank_transaction.admin_type','2')
    ->where('bank_transaction.status','5')
    ->where('bank_transaction.bank_id',$bank)
    ->whereBetween('bank_transaction.transaction_date', [$from, $to])
    ->get();
    }
    return view('view_report.managerCashToBankReportView')->with('result',$result)->with('bank',$bank)->with('from_date',$from_date)->with('to_date',$to_date);
    }
    // cashier bank to cash report
    public function managerBankToCashReport()
    {
       $bank = DB::table('bank')->where('system_branch_id',$this->branch_id)->get(); 
       return view('report.managerBankToCashReport')->with('bank',$bank);    
    }
   // manager bank to cash report
    public function managerBankToCashReportView(Request $request)
    {
      $from_date = trim($request->from_date);
      $to_date   = trim($request->to_date);
      $from      = date('Y-m-d',strtotime($from_date)) ;
      $to        = date('Y-m-d',strtotime($to_date)) ;
      $bank      = trim($request->bank);
      if($bank == ''){
    // all expenxe 
     $count      = DB::table('bank_transaction')
    ->join('bank', 'bank_transaction.bank_id', '=', 'bank.id')
    ->join('admin', 'bank_transaction.added_id', '=', 'admin.id')
    ->join('branch', 'bank_transaction.branch_id', '=', 'branch.id')
    ->select('bank_transaction.*','bank.bank_name','bank.branch as bank_branch','bank.account_name','bank.account_no','admin.name as admin_name','branch.name','branch.address','branch.mobile','branch.image as logo')
    ->where('bank_transaction.branch_id',$this->branch_id)
    ->where('bank_transaction.admin_type','2')
    ->where('bank_transaction.status','6')
    ->whereBetween('bank_transaction.transaction_date', [$from, $to])
    ->count();
    if($count == '0'){
        echo 'f1';
        exit();
    }
    }else{
    // individual expense
       $count      = DB::table('bank_transaction')
    ->join('bank', 'bank_transaction.bank_id', '=', 'bank.id')
    ->join('admin', 'bank_transaction.added_id', '=', 'admin.id')
    ->join('branch', 'bank_transaction.branch_id', '=', 'branch.id')
    ->select('bank_transaction.*','bank.bank_name','bank.branch as bank_branch','bank.account_name','bank.account_no','admin.name as admin_name','branch.name','branch.address','branch.mobile','branch.image as logo')
    ->where('bank_transaction.branch_id',$this->branch_id)
    ->where('bank_transaction.admin_type','2')
    ->where('bank_transaction.status','6')
    ->where('bank_transaction.bank_id',$bank)
    ->whereBetween('bank_transaction.transaction_date', [$from, $to])
    ->count();
    if($count == '0'){
        echo 'f1';
        exit();
    }

    }// end count
    if($bank == ''){
    // all expenxe 
     $result      = DB::table('bank_transaction')
    ->join('bank', 'bank_transaction.bank_id', '=', 'bank.id')
    ->join('admin', 'bank_transaction.added_id', '=', 'admin.id')
    ->join('branch', 'bank_transaction.branch_id', '=', 'branch.id')
    ->select('bank_transaction.*','bank.bank_name','bank.branch as bank_branch','bank.account_name','bank.account_no','admin.name as admin_name','branch.name','branch.address','branch.mobile','branch.image as logo')
    ->where('bank_transaction.branch_id',$this->branch_id)
    ->where('bank_transaction.admin_type','2')
    ->where('bank_transaction.status','6')
    ->whereBetween('bank_transaction.transaction_date', [$from, $to])
    ->get();
    }else{
    // individual expense
    $result      = DB::table('bank_transaction')
    ->join('bank', 'bank_transaction.bank_id', '=', 'bank.id')
    ->join('admin', 'bank_transaction.added_id', '=', 'admin.id')
    ->join('branch', 'bank_transaction.branch_id', '=', 'branch.id')
    ->select('bank_transaction.*','bank.bank_name','bank.branch as bank_branch','bank.account_name','bank.account_no','admin.name as admin_name','branch.name','branch.address','branch.mobile','branch.image as logo')
    ->where('bank_transaction.branch_id',$this->branch_id)
    ->where('bank_transaction.admin_type','2')
    ->where('bank_transaction.status','6')
    ->where('bank_transaction.bank_id',$bank)
    ->whereBetween('bank_transaction.transaction_date', [$from, $to])
    ->get();
    }
    return view('view_report.managerBankToCashReportView')->with('result',$result)->with('bank',$bank)->with('from_date',$from_date)->with('to_date',$to_date); 
    }
    // manager cash receive report
    public function managerCashReceiveReport()
    {
      return view('report.managerCashReceiveReport');    
    }
    // manager cash receive report
    public function managerCashReceiveReportView(Request $request)
    {
      $from_date      = trim($request->from_date);
      $to_date        = trim($request->to_date);
      $from           = date('Y-m-d',strtotime($from_date)) ;
      $to             = date('Y-m-d',strtotime($to_date)) ;
      $transfer_type  = trim($request->transfer_type);
      if($transfer_type == ''){
        // all cash transfer
        $count = DB::table('balance_transfer')
        ->where('branch_id',$this->branch_id)
        ->whereBetween('transfer_date', [$from, $to])
        ->count();
        if($count == '0'){
        echo 'f1';
        exit();
    }
      }else{
        // individualy trnasfer type
        $count = DB::table('balance_transfer')
        ->where('branch_id',$this->branch_id)
        ->where('status',$transfer_type)
        ->whereBetween('transfer_date', [$from, $to])
        ->count();
        if($count == '0'){
        echo 'f1';
        exit();
      }
      }
    if($transfer_type == ''){
        // all cash transfer
        $result = DB::table('balance_transfer')
        ->where('branch_id',$this->branch_id)
        ->whereBetween('transfer_date', [$from, $to])
        ->get();
      }else{
        // individualy trnasfer type
        $result = DB::table('balance_transfer')
        ->where('branch_id',$this->branch_id)
        ->where('status',$transfer_type)
        ->whereBetween('transfer_date', [$from, $to])
        ->get();
      }
      return view('view_report.managerCashReceiveReportView')->with('result',$result)->with('transfer_type',$transfer_type)->with('from_date',$from_date)->with('to_date',$to_date);
    }
    // manager income statement
    public function managerIncomeReport()
    {
      return view('report.managerIncomeReport');    
    }
    // manager cashier income statemnt
    public function managerIncomeReportView(Request $request)
    {
      $from_date      = trim($request->from_date);
      $to_date        = trim($request->to_date);
      $from           = date('Y-m-d',strtotime($from_date)) ;
      $to             = date('Y-m-d',strtotime($to_date)) ; 
     $result = DB::table('cashbook')
    ->join('branch', 'cashbook.branch_id', '=', 'branch.id')
    ->select('cashbook.*','branch.name','branch.address','branch.mobile','branch.image as logo')
    ->where('branch_id',$this->branch_id)
    ->whereBetween('cashbook.created_at', [$from, $to])
    ->orderBy('cashbook.created_at','asc')
    ->get();
     return view('view_report.managerIncomeReportView')->with('result',$result)->with('from_date',$from_date)->with('to_date',$to_date) ;
    }
   #--------------------------------- MANAGER REPORT END ----------------------------------------#
   # -------------------------------- CASHIER REPORT---------------------------------------------#
    public function cashierPathologyDueCollectionReport()
    {
       return view('report.cashierPathologyDueCollectionReport'); 
    }
    // cashier pathology due collection report
    public function cashierPathologyDueCollectionReportView(Request $request)
    {
      $from_date      = trim($request->from_date);
      $to_date        = trim($request->to_date);
      $from           = date('Y-m-d',strtotime($from_date)) ;
      $to             = date('Y-m-d',strtotime($to_date)) ; 
        $pathology_tr_count = DB::table('pathology_bill_transaction')->where('branch_id',$this->branch_id)->where('status',1)->whereBetween('tr_date', [$from, $to])->count();
        if($pathology_tr_count == '0'){
            echo "f1";
            exit();
        }

    $pathology_tr_transaction = DB::table('pathology_bill_transaction')->where('branch_id',$this->branch_id)->where('status',1)->whereBetween('tr_date', [$from, $to])->get();
    $total_payable_amt  = 0 ;
     $total_discount_amt = 0 ;
     $total_rebate_amt   = 0 ; 
     $total_payment_amt  = 0 ;
     foreach ($pathology_tr_transaction as $pathology_tr_value) {
        $total_payable_amt = $total_payable_amt + $pathology_tr_value->total_payable ;
        $total_discount_amt = $total_discount_amt + $pathology_tr_value->total_discount ;
        $total_rebate_amt = $total_rebate_amt + $pathology_tr_value->total_rebate ;
        $total_payment_amt = $total_payment_amt + $pathology_tr_value->total_payment ;
     }

    return view('view_report.cashierPathologyDueCollectionReportView')->with('pathology_tr_transaction',$pathology_tr_transaction)->with('branch_id',$this->branch_id)->with('from_date',$from_date)->with('to_date',$to_date)->with('total_payable_amt',$total_payable_amt)->with('total_discount_amt',$total_discount_amt)->with('total_rebate_amt',$total_rebate_amt)->with('total_payment_amt',$total_payment_amt);

    }
    // pathology doctor discount report
    public function cashierPathologyDoctorDiscountReport()
    {
      return view('report.cashierPathologyDoctorDiscountReport'); 
    }
    // cashier pathlogy doctor discount report view
    public function cashierPathologyDoctorDiscountReportView(Request $request)
    {
      $from_date      = trim($request->from_date);
      $to_date        = trim($request->to_date);
      $from           = date('Y-m-d',strtotime($from_date)) ;
      $to             = date('Y-m-d',strtotime($to_date)) ; 
        $pathology_tr_count = DB::table('pathology_bill_transaction')->where('branch_id',$this->branch_id)->where('status',2)->whereBetween('tr_date', [$from, $to])->count();
        if($pathology_tr_count == '0'){
            echo "f1";
            exit();
        }

    $pathology_tr_transaction = DB::table('pathology_bill_transaction')->where('branch_id',$this->branch_id)->where('status',2)->whereBetween('tr_date', [$from, $to])->get();
    $total_payable_amt  = 0 ;
     $total_discount_amt = 0 ;
     $total_rebate_amt   = 0 ; 
     $total_payment_amt  = 0 ;
     foreach ($pathology_tr_transaction as $pathology_tr_value) {
        $total_payable_amt = $total_payable_amt + $pathology_tr_value->total_payable ;
        $total_discount_amt = $total_discount_amt + $pathology_tr_value->total_discount ;
        $total_rebate_amt = $total_rebate_amt + $pathology_tr_value->total_rebate ;
        $total_payment_amt = $total_payment_amt + $pathology_tr_value->total_payment ;
     }

    return view('view_report.cashierPathologyDoctorDiscountReportView')->with('pathology_tr_transaction',$pathology_tr_transaction)->with('branch_id',$this->branch_id)->with('from_date',$from_date)->with('to_date',$to_date)->with('total_payable_amt',$total_payable_amt)->with('total_discount_amt',$total_discount_amt)->with('total_rebate_amt',$total_rebate_amt)->with('total_payment_amt',$total_payment_amt);   
    }
    // cashier opd due collect report
    public function cashierOPDDueCollectionReport()
    {
      return view('report.cashierOPDDueCollectionReport');
    }
    // cashier opd due collection report view
    public function cashierOPDDueCollectionReportView(Request $request)
    {
      $from_date      = trim($request->from_date);
      $to_date        = trim($request->to_date);
      $from           = date('Y-m-d',strtotime($from_date)) ;
      $to             = date('Y-m-d',strtotime($to_date)) ; 
        $pathology_tr_count = DB::table('opd_bill_transaction')->where('branch_id',$this->branch_id)->where('status',1)->whereBetween('tr_date', [$from, $to])->count();
        if($pathology_tr_count == '0'){
            echo "f1";
            exit();
        }

    $pathology_tr_transaction = DB::table('opd_bill_transaction')->where('branch_id',$this->branch_id)->where('status',1)->whereBetween('tr_date', [$from, $to])->get();
     $total_payable_amt  = 0 ;
     $total_discount_amt = 0 ;
     $total_rebate_amt   = 0 ; 
     $total_payment_amt  = 0 ;
     foreach ($pathology_tr_transaction as $pathology_tr_value) {
        $total_payable_amt = $total_payable_amt + $pathology_tr_value->total_payable ;
        $total_discount_amt = $total_discount_amt + $pathology_tr_value->total_discount ;
        $total_rebate_amt = $total_rebate_amt + $pathology_tr_value->total_rebate ;
        $total_payment_amt = $total_payment_amt + $pathology_tr_value->total_payment ;
     }

    return view('view_report.cashierOPDDueCollectionReportView')->with('pathology_tr_transaction',$pathology_tr_transaction)->with('branch_id',$this->branch_id)->with('from_date',$from_date)->with('to_date',$to_date)->with('total_payable_amt',$total_payable_amt)->with('total_discount_amt',$total_discount_amt)->with('total_rebate_amt',$total_rebate_amt)->with('total_payment_amt',$total_payment_amt); 
    }
    // cashier opd doctor discount report
    public function cashierOPDDoctorDiscountReport()
    {
      return view('report.cashierOPDDoctorDiscountReport');  
    }
    // cahsier opd doctor discount
    public function cashierOPDDoctorDiscountReportView(Request $request)
    {
      $from_date      = trim($request->from_date);
      $to_date        = trim($request->to_date);
      $from           = date('Y-m-d',strtotime($from_date)) ;
      $to             = date('Y-m-d',strtotime($to_date)) ; 
        $pathology_tr_count = DB::table('opd_bill_transaction')->where('branch_id',$this->branch_id)->where('status',2)->whereBetween('tr_date', [$from, $to])->count();
        if($pathology_tr_count == '0'){
            echo "f1";
            exit();
        }
    $pathology_tr_transaction = DB::table('opd_bill_transaction')->where('branch_id',$this->branch_id)->where('status',2)->whereBetween('tr_date', [$from, $to])->get();
    $total_payable_amt  = 0 ;
     $total_discount_amt = 0 ;
     $total_rebate_amt   = 0 ; 
     $total_payment_amt  = 0 ;
     foreach ($pathology_tr_transaction as $pathology_tr_value) {
        $total_payable_amt = $total_payable_amt + $pathology_tr_value->total_payable ;
        $total_discount_amt = $total_discount_amt + $pathology_tr_value->total_discount ;
        $total_rebate_amt = $total_rebate_amt + $pathology_tr_value->total_rebate ;
        $total_payment_amt = $total_payment_amt + $pathology_tr_value->total_payment ;
     }

    return view('view_report.cashierOPDDoctorDiscountReportView')->with('pathology_tr_transaction',$pathology_tr_transaction)->with('branch_id',$this->branch_id)->with('from_date',$from_date)->with('to_date',$to_date)->with('total_payable_amt',$total_payable_amt)->with('total_discount_amt',$total_discount_amt)->with('total_rebate_amt',$total_rebate_amt)->with('total_payment_amt',$total_payment_amt);   
    }

    // ot serjoun staff bill report
    public function cashierOTSurjenBillReport()
    {
      return view('report.cashierOTSurjenBillReport');    
    }
    // cashier surjeon and ot staff bill report
    public function cashierOTSurjeonAndStaffBillReportView(Request $request)
    {
      $from_date      = trim($request->from_date);
      $to_date        = trim($request->to_date);
      $from           = date('Y-m-d',strtotime($from_date)) ;
      $to             = date('Y-m-d',strtotime($to_date)) ; 
    $ot_serjeun_staff_count = DB::table('tbl_ot_serjeun_staff_bill')->where('branch_id',$this->branch_id)->whereBetween('ot_date', [$from, $to])->count();
    if($ot_serjeun_staff_count == '0'){
            echo "f1";
            exit();
        }
     $result      = DB::table('tbl_ot_serjeun_staff_bill')
    ->join('tbl_patient', 'tbl_ot_serjeun_staff_bill.patient_id', '=', 'tbl_patient.id')
    ->join('admin', 'tbl_ot_serjeun_staff_bill.added_id', '=', 'admin.id')
    ->join('branch', 'tbl_ot_serjeun_staff_bill.branch_id', '=', 'branch.id')
    ->select('tbl_ot_serjeun_staff_bill.*','tbl_patient.patient_name','tbl_patient.patient_number','tbl_patient.patient_mobile','tbl_patient.patient_age','tbl_patient.patient_sex','tbl_patient.address as patient_address','tbl_patient.c_o_name','admin.name as admin_name','branch.name','branch.address','branch.mobile','branch.image as logo')
    ->where('tbl_ot_serjeun_staff_bill.branch_id',$this->branch_id)
    ->whereBetween('tbl_ot_serjeun_staff_bill.ot_date', [$from, $to])
    ->get();

    $ot_staff_tr_transaction = DB::table('tbl_ot_ledger')->where('branch_id',$this->branch_id)->where('service_type',2)->whereBetween('service_created_at', [$from, $to])->get();
     $total_payable_amt  = 0 ;
     $total_discount_amt = 0 ;
     $total_rebate_amt   = 0 ; 
     $total_payment_amt  = 0 ;
     foreach ($ot_staff_tr_transaction as $ot_booking_tr_value) {
        $total_payable_amt = $total_payable_amt + $ot_booking_tr_value->payable_amount ;
        $total_discount_amt = $total_discount_amt + $ot_booking_tr_value->discount ;
        $total_rebate_amt = $total_rebate_amt + $ot_booking_tr_value->rebate ;
        $total_payment_amt = $total_payment_amt + $ot_booking_tr_value->payment_amount ;
     }

    return view('view_report.cashierOTSurjeonAndStaffBillReportView')->with('result',$result)->with('branch_id',$this->branch_id)->with('from_date',$from_date)->with('to_date',$to_date)->with('total_payable_amt',$total_payable_amt)->with('total_discount_amt',$total_discount_amt)->with('total_rebate_amt',$total_rebate_amt)->with('total_payment_amt',$total_payment_amt);

    }
    // casheir ot service reprot
    public function cashierOTServiceBillReport()
    {
      return view('report.cashierOTServiceBillReport');  
    }
    // cashier ot service report view
    public function cashierOTServiceBillReportView(Request $request)
    {
      $from_date      = trim($request->from_date);
      $to_date        = trim($request->to_date);
      $from           = date('Y-m-d',strtotime($from_date)) ;
      $to             = date('Y-m-d',strtotime($to_date)) ; 
     $ot_service_count = DB::table('tbl_ot_service_bill')->where('branch_id',$this->branch_id)->whereBetween('bill_date', [$from, $to])->count();
    if($ot_service_count == '0'){
            echo "f1";
            exit();
        }
    $result      = DB::table('tbl_ot_service_bill')
    ->join('tbl_patient', 'tbl_ot_service_bill.patient_id', '=', 'tbl_patient.id')
    ->join('admin', 'tbl_ot_service_bill.added_id', '=', 'admin.id')
    ->join('branch', 'tbl_ot_service_bill.branch_id', '=', 'branch.id')
    ->select('tbl_ot_service_bill.*','tbl_patient.patient_name','tbl_patient.patient_number','tbl_patient.patient_mobile','tbl_patient.patient_age','tbl_patient.patient_sex','tbl_patient.address as patient_address','tbl_patient.c_o_name','admin.name as admin_name','branch.name','branch.address','branch.mobile','branch.image as logo')
    ->where('tbl_ot_service_bill.branch_id',$this->branch_id)
    ->whereBetween('tbl_ot_service_bill.bill_date', [$from, $to])
    ->get();
    $ot_staff_tr_transaction = DB::table('tbl_ot_ledger')->where('branch_id',$this->branch_id)->where('service_type',3)->whereBetween('service_created_at', [$from, $to])->get();
     $total_payable_amt  = 0 ;
     $total_discount_amt = 0 ;
     $total_rebate_amt   = 0 ; 
     $total_payment_amt  = 0 ;
     foreach ($ot_staff_tr_transaction as $ot_booking_tr_value) {
        $total_payable_amt = $total_payable_amt + $ot_booking_tr_value->payable_amount ;
        $total_discount_amt = $total_discount_amt + $ot_booking_tr_value->discount ;
        $total_rebate_amt = $total_rebate_amt + $ot_booking_tr_value->rebate ;
        $total_payment_amt = $total_payment_amt + $ot_booking_tr_value->payment_amount ;
     }
    return view('view_report.cashierOTServiceBillReportView')->with('result',$result)->with('branch_id',$this->branch_id)->with('from_date',$from_date)->with('to_date',$to_date)->with('total_payable_amt',$total_payable_amt)->with('total_discount_amt',$total_discount_amt)->with('total_rebate_amt',$total_rebate_amt)->with('total_payment_amt',$total_payment_amt);

    }


   #--------------------------------- END  CASHIER REPORT ---------------------------------------#







    
}
