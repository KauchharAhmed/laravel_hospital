<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests;
use DB;
use Session;

class PaymentController extends Controller
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
     /**
     * Supllier Payment form.
     *
     * @return \Illuminate\Http\Response
     */
    public function supplierPayment()
    {
    $branch     = DB::table('branch')->get();
    $bank       = DB::table('bank')->where('status',1)->get();
    $supplier   = DB::table('supplier')->where('branch_id',$this->branch_id)->get();
    return view('payment.supplierPayment')->with('branch',$branch)->with('bank',$bank)->with('supplier',$supplier);  
    }
    /**
    * get supplier due .
    * ajax request
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function getSupllierDueAmount(Request $request)
    {
        $supplier_id = trim($request->supplier_id);
        $query = DB::table('supplier_due')->where('supplier_id',$supplier_id)->first();
        $due   = $query->total_due_amount ;
        echo $due ;
    }
    /**
    * supplier payment payment collection .
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
   public function supplierPaymentAmt(Request $request)
   {
    $this->validate($request, [
    'supplier_id'               => 'required',
    'payment_amount'            => 'required',
    'confirm_payment_amount'    => 'required',
    'payment_date'              => 'required'
    ]);
    $branch                = $this->branch_id;
    $supplier_id           = trim($request->supplier_id);
    $payment_amount        = trim($request->payment_amount);
    $confirm_payment_amount= trim($request->confirm_payment_amount);
    $remarks               = trim($request->remarks);
    $payment_method         = trim($request->payment_method);
    $bank_account_number    = trim($request->bank_account_number);
    $bank_paper             = trim($request->bank_paper);
    $payment_date           = trim($request->payment_date);
    $paymentDate            = date('Y-m-d',strtotime($payment_date)) ;

    if($payment_method == '3'){
        if($bank_account_number == ''){
        Session::put('failed','Sorry ! Please Select Bank. Try Again');
        return Redirect::to('supplierPayment'); 
        exit(); 
        }
        if($bank_paper == ''){
        Session::put('failed','Sorry ! Please Select Bank T T Number. Try Again');
        return Redirect::to('supplierPayment'); 
        exit(); 
        }
    }
    #---------------------- DATE VALIDATION----------------------#
     if($paymentDate > $this->rcdate){
        Session::put('failed','Sorry ! Enter Invalid Date. Please Enter Valid Date And Try Again ');
        return Redirect::to('supplierPayment');
        exit();
     }
     #--------------------- END DATE VALIDATIN--------------------#

    #-------------------------- payment amount and confirm payment match---------------#
      if($payment_amount != $confirm_payment_amount){
        Session::put('failed','Sorry ! Payment Amount And Confirm Payment Amount Did Not Match. Try Again');
        return Redirect::to('supplierPayment'); 
        exit();
      }
     #----------------------- end payment amount and confirm payment match---------------#
     // get supplier due
    $query = DB::table('supplier_due')->where('supplier_id',$supplier_id)->first();
    $total_due   = $query->total_due_amount ;
    if($total_due < $payment_amount){
         Session::put('failed','Sorry ! Enter Payment Amount Big Than Supplier Due Amount. Please Adjust The Payment Amount');
        return Redirect::to('supplierPayment'); 
        exit();
     }
     #----------------- pettycash check------------------------#
     if($payment_method == '1'){
     $pettryCashAmt1 = DB::table('pettycash')->where('branch_id', $branch)->where('type',2)->first();
     $available_pettyCash1 =  $pettryCashAmt1->pettycash_amount;
     if($available_pettyCash1 < $payment_amount)
     {
        Session::put('failed','Sorry ! Insufficient Balance Of Your Petty Cash. Try Again After Available Petty Cash');
        return Redirect::to('supplierPayment');
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
        return Redirect::to('supplierPayment');
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

      $purpose ='Supplier Payment By '.$payment_method_type_is;

 #------------------------- end check bank balnce------------------#
 #-------------------------- insert into collection table--------------------------#
     // get memo number by 
     // brach id , memo_no 
     $check_memo_number = DB::table('payment')->where('branch_id',$branch)->count();
     if($check_memo_number > 0){
        // get memo number
        $last_memo_number_query = DB::table('payment')->where('branch_id',$branch)->orderBy('memo_no','desc')->take(1)->first();
        $memo_number = $last_memo_number_query->memo_no + 1 ;
     }else{
        $memo_number = 1 ;
     }
     #------------------------ end insert into collection table------------------------#

     #----------------------- year memo------------------------------------------------#
    $check_year_memo_number = DB::table('payment')->where('branch_id',$branch)->where('year',$this->current_year)->count();
     if($check_year_memo_number > 0){
        // get year memo number
        $last_year_memo_number_query = DB::table('payment')->where('branch_id',$branch)->where('year',$this->current_year)->orderBy('year_memo','desc')->take(1)->first();
        $year_memo_number = $last_year_memo_number_query->year_memo + 1 ;
     }else{
        $year_memo_number = 1 ;
     }

     #----------------------- end year memo--------------------------------------------#

     #-------------------------- account memo number-----------------------------------#
     // get account holder  memo number by 
     $check_account_memo_number = DB::table('payment')->where('supplier_id',$supplier_id)->count();
     if($check_account_memo_number > 0){
        // get memo number
        $last_account_memo_number_query = DB::table('payment')->where('supplier_id',$supplier_id)->orderBy('id','desc')->take(1)->first();
        $account_memo_number = $last_account_memo_number_query->account_memo_no + 1 ;
     }else{
        $account_memo_number = 1 ;
     }
     #--------------------end account memo number--------------------------------------#]

    #------------------- insert into cashbook-----------------------------------------#
   if($payment_method == '1')
   {
     $cost_in_cashbook    = $payment_amount ;
     $cashbook_status_is  = 6 ; 
     $cashbook_tr_status = 1 ;
   }elseif($payment_method == '3'){
    $cashbook_status_is = 7 ;
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

    $data8 = array(); 
    $data8['overall_branch_id'] = $branch ;
    $data8['branch_id']         = $branch ;
    $data8['admin_id']          = $this->loged_id ;
    $data8['admin_type']        = 2 ;
    $data8['cost']              = $cost_in_cashbook ;
    $data8['status']            = $cashbook_status_is ;
    $data8['tr_status']         = $cashbook_tr_status ;
    $data8['payment_by_bank']   = $payment_by_bank ;
    $data8['bank_status']       = $bank_status_is_being ;
    $data8['purpose']           = $purpose;
    $data8['added_id']          = $this->loged_id;
    $data8['created_time']      = $this->current_time;
    $data8['created_at']        = $paymentDate;
    $data8['on_created_at']     = $this->rcdate;
    DB::table('cashbook')->insert($data8);

    #------------------- end insert into cashbook--------------------------------------#
    #--------------------- GET LAST CASH BOOK ID  ---------------------------#
     $last_cashbook_id_query = DB::table('cashbook')->orderBy('id','desc')->limit(1)->first();
     $last_cashbook_id       = $last_cashbook_id_query->id ; 
    #-------------------- GET LAST CASH BOOK ID -----------------------------#
     #-------------------------- insert payment table-----------------------------------#
     $data = array();
     $data['cashbook_id']       = $last_cashbook_id ;
     $data['payment_method']    = $payment_method ;
     $data['bank_id']           = $bank_account_number ;
     $data['branch_id']         = $branch ;
     $data['memo_no']           = $memo_number ;
     $data['year']              = $this->current_year ;
     $data['year_memo']         = $year_memo_number ;
     $data['account_memo_no']   = $account_memo_number ;
     $data['supplier_id']       = $supplier_id ;
     $data['payment_amount']    = $payment_amount ;
     $data['purpose']           = $purpose;
     $data['remarks']           = $remarks ;
     $data['added_id']          = $this->loged_id ;
     $data['payment_time']      = $this->current_time ;
     $data['created_at']        = $paymentDate ;
     $data['on_created_at']     = $this->rcdate ;
     DB::table('payment')->insert($data);
    #--------------------- end insert payment table---------------------------#
    #-------------------- insert into payment_leadger--------------------------#
  $check_supplier_id_ledger = DB::table('payment_ledger')->where('supplier_id',$supplier_id)->count();
   if($check_supplier_id_ledger == '0'){
       $supplier_ledger_previous_due_query = DB::table('supplier')->where('id',$supplier_id)->first();
    $supplier_ledger_previous_due = $supplier_ledger_previous_due_query->supplier_due ;
    if($supplier_ledger_previous_due != '0.00'){
        $dataledger['branch_id']     = $branch ;
        $dataledger['supplier_id']    = $supplier_id ;
        $dataledger['payable_amount'] = $supplier_ledger_previous_due ;
        $dataledger['status'] = 0 ;
        $dataledger['purpose'] = 'Previous Due' ;
        $dataledger['added_id'] = $this->loged_id ;
        $dataledger['created_at']        = $paymentDate ;
        $dataledger['on_created_at']        = $this->rcdate ;
        DB::table('payment_ledger')->insert($dataledger);
    } 
        $dataledger1 = array();
        $dataledger1['cashbook_id']   = $last_cashbook_id ;
        $dataledger1['branch_id']     = $branch ;
        $dataledger1['supplier_id']   = $supplier_id ;
        $dataledger1['money_receipt'] = $memo_number ;
        $dataledger1['payment_amount']= $payment_amount ;
        $dataledger1['status']        = 2 ;
        $dataledger1['tr_status']     = $cashbook_tr_status ;
        $dataledger1['purpose']       = $purpose ;
        $dataledger1['remarks']       = $remarks ;
        $dataledger1['added_id']      = $this->loged_id ;
        $dataledger1['created_at'] = $paymentDate;
        $dataledger1['created_time'] = $this->current_time;
        $dataledger1['on_created_at'] = $this->rcdate ;
        DB::table('payment_ledger')->insert($dataledger1);
    }else{
        $dataledger1 = array();
        $dataledger1['cashbook_id']   = $last_cashbook_id ;
        $dataledger1['branch_id']     = $branch ;
        $dataledger1['supplier_id']   = $supplier_id ;
        $dataledger1['money_receipt'] = $memo_number ;
        $dataledger1['payment_amount']= $payment_amount ;
        $dataledger1['status']        = 2 ;
        $dataledger1['tr_status']     = $cashbook_tr_status ;
        $dataledger1['purpose']       = $purpose ;
        $dataledger1['remarks']       = $remarks ;
        $dataledger1['added_id']      = $this->loged_id ;
        $dataledger1['created_at'] = $paymentDate;
        $dataledger1['created_time'] = $this->current_time;
        $dataledger1['on_created_at'] = $this->rcdate ;
        DB::table('payment_ledger')->insert($dataledger1);
        } 
     #-------------------- end insert payment leadger-------------------------------------#
    #-------------------- reduce due of supplier id------------------------------------#
   $now_due  = $total_due - $payment_amount ;
   $datadue = array();
   $datadue['total_due_amount'] = $now_due  ;
   DB::table('supplier_due')->where('supplier_id',$supplier_id)->update($datadue);
    #-------------------- end reduce due of supplier id---------------------------------#
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
  
#---------------------------- end reduce peety cash ---------------------------------#
if($payment_method == '3')
 {
    $bank_payment_data_insert     = array();
    $bank_payment_data_insert['overall_branch_id']  = $branch ;
    $bank_payment_data_insert['branch_id']          = $branch ;
    $bank_payment_data_insert['admin_type']         = 2 ;
    $bank_payment_data_insert['bank_id']            = $bank_account_number ;
    $bank_payment_data_insert['cashbook_id']        = $last_cashbook_id ;
    $bank_payment_data_insert['send_amount']        = $payment_amount;
    $bank_payment_data_insert['status']             = 2 ;
    $bank_payment_data_insert['added_id']           = $this->loged_id ;
    $bank_payment_data_insert['remarks']            = $purpose;
    $bank_payment_data_insert['created_time']       = $this->current_time ;
    $bank_payment_data_insert['transaction_date']   = $paymentDate ;
    $bank_payment_data_insert['created_at']         = $this->rcdate ;
    DB::table('bank_transaction')->insert($bank_payment_data_insert);
 }
    Session::put('succes','Thanks , Supplier Payment Successfully');
    return Redirect::to('supplierPayment');
    #-------------------- end insert cashbook-----------------------------#
   }
  #------------------------------------ MANAGE PAYMENT ------------------------#
    /**
     * Display all suppliery payment.
     *
     * @return \Illuminate\Http\Response
     */
    public function managePayment()
    {
    // get from payment table
     $result = DB::table('payment')
    ->join('supplier', 'payment.supplier_id', '=', 'supplier.id')
    ->select('payment.*','supplier.supplier_name','supplier.mobile')
    ->where('payment.branch_id',$this->branch_id)
    ->get();
    return view('payment.managePayment')->with('result',$result) ;
    }
  #------------------------------- END MANAGE PAYMENT--------------------------#

  #------------------------------- START PC PAYMENT ----------------------------#
    /**
     * PC Payment form.
     *
     * @return \Illuminate\Http\Response
     */
    public function pcPayment()
    {
    $branch     = DB::table('branch')->get();
    $bank       = DB::table('bank')->where('status',1)->get();
    $pc         = DB::table('tbl_pc')->where('branch_id',$this->branch_id)->get();
    return view('payment.pcPayment')->with('branch',$branch)->with('bank',$bank)->with('pc',$pc);  
    }
    // get pc due amount
    public function getPcDueAmount(Request $request)
    {
        $pc_id = trim($request->pc_id);
        $query = DB::table('pc_due')->where('pc_id',$pc_id)->first();
        $due   = $query->total_due_amount ;
        echo $due ;

    }
    // pc payment
    public function pcPaymentAmt(Request $request)
    {
    $this->validate($request, [
    'pc_id'                     => 'required',
    'payment_amount'            => 'required',
    'confirm_payment_amount'    => 'required',
    'payment_date'              => 'required'
    ]);
    $branch                = $this->branch_id;
    $pc_id                 = trim($request->pc_id);
    $payment_amount        = trim($request->payment_amount);
    $confirm_payment_amount= trim($request->confirm_payment_amount);
    $remarks               = trim($request->remarks);
    $payment_method        = trim($request->payment_method);
    $bank_account_number   = trim($request->bank_account_number);
    $bank_paper            = trim($request->bank_paper);
    $payment_date          = trim($request->payment_date);
    $paymentDate           = date('Y-m-d',strtotime($payment_date)) ;

    if($payment_method == '3'){
        if($bank_account_number == ''){
        Session::put('failed','Sorry ! Please Select Bank. Try Again');
        return Redirect::to('pcPayment'); 
        exit(); 
        }
        if($bank_paper == ''){
        Session::put('failed','Sorry ! Please Select Bank T T Number. Try Again');
        return Redirect::to('pcPayment'); 
        exit(); 
        }
    }
    #---------------------- DATE VALIDATION----------------------#
     if($paymentDate > $this->rcdate){
        Session::put('failed','Sorry ! Enter Invalid Date. Please Enter Valid Date And Try Again ');
        return Redirect::to('pcPayment');
        exit();
     }
     #--------------------- END DATE VALIDATIN--------------------#

    #-------------------------- payment amount and confirm payment match---------------#
      if($payment_amount != $confirm_payment_amount){
        Session::put('failed','Sorry ! Payment Amount And Confirm Payment Amount Did Not Match. Try Again');
        return Redirect::to('pcPayment'); 
        exit();
      }
     #----------------------- end payment amount and confirm payment match---------------#
     // get supplier due
    $query = DB::table('pc_due')->where('pc_id',$pc_id)->first();
    $total_due   = $query->total_due_amount ;
    if($total_due < $payment_amount){
         Session::put('failed','Sorry ! Enter Payment Amount Big Than PC Due Amount. Please Adjust The Payment Amount');
        return Redirect::to('pcPayment'); 
        exit();
     }
     #----------------- pettycash check------------------------#
     if($payment_method == '1'){
     $pettryCashAmt1 = DB::table('pettycash')->where('branch_id', $branch)->where('type',2)->first();
     $available_pettyCash1 =  $pettryCashAmt1->pettycash_amount;
     if($available_pettyCash1 < $payment_amount)
     {
        Session::put('failed','Sorry ! Insufficient Balance Of Your Petty Cash. Try Again After Available Petty Cash');
        return Redirect::to('pcPayment');
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
        return Redirect::to('pcPayment');
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

$purpose ='PC Payment By '.$payment_method_type_is;
 #------------------------- end check bank balnce------------------#
 #-------------------------- insert into collection table--------------------------#
     // get memo number by 
     // brach id , memo_no 
     $check_memo_number = DB::table('pc_payment')->where('branch_id',$branch)->count();
     if($check_memo_number > 0){
        // get memo number
        $last_memo_number_query = DB::table('pc_payment')->where('branch_id',$branch)->orderBy('memo_no','desc')->take(1)->first();
        $memo_number = $last_memo_number_query->memo_no + 1 ;
     }else{
        $memo_number = 1 ;
     }
     #------------------------ end insert into collection table------------------------#

     #----------------------- year memo------------------------------------------------#
    $check_year_memo_number = DB::table('pc_payment')->where('branch_id',$branch)->where('year',$this->current_year)->count();
     if($check_year_memo_number > 0){
        // get year memo number
        $last_year_memo_number_query = DB::table('pc_payment')->where('branch_id',$branch)->where('year',$this->current_year)->orderBy('year_memo','desc')->take(1)->first();
        $year_memo_number = $last_year_memo_number_query->year_memo + 1 ;
     }else{
        $year_memo_number = 1 ;
     }

     #----------------------- end year memo--------------------------------------------#

     #-------------------------- account memo number-----------------------------------#
     // get account holder  memo number by 
     $check_account_memo_number = DB::table('pc_payment')->where('pc_id',$pc_id)->count();
     if($check_account_memo_number > 0){
        // get memo number
        $last_account_memo_number_query = DB::table('pc_payment')->where('pc_id',$pc_id)->orderBy('id','desc')->take(1)->first();
        $account_memo_number = $last_account_memo_number_query->account_memo_no + 1 ;
     }else{
        $account_memo_number = 1 ;
     }
     #--------------------end account memo number--------------------------------------#

    #------------------- insert into cashbook-----------------------------------------#
   if($payment_method == '1')
   {
     $cost_in_cashbook    = $payment_amount ;
     $cashbook_status_is  = 19 ; 
     $cashbook_tr_status = 1 ;
   }elseif($payment_method == '3'){
    $cashbook_status_is = 20 ;
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

    #------------------- end insert into cashbook--------------------------------------#
    #--------------------- GET LAST CASH BOOK ID  ---------------------------#
     $last_cashbook_id_query = DB::table('cashbook')->orderBy('id','desc')->limit(1)->first();
     $last_cashbook_id       = $last_cashbook_id_query->id ; 
    #-------------------- GET LAST CASH BOOK ID -----------------------------#
     #-------------------------- insert payment table-----------------------------------#
     $data = array();
     $data['cashbook_id']       = $last_cashbook_id ;
     $data['payment_method']    = $payment_method ;
     $data['bank_id']           = $bank_account_number ;
     $data['branch_id']         = $branch ;
     $data['memo_no']           = $memo_number ;
     $data['year']              = $this->current_year ;
     $data['year_memo']         = $year_memo_number ;
     $data['account_memo_no']   = $account_memo_number ;
     $data['pc_id']             = $pc_id ;
     $data['payment_amount']    = $payment_amount ;
     $data['purpose']           = $purpose;
     $data['remarks']           = $remarks ;
     $data['added_id']          = $this->loged_id ;
     $data['payment_time']      = $this->current_time ;
     $data['created_at']        = $paymentDate ;
     $data['on_created_at']     = $this->rcdate ;
     DB::table('pc_payment')->insert($data);
    #--------------------- end insert payment table---------------------------#
    #-------------------- insert into payment_leadger--------------------------#
        $dataledger1 = array();
        $dataledger1['cashbook_id']   = $last_cashbook_id ;
        $dataledger1['branch_id']     = $branch ;
        $dataledger1['pc_id']         = $pc_id ;
        $dataledger1['money_receipt'] = $memo_number ;
        $dataledger1['payment_amount']= $payment_amount ;
        $dataledger1['status']        = 4 ;
        $dataledger1['tr_status']     = $cashbook_tr_status ;
        $dataledger1['purpose']       = $purpose ;
        $dataledger1['remarks']       = $remarks ;
        $dataledger1['added_id']      = $this->loged_id ;
        $dataledger1['created_at']    = $paymentDate;
        $dataledger1['created_time']  = $this->current_time;
        $dataledger1['on_created_at'] = $this->rcdate ;
        DB::table('pc_ledger')->insert($dataledger1);
     
     #-------------------- end insert payment leadger-------------------------------------#
    #-------------------- reduce due of supplier id------------------------------------#
   $now_due  = $total_due - $payment_amount ;
   $datadue = array();
   $datadue['total_due_amount'] = $now_due  ;
   DB::table('pc_due')->where('pc_id',$pc_id)->update($datadue);
    #-------------------- end reduce due of supplier id---------------------------------#
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
  
#---------------------------- end reduce peety cash ---------------------------------#
if($payment_method == '3')
 {
    $bank_payment_data_insert     = array();
    $bank_payment_data_insert['overall_branch_id']  = $branch ;
    $bank_payment_data_insert['branch_id']          = $branch ;
    $bank_payment_data_insert['admin_type']         = 2 ;
    $bank_payment_data_insert['bank_id']            = $bank_account_number ;
    $bank_payment_data_insert['cashbook_id']        = $last_cashbook_id ;
    $bank_payment_data_insert['send_amount']        = $payment_amount;
    $bank_payment_data_insert['status']             = 3 ;
    $bank_payment_data_insert['added_id']           = $this->loged_id ;
    $bank_payment_data_insert['remarks']            = $purpose;
    $bank_payment_data_insert['created_time']       = $this->current_time ;
    $bank_payment_data_insert['transaction_date']   = $paymentDate ;
    $bank_payment_data_insert['created_at']         = $this->rcdate ;
    DB::table('bank_transaction')->insert($bank_payment_data_insert);
 }
    Session::put('succes','Thanks , PC Payment Successfully');
    return Redirect::to('pcPayment');

    }
  #------------------------------- END PC PAYMENT -------------------------------#

}
