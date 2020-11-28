<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests;
use DB;
use Session;

class PurchaseController extends Controller
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
     * Display purchase form page.
     *
     * @return \Illuminate\Http\Response
     */
    public function addPurchase()
    {
         $result    = DB::table('product')->get();
         $supplier  = DB::table('supplier')->where('branch_id',$this->branch_id)->get();
    	 return view('purchase.addPurchase')->with('result',$result)->with('supplier',$supplier);
    }
    /**
    * new purchase bill create.
    * ajax request
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function createPurchaseBill(Request $request)
    {
     $branch                = $this->branch_id ;
     $supplier              = trim($request->supplier);
     $purchase_date         = trim($request->purchase_date);
     $purchaseDate          = date('Y-m-d',strtotime($purchase_date)) ;
     $memo_no               = trim($request->memo_no);
     $total_amount          = trim($request->total_amount);
     $total_paid            = trim($request->total_paid);
     $array_invoice_data    = $request->arr; 
     $invoice_datas         = json_decode($array_invoice_data);

    #---------------------- DATE VALIDATION----------------------#
     if($purchaseDate > $this->rcdate){
      echo "d1";
      exit();
     }
     #--------------------- END DATE VALIDATIN--------------------#
     #--------------------- CHECK AVAILABLE PETTY CASH ------------------------#
     // get admin petty cash.which branch id = 0 
     $pretty_cash_query = DB::table('pettycash')->where('branch_id', $this->branch_id)->where('type',2)->first();
     $available_pettyCash = $pretty_cash_query->pettycash_amount ;
     if($available_pettyCash < $total_paid){
        // not available petty cash
       echo "p1";
       exit();
     }
     #--------------------- END AVAILABLE PETTY CASH----------------------------#
     #--------------------- AMOUNT BIG THAN TOTAL AMOUNT ------------------------#
    if($total_amount < $total_paid){
       // paid amount not big than total amount
       echo "p2";
       exit();
     }
     #-------------------END AMOUNT BIG THAN TOTAL AMOUNT------------------------#
     #---------------- CHECK MEMO NUMBER (duplicate memo not allowed) -----------#
     if($memo_no != ''){
        $memo_check = DB::table('purchase')->where('supplier_id',$supplier)->where('memo_no',$memo_no)->count() ;
        if($memo_check > 0){
             // memo number exits
             echo "p3";
             exit();
        }
     }
     #--------- CREATE THE MAIN INVOICE (INSERT PURCHASE TABEL)-------------- ---#
     // get invoice number
     $invoice_query = DB::table('purchase')->where('branch_id',$this->branch_id)->orderBy('invoice','desc')->count();
     if($invoice_query == 0){
        $invoice_number = 1 ;
     }else{
     $invoice_query_row = DB::table('purchase')->where('branch_id',$this->branch_id)->orderBy('invoice','desc')->first();
     $invoice_number    =  $invoice_query_row->invoice + 1 ;
     }
     #------------------------- insert the cashbook ---------------------------#
    // insert cashbook
    // status = 4
    // tr status = 1 by cash transaction
        $data_cashbook                        = array();
        $data_cashbook['overall_branch_id']   = $this->branch_id ;
        $data_cashbook['branch_id']           = $this->branch_id ;
        $data_cashbook['admin_id']            = $this->loged_id  ;
        $data_cashbook['admin_type']          = 2 ;
        $data_cashbook['cost']                = $total_paid ;
        $data_cashbook['profit_cost']         = $total_amount ;
        $data_cashbook['status']              = 4 ;
        $data_cashbook['tr_status']           = 1 ;
        $data_cashbook['purpose']             = 'Purchase Products';
        $data_cashbook['added_id']            = $this->loged_id;
        $data_cashbook['created_time']        = $this->current_time;
        $data_cashbook['created_at']          = $purchaseDate;
        $data_cashbook['on_created_at']       = $this->rcdate;
        DB::table('cashbook')->insert($data_cashbook);
    #------------------------ end insert the cashbook -------------------------#
    #--------------------- GET LAST CASH BOOK ID  ---------------------------#
     $last_cashbook_id_query = DB::table('cashbook')->orderBy('id','desc')->limit(1)->first();
     $last_cashbook_id       = $last_cashbook_id_query->id ; 
    #-------------------- GET LAST CASH BOOK ID -----------------------------#
     // total quantity
     $total_quantity = 0 ;
     foreach ($invoice_datas as $quantity) {
         $total_quantity = $total_quantity + $quantity[1]; 
     }
     // insert data into purchase table
     $data = array();
     $data['cashbook_id']    = $last_cashbook_id ;
     $data['invoice']        = $invoice_number ;
     $data['memo_no']        = $memo_no ;
     $data['supplier_id']    = $supplier ;
     $data['branch_id']      = $branch ;
     $data['total_quantity'] = $total_quantity ;
     $data['total_price']    = $total_amount ;
     $data['total_payment']  = $total_paid ;
     $data['added_id']       = $this->loged_id ;
     $data['purchase_date']  = $purchaseDate;
     $data['created_at']     = $this->rcdate;
     $insert_purchase_query  = DB::table('purchase')->insert($data); 
    #--------- END CREATE THE MAIN INVOICE (INSERT PURCHASE TABEL)--------------#

    #--------- CREATE THE PURCHASE PRODUCT (INSERT PURCHASE PRODUCT TABEL)------#
    foreach ($invoice_datas as $product_info) {
    $product_id         = $product_info[0]; 
    $color_id           = $product_info[5]; 
    $purchase_price     = $product_info[3];
    $sub_quantity       = $product_info[1];
    $sub_total_price    = $product_info[4];
     $data1 = array();
     $data1['cashbook_id']      = $last_cashbook_id ;
     $data1['invoice_number']   = $invoice_number ;
     $data1['supplier_id']      = $supplier ;
     $data1['branch_id']        = $branch ;
     $data1['product_id']       = $product_id ;
     $data1['purchase_price']   = $purchase_price ;
     $data1['total_quantity']   = $sub_quantity ;
     $data1['total_price']      = $sub_total_price ;
     $data1['purchase_date']    = $purchaseDate;
     $data1['created_at']       = $this->rcdate;
     $insert_purchase_product_query  = DB::table('purchase_product')->insert($data1);
     }
    #--------- END CREATE THE PURCHASE PRODUCT (INSERT PURCHASE PRODUCT TABEL)-----#

    #---- REDUCE THE AMOUNT OF PRETTY CASH (PETTY CASH TABLE WHERE branch id = 0)-- #
     $data2 = array();
     $data2['pettycash_amount']     = $available_pettyCash - $total_paid ;
     $update_peetycash_query        = DB::table('pettycash')->where('branch_id', $this->branch_id)->where('type',2)->update($data2) ;
    #-------------- END REDUCE THE AMOUNT OF PRETTY CASH --------------------------#
    #------------ ADD THE DUE AMOUNT OF SUPPLIER (supplier_due table)------------- #
     $due_amount = $total_amount - $total_paid ;
     // get database supplier due
     $supplier_due_query    = DB::table('supplier_due')->where('supplier_id',$supplier)->first();
     $database_supplier_due = $supplier_due_query->total_due_amount ;
     $now_supplier_due      = $database_supplier_due + $due_amount ;
     $data3 = array();
     $data3['total_due_amount'] = $now_supplier_due ;
     $update_supplier_due = DB::table('supplier_due')->where('supplier_id',$supplier)->update($data3);
    #-------------- END ADD THE DUE AMOUNT OF SUPPLIER (supplier_due table)--------#

    #-------------------------- INSERT PAYMENT LEDGER-------------------------------#
  // if custome new then previous due insert
   $check_supplier_id_ledger = DB::table('payment_ledger')->where('supplier_id',$supplier)->where('branch_id',$this->branch_id)->count();
   if($check_supplier_id_ledger == '0'){
       $supplier_ledger_previous_due_query = DB::table('supplier')->where('id',$supplier)->where('branch_id',$this->branch_id)->first();
    $supplier_ledger_previous_due = $supplier_ledger_previous_due_query->supplier_due ;
    if($supplier_ledger_previous_due != '0.00'){
        $dataledger['branch_id']      = $this->branch_id ;
        $dataledger['supplier_id']    = $supplier ;
        $dataledger['payable_amount'] = $supplier_ledger_previous_due ;
        $dataledger['status']          = 0 ;
        $dataledger['tr_status']       = 1 ;
        $dataledger['purpose'] = 'Previous Due' ;
        $dataledger['added_id'] = $this->loged_id ;
        $dataledger['created_at']     = $purchaseDate ;
        $dataledger['on_created_at']  = $this->rcdate ;
        DB::table('payment_ledger')->insert($dataledger);
    } 

        $dataledger1['branch_id']      = $this->branch_id ;
        $dataledger1['cashbook_id']    = $last_cashbook_id ;
        $dataledger1['invoice']        = $invoice_number ;
        $dataledger1['supplier_id']    = $supplier ;
        $dataledger1['payable_amount'] = $total_amount ;
        $dataledger1['payment_amount'] = $total_paid ;
        $dataledger1['status']         = 1 ;
        $dataledger1['tr_status']      = 1 ;
        $dataledger1['purpose']        = 'Create Purchase Invoice' ;
        $dataledger1['added_id']       = $this->loged_id ;
        $dataledger1['created_time']   = $this->current_time ;
        $dataledger1['created_at']     = $purchaseDate ;
        $dataledger1['on_created_at']  = $this->rcdate ;
        DB::table('payment_ledger')->insert($dataledger1);   
        }else{ 
        $dataledger1['branch_id']      = $this->branch_id ;
        $dataledger1['cashbook_id']    = $last_cashbook_id ;
        $dataledger1['invoice']        = $invoice_number ;
        $dataledger1['supplier_id']    = $supplier ;
        $dataledger1['payable_amount'] = $total_amount ;
        $dataledger1['payment_amount'] = $total_paid ;
        $dataledger1['status']         = 1 ;
        $dataledger1['tr_status']      = 1 ;
        $dataledger1['purpose']        = 'Create Purchase Invoice' ;
        $dataledger1['added_id']       = $this->loged_id ;
        $dataledger1['created_time']   = $this->current_time ;
        $dataledger1['created_at']     = $purchaseDate ;
        $dataledger1['on_created_at']  = $this->rcdate ;
        DB::table('payment_ledger')->insert($dataledger1);  
      }
    #-------------------------- END INSERT PAYMENT LEDGER---------------------------# 
    echo $invoice_number.'/'.$last_cashbook_id ;

 }
 #-------------------------- manage purchase invoice-------------------------#
    /**
     * Display the all purchas bill.
     *
     * @return \Illuminate\Http\Response
     */
    public function managePurchase()
    {
     $result = DB::table('purchase')
    ->join('supplier', 'purchase.supplier_id', '=', 'supplier.id')
    ->join('branch', 'purchase.branch_id', '=', 'branch.id')
    ->select('purchase.*','supplier.supplier_name','branch.name','branch.address','branch.mobile')
    ->get();
    return view('purchase.managePurchase')->with('result',$result);
    }
    /**
     * delete purchase invoice.
     *
     * @param  int  $invoice
     * @return \Illuminate\Http\Response
     */
    public function deletePurchase($invoice,$branch_id)
    {
      $get_all_product_query = DB::table('purchase_product')->where('invoice_number',$invoice)->where('branch_id',$branch_id)->get();
      foreach ($get_all_product_query as $value) {
        $product_id     = $value->product_id ;
        $color_id       = $value->color ;
        $total_quantity = $value->total_quantity; 
        // check product serial
        $serial_check_query = DB::table('product')->where('id',$product_id)->first();
        $serial_chek        = $serial_check_query->serial_status;
        #--------------------------- sold product check--------------------------#
        if($serial_chek == '1'){
                // if sold any serila product then not delete purchase
                // get product serial from product serial table
               $sold_product_query = DB::table('product_serial')->where('branch_id',$branch_id)->where('invoice_no',$invoice)->where('invoice_type',2)->where('status',1)->count();
               if($sold_product_query > 0){
                   Session::put('failed','Sorry ! Purchase Product Atleast One Quantity Sold. You Can Not Delete This Purchase Invoice Bill');
                    return Redirect::to('managePurchase');  
                    exit();
               }
 
          }
            // chek this product main stock
             $stock_query = DB::table('stock')->where('branch_id',$branch_id)->where('product_id',$product_id)->first();
             if($stock_query->available_stock < $total_quantity){
                    Session::put('failed','Sorry ! Not Available Stock For Any Product. You Can Not Delete This Purchase Invoice Bill');
                    return Redirect::to('managePurchase');  
                    exit();
             }
            // chek this product color stock
             $check_color_stock_query = DB::table('colorstock')->where('branch_id',$branch_id)->where('product_id',$product_id)->where('color_id',$color_id)->first();
             if($check_color_stock_query->available_stock < $total_quantity){
                    Session::put('failed','Sorry ! Not Available Color Stock For Any Product. You Can Not Delete This Purchase Invoice Bill');
                    return Redirect::to('managePurchase');  
                    exit();
             }     
        }
        #----------------------------- sold product check-------------------------#
        #----------------------------- get avarage price--------------------------#
         foreach ($get_all_product_query as $all_product) {
        $productId     = $all_product->product_id ;
        $colorId       = $all_product->color ;
        $totalQuantity = $all_product->total_quantity;
        $purchasePrice = $all_product->purchase_price;
        $totalPrice    = $all_product->total_price;
        $created_at    = $all_product->created_at;
        $purchase_price  = $all_product->purchase_price;
        
       $main_stocks = DB::table('stock')->where('branch_id', $branch_id )->where('product_id', $productId)->first();

      $database_product_price = $main_stocks->purchase_amount ;
      $database_table_stock   = $main_stocks->available_stock ; 
      $total_db_price  = $database_product_price *  $database_table_stock ;

      #----------------------- remove product quantity and pricer---------------------#
       $remove_quantity_price = $totalQuantity * $purchasePrice ;
       $minus_price           = $total_db_price - $remove_quantity_price ;
       $nowQuantity           = $database_table_stock - $totalQuantity ;
       if($nowQuantity == 0){
         $avarage_price  = $database_product_price ;
       }else{
       $avarage_price         = $minus_price / $nowQuantity ;
       }
       #--------------------------- end get avarage price-------------------------#
       #--------------------------- get serial number-----------------------------#
         $serial_check_query1 = DB::table('product')->where('id',$productId)->first();
        $serial_chek1        = $serial_check_query1->serial_status;
      if($serial_chek1 == '1'){
        // get serila number
        $serial_for_delete_query = DB::table('product_serial')->where('branch_id',$branch_id)->where('invoice_no',$invoice)->where('invoice_type',2)->get();
        foreach ($serial_for_delete_query  as  $implode_value) {
            $implode_array[] = $implode_value->serial ;
        }
        $serial_for_delete = implode(',',$implode_array);
      }else{
        $serial_for_delete = '';
      }

       #--------------------------- end get serial number-------------------------#

       #--------------------------- delete log------------------------------------#
       $delete_log                          = array(); 
       $delete_log['branch_id']             = $branch_id ;
       $delete_log['product_id']            = $productId ;
       $delete_log['color_id']              = $colorId ;
       $delete_log['invoice_number']        = $invoice ;
       $delete_log['befor_avarage_price']   = $database_product_price ;
       $delete_log['after_avarage_price']   = $avarage_price ;
       $delete_log['purchase_price']        = $purchase_price ;
       $delete_log['total_quantity']        = $totalQuantity ;
       $delete_log['total_price']           = $totalPrice ;
       $delete_log['serial']                = $serial_for_delete ;
       $delete_log['created_at']            = $created_at ;
       $delete_log['delete_at']             = $this->rcdate ;
       DB::table('delete_purchase_log')->insert($delete_log);
       #--------------------------- end delete log--------------------------------#
       // update
       $update_stock_info = array();
       $update_stock_info['purchase_amount'] = $avarage_price ;
       $update_stock_info['available_stock'] = $nowQuantity ;
       DB::table('stock')->where('branch_id', $branch_id )->where('product_id', $productId)->update($update_stock_info);
       // color stock update
       $color_stocks = DB::table('colorstock')->where('branch_id', $branch_id )->where('product_id', $productId)->where('color_id',$colorId)->first();
       $db_color_stock  = $color_stocks->available_stock ;
       $now_color_stock = $db_color_stock - $totalQuantity ;
       $update_color_stock_info = array();
       $update_color_stock_info['available_stock'] = $now_color_stock ;
       DB::table('colorstock')->where('branch_id', $branch_id )->where('product_id', $productId)->where('color_id',$colorId)->update($update_color_stock_info);

    }
    // end braket of $all_product
    #-------------------------- adjust supplier due--------------------#
       // get purchase details (by invoice number and branch id)
      $purchas_table_query = DB::table('purchase')
     ->where('branch_id',$branch_id)
     ->where('invoice',$invoice)
     ->first();
     $supplier_id         = $purchas_table_query->supplier_id ; 
     $total_product_price = $purchas_table_query->total_price ;
     $total_payment       = $purchas_table_query->total_payment ;
     $was_supplier_due    = $total_product_price - $total_payment ;
    // database supplier due
     $suppllier_due_query = DB::table('supplier_due')->where('supplier_id',$supplier_id)->first();
     $supplier_due        = $suppllier_due_query->total_due_amount ;
     $now_supplier_due    = $supplier_due -  $was_supplier_due ;
     // update supplier due
     $data_supplier_due = array();
     $data_supplier_due['total_due_amount'] = $now_supplier_due ;
     DB::table('supplier_due')->where('supplier_id',$supplier_id)->update($data_supplier_due); 
    #-------------------------- end adjust supplier due------------------#
    #------------------------- update pettycahs---------------------------#
     $pretty_cash_query = DB::table('pettycash')->where('branch_id', 0)->first();
     $available_pettyCash = $pretty_cash_query->pettycash_amount ;
     $data2 = array();
     $data2['pettycash_amount']     = $available_pettyCash + $total_payment ;
     $update_peetycash_query        = DB::table('pettycash')->where('branch_id', 0)->update($data2) ;
    #------------------------- end update pettycah-------------------------# 
    #--------------------------------- START DELETE----------------------------#
    // delete purchase (by branch id and invoice number)
     $delete_purchase = DB::table('purchase')
     ->where('branch_id',$branch_id)
     ->where('invoice',$invoice)
     ->delete();
     // delete purchas product (by branch id and invoice number)
     $delete_purchase_product = DB::table('purchase_product')
     ->where('branch_id',$branch_id)
     ->where('invoice_number',$invoice)
     ->delete();
     // delet product serial (if count > 0)
     $delete_product_serial = DB::table('product_serial')
     ->where('branch_id',$branch_id)
     ->where('invoice_no',$invoice) 
     ->where('invoice_type',2)
     ->delete();
     // delete payment ledger (by invoice number and supplier id)
     $delete_payment_ledger = DB::table('payment_ledger')
     ->where('invoice',$invoice)->where('supplier_id',$supplier_id)
     ->delete();
     // delete cahsbook (by type = 0 and branch_id = 0 and invoice_number)
     $delete_cashbook = DB::table('cashbook')
     ->where('type',0)
     ->where('branch_id',0)
     ->where('invoice_number',$invoice)
     ->delete();
     if($delete_cashbook){
         Session::put('succes','Thanks , Purchase Invoice No '.$invoice.' Removed Sucessfully.');
         return Redirect::to('managePurchase');
     }
   #--------------------------------- END  DELETE----------------------------#
   }


 #------------------------- end manage purchase invoice-----------------------#



}
