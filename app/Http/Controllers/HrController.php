<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests;
use DB;
use Session;

class HrController extends Controller
{
    private $rcdate ;
	private $current_time ;
	private $loged_id ;
	private $branch_id ;
	private $current_year ;
  	public function __construct() {
	date_default_timezone_set('Asia/Dhaka');
	$this->rcdate 		= date('Y-m-d');
	$this->current_time = date('H:i:s');
  	$this->loged_id     = Session::get('admin_id');
  	$this->branch_id    = Session::get('branch_id');
  	$this->current_year = date('Y');
	}
	 /**
     * load designation form
     *
     * @return \Illuminate\Http\Response
     */
    public function addDesignation()
    {
       return view('hr.addDesignation');
    }
    /**
    * new designation information added .
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function addDesignationInfo(Request $request)
    {
    $this->validate($request, [
    'designation'                    => 'required'
    ]);
     $designation          	= trim($request->designation);
     $remarks        	    = trim($request->remarks);
     //check duplicatet designation name
     $count = DB::table('designation')
     ->where('branch_id',$this->branch_id)
     ->where('desi_name', $designation)
     ->count();
      if($count > 0){
        Session::put('failed','Sorry ! '.$designation. ' Designation Already Exits. Try To Add New Designation');
        return Redirect::to('addDesignation');
        exit();
      }
     $data=array();
     $data['branch_id']     = $this->branch_id;
     $data['desi_name']     = $designation;
     $data['remarks']       = $remarks;
     $data['added_id']    	= $this->loged_id  ;
     $data['created_at']    = $this->rcdate ;
     $query = DB::table('designation')->insert($data);
      if($query){
        Session::put('succes','Thanks , Designation Added Sucessfully');
        return Redirect::to('addDesignation');
        }else{
        Session::put('failed','Sorry ! Error Occued. Try Again');
        return Redirect::to('addDesignation');
        }
    }
     /**
     * Display the all designation.
     *
     * @return \Illuminate\Http\Response
     */
    public function manageDesignation()
    {
      $result = DB::table('designation')->where('branch_id',$this->branch_id)->get();
      return view('hr.manageDesignation')->with('result',$result);
    }
    public function addEmp()
    {
    $result = DB::table('designation')->where('branch_id',$this->branch_id)->get();
    return view('hr.addEmp')->with('result',$result);
    }
        /**
    * new employee information added .
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function addEmpInfo(Request $request)
    {
     $this->validate($request, [
    'desi'            => 'required',
    'name'		      => 'required',
    'salary'          => 'required',
    'confirm_salary'  => 'required',
    'mobile'          => 'required|size:11',
    'image'           => 'mimes:jpeg,jpg,png|max:100',
    'image1'          => 'mimes:jpeg,jpg,png|max:100'
    ]);
     $branch       			= trim($request->branch);
     $desi          		= trim($request->desi);
     $name        			= trim($request->name);
     $salary     			= trim($request->salary);
     $confirm_salary   	    = trim($request->confirm_salary);
     $father_name 			= trim($request->father_name);
     $mother_name   		= trim($request->mother_name);
     $mobile 				= trim($request->mobile);
     $email   				= trim($request->email);
     $edu			    	= trim($request->edu);
     $nid   				= trim($request->nid);
     $sex 					= trim($request->sex);
     $join_date 			= trim($request->join_date);
     $birth 				= trim($request->birth);
     $permanent_address     = trim($request->permanent_address);
     $present_address 		= trim($request->present_address);
     $ref_person 			= trim($request->ref_person);
     $ref_mobile 			= trim($request->ref_mobile);
     $remarks        		= trim($request->remarks);
     if($join_date == ''){
     	$joinDate = '';
     }else{
     	$joinDate              = date('Y-m-d',strtotime($join_date));
     }


     #------------------- match two salary------------------#
      if($salary != $confirm_salary){
      	 Session::put('failed','Sorry ! Salary And Confrim Salary Did Not Match. Try Again');
        return Redirect::to('addEmp');
      	exit();
      }
     #------------------- end match salary------------------#
     #------------------- check duplicate------------------#
     $count = DB::table('staff')->where('branch_id',$this->branch_id)->where('mobile',$mobile)->count();
     if($count > 0){
     	 Session::put('failed','Sorry ! Staff Mobile Number Already Added. Try Again');
        return Redirect::to('addEmp');
     	exit();
     }
     #------------------- end  duplicate--------------------#
     #------------------- staff account number--------------#
    $last_account = DB::table('staff')->where('branch_id',$this->branch_id)->count();
     if($last_account == 0){
        $account_number = 1 ;
     }else{
     $account_number_query_row = DB::table('staff')
                            ->where('branch_id',$this->branch_id)
                            ->orderBy('account_number','desc')
                            ->first();
     $account_number       =  $account_number_query_row->account_number + 1 ;
     }
    #------------------- end staff account number------------#
    #------------------- insert staff table-------------------#
     // status 1 = active staff
     $data=array();
     $data['account_number']      = $account_number ;
     $data['branch_id']           = $this->branch_id;
     $data['desi_id']             = $desi;
     $data['name']    	  	      = $name ;
     $data['father_name']         = $father_name  ;
     $data['mother_name']         = $mother_name ;
     $data['mobile']    		  = $mobile;
     $data['email']               = $email;
     $data['edu']    		  	  = $edu ;
     $data['nid']    		  	  = $nid ;
     $data['sex']    		      = $sex ;
     $data['join_date']    	      = $joinDate;
     $data['birth_certificate']   = $birth ;
     $data['perment_address']     = $permanent_address ;
     $data['present_address']     = $present_address ;
     $data['ref_person']    	  = $ref_person;
     $data['ref_person_mobile']   = $ref_mobile;
     $data['status'] 			  = 1 ;
     $data['remarks']       	  = $remarks ;
     $data['added_id']       	  = $this->loged_id ;
     $data['created_at']    	  = $this->rcdate ;
     $image                   	  = $request->file('image');
     $image1                      = $request->file('image1');
         // staff  image
         if($image){
         $image_name        = str_random(20);
         $ext               = strtolower($image->getClientOriginalExtension());
         $image_full_name   ='staff-'.$image_name.'.'.$ext;
         $upload_path       = "images/";
         $image_url         = $upload_path.$image_full_name;
         $success           = $image->move($upload_path,$image_full_name);
         $data['image']     = $image_url;
     }else{
     	// no image
     	$data['image'] = '';
     }
     // first nid/ birth certificat image
         if($image1){
         $g1_image_name        	= str_random(20);
         $ext1               	= strtolower($image1->getClientOriginalExtension());
         $image_full_name1   	='nid-'.$g1_image_name.'.'.$ext1;
         $upload_path1       	= "images/";
         $image_url1         	= $upload_path1.$image_full_name1;
         $success1           	= $image1->move($upload_path1,$image_full_name1);
         $data['image1'] 		= $image_url1;
     	}else{
     	// no image
     	$data['image1'] = '';
     	}
             DB::table('staff')->insert($data);
             //get last staff id 
             $staff_id = DB::table('staff')
             ->orderBy('id', 'desc')->take(1)->first();
             // insert into salary id
             $data1                        = array();
             $data1['branch_id'] 	       = $this->branch_id ;
             $data1['staff_id'] 	       = $staff_id->id ;
             $data1['year']    			   = $this->current_year ;
             $data1['month']    		   = '01' ;
             $data1['salary_amount']       = $salary ;
             $data1['added_id']            = $this->loged_id ;
             $data1['created_at']          = $this->rcdate ;
             DB::table('salary')->insert($data1);
             Session::put('succes','New Staff Added Sucessfully');
             return Redirect::to('addEmp');
       }
     /**
     * Display the all employee.
     *
     * @return \Illuminate\Http\Response
     */
    public function manageEmp()
    {
    $result = DB::table('staff')
    ->join('branch', 'staff.branch_id', '=', 'branch.id')
    ->join('designation', 'staff.desi_id', '=', 'designation.id')
    ->orderBy('name','asc')
    ->select('staff.*','branch.name AS branch_name','designation.desi_name')
    ->where('staff.branch_id',$this->branch_id)
    ->get();
     return view('hr.manageEmp')->with('result',$result);
     }
     /**
     * Emp salary info.
     * @param int $id
     * @return \Illuminate\Http\Response
     */
     public function empSalaryInfo($id)
     {
     $row    = DB::table('staff')
    ->join('branch', 'staff.branch_id', '=', 'branch.id')
    ->join('designation', 'staff.desi_id', '=', 'designation.id')
    ->select('staff.*','branch.name AS branch_name','designation.desi_name')
    ->where('staff.id',$id)
    ->first();
     $result = DB::table('salary')->where('staff_id',$id)->get() ;
     return view('hr.empSalaryInfo')->with('result',$result)->with('row',$row);
     }




}
