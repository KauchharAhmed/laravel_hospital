<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests;
use DB;
use Session;

class BalanceTransfer extends Controller
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
     * transfer amount from cashier
     *
     * @return \Illuminate\Http\Response
     */
    public function cashierBalanceTransfer()
    {
     return view('balance_transfer.cashierBalanceTransfer');	
    }
	/**
    * cashier cash money send to admin .
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function cashierCashTransferToAdmin(Request $request)
    {
     $this->validate($request, [
    'transfer_amount'           => 'required',
    'confirm_transfer_amount'   => 'required',
    ]);
     $transfer_amount    			= trim($request->transfer_amount);
     $confirm_transfer_amount    	= trim($request->confirm_transfer_amount);
     $transfer_date                 = trim($request->transfer_date);
     $transferDate 					= date('Y-m-d',strtotime($transfer_date));
     $remarks        				= trim($request->remarks);
     #---------------------- DATE VALIDATION----------------------#
     if($transferDate > $this->rcdate){
        Session::put('failed','Sorry ! Enter Invalid Date. Please Enter Valid Date And Try Again ');
        return Redirect::to('cashierBalanceTransfer');
        exit();
     }
     #--------------------- END DATE VALIDATIN--------------------#
     if($transfer_amount !=  $confirm_transfer_amount){
     	 Session::put('failed','Sorry ! Transfer Amount And Confirm Transfer Amount Did Not Match');
        return Redirect::to('cashierBalanceTransfer');
        exit();
     }
     #----------------- pettycash check------------------------#
     $pettryCashAmt1 = DB::table('pettycash')->where('branch_id',$this->branch_id)->where('type',3)->limit(1)->first();;
     $available_pettyCash1 =  $pettryCashAmt1->pettycash_amount;
     if($available_pettyCash1 < $transfer_amount)
     {
        Session::put('failed','Sorry ! Insufficient Balance Of Your Petty Cash. Try Again After Available Petty Cash');
        return Redirect::to('cashierBalanceTransfer');
        exit();
     }
     #----------------- end petty cash-------------------------#
     // insiall that will be pendig
     // insert data into balance transfer (this table only for manager transfer)
     // status 0 = pending
     $data   = array();
     $data['branch_id'] 		= $this->branch_id ;
     $data['transfer_amount'] 	= $transfer_amount ;
     $data['status'] 			= 0;
     $data['remarks'] 			= $remarks ;
     $data['added_id'] 			= $this->loged_id ;
     $data['transfer_time'] 	= $this->current_time ;
     $data['transfer_date'] 	= $transferDate ;
     $data['created_at'] 		= $this->rcdate ;
     $query = DB::table('balance_transfer')->insert($data);
     if($query){
     	 Session::put('succes','Thanks , ' .$transfer_amount. ' Amount Transfer Successfully. Your Balance Will Be Adjusted After Approve By Manager');
        return Redirect::to('cashierBalanceTransfer');

     }else{
     	 Session::put('failed','Sorry ! Error Occured. Try Again');
        return Redirect::to('cashierBalanceTransfer');
     }
    }
    #------------------------------- MANAGER ---------------------------------------------#
     /**
     * manager transfer cash pending amount list for admin
     *
     * @return \Illuminate\Http\Response
     */
    public function cashierBalanceTransferReceiveByManager()
    {
    $result = DB::table('balance_transfer')
    ->join('admin', 'balance_transfer.added_id', '=', 'admin.id')
    ->where('balance_transfer.branch_id',$this->branch_id)
    ->where('balance_transfer.status',0)
    ->select('balance_transfer.*','admin.name')
    ->get();
    return view('balance_transfer.cashierBalanceTransferReceiveByManager')->with('result',$result);
    }
    // manager  approved balance transfer
    public function managerApprovedBalanceTransfer($id)
    {
    	 // get balance transfer info by id
        $balance_transfer_query = DB::table('balance_transfer')->where('branch_id',$this->branch_id)->where('id',$id)->first();
        $from_branch_id         = $balance_transfer_query->branch_id ;
        $transfer_amount        = $balance_transfer_query->transfer_amount ;
        $trDate                 = $balance_transfer_query->transfer_date ;
        $cashier_id             = $balance_transfer_query->added_id ;
        $c_purpose = "Cash Transfer To Manager";
        $m_purpose = "Receive Cash From Manager";
    #--------------------- INSERT THE CASHBOOK-------------------------------#
     $pettryCashAmt1 = DB::table('pettycash')->where('branch_id',$this->branch_id)->where('type',3)->limit(1)->first();;
     $available_pettyCash1 =  $pettryCashAmt1->pettycash_amount;
     if($available_pettyCash1 < $transfer_amount)
     {
        Session::put('failed','Sorry ! Insufficient Balance Of Cashier Petty Cash. Try Again After Available Petty Cash');
        return Redirect::to('cashierBalanceTransferReceiveByManager');
        exit();
     }

     // COLUM status 
     //24 = manager send cash
    // balance transfer type 1 = out of pettycash
        $data8                        = array();
        $data8['overall_branch_id']   = $this->branch_id ;
        $data8['branch_id']           = $this->branch_id ;
        $data8['admin_id']            = $cashier_id ;
        $data8['admin_type']          = 3 ;
        $data8['balance_transfer']       = $transfer_amount;
        $data8['balance_transfer_type']  = 1;
        $data8['status']              = 24 ;
        $data8['tr_status']           = 1 ;
        $data8['purpose']             = $c_purpose;
        $data8['added_id']            = $cashier_id;
        $data8['created_time']        = $this->current_time;
        $data8['created_at']          = $trDate;
        $data8['on_created_at']       = $this->rcdate;
        DB::table('cashbook')->insert($data8);
    #--------------------- END INSERT THE CASHBOOK---------------------------#
    #--------------------- GET LAST CASH BOOK ID  ---------------------------#
     $last_cashbook_id_query = DB::table('cashbook')->orderBy('id','desc')->limit(1)->first();
     $last_cashbook_id       = $last_cashbook_id_query->id ; 
    #-------------------- GET LAST CASH BOOK ID -----------------------------#
     // update balance transfer table
     $data1 = array();
     $data1['sender_cashbook_id'] = $last_cashbook_id ;
     $data1['status']             = 1 ;
     $data1['decision_date']      = $this->rcdate ;
     $data1['decision_time']      = $this->current_time ;
     DB::table('balance_transfer')->where('branch_id',$this->branch_id)->where('id',$id)->update($data1);
    #-------------------- reciver -----------------------------------------#
    #--------------------- INSERT THE CASHBOOK-------------------------------#
    // COLUM status 
    //25 = admin receive cash
    // balance transfer type 2 = in of pettycash
        $data9                        = array();
        $data9['overall_branch_id']   = $this->branch_id ;
        $data9['branch_id']           = $this->branch_id ;
        $data9['admin_id']            = $this->loged_id ;
        $data9['admin_type']          = 2 ;
        $data9['balance_transfer']       = $transfer_amount;
        $data9['balance_transfer_type']  = 2;
        $data9['status']              = 25 ;
        $data9['tr_status']           = 1 ;
        $data9['purpose']             = $m_purpose;
        $data9['added_id']            = $this->loged_id;
        $data9['created_time']        = $this->current_time;
        $data9['created_at']          = $trDate;
        $data9['on_created_at']       = $this->rcdate;
        DB::table('cashbook')->insert($data9);
    #--------------------- END INSERT THE CASHBOOK---------------------------#
    #--------------------- GET LAST CASH BOOK ID  ---------------------------#
     $last_cashbook_id_query_two = DB::table('cashbook')->orderBy('id','desc')->limit(1)->first();
     $last_cashbook_id_two       = $last_cashbook_id_query_two->id ; 
    #-------------------- GET LAST CASH BOOK ID -----------------------------#
     $data2 = array();
     $data2['reciver_cashbook_id'] =  $last_cashbook_id_two ;
     $data2['manager_id']          =  $this->loged_id ;
     DB::table('balance_transfer')->where('branch_id',$this->branch_id)->where('id',$id)->update($data2);
    #-------------------- end receiver-------------------------------------#
     #---------------------PETTYCASH SECTION-------------------------------#
     $admin_pettryCashAmt = DB::table('pettycash')->where('branch_id',$this->branch_id)->where('type',2)->first();
     $admin_available_pettyCash =  $admin_pettryCashAmt->pettycash_amount;
     $data3 = array();
     $data3['pettycash_amount']     = $admin_available_pettyCash + $transfer_amount ;
     $admin_update_peetycash_query        = DB::table('pettycash')->where('branch_id',$this->branch_id)->where('type',2)->update($data3);
     // for manager pettycash
     $manager_pettryCashAmt = DB::table('pettycash')->where('branch_id',$this->branch_id)->where('type',3)->first();
     $manager_available_pettyCash =  $manager_pettryCashAmt->pettycash_amount;
     $data4 = array();
     $data4['pettycash_amount']     = $manager_available_pettyCash - $transfer_amount ;
     $admin_update_peetycash_query        = DB::table('pettycash')->where('branch_id',$this->branch_id)->where('type',3)->update($data4);
     Session::put('succes','Thanks , Cash Get Confirm Successfully');
     return Redirect::to('cashierBalanceTransferReceiveByManager');
    }

    // manager reject not get amount
    public function managerRejectBalanceTransfer($id)
    {
     $data1 = array();
     $data1['status']             = 2 ;
     $data1['manager_id']         = $this->loged_id ;
     $data1['decision_date']      = $this->rcdate ;
     $data1['decision_time']      = $this->current_time ;
     DB::table('balance_transfer')->where('branch_id',$this->branch_id)->where('id',$id)->update($data1);
    Session::put('succes','Thanks , Cash Not Get Confirm Successfully');
    return Redirect::to('cashierBalanceTransferReceiveByManager');
    }


}
