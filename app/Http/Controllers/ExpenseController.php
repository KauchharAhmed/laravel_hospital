<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests;
use DB;
use Session;

class ExpenseController extends Controller
{
     private $rcdate ;
     private $loged_id ;
     private $current_time ;
     private $branch_id ;
     /**
     * Expense CLASS costructor 
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
    #--------------- EXPENSE CATEGORY------------------------#
    /**
     * load expense category form
     *
     * @return \Illuminate\Http\Response
     */
    public function addExpenseCategory()
    {
     return view('expense.addExpenseCategory');	
    }
    /**
    * new expense category information added .
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function addExpenseCategoryInfo(Request $request)
    {
    $this->validate($request, [
    'name'        => 'required'
    ]);
     $name          	= trim($request->name);
     $remarks        	= trim($request->remarks);
     //check duplicatet brand name
     $count = DB::table('expense_category')
     ->where('branch_id', $this->branch_id)
     ->where('expense_name', $name)
     ->count();
      if($count > 0){
        Session::put('failed','Sorry ! '.$name. ' Expense Category Already Exits. Try To Add New Expense Category');
        return Redirect::to('addExpenseCategory');
        exit();
      }
     $data=array();
     $data['branch_id']       = $this->branch_id;
     $data['expense_name']    = $name;
     $data['remarks']       	= $remarks ;
     $data['added_id']        = $this->loged_id ;
     $data['created_at']    	= $this->rcdate ;
     $query = DB::table('expense_category')->insert($data);
     if($query){
        Session::put('succes','Thanks , Expense Category Added Sucessfully');
        return Redirect::to('addExpenseCategory');
    }else{
        Session::put('failed','Sorry ! Error Occued. Try Again');
        return Redirect::to('addExpenseCategory');
    }
    }
     /**
     * Display the all Expense Category.
     *
     * @return \Illuminate\Http\Response
     */

