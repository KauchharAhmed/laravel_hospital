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
                                    OT PATIENT LEDGER
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
   {!! Form::open(['url' =>'otPatientLedger','method' => 'post','role' => 'form','class'=>'form-horizontal','files' => true]) !!}
                    <div class="col-md-6">
                                 <!-- BEGIN SAMPLE FORM PORTLET-->
                                <div class="portlet box blue">
                                    <div class="portlet-title">
                                        <div class="caption">
                                         Given OT And Patient Info To See Ledger </div>
                                    </div>
                                    <div class="portlet-body form">
                                            <div class="form-body">
                                                <div class="form-group">
                                                    <label class="col-md-2 control-label"></label>
                                                    <div class="col-md-10">
                                                      <strong>OT BOOK NO - OT TYPE - PATIENT NAME - PATIENT ID </strong>
                                                     </div>
                                                </div>
                                                 <div class="form-group">
                                                    <label class="col-md-2 control-label">Select OT Booking Info <span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-10">
                                                        <select class="form-control spinner selectpicker" data-live-search="true" name="ot_booking_id" id="ot_booking_id" required="">
                                                          <option value="">Select OT Booking Info</option>
                                                          <?php foreach ($running_ot as $running_ot_value) { ?>
                                                           <option value="<?php echo $running_ot_value->id ;?>"><?php echo $running_ot_value->invoice ;?> - <?php echo $running_ot_value->ot_type ;?> - <?php echo $running_ot_value->patient_name ;?> - <?php echo $running_ot_value->patient_number ;?></option>
                                                           <?php } ?>
                                                      </select>

                                                     </div>
                                                </div>
                                                    <div class="form-group">
                                                    <label class="col-md-2 control-label"></label>
                                                    <div class="col-md-10">
                                                        <button type="submit" class="btn green">Current OT Patient Ledger</button>
                                                    </div>
                                                </div>
                                                                  
                        </div>
                          <!-- END SAMPLE FORM PORTLET-->
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <!-- END DASHBOARD STATS 1-->
                    </div><!-- END PAGE CONTENT BODY -->
                       {!! Form::close() !!}

                </div><!-- END PAGE CONTENT -->  
</div>
<!-- END CONTAINER -->
@endsection
