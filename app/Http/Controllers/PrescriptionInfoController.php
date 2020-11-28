<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests;
use DB;
use Session;

class PrescriptionInfoController extends Controller
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

    // Function for add medicine type
    public function addMedicineType()
    {
    	return view('medicine.addMedicineType');
    }

    // Function for add medicine type info
    public function addMedicineTypeInfo(Request $request)
    {
    	// Validation
		$this->validate($request, [
	    'typeFullName'   => 'required',
	    'shortName'		 => 'required'
	    ]);

	    // Collect data from html form
	    $typeFullName = trim($request->typeFullName);
	    $shortName    = trim($request->shortName);
	    $remarks      = trim($request->remarks);

	    // duplicate check
	    $count = DB::table('tbl_p_medicine_type')->where('type_full_name',$typeFullName)->where('status',0)->count();
	    if($count > 0){
	    	Session::put('failed','Sorry ! '.$typeFullName. ' Medicine Type Already Exits. Try To Add New Medicine Type');
        	return Redirect::to('addMedicineType');
        	exit();
	    }

	    // duplicate check
	    $count2 = DB::table('tbl_p_medicine_type')->where('short_name',$shortName)->where('status',0)->count();
	    if($count2 > 0){
	    	Session::put('failed','Sorry ! '.$shortName. ' Short Name Already Exits. Try To Add New Short Name');
        	return Redirect::to('addMedicineType');
        	exit();
	    }

	    $data = array();
	    $data['branch_id'] 		= $this->branch_id;
	    $data['type_full_name'] = $typeFullName;
	    $data['short_name'] 	= $shortName;
	    $data['status'] 		= "0";
	    $data['remarks'] 		= $remarks;
	    $data['added_id'] 		= $this->loged_id;
	    $data['created_at'] 	= $this->rcdate;
	    $query = DB::table('tbl_p_medicine_type')->insert($data);
		if($query){
			Session::put('succes','Thanks , Medicine Type Added Sucessfully');
			return Redirect::to('addMedicineType');
		}else{
			Session::put('failed','Sorry ! Error Occued. Try Again');
			return Redirect::to('addMedicineType');
		}
    }

    // Function for manage medicine type 
    public function manageMedicineType()
    {
    	$result = DB::table('tbl_p_medicine_type')
    		->join('branch','branch.id','=','tbl_p_medicine_type.branch_id')
    		->join('admin','admin.id','=','tbl_p_medicine_type.added_id')
    		->select('tbl_p_medicine_type.*','admin.name','branch.name AS branch_name')
    		->where('tbl_p_medicine_type.branch_id',$this->branch_id)
    		->where('tbl_p_medicine_type.status',0)
    		->get();
    	return view('medicine.manageMedicineType')->with('result',$result);
    }

    // Function for de-active medicine type
    public function deactiveMedicineType($id)
    {
    	$data = array();
    	$data['status'] = "1";
    	$data['modified_at'] = $this->rcdate;
    	$query = DB::table('tbl_p_medicine_type')->where('id',$id)->update($data);
		if($query){
			Session::put('succes','Thanks , Medicine Type De-active Sucessfully');
			return Redirect::to('manageMedicineType');
		}else{
			Session::put('failed','Sorry ! Error Occued. Try Again');
			return Redirect::to('manageMedicineType');
		}
    }

    // Function for add medicine 
    public function addMedicine()
    {
    	$medicine_type = DB::table('tbl_p_medicine_type')->where('branch_id',$this->branch_id)->where('status',0)->get();
    	return view('medicine.addMedicine')->with('medicine_type',$medicine_type);
    }

    // Function for add Medicine info
    public function addMedicineInfo(Request $request)
    {
    	// Validation
		$this->validate($request, [
	    'medicineType'   => 'required',
	    'medicineName'   => 'required'
	    ]);

	    // Collect data from html form
	    $medicineType = trim($request->medicineType);
	    $medicineName = trim($request->medicineName);
	    $genericName  = trim($request->genericName);
	    $remarks      = trim($request->remarks);

	    // duplicate check
	    $count = DB::table('tbl_p_medicine')->where('medicine_name',$medicineName)->where('status',0)->count();
	    if($count > 0){
	    	Session::put('failed','Sorry ! '.$medicineName. ' Medicine Already Exits. Try To Add New Medicine');
        	return Redirect::to('addMedicine');
        	exit();
	    }

	    $data = array();
	    $data['branch_id'] 		  = $this->branch_id;
	    $data['medicine_type_id'] = $medicineType;
	    $data['medicine_name'] 	  = $medicineName;
	    $data['generic_name'] 	  = $genericName;
	    $data['remarks'] 		  = $remarks;
	    $data['status'] 		  = "0";
	    $data['added_id'] 		  = $this->loged_id;
	    $data['created_at'] 	  = $this->rcdate;
	    $query = DB::table('tbl_p_medicine')->insert($data);
		if($query){
			Session::put('succes','Thanks , Medicine Added Sucessfully');
			return Redirect::to('addMedicine');
		}else{
			Session::put('failed','Sorry ! Error Occued. Try Again');
			return Redirect::to('addMedicine');
		}
    }

    // Function for manage medicine info
    public function manageMedicine()
    {
    	$result = DB::table('tbl_p_medicine')
    		->join('tbl_p_medicine_type','tbl_p_medicine_type.id','=','tbl_p_medicine.medicine_type_id')
    		->select('tbl_p_medicine.*','tbl_p_medicine_type.type_full_name')
    		->where('tbl_p_medicine.branch_id',$this->branch_id)
    		->where('tbl_p_medicine.status',0)
    		->get();
    	return view('medicine.manageMedicine')->with('result',$result);
    }

    // Function for de-active medicine type
    public function deactiveMedicine($id)
    {
    	$data = array();
    	$data['status'] = "1";
    	$data['modified_at'] = $this->rcdate;
    	$query = DB::table('tbl_p_medicine')->where('id',$id)->update($data);
		if($query){
			Session::put('succes','Thanks , Medicine De-active Sucessfully');
			return Redirect::to('manageMedicine');
		}else{
			Session::put('failed','Sorry ! Error Occued. Try Again');
			return Redirect::to('manageMedicine');
		}
    }

    // Function for add unaided var
    public function addUnaidedVar()
    {
    	return view('unaided.addUnaidedVar');
    }

    // Function for unaided var info
    public function addUnaidedVarInfo(Request $request)
    {
    	// Validation
		$this->validate($request, [
	    'var'   => 'required'
	    ]);

	    // Collect data from html form
	    $var     = trim($request->var);
	    $remarks = trim($request->remarks);

	    // duplicate check
	    $count = DB::table('tbl_p_unaided_var')->where('var',$var)->count();
	    if($count > 0){
	    	Session::put('failed','Sorry ! '.$var. ' Already Exits. Try To Add New');
        	return Redirect::to('addUnaidedVar');
        	exit();
	    }

	    $data = array();
	    $data['branch_id'] 		  = $this->branch_id;
	    $data['var'] 			  = $var;
	    $data['remarks'] 		  = $remarks;
	    $data['status'] 		  = "0";
	    $data['added_id'] 		  = $this->loged_id;
	    $data['created_at'] 	  = $this->rcdate;
	    $query = DB::table('tbl_p_unaided_var')->insert($data);
		if($query){
			Session::put('succes','Thanks , Var Added Sucessfully');
			return Redirect::to('addUnaidedVar');
		}else{
			Session::put('failed','Sorry ! Error Occued. Try Again');
			return Redirect::to('addUnaidedVar');
		}
    }

    // Function for manage var
    public function manageUnaidedVar()
    {
    	$result = DB::table('tbl_p_unaided_var')->where('branch_id',$this->branch_id)->where('status',0)->get();
    	return view('unaided.manageUnaidedVar')->with('result',$result);
    }

    // Function for deactive var
    public function deactiveVar($id)
    {
    	$data = array();
    	$data['status'] = "1";
    	$data['modified_at'] = $this->rcdate;
    	$query = DB::table('tbl_p_unaided_var')->where('id',$id)->update($data);
		if($query){
			Session::put('succes','Thanks , Var De-active Sucessfully');
			return Redirect::to('manageUnaidedVar');
		}else{
			Session::put('failed','Sorry ! Error Occued. Try Again');
			return Redirect::to('manageUnaidedVar');
		}
    }

    // Function for add unaided val
    public function addUnaidedVal()
    {
    	return view('unaided.addUnaidedVal');
    }

    // Function for unaided val info
    public function addUnaidedValInfo(Request $request)
    {
    	// Validation
		$this->validate($request, [
	    'val'   => 'required'
	    ]);

	    // Collect data from html form
	    $val     = trim($request->val);
	    $remarks = trim($request->remarks);

	    // duplicate check
	    $count = DB::table('tbl_p_unaided_val')->where('val',$val)->count();
	    if($count > 0){
	    	Session::put('failed','Sorry ! '.$val. ' Already Exits. Try To Add New');
        	return Redirect::to('addUnaidedVal');
        	exit();
	    }

	    $data = array();
	    $data['branch_id'] 		  = $this->branch_id;
	    $data['val'] 			  = $val;
	    $data['remarks'] 		  = $remarks;
	    $data['status'] 		  = "0";
	    $data['added_id'] 		  = $this->loged_id;
	    $data['created_at'] 	  = $this->rcdate;
	    $query = DB::table('tbl_p_unaided_val')->insert($data);
		if($query){
			Session::put('succes','Thanks , Val Added Sucessfully');
			return Redirect::to('addUnaidedVal');
		}else{
			Session::put('failed','Sorry ! Error Occued. Try Again');
			return Redirect::to('addUnaidedVal');
		}
    }

    // Function for manage val
    public function manageUnaidedVal()
    {
    	$result = DB::table('tbl_p_unaided_val')->where('branch_id',$this->branch_id)->where('status',0)->get();
    	return view('unaided.manageUnaidedVal')->with('result',$result);
    }

    // Function for deactive var
    public function deactiveVal($id)
    {
    	$data = array();
    	$data['status'] = "1";
    	$data['modified_at'] = $this->rcdate;
    	$query = DB::table('tbl_p_unaided_val')->where('id',$id)->update($data);
		if($query){
			Session::put('succes','Thanks , Val De-active Sucessfully');
			return Redirect::to('manageUnaidedVal');
		}else{
			Session::put('failed','Sorry ! Error Occued. Try Again');
			return Redirect::to('manageUnaidedVal');
		}
    }

    // Function for add pinhole var
    public function addPinholeVar()
    {
    	return view('pinhole.addPinholeVar');
    }

    // Function for add pinhole var info
    public function addPinholeVarInfo(Request $request)
    {
    	// Validation
		$this->validate($request, [
	    'var'   => 'required'
	    ]);

	    // Collect data from html form
	    $var     = trim($request->var);
	    $remarks = trim($request->remarks);

	    // duplicate check
	    $count = DB::table('tbl_p_pinhole_var')->where('var',$var)->count();
	    if($count > 0){
	    	Session::put('failed','Sorry ! '.$var. ' Already Exits. Try To Add New');
        	return Redirect::to('addPinholeVar');
        	exit();
	    }

	    $data = array();
	    $data['branch_id'] 		  = $this->branch_id;
	    $data['var'] 			  = $var;
	    $data['remarks'] 		  = $remarks;
	    $data['status'] 		  = "0";
	    $data['added_id'] 		  = $this->loged_id;
	    $data['created_at'] 	  = $this->rcdate;
	    $query = DB::table('tbl_p_pinhole_var')->insert($data);
		if($query){
			Session::put('succes','Thanks , Var Added Sucessfully');
			return Redirect::to('addPinholeVar');
		}else{
			Session::put('failed','Sorry ! Error Occued. Try Again');
			return Redirect::to('addPinholeVar');
		}
    }

    // Function for manage var
    public function managePinholeVar()
    {
    	$result = DB::table('tbl_p_pinhole_var')->where('branch_id',$this->branch_id)->where('status',0)->get();
    	return view('pinhole.managePinholeVar')->with('result',$result);
    }

    // Function for deactive var
    public function deactivePinholeVar($id)
    {
    	$data = array();
    	$data['status'] = "1";
    	$data['modified_at'] = $this->rcdate;
    	$query = DB::table('tbl_p_pinhole_var')->where('id',$id)->update($data);
		if($query){
			Session::put('succes','Thanks , Var De-active Sucessfully');
			return Redirect::to('managePinholeVar');
		}else{
			Session::put('failed','Sorry ! Error Occued. Try Again');
			return Redirect::to('managePinholeVar');
		}
    }

    // Function for add unaided val
    public function addPinholeVal()
    {
    	return view('pinhole.addPinholeVal');
    }

    // Function for unaided val info
    public function addPinholeValInfo(Request $request)
    {
    	// Validation
		$this->validate($request, [
	    'val'   => 'required'
	    ]);

	    // Collect data from html form
	    $val     = trim($request->val);
	    $remarks = trim($request->remarks);

	    // duplicate check
	    $count = DB::table('tbl_p_pinhole_val')->where('val',$val)->count();
	    if($count > 0){
	    	Session::put('failed','Sorry ! '.$val. ' Already Exits. Try To Add New');
        	return Redirect::to('addPinholeVal');
        	exit();
	    }

	    $data = array();
	    $data['branch_id'] 		  = $this->branch_id;
	    $data['val'] 			  = $val;
	    $data['remarks'] 		  = $remarks;
	    $data['status'] 		  = "0";
	    $data['added_id'] 		  = $this->loged_id;
	    $data['created_at'] 	  = $this->rcdate;
	    $query = DB::table('tbl_p_pinhole_val')->insert($data);
		if($query){
			Session::put('succes','Thanks , Val Added Sucessfully');
			return Redirect::to('addPinholeVal');
		}else{
			Session::put('failed','Sorry ! Error Occued. Try Again');
			return Redirect::to('addPinholeVal');
		}
    }

    // Function for manage val
    public function managePinholeVal()
    {
    	$result = DB::table('tbl_p_pinhole_val')->where('branch_id',$this->branch_id)->where('status',0)->get();
    	return view('pinhole.managePinholeVal')->with('result',$result);
    }

    // Function for deactive val
    public function deactivePinholeVal($id)
    {
    	$data = array();
    	$data['status'] = "1";
    	$data['modified_at'] = $this->rcdate;
    	$query = DB::table('tbl_p_pinhole_val')->where('id',$id)->update($data);
		if($query){
			Session::put('succes','Thanks , Val De-active Sucessfully');
			return Redirect::to('managePinholeVal');
		}else{
			Session::put('failed','Sorry ! Error Occued. Try Again');
			return Redirect::to('managePinholeVal');
		}
    }

    // Function for add Anterior Segment
    public function addAnteriorSegment()
    {
    	return view('anterior_segment.addAnteriorSegment');
    }

    // Function for add Anterior segment
    public function addAnteriorSegmentInfo(Request $request)
    {
    	// Validation
		$this->validate($request, [
	    'anterior_segment'   => 'required'
	    ]);

	    // Collect data from html form
	    $anterior_segment     = trim($request->anterior_segment);
	    $remarks = trim($request->remarks);

	    // duplicate check
	    $count = DB::table('tbl_p_anterior_segment')->where('anterior_segment',$anterior_segment)->count();
	    if($count > 0){
	    	Session::put('failed','Sorry ! '.$anterior_segment. ' Already Exits. Try To Add New');
        	return Redirect::to('addAnteriorSegment');
        	exit();
	    }

	    $data = array();
	    $data['branch_id'] 		  = $this->branch_id;
	    $data['anterior_segment'] = $anterior_segment;
	    $data['remarks'] 		  = $remarks;
	    $data['status'] 		  = "0";
	    $data['added_id'] 		  = $this->loged_id;
	    $data['created_at'] 	  = $this->rcdate;
	    $query = DB::table('tbl_p_anterior_segment')->insert($data);
		if($query){
			Session::put('succes','Thanks ,Anterior Segment Added Sucessfully');
			return Redirect::to('addAnteriorSegment');
		}else{
			Session::put('failed','Sorry ! Error Occued. Try Again');
			return Redirect::to('addAnteriorSegment');
		}
    }

    // Function for manage anterior segment
    public function manageAnteriorSegment()
    {
    	$result = DB::table('tbl_p_anterior_segment')->where('branch_id',$this->branch_id)->where('status',0)->get();
    	return view('anterior_segment.manageAnteriorSegment')->with('result',$result);
    }

    // Function for deactive anterior segment
    public function deactiveAnteriorSegment($id)
    {
    	$data = array();
    	$data['status'] = "1";
    	$data['modified_at'] = $this->rcdate;
    	$query = DB::table('tbl_p_anterior_segment')->where('id',$id)->update($data);
		if($query){
			Session::put('succes','Thanks , Anterior Segment De-active Sucessfully');
			return Redirect::to('manageAnteriorSegment');
		}else{
			Session::put('failed','Sorry ! Error Occued. Try Again');
			return Redirect::to('manageAnteriorSegment');
		}
    }

    // Function for add Posteror Segment
    public function addPosterorSegment()
    {
    	return view('posteror_segment.addPosterorSegment');
    }

    // Function for add Posteror segment
    public function addPosterorSegmentInfo(Request $request)
    {
    	// Validation
		$this->validate($request, [
	    'posteror_segment'   => 'required'
	    ]);

	    // Collect data from html form
	    $posteror_segment     = trim($request->posteror_segment);
	    $remarks = trim($request->remarks);

	    // duplicate check
	    $count = DB::table('tbl_p_posteror_segment')->where('posteror_segment',$posteror_segment)->count();
	    if($count > 0){
	    	Session::put('failed','Sorry ! '.$posteror_segment. ' Already Exits. Try To Add New');
        	return Redirect::to('addPosterorSegment');
        	exit();
	    }

	    $data = array();
	    $data['branch_id'] 		  = $this->branch_id;
	    $data['posteror_segment'] = $posteror_segment;
	    $data['remarks'] 		  = $remarks;
	    $data['status'] 		  = "0";
	    $data['added_id'] 		  = $this->loged_id;
	    $data['created_at'] 	  = $this->rcdate;
	    $query = DB::table('tbl_p_posteror_segment')->insert($data);
		if($query){
			Session::put('succes','Thanks ,Anterior Segment Added Sucessfully');
			return Redirect::to('addPosterorSegment');
		}else{
			Session::put('failed','Sorry ! Error Occued. Try Again');
			return Redirect::to('addPosterorSegment');
		}
    }

    // Function for manage posteror segment
    public function managePosterorSegment()
    {
    	$result = DB::table('tbl_p_posteror_segment')->where('branch_id',$this->branch_id)->where('status',0)->get();
    	return view('posteror_segment.managePosterorSegment')->with('result',$result);
    }

    // Function for de-active Posteror segment
    public function deactivePosterorSegment($id)
    {
    	$data = array();
    	$data['status'] = "1";
    	$data['modified_at'] = $this->rcdate;
    	$query = DB::table('tbl_p_posteror_segment')->where('id',$id)->update($data);
		if($query){
			Session::put('succes','Thanks , Posteror Segment De-active Sucessfully');
			return Redirect::to('managePosterorSegment');
		}else{
			Session::put('failed','Sorry ! Error Occued. Try Again');
			return Redirect::to('managePosterorSegment');
		}
    }

    // Function for add disgonsis
    public function addDisgnosis()
    {
    	return view('disgnosis.addDisgnosis');
    }

    // Function for add disgnosis info
    public function addDisgnosisInfo(Request $request)
    {
    	// Validation
		$this->validate($request, [
	    'disgnosis'   => 'required'
	    ]);

	    // Collect data from html form
	    $disgnosis     = trim($request->disgnosis);
	    $remarks = trim($request->remarks);

	    // duplicate check
	    $count = DB::table('tbl_p_disgnosis')->where('disgnosis',$disgnosis)->count();
	    if($count > 0){
	    	Session::put('failed','Sorry ! '.$disgnosis. ' Already Exits. Try To Add New');
        	return Redirect::to('addDisgnosis');
        	exit();
	    }

	    $data = array();
	    $data['branch_id'] 		  = $this->branch_id;
	    $data['disgnosis'] 	      = $disgnosis;
	    $data['remarks'] 		  = $remarks;
	    $data['status'] 		  = "0";
	    $data['added_id'] 		  = $this->loged_id;
	    $data['created_at'] 	  = $this->rcdate;
	    $query = DB::table('tbl_p_disgnosis')->insert($data);
		if($query){
			Session::put('succes','Thanks ,Disgonsis Added Sucessfully');
			return Redirect::to('addDisgnosis');
		}else{
			Session::put('failed','Sorry ! Error Occued. Try Again');
			return Redirect::to('addDisgnosis');
		}
    }

    // Function for manage disgnosis
    public function manageDisgnosis()
    {
    	$result = DB::table('tbl_p_disgnosis')->where('branch_id',$this->branch_id)->where('status',0)->get();
    	return view('disgnosis.manageDisgnosis')->with('result',$result);
    }

    // Function for de-active advice
    public function deactiveDisgnosis($id)
    {
    	$data = array();
    	$data['status'] = "1";
    	$data['modified_at'] = $this->rcdate;
    	$query = DB::table('tbl_p_disgnosis')->where('id',$id)->update($data);
		if($query){
			Session::put('succes','Thanks , Disgonsis De-active Sucessfully');
			return Redirect::to('manageDisgnosis');
		}else{
			Session::put('failed','Sorry ! Error Occued. Try Again');
			return Redirect::to('manageDisgnosis');
		}
    }

    // Function for add advice
    public function addAdvice()
    {
    	return view('advice.addAdvice');
    }

    // Function for add advice info
    public function addAdviceInfo(Request $request)
    {
    	// Validation
		$this->validate($request, [
	    'advice'   => 'required'
	    ]);

	    // Collect data from html form
	    $advice  = trim($request->advice);
	    $remarks = trim($request->remarks);

	    // duplicate check
	    $count = DB::table('tbl_p_advice')->where('advice',$advice)->count();
	    if($count > 0){
	    	Session::put('failed','Sorry ! '.$advice. ' Already Exits. Try To Add New');
        	return Redirect::to('addAdvice');
        	exit();
	    }

	    $data = array();
	    $data['branch_id'] 		  = $this->branch_id;
	    $data['advice'] 	      = $advice;
	    $data['remarks'] 		  = $remarks;
	    $data['status'] 		  = "0";
	    $data['added_id'] 		  = $this->loged_id;
	    $data['created_at'] 	  = $this->rcdate;
	    $query = DB::table('tbl_p_advice')->insert($data);
		if($query){
			Session::put('succes','Thanks ,Advice Added Sucessfully');
			return Redirect::to('addAdvice');
		}else{
			Session::put('failed','Sorry ! Error Occued. Try Again');
			return Redirect::to('addAdvice');
		}
    }

    // Function for manage advice
    public function manageAdvice()
    {
    	$result = DB::table('tbl_p_advice')->where('branch_id',$this->branch_id)->where('status',0)->get();
    	return view('advice.manageAdvice')->with('result',$result);
    }

    // Function for de-active advice
    public function deactiveAdvice($id)
    {
    	$data = array();
    	$data['status'] = "1";
    	$data['modified_at'] = $this->rcdate;
    	$query = DB::table('tbl_p_advice')->where('id',$id)->update($data);
		if($query){
			Session::put('succes','Thanks , Advice De-active Sucessfully');
			return Redirect::to('manageAdvice');
		}else{
			Session::put('failed','Sorry ! Error Occued. Try Again');
			return Redirect::to('manageAdvice');
		}
    }

    // Function for add followup
    public function addFollowup()
    {
    	return view('followup.addFollowup');
    }

    // Function for add followup info
    public function addFollowupInfo(Request $request)
    {
    	// Validation
		$this->validate($request, [
	    'followup'   => 'required'
	    ]);

	    // Collect data from html form
	    $followup  = trim($request->followup);
	    $remarks   = trim($request->remarks);

	    // duplicate check
	    $count = DB::table('tbl_p_follow_up')->where('follow_up',$followup)->count();
	    if($count > 0){
	    	Session::put('failed','Sorry ! '.$followup. ' Already Exits. Try To Add New');
        	return Redirect::to('addFollowup');
        	exit();
	    }

	    $data = array();
	    $data['branch_id'] 		  = $this->branch_id;
	    $data['follow_up'] 	      = $followup;
	    $data['remarks'] 		  = $remarks;
	    $data['status'] 		  = "0";
	    $data['added_id'] 		  = $this->loged_id;
	    $data['created_at'] 	  = $this->rcdate;
	    $query = DB::table('tbl_p_follow_up')->insert($data);
		if($query){
			Session::put('succes','Thanks ,Follow-up Added Sucessfully');
			return Redirect::to('addFollowup');
		}else{
			Session::put('failed','Sorry ! Error Occued. Try Again');
			return Redirect::to('addFollowup');
		}
    }

    // Function for manage followup
    public function manageFollowup()
    {
    	$result = DB::table('tbl_p_follow_up')->where('branch_id',$this->branch_id)->where('status',0)->get();
    	return view('followup.manageFollowup')->with('result',$result);
    }

    // Function for de-active advice
    public function deactiveFollowup($id)
    {
    	$data = array();
    	$data['status'] = "1";
    	$data['modified_at'] = $this->rcdate;
    	$query = DB::table('tbl_p_follow_up')->where('id',$id)->update($data);
		if($query){
			Session::put('succes','Thanks , Follow-up De-active Sucessfully');
			return Redirect::to('manageFollowup');
		}else{
			Session::put('failed','Sorry ! Error Occued. Try Again');
			return Redirect::to('manageFollowup');
		}
    }

    // Function for add dose
    public function addDose()
    {
    	return view('dose.addDose');
    }

    // Function for add dose info
    public function addDoseInfo(Request $request)
    {
    	// Validation
		$this->validate($request, [
	    'dose'   => 'required'
	    ]);

	    // Collect data from html form
	    $dose      = trim($request->dose);
	    $remarks   = trim($request->remarks);

	    // duplicate check
	    $count = DB::table('tbl_p_dose')->where('dose',$dose)->count();
	    if($count > 0){
	    	Session::put('failed','Sorry ! '.$dose. ' Already Exits. Try To Add New');
        	return Redirect::to('addDose');
        	exit();
	    }

	    $data = array();
	    $data['branch_id'] 		  = $this->branch_id;
	    $data['dose'] 	          = $dose;
	    $data['remarks'] 		  = $remarks;
	    $data['status'] 		  = "0";
	    $data['added_id'] 		  = $this->loged_id;
	    $data['created_at'] 	  = $this->rcdate;
	    $query = DB::table('tbl_p_dose')->insert($data);
		if($query){
			Session::put('succes','Thanks ,Dose Added Sucessfully');
			return Redirect::to('addDose');
		}else{
			Session::put('failed','Sorry ! Error Occued. Try Again');
			return Redirect::to('addDose');
		}
    }

    // Function for manage dose
    public function manageDose()
    {
    	$result = DB::table('tbl_p_dose')->where('branch_id',$this->branch_id)->where('status',0)->get();
    	return view('dose.manageDose')->with('result',$result);
    }

    // Function for de-active dose
    public function deactiveDose($id)
    {
    	$data = array();
    	$data['status'] = "1";
    	$data['modified_at'] = $this->rcdate;
    	$query = DB::table('tbl_p_dose')->where('id',$id)->update($data);
		if($query){
			Session::put('succes','Thanks , Dose De-active Sucessfully');
			return Redirect::to('manageDose');
		}else{
			Session::put('failed','Sorry ! Error Occued. Try Again');
			return Redirect::to('manageDose');
		}
    }


} // End of controller 
