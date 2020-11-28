<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests;
use DB;
use Session;

class BankController extends Controller
{
    private $rcdate ;
    private $current_time ;
    private $loged_id ;
    private $branch_id ;
    public function __construct() {
    date_default_timezone_set('Asia/Dhaka');
    $this->rcdate         = date('Y-m-d');
    $this->current_time = date('H:i:s');
    $this->loged_id     = Session::get('admin_id');
    $this->branch_id    = Session::get('branch_id');
    }
     /**
     * load bank form
     *
     * @return \Illuminate\Http\Response
     */
    public function addBank()
    {
     return view('bank.addBank');	
    }
    /**
    * new bank information added .
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function addBankInfo(Request $request)
    {
    $this->validate($request, [
    'name'                 => 'required',
    'branch'               => 'required',
    'account_name'         => 'required',
    'account_number'       => 'required',
    'bank_balance'         => 'required',
    'confirm_bank_balance' => 'required',
    'tr_date'              => 'required',
    ]);
     $name                  = trim($request->name);
     $branch                = trim($request->branch);
     $account_name          = trim($request->account_name);
     $account_number        = trim($request->account_number);
     $bank_balance          = trim($request->bank_balance);
     $confirm_bank_balance  = trim($request->confirm_bank_balance);
     $remarks               = trim($request->remarks);
     $tr_date               = trim($request->tr_date);
     $trDate                = date('Y-m-d',strtotime($tr_date));
    #---------------------- DATE VALIDATION----------------------#
     if($trDate > $this->rcdate){
        Session::put('failed','Sorry ! Enter Invalid Date. Please Enter Valid Date And Try Again ');
        return Redirect::to('addBank');
        exit();
     }
     #--------------------- END DATE VALIDATIN--------------------#
     // check bank balance and confirm bank balance
     if($bank_balance != $confirm_bank_balance){
      Session::put('failed','Sorry ! Bank Balance And Confirm Bank Balance Did Not Match. Try Again');
        return Redirect::to('addBank');  
        exit();
     }
     //check duplicate bank 
     $count = DB::table('bank')
     ->where('bank_name', $name)
     ->where('account_no', $account_number)
     ->count();
      if($count > 0){
        Session::put('failed','Sorry ! Bank Already Exits. Try To Add New Bank');
        return Redirect::to('addBank');
        exit();
      }
     $data=array();
     $data['system_branch_id']  = $this->branch_id ;
     $data['bank_name']         = $name;
     $data['branch']            = $branch;
     $data['account_name']      = $account_name;
     $data['account_no']        = $account_number;
     $data['status']            = '1';
     $data['remarks']           = $remarks;
     $data['added_id']          = $this->loged_id;
     $data['created_at']        = $this->rcdate ;
     DB::table('bank')->insert($data);
        // get last bank id
        $last_id  = DB::table('bank')->orderBy('id','desc')->first();;
        $last_idd = $last_id->id ;
        // insert bank balance table
        $data1 = array();
        $data1['branch_id']         = $this->branch_id ;
        $data1['bank_id']           = $last_idd ;
        $data1['total_balance']     = $bank_balance ;
        $data1['added_id']          = $this->loged_id;
        $data1['created_at']        = $this->rcdate ;
        DB::table('bank_balance')->insert($data1);
        $data_cashbook                        = array();
        $data_cashbook['overall_branch_id']   = $this->branch_id ;
        $data_cashbook['branch_id']           = $this->branch_id ;
        $data_cashbook['admin_id']            = $this->loged_id  ;
        $data_cashbook['admin_type']          = 2 ;
        $data_cashbook['status']              = 5 ;
        $data_cashbook['tr_status']           = 2 ;
        $data_cashbook['received_by_bank']   = $bank_balance ;
        $data_cashbook1['bank_status']       = 1 ;
        $data_cashbook['purpose']             = 'Opening Bank Balance';
        $data_cashbook['added_id']            = $this->loged_id;
        $data_cashbook['created_time']        = $this->current_time;
        $data_cashbook['created_at']          = $trDate ;
        $data_cashbook['on_created_at']       = $this->rcdate;
        DB::table('cashbook')->insert($data_cashbook);
    #------------------------ end insert the cashbook -------------------------#
    #--------------------- GET LAST CASH BOOK ID  ---------------------------#
     $last_cashbook_id_query = DB::table('cashbook')->orderBy('id','desc')->limit(1)->first();
     $last_cashbook_id       = $last_cashbook_id_query->id ; 
    #-------------------- GET LAST CASH BOOK ID -----------------------------#
        $data2 = array();
        $data2['overall_branch_id'] = $this->branch_id ;
        $data2['branch_id']         = $this->branch_id ;
        $data2['admin_type']        = 2;
        $data2['bank_id']           = $last_idd ;
        $data2['cashbook_id']       = $last_cashbook_id ;
        $data2['receive_amount']    = $bank_balance ;
        $data2['status']            = 1 ;
        $data2['added_id']          = $this->loged_id;
        $data2['created_time']      = $this->current_time ;
        $data2['transaction_date']  = $trDate ;
        $data2['created_at']        = $this->rcdate ;
        $query1 = DB::table('bank_transaction')->insert($data2);
        if($query1){
        Session::put('succes','Thanks , Bank Added Sucessfully');
        return Redirect::to('addBank');
        }else{
        Session::put('failed','Sorry ! Error Occued. Try Again');
        return Redirect::to('addBank');
    }
    }
    // managet the bank
    public function manageBank()
    {
        $result = DB::table('bank')->where('system_branch_id',$this->branch_id)->get();
        return view('bank.manageBank')->with('result',$result);
    }
    #---------------------//// END MANAGER BANK TRANSACTION ////--------------------#
    #--------------------- MANAGER CASH TO BANK ------------------------------------#
    // cash to bank trasaction
    public function cashToBankTransaction()
    {
        $result = DB::table('bank')->where('system_branch_id',$this->branch_id)->where('status',1)->get();
        return view('bank.cashToBankTransaction')->with('result',$result);
    }
    // manager cash to bank trasaction
    public function cashToBankTransactionInfo(Request  $request)
    {
    $this->validate($request, [
    'bank_name'                 => 'required',
    'transfer_amount'           => 'required',
    'confirm_transfer_amount'   => 'required',
    'transfer_date'             => 'required',
    'image'                     => 'mimes:jpeg,jpg,png|max:100'
    ]);
     $bank_name                     = trim($request->bank_name);
     $transfer_amount               = trim($request->transfer_amount);
     $confirm_transfer_amount       = trim($request->confirm_transfer_amount);
     $transfer_date                 = trim($request->transfer_date);
     $transferDate                  = date('Y-m-d',strtotime($transfer_date));
     $receipt                       = trim($request->receipt);
     $remarks                       = trim($request->remarks);
     $image                         = $request->file('image');
    #---------------------- DATE VALIDATION----------------------#
     if($transferDate > $this->rcdate){
        Session::put('failed','Sorry ! Enter Invalid Date. Please Enter Valid Date And Try Again ');
        return Redirect::to('cashToBankTransaction');
        exit();
     }
     #--------------------- END DATE VALIDATIN--------------------#
     if($transfer_amount !=  $confirm_transfer_amount){
         Session::put('failed','Sorry ! Transfer Amount And Confirm Transfer Amount Did Not Match. Try Again');
        return Redirect::to('cashToBankTransaction');
        exit();
     }
     #----------------- pettycash check------------------------#
    $pettryCashAmt1 = DB::table('pettycash')->where('branch_id',$this->branch_id)->where('type',2)->limit(1)->first();
     $available_pettyCash1 =  $pettryCashAmt1->pettycash_amount;
     if($available_pettyCash1 < $transfer_amount)
     {
        Session::put('failed','Sorry ! Insufficient Balance Of Your Petty Cash. Try Again After Available Petty Cash');
        return Redirect::to('cashToBankTransaction');
        exit();
     }
     #----------------- end petty cash-------------------------#
     #------------------------INSERT INTO CASHBOOK-----------------------#
        $data8                        = array();
        $data8['overall_branch_id']   = $this->branch_id ;
        $data8['branch_id']           = $this->branch_id ;
        $data8['admin_id']            = $this->loged_id ;
        $data8['admin_type']          = 2 ;
        $data8['c2b']                 = $transfer_amount;
        $data8['status']              = 26 ;
        $data8['tr_status']           = 3 ;
        $data8['purpose']             = "Transfer Amount From Cash To Bank Account";
        $data8['added_id']            = $this->loged_id ;
        $data8['created_time']        = $this->current_time;
        $data8['created_at']          = $transferDate;
        $data8['on_created_at']       = $this->rcdate;
        DB::table('cashbook')->insert($data8);
    #----------------------- END INSERT INTO CASHBOOK-----------------------#
    #--------------------- GET LAST CASH BOOK ID  ---------------------------#
     $last_cashbook_id_query = DB::table('cashbook')->orderBy('id','desc')->limit(1)->first();
     $last_cashbook_id       = $last_cashbook_id_query->id ; 
    #-------------------- GET LAST CASH BOOK ID -----------------------------#
     $data = array();
     $data['overall_branch_id'] = $this->branch_id ;
     $data['branch_id']         = $this->branch_id ;
     $data['admin_type']        = 2 ;
     $data['bank_id']           = $bank_name ;
     $data['cashbook_id']       = $last_cashbook_id ;
     $data['receive_amount']    = $transfer_amount ;
     $data['status']            = 5 ;
     $data['added_id']          = $this->loged_id ;
     $data['info_paper']        = $receipt ;
     $data['remarks']           = $remarks ;
     $data['created_time']      = $this->current_time ;
     $data['transaction_date']  =  $transferDate ;
     $data['created_at']        =  $this->rcdate ;
        if($image){
         $image_name        = str_random(20);
         $ext               = strtolower($image->getClientOriginalExtension());
         $image_full_name   ='bank_receipt-'.$image_name.'.'.$ext;
         $upload_path       = "images/";
         $image_url         = $upload_path.$image_full_name;
         $success           = $image->move($upload_path,$image_full_name);
         $data['image']     = $image_url;

     }else{
        // no image
        $data['image'] = '';
     }
     DB::table('bank_transaction')->insert($data);
     #-------------------- reduce peety cash ---------------------------------------------#
     $data2 = array();
     $data2['pettycash_amount']     = $available_pettyCash1 - $transfer_amount ;
     $update_peetycash_query        = DB::table('pettycash')->where('branch_id',$this->branch_id)->where('type',2)->update($data2) ;
    #---------------------------- end reduce peety cash ---------------------------------#
    #----------------------- incress the bank amount-------------------------------------#
      $bank_balance_query       = DB::table('bank_balance')->where('branch_id',$this->branch_id)->where('bank_id', $bank_name)->first();
      $available_bank_amount    = $bank_balance_query->total_balance ;
      // now bank amount
      $now_bank_amount = $available_bank_amount + $transfer_amount ;
      // update bank balance
      $data3 = array();
     $data3['total_balance']      = $now_bank_amount ;
     $query3            = DB::table('bank_balance')->where('branch_id',$this->branch_id)->where('bank_id', $bank_name)->update($data3) ;
    #----------------------- end incress the bank amount---------------------------------#
        Session::put('succes','Thanks , ' .$transfer_amount. ' Amount Transfer Successfully From Cash To Bank');
        return Redirect::to('cashToBankTransaction');
        
    }
    #--------------------- END MANAGER CASH TO BANK ---------------------------------#

    #--------------------- START MANAGER BANK TO CASH -------------------------------#
    public function bankToCashTransaction()
    {
        $result = DB::table('bank')->where('system_branch_id',$this->branch_id)->where('status',1)->get();
        return view('bank.bankToCashTransaction')->with('result',$result);
    }
    public function getBankAmount(Request $request)
    {
     $bank_name = trim($request->bank_name);
     $query     = DB::table('bank_balance')->where('branch_id',$this->branch_id)->where('bank_id',$bank_name)->first();
     echo $query->total_balance ;
    }
    // amount trnaser form bank to cash
    public function bankToCashTransactionInfo(Request $request)
    {
    $this->validate($request, [
    'bank_name'                 => 'required',
    'transfer_amount'           => 'required',
    'confirm_transfer_amount'   => 'required',
    'transfer_date'             => 'required',
    'image'                     => 'mimes:jpeg,jpg,png|max:100'
    ]);
     $bank_name                     = trim($request->bank_name);
     $transfer_amount               = trim($request->transfer_amount);
     $confirm_transfer_amount       = trim($request->confirm_transfer_amount);
     $transfer_date                 = trim($request->transfer_date);
     $transferDate                  = date('Y-m-d',strtotime($transfer_date));
     $receipt                       = trim($request->receipt);
     $remarks                       = trim($request->remarks);
     $image                         = $request->file('image');
     #---------------------- DATE VALIDATION----------------------#
     if($transferDate > $this->rcdate){
        Session::put('failed','Sorry ! Enter Invalid Date. Please Enter Valid Date And Try Again ');
        return Redirect::to('bankToCashTransaction');
        exit();
     }
     #--------------------- END DATE VALIDATIN--------------------#
     if($transfer_amount !=  $confirm_transfer_amount){
         Session::put('failed','Sorry ! Transfer Amount And Confirm Transfer Amount Did Not Match. Try Again');
        return Redirect::to('bankToCashTransaction');
        exit();
     }
     #----------------- bank balance check------------------------#
      $bank_balance_query       = DB::table('bank_balance')->where('branch_id',$this->branch_id)->where('bank_id', $bank_name)->first();
      $available_bank_amount    = $bank_balance_query->total_balance ;
    if($available_bank_amount < $transfer_amount)
     {
        Session::put('failed','Sorry ! Insufficient Balance Of Your Bank Account. Try Again After Available Bank Account Amount');
        return Redirect::to('bankToCashTransaction');
        exit();
     }
    #------------------------INSERT INTO CASHBOOK-----------------------#
        $data8                        = array();
        $data8['overall_branch_id']   = $this->branch_id ;
        $data8['branch_id']           = $this->branch_id ;
        $data8['admin_id']            = $this->loged_id ;
        $data8['admin_type']          = 2 ;
        $data8['b2c']                 = $transfer_amount;
        $data8['status']              = 27 ;
        $data8['tr_status']           = 4 ;
        $data8['purpose']             = "Transfer Amount From Bank Account To Cash";
        $data8['added_id']            = $this->loged_id ;
        $data8['created_time']        = $this->current_time;
        $data8['created_at']          = $transferDate;
        $data8['on_created_at']       = $this->rcdate;
        DB::table('cashbook')->insert($data8);
    #----------------------- END INSERT INTO CASHBOOK-----------------------#
    #--------------------- GET LAST CASH BOOK ID  ---------------------------#
     $last_cashbook_id_query = DB::table('cashbook')->orderBy('id','desc')->limit(1)->first();
     $last_cashbook_id       = $last_cashbook_id_query->id ; 
    #-------------------- GET LAST CASH BOOK ID -----------------------------#
     $data = array();
     $data['overall_branch_id'] = $this->branch_id ;
     $data['branch_id']         = $this->branch_id ;
     $data['admin_type']        = 2 ;
     $data['bank_id']           = $bank_name ;
     $data['cashbook_id']       = $last_cashbook_id ;
     $data['send_amount']       = $transfer_amount ;
     $data['status']            = 6 ;
     $data['added_id']          = $this->loged_id ;
     $data['info_paper']        = $receipt ;
     $data['remarks']           = $remarks ;
     $data['created_time']      = $this->current_time ;
     $data['transaction_date']  =  $transferDate ;
     $data['created_at']        =  $this->rcdate ;
        if($image){
         $image_name        = str_random(20);
         $ext               = strtolower($image->getClientOriginalExtension());
         $image_full_name   ='bank_receipt-'.$image_name.'.'.$ext;
         $upload_path       = "images/";
         $image_url         = $upload_path.$image_full_name;
         $success           = $image->move($upload_path,$image_full_name);
         $data['image']     = $image_url;

     }else{
        // no image
        $data['image'] = '';
     }
     DB::table('bank_transaction')->insert($data);
    #-------------------- incress pettycash ---------------------------------------------#
     $pettryCashAmt1 = DB::table('pettycash')->where('branch_id',$this->branch_id)->where('type',2)->limit(1)->first();
     $available_pettyCash1 =  $pettryCashAmt1->pettycash_amount;
     $data2 = array();
     $data2['pettycash_amount']     = $available_pettyCash1 + $transfer_amount ;
     $update_peetycash_query        = DB::table('pettycash')->where('branch_id',$this->branch_id)->where('type',2)->update($data2) ;
    #---------------------------- end incress peety cash ---------------------------------#
    #----------------------- reduce bannk account -------------------------------------#
      $bank_balance_query       = DB::table('bank_balance')->where('branch_id',$this->branch_id)->where('bank_id', $bank_name)->first();
      $available_bank_amount    = $bank_balance_query->total_balance ;
      // now bank amount
      $now_bank_amount = $available_bank_amount - $transfer_amount ;
      // update bank balance
      $data3 = array();
     $data3['total_balance']      = $now_bank_amount ;
     $query3            = DB::table('bank_balance')->where('branch_id',$this->branch_id)->where('bank_id', $bank_name)->update($data3) ;
    #----------------------- end reduec the bank amount---------------------------------#
        Session::put('succes','Thanks , ' .$transfer_amount. ' Amount Transfer Successfully From Bank Account To Cash');
        return Redirect::to('bankToCashTransaction');

    }


    #--------------------- END MANAGER BANK TO CASH----------------------------------#
}
