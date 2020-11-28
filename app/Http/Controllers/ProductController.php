<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests;
use DB;
use Session;

class ProductController extends Controller
{
   private $rcdate ;
   private $loged_id ;
   private $branch_id ;
     /**
     * PRODUCT CLASS costructor 
     *
     */
    public function __construct()
    {
    	date_default_timezone_set('Asia/Dhaka');
    	$this->rcdate      = date('Y-m-d');
    	$this->loged_id    = Session::get('admin_id');
        $this->branch_id   = Session::get('branch_id');
    }
    /**
     * load product form
     *
     * @return \Illuminate\Http\Response
     */
    public function addProduct()
    {
     // with product code
     $product_count = DB::table('product')->where('branch_id',$this->branch_id)->count();
     if($product_count > 0){
        // get product code
        $product_code_query = DB::table('product')->where('branch_id',$this->branch_id)->orderBy('product_code','desc')->first();
        $product_code       =  $product_code_query->product_code + 1 ; 
     }else{
        // accorinding to client product code
        $product_code = '';
     }
     $unit     = DB::table('unit')->where('branch_id',$this->branch_id)->get();
     return view('product.addProduct')->with('product_code',$product_code)->with('unit',$unit);	
    }
    /**
    * new product information added .
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function addProductInfo(Request $request)
    {
    $this->validate($request, [
    'product'      		=> 'required',
    'product_code' 		=> 'required',
    'purchase_price' 	=> 'required',
    'confirm_purchase_price'    => 'required',
    'unit'        		=> 'required',
    'image'         	=> 'mimes:jpeg,jpg,png|max:100'
    ]);
     $product        = trim($request->product);
     $product_code   = trim($request->product_code);
     $purchase_price = trim($request->purchase_price);
     $confirm_purchase_price = trim($request->confirm_purchase_price);
     $unit           = trim($request->unit);
     $description    = trim($request->description);
     $remarks        = trim($request->remarks);
     // purchase price would not be big than sale price
     if($purchase_price != $confirm_purchase_price)
     {
        Session::put('failed','Sorry ! Purchase Price And Confirm Purchase Price Did Not Match');
        return Redirect::to('addProduct');
        exit();
     }
     //check duplicate product name
     $check_product_name = DB::table('product')
     ->where('branch_id', $this->branch_id)
     ->where('product_name', $product)
     ->count();
     if($check_product_name > 0){
     	Session::put('failed','Sorry ! Product Name Already Exists. Try Again');
        return Redirect::to('addProduct');
     	exit();
     }
     //check duplicate product code
     $check_product_code = DB::table('product')
     ->where('branch_id', $this->branch_id)
     ->where('product_code', $product_code)
     ->count();
      if($check_product_code > 0){
        Session::put('failed','Sorry ! '.$product_code. ' Product Code Already Exits. Try To Add New  Product Code');
        return Redirect::to('addProduct');
        exit();
      }
     $data=array();
     $data['branch_id']         = $this->branch_id;
     $data['product_name']    	= $product;
     $data['product_code']    	= $product_code;
     $data['unit']    			= $unit;
     $data['purchase_amount']   = $purchase_price;
     $data['des']    			= $description;
     $data['remarks']       	= $remarks ;
     $data['added_id']       	= $this->loged_id ;
     $data['created_at']    	= $this->rcdate ;
     $image                   	= $request->file('image');
         if($image){
         $image_name        = str_random(20);
         $ext               = strtolower($image->getClientOriginalExtension());
         $image_full_name   ='product-'.$image_name.'.'.$ext;
         $upload_path       = "images/";
         $image_url         = $upload_path.$image_full_name;
         $success           = $image->move($upload_path,$image_full_name);
         if($success){
             $data['image'] = $image_url;
             DB::table('product')->insert($data);
             // get last product id 
             Session::put('succes','New Product Added Sucessfully');
             return Redirect::to('addProduct');
        }
     }else{
              DB::table('product')->insert($data);
             // get last product id 
             Session::put('succes','New Product Added Sucessfully');
             return Redirect::to('addProduct');
    }

    }
     /**
     * Display the all product.
     *
     * @return \Illuminate\Http\Response
     */

   public function manageProduct()
   {
    $result = DB::table('product')
    ->join('unit', 'product.unit', '=', 'unit.id')
    ->select('product.*','unit.unit_name')
    ->where('product.branch_id',$this->branch_id)
    ->get();
     return view('product.manageProduct')->with('result',$result);
   }
 
  public function getProductPurchasePrice(Request $request)
   {
    $product_id = trim($request->product_id);
    //get purchase price
    $query = DB::table('product')->where('id',$product_id)->first();
    $purchase_price = $query->purchase_amount;
    echo $purchase_price ;
   }
   
   

}
