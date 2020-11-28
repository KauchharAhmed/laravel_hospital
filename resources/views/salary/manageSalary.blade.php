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
                                   SALARY
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
                                          Add Increment Or Decrement Salary
                                          </div>
                                    </div>
                                    <div class="portlet-body form">
                                        	 {!! Form::open(['url' =>'changeSalary','role' => 'form','class'=>'form-horizontal']) !!}
                                            <div class="form-body">
                                                  <div class="form-group">
                                                    <label class="col-md-4 control-label">Select Staff<span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-8">
                                                      <select name="staff" class="form-control spinner selectpicker" data-live-search="true" required="" >
                                                        <option value="">Select Staff</option>
                                                        <?php foreach ($result as $value) { ?>
                                                           <option value="<?php echo $value->id ;?>"><?php echo $value->desi_name ;?> - <?php echo $value->name ;?> </option>
                                                        <?php } ?>
                                                      </select>
                                                    </div>
                                                </div>
                                                  <div class="form-group">
                                                    <label class="col-md-4 control-label">Start Year <span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-8">
                                                      <select name="year" class="form-control spinner" required="" >
                                                        <option value="">Select Year</option>
                                                         <option value="<?php echo date('Y')?>"><?php echo date('Y'); ?></option>
                                                        <?php foreach ($year as $years) { ?>
                                                           <option value="<?php echo $years->year ;?>"><?php echo $years->year ;?></option>
                                                        <?php } ?>
                                                      </select>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Start Month <span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-8">
                                                      <select name="month" class="form-control spinner" required="" >
                                                        <option value="">Select Month</option>
                                                           <option value="01">January</option>
                                                             <option value="02">February</option>
                                                               <option value="03">March</option>
                                                                 <option value="04">April</option>
                                                                   <option value="05">May</option>
                                                                     <option value="06">June</option>
                                                                       <option value="07">July</option>
                                                                         <option value="08">August</option>
                                                                           <option value="09">September</option>
                                                                             <option value="10">October</option>
                                                                               <option value="11">November</option>
                                                                                 <option value="12">December</option>
                                                    
                                                      </select>
                                                    </div>
                                                </div>
                                                   <div class="form-group">
                                                    <label class="col-md-4 control-label">Change Salary Amount <span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-8">
                                                      <input type="number" name="salary_amount" class="form-control spinner" required="" >
                                                    </div>
                                                </div>

                                                 <div class="form-group">
                                                    <label class="col-md-4 control-label">Change Confirm Salary Amount <span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-8">
                                                      <input type="number" name="confirm_salary_amount" class="form-control spinner" required="" >
                                                    </div>
                                                </div>
                                                
                                                    <div class="form-group">
                                                    <label class="col-md-4 control-label">Remarks</label>
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
