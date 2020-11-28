          @extends('admin.masterCashier')
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
                                 OT STAFF
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
                                          Add OT Staff
                                      </div>
                                    </div>
                                    <div class="portlet-body form">
                                             {!! Form::open(['url' =>'addOTstaffInfo','method' => 'post','role' => 'form','class'=>'form-horizontal','files' => true]) !!}
                                            <div class="form-body">
                                                  <div class="form-group">
                                                    <label class="col-md-4 control-label"> Name <span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control spinner" name="name" required="">
                                             
                                                </div>
                                              </div>
                                                <div class="form-group" style="display: none;">
                                                    <label class="col-md-4 control-label"> Specialist <span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control spinner" name="specialist" >
                                             
                                                </div>
                                              </div>
                                               <div class="form-group">
                                                    <label class="col-md-4 control-label">Father's Name </label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control spinner" name="father_name">
                                             
                                                </div>
                                              </div>
                                                  <div class="form-group">
                                                    <label class="col-md-4 control-label">Mobile (Log In Id)<span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-8">
                                                        <input type="number" class="form-control spinner" name="mobile" required="">
                                             
                                                </div>
                                              </div>
                                                 <div class="form-group">
                                                    <label class="col-md-4 control-label">Type<span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-8">
                                                        <select class="form-control spinner" name="type" required="">
                                                          <option value="">Select Type</option>
                                                          <option value="1">OT Assistant</option>
                                                          <option value="2">OT Incharge</option>
                                                          <option value="3">OT Nurse</option>
                                                          <option value="4">Other</option>
                                                        </select>
                                                </div>
                                              </div>
                                                  <div class="form-group">
                                                    <label class="col-md-4 control-label">Email</label>
                                                    <div class="col-md-8">
                                                        <input type="email" class="form-control spinner" name="email">
                                             
                                                </div>
                                              </div>
                                                  <div class="form-group" style="display: none;">
                                                    <label class="col-md-4 control-label">NID</label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control spinner" name="nid">
                                             
                                                </div>
                                              </div>
                                                <div class="form-group" style="display: none;">
                                                    <label class="col-md-4 control-label">Educational Qualification</label>
                                                    <div class="col-md-8">
                                                        <textarea type="text" class="form-control spinner" name="edu_qualification" placeholder="Educational Qualification"></textarea>
                                                        </div>
                                                </div>
                                                     <div class="form-group">
                                                    <label class="col-md-4 control-label">Addres</label>
                                                    <div class="col-md-8">
                                                        <textarea type="text" class="form-control spinner" name="address" placeholder="Address"></textarea>
                                                        </div>
                                                </div>
                                                    <div class="form-group">
                                                    <label class="col-md-4 control-label">Image</label>
                                                    <div class="col-md-8">
                                                      <input type="file" class="form-control spinner" name="image">
                                                        </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label"></label>
                                                    <div class="col-md-8">
                                                        <button type="submit" class="btn green">Submit</button>
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
            </div><!-- END CONTAINER -->
@endsection
