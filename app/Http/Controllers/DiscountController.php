<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests;
use DB;
use Session;

class DiscountController extends Controller
{
     private $rcdate ;
     private $loged_id ;
     private $current_time ;
     private $branch_id ;
     /**
     * Discount Controller costructor 
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
    // cashier pathology discount
    public function cashierPathologyDoctorDiscount()
    {
      $paid_invoice = DB::table('pathology_bill')->where('branch_id',$this->branch_id)->where('due_status',1)->orderBy('invoice','desc')->get();
		return view('discount.cashierPathologyDoctorDiscount')->with('paid_invoice',$paid_invoice);
    }
    public function getInvoiceCalculationForPathologyDiscount(Request $request)
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
            // get return amount
            $return_amt = DB::table('pathology_bill_transaction')->where('branch_id',$this->branch_id)->where('invoice_number',$invoice)->where('status',2)->get();
            $total_return_amt = 0 ;
            foreach ($return_amt as $return_value) {
                $total_return_amt = $total_return_amt + $return_value->total_discount ;
            }

                $all_discount_rebate = $total_discount + $total_rebate ;
                $total_payment_is = $total_payment - $total_return_amt ;  
                echo $total_payable.'/'.$all_discount_rebate.'/'.$total_payment_is ;
    }
    // pathlogy dorctor  discount
    public function pathologyDoctorDiscountInfo(Request $request)
    {
    $this->validate($request, [
    'invoice'                => 'required',
    'patient_info'           => 'required',
    'total_amount'           => 'required',
    'previous_discount'      => 'required',
    'total_payment'          => 'required',
    'tr_date'                => 'required',
    'doctor_discount'        => 'required',
    'confirm_doctor_discount' => 'required',
    ]);
     $invoice              = trim($request->invoice);
     $tr_date              = trim($request->tr_date);
     $trDate               = date('Y-m-d',strtotime($tr_date)) ;
     $doctor_discount      = trim($request->doctor_discount);
     $confirm_doctor_discount = trim($request->confirm_doctor_discount);
     $remarks              = trim($request->address);
     #------------------- start validation ----------------------------#
     // currenet date validatoin
     if($trDate > $this->rcdate){
        Session::put('failed','Sorry ! Enter Invalid Date. Please Enter Valid Date And Try Again ');
        return Redirect::to('cashierPathologyDoctorDiscount');
        exit();
     }
      // get bill info
     $bill_info = DB::table('pathology_bill')->where('branch_id',$this->branch_id)->where('invoice',$invoice)->first();
     $bill_date = $bill_info->bill_date ; 
     $main_invoice_cashbbook_id = $bill_info->cashbook_id ;

      if($trDate < $bill_date){
        Session::put('failed','Sorry ! This Bill Create '.$bill_date.' So Discount Date Not Small Than It');
        return Redirect::to('cashierPathologyDoctorDiscount');
        exit();
     }

     if($bill_info->due_status == '2'){
        Session::put('failed','Sorry ! This Bill Has Due. Please First Collect Due Then Given Doctor Discount');
        return Redirect::to('cashierPathologyDoctorDiscount');
        exit();
     }
      if($doctor_discount != $confirm_doctor_discount){
        Session::put('failed','Sorry ! Discount Amount And Confirm Discount Amount Did Not Match');
        return Redirect::to('cashierPathologyDoctorDiscount');
        exit();
     }
     // pettycash check
     $pettycash_amount1 = DB::table('pettycash')->where('branch_id',$this->branch_id)->where('type',3)->limit(1)->first();
     $current_pettycash_amt1 = $pettycash_amount1->pettycash_amount ;
     if($current_pettycash_amt1 < $doctor_discount){
     	Session::put('failed','Sorry ! Insufficient Balance Of Your Petty Cash. Try Again After Available Petty Cash');
        return Redirect::to('cashierPathologyDoctorDiscount');
        exit();
     }
        // discount amount big that total bill amt
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
            // get return amount
            $return_amt = DB::table('pathology_bill_transaction')->where('branch_id',$this->branch_id)->where('invoice_number',$invoice)->where('status',2)->get();
            $total_return_amt = 0 ;
            foreach ($return_amt as $return_value) {
                $total_return_amt = $total_return_amt + $return_value->total_discount ;
            }
            $total_payment_is = $total_payment - $total_return_amt ; 
            if($total_payment_is < $doctor_discount) {
            Session::put('failed','Sorry ! Discount Amount Big Than Total Patient Payment Amount');
            return Redirect::to('cashierPathologyDoctorDiscount');
            exit(); 
            }
     #------------------- end start validation ------------------------#
       $purpose = "Doctor Discount In Pathology Bill";
        $data_cashbook                        = array();
        $data_cashbook['overall_branch_id']   = $this->branch_id ;
        $data_cashbook['branch_id']           = $this->branch_id ;
        $data_cashbook['admin_id']            = $this->loged_id  ;
        $data_cashbook['admin_type']          = 3 ;
        $data_cashbook['cost']                = $doctor_discount ;
        $data_cashbook['profit_cost']         = $doctor_discount ;
        $data_cashbook['status']              = 28 ;
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
     $data_pathology_bill_tr_insert['total_discount']         = $doctor_discount ;
     $data_pathology_bill_tr_insert['status']                 = 2 ;
     $data_pathology_bill_tr_insert['added_id']               = $this->loged_id;
     $data_pathology_bill_tr_insert['tr_date']                = $trDate ;
     $data_pathology_bill_tr_insert['created_time']           = $this->current_time ;
     $data_pathology_bill_tr_insert['created_at']             = $this->rcdate;
     DB::table('pathology_bill_transaction')->insert($data_pathology_bill_tr_insert);
      $pettycash_amount = DB::table('pettycash')->where('branch_id',$this->branch_id)->where('type',3)->limit(1)->first();
      $current_pettycash_amt = $pettycash_amount->pettycash_amount ;
      $now_pettycash_amt     = $current_pettycash_amt - $doctor_discount ;
      // update pettycash amount
      $data_update_pettycash                      = array();
      $data_update_pettycash['pettycash_amount']  = $now_pettycash_amt; 
      DB::table('pettycash')->where('branch_id',$this->branch_id)->where('type',3)->update($data_update_pettycash);
      return Redirect::to('printA4PathologyBill/'.$invoice.'/'.$main_invoice_cashbbook_id);
    }
    // cashier opd discount
    public function cashierOPDDoctorDiscount()
    {
      $paid_invoice = DB::table('opd_bill')->where('branch_id',$this->branch_id)->where('due_status',1)->orderBy('invoice','desc')->get();
	 return view('discount.cashierOPDDoctorDiscount')->with('paid_invoice',$paid_invoice);
    }
    // get opd calculation
    public function getInvoiceCalculationForOPDDiscount(Request $request)
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
            $return_amt = DB::table('opd_bill_transaction')->where('branch_id',$this->branch_id)->where('invoice_number',$invoice)->where('status',2)->get();
            $total_return_amt = 0 ;
            foreach ($return_amt as $return_value) {
                $total_return_amt = $total_return_amt + $return_value->total_discount ;
            }
            $total_payment_is = $total_payment - $total_return_amt ;  
            echo $total_payable.'/'.$all_discount_rebate.'/'.$total_payment_is ;
    }
    // opd doctor discount info
    public function opdDoctorDiscountInfo(Request $request)
    {
    $this->validate($request, [
    'invoice'                => 'required',
    'patient_info'           => 'required',
    'total_amount'           => 'required',
    'previous_discount'      => 'required',
    'total_payment'          => 'required',
    'tr_date'                => 'required',
    'doctor_discount'        => 'required',
    'confirm_doctor_discount' => 'required',
    ]);
     $invoice              = trim($request->invoice);
     $tr_date              = trim($request->tr_date);
     $trDate               = date('Y-m-d',strtotime($tr_date)) ;
     $doctor_discount      = trim($request->doctor_discount);
     $confirm_doctor_discount = trim($request->confirm_doctor_discount);
     $remarks              = trim($request->address);
     #------------------- start validation ----------------------------#
     // currenet date validatoin
     if($trDate > $this->rcdate){
        Session::put('failed','Sorry ! Enter Invalid Date. Please Enter Valid Date And Try Again ');
        return Redirect::to('cashierOPDDoctorDiscount');
        exit();
     }
      // get bill info
     $bill_info = DB::table('opd_bill')->where('branch_id',$this->branch_id)->where('invoice',$invoice)->first();
     $bill_date = $bill_info->bill_date ; 
     $main_invoice_cashbbook_id = $bill_info->cashbook_id ;
     $main_opd_bill_id = $bill_info->id ;
     $opd_status =  $bill_info->opd_status ;

      if($trDate < $bill_date){
        Session::put('failed','Sorry ! This Bill Create '.$bill_date.' So Discount Date Not Small Than It');
        return Redirect::to('cashierOPDDoctorDiscount');
        exit();
     }

     if($bill_info->due_status == '2'){
        Session::put('failed','Sorry ! This Bill Has Due. Please First Collect Due Then Given Doctor Discount');
        return Redirect::to('cashierOPDDoctorDiscount');
        exit();
     }
      if($doctor_discount != $confirm_doctor_discount){
        Session::put('failed','Sorry ! Discount Amount And Confirm Discount Amount Did Not Match');
        return Redirect::to('cashierOPDDoctorDiscount');
        exit();
     }
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
            // get return amount
            $return_amt = DB::table('opd_bill_transaction')->where('branch_id',$this->branch_id)->where('invoice_number',$invoice)->where('status',2)->get();
            $total_return_amt = 0 ;
            foreach ($return_amt as $return_value) {
                $total_return_amt = $total_return_amt + $return_value->total_discount ;
            }
            $total_payment_is = $total_payment - $total_return_amt ; 
            if($total_payment_is < $doctor_discount) {
            Session::put('failed','Sorry ! Discount Amount Big Than Total Patient Payment Amount');
            return Redirect::to('cashierOPDDoctorDiscount');
            exit(); 
            }

     // pettycash check
     if($opd_status == '2'){
     $pettycash_amount1 = DB::table('pettycash')->where('branch_id',$this->branch_id)->where('type',3)->limit(1)->first();
     $current_pettycash_amt1 = $pettycash_amount1->pettycash_amount ;
     if($current_pettycash_amt1 < $doctor_discount){
     	Session::put('failed','Sorry ! Insufficient Balance Of Your Petty Cash. Try Again After Available Petty Cash');
        return Redirect::to('cashierOPDDoctorDiscount');
        exit();
     }
       }
     #------------------- end start validation ------------------------#
      if($opd_status == '2'){
       $purpose = "Doctor Discount In OPD Bill";
        $data_cashbook                        = array();
        $data_cashbook['overall_branch_id']   = $this->branch_id ;
        $data_cashbook['branch_id']           = $this->branch_id ;
        $data_cashbook['admin_id']            = $this->loged_id  ;
        $data_cashbook['admin_type']          = 3 ;
        $data_cashbook['cost']                = $doctor_discount ;
        $data_cashbook['profit_cost']         = $doctor_discount ;
        $data_cashbook['status']              = 28 ;
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
    if($opd_status == '2'){
      $cashbook_last_id = $last_cashbook_id ;
    }else{
      $cashbook_last_id = '0' ;
    }
    #-------------------- GET LAST CASH BOOK ID --------------------------------------#
       // get last tr id
     $last_tr_query = DB::table('opd_bill_transaction')->where('branch_id',$this->branch_id)->where('invoice_number',$invoice)->orderBy('invoice_tr_id','desc')->limit(1)->first();
     $last_tr_id    = $last_tr_query->invoice_tr_id + 1 ;
     $branch_invoice_year_number  = $last_tr_query->year_invoice_number ;
     $branch_daily_invoice_number = $last_tr_query->daily_invoice_number ;
     // insert pathology bill due collect
     $data_pathology_bill_tr_insert                           = array();
     $data_pathology_bill_tr_insert['cashbook_id']            = $cashbook_last_id ;
     $data_pathology_bill_tr_insert['branch_id']              = $this->branch_id ;
     $data_pathology_bill_tr_insert['invoice_number']         = $invoice;
     $data_pathology_bill_tr_insert['year_invoice_number']    = $branch_invoice_year_number;
     $data_pathology_bill_tr_insert['daily_invoice_number']   = $branch_daily_invoice_number;
     $data_pathology_bill_tr_insert['invoice_tr_id']          = $last_tr_id ;
     $data_pathology_bill_tr_insert['total_discount']         = $doctor_discount ;
     $data_pathology_bill_tr_insert['status']                 = 2 ;
     $data_pathology_bill_tr_insert['added_id']               = $this->loged_id;
     $data_pathology_bill_tr_insert['tr_date']                = $trDate ;
     $data_pathology_bill_tr_insert['created_time']           = $this->current_time ;
     $data_pathology_bill_tr_insert['created_at']             = $this->rcdate;
     DB::table('opd_bill_transaction')->insert($data_pathology_bill_tr_insert);
      if($opd_status == '2'){
      $pettycash_amount = DB::table('pettycash')->where('branch_id',$this->branch_id)->where('type',3)->limit(1)->first();
      $current_pettycash_amt = $pettycash_amount->pettycash_amount ;
      $now_pettycash_amt     = $current_pettycash_amt - $doctor_discount ;
      // update pettycash amount
      $data_update_pettycash                      = array();
      $data_update_pettycash['pettycash_amount']  = $now_pettycash_amt; 
      DB::table('pettycash')->where('branch_id',$this->branch_id)->where('type',3)->update($data_update_pettycash);
     }
      return Redirect::to('printA4OpdBill/'.$invoice.'/'.$main_opd_bill_id);
    }

}
