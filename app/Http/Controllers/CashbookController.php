<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests;
use DB;
use Session;

class CashbookController extends Controller
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
   // manager full  cashbok
   public function managerFullCashbook()
   {
   	 $result    = DB::table('cashbook')->where('branch_id',$this->branch_id)->where('admin_type',2)->whereNotIn('tr_status',[2])->orderby('created_at','asc')->get();
     return view('cashbook.managerFullCashbook')->with('result',$result)->with('branch_id',$this->branch_id) ; 
   }
   // manager today cashbook
   public function managerTodayCashbook()
   {
   	   $rcdate    = $this->rcdate ;
        // get clossing balnce befor today
        $previous_colum_balance = DB::table('cashbook')->where('branch_id',$this->branch_id)->where('admin_type',2)->whereNotIn('tr_status',[2])->where('created_at','<', $this->rcdate)->get();
        // get befor clossing balance
        $previous_earn_amount = 0 ;
        $previous_cost_amount = 0 ;
        $previous_c2b         = 0 ;
        $previous_b2c         = 0 ;
        $balance_transfer     = 0 ;
        $previous_send_transfer = 0 ;
        $previous_receive_transfer = 0 ;
        $previous_non_cash = 0 ;
        $previous_m2c      = 0 ;
        foreach ($previous_colum_balance as $previous_colum_balances) {
        $previous_earn_amount = $previous_earn_amount + $previous_colum_balances->earn ;

        $previous_cost_amount = $previous_cost_amount + $previous_colum_balances->cost ;

         $previous_non_cash = $previous_non_cash + $previous_colum_balances->get_non_cash_payment ;

        $previous_c2b      = $previous_c2b + $previous_colum_balances->c2b ;

    $previous_b2c      = $previous_b2c + $previous_colum_balances->b2c ;
     $previous_m2c      = $previous_m2c + $previous_colum_balances->m2c ;
    if($previous_colum_balances->balance_transfer_type == '1' OR $previous_colum_balances->balance_transfer_type == '0'){
    $previous_send_transfer = $previous_send_transfer + $previous_colum_balances->balance_transfer ;
    }else if($previous_colum_balances->balance_transfer_type == '2' OR $previous_colum_balances->balance_transfer_type == '0'){
    $previous_receive_transfer = $previous_receive_transfer + $previous_colum_balances->balance_transfer ;
            }
        }
    $previous_balance = $previous_earn_amount - $previous_cost_amount - $previous_c2b + $previous_b2c - $previous_send_transfer +  $previous_receive_transfer - $previous_non_cash + $previous_m2c ;
    // today cashbook
    $result = DB::table('cashbook')->where('branch_id',$this->branch_id)->where('admin_type',2)->whereNotIn('tr_status',[2])->where('created_at', $this->rcdate)->get();
    return view('cashbook.managerTodayCashbook')->with('result',$result)->with('previous_balance',$previous_balance)->with('rcdate',$this->rcdate)->with('branch_id',$this->branch_id);
   }
   // manager date wise cashbook
   public function managerDatewiseCashbook()
   {
   	return view('cashbook.managerDatewiseCashbook');
   }
   // manager datewise cashbook
   public function managerDatewiseCashbookView(Request $request)
   {
   	  $from_date = trim($request->from_date);
      $to_date   = trim($request->to_date);
      $from      = date('Y-m-d',strtotime($from_date)) ;
      $to        = date('Y-m-d',strtotime($to_date)) ;
      $next_form = date('Y-m-d', strtotime('+1 day', strtotime($from))) ;
      // get clossing balnce befor today
      $previous_colum_balance = DB::table('cashbook')->where('branch_id',$this->branch_id)->where('admin_type',2)->whereNotIn('tr_status',[2])->where('created_at','<=', $from)->get();
        // get befor clossing balance
        $previous_earn_amount = 0 ;
        $previous_cost_amount = 0 ;
        $previous_c2b         = 0 ;
        $previous_b2c         = 0 ;
        $balance_transfer     = 0 ;
        $previous_send_transfer = 0 ;
        $previous_receive_transfer = 0 ;
        $previous_non_cash = 0 ;
        $previous_m2c      = 0 ;
        foreach ($previous_colum_balance as $previous_colum_balances) {
        $previous_earn_amount = $previous_earn_amount + $previous_colum_balances->earn ;

        $previous_cost_amount = $previous_cost_amount + $previous_colum_balances->cost ;

         $previous_non_cash = $previous_non_cash + $previous_colum_balances->get_non_cash_payment ;

        $previous_c2b      = $previous_c2b + $previous_colum_balances->c2b ;

    $previous_b2c      = $previous_b2c + $previous_colum_balances->b2c ;
     $previous_m2c      = $previous_m2c + $previous_colum_balances->m2c ;
    if($previous_colum_balances->balance_transfer_type == '1' OR $previous_colum_balances->balance_transfer_type == '0'){
    $previous_send_transfer = $previous_send_transfer + $previous_colum_balances->balance_transfer ;
    }else if($previous_colum_balances->balance_transfer_type == '2' OR $previous_colum_balances->balance_transfer_type == '0'){
    $previous_receive_transfer = $previous_receive_transfer + $previous_colum_balances->balance_transfer ;
            }
        }
    $previous_balance = $previous_earn_amount - $previous_cost_amount - $previous_c2b + $previous_b2c - $previous_send_transfer +  $previous_receive_transfer - $previous_non_cash + $previous_m2c ;
    $result    = DB::table('cashbook')->where('branch_id',$this->branch_id)->where('admin_type',2)->whereNotIn('tr_status',[2])->whereBetween('created_at', [$next_form, $to])->orderby('created_at','asc')->get();
      return view('cashbook.managerDatewiseCashbookView')->with('result',$result)->with('from',$from)->with('to',$to)->with('next_form',$next_form)->with('previous_balance', $previous_balance)->with('branch_id',$this->branch_id);

   }
   #---------------------------------- CASHIER CASHBOOK START ---------------------------------------------#
   // cashier full cashbook
   public function cashierFullCashbook()
   {
     $result    = DB::table('cashbook')->where('branch_id',$this->branch_id)->where('admin_type',3)->whereNotIn('tr_status',[2])->orderby('created_at','asc')->get();
     return view('cashbook.cashierFullCashbook')->with('result',$result)->with('branch_id',$this->branch_id) ; 
   }
   // cashier today cashbook
   public function cashierTodayCashbook()
   {
        $rcdate    = $this->rcdate ;
        // get clossing balnce befor today
        $previous_colum_balance = DB::table('cashbook')->where('branch_id',$this->branch_id)->where('admin_type',3)->whereNotIn('tr_status',[2])->where('created_at','<', $this->rcdate)->get();
        // get befor clossing balance
        $previous_earn_amount = 0 ;
        $previous_cost_amount = 0 ;
        $previous_c2b         = 0 ;
        $previous_b2c         = 0 ;
        $balance_transfer     = 0 ;
        $previous_send_transfer = 0 ;
        $previous_receive_transfer = 0 ;
        $previous_non_cash = 0 ;
        $previous_m2c      = 0 ;
        foreach ($previous_colum_balance as $previous_colum_balances) {
        $previous_earn_amount = $previous_earn_amount + $previous_colum_balances->earn ;

        $previous_cost_amount = $previous_cost_amount + $previous_colum_balances->cost ;

         $previous_non_cash = $previous_non_cash + $previous_colum_balances->get_non_cash_payment ;

        $previous_c2b      = $previous_c2b + $previous_colum_balances->c2b ;

    $previous_b2c      = $previous_b2c + $previous_colum_balances->b2c ;
     $previous_m2c      = $previous_m2c + $previous_colum_balances->m2c ;
    if($previous_colum_balances->balance_transfer_type == '1' OR $previous_colum_balances->balance_transfer_type == '0'){
    $previous_send_transfer = $previous_send_transfer + $previous_colum_balances->balance_transfer ;
    }else if($previous_colum_balances->balance_transfer_type == '2' OR $previous_colum_balances->balance_transfer_type == '0'){
    $previous_receive_transfer = $previous_receive_transfer + $previous_colum_balances->balance_transfer ;
            }
        }
    $previous_balance = $previous_earn_amount - $previous_cost_amount - $previous_c2b + $previous_b2c - $previous_send_transfer +  $previous_receive_transfer - $previous_non_cash + $previous_m2c ;
    // today cashbook
    $result = DB::table('cashbook')->where('branch_id',$this->branch_id)->where('admin_type',3)->whereNotIn('tr_status',[2])->where('created_at', $this->rcdate)->get();
    return view('cashbook.cashierTodayCashbook')->with('result',$result)->with('previous_balance',$previous_balance)->with('rcdate',$this->rcdate)->with('branch_id',$this->branch_id);
   }
   // cashier date wise cashbook
   public function cashierDatewiseCashbook()
   {
    return view('cashbook.cashierDatewiseCashbook');
   }
   // cashier date wise cashbook view
   public function cashierDatewiseCashbookView(Request $request)
   {
      $from_date = trim($request->from_date);
      $to_date   = trim($request->to_date);
      $from      = date('Y-m-d',strtotime($from_date)) ;
      $to        = date('Y-m-d',strtotime($to_date)) ;
      $next_form = date('Y-m-d', strtotime('+1 day', strtotime($from))) ;
      // get clossing balnce befor today
      $previous_colum_balance = DB::table('cashbook')->where('branch_id',$this->branch_id)->where('admin_type',3)->whereNotIn('tr_status',[2])->where('created_at','<=', $from)->get();
        // get befor clossing balance
        $previous_earn_amount = 0 ;
        $previous_cost_amount = 0 ;
        $previous_c2b         = 0 ;
        $previous_b2c         = 0 ;
        $balance_transfer     = 0 ;
        $previous_send_transfer = 0 ;
        $previous_receive_transfer = 0 ;
        $previous_non_cash = 0 ;
        $previous_m2c      = 0 ;
        foreach ($previous_colum_balance as $previous_colum_balances) {
        $previous_earn_amount = $previous_earn_amount + $previous_colum_balances->earn ;

        $previous_cost_amount = $previous_cost_amount + $previous_colum_balances->cost ;

         $previous_non_cash = $previous_non_cash + $previous_colum_balances->get_non_cash_payment ;

        $previous_c2b      = $previous_c2b + $previous_colum_balances->c2b ;

    $previous_b2c      = $previous_b2c + $previous_colum_balances->b2c ;
     $previous_m2c      = $previous_m2c + $previous_colum_balances->m2c ;
    if($previous_colum_balances->balance_transfer_type == '1' OR $previous_colum_balances->balance_transfer_type == '0'){
    $previous_send_transfer = $previous_send_transfer + $previous_colum_balances->balance_transfer ;
    }else if($previous_colum_balances->balance_transfer_type == '2' OR $previous_colum_balances->balance_transfer_type == '0'){
    $previous_receive_transfer = $previous_receive_transfer + $previous_colum_balances->balance_transfer ;
            }
        }
    $previous_balance = $previous_earn_amount - $previous_cost_amount - $previous_c2b + $previous_b2c - $previous_send_transfer +  $previous_receive_transfer - $previous_non_cash + $previous_m2c ;
    $result    = DB::table('cashbook')->where('branch_id',$this->branch_id)->where('admin_type',3)->whereNotIn('tr_status',[2])->whereBetween('created_at', [$next_form, $to])->orderby('created_at','asc')->get();
      return view('cashbook.cashierDatewiseCashbookView')->with('result',$result)->with('from',$from)->with('to',$to)->with('next_form',$next_form)->with('previous_balance', $previous_balance)->with('branch_id',$this->branch_id);

   }







   #----------------------------------- END CASHIER CASHBOOK -----------------------------------------------#
}
