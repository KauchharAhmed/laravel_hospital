<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests;
use DB;
use Session;

class PrintController extends Controller
{
     private $rcdate ;
     private $loged_id ;
     private $current_time ;
     private $branch_id ;
     /**
     * print CLASS costructor 
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
     * Purchase Bill Print.
     *
     * @param  int  $bill
     * @return \Illuminate\Http\Response
     */
    public function printPurchaseBill($bill,$cashbook_id)
    {
      // get main invoie info
     $row = DB::table('purchase')
    ->join('supplier', 'purchase.supplier_id', '=', 'supplier.id')
    ->join('branch', 'purchase.branch_id', '=', 'branch.id')
    ->where('purchase.branch_id',$this->branch_id)
    ->where('purchase.cashbook_id',$cashbook_id)
    ->where('purchase.invoice',$bill)
    ->select('purchase.*','supplier.supplier_name','supplier.address AS supplier_address','branch.name','branch.address','branch.mobile')
    ->first();
    // get from purchase product table
     $result = DB::table('purchase_product')
    ->join('product', 'purchase_product.product_id', '=', 'product.id')
    ->where('purchase_product.branch_id',$this->branch_id)
    ->where('purchase_product.cashbook_id',$cashbook_id)
    ->where('purchase_product.invoice_number',$bill)
    ->select('purchase_product.*','product.product_name','product.product_code')
    ->get();
    // setting
    //$setting = DB::table('setting')->where('branch_id',0)->first();
    return view('print.printPurchaseBill')->with('row',$row)->with('result',$result);
    }
    // print pathlogy bill
    public function printPathologyBill($bill,$cashbook_id)
    {
    $row = DB::table('pathology_bill')
    ->join('tbl_patient', 'pathology_bill.patient_id', '=', 'tbl_patient.id')
    ->join('branch', 'pathology_bill.branch_id', '=', 'branch.id')
    ->join('admin', 'pathology_bill.added_id', '=', 'admin.id')
    ->where('pathology_bill.branch_id',$this->branch_id)
    ->where('pathology_bill.cashbook_id',$cashbook_id)
    ->where('pathology_bill.invoice',$bill)
    ->select('pathology_bill.*','tbl_patient.patient_name','tbl_patient.patient_name','tbl_patient.patient_name','tbl_patient.patient_name','tbl_patient.patient_number','tbl_patient.patient_mobile','tbl_patient.patient_age','tbl_patient.patient_sex','tbl_patient.address as patient_address','tbl_patient.c_o_name', 'branch.name','branch.address','branch.mobile','branch.image as logo','admin.name as admin_name')
    ->first(); 
    $result = DB::table('pathology_bill_item')
    ->join('tbl_test', 'pathology_bill_item.test_id', '=', 'tbl_test.id')
    ->where('pathology_bill_item.branch_id',$this->branch_id)
    ->where('pathology_bill_item.cashbook_id',$cashbook_id)
    ->where('pathology_bill_item.invoice_number',$bill)
    ->select('pathology_bill_item.*','tbl_test.test_name')
    ->get();
    // get pathology bill trasnstion info
    $bill_tr_info = DB::table('pathology_bill_transaction')->where('branch_id',$this->branch_id)->where('invoice_number',$bill)->get();
    // get last  transaction no
    $bill_last_tr_no = DB::table('pathology_bill_transaction')->where('branch_id',$this->branch_id)->where('invoice_number',$bill)->orderBy('invoice_tr_id','desc')->limit(1)->first();
    return view('print.printPathologyBill')->with('row',$row)->with('result',$result)->with('bill_tr_info',$bill_tr_info)->with('bill_last_tr_no',$bill_last_tr_no);
    }
    // print a4 pathology bill
    public function printA4PathologyBill($bill,$cashbook_id)
    {
     $row = DB::table('pathology_bill')
    ->join('tbl_patient', 'pathology_bill.patient_id', '=', 'tbl_patient.id')
    ->join('branch', 'pathology_bill.branch_id', '=', 'branch.id')
    ->join('admin', 'pathology_bill.added_id', '=', 'admin.id')
    ->where('pathology_bill.branch_id',$this->branch_id)
    ->where('pathology_bill.cashbook_id',$cashbook_id)
    ->where('pathology_bill.invoice',$bill)
    ->select('pathology_bill.*','tbl_patient.patient_name','tbl_patient.patient_name','tbl_patient.patient_name','tbl_patient.patient_name','tbl_patient.patient_number','tbl_patient.patient_mobile','tbl_patient.patient_age','tbl_patient.patient_sex','tbl_patient.address as patient_address','tbl_patient.c_o_name', 'branch.name','branch.address','branch.mobile','branch.image as logo','admin.name as admin_name')
    ->first(); 
    $result = DB::table('pathology_bill_item')
    ->join('tbl_test', 'pathology_bill_item.test_id', '=', 'tbl_test.id')
    ->where('pathology_bill_item.branch_id',$this->branch_id)
    ->where('pathology_bill_item.cashbook_id',$cashbook_id)
    ->where('pathology_bill_item.invoice_number',$bill)
    ->select('pathology_bill_item.*','tbl_test.test_name')
    ->get();
    // get pathology bill trasnstion info
    $bill_tr_info = DB::table('pathology_bill_transaction')->where('branch_id',$this->branch_id)->where('invoice_number',$bill)->get();
    // get last  transaction no
    $bill_last_tr_no = DB::table('pathology_bill_transaction')->where('branch_id',$this->branch_id)->where('invoice_number',$bill)->orderBy('invoice_tr_id','desc')->limit(1)->first();
    $bill_return_amount = DB::table('pathology_bill_transaction')->where('branch_id',$this->branch_id)->where('invoice_number',$bill)->where('status',2)->get();
    return view('print.printA4PathologyBill')->with('row',$row)->with('result',$result)->with('bill_tr_info',$bill_tr_info)->with('bill_last_tr_no',$bill_last_tr_no)->with('bill_return_amount',$bill_return_amount); 
    }
    // print opd bill
    public function printOpdBill($bill , $opd_bill_id)
    {
    $row = DB::table('opd_bill')
    ->join('tbl_patient', 'opd_bill.patient_id', '=', 'tbl_patient.id')
    ->join('branch', 'opd_bill.branch_id', '=', 'branch.id')
    ->join('admin', 'opd_bill.added_id', '=', 'admin.id')
    ->where('opd_bill.branch_id',$this->branch_id)
    ->where('opd_bill.id',$opd_bill_id)
    ->where('opd_bill.invoice',$bill)
    ->select('opd_bill.*','tbl_patient.patient_name','tbl_patient.patient_name','tbl_patient.patient_name','tbl_patient.patient_name','tbl_patient.patient_number','tbl_patient.patient_mobile','tbl_patient.patient_age','tbl_patient.patient_sex','tbl_patient.address as patient_address','tbl_patient.c_o_name', 'branch.name','branch.address','branch.mobile','branch.image as logo','admin.name as admin_name')
    ->first(); 
      $result = DB::table('opd_bill_item')
    ->join('tbl_opd_fee', 'opd_bill_item.opd_fee_id', '=', 'tbl_opd_fee.id')
    ->join('tbl_opd_category', 'tbl_opd_fee.opd_cat_id', '=', 'tbl_opd_category.id')
    ->where('opd_bill_item.branch_id',$this->branch_id)
    ->where('opd_bill_item.opd_bill_id',$opd_bill_id)
    ->where('opd_bill_item.invoice_number',$bill)
    ->select('opd_bill_item.*','tbl_opd_category.opd_name')
    ->get();
    $bill_tr_info = DB::table('opd_bill_transaction')->where('branch_id',$this->branch_id)->where('invoice_number',$bill)->get();
    $bill_last_tr_no = DB::table('opd_bill_transaction')->where('branch_id',$this->branch_id)->where('invoice_number',$bill)->orderBy('invoice_tr_id','desc')->limit(1)->first();
    return view('print.printOpdBill')->with('row',$row)->with('result',$result)->with('bill_tr_info',$bill_tr_info)->with('bill_last_tr_no',$bill_last_tr_no);
    } 
    // prit a4 opd bill
    public function printA4OpdBill($bill , $opd_bill_id)
    {
    $row = DB::table('opd_bill')
    ->join('tbl_patient', 'opd_bill.patient_id', '=', 'tbl_patient.id')
    ->join('branch', 'opd_bill.branch_id', '=', 'branch.id')
    ->join('admin', 'opd_bill.added_id', '=', 'admin.id')
    ->where('opd_bill.branch_id',$this->branch_id)
    ->where('opd_bill.id',$opd_bill_id)
    ->where('opd_bill.invoice',$bill)
    ->select('opd_bill.*','tbl_patient.patient_name','tbl_patient.patient_name','tbl_patient.patient_name','tbl_patient.patient_name','tbl_patient.patient_number','tbl_patient.patient_mobile','tbl_patient.patient_age','tbl_patient.patient_sex','tbl_patient.address as patient_address','tbl_patient.c_o_name', 'branch.name','branch.address','branch.mobile','branch.image as logo','admin.name as admin_name')
    ->first(); 
      $result = DB::table('opd_bill_item')
    ->join('tbl_opd_fee', 'opd_bill_item.opd_fee_id', '=', 'tbl_opd_fee.id')
    ->join('tbl_opd_category', 'tbl_opd_fee.opd_cat_id', '=', 'tbl_opd_category.id')
    ->where('opd_bill_item.branch_id',$this->branch_id)
    ->where('opd_bill_item.opd_bill_id',$opd_bill_id)
    ->where('opd_bill_item.invoice_number',$bill)
    ->select('opd_bill_item.*','tbl_opd_category.opd_name')
    ->get();
    $bill_tr_info = DB::table('opd_bill_transaction')->where('branch_id',$this->branch_id)->where('invoice_number',$bill)->get();
    $bill_last_tr_no = DB::table('opd_bill_transaction')->where('branch_id',$this->branch_id)->where('invoice_number',$bill)->orderBy('invoice_tr_id','desc')->limit(1)->first();
    $bill_return_amount = DB::table('opd_bill_transaction')->where('branch_id',$this->branch_id)->where('invoice_number',$bill)->where('status',2)->get();
    return view('print.printA4OpdBill')->with('row',$row)->with('result',$result)->with('bill_tr_info',$bill_tr_info)->with('bill_last_tr_no',$bill_last_tr_no)->with('bill_return_amount',$bill_return_amount);

    }
    // print ipd admission fee invoice
    public function printIpdAdmissionInvoice($ipd_admission_id , $bill_no)
    {
    // get ipd information
     $row = DB::table('tbl_ipd_admission')
    ->join('tbl_patient', 'tbl_ipd_admission.patient_id', '=', 'tbl_patient.id')
    ->join('admin', 'tbl_ipd_admission.added_id', '=', 'admin.id')
    ->join('branch', 'tbl_ipd_admission.branch_id', '=', 'branch.id')
    ->where('tbl_ipd_admission.branch_id',$this->branch_id)
    ->where('tbl_ipd_admission.id',$ipd_admission_id)
    ->where('tbl_ipd_admission.invoice',$bill_no)
    ->select('tbl_ipd_admission.*','tbl_patient.patient_name','tbl_patient.patient_number','tbl_patient.patient_mobile','tbl_patient.patient_age','tbl_patient.patient_sex','tbl_patient.address as patient_address','tbl_patient.c_o_name', 'branch.name','branch.address','branch.mobile','branch.image as logo','admin.name as admin_name')
    ->first();
    // payment information form ipd ledger
    $value = DB::table('tbl_ipd_ledger')->where('branch_id',$this->branch_id)->where('ipd_admission_id',$ipd_admission_id)->where('patient_id',$row->patient_id)->where('service_type',1)->where('service_id',$row->id)->where('service_invoice',$bill_no)->first();

    $ipd_booking_query = DB::table('tbl_ipd_cabin_bed_history')->where('branch_id',$this->branch_id)->where('ipd_admission_id',$ipd_admission_id)->where('patient_id',$row->patient_id)->where('invoice_number',$bill_no)->where('status',1)->first();
    $room_type = $ipd_booking_query->room_type;
    if($room_type =='1'){
     // cabin
    $cabin_room = DB::table('tbl_cabin_room')
    ->join('tbl_building', 'tbl_cabin_room.building_id', '=', 'tbl_building.id')
    ->join('tbl_building_floor', 'tbl_cabin_room.floor_id', '=', 'tbl_building_floor.id')
    ->join('tbl_cabin_type', 'tbl_cabin_room.cabin_type_id', '=', 'tbl_cabin_type.id')
    ->join('tbl_ipd_cabin_bed_history', 'tbl_cabin_room.id', '=', 'tbl_ipd_cabin_bed_history.cabin_id')
    ->select('tbl_cabin_room.*','tbl_building.building_name','tbl_building_floor.floor_name','tbl_cabin_type.cabin_type_name')
    ->where('tbl_ipd_cabin_bed_history.branch_id',$this->branch_id)
    ->where('tbl_ipd_cabin_bed_history.ipd_admission_id',$ipd_admission_id)
    ->where('tbl_ipd_cabin_bed_history.patient_id',$row->patient_id)
    ->where('tbl_ipd_cabin_bed_history.invoice_number',$bill_no)
    ->where('tbl_ipd_cabin_bed_history.cabin_id',$ipd_booking_query->cabin_id)
    ->where('tbl_ipd_cabin_bed_history.status',1)
    ->first();
    }elseif($room_type =='2'){
        // ward
    $ward_info =  DB::table('tbl_ward')
    ->join('tbl_building', 'tbl_ward.building_id', '=', 'tbl_building.id')
    ->join('tbl_building_floor', 'tbl_ward.floor_id', '=', 'tbl_building_floor.id')
    ->join('tbl_ipd_cabin_bed_history', 'tbl_ward.id', '=', 'tbl_ipd_cabin_bed_history.ward_id')
    ->select('tbl_ward.*','tbl_building.building_name','tbl_building_floor.floor_name','tbl_ipd_cabin_bed_history.ward_bed_id')
    ->where('tbl_ipd_cabin_bed_history.branch_id',$this->branch_id)
    ->where('tbl_ipd_cabin_bed_history.ipd_admission_id',$ipd_admission_id)
    ->where('tbl_ipd_cabin_bed_history.patient_id',$row->patient_id)
    ->where('tbl_ipd_cabin_bed_history.invoice_number',$bill_no)
    ->where('tbl_ipd_cabin_bed_history.ward_id',$ipd_booking_query->ward_id)
    ->where('tbl_ipd_cabin_bed_history.status',1)
    ->first();
    }
    if($room_type =='1'){
    return view('print.printIpdAdmissionInvoice')->with('row',$row)->with('value',$value)->with('cabin_room',$cabin_room)->with('room_type',$room_type)->with('branch_id',$this->branch_id);
   }elseif($room_type == '2'){
     return view('print.printIpdAdmissionInvoice')->with('row',$row)->with('value',$value)->with('ward_info',$ward_info)->with('room_type',$room_type)->with('branch_id',$this->branch_id);
   }

    }
    // print ipd pathology bill
    public function printIpdPathologyBill($ipd_pathlogy_id , $bill_no , $cashbook_id , $ipd_admission_id)
    {
    $row = DB::table('tbl_ipd_pathology_bill')
    ->join('tbl_patient', 'tbl_ipd_pathology_bill.patient_id', '=', 'tbl_patient.id')
    ->join('branch', 'tbl_ipd_pathology_bill.branch_id', '=', 'branch.id')
    ->join('admin', 'tbl_ipd_pathology_bill.added_id', '=', 'admin.id')
    ->where('tbl_ipd_pathology_bill.branch_id',$this->branch_id)
    ->where('tbl_ipd_pathology_bill.id',$ipd_pathlogy_id)
    ->where('tbl_ipd_pathology_bill.invoice',$bill_no)
    ->where('tbl_ipd_pathology_bill.cashbook_id',$cashbook_id)
    ->where('tbl_ipd_pathology_bill.ipd_admission_id',$ipd_admission_id)
    ->select('tbl_ipd_pathology_bill.*','tbl_patient.patient_name','tbl_patient.patient_number','tbl_patient.patient_mobile','tbl_patient.patient_age','tbl_patient.patient_sex','tbl_patient.address as patient_address','tbl_patient.c_o_name', 'branch.name','branch.address','branch.mobile','branch.image as logo','admin.name as admin_name')
    ->first();

    $result = DB::table('tbl_ipd_pathology_bill_item')
    ->join('tbl_test', 'tbl_ipd_pathology_bill_item.test_id', '=', 'tbl_test.id')
    ->where('tbl_ipd_pathology_bill_item.branch_id',$this->branch_id)
    ->where('tbl_ipd_pathology_bill_item.cashbook_id',$cashbook_id)
    ->where('tbl_ipd_pathology_bill_item.ipd_admission_id',$ipd_admission_id)
    ->where('tbl_ipd_pathology_bill_item.invoice_number',$bill_no)
    ->select('tbl_ipd_pathology_bill_item.*','tbl_test.test_name')
    ->get();

    $ipd_ledger = DB::table('tbl_ipd_ledger')->where('branch_id',$this->branch_id)->where('ipd_admission_id',$ipd_admission_id)->where('patient_id',$row->patient_id)->where('service_type',2)->where('service_id',$row->id)->where('service_invoice',$bill_no)->first();

    $ipd_booking_query = DB::table('tbl_ipd_cabin_bed_history')->where('branch_id',$this->branch_id)->where('ipd_admission_id',$ipd_admission_id)->where('patient_id',$row->patient_id)->where('invoice_number',$row->ipd_invoice_no)->where('status',1)->first();
    $room_type = $ipd_booking_query->room_type;
    if($room_type =='1'){
     // cabin
    $cabin_room = DB::table('tbl_cabin_room')
    ->join('tbl_building', 'tbl_cabin_room.building_id', '=', 'tbl_building.id')
    ->join('tbl_building_floor', 'tbl_cabin_room.floor_id', '=', 'tbl_building_floor.id')
    ->join('tbl_cabin_type', 'tbl_cabin_room.cabin_type_id', '=', 'tbl_cabin_type.id')
    ->join('tbl_ipd_cabin_bed_history', 'tbl_cabin_room.id', '=', 'tbl_ipd_cabin_bed_history.cabin_id')
    ->select('tbl_cabin_room.*','tbl_building.building_name','tbl_building_floor.floor_name','tbl_cabin_type.cabin_type_name')
    ->where('tbl_ipd_cabin_bed_history.branch_id',$this->branch_id)
    ->where('tbl_ipd_cabin_bed_history.ipd_admission_id',$ipd_admission_id)
    ->where('tbl_ipd_cabin_bed_history.patient_id',$row->patient_id)
    ->where('tbl_ipd_cabin_bed_history.invoice_number',$row->ipd_invoice_no)
    ->where('tbl_ipd_cabin_bed_history.cabin_id',$ipd_booking_query->cabin_id)
    ->where('tbl_ipd_cabin_bed_history.status',1)
    ->first();
    }elseif($room_type =='2'){
        // ward
    $ward_info =  DB::table('tbl_ward')
    ->join('tbl_building', 'tbl_ward.building_id', '=', 'tbl_building.id')
    ->join('tbl_building_floor', 'tbl_ward.floor_id', '=', 'tbl_building_floor.id')
    ->join('tbl_ipd_cabin_bed_history', 'tbl_ward.id', '=', 'tbl_ipd_cabin_bed_history.ward_id')
    ->select('tbl_ward.*','tbl_building.building_name','tbl_building_floor.floor_name','tbl_ipd_cabin_bed_history.ward_bed_id')
    ->where('tbl_ipd_cabin_bed_history.branch_id',$this->branch_id)
    ->where('tbl_ipd_cabin_bed_history.ipd_admission_id',$ipd_admission_id)
    ->where('tbl_ipd_cabin_bed_history.patient_id',$row->patient_id)
    ->where('tbl_ipd_cabin_bed_history.invoice_number',$row->ipd_invoice_no)
    ->where('tbl_ipd_cabin_bed_history.ward_id',$ipd_booking_query->ward_id)
    ->where('tbl_ipd_cabin_bed_history.status',1)
    ->first();
    }
    if($room_type == '1'){
    return view('print.printIpdPathologyBill')->with('row',$row)->with('result',$result)->with('ipd_ledger',$ipd_ledger)->with('cabin_room',$cabin_room)->with('room_type',$room_type)->with('branch_id',$this->branch_id);
    }elseif($room_type == '2'){
    return view('print.printIpdPathologyBill')->with('row',$row)->with('result',$result)->with('ipd_ledger',$ipd_ledger)->with('ward_info',$ward_info)->with('room_type',$room_type)->with('branch_id',$this->branch_id);
    }

    }
    // print ipd service bill
    public function printIpdServiceBill ($last_ipd_service_id , $bill_no , $cashbook_id , $ipd_admission_id)
    {
    $row = DB::table('tbl_ipd_service_bill')
    ->join('tbl_patient', 'tbl_ipd_service_bill.patient_id', '=', 'tbl_patient.id')
    ->join('branch', 'tbl_ipd_service_bill.branch_id', '=', 'branch.id')
    ->join('admin', 'tbl_ipd_service_bill.added_id', '=', 'admin.id')
    ->where('tbl_ipd_service_bill.branch_id',$this->branch_id)
    ->where('tbl_ipd_service_bill.id',$last_ipd_service_id)
    ->where('tbl_ipd_service_bill.invoice',$bill_no)
    ->where('tbl_ipd_service_bill.cashbook_id',$cashbook_id)
    ->where('tbl_ipd_service_bill.ipd_admission_id',$ipd_admission_id)
    ->select('tbl_ipd_service_bill.*','tbl_patient.patient_name','tbl_patient.patient_number','tbl_patient.patient_mobile','tbl_patient.patient_age','tbl_patient.patient_sex','tbl_patient.address as patient_address','tbl_patient.c_o_name', 'branch.name','branch.address','branch.mobile','branch.image as logo','admin.name as admin_name')
    ->first();

    $result = DB::table('tbl_ipd_service_bill_item')
    ->join('tbl_ipd_service', 'tbl_ipd_service_bill_item.service_id', '=', 'tbl_ipd_service.id')
    ->where('tbl_ipd_service_bill_item.branch_id',$this->branch_id)
    ->where('tbl_ipd_service_bill_item.cashbook_id',$cashbook_id)
    ->where('tbl_ipd_service_bill_item.ipd_admission_id',$ipd_admission_id)
    ->where('tbl_ipd_service_bill_item.invoice_number',$bill_no)
    ->select('tbl_ipd_service_bill_item.*','tbl_ipd_service.service_name')
    ->get();

  $ipd_ledger = DB::table('tbl_ipd_ledger')->where('branch_id',$this->branch_id)->where('ipd_admission_id',$ipd_admission_id)->where('patient_id',$row->patient_id)->where('service_type',3)->where('service_id',$row->id)->where('service_invoice',$bill_no)->first();

    $ipd_booking_query = DB::table('tbl_ipd_cabin_bed_history')->where('branch_id',$this->branch_id)->where('ipd_admission_id',$ipd_admission_id)->where('patient_id',$row->patient_id)->where('invoice_number',$row->ipd_invoice_no)->where('status',1)->first();
    $room_type = $ipd_booking_query->room_type;
    if($room_type =='1'){
     // cabin
    $cabin_room = DB::table('tbl_cabin_room')
    ->join('tbl_building', 'tbl_cabin_room.building_id', '=', 'tbl_building.id')
    ->join('tbl_building_floor', 'tbl_cabin_room.floor_id', '=', 'tbl_building_floor.id')
    ->join('tbl_cabin_type', 'tbl_cabin_room.cabin_type_id', '=', 'tbl_cabin_type.id')
    ->join('tbl_ipd_cabin_bed_history', 'tbl_cabin_room.id', '=', 'tbl_ipd_cabin_bed_history.cabin_id')
    ->select('tbl_cabin_room.*','tbl_building.building_name','tbl_building_floor.floor_name','tbl_cabin_type.cabin_type_name')
    ->where('tbl_ipd_cabin_bed_history.branch_id',$this->branch_id)
    ->where('tbl_ipd_cabin_bed_history.ipd_admission_id',$ipd_admission_id)
    ->where('tbl_ipd_cabin_bed_history.patient_id',$row->patient_id)
    ->where('tbl_ipd_cabin_bed_history.invoice_number',$row->ipd_invoice_no)
    ->where('tbl_ipd_cabin_bed_history.cabin_id',$ipd_booking_query->cabin_id)
    ->where('tbl_ipd_cabin_bed_history.status',1)
    ->first();
    }elseif($room_type =='2'){
        // ward
    $ward_info =  DB::table('tbl_ward')
    ->join('tbl_building', 'tbl_ward.building_id', '=', 'tbl_building.id')
    ->join('tbl_building_floor', 'tbl_ward.floor_id', '=', 'tbl_building_floor.id')
    ->join('tbl_ipd_cabin_bed_history', 'tbl_ward.id', '=', 'tbl_ipd_cabin_bed_history.ward_id')
    ->select('tbl_ward.*','tbl_building.building_name','tbl_building_floor.floor_name','tbl_ipd_cabin_bed_history.ward_bed_id')
    ->where('tbl_ipd_cabin_bed_history.branch_id',$this->branch_id)
    ->where('tbl_ipd_cabin_bed_history.ipd_admission_id',$ipd_admission_id)
    ->where('tbl_ipd_cabin_bed_history.patient_id',$row->patient_id)
    ->where('tbl_ipd_cabin_bed_history.invoice_number',$row->ipd_invoice_no)
    ->where('tbl_ipd_cabin_bed_history.ward_id',$ipd_booking_query->ward_id)
    ->where('tbl_ipd_cabin_bed_history.status',1)
    ->first();
    }
    if($room_type == '1'){
    return view('print.printIpdServiceBill')->with('row',$row)->with('result',$result)->with('ipd_ledger',$ipd_ledger)->with('cabin_room',$cabin_room)->with('room_type',$room_type)->with('branch_id',$this->branch_id);
    }elseif($room_type == '2'){
    return view('print.printIpdServiceBill')->with('row',$row)->with('result',$result)->with('ipd_ledger',$ipd_ledger)->with('ward_info',$ward_info)->with('room_type',$room_type)->with('branch_id',$this->branch_id);
    }

    }
    // print ipd clear bill
    public function printIpdClearBill($ipd_clear_bill_id ,  $bill_no , $cashbook_id , $ipd_admission_id)
    {
    $row = DB::table('tbl_ipd_clear_bill')
    ->join('tbl_patient', 'tbl_ipd_clear_bill.patient_id', '=', 'tbl_patient.id')
    ->join('branch', 'tbl_ipd_clear_bill.branch_id', '=', 'branch.id')
    ->join('admin', 'tbl_ipd_clear_bill.added_id', '=', 'admin.id')
    ->where('tbl_ipd_clear_bill.branch_id',$this->branch_id)
    ->where('tbl_ipd_clear_bill.id',$ipd_clear_bill_id)
    ->where('tbl_ipd_clear_bill.invoice',$bill_no)
    ->where('tbl_ipd_clear_bill.cashbook_id',$cashbook_id)
    ->where('tbl_ipd_clear_bill.ipd_admission_id',$ipd_admission_id)
    ->select('tbl_ipd_clear_bill.*','tbl_patient.patient_name','tbl_patient.patient_number','tbl_patient.patient_mobile','tbl_patient.patient_age','tbl_patient.patient_sex','tbl_patient.address as patient_address','tbl_patient.c_o_name', 'branch.name','branch.address','branch.mobile','branch.image as logo','admin.name as admin_name')
    ->first();

    $ipd_ledger_info      = DB::table('tbl_ipd_ledger')->where('branch_id',$this->branch_id)->where('ipd_admission_id',$ipd_admission_id)->where('patient_id',$row->patient_id)->whereNotIn('service_type',[6])->orderBy('service_created_at','asc')->get();
    $total_ledger_calculation = DB::table('tbl_ipd_ledger')->where('branch_id',$this->branch_id)->where('ipd_admission_id',$ipd_admission_id)->where('patient_id',$row->patient_id)->orderBy('service_created_at','asc')->get();
    // patient admint info
    $ipd_admission_info = DB::table('tbl_ipd_admission')->where('id',$ipd_admission_id)->where('patient_id',$row->patient_id)->limit(1)->first();

      $ipd_booking_query = DB::table('tbl_ipd_cabin_bed_history')->where('branch_id',$this->branch_id)->where('ipd_admission_id',$ipd_admission_id)->where('patient_id',$row->patient_id)->where('invoice_number',$row->ipd_invoice_no)->where('status',1)->first();
    $room_type = $ipd_booking_query->room_type;
    if($room_type =='1'){
     // cabin
    $cabin_room = DB::table('tbl_cabin_room')
    ->join('tbl_building', 'tbl_cabin_room.building_id', '=', 'tbl_building.id')
    ->join('tbl_building_floor', 'tbl_cabin_room.floor_id', '=', 'tbl_building_floor.id')
    ->join('tbl_cabin_type', 'tbl_cabin_room.cabin_type_id', '=', 'tbl_cabin_type.id')
    ->join('tbl_ipd_cabin_bed_history', 'tbl_cabin_room.id', '=', 'tbl_ipd_cabin_bed_history.cabin_id')
    ->select('tbl_cabin_room.*','tbl_building.building_name','tbl_building_floor.floor_name','tbl_cabin_type.cabin_type_name')
    ->where('tbl_ipd_cabin_bed_history.branch_id',$this->branch_id)
    ->where('tbl_ipd_cabin_bed_history.ipd_admission_id',$ipd_admission_id)
    ->where('tbl_ipd_cabin_bed_history.patient_id',$row->patient_id)
    ->where('tbl_ipd_cabin_bed_history.invoice_number',$row->ipd_invoice_no)
    ->where('tbl_ipd_cabin_bed_history.cabin_id',$ipd_booking_query->cabin_id)
    ->where('tbl_ipd_cabin_bed_history.status',1)
    ->first();
    }elseif($room_type =='2'){
        // ward
    $ward_info =  DB::table('tbl_ward')
    ->join('tbl_building', 'tbl_ward.building_id', '=', 'tbl_building.id')
    ->join('tbl_building_floor', 'tbl_ward.floor_id', '=', 'tbl_building_floor.id')
    ->join('tbl_ipd_cabin_bed_history', 'tbl_ward.id', '=', 'tbl_ipd_cabin_bed_history.ward_id')
    ->select('tbl_ward.*','tbl_building.building_name','tbl_building_floor.floor_name','tbl_ipd_cabin_bed_history.ward_bed_id')
    ->where('tbl_ipd_cabin_bed_history.branch_id',$this->branch_id)
    ->where('tbl_ipd_cabin_bed_history.ipd_admission_id',$ipd_admission_id)
    ->where('tbl_ipd_cabin_bed_history.patient_id',$row->patient_id)
    ->where('tbl_ipd_cabin_bed_history.invoice_number',$row->ipd_invoice_no)
    ->where('tbl_ipd_cabin_bed_history.ward_id',$ipd_booking_query->ward_id)
    ->where('tbl_ipd_cabin_bed_history.status',1)
    ->first();
    }

    if($room_type == '1'){
    return view('print.printIpdClearBill')->with('row',$row)->with('ipd_ledger_info',$ipd_ledger_info)->with('cabin_room',$cabin_room)->with('room_type',$room_type)->with('branch_id',$this->branch_id)->with('total_ledger_calculation',$total_ledger_calculation)->with('ipd_admission_info',$ipd_admission_info);
    }elseif($room_type == '2'){
    return view('print.printIpdClearBill')->with('row',$row)->with('ipd_ledger_info',$ipd_ledger_info)->with('ward_info',$ward_info)->with('room_type',$room_type)->with('branch_id',$this->branch_id)->with('total_ledger_calculation',$total_ledger_calculation)->with('ipd_admission_info',$ipd_admission_info);
    }

    }
    // print ot booking
    public function printOTBookingInvoice($ot_booking_id , $bill_no , $cashbook_id)
    {
     $row = DB::table('tbl_ot_booking')
    ->join('tbl_patient', 'tbl_ot_booking.patient_id', '=', 'tbl_patient.id')
    ->join('branch', 'tbl_ot_booking.branch_id', '=', 'branch.id')
    ->join('admin', 'tbl_ot_booking.added_id', '=', 'admin.id')
    ->where('tbl_ot_booking.branch_id',$this->branch_id)
    ->where('tbl_ot_booking.id',$ot_booking_id)
    ->where('tbl_ot_booking.invoice',$bill_no)
    ->where('tbl_ot_booking.cashbook_id',$cashbook_id)
    ->select('tbl_ot_booking.*','tbl_patient.patient_name','tbl_patient.patient_number','tbl_patient.patient_mobile','tbl_patient.patient_age','tbl_patient.patient_sex','tbl_patient.address as patient_address','tbl_patient.c_o_name', 'branch.name','branch.address','branch.mobile','branch.image as logo','admin.name as admin_name')
    ->first();

    $value = DB::table('tbl_ot_ledger')->where('branch_id',$this->branch_id)->where('ot_booking_id',$ot_booking_id)->where('patient_id',$row->patient_id)->where('service_type',1)->where('service_id',$ot_booking_id)->where('service_invoice',$bill_no)->first();
    $ot_type_query = DB::table('tbl_ot_type')->where('id',$row->ot_type)->limit(1)->first();
    return view('print.printOTBookingInvoice')->with('row',$row)->with('value',$value)->with('branch_id',$this->branch_id)->with('ot_type_query',$ot_type_query);
    }
    // print ot clearence bill
    public function printOTClearenceBill($ot_clear_bill_id , $bill_no , $cashbook_id , $ot_booking_id)
    {
    $row = DB::table('tbl_ot_clear_bill')
    ->join('tbl_patient', 'tbl_ot_clear_bill.patient_id', '=', 'tbl_patient.id')
    ->join('branch', 'tbl_ot_clear_bill.branch_id', '=', 'branch.id')
    ->join('admin', 'tbl_ot_clear_bill.added_id', '=', 'admin.id')
    ->where('tbl_ot_clear_bill.branch_id',$this->branch_id)
    ->where('tbl_ot_clear_bill.id',$ot_clear_bill_id)
    ->where('tbl_ot_clear_bill.invoice',$bill_no)
    ->where('tbl_ot_clear_bill.cashbook_id',$cashbook_id)
    ->where('tbl_ot_clear_bill.ot_booking_id',$ot_booking_id)
    ->select('tbl_ot_clear_bill.*','tbl_patient.patient_name','tbl_patient.patient_number','tbl_patient.patient_mobile','tbl_patient.patient_age','tbl_patient.patient_sex','tbl_patient.address as patient_address','tbl_patient.c_o_name', 'branch.name','branch.address','branch.mobile','branch.image as logo','admin.name as admin_name')
    ->first();
    $ot_ledger_info      = DB::table('tbl_ot_ledger')->where('branch_id',$this->branch_id)->where('ot_booking_id',$ot_booking_id)->where('patient_id',$row->patient_id)->whereNotIn('service_type',[4])->orderBy('service_created_at','asc')->get();
    $total_ot_ledger_calculation = DB::table('tbl_ot_ledger')->where('branch_id',$this->branch_id)->where('ot_booking_id',$ot_booking_id)->where('patient_id',$row->patient_id)->orderBy('service_created_at','asc')->get();
    $ot_booking_info = DB::table('tbl_ot_booking')->where('branch_id',$this->branch_id)->where('id',$ot_booking_id)->where('patient_id',$row->patient_id)->limit(1)->first();

    return view('print.printOTClearenceBill')->with('row',$row)->with('ot_ledger_info',$ot_ledger_info)->with('branch_id',$this->branch_id)->with('total_ot_ledger_calculation',$total_ot_ledger_calculation)->with('ot_booking_info',$ot_booking_info);
    }
    // cashier report print
    public function printCashierPathologyBillReport(Request $request)
    {
      $from_date = trim($request->from_date);
      $to_date   = trim($request->to_date);
      $from      = date('Y-m-d',strtotime($from_date)) ;
      $to        = date('Y-m-d',strtotime($to_date)) ;

     $count = DB::table('pathology_bill')
    ->join('tbl_patient', 'pathology_bill.patient_id', '=', 'tbl_patient.id')
    ->join('admin', 'pathology_bill.added_id', '=', 'admin.id')
    ->select('pathology_bill.*','tbl_patient.patient_number','tbl_patient.patient_name','tbl_patient.patient_number','tbl_patient.patient_mobile','tbl_patient.patient_age','tbl_patient.patient_sex','tbl_patient.address as patient_address','tbl_patient.c_o_name', 'branch.name','branch.address','branch.mobile','branch.image as logo','admin.name as admin_name')
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
    ->join('branch', 'pathology_bill.branch_id', '=', 'branch.id')
    ->select('pathology_bill.*','tbl_patient.patient_number','tbl_patient.patient_name','tbl_patient.patient_number','tbl_patient.patient_mobile','tbl_patient.patient_age','tbl_patient.patient_sex','tbl_patient.address as patient_address','tbl_patient.c_o_name','admin.name as admin_name','branch.name','branch.address','branch.mobile','branch.image as logo')
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

    return view('print.printCashierPathologyBillReport')->with('result',$result)->with('branch_id',$this->branch_id)->with('from_date',$from_date)->with('to_date',$to_date)->with('total_payable_amt',$total_payable_amt)->with('total_discount_amt',$total_discount_amt)->with('total_rebate_amt',$total_rebate_amt)->with('total_payment_amt',$total_payment_amt);

    }
    // print opd bill
    public function printCashierOPDBillReport(Request $request)
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
    ->join('branch', 'opd_bill.branch_id', '=', 'branch.id')
    ->select('opd_bill.*','tbl_patient.patient_number','tbl_patient.patient_name','tbl_patient.patient_number','tbl_patient.patient_mobile','tbl_patient.patient_age','tbl_patient.patient_sex','tbl_patient.address as patient_address','tbl_patient.c_o_name','admin.name as admin_name','branch.name','branch.address','branch.mobile','branch.image as logo')
    ->where('opd_bill.branch_id',$this->branch_id)
    ->whereBetween('opd_bill.bill_date', [$from, $to])
    ->get();
      }else{
      // for individually doctor
      $result = DB::table('opd_bill')
    ->join('tbl_patient', 'opd_bill.patient_id', '=', 'tbl_patient.id')
    ->join('admin', 'opd_bill.added_id', '=', 'admin.id')
    ->join('branch', 'opd_bill.branch_id', '=', 'branch.id')
    ->select('opd_bill.*','tbl_patient.patient_number','tbl_patient.patient_name','tbl_patient.patient_number','tbl_patient.patient_mobile','tbl_patient.patient_age','tbl_patient.patient_sex','tbl_patient.address as patient_address','tbl_patient.c_o_name','admin.name as admin_name','branch.name','branch.address','branch.mobile','branch.image as logo')
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
     return view('print.printCashierOPDBillReport')->with('result',$result)->with('branch_id',$this->branch_id)->with('from_date',$from_date)->with('to_date',$to_date)->with('total_payable_amt',$total_payable_amt)->with('total_discount_amt',$total_discount_amt)->with('total_rebate_amt',$total_rebate_amt)->with('total_payment_amt',$total_payment_amt)->with('doctor',$doctor);

    }
    // prit ipd admssion bill amount
    public function printCashierIpdAdmissionBillReport(Request $request)
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

      return view('print.printCashierIpdAdmissionBillReport')->with('result',$result)->with('branch_id',$this->branch_id)->with('from_date',$from_date)->with('to_date',$to_date)->with('total_payable_amt',$total_payable_amt)->with('total_discount_amt',$total_discount_amt)->with('total_rebate_amt',$total_rebate_amt)->with('total_payment_amt',$total_payment_amt);
    }
    //  cashier ip pathology bill print
    public function printCashierIpdPathologyBillReport(Request $request)
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
      return view('print.printCashierIpdPathologyBillReport')->with('result',$result)->with('branch_id',$this->branch_id)->with('from_date',$from_date)->with('to_date',$to_date)->with('total_payable_amt',$total_payable_amt)->with('total_discount_amt',$total_discount_amt)->with('total_rebate_amt',$total_rebate_amt)->with('total_payment_amt',$total_payment_amt);

    }
    public function printCashierIpdServiceBillReport(Request $request)
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
      return view('print.printCashierIpdServiceBillReport')->with('result',$result)->with('branch_id',$this->branch_id)->with('from_date',$from_date)->with('to_date',$to_date)->with('total_payable_amt',$total_payable_amt)->with('total_discount_amt',$total_discount_amt)->with('total_rebate_amt',$total_rebate_amt)->with('total_payment_amt',$total_payment_amt);

    }
    // print cashier ipd clearnce bill
    public function printCashierIpdClearanceBillReport(Request $request)
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
      return view('print.printCashierIpdClearanceBillReport')->with('result',$result)->with('branch_id',$this->branch_id)->with('from_date',$from_date)->with('to_date',$to_date)->with('total_payable_amt',$total_payable_amt)->with('total_discount_amt',$total_discount_amt)->with('total_rebate_amt',$total_rebate_amt)->with('total_payment_amt',$total_payment_amt);
    }
    // print ot cashier bill
    public function printCashierOTBookingBillReport(Request $request)
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
      return view('print.printCashierOTBookingBillReport')->with('result',$result)->with('branch_id',$this->branch_id)->with('from_date',$from_date)->with('to_date',$to_date)->with('total_payable_amt',$total_payable_amt)->with('total_discount_amt',$total_discount_amt)->with('total_rebate_amt',$total_rebate_amt)->with('total_payment_amt',$total_payment_amt)->with('ot_type',$ot_type);
    }
    // print ot clearnce bill report
    public function printCashierOTClearanceBillReport(Request $request)
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
    return view('print.printCashierOTClearanceBillReport')->with('result',$result)->with('branch_id',$this->branch_id)->with('from_date',$from_date)->with('to_date',$to_date)->with('total_payable_amt',$total_payable_amt)->with('total_discount_amt',$total_discount_amt)->with('total_rebate_amt',$total_rebate_amt)->with('total_payment_amt',$total_payment_amt)->with('total_advance_payment',$total_advance_payment);
    }
    // print  cashier cash transfer report
    public function printCashierCashTransferReport(Request $request)
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
      $value1 = DB::table('branch')->where('id',$this->branch_id)->limit(1)->first(); 
      return view('print.printCashierCashTransferReport')->with('result',$result)->with('transfer_type',$transfer_type)->with('from_date',$from_date)->with('to_date',$to_date)->with('value1',$value1);
    }
    // print manager purchase report
    public function printManagerPurchaseReport(Request $request)
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
     return view('print.printManagerPurchaseReport')->with('result',$result)->with('supplier',$supplier)->with('from_date',$from_date)->with('to_date',$to_date);
    }
    // print supplier payment report
    public function printManagerSupplierPaymentReport(Request $request)
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
    ->select('payment.*','supplier.supplier_name','supplier.supplier_name','supplier.mobile as supplier_mobile','supplier.address as supplier_address','admin.name as admin_name','branch.name','branch.address','branch.mobile','branch.image as logo')
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
    ->select('payment.*','supplier.supplier_name','supplier.supplier_name','supplier.mobile as supplier_mobile','supplier.address as supplier_address','admin.name as admin_name','branch.name','branch.address','branch.mobile','branch.image as logo')
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
    ->select('payment.*','supplier.supplier_name','supplier.supplier_name','supplier.mobile as supplier_mobile','supplier.address as supplier_address','admin.name as admin_name','branch.name','branch.address','branch.mobile','branch.image as logo')
    ->where('payment.branch_id',$this->branch_id)
    ->whereBetween('payment.created_at', [$from, $to])
    ->get();
    }else{
    // individual supplier purchase
    $result      = DB::table('payment')
    ->join('supplier', 'payment.supplier_id', '=', 'supplier.id')
    ->join('admin', 'payment.added_id', '=', 'admin.id')
    ->join('branch', 'payment.branch_id', '=', 'branch.id')
    ->select('payment.*','supplier.supplier_name','supplier.supplier_name','supplier.mobile as supplier_mobile','supplier.address as supplier_address','admin.name as admin_name','branch.name','branch.address','branch.mobile','branch.image as logo')
    ->where('payment.branch_id',$this->branch_id)
    ->where('payment.supplier_id',$supplier)
    ->whereBetween('payment.created_at', [$from, $to])
    ->get();
    }
     return view('print.printManagerSupplierPaymentReport')->with('result',$result)->with('supplier',$supplier)->with('from_date',$from_date)->with('to_date',$to_date);
    }
  // print manager pc payment report
    public function printManagerPCPaymentReport(Request $request)
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
    return view('print.printManagerPCPaymentReport')->with('result',$result)->with('pc',$pc)->with('from_date',$from_date)->with('to_date',$to_date);
    }
    // print manager expense report
    public function printManagerExpenseReport(Request $request)
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
    return view('print.printManagerExpenseReport')->with('result',$result)->with('expense',$expense)->with('from_date',$from_date)->with('to_date',$to_date);
    }
    // print manager bank statement
    public function printManagerBankStatementReport(Request $request)
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
    return view('print.printManagerBankStatementReport')->with('result',$result)->with('bank',$bank)->with('from_date',$from_date)->with('to_date',$to_date);
    }
    // print manager cash to bank report
    public function printManagerCashToBankReport(Request $request)
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
    return view('print.printManagerCashToBankReport')->with('result',$result)->with('bank',$bank)->with('from_date',$from_date)->with('to_date',$to_date);
    }
    // print manager bank to cash report
    public function printManagerBankToCashReport(Request $request)
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
    return view('print.printManagerBankToCashReport')->with('result',$result)->with('bank',$bank)->with('from_date',$from_date)->with('to_date',$to_date); 
    }
    // print manager cash receive amount
    public function printManagerCashReceiveAmtReport(Request $request)
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
      $value1 = DB::table('branch')->where('id',$this->branch_id)->limit(1)->first(); 
      return view('print.printManagerCashReceiveAmtReport')->with('result',$result)->with('transfer_type',$transfer_type)->with('from_date',$from_date)->with('to_date',$to_date)->with('value1',$value1);  
    }
    // print manager income statement
    public function printManagerIncomeStatement(Request $request)
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
     return view('print.printManagerIncomeStatement')->with('result',$result)->with('from_date',$from_date)->with('to_date',$to_date) ;
    }
}
