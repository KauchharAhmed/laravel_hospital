<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests;
use DB;
use Session;

class DeleteController extends Controller
{
     private $rcdate ;
     private $loged_id ;
     private $current_time ;
     private $branch_id ;
     /**
     * Delete class costructor 
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
    // cashier delete patology bill
    public function cashierDeletePathologyBill($invoice , $year_invoice , $daily_invoice ,$cashbook_id)
    {
      $pathology_tr_info = DB::table('pathology_bill_transaction')->where('branch_id',$this->branch_id)->where('invoice_number',$invoice)->where('year_invoice_number',$year_invoice)->where('daily_invoice_number',$daily_invoice)->where('cashbook_id',$cashbook_id)->where('invoice_tr_id',1)->where('status',0)->first();

      $total_payable   = $pathology_tr_info->total_payable ;
      $total_discount  = $pathology_tr_info->total_discount ;
      $total_rebate    = $pathology_tr_info->total_rebate ;
      $total_payment   = $pathology_tr_info->total_payment ;
      $check_pettycash_amount = DB::table('pettycash')->where('branch_id',$this->branch_id)->where('type',3)->limit(1)->first();
      // petty cash update
      $check_current_pettycash_amt = $check_pettycash_amount->pettycash_amount ;
      if($check_current_pettycash_amt < $total_payment){
        Session::put('failed','Sorry ! Pettycash Amount Small Than Delete Bill Payment Amount. Please Delete This Bill After Available Pettycash');
        return Redirect::to('cashierPathologyBillReport');
        exit();
      }

       //check doctor discount of this invoice
    	$doctor_discount_count = DB::table('pathology_bill_transaction')->where('branch_id',$this->branch_id)->where('invoice_number',$invoice)->where('year_invoice_number',$year_invoice)->where('daily_invoice_number',$daily_invoice)->where('status',2)->count();
    	if($doctor_discount_count > 0){
        Session::put('failed','Sorry ! Given Doctor Discount Of This Pathology Bill. First Delete Doctor Discount Of  This Bill Then Try To Delete Pathology Bill');
        return Redirect::to('cashierPathologyDoctorDiscountReport');
        exit();
    	}
    	$due_collection_count = DB::table('pathology_bill_transaction')->where('branch_id',$this->branch_id)->where('invoice_number',$invoice)->where('year_invoice_number',$year_invoice)->where('daily_invoice_number',$daily_invoice)->where('status',1)->count();
    	if($due_collection_count > 0){
        Session::put('failed','Sorry ! Due Collection Of This Pathology Bill. First Delete Due Collection Of This Bill Then Try To Delete Pathology Bill');
        return Redirect::to('cashierPathologyDueCollectionReport');
        exit();
    	}
       $delete_count = DB::table('pathology_bill_transaction')->where('branch_id',$this->branch_id)->where('invoice_number',$invoice)->where('year_invoice_number',$year_invoice)->where('daily_invoice_number',$daily_invoice)->whereNotIn('status',[0])->count();
    	if($delete_count > 0){
        Session::put('failed','Sorry ! Delete Doctor Discount And Due Collection If You Given It. Manualy Check That And Try To Delete Pathology Bill');
        return Redirect::to('cashierPathologyBillReport');
        exit();
    	}
      // get pathology bill info
    	$pathology_info    = DB::table('pathology_bill')->where('branch_id',$this->branch_id)->where('invoice',$invoice)->where('year_invoice',$year_invoice)->where('daily_invoice',$daily_invoice)->where('cashbook_id',$cashbook_id)->first();
    	$bill_added_id     = $pathology_info->added_id ;
    	$bill_tr_date      = $pathology_info->bill_date ;
    	$bill_created_time = $pathology_info->bill_time ;
    	$bill_created_at   = $pathology_info->created_at ;
    	$doctor_id         = $pathology_info->doctor_id ;
    	$pc_id 			       = $pathology_info->pc_id ;
    	$patient_id        = $pathology_info->patient_id ;
    	$due_status        = $pathology_info->due_status ;

    	$pathoogy_item = DB::table('pathology_bill_item')->where('branch_id',$this->branch_id)->where('invoice_number',$invoice)->where('year_invoice_number',$year_invoice)->where('daily_invoice_number',$daily_invoice)->where('cashbook_id',$cashbook_id)->get();
    	$itam = array();
    	foreach ($pathoogy_item as $value_iteam) {
    		$itam[] = $value_iteam->test_id ;
    	}
    	$item_implode = implode(',', $itam);
        // delete cashbook id
    	DB::table('cashbook')->where('id',$cashbook_id)->delete();
      $pettycash_amount = DB::table('pettycash')->where('branch_id',$this->branch_id)->where('type',3)->limit(1)->first();
      // petty cash update
      $current_pettycash_amt = $pettycash_amount->pettycash_amount ;
      $now_pettycash_amt     = $current_pettycash_amt - $total_payment ;
      // update pettycash amount
      $data_update_pettycash                      = array();
      $data_update_pettycash['pettycash_amount']  = $now_pettycash_amt; 
      DB::table('pettycash')->where('branch_id',$this->branch_id)->where('type',3)->update($data_update_pettycash);
      // pc info
      if($pc_id != '0'){
      	// get pc amount of this invoice
      	$pc_amt_query = DB::table('pc_ledger')->where('branch_id',$this->branch_id)->where('cashbook_id',$cashbook_id)->where('invoice',$invoice)->where('year_invoice',$year_invoice)->where('daily_invoice_number',$daily_invoice)->where('invoice_type',1)->where('pc_id',$pc_id)->where('status',1)->first();
      	    // reduce pc amount
      	   // update pc due
      $pc_amount = $pc_amt_query->payable_amount ;
      $pc_due_query = DB::table('pc_due')->where('pc_id',$pc_id)->limit(1)->first();
      $pc_due_amount = $pc_due_query->total_due_amount ;
      $now_pc_due_amount = $pc_due_amount - $pc_amount ;
      // updte pc due amount
      $data_pc_due_update     = array();
      $data_pc_due_update['total_due_amount'] = $now_pc_due_amount; 
      DB::table('pc_due')->where('pc_id',$pc_id)->update($data_pc_due_update);
      	// pc ledger delete
      	DB::table('pc_ledger')->where('branch_id',$this->branch_id)->where('cashbook_id',$cashbook_id)->where('invoice',$invoice)->where('year_invoice',$year_invoice)->where('daily_invoice_number',$daily_invoice)->where('invoice_type',1)->where('pc_id',$pc_id)->where('status',1)->delete();	
      }// pc ledger ended
       if($pc_id == '0'){
       	$bill_pc_amount = 0 ;
       }else{
       	$bill_pc_amount =  $pc_amount ;
       }
       
      // insert data into
      $data_delete_history     				= array();
      $data_delete_history['admin_type'] 	= 3 ;
      $data_delete_history['branch_id'] 	= $this->branch_id ;
      $data_delete_history['status'] 		= 3 ;
      $data_delete_history['tr_status'] 	= 1 ;
      $data_delete_history['before_pettycash_amt']  = $current_pettycash_amt ;
      $data_delete_history['after_pettycash_amt']   = $now_pettycash_amt ;
      $data_delete_history['bill_added_id'] 		= $bill_added_id ;
      $data_delete_history['bill_remove_id'] 		= $this->loged_id ;
      $data_delete_history['bill_tr_date'] 			= $bill_tr_date ;
      $data_delete_history['bill_remove_date'] 		= $this->rcdate ;
      $data_delete_history['bill_created_date'] 	= $bill_created_at ;
      $data_delete_history['bill_created_time'] 	= $bill_created_time ;
      $data_delete_history['bill_remove_time'] 		= $this->current_time ;	
      DB::table('delete_history')->insert($data_delete_history);
	  // get last delete history
      $last_delete_history_query = DB::table('delete_history')->orderBy('id','desc')->limit(1)->first();
      $last_delete_history_id    = $last_delete_history_query->id ; 
      // insert into delete pathlogy bill
      $data_delete_pathology_bill     = array();
      $data_delete_pathology_bill['delete_history_id'] = $last_delete_history_id ;
      $data_delete_pathology_bill['branch_id'] = $this->branch_id ;
      $data_delete_pathology_bill['cashbook_id'] = $cashbook_id ;
      $data_delete_pathology_bill['invoice_number'] = $invoice;
      $data_delete_pathology_bill['year_invoice_number'] = $year_invoice ;
      $data_delete_pathology_bill['daily_invoice_number'] = $daily_invoice;
      $data_delete_pathology_bill['invoice_tr_id'] = 1 ;
      $data_delete_pathology_bill['doctor_id'] = $doctor_id;
      $data_delete_pathology_bill['patient_id'] = $patient_id;
      $data_delete_pathology_bill['pc_id']      = $pc_id;
      $data_delete_pathology_bill['total_payable'] = $total_payable;
      $data_delete_pathology_bill['total_payment'] = $total_payment;
      $data_delete_pathology_bill['total_discount'] = $total_discount;
      $data_delete_pathology_bill['total_rebate'] = $total_rebate;
      $data_delete_pathology_bill['test_id'] = $item_implode;
      $data_delete_pathology_bill['due_status'] = $due_status;
      $data_delete_pathology_bill['pc_amount'] = $bill_pc_amount;
      $data_delete_pathology_bill['status'] = 0 ;
      $data_delete_pathology_bill['bill_added_id'] = $bill_added_id;
      $data_delete_pathology_bill['bill_remove_id'] = $this->loged_id ; 
      $data_delete_pathology_bill['bill_tr_date'] = $bill_tr_date ;
      $data_delete_pathology_bill['bill_remove_date'] = $this->rcdate ;
      $data_delete_pathology_bill['bill_created_date'] = $bill_created_at;
      $data_delete_pathology_bill['bill_created_time'] = $bill_created_time ;
      $data_delete_pathology_bill['bill_remove_time'] = $this->current_time;
      DB::table('delete_pathology_bill')->insert($data_delete_pathology_bill);
      // delete pathlogy bill transaction
      DB::table('pathology_bill_transaction')->where('branch_id',$this->branch_id)->where('invoice_number',$invoice)->where('year_invoice_number',$year_invoice)->where('daily_invoice_number',$daily_invoice)->where('status',0)->where('cashbook_id',$cashbook_id)->where('invoice_tr_id',1)->delete();
      // delete pathlogy bill iteam
      DB::table('pathology_bill_item')->where('branch_id',$this->branch_id)->where('invoice_number',$invoice)->where('year_invoice_number',$year_invoice)->where('daily_invoice_number',$daily_invoice)->where('cashbook_id',$cashbook_id)->delete();
      // delete pathlogy bill
      DB::table('pathology_bill')->where('branch_id',$this->branch_id)->where('invoice',$invoice)->where('year_invoice',$year_invoice)->where('daily_invoice',$daily_invoice)->where('cashbook_id',$cashbook_id)->delete();
      Session::put('succes','Thanks , Pathology Bill Deleted Successfully');
      return Redirect::to('cashierPathologyBillReport');
    }
    // cashier delete pathology due collection
    public function cashierDeletePathologyDueCollection($invoice , $year_invoice , $daily_invoice , $cashbook_id , $invoice_tr_id , $status)
    {
      // due collection info
      $collection_info = DB::table('pathology_bill_transaction')->where('branch_id',$this->branch_id)->where('invoice_number',$invoice)->where('year_invoice_number',$year_invoice)->where('daily_invoice_number',$daily_invoice)->where('status',$status)->where('cashbook_id',$cashbook_id)->where('invoice_tr_id',$invoice_tr_id)->first();
      $total_payable   = $collection_info->total_payable ;
      $total_discount  = $collection_info->total_discount ;
      $total_rebate    = $collection_info->total_rebate ;
      $total_payment   = $collection_info->total_payment ;
      $bill_added_id   = $collection_info->added_id ;
      $bill_tr_date    = $collection_info->tr_date ;
      $bill_created_time = $collection_info->created_time ;
      $bill_created_at   = $collection_info->created_at ;
      // petty cash check
      $check_pettycash_amount = DB::table('pettycash')->where('branch_id',$this->branch_id)->where('type',3)->limit(1)->first();
      $check_current_pettycash_amt = $check_pettycash_amount->pettycash_amount ;
      if($check_current_pettycash_amt < $total_payment){
        Session::put('failed','Sorry ! Pettycash Amount Small Than Delete Collection Amount. Please Delete This Collection After Available Pettycash');
        return Redirect::to('cashierPathologyDueCollectionReport');
        exit();
      }

    	// check doctor discount
    	$count_doctor_discount = DB::table('pathology_bill_transaction')->where('branch_id',$this->branch_id)->where('invoice_number',$invoice)->where('year_invoice_number',$year_invoice)->where('daily_invoice_number',$daily_invoice)->where('status',2)->count();
    	if($count_doctor_discount > 0){
        Session::put('failed','Sorry ! Given Doctor Discount Of This Pathology Bill. First Delete Doctor Discount Of This Bill Then Try To Delete Pathology Due Collection');
        return Redirect::to('cashierPathologyDoctorDiscountReport');
        exit();
    
    	}


    	$due_status = 2 ;
    	// delete cashbook id
    	DB::table('cashbook')->where('id',$cashbook_id)->delete();
    	// change pathology bill status
    	$data_pathlogy_due_status               = array() ;
    	$data_pathlogy_due_status['due_status'] = $due_status ;
    	DB::table('pathology_bill')->where('branch_id',$this->branch_id)->where('invoice',$invoice)->where('year_invoice',$year_invoice)->where('daily_invoice',$daily_invoice)->update($data_pathlogy_due_status);
    	// reduce pettycash amount
     $pettycash_amount = DB::table('pettycash')->where('branch_id',$this->branch_id)->where('type',3)->limit(1)->first();
      $current_pettycash_amt = $pettycash_amount->pettycash_amount ;
      $now_pettycash_amt     = $current_pettycash_amt - $total_payment ;
      // update pettycash amount
      $data_update_pettycash                      = array();
      $data_update_pettycash['pettycash_amount']  = $now_pettycash_amt; 
      DB::table('pettycash')->where('branch_id',$this->branch_id)->where('type',3)->update($data_update_pettycash);
      // insert data into
      $data_delete_history     				= array();
      $data_delete_history['admin_type'] 	= 3 ;
      $data_delete_history['branch_id'] 	= $this->branch_id ;
      $data_delete_history['status'] 		= 1 ;
      $data_delete_history['tr_status'] 	= 1 ;
      $data_delete_history['before_pettycash_amt']  = $current_pettycash_amt ;
      $data_delete_history['after_pettycash_amt']   = $now_pettycash_amt ;
      $data_delete_history['bill_added_id'] 		= $bill_added_id ;
      $data_delete_history['bill_remove_id'] 		= $this->loged_id ;
      $data_delete_history['bill_tr_date'] 			= $bill_tr_date ;
      $data_delete_history['bill_remove_date'] 		= $this->rcdate ;
      $data_delete_history['bill_created_date'] 	= $bill_created_at ;
      $data_delete_history['bill_created_time'] 	= $bill_created_time ;
      $data_delete_history['bill_remove_time'] 		= $this->current_time ;	
      DB::table('delete_history')->insert($data_delete_history);
      // get last delete history
      $last_delete_history_query = DB::table('delete_history')->orderBy('id','desc')->limit(1)->first();
      $last_delete_history_id    = $last_delete_history_query->id ; 
      // insert into delete pathlogy bill
      $data_delete_pathology_bill     = array();
      $data_delete_pathology_bill['delete_history_id'] = $last_delete_history_id ;
      $data_delete_pathology_bill['branch_id'] = $this->branch_id ;
      $data_delete_pathology_bill['cashbook_id'] = $cashbook_id ;
      $data_delete_pathology_bill['invoice_number'] = $invoice;
      $data_delete_pathology_bill['year_invoice_number'] = $year_invoice ;
      $data_delete_pathology_bill['daily_invoice_number'] = $daily_invoice;
      $data_delete_pathology_bill['invoice_tr_id'] = $invoice_tr_id ;
      $data_delete_pathology_bill['total_payable'] = $total_payable;
      $data_delete_pathology_bill['total_payment'] = $total_payment;
      $data_delete_pathology_bill['total_discount'] = $total_discount;
      $data_delete_pathology_bill['total_rebate'] = $total_rebate;
      $data_delete_pathology_bill['status'] = $status ;
      $data_delete_pathology_bill['bill_added_id'] = $bill_added_id;
      $data_delete_pathology_bill['bill_remove_id'] = $this->loged_id ; 
      $data_delete_pathology_bill['bill_tr_date'] = $bill_tr_date ;
      $data_delete_pathology_bill['bill_remove_date'] = $this->rcdate ;
      $data_delete_pathology_bill['bill_created_date'] = $bill_created_at;
      $data_delete_pathology_bill['bill_created_time'] = $bill_created_time ;
      $data_delete_pathology_bill['bill_remove_time'] = $this->current_time;
      DB::table('delete_pathology_bill')->insert($data_delete_pathology_bill);
      // now delete patylogy bill transaction
      DB::table('pathology_bill_transaction')->where('branch_id',$this->branch_id)->where('invoice_number',$invoice)->where('year_invoice_number',$year_invoice)->where('daily_invoice_number',$daily_invoice)->where('status',$status)->where('cashbook_id',$cashbook_id)->where('invoice_tr_id',$invoice_tr_id)->delete();
      Session::put('succes','Thanks , Pathology Due Collection Deleted Successfully');
      return Redirect::to('cashierPathologyDueCollectionReport');
    }
    // pathology doctor discount delete
    public function cashierDeletePathologyDoctorDiscount($invoice , $year_invoice , $daily_invoice , $cashbook_id , $invoice_tr_id , $status)
    {
      $collection_info = DB::table('pathology_bill_transaction')->where('branch_id',$this->branch_id)->where('invoice_number',$invoice)->where('year_invoice_number',$year_invoice)->where('daily_invoice_number',$daily_invoice)->where('status',$status)->where('cashbook_id',$cashbook_id)->where('invoice_tr_id',$invoice_tr_id)->first();
    	$total_payable   = $collection_info->total_payable ;
    	$total_discount  = $collection_info->total_discount ;
    	$total_rebate    = $collection_info->total_rebate ;
    	$total_payment   = $collection_info->total_payment ;
    	$bill_added_id   = $collection_info->added_id ;
    	$bill_tr_date    = $collection_info->tr_date ;
    	$bill_created_time = $collection_info->created_time ;
    	$bill_created_at   = $collection_info->created_at ;

    	// $due_status = 2 ;
    	// delete cashbook id
    	DB::table('cashbook')->where('id',$cashbook_id)->delete();
    	// // change pathology bill status
    	// $data_pathlogy_due_status               = array() ;
    	// $data_pathlogy_due_status['due_status'] = $due_status ;
    	// DB::table('pathology_bill')->where('branch_id',$this->branch_id)->where('invoice',$invoice)->where('year_invoice',$year_invoice)->where('daily_invoice',$daily_invoice)->where('cashbook_id',$cashbook_id)->update($data_pathlogy_due_status);
    	// reduce pettycash amount
     $pettycash_amount = DB::table('pettycash')->where('branch_id',$this->branch_id)->where('type',3)->limit(1)->first();
      $current_pettycash_amt = $pettycash_amount->pettycash_amount ;
      $now_pettycash_amt     = $current_pettycash_amt + $total_discount ;
      // update pettycash amount
      $data_update_pettycash                      = array();
      $data_update_pettycash['pettycash_amount']  = $now_pettycash_amt; 
      DB::table('pettycash')->where('branch_id',$this->branch_id)->where('type',3)->update($data_update_pettycash);
      // insert data into
      $data_delete_history     				= array();
      $data_delete_history['admin_type'] 	= 3 ;
      $data_delete_history['branch_id'] 	= $this->branch_id ;
      $data_delete_history['status'] 		= 2 ;
      $data_delete_history['tr_status'] 	= 1 ;
      $data_delete_history['before_pettycash_amt']  = $current_pettycash_amt ;
      $data_delete_history['after_pettycash_amt']   = $now_pettycash_amt ;
      $data_delete_history['bill_added_id'] 		= $bill_added_id ;
      $data_delete_history['bill_remove_id'] 		= $this->loged_id ;
      $data_delete_history['bill_tr_date'] 			= $bill_tr_date ;
      $data_delete_history['bill_remove_date'] 		= $this->rcdate ;
      $data_delete_history['bill_created_date'] 	= $bill_created_at ;
      $data_delete_history['bill_created_time'] 	= $bill_created_time ;
      $data_delete_history['bill_remove_time'] 		= $this->current_time ;	
      DB::table('delete_history')->insert($data_delete_history);
      // get last delete history
      $last_delete_history_query = DB::table('delete_history')->orderBy('id','desc')->limit(1)->first();
      $last_delete_history_id    = $last_delete_history_query->id ; 
      // insert into delete pathlogy bill
      $data_delete_pathology_bill     = array();
      $data_delete_pathology_bill['delete_history_id'] = $last_delete_history_id ;
      $data_delete_pathology_bill['branch_id'] = $this->branch_id ;
      $data_delete_pathology_bill['cashbook_id'] = $cashbook_id ;
      $data_delete_pathology_bill['invoice_number'] = $invoice;
      $data_delete_pathology_bill['year_invoice_number'] = $year_invoice ;
      $data_delete_pathology_bill['daily_invoice_number'] = $daily_invoice;
      $data_delete_pathology_bill['invoice_tr_id'] = $invoice_tr_id ;
      $data_delete_pathology_bill['total_payable'] = $total_payable;
      $data_delete_pathology_bill['total_payment'] = $total_payment;
      $data_delete_pathology_bill['total_discount'] = $total_discount;
      $data_delete_pathology_bill['total_rebate'] = $total_rebate;
      $data_delete_pathology_bill['status'] = $status ;
      $data_delete_pathology_bill['bill_added_id'] = $bill_added_id;
      $data_delete_pathology_bill['bill_remove_id'] = $this->loged_id ; 
      $data_delete_pathology_bill['bill_tr_date'] = $bill_tr_date ;
      $data_delete_pathology_bill['bill_remove_date'] = $this->rcdate ;
      $data_delete_pathology_bill['bill_created_date'] = $bill_created_at;
      $data_delete_pathology_bill['bill_created_time'] = $bill_created_time ;
      $data_delete_pathology_bill['bill_remove_time'] = $this->current_time;
      DB::table('delete_pathology_bill')->insert($data_delete_pathology_bill);
      // now delete patylogy bill transaction
      DB::table('pathology_bill_transaction')->where('branch_id',$this->branch_id)->where('invoice_number',$invoice)->where('year_invoice_number',$year_invoice)->where('daily_invoice_number',$daily_invoice)->where('status',$status)->where('cashbook_id',$cashbook_id)->where('invoice_tr_id',$invoice_tr_id)->delete();
      Session::put('succes','Thanks , Pathology Doctor Discount Deleted Successfully');
      return Redirect::to('cashierPathologyDoctorDiscountReport');
    }
    // cahsier delete opd bill
    public function cashierDeleteOPDBill($invoice , $year_invoice , $daily_invoice ,$cashbook_id)
    {
      $pathology_tr_info = DB::table('opd_bill_transaction')->where('branch_id',$this->branch_id)->where('invoice_number',$invoice)->where('year_invoice_number',$year_invoice)->where('daily_invoice_number',$daily_invoice)->where('cashbook_id',$cashbook_id)->where('invoice_tr_id',1)->where('status',0)->first();

      $total_payable   = $pathology_tr_info->total_payable ;
      $total_discount  = $pathology_tr_info->total_discount ;
      $total_rebate    = $pathology_tr_info->total_rebate ;
      $total_payment   = $pathology_tr_info->total_payment ;

      $opd_bill_info_query = DB::table('opd_bill')->where('branch_id',$this->branch_id)->where('invoice',$invoice)->where('year_invoice',$year_invoice)->where('daily_invoice',$daily_invoice)->limit(1)->first();
      $opd_status = $opd_bill_info_query->opd_status ;

      if($opd_status == '2'){
      $check_pettycash_amount = DB::table('pettycash')->where('branch_id',$this->branch_id)->where('type',3)->limit(1)->first();
      // petty cash update
      $check_current_pettycash_amt = $check_pettycash_amount->pettycash_amount ;
      if($check_current_pettycash_amt < $total_payment){
        Session::put('failed','Sorry ! Pettycash Amount Small Than Delete Bill Payment Amount. Please Delete This Bill After Available Pettycash');
        return Redirect::to('cashierOPDBillReport');
        exit();
      }
    }
      //check doctor discount of this invoice
      $doctor_discount_count = DB::table('opd_bill_transaction')->where('branch_id',$this->branch_id)->where('invoice_number',$invoice)->where('year_invoice_number',$year_invoice)->where('daily_invoice_number',$daily_invoice)->where('status',2)->count();
      if($doctor_discount_count > 0){
        Session::put('failed','Sorry ! Given Doctor Discount Of This OPD Bill. First Delete Doctor Discount Of  This Bill Then Try To Delete OPD Bill');
        return Redirect::to('cashierOPDDoctorDiscountReport');
        exit();
      }
      $due_collection_count = DB::table('opd_bill_transaction')->where('branch_id',$this->branch_id)->where('invoice_number',$invoice)->where('year_invoice_number',$year_invoice)->where('daily_invoice_number',$daily_invoice)->where('status',1)->count();
      if($due_collection_count > 0){
        Session::put('failed','Sorry ! Due Collection Of This OPD Bill. First Delete Due Collection Of This Bill Then Try To Delete OPD Bill');
        return Redirect::to('cashierOPDDueCollectionReport');
        exit();
      }
        $delete_count = DB::table('opd_bill_transaction')->where('branch_id',$this->branch_id)->where('invoice_number',$invoice)->where('year_invoice_number',$year_invoice)->where('daily_invoice_number',$daily_invoice)->whereNotIn('status',[0])->count();
      if($delete_count > 0){
        Session::put('failed','Sorry ! Delete Doctor Discount And Due Collection If You Given It. Manualy Check That And Try To Delete OPD Bill');
        return Redirect::to('cashierOPDBillReport');
        exit();
      } 
         // get pathology bill info
      $pathology_info    = DB::table('opd_bill')->where('branch_id',$this->branch_id)->where('invoice',$invoice)->where('year_invoice',$year_invoice)->where('daily_invoice',$daily_invoice)->where('cashbook_id',$cashbook_id)->first();
      $bill_added_id     = $pathology_info->added_id ;
      $bill_tr_date      = $pathology_info->bill_date ;
      $bill_created_time = $pathology_info->bill_time ;
      $bill_created_at   = $pathology_info->created_at ;
      $doctor_id         = $pathology_info->doctor_id ;
      $patient_id        = $pathology_info->patient_id ;
      $due_status        = $pathology_info->due_status ;

      $pathoogy_item = DB::table('opd_bill_item')->where('branch_id',$this->branch_id)->where('invoice_number',$invoice)->where('year_invoice_number',$year_invoice)->where('daily_invoice_number',$daily_invoice)->where('cashbook_id',$cashbook_id)->get();
      $itam = array();
      foreach ($pathoogy_item as $value_iteam) {
        $itam[] = $value_iteam->opd_fee_id ;
      }
      $item_implode = implode(',', $itam);
     if($opd_status == '2'){
      // delete cashbook id
      DB::table('cashbook')->where('id',$cashbook_id)->delete();
      $pettycash_amount = DB::table('pettycash')->where('branch_id',$this->branch_id)->where('type',3)->limit(1)->first();
      // petty cash update
      $current_pettycash_amt = $pettycash_amount->pettycash_amount ;
      $now_pettycash_amt     = $current_pettycash_amt - $total_payment ;
      // update pettycash amount
      $data_update_pettycash                      = array();
      $data_update_pettycash['pettycash_amount']  = $now_pettycash_amt; 
      DB::table('pettycash')->where('branch_id',$this->branch_id)->where('type',3)->update($data_update_pettycash);
     }else{
      $pettycash_amount = DB::table('pettycash')->where('branch_id',$this->branch_id)->where('type',3)->limit(1)->first();
      $current_pettycash_amt = $pettycash_amount->pettycash_amount ;
      $now_pettycash_amt     = $pettycash_amount->pettycash_amount ;
    }

      // insert data into
      $data_delete_history            = array();
      $data_delete_history['admin_type']  = 3 ;
      $data_delete_history['branch_id']   = $this->branch_id ;
      $data_delete_history['status']    = 6 ;
      $data_delete_history['tr_status']   = 1 ;
      $data_delete_history['before_pettycash_amt']  = $current_pettycash_amt ;
      $data_delete_history['after_pettycash_amt']   = $now_pettycash_amt ;
      $data_delete_history['bill_added_id']     = $bill_added_id ;
      $data_delete_history['bill_remove_id']    = $this->loged_id ;
      $data_delete_history['bill_tr_date']      = $bill_tr_date ;
      $data_delete_history['bill_remove_date']    = $this->rcdate ;
      $data_delete_history['bill_created_date']   = $bill_created_at ;
      $data_delete_history['bill_created_time']   = $bill_created_time ;
      $data_delete_history['bill_remove_time']    = $this->current_time ; 
      DB::table('delete_history')->insert($data_delete_history);
    // get last delete history
      $last_delete_history_query = DB::table('delete_history')->orderBy('id','desc')->limit(1)->first();
      $last_delete_history_id    = $last_delete_history_query->id ;
         // insert into delete pathlogy bill
      $data_delete_pathology_bill     = array();
      $data_delete_pathology_bill['delete_history_id'] = $last_delete_history_id ;
      $data_delete_pathology_bill['branch_id'] = $this->branch_id ;
      $data_delete_pathology_bill['cashbook_id'] = $cashbook_id ;
      $data_delete_pathology_bill['invoice_number'] = $invoice;
      $data_delete_pathology_bill['year_invoice_number'] = $year_invoice ;
      $data_delete_pathology_bill['daily_invoice_number'] = $daily_invoice;
      $data_delete_pathology_bill['invoice_tr_id'] = 1 ;
      $data_delete_pathology_bill['doctor_id'] = $doctor_id;
      $data_delete_pathology_bill['patient_id'] = $patient_id;
      $data_delete_pathology_bill['total_payable'] = $total_payable;
      $data_delete_pathology_bill['total_payment'] = $total_payment;
      $data_delete_pathology_bill['total_discount'] = $total_discount;
      $data_delete_pathology_bill['total_rebate'] = $total_rebate;
      $data_delete_pathology_bill['opd_fee_id'] = $item_implode;
      $data_delete_pathology_bill['due_status'] = $due_status;
      $data_delete_pathology_bill['status'] = 0 ;
      $data_delete_pathology_bill['opd_status'] = $opd_status ;
      $data_delete_pathology_bill['bill_added_id'] = $bill_added_id;
      $data_delete_pathology_bill['bill_remove_id'] = $this->loged_id ; 
      $data_delete_pathology_bill['bill_tr_date'] = $bill_tr_date ;
      $data_delete_pathology_bill['bill_remove_date'] = $this->rcdate ;
      $data_delete_pathology_bill['bill_created_date'] = $bill_created_at;
      $data_delete_pathology_bill['bill_created_time'] = $bill_created_time ;
      $data_delete_pathology_bill['bill_remove_time'] = $this->current_time;
      DB::table('delete_opd_bill')->insert($data_delete_pathology_bill);
      // delete pathlogy bill transaction
      DB::table('opd_bill_transaction')->where('branch_id',$this->branch_id)->where('invoice_number',$invoice)->where('year_invoice_number',$year_invoice)->where('daily_invoice_number',$daily_invoice)->where('status',0)->where('cashbook_id',$cashbook_id)->where('invoice_tr_id',1)->delete();
      // delete pathlogy bill iteam
      DB::table('opd_bill_item')->where('branch_id',$this->branch_id)->where('invoice_number',$invoice)->where('year_invoice_number',$year_invoice)->where('daily_invoice_number',$daily_invoice)->where('cashbook_id',$cashbook_id)->delete();
      // delete pathlogy bill
      DB::table('opd_bill')->where('branch_id',$this->branch_id)->where('invoice',$invoice)->where('year_invoice',$year_invoice)->where('daily_invoice',$daily_invoice)->where('cashbook_id',$cashbook_id)->delete();
      Session::put('succes','Thanks , OPD Bill Deleted Successfully');
      return Redirect::to('cashierOPDBillReport');

    }

    // cashier delete opd due collecion
    public function cashierDeleteOPDDueCollection($id , $invoice , $year_invoice , $daily_invoice , $cashbook_id , $invoice_tr_id , $status)
    {
      // due collection info
      $collection_info = DB::table('opd_bill_transaction')->where('branch_id',$this->branch_id)->where('id',$id)->where('invoice_number',$invoice)->where('year_invoice_number',$year_invoice)->where('daily_invoice_number',$daily_invoice)->where('status',$status)->where('cashbook_id',$cashbook_id)->where('invoice_tr_id',$invoice_tr_id)->first();
      $total_payable   = $collection_info->total_payable ;
      $total_discount  = $collection_info->total_discount ;
      $total_rebate    = $collection_info->total_rebate ;
      $total_payment   = $collection_info->total_payment ;
      $bill_added_id   = $collection_info->added_id ;
      $bill_tr_date    = $collection_info->tr_date ;
      $bill_created_time = $collection_info->created_time ;
      $bill_created_at   = $collection_info->created_at ;

      // get opd bill info
      $opd_bill_info_query = DB::table('opd_bill')->where('branch_id',$this->branch_id)->where('invoice',$invoice)->where('year_invoice',$year_invoice)->where('daily_invoice',$daily_invoice)->limit(1)->first();
      $opd_status = $opd_bill_info_query->opd_status ;

      if($opd_status == '2'){
      $check_pettycash_amount = DB::table('pettycash')->where('branch_id',$this->branch_id)->where('type',3)->limit(1)->first();
      // petty cash check
      $check_current_pettycash_amt = $check_pettycash_amount->pettycash_amount ;
      if($check_current_pettycash_amt < $total_payment){
        Session::put('failed','Sorry ! Pettycash Amount Small Than Delete Collection Amount. Please Delete This Collection After Available Pettycash');
        return Redirect::to('cashierOPDDueCollectionReport');
        exit();
      }
     }
      // check doctor discount
      $count_doctor_discount = DB::table('opd_bill_transaction')->where('branch_id',$this->branch_id)->where('invoice_number',$invoice)->where('year_invoice_number',$year_invoice)->where('daily_invoice_number',$daily_invoice)->where('status',2)->count();
      if($count_doctor_discount > 0){
        Session::put('failed','Sorry ! Given Doctor Discount Of This OPD Bill. First Delete Doctor Discount Of This Bill Then Try To Delete OPD Due Collection');
        return Redirect::to('cashierOPDDoctorDiscountReport');
        exit();
    
      }

      if($opd_status == '2'){
      // delete cashbook id
      DB::table('cashbook')->where('id',$cashbook_id)->delete();
      // reduce pettycash amount
      $pettycash_amount = DB::table('pettycash')->where('branch_id',$this->branch_id)->where('type',3)->limit(1)->first();
      $current_pettycash_amt = $pettycash_amount->pettycash_amount ;
      $now_pettycash_amt     = $current_pettycash_amt - $total_payment ;
      // update pettycash amount
      $data_update_pettycash                      = array();
      $data_update_pettycash['pettycash_amount']  = $now_pettycash_amt; 
      DB::table('pettycash')->where('branch_id',$this->branch_id)->where('type',3)->update($data_update_pettycash);
       }else{
      $pettycash_amount = DB::table('pettycash')->where('branch_id',$this->branch_id)->where('type',3)->limit(1)->first();
      $current_pettycash_amt = $pettycash_amount->pettycash_amount ;
      $now_pettycash_amt     = $pettycash_amount->pettycash_amount ;
    }

      $due_status = 2 ;
      // change opd bill status
      $data_pathlogy_due_status               = array() ;
      $data_pathlogy_due_status['due_status'] = $due_status ;
      DB::table('opd_bill')->where('branch_id',$this->branch_id)->where('invoice',$invoice)->where('year_invoice',$year_invoice)->where('daily_invoice',$daily_invoice)->update($data_pathlogy_due_status);
    
      // insert data into
      $data_delete_history            = array();
      $data_delete_history['admin_type']  = 3 ;
      $data_delete_history['branch_id']   = $this->branch_id ;
      $data_delete_history['status']    = 5 ;
      $data_delete_history['tr_status']   = 1 ;
      $data_delete_history['before_pettycash_amt']  = $current_pettycash_amt ;
      $data_delete_history['after_pettycash_amt']   = $now_pettycash_amt ;
      $data_delete_history['bill_added_id']     = $bill_added_id ;
      $data_delete_history['bill_remove_id']    = $this->loged_id ;
      $data_delete_history['bill_tr_date']      = $bill_tr_date ;
      $data_delete_history['bill_remove_date']    = $this->rcdate ;
      $data_delete_history['bill_created_date']   = $bill_created_at ;
      $data_delete_history['bill_created_time']   = $bill_created_time ;
      $data_delete_history['bill_remove_time']    = $this->current_time ; 
      DB::table('delete_history')->insert($data_delete_history);
      // get last delete history
      $last_delete_history_query = DB::table('delete_history')->orderBy('id','desc')->limit(1)->first();
      $last_delete_history_id    = $last_delete_history_query->id ; 
      // insert into delete pathlogy bill
      $data_delete_pathology_bill     = array();
      $data_delete_pathology_bill['delete_history_id'] = $last_delete_history_id ;
      $data_delete_pathology_bill['branch_id'] = $this->branch_id ;
      $data_delete_pathology_bill['cashbook_id'] = $cashbook_id ;
      $data_delete_pathology_bill['invoice_number'] = $invoice;
      $data_delete_pathology_bill['year_invoice_number'] = $year_invoice ;
      $data_delete_pathology_bill['daily_invoice_number'] = $daily_invoice;
      $data_delete_pathology_bill['invoice_tr_id'] = $invoice_tr_id ;
      $data_delete_pathology_bill['total_payable'] = $total_payable;
      $data_delete_pathology_bill['total_payment'] = $total_payment;
      $data_delete_pathology_bill['total_discount'] = $total_discount;
      $data_delete_pathology_bill['total_rebate'] = $total_rebate;
      $data_delete_pathology_bill['status'] = $status ;
      $data_delete_pathology_bill['opd_status'] = $opd_status ;
      $data_delete_pathology_bill['bill_added_id'] = $bill_added_id;
      $data_delete_pathology_bill['bill_remove_id'] = $this->loged_id ; 
      $data_delete_pathology_bill['bill_tr_date'] = $bill_tr_date ;
      $data_delete_pathology_bill['bill_remove_date'] = $this->rcdate ;
      $data_delete_pathology_bill['bill_created_date'] = $bill_created_at;
      $data_delete_pathology_bill['bill_created_time'] = $bill_created_time ;
      $data_delete_pathology_bill['bill_remove_time'] = $this->current_time;
      DB::table('delete_opd_bill')->insert($data_delete_pathology_bill);
      // now delete patylogy bill transaction
      DB::table('opd_bill_transaction')->where('branch_id',$this->branch_id)->where('id',$id)->where('invoice_number',$invoice)->where('year_invoice_number',$year_invoice)->where('daily_invoice_number',$daily_invoice)->where('status',$status)->where('cashbook_id',$cashbook_id)->where('invoice_tr_id',$invoice_tr_id)->delete();
      Session::put('succes','Thanks , OPD Due Collection Deleted Successfully');
      return Redirect::to('cashierOPDDueCollectionReport');
    }


    // cahser delete opd discount  
    public function cashierDeleteOPDDoctorDiscount ($id , $invoice , $year_invoice , $daily_invoice , $cashbook_id , $invoice_tr_id , $status)
    {
    $collection_info = DB::table('opd_bill_transaction')->where('branch_id',$this->branch_id)->where('id',$id)->where('invoice_number',$invoice)->where('year_invoice_number',$year_invoice)->where('daily_invoice_number',$daily_invoice)->where('status',$status)->where('cashbook_id',$cashbook_id)->where('invoice_tr_id',$invoice_tr_id)->first();
      $total_payable   = $collection_info->total_payable ;
      $total_discount  = $collection_info->total_discount ;
      $total_rebate    = $collection_info->total_rebate ;
      $total_payment   = $collection_info->total_payment ;
      $bill_added_id   = $collection_info->added_id ;
      $bill_tr_date    = $collection_info->tr_date ;
      $bill_created_time = $collection_info->created_time ;
      $bill_created_at   = $collection_info->created_at ;
      // get opd bill info
      $opd_bill_info_query = DB::table('opd_bill')->where('branch_id',$this->branch_id)->where('invoice',$invoice)->where('year_invoice',$year_invoice)->where('daily_invoice',$daily_invoice)->limit(1)->first();
      $opd_status = $opd_bill_info_query->opd_status ;
      if($opd_status == '2'){
      // delete cashbook id
      DB::table('cashbook')->where('id',$cashbook_id)->delete();
  
      // incress pettycash amount
      $pettycash_amount = DB::table('pettycash')->where('branch_id',$this->branch_id)->where('type',3)->limit(1)->first();
      $current_pettycash_amt = $pettycash_amount->pettycash_amount ;
      $now_pettycash_amt     = $current_pettycash_amt + $total_discount ;
      // update pettycash amount
      $data_update_pettycash                      = array();
      $data_update_pettycash['pettycash_amount']  = $now_pettycash_amt; 
      DB::table('pettycash')->where('branch_id',$this->branch_id)->where('type',3)->update($data_update_pettycash);
    }else{
      $pettycash_amount = DB::table('pettycash')->where('branch_id',$this->branch_id)->where('type',3)->limit(1)->first();
      $current_pettycash_amt = $pettycash_amount->pettycash_amount ;
      $now_pettycash_amt     = $pettycash_amount->pettycash_amount ;
    }
      // insert data into
      $data_delete_history            = array();
      $data_delete_history['admin_type']  = 3 ;
      $data_delete_history['branch_id']   = $this->branch_id ;
      $data_delete_history['status']    = 4 ;
      $data_delete_history['tr_status']   = 1 ;
      $data_delete_history['before_pettycash_amt']  = $current_pettycash_amt ;
      $data_delete_history['after_pettycash_amt']   = $now_pettycash_amt ;
      $data_delete_history['bill_added_id']     = $bill_added_id ;
      $data_delete_history['bill_remove_id']    = $this->loged_id ;
      $data_delete_history['bill_tr_date']      = $bill_tr_date ;
      $data_delete_history['bill_remove_date']    = $this->rcdate ;
      $data_delete_history['bill_created_date']   = $bill_created_at ;
      $data_delete_history['bill_created_time']   = $bill_created_time ;
      $data_delete_history['bill_remove_time']    = $this->current_time ; 
      DB::table('delete_history')->insert($data_delete_history);
      // get last delete history
      $last_delete_history_query = DB::table('delete_history')->orderBy('id','desc')->limit(1)->first();
      $last_delete_history_id    = $last_delete_history_query->id ; 
      // insert into delete pathlogy bill
      $data_delete_pathology_bill     = array();
      $data_delete_pathology_bill['delete_history_id'] = $last_delete_history_id ;
      $data_delete_pathology_bill['branch_id'] = $this->branch_id ;
      $data_delete_pathology_bill['cashbook_id'] = $cashbook_id ;
      $data_delete_pathology_bill['invoice_number'] = $invoice;
      $data_delete_pathology_bill['year_invoice_number'] = $year_invoice ;
      $data_delete_pathology_bill['daily_invoice_number'] = $daily_invoice;
      $data_delete_pathology_bill['invoice_tr_id'] = $invoice_tr_id ;
      $data_delete_pathology_bill['total_payable'] = $total_payable;
      $data_delete_pathology_bill['total_payment'] = $total_payment;
      $data_delete_pathology_bill['total_discount'] = $total_discount;
      $data_delete_pathology_bill['total_rebate'] = $total_rebate;
      $data_delete_pathology_bill['opd_status']   = $opd_status;
      $data_delete_pathology_bill['status'] = $status ;
      $data_delete_pathology_bill['bill_added_id'] = $bill_added_id;
      $data_delete_pathology_bill['bill_remove_id'] = $this->loged_id ; 
      $data_delete_pathology_bill['bill_tr_date'] = $bill_tr_date ;
      $data_delete_pathology_bill['bill_remove_date'] = $this->rcdate ;
      $data_delete_pathology_bill['bill_created_date'] = $bill_created_at;
      $data_delete_pathology_bill['bill_created_time'] = $bill_created_time ;
      $data_delete_pathology_bill['bill_remove_time'] = $this->current_time;
      DB::table('delete_opd_bill')->insert($data_delete_pathology_bill);
      // now delete patylogy bill transaction
      DB::table('opd_bill_transaction')->where('branch_id',$this->branch_id)->where('id',$id)->where('invoice_number',$invoice)->where('year_invoice_number',$year_invoice)->where('daily_invoice_number',$daily_invoice)->where('status',$status)->where('cashbook_id',$cashbook_id)->where('invoice_tr_id',$invoice_tr_id)->delete();
      Session::put('succes','Thanks , OPD Doctor Discount Deleted Successfully');
      return Redirect::to('cashierOPDDoctorDiscountReport');
    }
    #------------------------------------ DELETE IPD ADMISSION ------------------------------#
    // cashier delete ipd admission
    public function cashierDeleteIPDAdmission($id , $invoice , $year_invoice , $daily_invoice , $cashbook_id)
    {
      $admission_tr_info = DB::table('tbl_ipd_ledger')->where('branch_id',$this->branch_id)->where('ipd_admission_id',$id)->where('service_type',1)->where('service_id',$id)->where('service_invoice',$invoice)->first();
      $total_payable   = $admission_tr_info->payable_amount ;
      $total_discount  = $admission_tr_info->discount ;
      $total_rebate    = $admission_tr_info->rebate ;
      $total_payment   = $admission_tr_info->payment_amount ;

      $check_pettycash_amount = DB::table('pettycash')->where('branch_id',$this->branch_id)->where('type',3)->limit(1)->first();
      // petty cash check
      $check_current_pettycash_amt = $check_pettycash_amount->pettycash_amount ;
      if($check_current_pettycash_amt < $total_payment){
        Session::put('failed','Sorry ! Pettycash Amount Small Than IPD Admission Payment Amount. Please Delete This IPD Admission After Available Pettycash');
        return Redirect::to('cashierIpdAdmissionBillReport');
        exit();
      }

      // check ipd clearnce bill
      $count_ipd_clearence_bill = DB::table('tbl_ipd_clear_bill')->where('branch_id',$this->branch_id)->where('ipd_admission_id',$id)->where('ipd_invoice_no',$invoice)->count();
      if($count_ipd_clearence_bill > 0){
         Session::put('failed','Sorry ! IPD Clearence Bill Already Created Of This IPD Admission Patient. Delete IPD Clearence Bill Then Try To Delete IPD Admission Bill');
        return Redirect::to('cashierIpdClearanceBillReport');
        exit();
    
      }
      // chec ipd service bill
      $count_ipd_service_bill = DB::table('tbl_ipd_service_bill')->where('branch_id',$this->branch_id)->where('ipd_admission_id',$id)->where('ipd_invoice_no',$invoice)->count();
      if($count_ipd_service_bill > 0){
         Session::put('failed','Sorry ! IPD Service Bill Already Created Of This IPD Admission Patient. Delete IPD Service Bill Then Try To Delete IPD Admission Bill');
        return Redirect::to('cashierIpdServiceBillReport');
        exit();
      }
       $count_ipd_pathology_bill = DB::table('tbl_ipd_pathology_bill')->where('branch_id',$this->branch_id)->where('ipd_admission_id',$id)->where('ipd_invoice_no',$invoice)->count();
      if($count_ipd_pathology_bill > 0){
         Session::put('failed','Sorry ! IPD Pathology Bill Already Created Of This IPD Admission Patient. Delete IPD Pathology Bill Then Try To Delete IPD Admission Bill');
        return Redirect::to('cashierIpdPathologyBillReport');
        exit();
    
      }
      // cashier collection
      $count_ipd_collection_bill = DB::table('tbl_ipd_ledger')->where('branch_id',$this->branch_id)->where('ipd_admission_id',$id)->where('service_type',4)->count();
      if($count_ipd_collection_bill > 0){
         Session::put('failed','Sorry ! IPD Collection Already Created Of This IPD Admission Patient. Delete IPD Collection Bill Then Try To Delete IPD Admission Bill');
        return Redirect::to('cashierIpdAdmissionBillReport');
        exit();
      }
      $delete_count = DB::table('tbl_ipd_ledger')->where('branch_id',$this->branch_id)->where('ipd_admission_id',$id)->whereNotIn('service_type',[1])->count();
      if($delete_count > 0){
        Session::put('failed','Sorry ! You Have Created Another Transaction Of This IPD Admission Patient. Please Delete This Transaction And Try Again');
        return Redirect::to('cashierIpdAdmissionBillReport');
        exit();
      }

    // ipd admission info
      $ipd_admission_info = DB::table('tbl_ipd_admission')->where('branch_id',$this->branch_id)->where('id',$id)->where('invoice',$invoice)->where('year_invoice',$year_invoice)->where('daily_invoice',$daily_invoice)->where('cashbook_id',$cashbook_id)->first();
      $bill_added_id     = $ipd_admission_info->added_id ;
      $bill_tr_date      = $ipd_admission_info->admit_date ;
      $bill_created_time = $ipd_admission_info->admit_time ;
      $bill_created_at   = $ipd_admission_info->created_at ;
      $admission_status  = $ipd_admission_info->status ;
      $patient_id        = $ipd_admission_info->patient_id ;

      #----------------------------------- cashbook delete ------------------------------------#
      DB::table('cashbook')->where('id',$cashbook_id)->delete();
      #----------------------------------- end cashbook delete ---------------------------------#
      #----------------------------------- update pettycash ------------------------------------#
      $pettycash_amount = DB::table('pettycash')->where('branch_id',$this->branch_id)->where('type',3)->limit(1)->first();
      // petty cash update
      $current_pettycash_amt = $pettycash_amount->pettycash_amount ;
      $now_pettycash_amt     = $current_pettycash_amt - $total_payment ;
      // update pettycash amount
      $data_update_pettycash                      = array();
      $data_update_pettycash['pettycash_amount']  = $now_pettycash_amt; 
      DB::table('pettycash')->where('branch_id',$this->branch_id)->where('type',3)->update($data_update_pettycash);
      #----------------------------------- end update pettycash ------------------------------- #
      #----------------------------- update cabin and room status ------------------------------#
        //booked_status
      // get booked type of this patient
       $room_type_query = DB::table('tbl_ipd_cabin_bed_history')->where('branch_id',$this->branch_id)->where('ipd_admission_id',$id)->where('invoice_number',$invoice)->where('status',1)->first();

       $room_type = $room_type_query->room_type ;
       if($room_type == '1'){
        // cabin
        $cabin_id = $room_type_query->cabin_id ;

        $cabin_room_id_is_used = $cabin_id ;
        $ward_bed_id_used = '';
        // update booked status cabin room free
        $data_cabin_room_booked_status_update     = array();
        $data_cabin_room_booked_status_update['booked_status'] = 0 ;
        DB::table('tbl_cabin_room')->where('id',$cabin_id)->update($data_cabin_room_booked_status_update) ;
       }else{
        // ward
        $ward_id      = $room_type_query->ward_id ;
        $ward_bed_id  = $room_type_query->ward_bed_id ;
        $cabin_room_id_is_used = '';
        $ward_bed_id_used = $ward_bed_id ;
        $data_ward_booked_status_update     = array();
        $data_ward_booked_status_update['booked_status'] = 0 ;
        DB::table('tbl_ward_bed')->where('ward_id',$ward_id)->where('id',$ward_bed_id)->update($data_ward_booked_status_update) ;
       }
       #------------------------------ end update cabin and room status --------------------------#

       #----------------------------- insert data into delete history table----------------------------------#
      $data_delete_history            = array();
      $data_delete_history['admin_type']  = 3 ;
      $data_delete_history['branch_id']   = $this->branch_id ;
      $data_delete_history['status']    = 7 ;
      $data_delete_history['tr_status']   = 1 ;
      $data_delete_history['before_pettycash_amt']  = $current_pettycash_amt ;
      $data_delete_history['after_pettycash_amt']   = $now_pettycash_amt ;
      $data_delete_history['bill_added_id']     = $bill_added_id ;
      $data_delete_history['bill_remove_id']    = $this->loged_id ;
      $data_delete_history['bill_tr_date']      = $bill_tr_date ;
      $data_delete_history['bill_remove_date']    = $this->rcdate ;
      $data_delete_history['bill_created_date']   = $bill_created_at ;
      $data_delete_history['bill_created_time']   = $bill_created_time ;
      $data_delete_history['bill_remove_time']    = $this->current_time ; 
      DB::table('delete_history')->insert($data_delete_history);
       #----------------------------- end insert  data into delete history table ----------------------------#
      #------------------------------ insert delete ipd bill history ----------------------------------------#
        // get last delete history
      $last_delete_history_query = DB::table('delete_history')->orderBy('id','desc')->limit(1)->first();
      $last_delete_history_id    = $last_delete_history_query->id ; 
      // insert into delete pathlogy bill
      $data_delete_ipd_bill_delete_bill     = array();
      $data_delete_ipd_bill_delete_bill['ipd_admission_id'] = $id ;
      $data_delete_ipd_bill_delete_bill['delete_history_id'] = $last_delete_history_id ;
      $data_delete_ipd_bill_delete_bill['branch_id'] = $this->branch_id ;
      $data_delete_ipd_bill_delete_bill['cashbook_id'] = $cashbook_id ;
      $data_delete_ipd_bill_delete_bill['invoice_number'] = $invoice;
      $data_delete_ipd_bill_delete_bill['year_invoice_number'] = $year_invoice ;
      $data_delete_ipd_bill_delete_bill['daily_invoice_number'] = $daily_invoice;
      $data_delete_ipd_bill_delete_bill['patient_id'] = $patient_id ;
      $data_delete_ipd_bill_delete_bill['room_type'] = $room_type ;
      $data_delete_ipd_bill_delete_bill['cabin_id'] = $cabin_room_id_is_used ;
      $data_delete_ipd_bill_delete_bill['ward_bed_id'] = $ward_bed_id_used ;
      $data_delete_ipd_bill_delete_bill['total_payment'] = $total_payment;
      $data_delete_ipd_bill_delete_bill['status'] = 1 ;
      $data_delete_ipd_bill_delete_bill['admission_status'] = $admission_status ;
      $data_delete_ipd_bill_delete_bill['bill_added_id'] = $bill_added_id;
      $data_delete_ipd_bill_delete_bill['bill_remove_id'] = $this->loged_id ; 
      $data_delete_ipd_bill_delete_bill['bill_tr_date'] = $bill_tr_date ;
      $data_delete_ipd_bill_delete_bill['bill_remove_date'] = $this->rcdate ;
      $data_delete_ipd_bill_delete_bill['bill_created_date'] = $bill_created_at;
      $data_delete_ipd_bill_delete_bill['bill_created_time'] = $bill_created_time ;
      $data_delete_ipd_bill_delete_bill['bill_remove_time'] = $this->current_time;
      DB::table('delete_ipd_bill')->insert($data_delete_ipd_bill_delete_bill);
      #----------------------------- end insert deleret ipd bill history ------------------------------------#
      #----------------------------- delete ipd cabin bed history ------------------------------#
      DB::table('tbl_ipd_cabin_bed_history')->where('branch_id',$this->branch_id)->where('ipd_admission_id',$id)->where('invoice_number',$invoice)->where('status',1)->delete();
      #----------------------------- end ipd cabin bed history ---------------------------------#
      #----------------------------- delete ipd ledger ------------------------------------------------------#
      DB::table('tbl_ipd_ledger')->where('branch_id',$this->branch_id)->where('ipd_admission_id',$id)->where('service_type',1)->where('service_id',$id)->where('service_invoice',$invoice)->where('patient_id',$patient_id)->delete();
      #---------------------------- end delete ipd ledger ----------------------------------------------------#
      #---------------------------- delete ipd admission ------------------------------------------------------#
       DB::table('tbl_ipd_admission')->where('branch_id',$this->branch_id)->where('id',$id)->where('invoice',$invoice)->where('year_invoice',$year_invoice)->where('daily_invoice',$daily_invoice)->where('cashbook_id',$cashbook_id)->where('patient_id',$patient_id)->delete();

      Session::put('succes','Thanks , IPD Admission Deleted Successfully');
      return Redirect::to('cashierIpdAdmissionBillReport');

      #---------------------------- end delete ipd admission ---------------------------------------------------#

    }// end function

    #------------------------------------ END DELETE IPD ADMISSION ---------------------------#
    #------------------------------------ DELETE IPD ADMISSION BILL --------------------------#
     public function cashierDeleteIPDPathology($bill_id ,$ipd_admission_id , $invoice , $year_invoice , $daily_invoice , $cashbook_id)
     {
      // check clearence bill ok
      $count_ipd_clearence_bill = DB::table('tbl_ipd_clear_bill')->where('branch_id',$this->branch_id)->where('ipd_admission_id',$ipd_admission_id)->count();
      if($count_ipd_clearence_bill > 0){
         Session::put('failed','Sorry ! IPD Clearence Bill Already Created Of This IPD Patient. Delete IPD Clearence Bill Then Try To Delete IPD Pathology Bill');
        return Redirect::to('cashierIpdClearanceBillReport');
        exit();
    
      }

      $admission_tr_info = DB::table('tbl_ipd_ledger')->where('branch_id',$this->branch_id)->where('ipd_admission_id',$ipd_admission_id)->where('service_type',2)->where('service_id',$bill_id)->where('service_invoice',$invoice)->first();
      $total_payable   = $admission_tr_info->payable_amount ;
      $total_discount  = $admission_tr_info->discount ;
      $total_rebate    = $admission_tr_info->rebate ;
      $total_payment   = $admission_tr_info->payment_amount ;

      $check_pettycash_amount = DB::table('pettycash')->where('branch_id',$this->branch_id)->where('type',3)->limit(1)->first();
      // petty cash check
      $check_current_pettycash_amt = $check_pettycash_amount->pettycash_amount ;
      if($check_current_pettycash_amt < $total_payment){
        Session::put('failed','Sorry ! Pettycash Amount Small Than IPD Pathology Bill Payment Amount. Please Delete This IPD Pathology Bill After Available Pettycash');
        return Redirect::to('cashierIpdPathologyBillReport');
        exit();
      }

      // get pathology bill info
      $pathology_info    = DB::table('tbl_ipd_pathology_bill')->where('branch_id',$this->branch_id)->where('ipd_admission_id',$ipd_admission_id)->where('id',$bill_id)->where('invoice',$invoice)->where('year_invoice',$year_invoice)->where('daily_invoice',$daily_invoice)->where('cashbook_id',$cashbook_id)->first();
      $bill_added_id     = $pathology_info->added_id ;
      $bill_tr_date      = $pathology_info->bill_date ;
      $bill_created_time = $pathology_info->bill_time ;
      $bill_created_at   = $pathology_info->created_at ;
      $doctor_id         = $pathology_info->doctor_id ;
      $pc_id             = $pathology_info->pc_id ;
      $patient_id        = $pathology_info->patient_id ;

      $pathoogy_item = DB::table('tbl_ipd_pathology_bill_item')->where('branch_id',$this->branch_id)->where('ipd_admission_id',$ipd_admission_id)->where('invoice_number',$invoice)->where('year_invoice_number',$year_invoice)->where('daily_invoice_number',$daily_invoice)->where('cashbook_id',$cashbook_id)->get();
      $itam = array();
      foreach ($pathoogy_item as $value_iteam) {
        $itam[] = $value_iteam->test_id ;
      }
      $item_implode = implode(',', $itam);

      #----------------------------------- cashbook delete ------------------------------------#
      DB::table('cashbook')->where('id',$cashbook_id)->delete();
      #----------------------------------- end cashbook delete ---------------------------------#
      #----------------------------------- update pettycash ------------------------------------#
      $pettycash_amount = DB::table('pettycash')->where('branch_id',$this->branch_id)->where('type',3)->limit(1)->first();
      // petty cash update
      $current_pettycash_amt = $pettycash_amount->pettycash_amount ;
      $now_pettycash_amt     = $current_pettycash_amt - $total_payment ;
      // update pettycash amount
      $data_update_pettycash                      = array();
      $data_update_pettycash['pettycash_amount']  = $now_pettycash_amt; 
      DB::table('pettycash')->where('branch_id',$this->branch_id)->where('type',3)->update($data_update_pettycash);
      #----------------------------------- end update pettycash ------------------------------- #
      #----------------------------- insert data into delete history table----------------------------------#
      $data_delete_history            = array();
      $data_delete_history['admin_type']  = 3 ;
      $data_delete_history['branch_id']   = $this->branch_id ;
      $data_delete_history['status']    = 8 ;
      $data_delete_history['tr_status']   = 1 ;
      $data_delete_history['before_pettycash_amt']  = $current_pettycash_amt ;
      $data_delete_history['after_pettycash_amt']   = $now_pettycash_amt ;
      $data_delete_history['bill_added_id']     = $bill_added_id ;
      $data_delete_history['bill_remove_id']    = $this->loged_id ;
      $data_delete_history['bill_tr_date']      = $bill_tr_date ;
      $data_delete_history['bill_remove_date']    = $this->rcdate ;
      $data_delete_history['bill_created_date']   = $bill_created_at ;
      $data_delete_history['bill_created_time']   = $bill_created_time ;
      $data_delete_history['bill_remove_time']    = $this->current_time ; 
      DB::table('delete_history')->insert($data_delete_history);
      #----------------------------- end insert  data into delete history table ----------------------------#
      #------------------------------ insert delete ipd bill history ----------------------------------------#
        // get last delete history
      $last_delete_history_query = DB::table('delete_history')->orderBy('id','desc')->limit(1)->first();
      $last_delete_history_id    = $last_delete_history_query->id ; 
      // insert into delete pathlogy bill
      $data_delete_ipd_bill_delete_bill     = array();
      $data_delete_ipd_bill_delete_bill['ipd_admission_id'] = $ipd_admission_id;
      $data_delete_ipd_bill_delete_bill['delete_history_id'] = $last_delete_history_id ;
      $data_delete_ipd_bill_delete_bill['branch_id'] = $this->branch_id ;
      $data_delete_ipd_bill_delete_bill['cashbook_id'] = $cashbook_id ;
      $data_delete_ipd_bill_delete_bill['invoice_number'] = $invoice;
      $data_delete_ipd_bill_delete_bill['year_invoice_number'] = $year_invoice ;
      $data_delete_ipd_bill_delete_bill['daily_invoice_number'] = $daily_invoice;
      $data_delete_ipd_bill_delete_bill['doctor_id'] = $doctor_id ;
      $data_delete_ipd_bill_delete_bill['patient_id'] = $patient_id ;
      $data_delete_ipd_bill_delete_bill['total_payable'] = $total_payable;
      $data_delete_ipd_bill_delete_bill['total_payment'] = $total_payment;
      $data_delete_ipd_bill_delete_bill['total_discount'] = $total_discount;
      $data_delete_ipd_bill_delete_bill['total_rebate'] = $total_rebate;
      $data_delete_ipd_bill_delete_bill['test_id_service_id'] = $item_implode ;
      $data_delete_ipd_bill_delete_bill['status'] = 2 ;
      $data_delete_ipd_bill_delete_bill['bill_added_id'] = $bill_added_id;
      $data_delete_ipd_bill_delete_bill['bill_remove_id'] = $this->loged_id ; 
      $data_delete_ipd_bill_delete_bill['bill_tr_date'] = $bill_tr_date ;
      $data_delete_ipd_bill_delete_bill['bill_remove_date'] = $this->rcdate ;
      $data_delete_ipd_bill_delete_bill['bill_created_date'] = $bill_created_at;
      $data_delete_ipd_bill_delete_bill['bill_created_time'] = $bill_created_time ;
      $data_delete_ipd_bill_delete_bill['bill_remove_time'] = $this->current_time;
      DB::table('delete_ipd_bill')->insert($data_delete_ipd_bill_delete_bill);
      #----------------------------- end insert deleret ipd bill history ------------------------------------#
      #----------------------------- delete ipd ledger ------------------------------------------------------#
      DB::table('tbl_ipd_ledger')->where('branch_id',$this->branch_id)->where('ipd_admission_id',$ipd_admission_id)->where('service_type',2)->where('service_id',$bill_id)->where('service_invoice',$invoice)->delete();
      #----------------------------- end delete ipd ledger ---------------------------------------------------#
      #----------------------------- delete ipd patology bill itema-------------------------------------------#
       DB::table('tbl_ipd_pathology_bill_item')->where('branch_id',$this->branch_id)->where('ipd_admission_id',$ipd_admission_id)->where('invoice_number',$invoice)->where('year_invoice_number',$year_invoice)->where('daily_invoice_number',$daily_invoice)->where('cashbook_id',$cashbook_id)->delete();
      #----------------------------- end delete ipd patology bill itema----------------------------------------#
      #----------------------------- delet ipd pathology bill --------------------------------------------------#
       DB::table('tbl_ipd_pathology_bill')->where('branch_id',$this->branch_id)->where('ipd_admission_id',$ipd_admission_id)->where('id',$bill_id)->where('invoice',$invoice)->where('year_invoice',$year_invoice)->where('daily_invoice',$daily_invoice)->where('cashbook_id',$cashbook_id)->delete();
      #----------------------------- end delete ipd pathology bill ----------------------------------------------#
      Session::put('succes','Thanks , IPD Pathology Bill Deleted Successfully');
      return Redirect::to('cashierIpdPathologyBillReport');

     }
    #------------------------------------ END DELETE IPD ADMISSION BILL -----------------------#
    #------------------------------------ CASHIER DELETE IPD SERVICE----------------------------#
    public function cashierDeleteIPDService($bill_id ,$ipd_admission_id , $invoice , $year_invoice , $daily_invoice , $cashbook_id)
    {
      // check clearence bill ok
      $count_ipd_clearence_bill = DB::table('tbl_ipd_clear_bill')->where('branch_id',$this->branch_id)->where('ipd_admission_id',$ipd_admission_id)->count();
      if($count_ipd_clearence_bill > 0){
         Session::put('failed','Sorry ! IPD Clearence Bill Already Created Of This IPD Patient. Delete IPD Clearence Bill Then Try To Delete IPD Service Bill');
        return Redirect::to('cashierIpdClearanceBillReport');
        exit();
      }
      $admission_tr_info = DB::table('tbl_ipd_ledger')->where('branch_id',$this->branch_id)->where('ipd_admission_id',$ipd_admission_id)->where('service_type',3)->where('service_id',$bill_id)->where('service_invoice',$invoice)->first();
      $total_payable   = $admission_tr_info->payable_amount ;
      $total_discount  = $admission_tr_info->discount ;
      $total_rebate    = $admission_tr_info->rebate ;
      $total_payment   = $admission_tr_info->payment_amount ;

      $check_pettycash_amount = DB::table('pettycash')->where('branch_id',$this->branch_id)->where('type',3)->limit(1)->first();
      // petty cash check
      $check_current_pettycash_amt = $check_pettycash_amount->pettycash_amount ;
      if($check_current_pettycash_amt < $total_payment){
        Session::put('failed','Sorry ! Pettycash Amount Small Than IPD Service Bill Payment Amount. Please Delete This IPD Service Bill After Available Pettycash');
        return Redirect::to('cashierIpdServiceBillReport');
        exit();
      }
       // get servie bill info
      $ipd_service_info_bill  = DB::table('tbl_ipd_service_bill')->where('branch_id',$this->branch_id)->where('ipd_admission_id',$ipd_admission_id)->where('id',$bill_id)->where('invoice',$invoice)->where('year_invoice',$year_invoice)->where('daily_invoice',$daily_invoice)->where('cashbook_id',$cashbook_id)->first();
      $bill_added_id     = $ipd_service_info_bill->added_id ;
      $bill_tr_date      = $ipd_service_info_bill->bill_date ;
      $bill_created_time = $ipd_service_info_bill->bill_time ;
      $bill_created_at   = $ipd_service_info_bill->created_at ;
      $patient_id        = $ipd_service_info_bill->patient_id ;

      $ipd_service_item = DB::table('tbl_ipd_service_bill_item')->where('branch_id',$this->branch_id)->where('ipd_admission_id',$ipd_admission_id)->where('invoice_number',$invoice)->where('year_invoice_number',$year_invoice)->where('daily_invoice_number',$daily_invoice)->where('cashbook_id',$cashbook_id)->get();
      $itam = array();
      foreach ($ipd_service_item as $value_iteam) {
        $itam[] = $value_iteam->service_id ;
      }
      $item_implode = implode(',', $itam);
      #----------------------------------- cashbook delete ------------------------------------#
      DB::table('cashbook')->where('id',$cashbook_id)->delete();
      #----------------------------------- end cashbook delete ---------------------------------#
      #----------------------------------- update pettycash ------------------------------------#
      $pettycash_amount = DB::table('pettycash')->where('branch_id',$this->branch_id)->where('type',3)->limit(1)->first();
      // petty cash update
      $current_pettycash_amt = $pettycash_amount->pettycash_amount ;
      $now_pettycash_amt     = $current_pettycash_amt - $total_payment ;
      // update pettycash amount
      $data_update_pettycash                      = array();
      $data_update_pettycash['pettycash_amount']  = $now_pettycash_amt; 
      DB::table('pettycash')->where('branch_id',$this->branch_id)->where('type',3)->update($data_update_pettycash);
      #----------------------------------- end update pettycash ------------------------------- #
      #----------------------------- insert data into delete history table----------------------------------#
      $data_delete_history            = array();
      $data_delete_history['admin_type']  = 3 ;
      $data_delete_history['branch_id']   = $this->branch_id ;
      $data_delete_history['status']    = 9 ;
      $data_delete_history['tr_status']   = 1 ;
      $data_delete_history['before_pettycash_amt']  = $current_pettycash_amt ;
      $data_delete_history['after_pettycash_amt']   = $now_pettycash_amt ;
      $data_delete_history['bill_added_id']     = $bill_added_id ;
      $data_delete_history['bill_remove_id']    = $this->loged_id ;
      $data_delete_history['bill_tr_date']      = $bill_tr_date ;
      $data_delete_history['bill_remove_date']    = $this->rcdate ;
      $data_delete_history['bill_created_date']   = $bill_created_at ;
      $data_delete_history['bill_created_time']   = $bill_created_time ;
      $data_delete_history['bill_remove_time']    = $this->current_time ; 
      DB::table('delete_history')->insert($data_delete_history);
      #----------------------------- end insert  data into delete history table ----------------------------#
      #------------------------------ insert delete ipd bill history ----------------------------------------#
        // get last delete history
      $last_delete_history_query = DB::table('delete_history')->orderBy('id','desc')->limit(1)->first();
      $last_delete_history_id    = $last_delete_history_query->id ; 
      // insert into delete pathlogy bill
      $data_delete_ipd_bill_delete_bill     = array();
      $data_delete_ipd_bill_delete_bill['ipd_admission_id'] = $ipd_admission_id;
      $data_delete_ipd_bill_delete_bill['delete_history_id'] = $last_delete_history_id ;
      $data_delete_ipd_bill_delete_bill['branch_id'] = $this->branch_id ;
      $data_delete_ipd_bill_delete_bill['cashbook_id'] = $cashbook_id ;
      $data_delete_ipd_bill_delete_bill['invoice_number'] = $invoice;
      $data_delete_ipd_bill_delete_bill['year_invoice_number'] = $year_invoice ;
      $data_delete_ipd_bill_delete_bill['daily_invoice_number'] = $daily_invoice;
      $data_delete_ipd_bill_delete_bill['patient_id'] = $patient_id ;
      $data_delete_ipd_bill_delete_bill['total_payable'] = $total_payable;
      $data_delete_ipd_bill_delete_bill['total_payment'] = $total_payment;
      $data_delete_ipd_bill_delete_bill['total_discount'] = $total_discount;
      $data_delete_ipd_bill_delete_bill['total_rebate'] = $total_rebate;
      $data_delete_ipd_bill_delete_bill['test_id_service_id'] = $item_implode ;
      $data_delete_ipd_bill_delete_bill['status'] = 3 ;
      $data_delete_ipd_bill_delete_bill['bill_added_id'] = $bill_added_id;
      $data_delete_ipd_bill_delete_bill['bill_remove_id'] = $this->loged_id ; 
      $data_delete_ipd_bill_delete_bill['bill_tr_date'] = $bill_tr_date ;
      $data_delete_ipd_bill_delete_bill['bill_remove_date'] = $this->rcdate ;
      $data_delete_ipd_bill_delete_bill['bill_created_date'] = $bill_created_at;
      $data_delete_ipd_bill_delete_bill['bill_created_time'] = $bill_created_time ;
      $data_delete_ipd_bill_delete_bill['bill_remove_time'] = $this->current_time;
      DB::table('delete_ipd_bill')->insert($data_delete_ipd_bill_delete_bill);
      #----------------------------- end insert deleret ipd bill history ------------------------------------#
      #----------------------------- delete ipd ledger ------------------------------------------------------#
      DB::table('tbl_ipd_ledger')->where('branch_id',$this->branch_id)->where('ipd_admission_id',$ipd_admission_id)->where('service_type',3)->where('service_id',$bill_id)->where('service_invoice',$invoice)->delete();
      #----------------------------- end delete ipd ledger ---------------------------------------------------#
      #----------------------------- delete ipd patology bill itema-------------------------------------------#
       DB::table('tbl_ipd_service_bill_item')->where('branch_id',$this->branch_id)->where('ipd_admission_id',$ipd_admission_id)->where('invoice_number',$invoice)->where('year_invoice_number',$year_invoice)->where('daily_invoice_number',$daily_invoice)->where('cashbook_id',$cashbook_id)->delete();
      #----------------------------- end delete ipd patology bill itema----------------------------------------#
      #----------------------------- delet ipd pathology bill --------------------------------------------------#
       DB::table('tbl_ipd_service_bill')->where('branch_id',$this->branch_id)->where('ipd_admission_id',$ipd_admission_id)->where('id',$bill_id)->where('invoice',$invoice)->where('year_invoice',$year_invoice)->where('daily_invoice',$daily_invoice)->where('cashbook_id',$cashbook_id)->delete();
      #----------------------------- end delete ipd pathology bill ----------------------------------------------#
      Session::put('succes','Thanks , IPD Service Bill Deleted Successfully');
      return Redirect::to('cashierIpdServiceBillReport');
    }// end function
     #------------------------------------- END CASHIER DELETE IPD SERVICE -------------------------#
    public function cashierDeleteIPDClearence($bill_id ,$ipd_admission_id , $invoice , $year_invoice , $daily_invoice , $cashbook_id)
    {
      // if count delete clear bill room or bed empty or book by other patient
      $room_type_query = DB::table('tbl_ipd_cabin_bed_history')->where('branch_id',$this->branch_id)->where('ipd_admission_id',$ipd_admission_id)->where('status',1)->first();

       $room_type = $room_type_query->room_type ;

       if($room_type == '1'){
         $cabin_id = $room_type_query->cabin_id ;
         $cabin_room_booked_status_count = DB::table('tbl_cabin_room')->where('id',$cabin_id)->where('booked_status',1)->count();
         if($cabin_room_booked_status_count > 0){
        Session::put('failed','Sorry ! IPD Clearence Bill Patient Room Already Booked By Another Patient. You Can Not Delete This IPD Clearence Bill. Free This Room Then Delete This Bill------------------------');
        return Redirect::to('cashierIpdClearanceBillReport');
        exit();

         }
       }else{
         $ward_bed_id  = $room_type_query->ward_bed_id ;
         $ward_bed_booked_status_count = DB::table('tbl_ward_bed')->where('id',$ward_bed_id)->where('booked_status',1)->count();
         if($ward_bed_booked_status_count > 0){
          Session::put('failed','Sorry ! IPD Clearence Bill Bed Already Booked By Another Patient. You Can Not Delete This IPD Clearence Bill. Free This Bed Then Delete This Bill----------------------------');
        return Redirect::to('cashierIpdClearanceBillReport');
        exit();

         }
       }

       if($room_type == '1'){
        // cabin
        $cabin_id = $room_type_query->cabin_id ;
        $cabin_room_id_is_used = $cabin_id ;
        $ward_bed_id_used = '';
        $cabin_book_without_this_admission_id = DB::table('tbl_ipd_cabin_bed_history')->where('branch_id',$this->branch_id)->where('cabin_id',$cabin_id)->where('ipd_admission_id','>',$ipd_admission_id)->count();
        if($cabin_book_without_this_admission_id > 0){
        Session::put('failed','Sorry ! IPD Clearence Bill Patient Room Already Booked By Another Patient. You Can Not Delete This IPD Clearence Bill. Free This Room Then Delete This Bill');
        return Redirect::to('cashierIpdClearanceBillReport');
        exit();
        }

       }else{
        // ward
        $ward_id      = $room_type_query->ward_id ;
        $ward_bed_id  = $room_type_query->ward_bed_id ;
        $cabin_room_id_is_used = '';
        $ward_bed_id_used = $ward_bed_id ;
        $book_bed_without_this_admission_id = DB::table('tbl_ipd_cabin_bed_history')->where('branch_id',$this->branch_id)->where('ward_id',$ward_id)->where('ward_bed_id',$ward_bed_id)->where('ipd_admission_id','>',$ipd_admission_id)->count();
        if($book_bed_without_this_admission_id > 0){
        Session::put('failed','Sorry ! IPD Clearence Bill Bed Already Booked By Another Patient. You Can Not Delete This IPD Clearence Bill. Free This Bed Then Delete This Bill');
        return Redirect::to('cashierIpdClearanceBillReport');
        exit();
        }
       }// check room or bed ended

      $room_rent_tr_info = DB::table('tbl_ipd_ledger')->where('branch_id',$this->branch_id)->where('ipd_admission_id',$ipd_admission_id)->where('service_type',5)->where('service_id',$bill_id)->where('service_invoice',$invoice)->first();

      $total_payable_r   = $room_rent_tr_info->payable_amount ;
      $total_discount_r  = $room_rent_tr_info->discount ;
      $total_rebate_r    = $room_rent_tr_info->rebate ;
      $total_payment_r   = $room_rent_tr_info->payment_amount ;

      $clear_payment = DB::table('tbl_ipd_ledger')->where('branch_id',$this->branch_id)->where('ipd_admission_id',$ipd_admission_id)->where('service_type',6)->where('service_id',$bill_id)->where('service_invoice',$invoice)->first();

      $total_payable_p   = $clear_payment->payable_amount ;
      $total_discount_p  = $clear_payment->discount ;
      $total_rebate_p    = $clear_payment->rebate ;
      $total_payment_p   = $clear_payment->payment_amount ;

      $total_payable   = $total_payable_r + $total_payable_p ;
      $total_discount  = $total_discount_r + $total_discount_p ;
      $total_rebate    = $total_rebate_r + $total_rebate_p ;
      $total_payment   = $total_payment_r + $total_payment_p ; 

      $check_pettycash_amount = DB::table('pettycash')->where('branch_id',$this->branch_id)->where('type',3)->limit(1)->first();
      // petty cash check
      $check_current_pettycash_amt = $check_pettycash_amount->pettycash_amount ;
      if($check_current_pettycash_amt < $total_payment){
        Session::put('failed','Sorry ! Pettycash Amount Small Than IPD Clearence Bill Payment Amount. Please Delete This IPD Clearence Bill After Available Pettycash');
        return Redirect::to('cashierIpdClearanceBillReport');
        exit();
      }
      $ipd_clearenc_info_bill  = DB::table('tbl_ipd_clear_bill')->where('branch_id',$this->branch_id)->where('ipd_admission_id',$ipd_admission_id)->where('id',$bill_id)->where('invoice',$invoice)->where('year_invoice',$year_invoice)->where('daily_invoice',$daily_invoice)->where('cashbook_id',$cashbook_id)->first();
      $bill_added_id     = $ipd_clearenc_info_bill->added_id ;
      $bill_tr_date      = $ipd_clearenc_info_bill->bill_date ;
      $bill_created_time = $ipd_clearenc_info_bill->bill_time ;
      $bill_created_at   = $ipd_clearenc_info_bill->created_at ;
      $patient_id        = $ipd_clearenc_info_bill->patient_id ;
      $pc_id             = $ipd_clearenc_info_bill->pc_id ;
      $admit_date        = $ipd_clearenc_info_bill->admit_date ;
      $end_date          = $ipd_clearenc_info_bill->end_date ;

      #----------------------------------- cashbook delete ------------------------------------#
      DB::table('cashbook')->where('id',$cashbook_id)->delete();
      #----------------------------------- end cashbook delete ---------------------------------#
      #----------------------------------- update pettycash ------------------------------------#
      $pettycash_amount = DB::table('pettycash')->where('branch_id',$this->branch_id)->where('type',3)->limit(1)->first();
      // petty cash update
      $current_pettycash_amt = $pettycash_amount->pettycash_amount ;
      $now_pettycash_amt     = $current_pettycash_amt - $total_payment ;
      // update pettycash amount
      $data_update_pettycash                      = array();
      $data_update_pettycash['pettycash_amount']  = $now_pettycash_amt; 
      DB::table('pettycash')->where('branch_id',$this->branch_id)->where('type',3)->update($data_update_pettycash);
      #----------------------------------- end update pettycash ------------------------------- #

        #----------------------------- pc information-----------------------------------------------------------#
        if($pc_id != '0'){
        // get pc amount of this invoice
        $pc_amt_query = DB::table('pc_ledger')->where('branch_id',$this->branch_id)->where('cashbook_id',$cashbook_id)->where('invoice',$invoice)->where('year_invoice',$year_invoice)->where('daily_invoice_number',$daily_invoice)->where('invoice_type',2)->where('pc_id',$pc_id)->where('status',2)->first();
            // reduce pc amount
           // update pc due
      $pc_amount = $pc_amt_query->payable_amount ;
      $pc_due_query = DB::table('pc_due')->where('pc_id',$pc_id)->limit(1)->first();
      $pc_due_amount = $pc_due_query->total_due_amount ;
      $now_pc_due_amount = $pc_due_amount - $pc_amount ;
      // updte pc due amount
      $data_pc_due_update     = array();
      $data_pc_due_update['total_due_amount'] = $now_pc_due_amount; 
      DB::table('pc_due')->where('pc_id',$pc_id)->update($data_pc_due_update);
        // pc ledger delete
        DB::table('pc_ledger')->where('branch_id',$this->branch_id)->where('cashbook_id',$cashbook_id)->where('invoice',$invoice)->where('year_invoice',$year_invoice)->where('daily_invoice_number',$daily_invoice)->where('invoice_type',2)->where('pc_id',$pc_id)->where('status',2)->delete();  
      }// pc ledger ended
       if($pc_id == '0'){
        $bill_pc_amount = 0 ;
       }else{
        $bill_pc_amount =  $pc_amount ;
       }
      #----------------------------- end pc information --------------------------------------------------------#
      #----------------------------- insert data into delete history table----------------------------------#
      $data_delete_history            = array();
      $data_delete_history['admin_type']  = 3 ;
      $data_delete_history['branch_id']   = $this->branch_id ;
      $data_delete_history['status']    = 10 ;
      $data_delete_history['tr_status']   = 1 ;
      $data_delete_history['before_pettycash_amt']  = $current_pettycash_amt ;
      $data_delete_history['after_pettycash_amt']   = $now_pettycash_amt ;
      $data_delete_history['bill_added_id']     = $bill_added_id ;
      $data_delete_history['bill_remove_id']    = $this->loged_id ;
      $data_delete_history['bill_tr_date']      = $bill_tr_date ;
      $data_delete_history['bill_remove_date']    = $this->rcdate ;
      $data_delete_history['bill_created_date']   = $bill_created_at ;
      $data_delete_history['bill_created_time']   = $bill_created_time ;
      $data_delete_history['bill_remove_time']    = $this->current_time ; 
      DB::table('delete_history')->insert($data_delete_history);
      #----------------------------- end insert  data into delete history table ----------------------------#
      #------------------------------ insert delete ipd bill history ----------------------------------------#
        // get last delete history
      $last_delete_history_query = DB::table('delete_history')->orderBy('id','desc')->limit(1)->first();
      $last_delete_history_id    = $last_delete_history_query->id ; 
      // insert into delete pathlogy bill
      $data_delete_ipd_bill_delete_bill     = array();
      $data_delete_ipd_bill_delete_bill['ipd_admission_id'] = $ipd_admission_id ;
      $data_delete_ipd_bill_delete_bill['delete_history_id'] = $last_delete_history_id ;
      $data_delete_ipd_bill_delete_bill['branch_id'] = $this->branch_id ;
      $data_delete_ipd_bill_delete_bill['cashbook_id'] = $cashbook_id ;
      $data_delete_ipd_bill_delete_bill['invoice_number'] = $invoice;
      $data_delete_ipd_bill_delete_bill['year_invoice_number'] = $year_invoice ;
      $data_delete_ipd_bill_delete_bill['daily_invoice_number'] = $daily_invoice;
      $data_delete_ipd_bill_delete_bill['patient_id'] = $patient_id ;
      $data_delete_ipd_bill_delete_bill['pc_id'] = $pc_id ;
      $data_delete_ipd_bill_delete_bill['room_type'] = $room_type ;
      $data_delete_ipd_bill_delete_bill['cabin_id'] = $cabin_room_id_is_used ;
      $data_delete_ipd_bill_delete_bill['ward_bed_id'] = $ward_bed_id_used ;
      $data_delete_ipd_bill_delete_bill['total_payable'] = $total_payable;
      $data_delete_ipd_bill_delete_bill['total_payment'] = $total_payment;
      $data_delete_ipd_bill_delete_bill['total_discount'] = $total_discount;
      $data_delete_ipd_bill_delete_bill['total_rebate'] = $total_rebate;
      $data_delete_ipd_bill_delete_bill['pc_amount'] = $bill_pc_amount;
      $data_delete_ipd_bill_delete_bill['status'] = 4 ;
      $data_delete_ipd_bill_delete_bill['admit_date'] = $admit_date;
      $data_delete_ipd_bill_delete_bill['end_date'] = $end_date ;
      $data_delete_ipd_bill_delete_bill['bill_added_id'] = $bill_added_id;
      $data_delete_ipd_bill_delete_bill['bill_remove_id'] = $this->loged_id ; 
      $data_delete_ipd_bill_delete_bill['bill_tr_date'] = $bill_tr_date ;
      $data_delete_ipd_bill_delete_bill['bill_remove_date'] = $this->rcdate ;
      $data_delete_ipd_bill_delete_bill['bill_created_date'] = $bill_created_at;
      $data_delete_ipd_bill_delete_bill['bill_created_time'] = $bill_created_time ;
      $data_delete_ipd_bill_delete_bill['bill_remove_time'] = $this->current_time;
      DB::table('delete_ipd_bill')->insert($data_delete_ipd_bill_delete_bill);
      #----------------------------- end insert deleret ipd bill history ------------------------------------#
      #----------------------------- update tbl ipd admission ------------------------------------------------#
        // Update admit status
       $data_admit_status_update             = array();
       $data_admit_status_update['end_time'] = '' ;
       $data_admit_status_update['end_date'] = '' ;
       $data_admit_status_update['status']   = 0 ;
       DB::table('tbl_ipd_admission')->where('branch_id',$this->branch_id)->where('id',$ipd_admission_id)->where('patient_id',$patient_id)->update($data_admit_status_update);
      #----------------------------- end update tbl ipd admission ---------------------------------------------#
      #----------------------------- start update ipd cabin bed history----------------------------------------#
      $data_bed_cabin_status_update = array();
      $data_bed_cabin_status_update['end_time'] = '' ;
      $data_bed_cabin_status_update['end_date'] = '' ;
      $data_bed_cabin_status_update['booked_status'] = 0 ;

      if($room_type == '1'){
      // get cabin room
      $booked_cabin_query_release = DB::table('tbl_ipd_cabin_bed_history')->where('branch_id',$this->branch_id)->where('ipd_admission_id',$ipd_admission_id)->where('patient_id',$patient_id)->where('room_type',1)->where('status',1)->first();
      $cabin_room_release = $booked_cabin_query_release->cabin_id ;
      DB::table('tbl_ipd_cabin_bed_history')->where('branch_id',$this->branch_id)->where('ipd_admission_id',$ipd_admission_id)->where('cabin_id',$cabin_room_release)->where('patient_id',$patient_id)->where('room_type',1)->where('status',1)->update($data_bed_cabin_status_update);
      // update cabin room table
      $data_cabin_room_update_status = array();
      $data_cabin_room_update_status['booked_status'] = 1 ;
      DB::table('tbl_cabin_room')->where('id',$cabin_room_release)->update($data_cabin_room_update_status);
     }elseif($room_type == '2'){
       $booked_bed_query_release = DB::table('tbl_ipd_cabin_bed_history')->where('branch_id',$this->branch_id)->where('ipd_admission_id',$ipd_admission_id)->where('patient_id',$patient_id)->where('room_type',2)->where('status',1)->first();
       $ward_bed_relase = $booked_bed_query_release->ward_bed_id ;
       DB::table('tbl_ipd_cabin_bed_history')->where('branch_id',$this->branch_id)->where('ipd_admission_id',$ipd_admission_id)->where('ward_bed_id',$ward_bed_relase)->where('patient_id',$patient_id)->where('room_type',2)->where('status',1)->update($data_bed_cabin_status_update); 
       // update bed room table
      $data_ward_bed_update_status = array();;
      $data_ward_bed_update_status['booked_status'] = 1 ;
      DB::table('tbl_ward_bed')->where('id',$ward_bed)->update($data_ward_bed_update_status);
     }
    #---------------------------- end start update ipd cabin bed history -----------------------------------#
    
    #------------------------------- delete tbl ipd ledger -------------------------------------------------#
      DB::table('tbl_ipd_ledger')->where('branch_id',$this->branch_id)->where('ipd_admission_id',$ipd_admission_id)->where('service_type',5)->where('service_id',$bill_id)->where('service_invoice',$invoice)->delete();
      DB::table('tbl_ipd_ledger')->where('branch_id',$this->branch_id)->where('ipd_admission_id',$ipd_admission_id)->where('service_type',6)->where('service_id',$bill_id)->where('service_invoice',$invoice)->delete();
    #------------------------------ delete tbl ipd clear bill -----------------------------------------------#
    #------------------------------ end delete tbl ipd clear bill --------------------------------------------#
      DB::table('tbl_ipd_clear_bill')->where('branch_id',$this->branch_id)->where('ipd_admission_id',$ipd_admission_id)->where('id',$bill_id)->where('invoice',$invoice)->where('year_invoice',$year_invoice)->where('daily_invoice',$daily_invoice)->where('cashbook_id',$cashbook_id)->delete(); 

    Session::put('succes','Thanks , IPD Clearence Bill Deleted Successfully');
    return Redirect::to('cashierIpdClearanceBillReport');
    #------------------------------- end delete tbl ipd ledger ---------------------------------------------#
    }
    #------------------------------- END DELETE IPD CLEARENCE BILL ------------------------------------------#
    #------------------------------- START DELETE OT BOOKING ------------------------------------------------#
    // cashier delete ot boooking
    public function cashierDeleteOTBooking($booking_id , $invoice , $year_invoice , $daily_invoice , $cashbook_id)
    {
       // check ot clerance bill
       $count_ot_clearence = DB::table('tbl_ot_clear_bill')->where('branch_id',$this->branch_id)->where('ot_booking_id',$booking_id)->count();
       if($count_ot_clearence > 0){
        Session::put('failed','Sorry ! OT Clearence Bill Already Created Of This OT Booking Patient. Delete OT Clearence Bill Then Try To Delete OT Booking Bill');
        return Redirect::to('cashierOTBookingBillReport');
        exit();
       }
       // count ot service bll
      $count_ot_service_bill = DB::table('tbl_ot_service_bill')->where('branch_id',$this->branch_id)->where('ot_booking_id',$booking_id)->count();
       if($count_ot_service_bill > 0){
        Session::put('failed','Sorry ! OT Service Bill Already Created Of This OT Booking Patient. Delete OT Service Bill Then Try To Delete OT Booking Bill');
        return Redirect::to('cashierOTBookingBillReport');
        exit();
       }
      $count_ot_staff_bill = DB::table('tbl_ot_serjeun_staff_bill')->where('branch_id',$this->branch_id)->where('ot_booked_id',$booking_id)->count();
       if($count_ot_staff_bill > 0){
        Session::put('failed','Sorry ! OT Surjeon And Staffs Bill Already Created Of This OT Booking Patient. Delete OT Surjeon And Staffs Bill Then Try To Delete OT Booking Bill');
        return Redirect::to('cashierOTBookingBillReport');
        exit();
       }
       // tbl ot ledger
       $delete_count = DB::table('tbl_ot_ledger')->where('branch_id',$this->branch_id)->where('ot_booking_id',$booking_id)->whereNotIn('service_type',[1])->count();
       if($delete_count > 0){
        Session::put('failed','Sorry ! Create Another Transaction Of This OT Patient. Manualy Check That And Delete Anothers Transaction Bill Then Try To Delete OT Booking Bill');
        return Redirect::to('cashierOTBookingBillReport');
        exit();
       }

       #----------------------------------------- get information from otb bill ----------------------------------#
        $ot_ledger = DB::table('tbl_ot_ledger')->where('branch_id',$this->branch_id)->where('ot_booking_id',$booking_id)->where('service_type',1)->where('service_id',$booking_id)->where('service_invoice',$invoice)->first();

      $total_payable   = $ot_ledger->payable_amount ;
      $total_discount  = $ot_ledger->discount ;
      $total_rebate    = $ot_ledger->rebate ;
      $total_payment   = $ot_ledger->payment_amount ;

      $check_pettycash_amount = DB::table('pettycash')->where('branch_id',$this->branch_id)->where('type',3)->limit(1)->first();
      // petty cash check
      $check_current_pettycash_amt = $check_pettycash_amount->pettycash_amount ;
      if($check_current_pettycash_amt < $total_payment){
        Session::put('failed','Sorry ! Pettycash Amount Small Than OT Booking Advance Payment Amount. Please Delete This OPD Booking After Available Pettycash');
        return Redirect::to('cashierOTBookingBillReport');
        exit();
      }
      // get ot booking info
     $ot_booking_info = DB::table('tbl_ot_booking')->where('branch_id',$this->branch_id)->where('id',$booking_id)->where('invoice',$invoice)->where('year_invoice',$year_invoice)->where('daily_invoice',$daily_invoice)->where('cashbook_id',$cashbook_id)->first();
      $bill_added_id     = $ot_booking_info->added_id ;
      $bill_tr_date      = $ot_booking_info->booking_date ;
      $bill_created_time = $ot_booking_info->created_time ;
      $bill_created_at   = $ot_booking_info->created_at ;
      $booking_status    = $ot_booking_info->status ;
      $patient_id        = $ot_booking_info->patient_id ;
      $ot_type           = $ot_booking_info->ot_type ;
       #----------------------------------------- end get information from otb bill --------------------------------#
      // delete cashbook
      DB::table('cashbook')->where('id',$cashbook_id)->delete();
      #----------------------------------- update pettycash ------------------------------------#
      $pettycash_amount = DB::table('pettycash')->where('branch_id',$this->branch_id)->where('type',3)->limit(1)->first();
      // petty cash update
      $current_pettycash_amt = $pettycash_amount->pettycash_amount ;
      $now_pettycash_amt     = $current_pettycash_amt - $total_payment ;
      // update pettycash amount
      $data_update_pettycash                      = array();
      $data_update_pettycash['pettycash_amount']  = $now_pettycash_amt; 
      DB::table('pettycash')->where('branch_id',$this->branch_id)->where('type',3)->update($data_update_pettycash);
      #----------------------------------- end update pettycash ------------------------------------ #
      #----------------------------------- insert date into delete history---------------------------#
      $data_delete_history                = array();
      $data_delete_history['admin_type']  = 3 ;
      $data_delete_history['branch_id']   = $this->branch_id ;
      $data_delete_history['status']      = 11 ;
      $data_delete_history['tr_status']   = 1 ;
      $data_delete_history['before_pettycash_amt']  = $current_pettycash_amt ;
      $data_delete_history['after_pettycash_amt']   = $now_pettycash_amt ;
      $data_delete_history['bill_added_id']     = $bill_added_id ;
      $data_delete_history['bill_remove_id']    = $this->loged_id ;
      $data_delete_history['bill_tr_date']      = $bill_tr_date ;
      $data_delete_history['bill_remove_date']    = $this->rcdate ;
      $data_delete_history['bill_created_date']   = $bill_created_at ;
      $data_delete_history['bill_created_time']   = $bill_created_time ;
      $data_delete_history['bill_remove_time']    = $this->current_time ; 
      DB::table('delete_history')->insert($data_delete_history);
      #----------------------------------- end insert data into delete history -----------------------#
      // get last delete history
      $last_delete_history_query = DB::table('delete_history')->orderBy('id','desc')->limit(1)->first();
      $last_delete_history_id    = $last_delete_history_query->id ; 

      $delete_ot_bill     = array();
      $delete_ot_bill['ot_booking_id'] = $booking_id; 
      $delete_ot_bill['delete_history_id'] = $last_delete_history_id  ; 
      $delete_ot_bill['branch_id'] = $this->branch_id ; 
      $delete_ot_bill['cashbook_id'] = $cashbook_id ; 
      $delete_ot_bill['invoice_number'] = $invoice ; 
      $delete_ot_bill['year_invoice_number'] = $year_invoice ; 
      $delete_ot_bill['daily_invoice_number'] = $daily_invoice ; 
      $delete_ot_bill['doctor_id'] = ''; 
      $delete_ot_bill['patient_id'] = $patient_id; 
      $delete_ot_bill['pc_id'] = ''; 
      $delete_ot_bill['ot_type_id'] = $ot_type; 
      $delete_ot_bill['total_payable'] = $total_payable; 
      $delete_ot_bill['total_payment'] = $total_payment ; 
      $delete_ot_bill['total_discount'] = $total_discount ; 
      $delete_ot_bill['total_rebate'] = $total_rebate; 
      $delete_ot_bill['pc_amount'] = ''; 
      $delete_ot_bill['service_id'] = '' ;
      $delete_ot_bill['status'] = 1 ; 
      $delete_ot_bill['booking_date'] = ''; 
      $delete_ot_bill['end_date'] = '' ; 
      $delete_ot_bill['booking_status'] =  $booking_status ; 
      $delete_ot_bill['bill_added_id'] = $bill_added_id;
      $delete_ot_bill['bill_remove_id'] = $this->loged_id ; 
      $delete_ot_bill['bill_tr_date'] = $bill_tr_date ;
      $delete_ot_bill['bill_remove_date'] = $this->rcdate ;
      $delete_ot_bill['bill_created_date'] = $bill_created_at;
      $delete_ot_bill['bill_created_time'] = $bill_created_time ;
      $delete_ot_bill['bill_remove_time'] = $this->current_time;
      DB::table('delete_ot_bill')->insert($delete_ot_bill);
      #------------------------------- remove delete ot ledger -----------------------------#
      DB::table('tbl_ot_ledger')->where('branch_id',$this->branch_id)->where('ot_booking_id',$booking_id)->where('service_type',1)->where('service_id',$booking_id)->where('service_invoice',$invoice)->delete();
      #------------------------------- end remove delete ot ledger --------------------------#
      #------------------------------- delet tbl ot booking ---------------------------------#
      DB::table('tbl_ot_booking')->where('branch_id',$this->branch_id)->where('id',$booking_id)->where('invoice',$invoice)->where('year_invoice',$year_invoice)->where('daily_invoice',$daily_invoice)->where('cashbook_id',$cashbook_id)->delete();
      #------------------------------- delete tbl ot booking ---------------------------------#
        Session::put('succes','Thanks , OT Booking Deleted Successfully');
        return Redirect::to('cashierOTBookingBillReport');

    }// end function
    #------------------------------ END START DELETE OT BOOKING ---------------------------------------------#
    #------------------------------ START DELET OT STAFF BILL -----------------------------------------------#
    public function cashierDeleteOTStaffPosting($bill_id , $booking_id , $invoice , $year_invoice , $daily_invoice)
    {
      // check count ot clearec completed
      $count_ot_clearence_bill = DB::table('tbl_ot_clear_bill')->where('branch_id',$this->branch_id)->where('ot_booking_id',$booking_id)->count();
      if($count_ot_clearence_bill > 0){
        Session::put('failed','Sorry ! OT Clearence Bill Already Created . Delete OT Clearence Bill Then Try To Delete OT Surjeon And Staffs Bill');
        return Redirect::to('cashierOTSurjenBillReport');
        exit(); 
      }
     $ot_booking_tr_value = DB::table('tbl_ot_ledger')->where('branch_id',$this->branch_id)->where('service_type',2)->where('ot_booking_id',$booking_id)->first();
     $total_payable  = $ot_booking_tr_value->payable_amount;
     $total_discount = $ot_booking_tr_value->discount ;

     $ot_staff_bill_info = DB::table('tbl_ot_serjeun_staff_bill')->where('branch_id',$this->branch_id)->where('ot_booked_id',$booking_id)->where('id',$bill_id)->where('invoice',$invoice)->where('year_invoice',$year_invoice)->where('daily_invoice',$daily_invoice)->first();

      $bill_added_id     = $ot_staff_bill_info->added_id ;
      $bill_tr_date      = $ot_staff_bill_info->ot_date ;
      $bill_created_time = $ot_staff_bill_info->ot_time ;
      $bill_created_at   = $ot_staff_bill_info->created_at ;
      $patient_id        = $ot_staff_bill_info->patient_id ;
      //staff info
      $staff_info_of_bill = DB::table('tbl_ot_staff_info')->where('branch_id',$this->branch_id)->where('ot_booked_id',$booking_id)->get();
       $itam = array();
      foreach ($staff_info_of_bill as $value_iteam) {
       $itam[] = $value_iteam->staff_type.'-'.$value_iteam->staff_id ;
      }
      $item_implode = implode(',', $itam);

      $pettycash_amount = DB::table('pettycash')->where('branch_id',$this->branch_id)->where('type',3)->limit(1)->first();
      // petty cash update
      $current_pettycash_amt = $pettycash_amount->pettycash_amount ;
      #----------------------------------- delete history -------------------------------#
      #----------------------------------- insert date into delete history---------------------------#
      $data_delete_history                = array();
      $data_delete_history['admin_type']  = 3 ;
      $data_delete_history['branch_id']   = $this->branch_id ;
      $data_delete_history['status']      = 12 ;
      $data_delete_history['tr_status']   = 1 ;
      $data_delete_history['before_pettycash_amt']  = $current_pettycash_amt ;
      $data_delete_history['after_pettycash_amt']   = $current_pettycash_amt ;
      $data_delete_history['bill_added_id']     = $bill_added_id ;
      $data_delete_history['bill_remove_id']    = $this->loged_id ;
      $data_delete_history['bill_tr_date']      = $bill_tr_date ;
      $data_delete_history['bill_remove_date']    = $this->rcdate ;
      $data_delete_history['bill_created_date']   = $bill_created_at ;
      $data_delete_history['bill_created_time']   = $bill_created_time ;
      $data_delete_history['bill_remove_time']    = $this->current_time ; 
      DB::table('delete_history')->insert($data_delete_history);
      #----------------------------------- end insert data into delete history -----------------------#
      // get last delete history
      $last_delete_history_query = DB::table('delete_history')->orderBy('id','desc')->limit(1)->first();
      $last_delete_history_id    = $last_delete_history_query->id ; 
      #----------------------------------- end delete history ---------------------------#
      $delete_ot_bill     = array();
      $delete_ot_bill['ot_booking_id'] = $booking_id; 
      $delete_ot_bill['delete_history_id'] = $last_delete_history_id  ; 
      $delete_ot_bill['branch_id'] = $this->branch_id ; 
      $delete_ot_bill['cashbook_id'] = ''; 
      $delete_ot_bill['invoice_number'] = $invoice ; 
      $delete_ot_bill['year_invoice_number'] = $year_invoice ; 
      $delete_ot_bill['daily_invoice_number'] = $daily_invoice ; 
      $delete_ot_bill['doctor_id'] = $item_implode; 
      $delete_ot_bill['patient_id'] = $patient_id; 
      $delete_ot_bill['pc_id'] = ''; 
      $delete_ot_bill['ot_type_id'] = ''; 
      $delete_ot_bill['total_payable'] = $total_payable; 
      $delete_ot_bill['total_payment'] = '' ; 
      $delete_ot_bill['total_discount'] = $total_discount ; 
      $delete_ot_bill['total_rebate'] = ''; 
      $delete_ot_bill['pc_amount'] = ''; 
      $delete_ot_bill['service_id'] = '' ;
      $delete_ot_bill['status'] = 2 ; 
      $delete_ot_bill['booking_date'] = ''; 
      $delete_ot_bill['end_date'] = '' ; 
      $delete_ot_bill['booking_status'] =  '' ; 
      $delete_ot_bill['bill_added_id'] = $bill_added_id;
      $delete_ot_bill['bill_remove_id'] = $this->loged_id ; 
      $delete_ot_bill['bill_tr_date'] = $bill_tr_date ;
      $delete_ot_bill['bill_remove_date'] = $this->rcdate ;
      $delete_ot_bill['bill_created_date'] = $bill_created_at;
      $delete_ot_bill['bill_created_time'] = $bill_created_time ;
      $delete_ot_bill['bill_remove_time'] = $this->current_time;
      DB::table('delete_ot_bill')->insert($delete_ot_bill);
      #--------------------------- delete tbl ot serjoun staff bill----------------------------#
      DB::table('tbl_ot_serjeun_staff_bill')->where('branch_id',$this->branch_id)->where('ot_booked_id',$booking_id)->delete();
      #--------------------------- end delete tbl ot serjount staff bill ----------------------#
      #--------------------------- delete tbl - ot staff type amount --------------------------#
      DB::table('tbl_ot_staff_type_amount')->where('branch_id',$this->branch_id)->where('ot_booked_id',$booking_id)->delete();
      #-------------------------- end delete tbl -ot staff type amount -------------------------#
      #--------------------------- delete tbl - ot-staff info --------------------------------#
      DB::table('tbl_ot_staff_info')->where('branch_id',$this->branch_id)->where('ot_booked_id',$booking_id)->delete();

       #--------------------------- end delete tbl - ot-staff info --------------------------------#
      #--------------------------- delete tbl - ot-staff info --------------------------------#
        DB::table('tbl_ot_ledger')->where('branch_id',$this->branch_id)->where('service_type',2)->where('ot_booking_id',$booking_id)->delete();
      # -------------------------- delet ot ledger --------------------------------------------#
       Session::put('succes','Thanks , OT Surjeon And Staff Bill Deleted Successfully');
        return Redirect::to('cashierOTSurjenBillReport');

    }
    #------------------------------ END START DELETE OT STAFF BILL -------------------------------------------#
    #------------------------------ START DELETE CASHIER OT BILL SERVICE -------------------------------------#
   public function cashierDeleteOTService($bill_id , $booking_id , $invoice , $year_invoice , $daily_invoice)
   {
          // check count ot clearec completed
      $count_ot_clearence_bill = DB::table('tbl_ot_clear_bill')->where('branch_id',$this->branch_id)->where('ot_booking_id',$booking_id)->count();
      if($count_ot_clearence_bill > 0){
        Session::put('failed','Sorry ! OT Clearence Bill Already Created . Delete OT Clearence Bill Then Try To Delete OT Surjeon And Staffs Bill');
        return Redirect::to('cashierOTSurjenBillReport');
        exit(); 
      }
      $ot_booking_tr_value = DB::table('tbl_ot_ledger')->where('branch_id',$this->branch_id)->where('service_type',3)->where('ot_booking_id',$booking_id)->where('service_id',$bill_id)->where('service_invoice',$invoice)->first();
     $total_payable  = $ot_booking_tr_value->payable_amount;
     $total_discount = $ot_booking_tr_value->discount ;

    $ot_service_bill_info = DB::table('tbl_ot_service_bill')->where('branch_id',$this->branch_id)->where('ot_booking_id',$booking_id)->where('id',$bill_id)->where('invoice',$invoice)->where('year_invoice',$year_invoice)->where('daily_invoice',$daily_invoice)->first();

      $bill_added_id     = $ot_service_bill_info->added_id ;
      $bill_tr_date      = $ot_service_bill_info->bill_date ;
      $bill_created_time = $ot_service_bill_info->bill_time ;
      $bill_created_at   = $ot_service_bill_info->created_at ;
      $patient_id        = $ot_service_bill_info->patient_id ;
      // service bill item

       $service_item = DB::table('tbl_ot_service_bill_item')->where('branch_id',$this->branch_id)->where('ot_booking_id',$booking_id)->where('invoice_number',$invoice)->where('year_invoice_number',$year_invoice)->where('daily_invoice_number',$daily_invoice)->get();
       $itam = array();
      foreach ($service_item as $value_iteam) {
       $itam[] = $value_iteam->service_id ;
      }
      $item_implode = implode(',', $itam);

      $pettycash_amount = DB::table('pettycash')->where('branch_id',$this->branch_id)->where('type',3)->limit(1)->first();
      // petty cash update
      $current_pettycash_amt = $pettycash_amount->pettycash_amount ;
      #----------------------------------- insert date into delete history---------------------------#
      $data_delete_history                = array();
      $data_delete_history['admin_type']  = 3 ;
      $data_delete_history['branch_id']   = $this->branch_id ;
      $data_delete_history['status']      = 13 ;
      $data_delete_history['tr_status']   = 1 ;
      $data_delete_history['before_pettycash_amt']  = $current_pettycash_amt ;
      $data_delete_history['after_pettycash_amt']   = $current_pettycash_amt ;
      $data_delete_history['bill_added_id']     = $bill_added_id ;
      $data_delete_history['bill_remove_id']    = $this->loged_id ;
      $data_delete_history['bill_tr_date']      = $bill_tr_date ;
      $data_delete_history['bill_remove_date']    = $this->rcdate ;
      $data_delete_history['bill_created_date']   = $bill_created_at ;
      $data_delete_history['bill_created_time']   = $bill_created_time ;
      $data_delete_history['bill_remove_time']    = $this->current_time ; 
      DB::table('delete_history')->insert($data_delete_history);
      #----------------------------------- end insert data into delete history -----------------------#
      // get last delete history
      $last_delete_history_query = DB::table('delete_history')->orderBy('id','desc')->limit(1)->first();
      $last_delete_history_id    = $last_delete_history_query->id ; 
        $delete_ot_bill     = array();
      $delete_ot_bill['ot_booking_id'] = $booking_id; 
      $delete_ot_bill['delete_history_id'] = $last_delete_history_id  ; 
      $delete_ot_bill['branch_id'] = $this->branch_id ; 
      $delete_ot_bill['cashbook_id'] = ''; 
      $delete_ot_bill['invoice_number'] = $invoice ; 
      $delete_ot_bill['year_invoice_number'] = $year_invoice ; 
      $delete_ot_bill['daily_invoice_number'] = $daily_invoice ; 
      $delete_ot_bill['doctor_id'] = ''; 
      $delete_ot_bill['patient_id'] = $patient_id; 
      $delete_ot_bill['pc_id'] = ''; 
      $delete_ot_bill['ot_type_id'] = ''; 
      $delete_ot_bill['total_payable'] = $total_payable; 
      $delete_ot_bill['total_payment'] = '' ; 
      $delete_ot_bill['total_discount'] = $total_discount ; 
      $delete_ot_bill['total_rebate'] = ''; 
      $delete_ot_bill['pc_amount'] = ''; 
      $delete_ot_bill['service_id'] = $item_implode ;
      $delete_ot_bill['status'] = 3 ; 
      $delete_ot_bill['booking_date'] = ''; 
      $delete_ot_bill['end_date'] = '' ; 
      $delete_ot_bill['booking_status'] =  '' ; 
      $delete_ot_bill['bill_added_id'] = $bill_added_id;
      $delete_ot_bill['bill_remove_id'] = $this->loged_id ; 
      $delete_ot_bill['bill_tr_date'] = $bill_tr_date ;
      $delete_ot_bill['bill_remove_date'] = $this->rcdate ;
      $delete_ot_bill['bill_created_date'] = $bill_created_at;
      $delete_ot_bill['bill_created_time'] = $bill_created_time ;
      $delete_ot_bill['bill_remove_time'] = $this->current_time;
      DB::table('delete_ot_bill')->insert($delete_ot_bill);

      DB::table('tbl_ot_service_bill_item')->where('branch_id',$this->branch_id)->where('ot_booking_id',$booking_id)->where('invoice_number',$invoice)->where('year_invoice_number',$year_invoice)->where('daily_invoice_number',$daily_invoice)->delete();
      DB::table('tbl_ot_ledger')->where('branch_id',$this->branch_id)->where('service_type',3)->where('ot_booking_id',$booking_id)->where('service_id',$bill_id)->where('service_invoice',$invoice)->delete();
      DB::table('tbl_ot_service_bill')->where('branch_id',$this->branch_id)->where('ot_booking_id',$booking_id)->where('id',$bill_id)->where('invoice',$invoice)->where('year_invoice',$year_invoice)->where('daily_invoice',$daily_invoice)->delete();
        Session::put('succes','Thanks , OT Service Bill Deleted Successfully');
        return Redirect::to('cashierOTServiceBillReport');
   }
    #------------------------------ END START DELETE CASHIER OT BILL SERVICE ---------------------------------#
    #------------------------------ DELETE OT CLEARNCE BILL --------------------------------------------------#
     public function cashierDeleteOTClearence($bill_id , $booking_id , $invoice , $year_invoice , $daily_invoice, $cashbook_id)
     {
       // amount distribution of this ot by manager
      $count_amount_distribution = DB::table('tbl_ot_distribution_amount')->where('branch_id',$this->branch_id)->where('ot_booked_id',$booking_id)->count();
      if($count_amount_distribution > 0){
        Session::put('failed','Sorry ! Already Amount Distribuition Into OT Surgeon And Staffs Of This OT. You Can Not Delete This OT Clearence Bill. Contact With Manager To Delete OT Amount Distribuition Amount Then Delete This OT Cleearence Bill');
        return Redirect::to('cashierOTClearanceBillReport');
        exit(); 
      }

      $count_amount_distribution = DB::table('tbl_ot_clear_bill')->where('branch_id',$this->branch_id)->where('id',$bill_id)->where('ot_booking_id',$booking_id)->where('cashbook_id',$cashbook_id)->where('ot_amount_distribution','1')->count();
      if($count_amount_distribution > 0){
        Session::put('failed','Sorry ! Already Amount Distribuition Into OT Surgeon And Staffs Of This OT. You Can Not Delete This OT Clearence Bill. Contact With Manager To Delete OT Amount Distribuition Amount Then Delete This OT Cleearence Bill');
        return Redirect::to('cashierOTClearanceBillReport');
        exit(); 
      }
      $ot_ledger_info = DB::table('tbl_ot_ledger')->where('branch_id',$this->branch_id)->where('ot_booking_id',$booking_id)->whereNotIn('service_type',[4])->get();
      $total_payable_without_clear  = 0 ;
      $total_discount_without_clear = 0 ;
      $total_rebate_without_clear   = 0 ;
      $total_payment_without_clear  = 0 ;

      foreach ($ot_ledger_info as $ledger_value) {
         $total_payable_without_clear  = $total_payable_without_clear + $ledger_value->payable_amount ;
         $total_discount_without_clear = $total_discount_without_clear + $ledger_value->discount ;
         $total_rebate_without_clear   = $total_rebate_without_clear + $ledger_value->rebate ;
         $total_payment_without_clear  = $total_payment_without_clear + $ledger_value->payment_amount ;

      }
      $previus_discountAnd_rebate  = $total_discount_without_clear + $total_rebate_without_clear ;
      $total_payment_with_discount = $total_payment_without_clear + $previus_discountAnd_rebate ;
      $total_payable  = $total_payable_without_clear - $total_payment_with_discount ;
      // service type 4 info
       $ot_clear_ledger_info = DB::table('tbl_ot_ledger')->where('branch_id',$this->branch_id)->where('ot_booking_id',$booking_id)->where('service_type',4)->first();

      $total_discount  = $ot_clear_ledger_info->discount ;
      $total_rebate    = $ot_clear_ledger_info->rebate ;
      $total_payment   = $ot_clear_ledger_info->payment_amount ;

      $check_pettycash_amount = DB::table('pettycash')->where('branch_id',$this->branch_id)->where('type',3)->limit(1)->first();
      // petty cash check
      $check_current_pettycash_amt = $check_pettycash_amount->pettycash_amount ;
      if($check_current_pettycash_amt < $total_payment){
        Session::put('failed','Sorry ! Pettycash Amount Small Than OT Clear Bill Payment Amount. Please Delete This OT Clearence Bill After Available Pettycash');
        return Redirect::to('cashierOTClearanceBillReport');
        exit();
      }
      // ot clearence bill info
      $ot_clear_bill_info = DB::table('tbl_ot_clear_bill')->where('branch_id',$this->branch_id)->where('id',$bill_id)->where('ot_booking_id',$booking_id)->where('cashbook_id',$cashbook_id)->first();

      $bill_added_id     = $ot_clear_bill_info->added_id ;
      $bill_tr_date      = $ot_clear_bill_info->bill_date ;
      $bill_created_time = $ot_clear_bill_info->bill_time ;
      $bill_created_at   = $ot_clear_bill_info->created_at ;
      $patient_id        = $ot_clear_bill_info->patient_id ;
      $pc_id             = $ot_clear_bill_info->pc_id ;
      // delete cashbook id
      DB::table('cashbook')->where('id',$cashbook_id)->delete();
      $pettycash_amount = DB::table('pettycash')->where('branch_id',$this->branch_id)->where('type',3)->limit(1)->first();
      // petty cash update
      $current_pettycash_amt = $pettycash_amount->pettycash_amount ;
      $now_pettycash_amt     = $current_pettycash_amt - $total_payment ;
      // update pettycash amount
      $data_update_pettycash                      = array();
      $data_update_pettycash['pettycash_amount']  = $now_pettycash_amt; 
      DB::table('pettycash')->where('branch_id',$this->branch_id)->where('type',3)->update($data_update_pettycash);
      // pc info
      if($pc_id != '0'){
        // get pc amount of this invoice
        $pc_amt_query = DB::table('pc_ledger')->where('branch_id',$this->branch_id)->where('cashbook_id',$cashbook_id)->where('invoice',$invoice)->where('year_invoice',$year_invoice)->where('daily_invoice_number',$daily_invoice)->where('invoice_type',3)->where('pc_id',$pc_id)->where('status',3)->first();
            // reduce pc amount
           // update pc due
      $pc_amount = $pc_amt_query->payable_amount ;
      $pc_due_query = DB::table('pc_due')->where('pc_id',$pc_id)->limit(1)->first();
      $pc_due_amount = $pc_due_query->total_due_amount ;
      $now_pc_due_amount = $pc_due_amount - $pc_amount ;
      // updte pc due amount
      $data_pc_due_update     = array();
      $data_pc_due_update['total_due_amount'] = $now_pc_due_amount; 
      DB::table('pc_due')->where('pc_id',$pc_id)->update($data_pc_due_update);
        // pc ledger delete
        DB::table('pc_ledger')->where('branch_id',$this->branch_id)->where('cashbook_id',$cashbook_id)->where('invoice',$invoice)->where('year_invoice',$year_invoice)->where('daily_invoice_number',$daily_invoice)->where('invoice_type',3)->where('pc_id',$pc_id)->where('status',3)->delete();  
      }// pc ledger ended
       if($pc_id == '0'){
        $bill_pc_amount = 0 ;
       }else{
        $bill_pc_amount =  $pc_amount ;
       }
       // update tbl ot bookin
          #--------------------------- UPDAT OT BOOKING STATUS----------------------------#
   $data_ot_booking_update              = array();
   $data_ot_booking_update['status']    = 0 ;
   $data_ot_booking_update['end_date']  = '' ;
   DB::table('tbl_ot_booking')->where('branch_id',$this->branch_id)->where('id',$booking_id)->where('patient_id',$patient_id)->where('status',1)->update($data_ot_booking_update );
   #--------------------------- END UPDATE OT BOOKING STATUS------------------------#
   #----------------------------------- insert date into delete history---------------------------#
      $data_delete_history                = array();
      $data_delete_history['admin_type']  = 3 ;
      $data_delete_history['branch_id']   = $this->branch_id ;
      $data_delete_history['status']      = 14 ;
      $data_delete_history['tr_status']   = 1 ;
      $data_delete_history['before_pettycash_amt']  = $current_pettycash_amt ;
      $data_delete_history['after_pettycash_amt']   = $now_pettycash_amt ;
      $data_delete_history['bill_added_id']     = $bill_added_id ;
      $data_delete_history['bill_remove_id']    = $this->loged_id ;
      $data_delete_history['bill_tr_date']      = $bill_tr_date ;
      $data_delete_history['bill_remove_date']    = $this->rcdate ;
      $data_delete_history['bill_created_date']   = $bill_created_at ;
      $data_delete_history['bill_created_time']   = $bill_created_time ;
      $data_delete_history['bill_remove_time']    = $this->current_time ; 
      DB::table('delete_history')->insert($data_delete_history);
      #----------------------------------- end insert data into delete history -----------------------#
       // get last delete history
      $last_delete_history_query = DB::table('delete_history')->orderBy('id','desc')->limit(1)->first();
      $last_delete_history_id    = $last_delete_history_query->id ; 
        $delete_ot_bill     = array();
      $delete_ot_bill['ot_booking_id'] = $booking_id; 
      $delete_ot_bill['delete_history_id'] = $last_delete_history_id  ; 
      $delete_ot_bill['branch_id'] = $this->branch_id ; 
      $delete_ot_bill['cashbook_id'] = $cashbook_id; 
      $delete_ot_bill['invoice_number'] = $invoice ; 
      $delete_ot_bill['year_invoice_number'] = $year_invoice ; 
      $delete_ot_bill['daily_invoice_number'] = $daily_invoice ; 
      $delete_ot_bill['doctor_id'] = ''; 
      $delete_ot_bill['patient_id'] = $patient_id; 
      $delete_ot_bill['pc_id'] = $pc_id; 
      $delete_ot_bill['ot_type_id'] = ''; 
      $delete_ot_bill['total_payable'] = $total_payable; 
      $delete_ot_bill['total_payment'] =  $total_payment ; 
      $delete_ot_bill['total_discount'] = $total_discount ; 
      $delete_ot_bill['total_rebate'] = $total_rebate; 
      $delete_ot_bill['pc_amount'] = $bill_pc_amount; 
      $delete_ot_bill['service_id'] = '' ;
      $delete_ot_bill['status'] = 4 ; 
      $delete_ot_bill['booking_date'] = ''; 
      $delete_ot_bill['end_date'] = '' ; 
      $delete_ot_bill['booking_status'] =  '' ; 
      $delete_ot_bill['bill_added_id'] = $bill_added_id;
      $delete_ot_bill['bill_remove_id'] = $this->loged_id ; 
      $delete_ot_bill['bill_tr_date'] = $bill_tr_date ;
      $delete_ot_bill['bill_remove_date'] = $this->rcdate ;
      $delete_ot_bill['bill_created_date'] = $bill_created_at;
      $delete_ot_bill['bill_created_time'] = $bill_created_time ;
      $delete_ot_bill['bill_remove_time'] = $this->current_time;
      DB::table('delete_ot_bill')->insert($delete_ot_bill);
      #----------------------------- delete opd ledger ---------------------------#
      DB::table('tbl_ot_ledger')->where('branch_id',$this->branch_id)->where('ot_booking_id',$booking_id)->where('service_type',4)->delete();
      #--------------------------- end delete opd ledger ------------------------#
      DB::table('tbl_ot_clear_bill')->where('branch_id',$this->branch_id)->where('id',$bill_id)->where('ot_booking_id',$booking_id)->where('cashbook_id',$cashbook_id)->delete();
      Session::put('succes','Thanks , OT Clearence Bill Deleted Successfully');
      return Redirect::to('cashierOTClearanceBillReport');

     }
    #------------------------------ END DELETE OT CLEARNCE BILL -----------------------------------------------#
    #------------------------------ CASHIER DELETE PENDING CASH TRNASFER---------------------------------------#
    public function cashierDeletePendingCashTransfer($id , $status)
    {
      if($status == '1'){
        Session::put('failed','Sorry ! You Can Only Delete Pending Cash Transfer. Request To Manager To Delete This Transaction');
        return Redirect::to('cashierCashTransferReport');
        exit();
      }
      if($status == '2'){
        Session::put('failed','Sorry ! You Can Only Delete Pending Cash Transfer. Request To Manager To Delete This Transaction');
        return Redirect::to('cashierCashTransferReport');
        exit();
      }
      // get information of this balance transfer
      $info_query = DB::table('balance_transfer')->where('branch_id',$this->branch_id)->where('id',$id)->where('status',$status)->first();
      $bill_added_id     = $info_query->added_id ;
      $bill_tr_date      = $info_query->transfer_date ;
      $bill_created_time = $info_query->transfer_time ;
      $bill_created_at   = $info_query->created_at ;
      $transfer_amt   = $info_query->transfer_amount ;

      $pettycash_amount = DB::table('pettycash')->where('branch_id',$this->branch_id)->where('type',3)->limit(1)->first();
      // petty cash update
      $current_pettycash_amt = $pettycash_amount->pettycash_amount ;
      $now_pettycash_amt     = $pettycash_amount->pettycash_amount ;

      #----------------------------------- insert date into delete history---------------------------#
      $data_delete_history                = array();
      $data_delete_history['admin_type']  = 3 ;
      $data_delete_history['branch_id']   = $this->branch_id ;
      $data_delete_history['status']      = 15 ;
      $data_delete_history['tr_status']   = 1 ;
      $data_delete_history['before_pettycash_amt']  = $current_pettycash_amt ;
      $data_delete_history['after_pettycash_amt']   = $now_pettycash_amt ;
      $data_delete_history['bill_added_id']     = $bill_added_id ;
      $data_delete_history['bill_remove_id']    = $this->loged_id ;
      $data_delete_history['bill_tr_date']      = $bill_tr_date ;
      $data_delete_history['bill_remove_date']    = $this->rcdate ;
      $data_delete_history['bill_created_date']   = $bill_created_at ;
      $data_delete_history['bill_created_time']   = $bill_created_time ;
      $data_delete_history['bill_remove_time']    = $this->current_time ; 
      DB::table('delete_history')->insert($data_delete_history);
      #----------------------------------- end insert data into delete history -----------------------#
      // get last delete history
      $last_delete_history_query = DB::table('delete_history')->orderBy('id','desc')->limit(1)->first();
      $last_delete_history_id    = $last_delete_history_query->id ; 

      $delete_balance_tr = array();
      $delete_balance_tr['delete_history_id'] = $last_delete_history_id ;
      $delete_balance_tr['sender_cashbook_id'] = '' ;
      $delete_balance_tr['reciver_cashbook_id'] = '';
      $delete_balance_tr['branch_id'] = $this->branch_id ;
      $delete_balance_tr['transfer_amount'] = $transfer_amt ;
      $delete_balance_tr['status'] = $status ;
      $delete_balance_tr['bill_added_id'] = $bill_added_id;
      $delete_balance_tr['bill_remove_id'] = $this->loged_id ; 
      $delete_balance_tr['bill_tr_date'] = $bill_tr_date ;
      $delete_balance_tr['bill_remove_date'] = $this->rcdate ;
      $delete_balance_tr['bill_created_date'] = $bill_created_at;
      $delete_balance_tr['bill_created_time'] = $bill_created_time ;
      $delete_balance_tr['bill_remove_time'] = $this->current_time;
      DB::table('delete_balance_transfer')->insert($delete_balance_tr);
      DB::table('balance_transfer')->where('branch_id',$this->branch_id)->where('id',$id)->where('status',$status)->delete();
      Session::put('succes','Thanks , Pending Cash Transfer Deleted Successfully');
      return Redirect::to('cashierCashTransferReport');

    }
    #------------------------------ END CAHSIER DELETE PENDING CASH TRANSFER -----------------------------------#
    #------------------------------ MANAGER DELETE START -------------------------------------------------------#
    #------------------------------- manager purchase delete ---------------------------------------------------#
    public function managerPurchaseDelete($invoice , $cashbook_id)
    {
      // get purchas information
      $purchase_info_query = DB::table('purchase')->where('cashbook_id',$cashbook_id)->where('invoice',$invoice)->first();

      $bill_added_id     = $purchase_info_query->added_id ;
      $bill_tr_date      = $purchase_info_query->purchase_date ;
      $bill_created_at   = $purchase_info_query->created_at ;
      $total_price       = $purchase_info_query->total_price;
      $total_payment     = $purchase_info_query->total_payment ;
      $total_qty         = $purchase_info_query->total_quantity ;
      $memo              = $purchase_info_query->memo_no ;
      $supplier_id       = $purchase_info_query->supplier_id ;

      $was_supplier_due    = $total_price - $total_payment ;

      // purchase product item
      $product_item = DB::table('purchase_product')->where('branch_id',$this->branch_id)->where('cashbook_id',$cashbook_id)->where('invoice_number',$invoice)->get();
       $itam = array();
       $qty  = array();
       $price= array();
      foreach ($product_item as $value_iteam) {
       $itam[] = $value_iteam->product_id ;
       $qty[] = $value_iteam->total_quantity ;
       $price[] = $value_iteam->purchase_price ;
      }
      $item_implode   = implode(',', $itam);
      $qty_implode    = implode(',', $qty);
      $price_implode  = implode(',', $price);
      #----------------------------- pettycash amount ----------------------------------#
      #----------------------------------- update pettycash ------------------------------------#
      $pettycash_amount = DB::table('pettycash')->where('branch_id',$this->branch_id)->where('type',2)->limit(1)->first();
      // petty cash update
      $current_pettycash_amt = $pettycash_amount->pettycash_amount ;
      $now_pettycash_amt     = $current_pettycash_amt + $total_payment ;
      // update pettycash amount
      $data_update_pettycash                      = array();
      $data_update_pettycash['pettycash_amount']  = $now_pettycash_amt; 
      DB::table('pettycash')->where('branch_id',$this->branch_id)->where('type',2)->update($data_update_pettycash);
      #----------------------------------- end update pettycash ------------------------------------ #
      #----------------------------------- insert date into delete history---------------------------#
      $data_delete_history                = array();
      $data_delete_history['admin_type']  = 2 ;
      $data_delete_history['branch_id']   = $this->branch_id ;
      $data_delete_history['status']      = 16 ;
      $data_delete_history['tr_status']   = 1 ;
      $data_delete_history['before_pettycash_amt']  = $current_pettycash_amt ;
      $data_delete_history['after_pettycash_amt']   = $now_pettycash_amt ;
      $data_delete_history['bill_added_id']     = $bill_added_id ;
      $data_delete_history['bill_remove_id']    = $this->loged_id ;
      $data_delete_history['bill_tr_date']      = $bill_tr_date ;
      $data_delete_history['bill_remove_date']    = $this->rcdate ;
      $data_delete_history['bill_created_date']   = $bill_created_at ;
      $data_delete_history['bill_remove_time']    = $this->current_time ; 
      DB::table('delete_history')->insert($data_delete_history);
      #----------------------------------- end insert data into delete history -----------------------#
      // get last delete history
      $last_delete_history_query = DB::table('delete_history')->orderBy('id','desc')->limit(1)->first();
      $last_delete_history_id    = $last_delete_history_query->id ; 
      #----------------------------- end pettycash amount -------------------------------#
      #----------------------------- insert delete purcahse ------------------------------#
      $delete_purchase     = array();
      $delete_purchase['cashbook_id'] = $cashbook_id ;
      $delete_purchase['invoice'] = $invoice ;
      $delete_purchase['memo_no'] =  $memo ;
      $delete_purchase['supplier_id'] = $supplier_id ;
      $delete_purchase['branch_id'] = $this->branch_id ;
      $delete_purchase['total_quantity'] = $total_qty ;
      $delete_purchase['total_price'] = $total_price ;
      $delete_purchase['total_payment'] =  $total_payment ;
      $delete_purchase['product_id'] = $item_implode ;
      $delete_purchase['product_qty'] = $qty_implode ;
      $delete_purchase['product_price'] = $price_implode ;
      $delete_purchase['bill_added_id'] = $bill_added_id;
      $delete_purchase['bill_remove_id'] = $this->loged_id ; 
      $delete_purchase['bill_tr_date'] = $bill_tr_date ;
      $delete_purchase['bill_remove_date'] = $this->rcdate ;
      $delete_purchase['bill_created_date'] = $bill_created_at;
      $delete_purchase['bill_remove_time'] = $this->current_time;
      DB::table('delete_purchase')->insert($delete_purchase);
      #----------------------------- end insert delete purchase ---------------------------#
      #----------------------------- update supplier due-----------------------------------#
     $supplier_due_query    = DB::table('supplier_due')->where('supplier_id',$supplier_id)->first();
     $database_supplier_due = $supplier_due_query->total_due_amount ;
     $now_supplier_due      = $database_supplier_due - $was_supplier_due  ;
     $data3 = array();
     $data3['total_due_amount'] = $now_supplier_due ;
     $update_supplier_due = DB::table('supplier_due')->where('supplier_id',$supplier_id)->update($data3);

      #----------------------------- end update supplier due -------------------------------#
      #----------------------------- delete cash book --------------------------------------# 
      DB::table('cashbook')->where('id',$cashbook_id)->delete();

      #----------------------------- end delete  cash book ----------------------------------#
      #----------------------------- delete payment ledger --------------------------------#
       DB::table('payment_ledger')->where('branch_id',$this->branch_id)->where('cashbook_id',$cashbook_id)->where('invoice',$invoice)->where('status',1)->where('supplier_id',$supplier_id)->delete();
      #---------------------------- end delete payment ledger -----------------------------#
      #---------------------------- delete purchase product -------------------------------#
      DB::table('purchase_product')->where('branch_id',$this->branch_id)->where('cashbook_id',$cashbook_id)->where('invoice_number',$invoice)->delete();
      #---------------------------- end delete purchas product-----------------------------#
      #---------------------------- delete purchase ---------------------------------------#
       DB::table('purchase')->where('cashbook_id',$cashbook_id)->where('invoice',$invoice)->delete();
      #---------------------------- end delete purchase -----------------------------------#
      Session::put('succes','Thanks , Purchase Deleted Successfully');
      return Redirect::to('managerPurchaseReport');

    }
    #------------------------------ end manager purchase delete--------------------------------------------------#


    #------------------------------ END MANAGER DELETE START ----------------------------------------------------#

}
 