   public function manageExpenseCategory()
   {
       $result = DB::table('expense_category')->where('branch_id',$this->branch_id)->get();
       return view('expense.manageExpenseCategory')->with('result',$result);
   }
   #-------------------END EXPENSE CATEGORY-------------------------#  
   #--------------------- START MANAGER EXPENSE------------------------------#
    public function addManagerExpense()
    {
       // expense categoty and status = 2
       $result = DB::table('expense_category')->get(); 
       return view('expense.addManagerExpense')->with('result',$result);
   }
   // add expense by manager
   public function addManagerExpenseInfo(Request $request)
   {
    $this->validate($request, [
    'expense_category'  => 'required',
    'amount'            => 'required',
    'confirm_amount'    => 'required',
    'tr_date'           => 'required'
    ]);
     $expense_category  = trim($request->expense_category);
     $amount            = trim($request->amount);
     $confirm_amount    = trim($request->confirm_amount);
     $provider          = trim($request->provider);
     $provider_memo     = trim($request->provider_memo);
     $remarks           = trim($request->remarks);
     $tr_date           = trim($request->tr_date);
     $trDate            = date('Y-m-d',strtotime($tr_date));

     #--------------------- DATE VALIDATION --------------------------------#
    if($trDate > $this->rcdate){
        Session::put('failed','Sorry ! Enter Wrong Expense Date. Expense Date Will Not Be Big Than Current Date');
        return Redirect::to('addManagerExpense');
        exit();
      }
     #--------------------- END DATE VALIDATION -----------------------------#

     #---------------------- MATCH AMOUNT AND CONFIRM AMOUNT-----------------#
      if($amount != $confirm_amount){
        Session::put('failed','Sorry ! Amount And Confirm Amount Did Not Match.');
        return Redirect::to('addManagerExpense');
        exit();
      }
     #---------------------- END MATCH AMOUNT AND CONFIRM AMOUNT -----------# 
     #---------------------- CHECK THE PETTYCAH AMOUNT ---------------------#
     $pettryCashAmt1            =  DB::table('pettycash')->where('branch_id',$this->branch_id)->where('type',2)->limit(1)->first();
     $available_pettyCash1      =  $pettryCashAmt1->pettycash_amount;
     if($available_pettyCash1 < $amount)
     {
        Session::put('failed','Sorry ! Insufficient Balance Of Your Petty Cash. Try Again After Available Petty Cash');
        return Redirect::to('addManagerExpense');
        exit();
     }
     #--------------------- END CHECK THE PETTYCAH AMOUNT--------------------#
     $purpose = "Expense";

     #--------------------- INSERT THE CASHBOOK-------------------------------#
        $data_cashbook                        = array();
        $data_cashbook['overall_branch_id']   = $this->branch_id ;
        $data_cashbook['branch_id']           = $this->branch_id ;
        $data_cashbook['admin_id']            = $this->loged_id  ;
        $data_cashbook['admin_type']          = 2 ;
        $data_cashbook['cost']                = $amount ;
        $data_cashbook['profit_cost']         = $amount ;
        $data_cashbook['status']              = 18 ;
        $data_cashbook['tr_status']           = 1 ;
        $data_cashbook['purpose']             = $purpose;
        $data_cashbook['added_id']            = $this->loged_id;
        $data_cashbook['created_time']        = $this->current_time;
        $data_cashbook['created_at']          = $trDate;
        $data_cashbook['on_created_at']       = $this->rcdate;
        DB::table('cashbook')->insert($data_cashbook);
    #--------------------- END INSERT THE CASHBOOK---------------------------#
    #--------------------- GET LAST CASH BOOK ID  ---------------------------#
     $last_cashbook_id_query = DB::table('cashbook')->orderBy('id','desc')->limit(1)->first();
     $last_cashbook_id       = $last_cashbook_id_query->id ; 
    #-------------------- GET LAST CASH BOOK ID -----------------------------#

    #------------------- CREATE EXPENSE MEMO NUMBER--------------------------#
     // get memo number by 
     $check_memo_number = DB::table('expense_history')->where('branch_id',$this->branch_id)->count();
     if($check_memo_number > 0){
        // get memo number
        $last_memo_number_query = DB::table('expense_history')->where('branch_id',$this->branch_id)->orderBy('id','desc')->take(1)->first();
        $memo_number = $last_memo_number_query->memo_no + 1 ;
     }else{
        $memo_number = 1 ;
     }
    #------------------- END EXPENSE MEMO NUMBER------------------------------#
    #--------------------- INSERT EXEPENSE HISTORY TABLE ------------------#
      $datae = array();
      $datae['cashbook_id']     = $last_cashbook_id;
      $datae['branch_id']       = $this->branch_id;
      $datae['memo_no']         = $memo_number;
      $datae['category']        = $expense_category;
      $datae['amount']          = $amount;
      $datae['status']          = 2;
      $datae['service_provider'] = $provider;
      $datae['service_provider_memo_no'] = $provider_memo  ;
      $datae['remarks']         = $remarks ;
      $datae['added_id']        = $this->loged_id ;
      $datae['expense_time']    = $this->current_time;
      $datae['created_at']      = $trDate ;
      $datae['on_created_at']   = $this->rcdate ;
      DB::table('expense_history')->insert($datae);
    #-------------------- END INSERT EXEPENSE HISTORY TABLE -----------------#
    #------------------- REDUCE THE PETTY CASH OF ADMIN----------------------#
     $pettryCashAmt = DB::table('pettycash')->where('branch_id',$this->branch_id)->where('type',2)->limit(1)->first();
     $available_pettyCash =  $pettryCashAmt->pettycash_amount;
     $data2 = array();
     $data2['pettycash_amount']     = $available_pettyCash - $amount ;
     $update_peetycash_query        = DB::table('pettycash')->where('branch_id', $this->branch_id)->where('type',2)->update($data2);
    #------------------- END REDUCE THE PETTY CASH OF ADMIN------------------#
       Session::put('succes','Thanks , Expense Added Sucessfully');
       return Redirect::to('addManagerExpense');
   }
}
