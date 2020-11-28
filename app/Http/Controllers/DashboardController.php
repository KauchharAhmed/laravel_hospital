<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests;
use DB;
use Session;
class DashboardController extends Controller
{
	 private $rcdate ;
     private $current_time ;
     private $loged_id ;
     private $branch_id ;
     private $type ;
     public function __construct() {
     $this->rcdate 		  = date('Y-m-d');
	$this->current_time = date('H:i:s');
    $this->loged_id     = Session::get('admin_id');
    $this->branch_id    = Session::get('branch_id');
    $this->type     	= Session::get('type');
	}

	#--------------------- Admin Dashboard -----------------------#
	public function adminDashboard()
	{
	    return view('admin.adminDashboard');
	}
	#-------------------- end admin dashborad---------------------#
    #-------------------- manager dashborad-----------------------#
    public function managerDashboard()
    {
    $cash = DB::table('pettycash')->where('branch_id',$this->branch_id)->where('type',2)->first();
    $petty_cash = $cash->pettycash_amount ;
    $bank_balance = DB::table('bank_balance')->where('branch_id',$this->branch_id)->get();
    $total_bank_balance = 0 ;
    foreach ($bank_balance as $bank_balance_amt) {
        $total_bank_balance = $total_bank_balance + $bank_balance_amt->total_balance ; 
    }
     return view('admin.managerDashboard')->with('petty_cash',$petty_cash)->with('total_bank_balance',$total_bank_balance);	
    }
    // manger add all cassiery pettycash
    public function managerAddOpeningCashierBalance()
    {
    	return view('admin.managerAddOpeningCashierBalance');
    }
    // add cashier opening balance
    public function addCashierOpeningBalanceInfo(Request $request)
    {
    $this->validate($request, [
    'amount'               => 'required',
    'confirm_amount'       => 'required',
    'tr_date'              => 'required'
    ]);
     $amount            = trim($request->amount);
     $confirm_amount 	= trim($request->confirm_amount);
     $tr_date           = trim($request->tr_date);
     $trDate            = date('Y-m-d',strtotime($tr_date)) ;
     #---------------------- DATE VALIDATION----------------------#
     if($trDate > $this->rcdate){
        Session::put('failed','Sorry ! Enter Invalid Date. Please Enter Valid Date And Try Again ');
        return Redirect::to('managerAddOpeningCashierBalance');
        exit();
     }
     #--------------------- END DATE VALIDATIN--------------------#
     // check amount and confirm amount
     if($amount != $confirm_amount){
      Session::put('failed','Sorry ! Amount And Confirm Confirm Amount Did Not Match. Try Again');
        return Redirect::to('managerAddOpeningCashierBalance');  
        exit();
     }
     // check duplicate entry
     $count = DB::table('pettycash')->where('branch_id',$this->branch_id)->where('type',3)->count();
     if($count > 0){
         Session::put('failed','Sorry ! Cashiers Opening Balance Already Added.');
        return Redirect::to('managerAddOpeningCashierBalance');  
        exit();
     }
     // insert the pettycash
     $data=array();
     $data['branch_id']           = $this->branch_id ;
     $data['type']                = 3 ;
     $data['pettycash_amount']    = $amount ;
     $data['created_at']          = $this->rcdate ;
     $query = DB::table('pettycash')->insert($data);
     if($query){
        // insert into cahsbook opening balance
        // status 3 = cashier opening balance
        $data_cashbook                		  = array();
        $data_cashbook['overall_branch_id']   = $this->branch_id ;
        $data_cashbook['branch_id']   		  = $this->branch_id ;
        $data_cashbook['admin_type']   		  = 3 ;
        $data_cashbook['earn']        = $amount ;
        $data_cashbook['status']      = 3 ;
        $data_cashbook['tr_status']   = 1 ;
        $data_cashbook['purpose']     = 'Branch Cashier Opening Balance';
        $data_cashbook['added_id']    = $this->loged_id ;
        $data_cashbook['created_time']  = $this->current_time ;
        $data_cashbook['created_at']    = $trDate ;
        $data_cashbook['on_created_at'] = $this->rcdate;
        $query2 = DB::table('cashbook')->insert($data_cashbook);
        if($query2){
        Session::put('succes','Thanks , Opening Balance Added Sucessfully');
        return Redirect::to('managerAddOpeningCashierBalance');
        }else{
        Session::put('failed','Sorry ! Error Occued. Try Again');
        return Redirect::to('managerAddOpeningCashierBalance');
        }
       }else{
        Session::put('failed','Sorry ! Error Occued. Try Again');
        return Redirect::to('managerAddOpeningCashierBalance');
    }
}
#-------------------- end manager dashboard--------------------#
// cashier dashboard
public function cashierDashboard()
{
  $cash = DB::table('pettycash')->where('branch_id',$this->branch_id)->where('type',3)->first();
  $petty_cash = $cash->pettycash_amount ;
  // today pathology bill info
  $count_patology_bill = DB::table('pathology_bill')->where('branch_id',$this->branch_id)->where('bill_date',$this->rcdate)->count();
  $patology_bill_info  = DB::table('pathology_bill_transaction')->where('branch_id',$this->branch_id)->where('tr_date',$this->rcdate)->get();
  $pathology_return_amt = DB::table('pathology_bill_transaction')->where('branch_id',$this->branch_id)->where('tr_date',$this->rcdate)->where('status',2)->get();
  $count_opd_bill     = DB::table('opd_bill')->where('branch_id',$this->branch_id)->where('bill_date',$this->rcdate)->count();
  $count_ipd_admission =  DB::table('tbl_ipd_admission')->where('branch_id',$this->branch_id)->where('admit_date',$this->rcdate)->count();
  $count_ipd_patology_bill = DB::table('tbl_ipd_pathology_bill')->where('branch_id',$this->branch_id)->where('bill_date',$this->rcdate)->count();
  $count_ipd_service_bill  = DB::table('tbl_ipd_service_bill')->where('branch_id',$this->branch_id)->where('bill_date',$this->rcdate)->count();
 $count_ipd_clearence_bill  = DB::table('tbl_ipd_clear_bill')->where('branch_id',$this->branch_id)->where('bill_date',$this->rcdate)->count();
 $count_ot_booking          = DB::table('tbl_ot_booking')->where('branch_id',$this->branch_id)->where('booking_date',$this->rcdate)->count();
  $count_ot_clear          = DB::table('tbl_ot_clear_bill')->where('branch_id',$this->branch_id)->where('bill_date',$this->rcdate)->count();

 return view('admin.cashierDashboard')->with('petty_cash',$petty_cash)->with('count_patology_bill',$count_patology_bill)->with('patology_bill_info',$patology_bill_info)->with('pathology_return_amt',$pathology_return_amt)->with('count_opd_bill',$count_opd_bill)->with('count_ipd_admission',$count_ipd_admission)->with('count_ipd_patology_bill',$count_ipd_patology_bill)->with('count_ipd_service_bill',$count_ipd_service_bill)->with('count_ipd_clearence_bill',$count_ipd_clearence_bill)->with('count_ot_booking',$count_ot_booking)->with('count_ot_clear',$count_ot_clear); 
}
// doctor dashboard
public function doctorDashboard()
{
    return view('admin.doctorDashboard') ; 
}

}
