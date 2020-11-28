<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests;
use DB;
use Session;
class SalaryController extends Controller
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
     * manage salary form
     * 
     * @return \Illuminate\Http\Response
     */
    public function manageSalary()
    { 
     $result = DB::table('staff')
    ->join('designation', 'staff.desi_id', '=', 'designation.id')
    ->orderBy('name','asc')
    ->select('staff.*','designation.desi_name')
    ->where('staff.branch_id',$this->branch_id)
    ->where('staff.status',1)
    ->get();
        // year
      $year   = DB::table('salary')->distinct()->get(['year']);
      return view('salary.manageSalary')->with('result',$result)->with('year',$year);
      }
     // change salary
     public function changeSalary(Request $request)
     {
     $this->validate($request, [
    'staff'                   => 'required',
    'year'                    => 'required',
    'month'                   => 'required',
    'salary_amount'           => 'required',
    'confirm_salary_amount'	  => 'required'
    ]);
     $staff          			= trim($request->staff);
     $year          			= trim($request->year);
     $month          			= trim($request->month);
     $salary_amount     		= trim($request->salary_amount);
     $confirm_salary_amount     = trim($request->confirm_salary_amount);
     $remarks        	    	= trim($request->remarks);
     #------------------------- match salary amount----------------------#
        if($salary_amount != $confirm_salary_amount){
        Session::put('failed','Sorry ! Salary Amount And Confirm Salary Amount Did Not Match. Try Again');
        return Redirect::to('manageSalary');  
        exit();
        }
     #------------------------ end match salary amount--------------------#
     #------------------------- Check duplicate entry----------------------#
     $count = DB::table('salary')->where('year',$year)->where('month',$month)->where('staff_id',$staff)->count();
     if($count > 0){
        Session::put('failed','Sorry ! Already Added Salary Of This Year And Month. Try Again');
        return Redirect::to('manageSalary');  
        exit();

     }
     #------------------------- end Check duplicate entry----------------------#
     $data=array();
     $data['branch_id']     = $this->branch_id;
     $data['staff_id']      = $staff;
     $data['year']          = $year ;
     $data['month']         = $month ;
     $data['salary_amount'] = $salary_amount ;
     $data['remarks']       = $remarks;
     $data['added_id']    	= $this->loged_id  ;
     $data['created_at']    = $this->rcdate ;
     $query = DB::table('salary')->insert($data);
      if($query){
        Session::put('succes','Thanks , Salary Added Sucessfully');
        return Redirect::to('manageSalary');  
        }else{
        Session::put('failed','Sorry ! Error Occued. Try Again');
        return Redirect::to('manageSalary');  
        }
     }
    // salary payment
     public function salaryPaymentForm()
     {
     // with all staff
     $result = DB::table('staff')
    ->join('designation', 'staff.desi_id', '=', 'designation.id')
    ->orderBy('name','asc')
    ->select('staff.*','designation.desi_name')
    ->where('staff.branch_id',$this->branch_id)
    ->where('staff.status',1)
    ->get();
    // year
    $year   = DB::table('salary')->distinct()->get(['year']);
    return view('salary.salaryPaymentForm')->with('result',$result)->with('year',$year);
   }
      /**
    * salary calculation .
    * ajax request 
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function getSalaryCalculation(Request $request)
    {
        $staff = trim($request->staff);
        $year  = trim($request->year);
        $month = trim($request->month);
        $staff_count = DB::table('salary')->where('staff_id',$staff)->count() ;
        if($staff_count > 0){
           // get salary by year and month
             $salary_query = DB::table('salary')
             ->where('year',$year)
             ->where('staff_id',$staff)
             ->where('month','<=',$month)
             ->orderBy('id','desc')
             ->take(1)
             ->get() ;
             foreach ($salary_query as $value) {
                $monthly_salary = $value->salary_amount ;
                $salary_id = $value->id ;
              }

        }else{
           $salary_query = DB::table('salary')->where('staff_id',$staff)->first() ;
           $monthly_salary = $salary_query->salary_amount ;
           $salary_id = $salary_query->id ;
        }
        // total payment 
        $payment_count = DB::table('salary_payment')->where('year',$year)->where('month',$month)->where('staff_id',$staff)->count();
        if($payment_count > 0){
        $payment_query = DB::table('salary_payment')->where('year',$year)->where('month',$month)->where('staff_id',$staff)->where('status',1)->get();
        $payment_amount = 0 ;
        $total_fine     = 0 ;
        foreach ($payment_query as $paymentValue) {
            $payment_amount = $payment_amount + $paymentValue->payment_amount  ;
            $total_fine     = $total_fine + $paymentValue->fine  ;
        }
        $total_payment_amount = $payment_amount + $total_fine ;

        }else{
            $total_payment_amount = 0 ;
        }
        // due  calculation
        $total_due = $monthly_salary - $total_payment_amount ;
        echo $monthly_salary.'/'.$total_payment_amount.'.00'.'/'.$total_due.'.00' ;
    }
    // payemnt salary
    public function paymentSalary(Request $request)
    {
     $this->validate($request, [
    'staff'                   => 'required',
    'year'                    => 'required',
    'month'                   => 'required',
    'salary_type'             => 'required',
    'payment_amount'          => 'required',
    'confirm_payment_amount'  => 'required',
    'tr_date'                 => 'required'
    ]);
     $staff                     = trim($request->staff);
     $year                      = trim($request->year);
     $month                     = trim($request->month);
     $salary_type               = trim($request->salary_type);
     $payment_amountt           = trim($request->payment_amount);
     $confirm_payment_amount    = trim($request->confirm_payment_amount);
     $fine_amount               = trim($request->fine_amount);
     $remarks                   = trim($request->remarks);
     $tr_date                   = trim($request->tr_date);
     $trDate                    = date('Y-m-d',strtotime($tr_date));
     // total Paid Amount With Fine Amount
     $total_paid = $payment_amountt + $fine_amount ;
   #--------------------- DATE VALIDATION --------------------------------#
    if($trDate > $this->rcdate){
        Session::put('failed','Sorry ! Enter Wrong Expense Date. Expense Date Will Not Be Big Than Current Date');
        return Redirect::to('salaryPaymentForm');
        exit();
      }
     #--------------------- END DATE VALIDATION -----------------------------#
     #------------------------- match salary amount----------------------#
    if($payment_amountt != $confirm_payment_amount){
    Session::put('failed','Sorry ! Payment Amount And Confirm Payment Amount Did Not Match. Try Again');
    return Redirect::to('salaryPaymentForm');  
    exit();
    }
     #------------------------ end match salary amount--------------------------------------#
     #------------------------- payment amount not big than due amount----------------------#
        $staff_count = DB::table('salary')->where('staff_id',$staff)->count() ;
            if($staff_count > 0){
               // get salary by year and month
                 $salary_query = DB::table('salary')
                 ->where('year',$year)
                 ->where('staff_id',$staff)
                 ->where('month','<=',$month)
                 ->orderBy('id','desc')
                 ->take(1)
                 ->get() ;
                 foreach ($salary_query as $value) {
                    $monthly_salary = $value->salary_amount ;
                    $salary_id      = $value->id ;
                  }

            }else{
               $salary_query    = DB::table('salary')->where('staff_id',$staff)->first() ;
               $monthly_salary  = $salary_query->salary_amount ;
               $salary_id       = $salary_query->id ;
            }
        // total payment 
        $payment_count = DB::table('salary_payment')->where('year',$year)->where('month',$month)->where('staff_id',$staff)->count();
        if($payment_count > 0){
        $payment_query = DB::table('salary_payment')->where('year',$year)->where('month',$month)->where('staff_id',$staff)->where('status',1)->get();
        $payment_amount = 0 ;
        $total_fine     = 0 ;
        foreach ($payment_query as $paymentValue) {
            $payment_amount = $payment_amount + $paymentValue->payment_amount  ;
            $total_fine     = $total_fine + $paymentValue->fine  ;
        }
        $total_payment_amount = $payment_amount + $total_fine ;

        }else{
            $total_payment_amount = 0 ;
        }
        // due  calculation
        $total_due = $monthly_salary - $total_payment_amount ;
        
        if($salary_type == '1'){
        if($total_paid > $total_due)
        {
        Session::put('failed','Sorry ! Payment Amount Big Than Total Due Amount. Try Again');
        return Redirect::to('salaryPaymentForm');  
        exit();
        }
    }
     #------------------------- end payment amount not big than due amount----------------------#
     #---------------------- CHECK THE PETTYCAH AMOUNT ---------------------#
     $pettryCashAmt1            =  DB::table('pettycash')->where('branch_id',$this->branch_id)->where('type',2)->limit(1)->first();
     $available_pettyCash1      =  $pettryCashAmt1->pettycash_amount;
     if($available_pettyCash1 < $payment_amountt)
     {
        Session::put('failed','Sorry ! Insufficient Balance Of Your Petty Cash. Try Again After Available Petty Cash');
        return Redirect::to('salaryPaymentForm');
        exit();
     }
     #--------------------- END CHECK THE PETTYCAH AMOUNT--------------------#
     $purpose = "Salary Payment";
    #--------------------- INSERT THE CASHBOOK-------------------------------------------------#
	     // COLUM status 
	     //21 = paid salary
        $data_cashbook                        = array();
        $data_cashbook['overall_branch_id']   = $this->branch_id ;
        $data_cashbook['branch_id']           = $this->branch_id ;
        $data_cashbook['admin_id']            = $this->loged_id  ;
        $data_cashbook['admin_type']          = 2 ;
        $data_cashbook['cost']                = $payment_amountt ;
        $data_cashbook['profit_cost']         = $payment_amountt ;
        $data_cashbook['status']              = 21 ;
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
    #---------------------------- get last payemnt number--------------------------------------#
     $payment_number_query = DB::table('salary_payment')->where('branch_id',$this->branch_id)->count();
     if($payment_number_query > 0){
      $get_payment_number_query = DB::table('salary_payment')->where('branch_id',$this->branch_id)->orderBy('id','desc')->take(1)->first();
      $payment_number = $get_payment_number_query->payment_number + 1 ; 
     }else{
        $payment_number = 1 ;
     }
    #---------------------------- end get last payemnt number----------------------------------#
    #---------------------------- inseert salary_payment table---------------------------------#
     $data=array();
     $data['branch_id']             = $this->branch_id ;
     $data['cashbook_id']           = $last_cashbook_id ;
     $data['payment_number']        = $payment_number;
     $data['staff_id']              = $staff;
     $data['year']                  = $year ;
     $data['month']                 = $month ;
     $data['salary_id']             = $salary_id ;
     $data['payment_amount']        = $payment_amountt ;
     $data['fine']                  = $fine_amount ;
     $data['status']                = $salary_type ;
     $data['remarks']               = $remarks;
     $data['added_id']              = $this->loged_id  ;
     $data['created_at']            = $trDate ;
     $data['on_created_at']         = $this->rcdate ;
     DB::table('salary_payment')->insert($data);
    #---------------------------- end inseert salary_payment table------------------------------#
     $pettryCashAmt = DB::table('pettycash')->where('branch_id',$this->branch_id)->where('type',2)->limit(1)->first();
     $available_pettyCash =  $pettryCashAmt->pettycash_amount;
     $data2 = array();
     $data2['pettycash_amount']     = $available_pettyCash - $payment_amountt ;
     $update_peetycash_query        = DB::table('pettycash')->where('branch_id', $this->branch_id)->where('type',2)->update($data2);
    #------------------- END REDUCE THE PETTY CASH OF ADMIN------------------#
    #-------------- END REDUCE THE AMOUNT OF PRETTY CASH --------------------------# 

      if($update_peetycash_query){
         return Redirect::to('salaryReciptPrint/'.$staff.'/'.$salary_id.'/'.$year.'/'.$month.'/'.$salary_type);
        }else{
        Session::put('failed','Sorry ! Error Occued. Try Again');
        return Redirect::to('salaryPaymentForm');  
        }

    }
}
