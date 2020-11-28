          @extends('admin.masterDoctor')
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
                                   MEDICINE TYPE
                                    <!-- <i class="fa fa-circle"></i> -->
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
 <div class="col-md-7">
                                 <!-- BEGIN SAMPLE FORM PORTLET-->
                                <div class="portlet box blue">
                                    <div class="portlet-title">
                                        <div class="caption">
                                          Add Medicine Type
                                      </div>
                                    </div>
                                    <div class="portlet-body form">
                                        	 {!! Form::open(['url' =>'addMedicineTypeInfo','method' => 'post','role' => 'form','class'=>'form-horizontal']) !!}
                                            <div class="form-body">
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Full Name <span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control spinner" name="typeFullName" placeholder="Full Name" required="">
                                                      </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Short Name <span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control spinner" name="shortName" placeholder="Short Name" required="">
                                                      </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Remarks</label>
                                                    <div class="col-md-8">
                                                        <textarea type="text" class="form-control spinner" name="remarks" placeholder="Remarks"></textarea>
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