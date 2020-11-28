<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests;
use DB;
use Session;

class AdminController extends Controller {
     private $rcdate ;
     private $loged_id ;
     private $current_time ;
     private $branch_id ;
	public function __construct() {
		date_default_timezone_set('Asia/Dhaka');
		$this->rcdate = date('Y-m-d');
		$this->current_time = date('H:i:s');
        $this->loged_id     = Session::get('admin_id');
        $this->branch_id    = Session::get('branch_id');
	}
	/**
	 * Display admin login page.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		return view('admin.index');
	}

	#-------------- Login --------------------#
	public function login(Request $request) 
    {
        $this->validate($request, [
            'mobile'    => 'required',
            'password'  => 'required',
        ]);
        $mobile     = trim($request->mobile);
        $pwd        = trim($request->password);
        $salt       = 'a123A321';
        $password   = sha1($pwd.$salt);

        $admin_id_count  = Session::get('admin_id');
        $admin_type_is  = Session::get('type');

        if($admin_id_count != null){
        if($admin_type_is == '1'){
            $admin_type_name_is = "Admin";
        }elseif($admin_type_is == '2'){
          $admin_type_name_is = "Manager";  
        }elseif($admin_type_is == '3'){
          $admin_type_name_is = "Cashier";  
        }elseif($admin_type_is == '4'){
          $admin_type_name_is = "Doctor";  
        }
         Session::put('login_faild','Sorry!! Already You Have Logged In As A '.$admin_type_name_is.'. Please Logout As A '.$admin_type_name_is.' Or Use Different Browser Or Clear History Of Your Browser And Then Try To Login');

        Session::put('hyperlink','Please Session Destroy');
          return Redirect::to('/admin');
          exit();
     }
        #------------------- Check Valid Information ---------------#
        $check_count = DB::table('admin')->where('status',1)->where('mobile',$mobile)->where('password',$password)->count();
        if ($check_count > 0) {
            $admin_login = DB::table('admin')
            ->where('mobile', $mobile)
            ->where('password', $password)
            ->where('status',1)
            ->first();
            // check user type
            $type = $admin_login->type ;
            if($type == '1'){
                // admin login
                Session::put('admin_name',$admin_login->name);
                Session::put('admin_id',$admin_login->id);
                Session::put('type',$admin_login->type);
                Session::put('branch_id',$admin_login->branch_id);
                Session::put('photo',$admin_login->image);
                return Redirect::to('/adminDashboard');
            }elseif($type == '2'){
               // Department Incharge dashboard
                Session::put('admin_name',$admin_login->name);
                Session::put('admin_id',$admin_login->id);
                Session::put('branch_id',$admin_login->branch_id);
                Session::put('type',$admin_login->type);
                Session::put('photo',$admin_login->image);
                return Redirect::to('/managerDashboard'); 
            }elseif($type == '3'){
               // Department Incharge dashboard
                Session::put('admin_name',$admin_login->name);
                Session::put('admin_id',$admin_login->id);
                Session::put('branch_id',$admin_login->branch_id);
                Session::put('type',$admin_login->type);
                Session::put('photo',$admin_login->image);
                return Redirect::to('/cashierDashboard'); 
            }elseif($type == '4'){
               // Department Incharge dashboard
                Session::put('admin_name',$admin_login->name);
                Session::put('admin_id',$admin_login->id);
                Session::put('branch_id',$admin_login->branch_id);
                Session::put('type',$admin_login->type);
                Session::put('photo',$admin_login->image);
                return Redirect::to('/doctorDashboard'); 
            }
        }else{
            Session::put('login_faild','Sorry!! Your Information Did Not Match. Try Again');
            return Redirect::to('/admin');
        }
	}

    #--------------------------------- STAFF ---------------------------------#
    /**
     * add manager form.
     * with branch id
     * @return \Illuminate\Http\Response
     */
    public function addBranchManager()
    {
        $result = DB::table('branch')->get();
        return view('admin.addBranchManager')->with('result',$result);
    }
    /**
    * new manager information added .
    * with current due
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function addManagerInfo(Request $request)
    {
    $this->validate($request, [
    'branch'            => 'required',
    'name'              => 'required',
    'mobile'            => 'required',
    'address'           => 'required',
    'image'             => 'mimes:jpeg,jpg,png|max:100'
    ]);
     $branch                = trim($request->branch);
     $name                  = trim($request->name);
     $father_name           = trim($request->father_name);
     $mobile                = trim($request->mobile);
     $email                 = trim($request->email);
     $nid                   = trim($request->nid);
     $address               = trim($request->address);
     #--------------------- DUPLIATE ENTRY CHECK------------------#
     // by mobile number
     $check_count = DB::table('admin')->where('mobile',$mobile)->count();
     if($check_count > 0){
        Session::put('failed','Sorry ! This Manager Mobile Number Already Exits');
        return Redirect::to('addBranchManager');
        exit();
     }
     #-------------------- END DUPLICATE ENTRY CHECK---------------#
     $salt      = 'a123A321';
     $password  = trim(sha1($mobile.$salt));

     $data=array();
     $data['branch_id']       = $branch ;
     $data['name']            = $name ;
     $data['nid']             = $nid ;
     $data['father_name']     = $father_name ;
     $data['email']           = $email ;
     $data['mobile']          = $mobile ;
     $data['type']            = 2 ;
     $data['password']        =  $password;
     $data['address']         = $address;
     $data['status']          = 1;
     $data['added_id']        = $this->loged_id;
     $data['creatd_at']      = $this->rcdate ;
     $image                  = $request->file('image');
         if($image){
         $image_name        = str_random(20);
         $ext               = strtolower($image->getClientOriginalExtension());
         $image_full_name   ='manager-'.$image_name.'.'.$ext;
         $upload_path       = "images/";
         $image_url         = $upload_path.$image_full_name;
         $success           = $image->move($upload_path,$image_full_name);
         $data['image']     = $image_url;
        }else{
         $data['image']     = '';
        }
        DB::table('admin')->insert($data);
        Session::put('succes','Thanks , Branch Manager Added Sucessfully');
        return Redirect::to('addBranchManager');
       
    }
     /**
     * Display the all manger.
     *
     * @return \Illuminate\Http\Response
     */
    public function manageBranchManager()
    {
    $result = DB::table('admin')
    ->join('branch', 'admin.branch_id', '=', 'branch.id')
    ->select('admin.*','branch.name AS branch_name')
    ->where('admin.type', 2)
    ->get();
    return view('admin.manageBranchManager')->with('result',$result);
    }
    /**
     * super admin logout process 
     * @return \Illuminate\Http\Response
    */
    public function adminLogout()
    {
       Session::put('admin_id',null);
       Session::put('type',null);
       Session::put('branch_id',null);
       return Redirect::to('/admin');
    }
    /**
     * managet logout process 
     * @return \Illuminate\Http\Response
    */
    public function managerLogout()
    {
       Session::put('admin_id',null);
       Session::put('type',null);
       Session::put('branch_id',null);
       return Redirect::to('/admin');
    }

    /**
     * cashier logout process 
     * @return \Illuminate\Http\Response
    */
    public function cashierLogout()
    {
       Session::put('admin_id',null);
       Session::put('type',null);
       Session::put('branch_id',null);
       return Redirect::to('/admin');
    }

    

}
