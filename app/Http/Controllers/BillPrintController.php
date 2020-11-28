<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests;
use DB;
use Session;

class BillPrintController extends Controller
{
     private $rcdate ;
     private $loged_id ;
     private $current_time ;
     private $branch_id ;
     /**
     * bill print CLASS costructor 
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
    // cashier previous bill print
    public function cashierPreviousPathologyBillReportForPrint()
    {
    	// with pathlogy bill
    	$bill = DB::table('pathology_bill')->where('branch_id',$this->branch_id)->orderBy('invoice','desc')->get();
    	return view('bill_print.cashierPreviousPathologyBillReportForPrint')->with('bill',$bill);
    }
    // cashier pathlogy bill for print
    public function cashierPathologyBillViewForPrint(Request $request)
    {
      $from_date = trim($request->from_date);
      $to_date   = trim($request->to_date);
      $from      = date('Y-m-d',strtotime($from_date)) ;
      $to        = date('Y-m-d',strtotime($to_date)) ;
      $bill      = trim($request->bill);
      if($bill == ''){
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
    }else{
    $count = DB::table('pathology_bill')
    ->join('tbl_patient', 'pathology_bill.patient_id', '=', 'tbl_patient.id')
    ->join('admin', 'pathology_bill.added_id', '=', 'admin.id')
    ->select('pathology_bill.*','tbl_patient.patient_number','tbl_patient.patient_name','tbl_patient.patient_number','tbl_patient.patient_mobile','tbl_patient.patient_age','tbl_patient.patient_sex','tbl_patient.address as patient_address','tbl_patient.c_o_name','admin.name as admin_name')
    ->where('pathology_bill.branch_id',$this->branch_id)
    ->where('pathology_bill.invoice',$bill)
    ->whereBetween('pathology_bill.bill_date', [$from, $to])
    ->count();
    if($count == '0'){
    echo 'f1';
    exit();
    }

    }// count endede
    // result 
      if($bill == ''){
     $result = DB::table('pathology_bill')
    ->join('tbl_patient', 'pathology_bill.patient_id', '=', 'tbl_patient.id')
    ->join('admin', 'pathology_bill.added_id', '=', 'admin.id')
    ->select('pathology_bill.*','tbl_patient.patient_number','tbl_patient.patient_name','tbl_patient.patient_number','tbl_patient.patient_mobile','tbl_patient.patient_age','tbl_patient.patient_sex','tbl_patient.address as patient_address','tbl_patient.c_o_name','admin.name as admin_name')
    ->where('pathology_bill.branch_id',$this->branch_id)
    ->whereBetween('pathology_bill.bill_date', [$from, $to])
    ->get();
    }else{
    $result = DB::table('pathology_bill')
    ->join('tbl_patient', 'pathology_bill.patient_id', '=', 'tbl_patient.id')
    ->join('admin', 'pathology_bill.added_id', '=', 'admin.id')
    ->select('pathology_bill.*','tbl_patient.patient_number','tbl_patient.patient_name','tbl_patient.patient_number','tbl_patient.patient_mobile','tbl_patient.patient_age','tbl_patient.patient_sex','tbl_patient.address as patient_address','tbl_patient.c_o_name','admin.name as admin_name')
    ->where('pathology_bill.branch_id',$this->branch_id)
    ->where('pathology_bill.invoice',$bill)
    ->whereBetween('pathology_bill.bill_date', [$from, $to])
    ->get();
    }
     return view('view_bill_print.cashierPathologyBillViewForPrint')->with('result',$result)->with('branch_id',$this->branch_id)->with('from_date',$from_date)->with('to_date',$to_date);

    }// function end

    // opd bill print
    public function cashierPreviousOPDBillReportForPrint()
    {
      $bill = DB::table('opd_bill')->where('branch_id',$this->branch_id)->orderBy('invoice','desc')->get();
      return view('bill_print.cashierPreviousOPDBillReportForPrint')->with('bill',$bill);
    }
    // cashier opd bill print
    public function cashierOPDBillViewForPrint(Request $request)
    {
      $from_date = trim($request->from_date);
      $to_date   = trim($request->to_date);
      $from      = date('Y-m-d',strtotime($from_date)) ;
      $to        = date('Y-m-d',strtotime($to_date)) ;
      $bill      = trim($request->bill);
      if($bill == ''){
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
    $count = DB::table('opd_bill')
    ->join('tbl_patient', 'opd_bill.patient_id', '=', 'tbl_patient.id')
    ->join('admin', 'opd_bill.added_id', '=', 'admin.id')
    ->select('opd_bill.*','tbl_patient.patient_number','tbl_patient.patient_name','tbl_patient.patient_number','tbl_patient.patient_mobile','tbl_patient.patient_age','tbl_patient.patient_sex','tbl_patient.address as patient_address','tbl_patient.c_o_name','admin.name as admin_name')
    ->where('opd_bill.branch_id',$this->branch_id)
    ->where('opd_bill.invoice',$bill)
    ->whereBetween('opd_bill.bill_date', [$from, $to])
    ->count();
    if($count == '0'){
    echo 'f1';
    exit();
    }

    }// count endede
    // result 
      if($bill == ''){
     $result = DB::table('opd_bill')
    ->join('tbl_patient', 'opd_bill.patient_id', '=', 'tbl_patient.id')
    ->join('admin', 'opd_bill.added_id', '=', 'admin.id')
    ->select('opd_bill.*','tbl_patient.patient_number','tbl_patient.patient_name','tbl_patient.patient_number','tbl_patient.patient_mobile','tbl_patient.patient_age','tbl_patient.patient_sex','tbl_patient.address as patient_address','tbl_patient.c_o_name','admin.name as admin_name')
    ->where('opd_bill.branch_id',$this->branch_id)
    ->whereBetween('opd_bill.bill_date', [$from, $to])
    ->get();
    }else{
    $result = DB::table('opd_bill')
    ->join('tbl_patient', 'opd_bill.patient_id', '=', 'tbl_patient.id')
    ->join('admin', 'opd_bill.added_id', '=', 'admin.id')
    ->select('opd_bill.*','tbl_patient.patient_number','tbl_patient.patient_name','tbl_patient.patient_number','tbl_patient.patient_mobile','tbl_patient.patient_age','tbl_patient.patient_sex','tbl_patient.address as patient_address','tbl_patient.c_o_name','admin.name as admin_name')
    ->where('opd_bill.branch_id',$this->branch_id)
    ->where('opd_bill.invoice',$bill)
    ->whereBetween('opd_bill.bill_date', [$from, $to])
    ->get();
    }
     return view('view_bill_print.cashierOPDBillViewForPrint')->with('result',$result)->with('branch_id',$this->branch_id)->with('from_date',$from_date)->with('to_date',$to_date);
    }
    // ipd admission bill print 
    public function cashierIPDAdmissionBillReportForPrint()
    {
      $bill = DB::table('tbl_ipd_admission')->where('branch_id',$this->branch_id)->orderBy('id','desc')->get();
      return view('bill_print.cashierIPDAdmissionBillReportForPrint')->with('bill',$bill);
    }
    // ipd admission bill print
    public function cashierIpdAdmissionBillViewForPrint(Request $request)
    {
      $from_date = trim($request->from_date);
      $to_date   = trim($request->to_date);
      $from      = date('Y-m-d',strtotime($from_date)) ;
      $to        = date('Y-m-d',strtotime($to_date)) ;
      $bill_id   = trim($request->bill);
      if($bill_id == ''){
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
  }else{
      $count      = DB::table('tbl_ipd_admission')
    ->join('tbl_patient', 'tbl_ipd_admission.patient_id', '=', 'tbl_patient.id')
    ->join('admin', 'tbl_ipd_admission.added_id', '=', 'admin.id')
    ->join('branch', 'tbl_ipd_admission.branch_id', '=', 'branch.id')
    ->select('tbl_ipd_admission.*','tbl_patient.patient_name','tbl_patient.patient_number','tbl_patient.patient_mobile','tbl_patient.patient_age','tbl_patient.patient_sex','tbl_patient.address as patient_address','tbl_patient.c_o_name','admin.name as admin_name','branch.name','branch.address','branch.mobile','branch.image as logo')
    ->where('tbl_ipd_admission.branch_id',$this->branch_id)
    ->where('tbl_ipd_admission.id',$bill_id)
    ->whereBetween('tbl_ipd_admission.admit_date', [$from, $to])
    ->count();
    if($count == '0'){
      echo 'f1';
      exit();
    }
  }

    if($bill_id == ''){
     $result      = DB::table('tbl_ipd_admission')
    ->join('tbl_patient', 'tbl_ipd_admission.patient_id', '=', 'tbl_patient.id')
    ->join('admin', 'tbl_ipd_admission.added_id', '=', 'admin.id')
    ->join('branch', 'tbl_ipd_admission.branch_id', '=', 'branch.id')
    ->select('tbl_ipd_admission.*','tbl_patient.patient_name','tbl_patient.patient_number','tbl_patient.patient_mobile','tbl_patient.patient_age','tbl_patient.patient_sex','tbl_patient.address as patient_address','tbl_patient.c_o_name','admin.name as admin_name','branch.name','branch.address','branch.mobile','branch.image as logo')
    ->where('tbl_ipd_admission.branch_id',$this->branch_id)
    ->whereBetween('tbl_ipd_admission.admit_date', [$from, $to])
    ->get();
  }else{
      $result      = DB::table('tbl_ipd_admission')
    ->join('tbl_patient', 'tbl_ipd_admission.patient_id', '=', 'tbl_patient.id')
    ->join('admin', 'tbl_ipd_admission.added_id', '=', 'admin.id')
    ->join('branch', 'tbl_ipd_admission.branch_id', '=', 'branch.id')
    ->select('tbl_ipd_admission.*','tbl_patient.patient_name','tbl_patient.patient_number','tbl_patient.patient_mobile','tbl_patient.patient_age','tbl_patient.patient_sex','tbl_patient.address as patient_address','tbl_patient.c_o_name','admin.name as admin_name','branch.name','branch.address','branch.mobile','branch.image as logo')
    ->where('tbl_ipd_admission.branch_id',$this->branch_id)
    ->where('tbl_ipd_admission.id',$bill_id)
    ->whereBetween('tbl_ipd_admission.admit_date', [$from, $to])
    ->get();
  }
  return view('view_bill_print.cashierIpdAdmissionBillViewForPrint')->with('result',$result)->with('branch_id',$this->branch_id)->with('from_date',$from_date)->with('to_date',$to_date);
    }
    // ipd pathlogy bill print
    public function cashierIPDPathologyBillReportForPrint()
    {
      $bill = DB::table('tbl_ipd_pathology_bill')->where('branch_id',$this->branch_id)->orderBy('id','desc')->get();
      return view('bill_print.cashierIPDPathologyBillReportForPrint')->with('bill',$bill);
    }
    // cashier ipd pathology bill for print
    public function cashierIpdPathologyBillViewForPrint(Request $request)
    {
      $from_date = trim($request->from_date);
      $to_date   = trim($request->to_date);
      $from      = date('Y-m-d',strtotime($from_date)) ;
      $to        = date('Y-m-d',strtotime($to_date)) ;
      $bill_id   = trim($request->bill);
      if($bill_id == ''){
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
  }else{
     $count      = DB::table('tbl_ipd_pathology_bill')
    ->join('tbl_patient', 'tbl_ipd_pathology_bill.patient_id', '=', 'tbl_patient.id')
    ->join('admin', 'tbl_ipd_pathology_bill.added_id', '=', 'admin.id')
    ->join('branch', 'tbl_ipd_pathology_bill.branch_id', '=', 'branch.id')
    ->select('tbl_ipd_pathology_bill.*','tbl_patient.patient_name','tbl_patient.patient_number','tbl_patient.patient_mobile','tbl_patient.patient_age','tbl_patient.patient_sex','tbl_patient.address as patient_address','tbl_patient.c_o_name','admin.name as admin_name','branch.name','branch.address','branch.mobile','branch.image as logo')
    ->where('tbl_ipd_pathology_bill.branch_id',$this->branch_id)
    ->where('tbl_ipd_pathology_bill.id',$bill_id)
    ->whereBetween('tbl_ipd_pathology_bill.bill_date', [$from, $to])
    ->count();
    if($count == '0'){
      echo 'f1';
      exit();
    }
  }

      if($bill_id == ''){
     $result      = DB::table('tbl_ipd_pathology_bill')
    ->join('tbl_patient', 'tbl_ipd_pathology_bill.patient_id', '=', 'tbl_patient.id')
    ->join('admin', 'tbl_ipd_pathology_bill.added_id', '=', 'admin.id')
    ->join('branch', 'tbl_ipd_pathology_bill.branch_id', '=', 'branch.id')
    ->select('tbl_ipd_pathology_bill.*','tbl_patient.patient_name','tbl_patient.patient_number','tbl_patient.patient_mobile','tbl_patient.patient_age','tbl_patient.patient_sex','tbl_patient.address as patient_address','tbl_patient.c_o_name','admin.name as admin_name','branch.name','branch.address','branch.mobile','branch.image as logo')
    ->where('tbl_ipd_pathology_bill.branch_id',$this->branch_id)
    ->whereBetween('tbl_ipd_pathology_bill.bill_date', [$from, $to])
    ->get();
  }else{
     $result      = DB::table('tbl_ipd_pathology_bill')
    ->join('tbl_patient', 'tbl_ipd_pathology_bill.patient_id', '=', 'tbl_patient.id')
    ->join('admin', 'tbl_ipd_pathology_bill.added_id', '=', 'admin.id')
    ->join('branch', 'tbl_ipd_pathology_bill.branch_id', '=', 'branch.id')
    ->select('tbl_ipd_pathology_bill.*','tbl_patient.patient_name','tbl_patient.patient_number','tbl_patient.patient_mobile','tbl_patient.patient_age','tbl_patient.patient_sex','tbl_patient.address as patient_address','tbl_patient.c_o_name','admin.name as admin_name','branch.name','branch.address','branch.mobile','branch.image as logo')
    ->where('tbl_ipd_pathology_bill.branch_id',$this->branch_id)
    ->where('tbl_ipd_pathology_bill.id',$bill_id)
    ->whereBetween('tbl_ipd_pathology_bill.bill_date', [$from, $to])
    ->get();
  }
  return view('view_bill_print.cashierIpdPathologyBillViewForPrint')->with('result',$result)->with('branch_id',$this->branch_id)->with('from_date',$from_date)->with('to_date',$to_date);
    }
    // cahsier ipd service bill
    public function cashierIPDServiceBillReportForPrint()
    {
      $bill = DB::table('tbl_ipd_service_bill')->where('branch_id',$this->branch_id)->orderBy('id','desc')->get();
      return view('bill_print.cashierIPDServiceBillReportForPrint')->with('bill',$bill);
    }
    // cashier ipd service bill print
    public function cashierIpdServiceBillViewForPrint(Request $request)
    {
      $from_date = trim($request->from_date);
      $to_date   = trim($request->to_date);
      $from      = date('Y-m-d',strtotime($from_date)) ;
      $to        = date('Y-m-d',strtotime($to_date)) ;
      $bill_id   = trim($request->bill);
      if($bill_id == ''){
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
  }else{
      $count      = DB::table('tbl_ipd_service_bill')
    ->join('tbl_patient', 'tbl_ipd_service_bill.patient_id', '=', 'tbl_patient.id')
    ->join('admin', 'tbl_ipd_service_bill.added_id', '=', 'admin.id')
    ->join('branch', 'tbl_ipd_service_bill.branch_id', '=', 'branch.id')
    ->select('tbl_ipd_service_bill.*','tbl_patient.patient_name','tbl_patient.patient_number','tbl_patient.patient_mobile','tbl_patient.patient_age','tbl_patient.patient_sex','tbl_patient.address as patient_address','tbl_patient.c_o_name','admin.name as admin_name','branch.name','branch.address','branch.mobile','branch.image as logo')
    ->where('tbl_ipd_service_bill.branch_id',$this->branch_id)
    ->where('tbl_ipd_service_bill.id',$bill_id)
    ->whereBetween('tbl_ipd_service_bill.bill_date', [$from, $to])
    ->count();
    if($count == '0'){
        echo 'f1';
        exit();
    }
  }

    if($bill_id == ''){
     $result      = DB::table('tbl_ipd_service_bill')
    ->join('tbl_patient', 'tbl_ipd_service_bill.patient_id', '=', 'tbl_patient.id')
    ->join('admin', 'tbl_ipd_service_bill.added_id', '=', 'admin.id')
    ->join('branch', 'tbl_ipd_service_bill.branch_id', '=', 'branch.id')
    ->select('tbl_ipd_service_bill.*','tbl_patient.patient_name','tbl_patient.patient_number','tbl_patient.patient_mobile','tbl_patient.patient_age','tbl_patient.patient_sex','tbl_patient.address as patient_address','tbl_patient.c_o_name','admin.name as admin_name','branch.name','branch.address','branch.mobile','branch.image as logo')
    ->where('tbl_ipd_service_bill.branch_id',$this->branch_id)
    ->whereBetween('tbl_ipd_service_bill.bill_date', [$from, $to])
    ->get();
  }else{
    $result      = DB::table('tbl_ipd_service_bill')
    ->join('tbl_patient', 'tbl_ipd_service_bill.patient_id', '=', 'tbl_patient.id')
    ->join('admin', 'tbl_ipd_service_bill.added_id', '=', 'admin.id')
    ->join('branch', 'tbl_ipd_service_bill.branch_id', '=', 'branch.id')
    ->select('tbl_ipd_service_bill.*','tbl_patient.patient_name','tbl_patient.patient_number','tbl_patient.patient_mobile','tbl_patient.patient_age','tbl_patient.patient_sex','tbl_patient.address as patient_address','tbl_patient.c_o_name','admin.name as admin_name','branch.name','branch.address','branch.mobile','branch.image as logo')
    ->where('tbl_ipd_service_bill.branch_id',$this->branch_id)
    ->where('tbl_ipd_service_bill.id',$bill_id)
    ->whereBetween('tbl_ipd_service_bill.bill_date', [$from, $to])
    ->get();
  }
      return view('view_bill_print.cashierIpdServiceBillViewForPrint')->with('result',$result)->with('branch_id',$this->branch_id)->with('from_date',$from_date)->with('to_date',$to_date);
    }
    // ipd clearnece bill
    public function cashierIPDClearanceBillReportForPrint()
    {
      $bill = DB::table('tbl_ipd_clear_bill')->where('branch_id',$this->branch_id)->orderBy('id','desc')->get();
      return view('bill_print.cashierIPDClearanceBillReportForPrint')->with('bill',$bill);
    }
    // cashier ipd bill repot view
    public function cashierIpdClearBillViewForPrint(Request $request)
    {
      $from_date = trim($request->from_date);
      $to_date   = trim($request->to_date);
      $from      = date('Y-m-d',strtotime($from_date)) ;
      $to        = date('Y-m-d',strtotime($to_date)) ;
      $bill_id   = trim($request->bill);
      if($bill_id == ''){
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
   }else{
    $count      = DB::table('tbl_ipd_clear_bill')
    ->join('tbl_patient', 'tbl_ipd_clear_bill.patient_id', '=', 'tbl_patient.id')
    ->join('admin', 'tbl_ipd_clear_bill.added_id', '=', 'admin.id')
    ->join('branch', 'tbl_ipd_clear_bill.branch_id', '=', 'branch.id')
    ->select('tbl_ipd_clear_bill.*','tbl_patient.patient_name','tbl_patient.patient_number','tbl_patient.patient_mobile','tbl_patient.patient_age','tbl_patient.patient_sex','tbl_patient.address as patient_address','tbl_patient.c_o_name','admin.name as admin_name','branch.name','branch.address','branch.mobile','branch.image as logo')
    ->where('tbl_ipd_clear_bill.branch_id',$this->branch_id)
    ->where('tbl_ipd_clear_bill.id',$bill_id)
    ->whereBetween('tbl_ipd_clear_bill.bill_date', [$from, $to])
    ->count();
    if($count == '0'){
        echo 'f1';
        exit();
    }
   }

    if($bill_id == ''){
     $result      = DB::table('tbl_ipd_clear_bill')
    ->join('tbl_patient', 'tbl_ipd_clear_bill.patient_id', '=', 'tbl_patient.id')
    ->join('admin', 'tbl_ipd_clear_bill.added_id', '=', 'admin.id')
    ->join('branch', 'tbl_ipd_clear_bill.branch_id', '=', 'branch.id')
    ->select('tbl_ipd_clear_bill.*','tbl_patient.patient_name','tbl_patient.patient_number','tbl_patient.patient_mobile','tbl_patient.patient_age','tbl_patient.patient_sex','tbl_patient.address as patient_address','tbl_patient.c_o_name','admin.name as admin_name','branch.name','branch.address','branch.mobile','branch.image as logo')
    ->where('tbl_ipd_clear_bill.branch_id',$this->branch_id)
    ->whereBetween('tbl_ipd_clear_bill.bill_date', [$from, $to])
    ->get();
   }else{
    $result      = DB::table('tbl_ipd_clear_bill')
    ->join('tbl_patient', 'tbl_ipd_clear_bill.patient_id', '=', 'tbl_patient.id')
    ->join('admin', 'tbl_ipd_clear_bill.added_id', '=', 'admin.id')
    ->join('branch', 'tbl_ipd_clear_bill.branch_id', '=', 'branch.id')
    ->select('tbl_ipd_clear_bill.*','tbl_patient.patient_name','tbl_patient.patient_number','tbl_patient.patient_mobile','tbl_patient.patient_age','tbl_patient.patient_sex','tbl_patient.address as patient_address','tbl_patient.c_o_name','admin.name as admin_name','branch.name','branch.address','branch.mobile','branch.image as logo')
    ->where('tbl_ipd_clear_bill.branch_id',$this->branch_id)
    ->where('tbl_ipd_clear_bill.id',$bill_id)
    ->whereBetween('tbl_ipd_clear_bill.bill_date', [$from, $to])
    ->get();
   }
  return view('view_bill_print.cashierIpdClearBillViewForPrint')->with('result',$result)->with('branch_id',$this->branch_id)->with('from_date',$from_date)->with('to_date',$to_date);
    }
    // cashier ot booking
    public function cashierOTBookingBillReportForPrint()
    {
      $bill = DB::table('tbl_ot_booking')->where('branch_id',$this->branch_id)->orderBy('id','desc')->get();
      return view('bill_print.cashierOTBookingBillReportForPrint')->with('bill',$bill); 
    }
    // cashier ot booking bill
    public function cashierOTBookingBillViewForPrint(Request $request)
    {
      $from_date = trim($request->from_date);
      $to_date   = trim($request->to_date);
      $from      = date('Y-m-d',strtotime($from_date)) ;
      $to        = date('Y-m-d',strtotime($to_date)) ;
      $bill_id   = trim($request->bill);
     if($bill_id == ''){
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
    ->where('tbl_ot_booking.id',$bill_id)
    ->whereBetween('tbl_ot_booking.booking_date', [$from, $to])
    ->count();
    if($count == '0'){
        echo 'f1';
        exit();
    }
   }
   if($bill_id == ''){
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
    ->where('tbl_ot_booking.id',$bill_id)
    ->whereBetween('tbl_ot_booking.booking_date', [$from, $to])
    ->get();
   }
    return view('view_bill_print.cashierOTBookingBillViewForPrint')->with('result',$result)->with('branch_id',$this->branch_id)->with('from_date',$from_date)->with('to_date',$to_date);
    }
    // cashier ot claear report print
    public function cashierOTClearBillReportForPrint()
    {
      $bill = DB::table('tbl_ot_clear_bill')->where('branch_id',$this->branch_id)->orderBy('id','desc')->get();
      return view('bill_print.cashierOTClearBillReportForPrint')->with('bill',$bill);
    }
    // ot clearnec bill
    public function cashierOTClearBillViewForPrint(Request $request)
    {
      $from_date = trim($request->from_date);
      $to_date   = trim($request->to_date);
      $from      = date('Y-m-d',strtotime($from_date)) ;
      $to        = date('Y-m-d',strtotime($to_date)) ;
      $bill_id   = trim($request->bill);
      if($bill_id == ''){
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
  }else{
    $count      = DB::table('tbl_ot_clear_bill')
    ->join('tbl_patient', 'tbl_ot_clear_bill.patient_id', '=', 'tbl_patient.id')
    ->join('admin', 'tbl_ot_clear_bill.added_id', '=', 'admin.id')
    ->join('branch', 'tbl_ot_clear_bill.branch_id', '=', 'branch.id')
    ->select('tbl_ot_clear_bill.*','tbl_patient.patient_name','tbl_patient.patient_number','tbl_patient.patient_mobile','tbl_patient.patient_age','tbl_patient.patient_sex','tbl_patient.address as patient_address','tbl_patient.c_o_name','admin.name as admin_name','branch.name','branch.address','branch.mobile','branch.image as logo')
    ->where('tbl_ot_clear_bill.branch_id',$this->branch_id)
    ->where('tbl_ot_clear_bill.id',$bill_id)
    ->whereBetween('tbl_ot_clear_bill.bill_date', [$from, $to])
    ->count();
    if($count == '0'){
        echo 'f1';
        exit();
    }
  }
    if($bill_id == ''){
     $result      = DB::table('tbl_ot_clear_bill')
    ->join('tbl_patient', 'tbl_ot_clear_bill.patient_id', '=', 'tbl_patient.id')
    ->join('admin', 'tbl_ot_clear_bill.added_id', '=', 'admin.id')
    ->join('branch', 'tbl_ot_clear_bill.branch_id', '=', 'branch.id')
    ->select('tbl_ot_clear_bill.*','tbl_patient.patient_name','tbl_patient.patient_number','tbl_patient.patient_mobile','tbl_patient.patient_age','tbl_patient.patient_sex','tbl_patient.address as patient_address','tbl_patient.c_o_name','admin.name as admin_name','branch.name','branch.address','branch.mobile','branch.image as logo')
    ->where('tbl_ot_clear_bill.branch_id',$this->branch_id)
    ->whereBetween('tbl_ot_clear_bill.bill_date', [$from, $to])
    ->get();
  }else{
    $result      = DB::table('tbl_ot_clear_bill')
    ->join('tbl_patient', 'tbl_ot_clear_bill.patient_id', '=', 'tbl_patient.id')
    ->join('admin', 'tbl_ot_clear_bill.added_id', '=', 'admin.id')
    ->join('branch', 'tbl_ot_clear_bill.branch_id', '=', 'branch.id')
    ->select('tbl_ot_clear_bill.*','tbl_patient.patient_name','tbl_patient.patient_number','tbl_patient.patient_mobile','tbl_patient.patient_age','tbl_patient.patient_sex','tbl_patient.address as patient_address','tbl_patient.c_o_name','admin.name as admin_name','branch.name','branch.address','branch.mobile','branch.image as logo')
    ->where('tbl_ot_clear_bill.branch_id',$this->branch_id)
    ->where('tbl_ot_clear_bill.id',$bill_id)
    ->whereBetween('tbl_ot_clear_bill.bill_date', [$from, $to])
    ->get(); 
  }
    return view('view_bill_print.cashierOTClearBillViewForPrint')->with('result',$result)->with('branch_id',$this->branch_id)->with('from_date',$from_date)->with('to_date',$to_date);
    }
    // cashier pathlogy pc change
    public function cashierPathologyPcChange()
    {
      $bill = DB::table('pathology_bill')->where('branch_id',$this->branch_id)->orderBy('invoice','desc')->get();
      return view('bill_print.cashierPathologyPcChange')->with('bill',$bill);
    }
    // pathology pc change
    public function cashierPathologyBillViewForPCChange(Request $request)
    {
      $from_date = trim($request->from_date);
      $to_date   = trim($request->to_date);
      $from      = date('Y-m-d',strtotime($from_date)) ;
      $to        = date('Y-m-d',strtotime($to_date)) ;
      $bill      = trim($request->bill);
      if($bill == ''){
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
    }else{
    $count = DB::table('pathology_bill')
    ->join('tbl_patient', 'pathology_bill.patient_id', '=', 'tbl_patient.id')
    ->join('admin', 'pathology_bill.added_id', '=', 'admin.id')
    ->select('pathology_bill.*','tbl_patient.patient_number','tbl_patient.patient_name','tbl_patient.patient_number','tbl_patient.patient_mobile','tbl_patient.patient_age','tbl_patient.patient_sex','tbl_patient.address as patient_address','tbl_patient.c_o_name','admin.name as admin_name')
    ->where('pathology_bill.branch_id',$this->branch_id)
    ->where('pathology_bill.invoice',$bill)
    ->whereBetween('pathology_bill.bill_date', [$from, $to])
    ->count();
    if($count == '0'){
    echo 'f1';
    exit();
    }

    }// count endede
    // result 
      if($bill == ''){
     $result = DB::table('pathology_bill')
    ->join('tbl_patient', 'pathology_bill.patient_id', '=', 'tbl_patient.id')
    ->join('admin', 'pathology_bill.added_id', '=', 'admin.id')
    ->select('pathology_bill.*','tbl_patient.patient_number','tbl_patient.patient_name','tbl_patient.patient_number','tbl_patient.patient_mobile','tbl_patient.patient_age','tbl_patient.patient_sex','tbl_patient.address as patient_address','tbl_patient.c_o_name','admin.name as admin_name')
    ->where('pathology_bill.branch_id',$this->branch_id)
    ->whereBetween('pathology_bill.bill_date', [$from, $to])
    ->get();
    }else{
    $result = DB::table('pathology_bill')
    ->join('tbl_patient', 'pathology_bill.patient_id', '=', 'tbl_patient.id')
    ->join('admin', 'pathology_bill.added_id', '=', 'admin.id')
    ->select('pathology_bill.*','tbl_patient.patient_number','tbl_patient.patient_name','tbl_patient.patient_number','tbl_patient.patient_mobile','tbl_patient.patient_age','tbl_patient.patient_sex','tbl_patient.address as patient_address','tbl_patient.c_o_name','admin.name as admin_name')
    ->where('pathology_bill.branch_id',$this->branch_id)
    ->where('pathology_bill.invoice',$bill)
    ->whereBetween('pathology_bill.bill_date', [$from, $to])
    ->get();
    }
     return view('view_bill_print.cashierPathologyBillViewForPCChange')->with('result',$result)->with('branch_id',$this->branch_id)->with('from_date',$from_date)->with('to_date',$to_date);
    }
    // pathlogy pc change
    public function pathologyPCChange($invoice , $year_invoice , $daily_invoice , $casbook_id)
    {
      $pathology_query =  DB::table('pathology_bill')
    ->join('tbl_patient', 'pathology_bill.patient_id', '=', 'tbl_patient.id')
    ->join('admin', 'pathology_bill.added_id', '=', 'admin.id')
    ->select('pathology_bill.*','tbl_patient.patient_number','tbl_patient.patient_name','tbl_patient.patient_mobile','tbl_patient.patient_age','tbl_patient.patient_sex','tbl_patient.address as patient_address','tbl_patient.c_o_name','admin.name as admin_name')
    ->where('pathology_bill.branch_id',$this->branch_id)
    ->where('pathology_bill.invoice',$invoice)
    ->where('pathology_bill.year_invoice',$year_invoice)
    ->where('pathology_bill.daily_invoice',$daily_invoice)
    ->where('pathology_bill.cashbook_id',$casbook_id)
    ->limit(1)
    ->first();
    
        $pathology_tr_query = DB::table('pathology_bill_transaction')->where('branch_id',$this->branch_id)->where('invoice_number',$invoice)->where('year_invoice_number',$year_invoice)->where('daily_invoice_number',$daily_invoice)->get(); 
                                                $total_payable  = 0 ;
                                                $total_discount = 0 ;
                                                $total_rebate = 0 ;
                                                $total_payment = 0 ;
                                                    foreach ($pathology_tr_query as $pathology_tr_value) {
                                                      $total_payable = $total_payable + $pathology_tr_value->total_payable ;
                                                      $total_discount = $total_discount + $pathology_tr_value->total_discount ;
                                                      $total_rebate    = $total_rebate + $pathology_tr_value->total_rebate ;
                                                      $total_payment    = $total_payment + $pathology_tr_value->total_payment ;
                                                    }
        $pathology_return_amount = DB::table('pathology_bill_transaction')->where('branch_id',$this->branch_id)->where('invoice_number',$invoice)->where('year_invoice_number',$year_invoice)->where('daily_invoice_number',$daily_invoice)->where('status',2)->get(); 
        $total_return_amount = 0 ;
        foreach ($pathology_return_amount as $return_value) {
        $total_return_amount = $total_return_amount + $return_value->total_discount ;
        }
        $total_payment_amt_is = $total_payment - $total_return_amount ;

       $pc_id = $pathology_query->pc_id ;
       if($pc_id == '0'){
        // hospital
        $pc_amount = '0.00';
       }else{
        $pc_amt_query = DB::table('pc_ledger')->where('branch_id',$this->branch_id)->where('invoice',$invoice)->where('year_invoice',$year_invoice)->where('daily_invoice_number',$daily_invoice)->where('cashbook_id',$casbook_id)->where('invoice_type',1)->where('pc_id',$pc_id)->first();
         $pc_amount =  $pc_amt_query->payable_amount ;
       }
       // get all pc
       $pc = DB::table('tbl_pc')->where('branch_id',$this->branch_id)->get();
       return view('view_bill_print.pathologyPCChange')->with('pathology_query',$pathology_query)->with('total_payable',$total_payable)->with('total_discount',$total_discount)->with('total_rebate',$total_rebate)->with('total_payment_amt_is',$total_payment_amt_is)->with('pc_amount',$pc_amount)->with('pc',$pc)->with('invoice',$invoice)->with('year_invoice',$year_invoice)->with('daily_invoice',$daily_invoice)->with('casbook_id',$casbook_id);
    }
    // change pc amount
    public function changePathologyPcAmountInfo(Request $request)
    {
    $this->validate($request, [
    'pc_id'                  => 'required',
    'change_date'            => 'required',
    'pc_amount'              => 'required',
    'confirm_pc_amount'      => 'required',
    'invoice'                => 'required',
    'year_invoice'           => 'required',
    'daily_invoice'          => 'required',
    'cashbook_id'            => 'required',
    'bill_date'              => 'required',
    'total_bill_amt'         => 'required',
    'patient_id'             => 'required',
    'previous_pc_id'         => 'required',
    'previous_pc_amount'     => 'required',
    
    ]);
     $pc_id              = trim($request->pc_id);
     $change_date        = trim($request->change_date);
     $changeDate         = date('Y-m-d',strtotime($change_date)) ;
     $pc_amount          = trim($request->pc_amount);
     $confirm_pc_amount  = trim($request->confirm_pc_amount);
     $invoice            = trim($request->invoice);
     $year_invoice       = trim($request->year_invoice);
     $daily_invoice      = trim($request->daily_invoice);
     $cashbook_id        = trim($request->cashbook_id);
     $bill_date          = trim($request->bill_date);
     $total_bill_amt     = trim($request->total_bill_amt);
     $patient_id         = trim($request->patient_id);
     $previous_pc_id     = trim($request->previous_pc_id);
     $previous_pc_amount  = trim($request->previous_pc_amount);
     $remarks            = trim($request->remarks);
     #---------------------- START VALIDATION -----------------------#
        if($changeDate > $this->rcdate){
        Session::put('failed','Sorry ! Enter Invalid Date. Please Enter Valid Date And Try Again ');
        return Redirect::to('pathologyPCChange/'.$invoice.'/'.$year_invoice.'/'.$daily_invoice.'/'.$cashbook_id);
        exit();
     }
      if($changeDate < $bill_date){
        Session::put('failed','Sorry ! This Bill Create '.$bill_date.' So PC Change Date Not Small Than It');
        return Redirect::to('pathologyPCChange/'.$invoice.'/'.$year_invoice.'/'.$daily_invoice.'/'.$cashbook_id);
        exit();
     }
      if($pc_amount != $confirm_pc_amount){
        Session::put('failed','Sorry ! PC Amount And Confirm PC Amount Did Not Match. Try Again');
        return Redirect::to('pathologyPCChange/'.$invoice.'/'.$year_invoice.'/'.$daily_invoice.'/'.$cashbook_id); 
        exit();
      }
      // pc hospital
      if($pc_id == '0'){
        if($pc_amount > 0){
        Session::put('failed','Sorry ! You Have Selected PC As A Hospital But Given PC Amount. PC Amount Will Be 0 For Hospital PC . Try Again');
        return Redirect::to('pathologyPCChange/'.$invoice.'/'.$year_invoice.'/'.$daily_invoice.'/'.$cashbook_id); 
        exit();
        }
      }
      //pc amount big than total bill amt
      if($total_bill_amt < $pc_amount){
        Session::put('failed','Sorry ! PC Amount Did Not Big Than Total Bill Amount. Try Again');
        return Redirect::to('pathologyPCChange/'.$invoice.'/'.$year_invoice.'/'.$daily_invoice.'/'.$cashbook_id); 
        exit();
      }
      // pathology bill update
      $data_pathlogy_bill_update                = array();
      $data_pathlogy_bill_update['pc_id']       = $pc_id ;
      $data_pathlogy_bill_update['modified_at'] = $this->rcdate;
      DB::table('pathology_bill')->where('branch_id',$this->branch_id)->where('cashbook_id',$cashbook_id)->where('invoice',$invoice)->where('year_invoice',$year_invoice)->where('daily_invoice',$daily_invoice)->where('patient_id',$patient_id)->update($data_pathlogy_bill_update) ;
      if($previous_pc_id == '0' AND $pc_id == '0'){
       // not any transaction 
      }elseif($previous_pc_id == '0' AND $pc_id != '0'){
      $data_pc_ledger_insert                      = array();
      $data_pc_ledger_insert['cashbook_id']       = $cashbook_id ;
      $data_pc_ledger_insert['branch_id']         = $this->branch_id ;
      $data_pc_ledger_insert['invoice']           = $invoice ;
      $data_pc_ledger_insert['year_invoice']      = $year_invoice;
      $data_pc_ledger_insert['daily_invoice_number'] = $daily_invoice;
      $data_pc_ledger_insert['invoice_type']      = 1;
      $data_pc_ledger_insert['pc_id']             = $pc_id ;
      $data_pc_ledger_insert['payable_amount']    = $pc_amount ;
      $data_pc_ledger_insert['status']            = 1 ; 
      $data_pc_ledger_insert['purpose']           = 'Pathology Bill Create';
      $data_pc_ledger_insert['added_id']          = $this->loged_id; 
      $data_pc_ledger_insert['created_time']      = $this->current_time ;
      $data_pc_ledger_insert['created_at']        = $bill_date ;
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

      }elseif($previous_pc_id != '0' AND $pc_id == '0'){
        // delete pc info from ledger
        DB::table('pc_ledger')->where('branch_id',$this->branch_id)->where('cashbook_id',$cashbook_id)->where('invoice',$invoice)->where('year_invoice',$year_invoice)->where('daily_invoice_number',$daily_invoice)->where('invoice_type',1)->where('status',1)->delete();
        // redue the pc id deu
      $pc_due_query = DB::table('pc_due')->where('pc_id',$previous_pc_id)->limit(1)->first();
      $pc_due_amount = $pc_due_query->total_due_amount ;
      $now_pc_due_amount = $pc_due_amount - $previous_pc_amount ;
      // updte pc due amount
      $data_pc_due_update     = array();
      $data_pc_due_update['total_due_amount'] = $now_pc_due_amount; 
      DB::table('pc_due')->where('pc_id',$previous_pc_id)->update($data_pc_due_update);

      }elseif($previous_pc_id != '0' AND $pc_id != '0'){
        $data_pc_ledger_update                   = array();
        $data_pc_ledger_update['pc_id']          = $pc_id ;
        $data_pc_ledger_update['payable_amount'] = $pc_amount ;
        $data_pc_ledger_update['remarks']        = $remarks;
        $data_pc_ledger_update['modified_at']    = $this->rcdate;
        DB::table('pc_ledger')->where('branch_id',$this->branch_id)->where('cashbook_id',$cashbook_id)->where('invoice',$invoice)->where('year_invoice',$year_invoice)->where('daily_invoice_number',$daily_invoice)->where('invoice_type',1)->where('status',1)->update($data_pc_ledger_update) ;
        // previus pc due reduce
      $pc_due_query = DB::table('pc_due')->where('pc_id',$previous_pc_id)->limit(1)->first();
      $pc_due_amount = $pc_due_query->total_due_amount ;
      $now_pc_due_amount = $pc_due_amount - $previous_pc_amount ;
      // updte pc due amount
      $data_pc_due_update     = array();
      $data_pc_due_update['total_due_amount'] = $now_pc_due_amount; 
      DB::table('pc_due')->where('pc_id',$previous_pc_id)->update($data_pc_due_update);
      // incress ne pc due  amount
      $pc_due_query_new = DB::table('pc_due')->where('pc_id',$pc_id)->limit(1)->first();
      $pc_due_amount_new = $pc_due_query_new->total_due_amount ;
      $now_pc_due_amount_new = $pc_due_amount_new + $pc_amount ;
      // updte pc due amount
      $data_pc_due_update_new     = array();
      $data_pc_due_update_new['total_due_amount'] = $now_pc_due_amount_new; 
      DB::table('pc_due')->where('pc_id',$pc_id)->update($data_pc_due_update_new);
      }

      //pc history
      $data_pc_history                        = array();
      $data_pc_history['cashbook_id']         = $cashbook_id ;
      $data_pc_history['branch_id']           = $this->branch_id ;
      $data_pc_history['invoice']             = $invoice ;
      $data_pc_history['year_invoice']        = $year_invoice;
      $data_pc_history['daily_invoice_number'] = $daily_invoice;
      $data_pc_history['invoice_type']         = 1 ;
      $data_pc_history['old_pc_id']            = $previous_pc_id ;
      $data_pc_history['new_pc_id']            = $pc_id ;
      $data_pc_history['old_pc_amount']        = $previous_pc_amount ;
      $data_pc_history['new_pc_amount']        = $pc_amount ;
      $data_pc_history['status']               = 1 ;
      $data_pc_history['remarks']              = $remarks ;
      $data_pc_history['added_id']             = $this->loged_id;
      $data_pc_history['created_time']         = $this->current_time ;
      $data_pc_history['created_at']           = $changeDate ;
      $data_pc_history['on_created_at']        = $this->rcdate ;
      DB::table('pc_change_history')->insert($data_pc_history);
      Session::put('succes','Thanks , PC Information Change Sucessfully');
      return Redirect::to('cashierPathologyPcChange');

     #---------------------- END START VALIDATION --------------------#
    }
    // cashier ipd pc change
    public function cashierIPDPcChange()
    {
      $bill = DB::table('tbl_ipd_clear_bill')->where('branch_id',$this->branch_id)->orderBy('id','desc')->get();
      return view('bill_print.cashierIPDPcChange')->with('bill',$bill);
    }
    // view ipd clear bill for pc change
    public function cashierIpdClearBillViewForPCChange(Request $request)
    {
     $from_date = trim($request->from_date);
      $to_date   = trim($request->to_date);
      $from      = date('Y-m-d',strtotime($from_date)) ;
      $to        = date('Y-m-d',strtotime($to_date)) ;
      $bill_id   = trim($request->bill);
      if($bill_id == ''){
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
   }else{
    $count      = DB::table('tbl_ipd_clear_bill')
    ->join('tbl_patient', 'tbl_ipd_clear_bill.patient_id', '=', 'tbl_patient.id')
    ->join('admin', 'tbl_ipd_clear_bill.added_id', '=', 'admin.id')
    ->join('branch', 'tbl_ipd_clear_bill.branch_id', '=', 'branch.id')
    ->select('tbl_ipd_clear_bill.*','tbl_patient.patient_name','tbl_patient.patient_number','tbl_patient.patient_mobile','tbl_patient.patient_age','tbl_patient.patient_sex','tbl_patient.address as patient_address','tbl_patient.c_o_name','admin.name as admin_name','branch.name','branch.address','branch.mobile','branch.image as logo')
    ->where('tbl_ipd_clear_bill.branch_id',$this->branch_id)
    ->where('tbl_ipd_clear_bill.id',$bill_id)
    ->whereBetween('tbl_ipd_clear_bill.bill_date', [$from, $to])
    ->count();
    if($count == '0'){
        echo 'f1';
        exit();
    }
   }

    if($bill_id == ''){
     $result      = DB::table('tbl_ipd_clear_bill')
    ->join('tbl_patient', 'tbl_ipd_clear_bill.patient_id', '=', 'tbl_patient.id')
    ->join('admin', 'tbl_ipd_clear_bill.added_id', '=', 'admin.id')
    ->join('branch', 'tbl_ipd_clear_bill.branch_id', '=', 'branch.id')
    ->select('tbl_ipd_clear_bill.*','tbl_patient.patient_name','tbl_patient.patient_number','tbl_patient.patient_mobile','tbl_patient.patient_age','tbl_patient.patient_sex','tbl_patient.address as patient_address','tbl_patient.c_o_name','admin.name as admin_name','branch.name','branch.address','branch.mobile','branch.image as logo')
    ->where('tbl_ipd_clear_bill.branch_id',$this->branch_id)
    ->whereBetween('tbl_ipd_clear_bill.bill_date', [$from, $to])
    ->get();
   }else{
    $result      = DB::table('tbl_ipd_clear_bill')
    ->join('tbl_patient', 'tbl_ipd_clear_bill.patient_id', '=', 'tbl_patient.id')
    ->join('admin', 'tbl_ipd_clear_bill.added_id', '=', 'admin.id')
    ->join('branch', 'tbl_ipd_clear_bill.branch_id', '=', 'branch.id')
    ->select('tbl_ipd_clear_bill.*','tbl_patient.patient_name','tbl_patient.patient_number','tbl_patient.patient_mobile','tbl_patient.patient_age','tbl_patient.patient_sex','tbl_patient.address as patient_address','tbl_patient.c_o_name','admin.name as admin_name','branch.name','branch.address','branch.mobile','branch.image as logo')
    ->where('tbl_ipd_clear_bill.branch_id',$this->branch_id)
    ->where('tbl_ipd_clear_bill.id',$bill_id)
    ->whereBetween('tbl_ipd_clear_bill.bill_date', [$from, $to])
    ->get();
   }
  return view('view_bill_print.cashierIpdClearBillViewForPCChange')->with('result',$result)->with('branch_id',$this->branch_id)->with('from_date',$from_date)->with('to_date',$to_date);
    }
    // ipd pc change
    public function ipdPCChange($invoice , $year_invoice , $daily_invoice , $casbook_id , $ipd_admission_id)
    {
      $ipd_query =  DB::table('tbl_ipd_clear_bill')
    ->join('tbl_patient', 'tbl_ipd_clear_bill.patient_id', '=', 'tbl_patient.id')
    ->join('admin', 'tbl_ipd_clear_bill.added_id', '=', 'admin.id')
    ->select('tbl_ipd_clear_bill.*','tbl_patient.patient_number','tbl_patient.patient_name','tbl_patient.patient_mobile','tbl_patient.patient_age','tbl_patient.patient_sex','tbl_patient.address as patient_address','tbl_patient.c_o_name','admin.name as admin_name')
    ->where('tbl_ipd_clear_bill.branch_id',$this->branch_id)
    ->where('tbl_ipd_clear_bill.invoice',$invoice)
    ->where('tbl_ipd_clear_bill.year_invoice',$year_invoice)
    ->where('tbl_ipd_clear_bill.daily_invoice',$daily_invoice)
    ->where('tbl_ipd_clear_bill.cashbook_id',$casbook_id)
    ->where('tbl_ipd_clear_bill.ipd_admission_id',$ipd_admission_id)
    ->limit(1)
    ->first();
    
        $ipd_admission_tr_transaction = DB::table('tbl_ipd_ledger')->where('branch_id',$this->branch_id)->where('ipd_admission_id',$ipd_admission_id)->get();
                                                $total_payable  = 0 ;
                                                $total_discount = 0 ;
                                                $total_rebate = 0 ;
                                                $total_payment = 0 ;
                                                    foreach ($ipd_admission_tr_transaction as $pathology_tr_value) {
                                                      $total_payable = $total_payable + $pathology_tr_value->payable_amount ;
                                                      $total_discount = $total_discount + $pathology_tr_value->discount ;
                                                      $total_rebate    = $total_rebate + $pathology_tr_value->rebate ;
                                                      $total_payment    = $total_payment + $pathology_tr_value->payment_amount ;
                                                    }

       

       $pc_id = $ipd_query->pc_id ;
       if($pc_id == '0'){
        // hospital
        $pc_amount = '0.00';
       }else{
        $pc_amt_query = DB::table('pc_ledger')->where('branch_id',$this->branch_id)->where('invoice',$invoice)->where('year_invoice',$year_invoice)->where('daily_invoice_number',$daily_invoice)->where('cashbook_id',$casbook_id)->where('invoice_type',2)->where('pc_id',$pc_id)->first();
         $pc_amount =  $pc_amt_query->payable_amount ;
       }
       // get all pc
       $pc = DB::table('tbl_pc')->where('branch_id',$this->branch_id)->get();
       return view('view_bill_print.ipdPCChange')->with('ipd_query',$ipd_query)->with('total_payable',$total_payable)->with('total_discount',$total_discount)->with('total_rebate',$total_rebate)->with('total_payment',$total_payment)->with('pc_amount',$pc_amount)->with('pc',$pc)->with('invoice',$invoice)->with('year_invoice',$year_invoice)->with('daily_invoice',$daily_invoice)->with('casbook_id',$casbook_id)->with('ipd_admission_id',$ipd_admission_id);
    }
    // change ipd pc info
    public function changeIPDPcAmountInfo(Request $request)
    {
     $this->validate($request, [
    'pc_id'                  => 'required',
    'change_date'            => 'required',
    'pc_amount'              => 'required',
    'confirm_pc_amount'      => 'required',
    'invoice'                => 'required',
    'year_invoice'           => 'required',
    'daily_invoice'          => 'required',
    'cashbook_id'            => 'required',
    'bill_date'              => 'required',
    'total_bill_amt'         => 'required',
    'patient_id'             => 'required',
    'previous_pc_id'         => 'required',
    'previous_pc_amount'     => 'required',
    'ipd_admission_id'       => 'required',
    ]);
     $pc_id              = trim($request->pc_id);
     $change_date        = trim($request->change_date);
     $changeDate         = date('Y-m-d',strtotime($change_date)) ;
     $pc_amount          = trim($request->pc_amount);
     $confirm_pc_amount  = trim($request->confirm_pc_amount);
     $invoice            = trim($request->invoice);
     $year_invoice       = trim($request->year_invoice);
     $daily_invoice      = trim($request->daily_invoice);
     $cashbook_id        = trim($request->cashbook_id);
     $bill_date          = trim($request->bill_date);
     $total_bill_amt     = trim($request->total_bill_amt);
     $patient_id         = trim($request->patient_id);
     $previous_pc_id     = trim($request->previous_pc_id);
     $previous_pc_amount  = trim($request->previous_pc_amount);
     $ipd_admission_id    = trim($request->ipd_admission_id);
     $remarks            = trim($request->remarks);
     #---------------------- START VALIDATION -----------------------#
        if($changeDate > $this->rcdate){
        Session::put('failed','Sorry ! Enter Invalid Date. Please Enter Valid Date And Try Again ');
        return Redirect::to('ipdPCChange/'.$invoice.'/'.$year_invoice.'/'.$daily_invoice.'/'.$cashbook_id.'/'.$ipd_admission_id);
        exit();
     }
      if($changeDate < $bill_date){
        Session::put('failed','Sorry ! This Bill Create '.$bill_date.' So PC Change Date Not Small Than It');
        return Redirect::to('ipdPCChange/'.$invoice.'/'.$year_invoice.'/'.$daily_invoice.'/'.$cashbook_id.'/'.$ipd_admission_id);
        exit();
     }
      if($pc_amount != $confirm_pc_amount){
        Session::put('failed','Sorry ! PC Amount And Confirm PC Amount Did Not Match. Try Again');
        return Redirect::to('ipdPCChange/'.$invoice.'/'.$year_invoice.'/'.$daily_invoice.'/'.$cashbook_id.'/'.$ipd_admission_id);
        exit();
      }
      // pc hospital
      if($pc_id == '0'){
        if($pc_amount > 0){
        Session::put('failed','Sorry ! You Have Selected PC As A Hospital But Given PC Amount. PC Amount Will Be 0 For Hospital PC . Try Again');
        return Redirect::to('ipdPCChange/'.$invoice.'/'.$year_invoice.'/'.$daily_invoice.'/'.$cashbook_id.'/'.$ipd_admission_id); 
        exit();
        }
      }
      //pc amount big than total bill amt
      if($total_bill_amt < $pc_amount){
        Session::put('failed','Sorry ! PC Amount Did Not Big Than Total Bill Amount. Try Again');
        return Redirect::to('ipdPCChange/'.$invoice.'/'.$year_invoice.'/'.$daily_invoice.'/'.$cashbook_id.'/'.$ipd_admission_id);
        exit();
      }
      // pathology bill update
      $data_pathlogy_bill_update                = array();
      $data_pathlogy_bill_update['pc_id']       = $pc_id ;
      $data_pathlogy_bill_update['modified_at'] = $this->rcdate;
      DB::table('tbl_ipd_clear_bill')->where('branch_id',$this->branch_id)->where('cashbook_id',$cashbook_id)->where('invoice',$invoice)->where('year_invoice',$year_invoice)->where('daily_invoice',$daily_invoice)->where('patient_id',$patient_id)->where('ipd_admission_id',$ipd_admission_id)->update($data_pathlogy_bill_update) ;
      if($previous_pc_id == '0' AND $pc_id == '0'){
       // not any transaction 
      }elseif($previous_pc_id == '0' AND $pc_id != '0'){
      $data_pc_ledger_insert                      = array();
      $data_pc_ledger_insert['cashbook_id']       = $cashbook_id ;
      $data_pc_ledger_insert['branch_id']         = $this->branch_id ;
      $data_pc_ledger_insert['invoice']           = $invoice ;
      $data_pc_ledger_insert['year_invoice']      = $year_invoice;
      $data_pc_ledger_insert['daily_invoice_number'] = $daily_invoice;
      $data_pc_ledger_insert['invoice_type']      = 2;
      $data_pc_ledger_insert['pc_id']             = $pc_id ;
      $data_pc_ledger_insert['payable_amount']    = $pc_amount ;
      $data_pc_ledger_insert['status']            = 2 ; 
      $data_pc_ledger_insert['purpose']           = 'IPD Clearence Bill Create';
      $data_pc_ledger_insert['added_id']          = $this->loged_id; 
      $data_pc_ledger_insert['created_time']      = $this->current_time ;
      $data_pc_ledger_insert['created_at']        = $bill_date ;
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

      }elseif($previous_pc_id != '0' AND $pc_id == '0'){
        // delete pc info from ledger
        DB::table('pc_ledger')->where('branch_id',$this->branch_id)->where('cashbook_id',$cashbook_id)->where('invoice',$invoice)->where('year_invoice',$year_invoice)->where('daily_invoice_number',$daily_invoice)->where('invoice_type',2)->where('status',2)->delete();
        // redue the pc id deu
      $pc_due_query = DB::table('pc_due')->where('pc_id',$previous_pc_id)->limit(1)->first();
      $pc_due_amount = $pc_due_query->total_due_amount ;
      $now_pc_due_amount = $pc_due_amount - $previous_pc_amount ;
      // updte pc due amount
      $data_pc_due_update     = array();
      $data_pc_due_update['total_due_amount'] = $now_pc_due_amount; 
      DB::table('pc_due')->where('pc_id',$previous_pc_id)->update($data_pc_due_update);

      }elseif($previous_pc_id != '0' AND $pc_id != '0'){
        $data_pc_ledger_update                   = array();
        $data_pc_ledger_update['pc_id']          = $pc_id ;
        $data_pc_ledger_update['payable_amount'] = $pc_amount ;
        $data_pc_ledger_update['remarks']        = $remarks;
        $data_pc_ledger_update['modified_at']    = $this->rcdate;
        DB::table('pc_ledger')->where('branch_id',$this->branch_id)->where('cashbook_id',$cashbook_id)->where('invoice',$invoice)->where('year_invoice',$year_invoice)->where('daily_invoice_number',$daily_invoice)->where('invoice_type',2)->where('status',2)->update($data_pc_ledger_update) ;
        // previus pc due reduce
      $pc_due_query = DB::table('pc_due')->where('pc_id',$previous_pc_id)->limit(1)->first();
      $pc_due_amount = $pc_due_query->total_due_amount ;
      $now_pc_due_amount = $pc_due_amount - $previous_pc_amount ;
      // updte pc due amount
      $data_pc_due_update     = array();
      $data_pc_due_update['total_due_amount'] = $now_pc_due_amount; 
      DB::table('pc_due')->where('pc_id',$previous_pc_id)->update($data_pc_due_update);
      // incress ne pc due  amount
      $pc_due_query_new = DB::table('pc_due')->where('pc_id',$pc_id)->limit(1)->first();
      $pc_due_amount_new = $pc_due_query_new->total_due_amount ;
      $now_pc_due_amount_new = $pc_due_amount_new + $pc_amount ;
      // updte pc due amount
      $data_pc_due_update_new     = array();
      $data_pc_due_update_new['total_due_amount'] = $now_pc_due_amount_new; 
      DB::table('pc_due')->where('pc_id',$pc_id)->update($data_pc_due_update_new);
      }

      //pc history
      $data_pc_history                        = array();
      $data_pc_history['cashbook_id']         = $cashbook_id ;
      $data_pc_history['branch_id']           = $this->branch_id ;
      $data_pc_history['invoice']             = $invoice ;
      $data_pc_history['year_invoice']        = $year_invoice;
      $data_pc_history['daily_invoice_number'] = $daily_invoice;
      $data_pc_history['invoice_type']         = 2 ;
      $data_pc_history['old_pc_id']            = $previous_pc_id ;
      $data_pc_history['new_pc_id']            = $pc_id ;
      $data_pc_history['old_pc_amount']        = $previous_pc_amount ;
      $data_pc_history['new_pc_amount']        = $pc_amount ;
      $data_pc_history['status']               = 2 ;
      $data_pc_history['remarks']              = $remarks ;
      $data_pc_history['added_id']             = $this->loged_id;
      $data_pc_history['created_time']         = $this->current_time ;
      $data_pc_history['created_at']           = $changeDate ;
      $data_pc_history['on_created_at']        = $this->rcdate ;
      DB::table('pc_change_history')->insert($data_pc_history);
      Session::put('succes','Thanks , IPD PC Information Change Sucessfully');
      return Redirect::to('cashierIPDPcChange');
    }
    // cahshier ot pc change
    public function cashierOTPcChange()
    {
      $bill = DB::table('tbl_ot_clear_bill')->where('branch_id',$this->branch_id)->orderBy('id','desc')->get();
      return view('bill_print.cashierOTPcChange')->with('bill',$bill);
    }
    // change ot pc info
    public function cashierOTClearBillViewForChangePC(Request $request)
    {
      $from_date = trim($request->from_date);
      $to_date   = trim($request->to_date);
      $from      = date('Y-m-d',strtotime($from_date)) ;
      $to        = date('Y-m-d',strtotime($to_date)) ;
      $bill_id   = trim($request->bill);
      if($bill_id == ''){
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
  }else{
    $count      = DB::table('tbl_ot_clear_bill')
    ->join('tbl_patient', 'tbl_ot_clear_bill.patient_id', '=', 'tbl_patient.id')
    ->join('admin', 'tbl_ot_clear_bill.added_id', '=', 'admin.id')
    ->join('branch', 'tbl_ot_clear_bill.branch_id', '=', 'branch.id')
    ->select('tbl_ot_clear_bill.*','tbl_patient.patient_name','tbl_patient.patient_number','tbl_patient.patient_mobile','tbl_patient.patient_age','tbl_patient.patient_sex','tbl_patient.address as patient_address','tbl_patient.c_o_name','admin.name as admin_name','branch.name','branch.address','branch.mobile','branch.image as logo')
    ->where('tbl_ot_clear_bill.branch_id',$this->branch_id)
    ->where('tbl_ot_clear_bill.id',$bill_id)
    ->whereBetween('tbl_ot_clear_bill.bill_date', [$from, $to])
    ->count();
    if($count == '0'){
        echo 'f1';
        exit();
    }
  }
    if($bill_id == ''){
     $result      = DB::table('tbl_ot_clear_bill')
    ->join('tbl_patient', 'tbl_ot_clear_bill.patient_id', '=', 'tbl_patient.id')
    ->join('admin', 'tbl_ot_clear_bill.added_id', '=', 'admin.id')
    ->join('branch', 'tbl_ot_clear_bill.branch_id', '=', 'branch.id')
    ->select('tbl_ot_clear_bill.*','tbl_patient.patient_name','tbl_patient.patient_number','tbl_patient.patient_mobile','tbl_patient.patient_age','tbl_patient.patient_sex','tbl_patient.address as patient_address','tbl_patient.c_o_name','admin.name as admin_name','branch.name','branch.address','branch.mobile','branch.image as logo')
    ->where('tbl_ot_clear_bill.branch_id',$this->branch_id)
    ->whereBetween('tbl_ot_clear_bill.bill_date', [$from, $to])
    ->get();
  }else{
    $result      = DB::table('tbl_ot_clear_bill')
    ->join('tbl_patient', 'tbl_ot_clear_bill.patient_id', '=', 'tbl_patient.id')
    ->join('admin', 'tbl_ot_clear_bill.added_id', '=', 'admin.id')
    ->join('branch', 'tbl_ot_clear_bill.branch_id', '=', 'branch.id')
    ->select('tbl_ot_clear_bill.*','tbl_patient.patient_name','tbl_patient.patient_number','tbl_patient.patient_mobile','tbl_patient.patient_age','tbl_patient.patient_sex','tbl_patient.address as patient_address','tbl_patient.c_o_name','admin.name as admin_name','branch.name','branch.address','branch.mobile','branch.image as logo')
    ->where('tbl_ot_clear_bill.branch_id',$this->branch_id)
    ->where('tbl_ot_clear_bill.id',$bill_id)
    ->whereBetween('tbl_ot_clear_bill.bill_date', [$from, $to])
    ->get(); 
  }
    return view('view_bill_print.cashierOTClearBillViewForChangePC')->with('result',$result)->with('branch_id',$this->branch_id)->with('from_date',$from_date)->with('to_date',$to_date);
    }
    // ot pc change
    public function otPCChange($invoice , $year_invoice , $daily_invoice , $casbook_id , $ot_booking_id)
    {
      $ot_query =  DB::table('tbl_ot_clear_bill')
    ->join('tbl_patient', 'tbl_ot_clear_bill.patient_id', '=', 'tbl_patient.id')
    ->join('admin', 'tbl_ot_clear_bill.added_id', '=', 'admin.id')
    ->select('tbl_ot_clear_bill.*','tbl_patient.patient_number','tbl_patient.patient_name','tbl_patient.patient_mobile','tbl_patient.patient_age','tbl_patient.patient_sex','tbl_patient.address as patient_address','tbl_patient.c_o_name','admin.name as admin_name')
    ->where('tbl_ot_clear_bill.branch_id',$this->branch_id)
    ->where('tbl_ot_clear_bill.invoice',$invoice)
    ->where('tbl_ot_clear_bill.year_invoice',$year_invoice)
    ->where('tbl_ot_clear_bill.daily_invoice',$daily_invoice)
    ->where('tbl_ot_clear_bill.cashbook_id',$casbook_id)
    ->where('tbl_ot_clear_bill.ot_booking_id',$ot_booking_id)
    ->limit(1)
    ->first();
    
      $ot_tr_transaction = DB::table('tbl_ot_ledger')->where('branch_id',$this->branch_id)->where('ot_booking_id',$ot_booking_id)->get();
                                                $total_payable  = 0 ;
                                                $total_discount = 0 ;
                                                $total_rebate = 0 ;
                                                $total_payment = 0 ;
                                                    foreach ($ot_tr_transaction as $pathology_tr_value) {
                                                      $total_payable = $total_payable + $pathology_tr_value->payable_amount ;
                                                      $total_discount = $total_discount + $pathology_tr_value->discount ;
                                                      $total_rebate    = $total_rebate + $pathology_tr_value->rebate ;
                                                      $total_payment    = $total_payment + $pathology_tr_value->payment_amount ;
                                                    }

       

       $pc_id = $ot_query->pc_id ;
       if($pc_id == '0'){
        // hospital
        $pc_amount = '0.00';
       }else{
        $pc_amt_query = DB::table('pc_ledger')->where('branch_id',$this->branch_id)->where('invoice',$invoice)->where('year_invoice',$year_invoice)->where('daily_invoice_number',$daily_invoice)->where('cashbook_id',$casbook_id)->where('invoice_type',3)->where('pc_id',$pc_id)->first();
         $pc_amount =  $pc_amt_query->payable_amount ;
       }
       // get all pc
       $pc = DB::table('tbl_pc')->where('branch_id',$this->branch_id)->get();
       return view('view_bill_print.otPCChange')->with('ot_query',$ot_query)->with('total_payable',$total_payable)->with('total_discount',$total_discount)->with('total_rebate',$total_rebate)->with('total_payment',$total_payment)->with('pc_amount',$pc_amount)->with('pc',$pc)->with('invoice',$invoice)->with('year_invoice',$year_invoice)->with('daily_invoice',$daily_invoice)->with('casbook_id',$casbook_id)->with('ot_booking_id',$ot_booking_id);
    }
    // chage ot pc amount info
    public function changeOTPcAmountInfo(Request $request)
    {
      $this->validate($request, [
    'pc_id'                  => 'required',
    'change_date'            => 'required',
    'pc_amount'              => 'required',
    'confirm_pc_amount'      => 'required',
    'invoice'                => 'required',
    'year_invoice'           => 'required',
    'daily_invoice'          => 'required',
    'cashbook_id'            => 'required',
    'bill_date'              => 'required',
    'total_bill_amt'         => 'required',
    'patient_id'             => 'required',
    'previous_pc_id'         => 'required',
    'previous_pc_amount'     => 'required',
    'ot_booking_id'          => 'required',
    ]);
     $pc_id              = trim($request->pc_id);
     $change_date        = trim($request->change_date);
     $changeDate         = date('Y-m-d',strtotime($change_date)) ;
     $pc_amount          = trim($request->pc_amount);
     $confirm_pc_amount  = trim($request->confirm_pc_amount);
     $invoice            = trim($request->invoice);
     $year_invoice       = trim($request->year_invoice);
     $daily_invoice      = trim($request->daily_invoice);
     $cashbook_id        = trim($request->cashbook_id);
     $bill_date          = trim($request->bill_date);
     $total_bill_amt     = trim($request->total_bill_amt);
     $patient_id         = trim($request->patient_id);
     $previous_pc_id     = trim($request->previous_pc_id);
     $previous_pc_amount  = trim($request->previous_pc_amount);
     $ot_booking_id      = trim($request->ot_booking_id);
     $remarks            = trim($request->remarks);
     #---------------------- START VALIDATION -----------------------#
        if($changeDate > $this->rcdate){
        Session::put('failed','Sorry ! Enter Invalid Date. Please Enter Valid Date And Try Again ');
        return Redirect::to('otPCChange/'.$invoice.'/'.$year_invoice.'/'.$daily_invoice.'/'.$cashbook_id.'/'.$ot_booking_id);
        exit();
     }
      if($changeDate < $bill_date){
        Session::put('failed','Sorry ! This Bill Create '.$bill_date.' So PC Change Date Not Small Than It');
        return Redirect::to('otPCChange/'.$invoice.'/'.$year_invoice.'/'.$daily_invoice.'/'.$cashbook_id.'/'.$ot_booking_id);
        exit();
     }
      if($pc_amount != $confirm_pc_amount){
        Session::put('failed','Sorry ! PC Amount And Confirm PC Amount Did Not Match. Try Again');
        return Redirect::to('otPCChange/'.$invoice.'/'.$year_invoice.'/'.$daily_invoice.'/'.$cashbook_id.'/'.$ot_booking_id);
        exit();
      }
      // pc hospital
      if($pc_id == '0'){
        if($pc_amount > 0){
        Session::put('failed','Sorry ! You Have Selected PC As A Hospital But Given PC Amount. PC Amount Will Be 0 For Hospital PC . Try Again');
        return Redirect::to('otPCChange/'.$invoice.'/'.$year_invoice.'/'.$daily_invoice.'/'.$cashbook_id.'/'.$ot_booking_id); 
        exit();
        }
      }
      //pc amount big than total bill amt
      if($total_bill_amt < $pc_amount){
        Session::put('failed','Sorry ! PC Amount Did Not Big Than Total Bill Amount. Try Again');
        return Redirect::to('otPCChange/'.$invoice.'/'.$year_invoice.'/'.$daily_invoice.'/'.$cashbook_id.'/'.$ot_booking_id);
        exit();
      }
      // pathology bill update
      $data_pathlogy_bill_update                = array();
      $data_pathlogy_bill_update['pc_id']       = $pc_id ;
      $data_pathlogy_bill_update['modified_at'] = $this->rcdate;
      DB::table('tbl_ot_clear_bill')->where('branch_id',$this->branch_id)->where('cashbook_id',$cashbook_id)->where('invoice',$invoice)->where('year_invoice',$year_invoice)->where('daily_invoice',$daily_invoice)->where('patient_id',$patient_id)->where('ot_booking_id',$ot_booking_id)->update($data_pathlogy_bill_update) ;
      if($previous_pc_id == '0' AND $pc_id == '0'){
       // not any transaction 
      }elseif($previous_pc_id == '0' AND $pc_id != '0'){
      $data_pc_ledger_insert                      = array();
      $data_pc_ledger_insert['cashbook_id']       = $cashbook_id ;
      $data_pc_ledger_insert['branch_id']         = $this->branch_id ;
      $data_pc_ledger_insert['invoice']           = $invoice ;
      $data_pc_ledger_insert['year_invoice']      = $year_invoice;
      $data_pc_ledger_insert['daily_invoice_number'] = $daily_invoice;
      $data_pc_ledger_insert['invoice_type']      = 3;
      $data_pc_ledger_insert['pc_id']             = $pc_id ;
      $data_pc_ledger_insert['payable_amount']    = $pc_amount ;
      $data_pc_ledger_insert['status']            = 3 ; 
      $data_pc_ledger_insert['purpose']           = 'OT Clearence Bill Create';
      $data_pc_ledger_insert['added_id']          = $this->loged_id; 
      $data_pc_ledger_insert['created_time']      = $this->current_time ;
      $data_pc_ledger_insert['created_at']        = $bill_date ;
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

      }elseif($previous_pc_id != '0' AND $pc_id == '0'){
        // delete pc info from ledger
        DB::table('pc_ledger')->where('branch_id',$this->branch_id)->where('cashbook_id',$cashbook_id)->where('invoice',$invoice)->where('year_invoice',$year_invoice)->where('daily_invoice_number',$daily_invoice)->where('invoice_type',3)->where('status',3)->delete();
        // redue the pc id deu
      $pc_due_query = DB::table('pc_due')->where('pc_id',$previous_pc_id)->limit(1)->first();
      $pc_due_amount = $pc_due_query->total_due_amount ;
      $now_pc_due_amount = $pc_due_amount - $previous_pc_amount ;
      // updte pc due amount
      $data_pc_due_update     = array();
      $data_pc_due_update['total_due_amount'] = $now_pc_due_amount; 
      DB::table('pc_due')->where('pc_id',$previous_pc_id)->update($data_pc_due_update);

      }elseif($previous_pc_id != '0' AND $pc_id != '0'){
        $data_pc_ledger_update                   = array();
        $data_pc_ledger_update['pc_id']          = $pc_id ;
        $data_pc_ledger_update['payable_amount'] = $pc_amount ;
        $data_pc_ledger_update['remarks']        = $remarks;
        $data_pc_ledger_update['modified_at']    = $this->rcdate;
        DB::table('pc_ledger')->where('branch_id',$this->branch_id)->where('cashbook_id',$cashbook_id)->where('invoice',$invoice)->where('year_invoice',$year_invoice)->where('daily_invoice_number',$daily_invoice)->where('invoice_type',3)->where('status',3)->update($data_pc_ledger_update) ;
        // previus pc due reduce
      $pc_due_query = DB::table('pc_due')->where('pc_id',$previous_pc_id)->limit(1)->first();
      $pc_due_amount = $pc_due_query->total_due_amount ;
      $now_pc_due_amount = $pc_due_amount - $previous_pc_amount ;
      // updte pc due amount
      $data_pc_due_update     = array();
      $data_pc_due_update['total_due_amount'] = $now_pc_due_amount; 
      DB::table('pc_due')->where('pc_id',$previous_pc_id)->update($data_pc_due_update);
      // incress ne pc due  amount
      $pc_due_query_new = DB::table('pc_due')->where('pc_id',$pc_id)->limit(1)->first();
      $pc_due_amount_new = $pc_due_query_new->total_due_amount ;
      $now_pc_due_amount_new = $pc_due_amount_new + $pc_amount ;
      // updte pc due amount
      $data_pc_due_update_new     = array();
      $data_pc_due_update_new['total_due_amount'] = $now_pc_due_amount_new; 
      DB::table('pc_due')->where('pc_id',$pc_id)->update($data_pc_due_update_new);
      }
      //pc history
      $data_pc_history                        = array();
      $data_pc_history['cashbook_id']         = $cashbook_id ;
      $data_pc_history['branch_id']           = $this->branch_id ;
      $data_pc_history['invoice']             = $invoice ;
      $data_pc_history['year_invoice']        = $year_invoice;
      $data_pc_history['daily_invoice_number'] = $daily_invoice;
      $data_pc_history['invoice_type']         = 3 ;
      $data_pc_history['old_pc_id']            = $previous_pc_id ;
      $data_pc_history['new_pc_id']            = $pc_id ;
      $data_pc_history['old_pc_amount']        = $previous_pc_amount ;
      $data_pc_history['new_pc_amount']        = $pc_amount ;
      $data_pc_history['status']               = 3 ;
      $data_pc_history['remarks']              = $remarks ;
      $data_pc_history['added_id']             = $this->loged_id;
      $data_pc_history['created_time']         = $this->current_time ;
      $data_pc_history['created_at']           = $changeDate ;
      $data_pc_history['on_created_at']        = $this->rcdate ;
      DB::table('pc_change_history')->insert($data_pc_history);
      Session::put('succes','Thanks , OT PC Information Change Sucessfully');
      return Redirect::to('cashierOTPcChange'); 
    }
}
