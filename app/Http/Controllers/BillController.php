<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests;
use DB;
use Session;

class BillController extends Controller
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
	// collect bill
	public function collectBill()
	{
		return view('bill.collectBill');
	}
	// pathology bill create
	public function pathologyBillCreate()
	{
		$result  = DB::table('tbl_test')->where('status',0)->get();
		$patient = DB::table('tbl_patient')->where('branch_id',$this->branch_id)->orderBy('id','desc')->get();
		$doctor  = DB::table('admin')->where('branch_id',$this->branch_id)->where('type',4)->where('status',1)->get();
		$pc      = DB::table('tbl_pc')->where('branch_id',$this->branch_id)->where('status',1)->get();
		return view('bill.pathologyBillCreate')->with('result',$result)->with('patient',$patient)->with('doctor',$doctor)->with('pc',$pc);
	}
	// create pathology bill
	public function createPathologyBill(Request $request)
	{
     $branch                = $this->branch_id ;
     $bill_date             = trim($request->bill_date);
     $billDate              = date('Y-m-d',strtotime($bill_date)) ;
     $report_date_and_time  = trim($request->report_date_and_time);
     $reportDate            = date('Y-m-d',strtotime($report_date_and_time)) ;
     $patient_id            = trim($request->patient_id);
     $doctor_id             = trim($request->doctor_id);
     $pc_id                 = trim($request->pc_id);
     $patient_name          = trim($request->patient_name);
     $mobile_number         = trim($request->mobile_number);
     $age                   = trim($request->age);
     $sex                   = trim($request->sex);
     $total_amount          = trim($request->total_amount);
     $total_discount        = trim($request->total_discount);
     $total_paid            = trim($request->total_paid);
     $pc_amount             = trim($request->pc_amount);
     $care_of               = trim($request->care_of);
     $address               = trim($request->address);
     $array_invoice_data    = $request->arr; 
     $invoice_datas         = json_decode($array_invoice_data);
     $payableAmount         = $total_amount - $total_discount ;
     $total_due             = $payableAmount - $total_paid ;

     if($total_due > 0){
      $due_status = 2 ;
     }else{
       $due_status = 1 ;
     }
    #---------------------- DATE VALIDATION----------------------#
     if($billDate > $this->rcdate){
      echo "d1";
      exit();
     }
     #--------------------- END DATE VALIDATIN--------------------#
     #--------------------- AMOUNT BIG THAN TOTAL AMOUNT ------------------------#
    if($payableAmount < $total_paid){
       // paid amount not big than total amount
       echo "p2";
       exit();
     }
     #-------------------END AMOUNT BIG THAN TOTAL AMOUNT------------------------#
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
     #----------------------------------- INSERT INTO CASHBOOK -----------------------#
     if($total_discount > 0){
      $purpose = "Pathology Bill Create With Discount";
     }else{
      $purpose = "Pathology Bill Create";
     } 
        // status = 8
        // tr status = 1 by cash transaction
        $data_cashbook                        = array();
        $data_cashbook['overall_branch_id']   = $this->branch_id ;
        $data_cashbook['branch_id']           = $this->branch_id ;
        $data_cashbook['admin_id']            = $this->loged_id  ;
        $data_cashbook['admin_type']          = 3 ;
        $data_cashbook['earn']                = $total_paid + $total_discount ;
        $data_cashbook['cost']                = $total_discount ;
        $data_cashbook['profit_earn']         = $total_amount ;
        $data_cashbook['profit_cost']         = $total_discount ;
        $data_cashbook['status']              = 8 ;
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
     $branch_invoice_count = DB::table('pathology_bill')->where('branch_id',$branch)->orderBy('invoice','desc')->count();
     if($branch_invoice_count == '0'){
      $branch_invoice_number = 1 ;
     }else{
      $branch_invoice_query = DB::table('pathology_bill')->where('branch_id',$branch)->orderBy('invoice','desc')->limit(1)->first();
      $branch_invoice_number = $branch_invoice_query->invoice + 1 ;
     }
    #-------------------- get year invoice------------------------------------#
    $branch_year_invoice_count = DB::table('pathology_bill')->where('year',$this->current_year)->where('branch_id',$branch)->orderBy('year_invoice','desc')->count();
     if($branch_year_invoice_count == '0'){
      $branch_invoice_year_number = 1 ;
     }else{
      $branch_year_invoice_query = DB::table('pathology_bill')->where('year',$this->current_year)->where('branch_id',$branch)->orderBy('year_invoice','desc')->limit(1)->first();
      $branch_invoice_year_number = $branch_year_invoice_query->year_invoice + 1 ;
     }
     #--------------------- daily invoice -------------------------------------#
      $branch_daily_invoice_count = DB::table('pathology_bill')->where('bill_date',$this->rcdate)->where('branch_id',$branch)->orderBy('daily_invoice','desc')->count();
     if($branch_daily_invoice_count == '0'){
      $branch_daily_invoice_number = 1 ;
     }else{
      $branch_daily_invoice_query = DB::table('pathology_bill')->where('bill_date',$this->rcdate)->where('branch_id',$branch)->orderBy('daily_invoice','desc')->limit(1)->first();
      $branch_daily_invoice_number = $branch_daily_invoice_query->daily_invoice + 1 ;
     }
    #------------------ END GET INVIOCE NUMBER ----------------------------------#

    #---------------------------- INSERT INTO PATHOLOGY BILL TABLE---------------#
    $data_pathology_bill_insert                   = array();
    $data_pathology_bill_insert['cashbook_id']    = $last_cashbook_id ;
    $data_pathology_bill_insert['year']           = $this->current_year;
    $data_pathology_bill_insert['invoice']        = $branch_invoice_number;
    $data_pathology_bill_insert['year_invoice']   = $branch_invoice_year_number;
    $data_pathology_bill_insert['daily_invoice']  = $branch_daily_invoice_number ;
    $data_pathology_bill_insert['branch_id']      = $branch ;
    $data_pathology_bill_insert['doctor_id']      = $doctor_id ;
    $data_pathology_bill_insert['pc_id']          = $pc_id ;
    $data_pathology_bill_insert['patient_id']     = $patient_primary_id_is;
    $data_pathology_bill_insert['due_status']     = $due_status ;
    $data_pathology_bill_insert['purpose']        = 'Pathology Bill Create';   
    $data_pathology_bill_insert['report_date']      = $reportDate ;
    $data_pathology_bill_insert['bill_time']        = $this->current_time ;
    $data_pathology_bill_insert['bill_date']        = $billDate ;
    $data_pathology_bill_insert['added_id']         = $this->loged_id;
    $data_pathology_bill_insert['created_at']       = $this->rcdate ;
    DB::table('pathology_bill')->insert($data_pathology_bill_insert);
   #--------------------------- END INSERT INTO PATHYOLOGY BILL TABLE-------------#
   #--------- CREATE THE BILL ITEAM (INSERT PATHLOGY BILL ITEAM TABEL)------#
    foreach ($invoice_datas as $product_info) {
    $test_id            = $product_info[0]; 
    $sale_price         = $product_info[3];
    $sub_quantity       = $product_info[1];
    $sub_total_price    = $product_info[4];

     $data_pathology_bill_item_insert                         = array();
     $data_pathology_bill_item_insert['cashbook_id']          = $last_cashbook_id ;
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
     DB::table('pathology_bill_item')->insert($data_pathology_bill_item_insert);
     }
     // invoice tr no 1
     $data_pathology_bill_tr_insert                           = array();
     $data_pathology_bill_tr_insert['cashbook_id']            = $last_cashbook_id ;
     $data_pathology_bill_tr_insert['branch_id']              = $branch ;
     $data_pathology_bill_tr_insert['invoice_number']         = $branch_invoice_number;
     $data_pathology_bill_tr_insert['year_invoice_number']    = $branch_invoice_year_number;
     $data_pathology_bill_tr_insert['daily_invoice_number']   = $branch_daily_invoice_number;
     $data_pathology_bill_tr_insert['invoice_tr_id']          = 1;
     $data_pathology_bill_tr_insert['total_payable']          = $total_amount ;
     $data_pathology_bill_tr_insert['total_payment']          = $total_paid ;
     $data_pathology_bill_tr_insert['total_discount']         = $total_discount ;
     $data_pathology_bill_tr_insert['added_id']               = $this->loged_id;
     $data_pathology_bill_tr_insert['tr_date']                = $billDate ;
     $data_pathology_bill_tr_insert['created_time']           = $this->current_time ;
     $data_pathology_bill_tr_insert['created_at']             = $this->rcdate;
     DB::table('pathology_bill_transaction')->insert($data_pathology_bill_tr_insert);

     if($pc_id != '0'){
      // insert the data into pc ledger
      $data_pc_ledger_insert                      = array();
      $data_pc_ledger_insert['cashbook_id']       = $last_cashbook_id ;
      $data_pc_ledger_insert['branch_id']         = $branch ;
      $data_pc_ledger_insert['invoice']           = $branch_invoice_number ;
      $data_pc_ledger_insert['year_invoice']      = $branch_invoice_year_number;
      $data_pc_ledger_insert['daily_invoice_number'] = $branch_daily_invoice_number;
      $data_pc_ledger_insert['invoice_type']      = 1;
      $data_pc_ledger_insert['pc_id']             = $pc_id ;
      $data_pc_ledger_insert['payable_amount']    = $pc_amount ;
      $data_pc_ledger_insert['status']            = 1 ; 
      $data_pc_ledger_insert['purpose']           = 'Pathology Bill Create';
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
      $now_pettycash_amt     = $current_pettycash_amt + $total_paid ;
      // update pettycash amount
      $data_update_pettycash                      = array();
      $data_update_pettycash['pettycash_amount']  = $now_pettycash_amt; 
      DB::table('pettycash')->where('branch_id',$branch)->where('type',3)->update($data_update_pettycash);
     #-------------------------------- end incress pettycash amount---------------------------#
     echo $branch_invoice_number.'/'.$last_cashbook_id ;
	}
  // manage pathology bill
  public function managePathlogyBill()
  {
     $result = DB::table('pathology_bill')
    ->join('tbl_patient', 'pathology_bill.patient_id', '=', 'tbl_patient.id')
    ->join('branch', 'pathology_bill.branch_id', '=', 'branch.id')
    ->join('admin', 'pathology_bill.added_id', '=', 'admin.id')
    ->where('pathology_bill.branch_id',$this->branch_id)
    ->select('pathology_bill.*','tbl_patient.patient_number','tbl_patient.patient_name','tbl_patient.patient_number','tbl_patient.patient_mobile','tbl_patient.patient_age','tbl_patient.patient_sex','tbl_patient.address as patient_address','tbl_patient.c_o_name', 'branch.name','branch.address','branch.mobile','branch.image as logo','admin.name as admin_name')
    ->orderBy('pathology_bill.id','desc')
    ->get();
    return view('bill.managePathlogyBill')->with('result',$result)->with('branch_id',$this->branch_id);
  }
  // opd bill create
  public function opdBillCreate()
  {
    $result  = DB::table('tbl_test')->where('status',0)->get();
    $patient = DB::table('tbl_patient')->where('branch_id',$this->branch_id)->orderBy('id','desc')->get();
    $doctor  = DB::table('admin')->where('branch_id',$this->branch_id)->where('type',4)->where('status',1)->get();
    $pc      = DB::table('tbl_pc')->where('branch_id',$this->branch_id)->where('status',1)->get();
    return view('bill.opdBillCreate')->with('result',$result)->with('patient',$patient)->with('doctor',$doctor)->with('pc',$pc);
  }
  // create opd bill
  public function createOpdBill(Request $request)
  {
     $branch                = $this->branch_id ;
     $bill_date             = trim($request->bill_date);
     $billDate              = date('Y-m-d',strtotime($bill_date)) ;
     $patient_id            = trim($request->patient_id);
     $doctor_id             = trim($request->doctor_id);
     $patient_name          = trim($request->patient_name);
     $mobile_number         = trim($request->mobile_number);
     $age                   = trim($request->age);
     $sex                   = trim($request->sex);
     $total_amount          = trim($request->total_amount);
     $total_discount        = trim($request->total_discount);
     $total_paid            = trim($request->total_paid);
     $care_of               = trim($request->care_of);
     $address               = trim($request->address);
     $array_invoice_data    = $request->arr; 
     $invoice_datas         = json_decode($array_invoice_data);
     $payableAmount         = $total_amount - $total_discount ;
     $total_due             = $payableAmount - $total_paid ;

     $invoice_iteam = count($invoice_datas);
      if($invoice_iteam > 1){
        echo "p3";
        exit();
      }

     if($total_due > 0){
      $due_status = 2 ;
     }else{
       $due_status = 1 ;
     }
    #---------------------- DATE VALIDATION----------------------#
     if($billDate > $this->rcdate){
      echo "d1";
      exit();
     }
     #--------------------- END DATE VALIDATIN--------------------#
     #--------------------- AMOUNT BIG THAN TOTAL AMOUNT ------------------------#
    if($payableAmount < $total_paid){
       // paid amount not big than total amount
       echo "p2";
       exit();
     }
     #-------------------END AMOUNT BIG THAN TOTAL AMOUNT------------------------#
     #------------------ GET OPD STATUS -----------------------------------------#
     $opd_setting_status = DB::table('tbl_opd_setting')->where('branch_id',$this->branch_id)->first();
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
      $data_patient_insert                   = array();
      $data_patient_insert['branch_id']      = $branch ;
      $data_patient_insert['year']           = $this->current_year ;
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
     if($total_discount > 0){
      $purpose = "OPD Bill Create With Discount";
     }else{
      $purpose = "OPD Bill Create";
     }

     if($opd_setting_status->current_status == '2'){
        // only get taka doctor
       // tr status = 1 by cash transaction
        $data_cashbook                        = array();
        $data_cashbook['overall_branch_id']   = $this->branch_id ;
        $data_cashbook['branch_id']           = $this->branch_id ;
        $data_cashbook['admin_id']            = $this->loged_id  ;
        $data_cashbook['admin_type']          = 3 ;
        $data_cashbook['earn']                = $total_paid + $total_discount ;
        $data_cashbook['cost']                = $total_discount ;
        $data_cashbook['profit_earn']         = $total_amount ;
        $data_cashbook['profit_cost']         = $total_discount ;
        $data_cashbook['status']              = 9 ;
        $data_cashbook['tr_status']           = 1 ;
        $data_cashbook['purpose']             = $purpose;
        $data_cashbook['added_id']            = $this->loged_id;
        $data_cashbook['created_time']        = $this->current_time;
        $data_cashbook['created_at']          = $billDate;
        $data_cashbook['on_created_at']       = $this->rcdate;
        DB::table('cashbook')->insert($data_cashbook);
      #--------------------- GET LAST CASH BOOK ID  -----------------------------------#
       $last_cashbook_id_query = DB::table('cashbook')->orderBy('id','desc')->limit(1)->first();
       $last_cashbook_id       = $last_cashbook_id_query->id ; 
    #-------------------- GET LAST CASH BOOK ID --------------------------------------#
     }

    #------------------- GET INVOICE NUMBER-------------------------------------------#
    // branch wise invoice
     $branch_invoice_count = DB::table('opd_bill')->where('branch_id',$branch)->orderBy('invoice','desc')->count();
     if($branch_invoice_count == '0'){
      $branch_invoice_number = 1 ;
     }else{
      $branch_invoice_query = DB::table('opd_bill')->where('branch_id',$branch)->orderBy('invoice','desc')->limit(1)->first();
      $branch_invoice_number = $branch_invoice_query->invoice + 1 ;
     }
    #-------------------- get year invoice------------------------------------#
    $branch_year_invoice_count = DB::table('opd_bill')->where('year',$this->current_year)->where('branch_id',$branch)->orderBy('year_invoice','desc')->count();
     if($branch_year_invoice_count == '0'){
      $branch_invoice_year_number = 1 ;
     }else{
      $branch_year_invoice_query = DB::table('opd_bill')->where('year',$this->current_year)->where('branch_id',$branch)->orderBy('year_invoice','desc')->limit(1)->first();
      $branch_invoice_year_number = $branch_year_invoice_query->year_invoice + 1 ;
     }
     #--------------------- daily invoice -------------------------------------#
      $branch_daily_invoice_count = DB::table('opd_bill')->where('bill_date',$this->rcdate)->where('branch_id',$branch)->orderBy('daily_invoice','desc')->count();
     if($branch_daily_invoice_count == '0'){
      $branch_daily_invoice_number = 1 ;
     }else{
      $branch_daily_invoice_query = DB::table('opd_bill')->where('bill_date',$this->rcdate)->where('branch_id',$branch)->orderBy('daily_invoice','desc')->limit(1)->first();
      $branch_daily_invoice_number = $branch_daily_invoice_query->daily_invoice + 1 ;
     }
    #------------------ END GET INVIOCE NUMBER ----------------------------------#
     if($opd_setting_status->current_status == '2'){
      $cashbook_last_id = $last_cashbook_id ;
     }else{
      $cashbook_last_id = 0 ;
     }
    #---------------------------- INSERT INTO PATHOLOGY BILL TABLE---------------#
    $data_pathology_bill_insert                   = array();
    $data_pathology_bill_insert['cashbook_id']    = $cashbook_last_id ;
    $data_pathology_bill_insert['year']           = $this->current_year;
    $data_pathology_bill_insert['invoice']        = $branch_invoice_number;
    $data_pathology_bill_insert['year_invoice']   = $branch_invoice_year_number;
    $data_pathology_bill_insert['daily_invoice']  = $branch_daily_invoice_number ;
    $data_pathology_bill_insert['branch_id']      = $branch ;
    $data_pathology_bill_insert['doctor_id']      = $doctor_id ;
    $data_pathology_bill_insert['patient_id']     = $patient_primary_id_is;
    $data_pathology_bill_insert['due_status']     = $due_status ;
    $data_pathology_bill_insert['opd_status']     = $opd_setting_status->current_status ;
    $data_pathology_bill_insert['purpose']        = 'OPD Bill Create';   
    $data_pathology_bill_insert['bill_time']        = $this->current_time ;
    $data_pathology_bill_insert['bill_date']        = $billDate ;
    $data_pathology_bill_insert['added_id']         = $this->loged_id;
    $data_pathology_bill_insert['created_at']       = $this->rcdate ;
    DB::table('opd_bill')->insert($data_pathology_bill_insert);
   #--------------------------- END INSERT INTO PATHYOLOGY BILL TABLE-------------#
   // get last opd_bill id
   $last_opd_bill_query = DB::table('opd_bill')->where('branch_id',$this->branch_id)->orderBy('id','desc')->limit(1)->first();
   $last_opd_bill_id    = $last_opd_bill_query->id ; 

   #--------- CREATE THE BILL ITEAM (INSERT PATHLOGY BILL ITEAM TABEL)------#
    foreach ($invoice_datas as $product_info) {
    $category_id        = $product_info[0]; 
    $sale_price         = $product_info[3];
    $sub_quantity       = $product_info[1];
    $sub_total_price    = $product_info[4];

     $data_pathology_bill_item_insert                         = array();
     $data_pathology_bill_item_insert['cashbook_id']          = $cashbook_last_id ;
     $data_pathology_bill_item_insert['opd_bill_id']          = $last_opd_bill_id ;
     $data_pathology_bill_item_insert['invoice_number']       = $branch_invoice_number;
     $data_pathology_bill_item_insert['year_invoice_number']  = $branch_invoice_year_number;
     $data_pathology_bill_item_insert['daily_invoice_number'] = $branch_daily_invoice_number;
     $data_pathology_bill_item_insert['branch_id']            = $branch;
     $data_pathology_bill_item_insert['opd_fee_id']           = $category_id ;
     $data_pathology_bill_item_insert['opd_fee']             = $sale_price;
     $data_pathology_bill_item_insert['total_quantity']       = $sub_quantity;
     $data_pathology_bill_item_insert['total_price']          = $sub_total_price ;
     $data_pathology_bill_item_insert['opd_status']           = $opd_setting_status->current_status ;
     $data_pathology_bill_item_insert['bill_date']            = $billDate ;
     $data_pathology_bill_item_insert['added_id']             = $this->loged_id;
     $data_pathology_bill_item_insert['created_time']         = $this->current_time ;
     $data_pathology_bill_item_insert['created_at']           = $this->rcdate;
     DB::table('opd_bill_item')->insert($data_pathology_bill_item_insert);
     }
     // invoice tr no 1
     $data_pathology_bill_tr_insert                           = array();
     $data_pathology_bill_tr_insert['cashbook_id']            = $cashbook_last_id ;
     $data_pathology_bill_tr_insert['opd_bill_id']            = $last_opd_bill_id ;
     $data_pathology_bill_tr_insert['branch_id']              = $branch ;
     $data_pathology_bill_tr_insert['invoice_number']         = $branch_invoice_number;
     $data_pathology_bill_tr_insert['year_invoice_number']    = $branch_invoice_year_number;
     $data_pathology_bill_tr_insert['daily_invoice_number']   = $branch_daily_invoice_number;
     $data_pathology_bill_tr_insert['invoice_tr_id']          = 1;
     $data_pathology_bill_tr_insert['total_payable']          = $total_amount ;
     $data_pathology_bill_tr_insert['total_payment']          = $total_paid ;
     $data_pathology_bill_tr_insert['total_discount']         = $total_discount ;
     $data_pathology_bill_tr_insert['added_id']               = $this->loged_id;
     $data_pathology_bill_tr_insert['tr_date']                = $billDate ;
     $data_pathology_bill_tr_insert['created_time']           = $this->current_time ;
     $data_pathology_bill_tr_insert['created_at']             = $this->rcdate;
     DB::table('opd_bill_transaction')->insert($data_pathology_bill_tr_insert);
     #-------------------------------- incress pettycash amount ------------------------------#
      if($opd_setting_status->current_status == '2'){
      $pettycash_amount = DB::table('pettycash')->where('branch_id',$branch)->where('type',3)->limit(1)->first();
      $current_pettycash_amt = $pettycash_amount->pettycash_amount ;
      $now_pettycash_amt     = $current_pettycash_amt + $total_paid ;
      // update pettycash amount
      $data_update_pettycash                      = array();
      $data_update_pettycash['pettycash_amount']  = $now_pettycash_amt; 
      DB::table('pettycash')->where('branch_id',$branch)->where('type',3)->update($data_update_pettycash);
     }
     #-------------------------------- end incress pettycash amount---------------------------#
     echo $branch_invoice_number.'/'.$last_opd_bill_id ;
  }
  // manage opd bill
  public function manageOpdBill()
  {
     $result = DB::table('opd_bill')
    ->join('tbl_patient', 'opd_bill.patient_id', '=', 'tbl_patient.id')
    ->join('branch', 'opd_bill.branch_id', '=', 'branch.id')
    ->join('admin', 'opd_bill.added_id', '=', 'admin.id')
    ->where('opd_bill.branch_id',$this->branch_id)
    ->select('opd_bill.*','tbl_patient.patient_number','tbl_patient.patient_name','tbl_patient.patient_mobile','tbl_patient.patient_age','tbl_patient.patient_sex','tbl_patient.address as patient_address','tbl_patient.c_o_name', 'branch.name','branch.address','branch.mobile','branch.image as logo','admin.name as admin_name')
    ->orderBy('opd_bill.id','desc')
    ->get();
    return view('bill.manageOpdBill')->with('result',$result)->with('branch_id',$this->branch_id);
  }

}
