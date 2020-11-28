 @extends('admin.masterManager')
  @section('content')
                <!-- BEGIN CONTENT -->
                <div class="page-content-wrapper">
                    <!-- BEGIN CONTENT BODY -->
                    <div class="page-content">
                        <!-- BEGIN PAGE HEADER-->
                        <!-- BEGIN PAGE BAR -->
                        <div class="page-bar">
                            <ul class="page-breadcrumb">
                                <li>
                                   STAFF
                                    <i class="fa fa-circle"></i>
                               </li>
                            </ul>
                        </div>
                        <!-- END PAGE BAR -->
                        <!-- BEGIN PAGE TITLE-->
                        <h1 class="page-title"></h1>
                        <!-- END PAGE TITLE-->
                        <!-- END PAGE HEADER-->
                        <!-- BEGIN DASHBOARD STATS 1--> 
     <?php if(Session::get('succes') != null) { ?>
   <div class="alert alert-info alert-dismissible" role="alert">
  <a href="#" class="fa fa-times" data-dismiss="alert" aria-label="close"></a>
  <strong><?php echo Session::get('succes') ;  ?></strong>
  <?php Session::put('succes',null) ;  ?>
</div>
<?php } ?>
<?php
if(Session::get('failed') != null) { ?>
 <div class="alert alert-danger alert-dismissible" role="alert">
  <a href="#" class="fa fa-times" data-dismiss="alert" aria-label="close"></a>
 <strong><?php echo Session::get('failed') ; ?></strong>
 <?php echo Session::put('failed',null) ; ?>
</div>
<?php } ?>

  @if (count($errors) > 0)
    @foreach ($errors->all() as $error)      
 <div class="alert alert-danger alert-dismissible" role="alert">
   <a href="#" class="fa fa-times" data-dismiss="alert" aria-label="close"></a>
  <strong>{{ $error }}</strong>
</div>
@endforeach
@endif
<div class="row">
 <div class="col-md-6">
                                 <!-- BEGIN SAMPLE FORM PORTLET-->
                                <div class="portlet box blue">
                                    <div class="portlet-title">
                                        <div class="caption">
                                          Add Staff
                                          </div>
                                    </div>
                                    <div class="portlet-body form">
                                        	 {!! Form::open(['url' =>'addEmpInfo','role' => 'form','class'=>'form-horizontal','files' => true]) !!}
                                            <div class="form-body">

                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Select Designation<span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-8">
                                                      <select name="desi" class="form-control spinner selectpicker" data-live-search="true"  required="">
                                                        <option value="">Select Designation</option>
                                                        <?php foreach ($result as $value) { ?>
                                                        <option value="<?php echo $value->id ; ?>"><?php echo $value->desi_name ; ?></option>
                                                        <?php } ?>
                                                      </select>
                                                    </div>
                                                </div>
                                                  <div class="form-group">
                                                    <label class="col-md-4 control-label">Staff Name <span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-8">
                                                      <input type="text" name="name" class="form-control spinner" required="" >
                                                    </div>
                                                </div>
                                                 <div class="form-group">
                                                    <label class="col-md-4 control-label">Salary (Per Month) <span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-8">
                                                      <input type="number" name="salary" class="form-control spinner" required="" >
                                                    </div>
                                                </div>
                                                 <div class="form-group">
                                                    <label class="col-md-4 control-label">Confirm Salary (Per Month) <span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-8">
                                                      <input type="number" name="confirm_salary" class="form-control spinner" required="" >
                                                    </div>
                                                </div>

                                                 <div class="form-group">
                                                    <label class="col-md-4 control-label">Father Name</label>
                                                    <div class="col-md-8">
                                                      <input type="text" name="father_name" class="form-control spinner">
                                                    </div>
                                                </div>
                                                 <div class="form-group">
                                                    <label class="col-md-4 control-label">Mother Name</label>
                                                    <div class="col-md-8">
                                                      <input type="text" name="mother_name" class="form-control spinner">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Mobile <span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-8">
                                                      <input type="number" name="mobile" class="form-control spinner" required="" >
                                                    </div>
                                                </div>

                                                  <div class="form-group">
                                                    <label class="col-md-4 control-label">Email</label>
                                                    <div class="col-md-8">
                                                      <input type="email" name="email" class="form-control spinner">
                                                    </div>
                                                </div>
                                                 <div class="form-group">
                                                    <label class="col-md-4 control-label">Educational Qualification</label>
                                                    <div class="col-md-8">
                                                      <textarea name="edu" class="form-control spinner">
                                                        
                                                      </textarea>
                                                    </div>
                                                </div>
                                                 <div class="form-group">
                                                    <label class="col-md-4 control-label">NID Number 
                                                    </label>
                                                    <div class="col-md-8">
                                                      <input type="number" name="nid" class="form-control spinner" >
                                                    </div>
                                                </div>
                                                 <div class="form-group">
                                                    <label class="col-md-4 control-label">Select Sex</label>
                                                    <div class="col-md-8">
                                                      <select name="sex" class="form-control spinner" >
                                                      <option value="">Select Sex</option>
                                                      <option value="1">Male</option>
                                                      <option value="2">Female</option>
                                                      </select>
                                                    </div>
                                                </div>
                                                 <div class="form-group">
                                                   <label class="col-md-4 control-label">Join Date</label>
                                                    <div class="col-md-3">
                                                        <input class="form-control form-control-inline input-medium date-picker" data-date-format="dd-mm-yyyy"  type="text" name="join_date">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Birth Certificate 
                                                    </label>
                                                    <div class="col-md-8">
                                                      <input type="text" name="birth" class="form-control spinner" >
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Permanent Address</label>
                                                    <div class="col-md-8">
                                                      <textarea name="permanent_address" class="form-control spinner"></textarea>
                                                    </div>
                                                </div>
                                                 <div class="form-group">
                                                    <label class="col-md-4 control-label">Present Address</label>
                                                    <div class="col-md-8">
                                                      <textarea name="present_address" class="form-control spinner"></textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Staff Photo</label>
                                                    <div class="col-md-8">
                                                      <input type="file" class="form-control spinner" name="image">
                                                        </div>
                                                </div>
                                                 <div class="form-group">
                                                    <label class="col-md-4 control-label">NID /Birth Certificat Scan Copy Photo</label>
                                                    <div class="col-md-8">
                                                      <input type="file" class="form-control spinner" name="image1">
                                                        </div>
                                                </div>
                                                 <div class="form-group">
                                                    <label class="col-md-4 control-label">Ref Person
                                                    </label>
                                                    <div class="col-md-8">
                                                      <input type="text" name="ref_person" class="form-control spinner" >
                                                    </div>
                                                </div>
                                                 <div class="form-group">
                                                    <label class="col-md-4 control-label">Ref Person Mobile Number
                                                    </label>
                                                    <div class="col-md-8">
                                                      <input type="text" name="ref_mobile" class="form-control spinner" >
                                                    </div>
                                                </div>
                                                    <div class="form-group">
                                                    <label class="col-md-4 control-label">Remarks </label>
                                                    <div class="col-md-8">
                                                      <textarea name="remarks" class="form-control spinner"></textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                  <label class="col-md-4 control-label"></label>
                                                   <div class="col-md-8">
                                                  <button class="btn btn-success">Submit</button>
                            
                        </div>
                        </div>
                        {!! Form::close() !!}
                        </div>
                          <!-- END SAMPLE FORM PORTLET-->
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <!-- END DASHBOARD STATS 1-->
                    </div><!-- END PAGE CONTENT BODY -->
                </div><!-- END PAGE CONTENT --> 
              </div>
            </div>
@endsection
