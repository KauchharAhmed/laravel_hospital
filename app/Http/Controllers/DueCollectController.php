<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests;
use DB;
use Session;

class DueCollectController extends Controller
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
	// pathology bill due collection
	public function pathlogyBillDueCollect()
	{
		// with invoice which due
		$due_invoice = DB::table('pathology_bill')->where('branch_id',$this->branch_id)->where('due_status',2)->orderBy('invoice','asc')->get();
		return view('due_collect.pathlogyBillDueCollect')->with('due_invoice',$due_invoice);
	}
	// get invoice info
	public function getInvoiceInfo(Request $request)
	{
		$invoice = trim($request->invoice);
		// get patient id by invoice
		$patient_query = DB::table('pathology_bill')->where('branch_id',$this->branch_id)->where('invoice',$invoice)->first();
		$patient_id    = $patient_query->patient_id ;
		$patient_info  = DB::table('tbl_patient')->where('branch_id',$this->branch_id)->where('id',$patient_id)->first();
		echo  "Patient Id : ".$patient_info->patient_number." Name : ".$patient_info->patient_name." Mobile : ".$patient_info->patient_mobile ;
	}
	// get invoice calculation
	public function getInvoiceCalculation(Request $request)
	{
	  $invoice = trim($request->invoice);
     // get patient id by invoice
	  $bill_tr_info = DB::table('pathology_bill_transaction')->where('branch_id',$this->branch_id)->where('invoice_number',$invoice)->get();
	       $total_payable  = 0 ;
           $total_payment  = 0 ;
           $total_discount = 0 ;
           $total_rebate   = 0 ;
            foreach ($bill_tr_info as $bill_tr_info_value) {
            	$total_payable  = $total_payable + $bill_tr_info_value->total_payable ;
            	$total_payment  = $total_payment + $bill_tr_info_value->total_payment ;
            	$total_discount = $total_discount + $bill_tr_info_value->total_discount ;
            	$total_rebate   = $total_rebate + $bill_tr_info_value->total_rebate ;	
            }
                $all_discount_rebate = $total_discount + $total_rebate ;
                $net_payable_is      = $total_payable - $all_discount_rebate ;
                $due_amount          = $net_payable_is - $total_payment ;
                echo $total_payable.'/'.$due_amount ;
	}
	// patology due colllection
	public function pathologyDueCollectInfo(Request $request)
	{
     $this->validate($request, [
    'invoice'             => 'required',
    'patient_info'        => 'required',
    'total_amount'        => 'required',
    'total_due'           => 'required',
    'due_payment'         => 'required',
    'confirm_due_payment' => 'required',
    'rebate'         	    => 'required',
    'tr_date'         	  => 'required',
    ]);
     $invoice                  = trim($request->invoice);
     $due_payment              = trim($request->due_payment);
     $confirm_due_payment      = trim($request->confirm_due_payment);
     $rebate          		     = trim($request->rebate);
     $confirm_bank_balance     = trim($request->confirm_bank_balance);
     $remarks                  = trim($request->remarks);
     $tr_date                  = trim($request->tr_date);
     $trDate                   = date('Y-m-d',strtotime($tr_date)) ;
     $bill_tr_info = DB::table('pathology_bill_transaction')->where('branch_id',$this->branch_id)->where('invoice_number',$invoice)->get();
	       $total_payable  = 0 ;
           $total_payment  = 0 ;
           $total_discount = 0 ;
           $total_rebate   = 0 ;
            foreach ($bill_tr_info as $bill_tr_info_value) {
            	$total_payable  = $total_payable + $bill_tr_info_value->total_payable ;
            	$total_payment  = $total_payment + $bill_tr_info_value->total_payment ;
            	$total_discount = $total_discount + $bill_tr_info_value->total_discount ;
            	$total_rebate   = $total_rebate + $bill_tr_info_value->total_rebate ;	
            }
                $all_discount_rebate = $total_discount + $total_rebate ;
                $net_payable_is      = $total_payable - $all_discount_rebate ;
                $total_due          = $net_payable_is - $total_payment ;
     if($trDate > $this->rcdate){
        Session::put('failed','Sorry ! Enter Invalid Date. Please Enter Valid Date And Try Again ');
        return Redirect::to('pathlogyBillDueCollect');
        exit();
     }
       if($due_payment != $confirm_due_payment){
        Session::put('failed','Sorry ! Due Collect Amount And Confirm Due Collect Amount Did Not Match');
        return Redirect::to('pathlogyBillDueCollect');
        exit();
     }
     // total amount sum
     $total_collect_amount_cal = $due_payment + $rebate ;
     if($total_due < $total_collect_amount_cal){
     	Session::put('failed','Sorry ! Collect Amount + Rebate Amount Will Not Be Big Than Total Due Amount');
        return Redirect::to('pathlogyBillDueCollect');
        exit();
     }
     // get bill info
     $bill_info = DB::table('pathology_bill')->where('branch_id',$this->branch_id)->where('invoice',$invoice)->first();
     $bill_date = $bill_info->bill_date ; 
     $main_invoice_cashbbook_id = $bill_info->cashbook_id ;

        if($trDate < $bill_date){
        Session::put('failed','Sorry ! This Bill Create '.$bill_date.' So Due Collect Date Not Small Than It');
        return Redirect::to('pathlogyBillDueCollect');
        exit();
     }
      if($rebate > 0){
       $purpose = "Pathology Due Collection With Rebate" ;
      }else{
       $purpose = "Pathology Due Collection";
      }
        $data_cashbook                        = array();
        $data_cashbook['overall_branch_id']   = $this->branch_id ;
        $data_cashbook['branch_id']           = $this->branch_id ;
        $data_cashbook['admin_id']            = $this->loged_id  ;
        $data_cashbook['admin_type']          = 3 ;
        $data_cashbook['earn']                = $due_payment + $rebate ;
        $data_cashbook['cost']                = $rebate ;
        $data_cashbook['profit_cost']         = $rebate ;
        $data_cashbook['status']              = 10 ;
        $data_cashbook['tr_status']           = 1 ;
        $data_cashbook['purpose']             = $purpose;
        $data_cashbook['added_id']            = $this->loged_id;
        $data_cashbook['created_time']        = $this->current_time;
        $data_cashbook['created_at']          = $trDate;
        $data_cashbook['on_created_at']       = $this->rcdate;
        DB::table('cashbook')->insert($data_cashbook);
      #---------------------------------- END INSERT INTO CASHBOOK --------------------#
      #--------------------- GET LAST CASH BOOK ID  -----------------------------------#
     $last_cashbook_id_query = DB::table('cashbook')->orderBy('id','desc')->limit(1)->first();
     $last_cashbook_id       = $last_cashbook_id_query->id ; 
    #-------------------- GET LAST CASH BOOK ID --------------------------------------#
     // get last tr id
     $last_tr_query = DB::table('pathology_bill_transaction')->where('branch_id',$this->branch_id)->where('invoice_number',$invoice)->orderBy('invoice_tr_id','desc')->limit(1)->first();
     $last_tr_id    = $last_tr_query->invoice_tr_id + 1 ;
     $branch_invoice_year_number  = $last_tr_query->year_invoice_number ;
     $branch_daily_invoice_number = $last_tr_query->daily_invoice_number ;
     // insert pathology bill due collect
     $data_pathology_bill_tr_insert                           = array();
     $data_pathology_bill_tr_insert['cashbook_id']            = $last_cashbook_id ;
     $data_pathology_bill_tr_insert['branch_id']              = $this->branch_id ;
     $data_pathology_bill_tr_insert['invoice_number']         = $invoice;
     $data_pathology_bill_tr_insert['year_invoice_number']    = $branch_invoice_year_number;
     $data_pathology_bill_tr_insert['daily_invoice_number']   = $branch_daily_invoice_number;
     $data_pathology_bill_tr_insert['invoice_tr_id']          = $last_tr_id ;
     $data_pathology_bill_tr_insert['total_payment']          = $due_payment ;
     $data_pathology_bill_tr_insert['total_rebate']           = $rebate ;
     $data_pathology_bill_tr_insert['status']                 = 1 ;
     $data_pathology_bill_tr_insert['added_id']               = $this->loged_id;
     $data_pathology_bill_tr_insert['tr_date']                = $trDate ;
     $data_pathology_bill_tr_insert['created_time']           = $this->current_time ;
     $data_pathology_bill_tr_insert['created_at']             = $this->rcdate;
     DB::table('pathology_bill_transaction')->insert($data_pathology_bill_tr_insert);
      // insert into pettycash
      $pettycash_amount = DB::table('pettycash')->where('branch_id',$this->branch_id)->where('type',3)->limit(1)->first();
      $current_pettycash_amt = $pettycash_amount->pettycash_amount ;
      $now_pettycash_amt     = $current_pettycash_amt + $due_payment ;
      // update pettycash amount
      $data_update_pettycash                      = array();
      $data_update_pettycash['pettycash_amount']  = $now_pettycash_amt; 
      DB::table('pettycash')->where('branch_id',$this->branch_id)->where('type',3)->update($data_update_pettycash);
      // update due status of main invoice
      if($total_collect_amount_cal < $total_due){
        $due_status_is = '2' ;
      }else{
       $due_status_is = '1' ;
      }
      $data_invoice_update               = array();
      $data_invoice_update['due_status'] = $due_status_is ;
      DB::table('pathology_bill')->where('branch_id',$this->branch_id)->where('invoice',$invoice)->update($data_invoice_update);

      return Redirect::to('printA4PathologyBill/'.$invoice.'/'.$main_invoice_cashbbook_id);
	}
  // due collection report
  public function pathlogyBillDueCollectList()
  {
    $result = DB::table('pathology_bill_transaction')->where('branch_id',$this->branch_id)->where('status','1')->orderBy('id','desc')->get();
    return view('due_collect.pathlogyBillDueCollectList')->with('result',$result);
  }
  // opd due collection
  public function opdBillDueCollect()
  {
    $due_invoice = DB::table('opd_bill')->where('branch_id',$this->branch_id)->where('due_status',2)->orderBy('invoice','asc')->get();
    return view('due_collect.opdBillDueCollect')->with('due_invoice',$due_invoice); 

  }
  public function getOPDInvoiceInfo(Request $request)
  {
    $invoice = trim($request->invoice);
    // get patient id by invoice
    $patient_query = DB::table('opd_bill')->where('branch_id',$this->branch_id)->where('invoice',$invoice)->first();
    $patient_id    = $patient_query->patient_id ;
    $patient_info  = DB::table('tbl_patient')->where('branch_id',$this->branch_id)->where('id',$patient_id)->first();
    echo  "Patient Id : ".$patient_info->patient_number." Name : ".$patient_info->patient_name." Mobile : ".$patient_info->patient_mobile ;
  }
  // get opd invoice calculation
  public function getOpdInvoiceCalculation(Request $request)
  {
    $invoice = trim($request->invoice);
     // get patient id by invoice
    $bill_tr_info = DB::table('opd_bill_transaction')->where('branch_id',$this->branch_id)->where('invoice_number',$invoice)->get();
         $total_payable  = 0 ;
           $total_payment  = 0 ;
           $total_discount = 0 ;
           $total_rebate   = 0 ;
            foreach ($bill_tr_info as $bill_tr_info_value) {
              $total_payable  = $total_payable + $bill_tr_info_value->total_payable ;
              $total_payment  = $total_payment + $bill_tr_info_value->total_payment ;
              $total_discount = $total_discount + $bill_tr_info_value->total_discount ;
              $total_rebate   = $total_rebate + $bill_tr_info_value->total_rebate ; 
            }
                $all_discount_rebate = $total_discount + $total_rebate ;
                $net_payable_is      = $total_payable - $all_discount_rebate ;
                $due_amount          = $net_payable_is - $total_payment ;
                echo $total_payable.'/'.$due_amount ;
  }
  // opd due collection info
  public function opdDueCollectInfo(Request $request)
  {
    $this->validate($request, [
    'invoice'             => 'required',
    'total_due'           => 'required',
    'due_payment'         => 'required',
    'confirm_due_payment' => 'required',
    'rebate'              => 'required',
    'tr_date'             => 'required',
    ]);
     $invoice                  = trim($request->invoice);
     $due_payment              = trim($request->due_payment);
     $confirm_due_payment      = trim($request->confirm_due_payment);
     $rebate                 = trim($request->rebate);
     $confirm_bank_balance     = trim($request->confirm_bank_balance);
     $remarks                  = trim($request->remarks);
     $tr_date                  = trim($request->tr_date);
     $trDate                   = date('Y-m-d',strtotime($tr_date)) ;
     $bill_tr_info = DB::table('opd_bill_transaction')->where('branch_id',$this->branch_id)->where('invoice_number',$invoice)->get();
         $total_payable  = 0 ;
           $total_payment  = 0 ;
           $total_discount = 0 ;
           $total_rebate   = 0 ;
            foreach ($bill_tr_info as $bill_tr_info_value) {
              $total_payable  = $total_payable + $bill_tr_info_value->total_payable ;
              $total_payment  = $total_payment + $bill_tr_info_value->total_payment ;
              $total_discount = $total_discount + $bill_tr_info_value->total_discount ;
              $total_rebate   = $total_rebate + $bill_tr_info_value->total_rebate ; 
            }
                $all_discount_rebate = $total_discount + $total_rebate ;
                $net_payable_is      = $total_payable - $all_discount_rebate ;
                $total_due          = $net_payable_is - $total_payment ;
     if($trDate > $this->rcdate){
        Session::put('failed','Sorry ! Enter Invalid Date. Please Enter Valid Date And Try Again ');
        return Redirect::to('opdBillDueCollect');
        exit();
     }
       if($due_payment != $confirm_due_payment){
        Session::put('failed','Sorry ! Due Collect Amount And Confirm Due Collect Amount Did Not Match');
        return Redirect::to('opdBillDueCollect');
        exit();
     }
     // total amount sum
     $total_collect_amount_cal = $due_payment + $rebate ;
     if($total_due < $total_collect_amount_cal){
      Session::put('failed','Sorry ! Collect Amount + Rebate Amount Will Not Be Big Than Total Due Amount');
        return Redirect::to('opdBillDueCollect');
        exit();
     }
     // get bill info
     $bill_info = DB::table('opd_bill')->where('branch_id',$this->branch_id)->where('invoice',$invoice)->first();
     $bill_date = $bill_info->bill_date ; 
     $main_invoice_cashbbook_id = $bill_info->cashbook_id ;
     $main_opd_bill_id = $bill_info->id ;
     $opd_status =  $bill_info->opd_status ;

        if($trDate < $bill_date){
        Session::put('failed','Sorry ! This Bill Create '.$bill_date.' So Due Collect Date Not Small Than It');
        return Redirect::to('opdBillDueCollect');
        exit();
     }
      if($rebate > 0){
       $purpose = "OPD Due Collection With Rebate" ;
      }else{
       $purpose = "OPD Due Collection";
      }
      // get opd status

      if($opd_status == '2'){
        $data_cashbook                        = array();
        $data_cashbook['overall_branch_id']   = $this->branch_id ;
        $data_cashbook['branch_id']           = $this->branch_id ;
        $data_cashbook['admin_id']            = $this->loged_id  ;
        $data_cashbook['admin_type']          = 3 ;
        $data_cashbook['earn']                = $due_payment + $rebate ;
        $data_cashbook['cost']                = $rebate ;
        $data_cashbook['profit_cost']         = $rebate ;
        $data_cashbook['status']              = 11 ;
        $data_cashbook['tr_status']           = 1 ;
        $data_cashbook['purpose']             = $purpose;
        $data_cashbook['added_id']            = $this->loged_id;
        $data_cashbook['created_time']        = $this->current_time;
        $data_cashbook['created_at']          = $trDate;
        $data_cashbook['on_created_at']       = $this->rcdate;
        DB::table('cashbook')->insert($data_cashbook);
      #---------------------------------- END INSERT INTO CASHBOOK --------------------#
      #--------------------- GET LAST CASH BOOK ID  -----------------------------------#
     $last_cashbook_id_query = DB::table('cashbook')->orderBy('id','desc')->limit(1)->first();
     $last_cashbook_id       = $last_cashbook_id_query->id ;
     } 
    #-------------------- GET LAST CASH BOOK ID --------------------------------------#

    if($opd_status == '2'){
      $cashbook_last_id = $last_cashbook_id ;
    }else{
      $cashbook_last_id = '0' ;
    }
     // get last tr id
     $last_tr_query = DB::table('opd_bill_transaction')->where('branch_id',$this->branch_id)->where('invoice_number',$invoice)->orderBy('invoice_tr_id','desc')->limit(1)->first();
     $last_tr_id    = $last_tr_query->invoice_tr_id + 1 ;
     $branch_invoice_year_number  = $last_tr_query->year_invoice_number ;
     $branch_daily_invoice_number = $last_tr_query->daily_invoice_number ;
     // insert pathology bill due collect
     $data_pathology_bill_tr_insert                           = array();
     $data_pathology_bill_tr_insert['cashbook_id']            = $cashbook_last_id ;
     $data_pathology_bill_tr_insert['opd_bill_id']            = $main_opd_bill_id ;
     $data_pathology_bill_tr_insert['branch_id']              = $this->branch_id ;
     $data_pathology_bill_tr_insert['invoice_number']         = $invoice;
     $data_pathology_bill_tr_insert['year_invoice_number']    = $branch_invoice_year_number;
     $data_pathology_bill_tr_insert['daily_invoice_number']   = $branch_daily_invoice_number;
     $data_pathology_bill_tr_insert['invoice_tr_id']          = $last_tr_id ;
     $data_pathology_bill_tr_insert['total_payment']          = $due_payment ;
     $data_pathology_bill_tr_insert['total_rebate']           = $rebate ;
     $data_pathology_bill_tr_insert['status']                 = 1 ;
     $data_pathology_bill_tr_insert['added_id']               = $this->loged_id;
     $data_pathology_bill_tr_insert['tr_date']                = $trDate ;
     $data_pathology_bill_tr_insert['created_time']           = $this->current_time ;
     $data_pathology_bill_tr_insert['created_at']             = $this->rcdate;
     DB::table('opd_bill_transaction')->insert($data_pathology_bill_tr_insert);
      // insert into pettycash
     if($opd_status == '2'){
      $pettycash_amount = DB::table('pettycash')->where('branch_id',$this->branch_id)->where('type',3)->limit(1)->first();
      $current_pettycash_amt = $pettycash_amount->pettycash_amount ;
      $now_pettycash_amt     = $current_pettycash_amt + $due_payment ;
      // update pettycash amount
      $data_update_pettycash                      = array();
      $data_update_pettycash['pettycash_amount']  = $now_pettycash_amt; 
      DB::table('pettycash')->where('branch_id',$this->branch_id)->where('type',3)->update($data_update_pettycash);
       }
      // update due status of main invoice
      if($total_collect_amount_cal < $total_due){
        $due_status_is = '2' ;
      }else{
       $due_status_is = '1' ;
      }
      $data_invoice_update               = array();
      $data_invoice_update['due_status'] = $due_status_is ;
      DB::table('opd_bill')->where('branch_id',$this->branch_id)->where('invoice',$invoice)->update($data_invoice_update);
      return Redirect::to('printA4OpdBill/'.$invoice.'/'.$main_opd_bill_id);
  }
  // opd bill due collleciton list
  public function opdBillDueCollectList()
  {
    $result = DB::table('opd_bill_transaction')->where('branch_id',$this->branch_id)->where('status','1')->orderBy('id','desc')->get();
    return view('due_collect.opdBillDueCollectList')->with('result',$result);
  }

  

}
