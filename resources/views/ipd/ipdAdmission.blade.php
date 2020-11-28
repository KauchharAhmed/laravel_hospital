          @extends('admin.masterCashier')
          @section('content')
                <!-- BEGIN CONTENT -->
                <div class="page-content-wrapper">
                    <!-- BEGIN CONTENT BODY -->
                    <div class="page-content">
                        <!-- BEGIN PAGE HEADER-->
                        <!-- BEGIN PAGE BAR -->
                        <div class="page-bar" style="background: aqua;color: black;font-weight: bold;">
                            <ul class="page-breadcrumb">
                                <li>
                                 PATIENT IPD ADMISSION FORM
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
    {!! Form::open(['url' =>'patientIpdAdmission','method' => 'post','role' => 'form','class'=>'form-horizontal','files' => true]) !!}
<div class="row">
 <div class="col-md-6">
                                 <!-- BEGIN SAMPLE FORM PORTLET-->
                                <div class="portlet box blue">
                                    <div class="portlet-title">
                                        <div class="caption">
                                         PATIENT INFORMATION
                                      </div>
                                    </div>
                                    <div class="portlet-body form">
                                         
                                            <div class="form-body">
                                              <div class="form-group" id="barcode_scan_status" style="display: none;">
                                                    <label class="col-md-4 control-label">Scan Patient Barcode</label>
                                                    <div class="col-md-8">
                                                       <input type="text" class="form-control spinner" name="scan_barcode" id="scan_barcode"></div>
                                                    </div>
                                         
                                                <div class="form-group" id="old_patient_status">
                                                    <label class="col-md-4 control-label">Select Patient<span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-8">
                                                      <select class="form-control spinner selectpicker" data-live-search="true" name="old_patient" id="old_patient" required="">
                                                          <option value="">Select Old Patient</option>
                                                          <option style="color: green;font-weight: bold;" value="0">Add New Patient</option>
                                                          <?php foreach ($patient as $patients) { ?>
                                                           <option value="<?php echo $patients->id ;?>"><?php echo $patients->patient_name ;?> ( <?php echo $patients->patient_number ;?>)</option>
                                                           <?php } ?>
                                                      </select>
                                                    </div>
                                                </div>
                                                <div id="for_new_patient" style="display: none;">
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Patient Name<span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-8">
                                                       <input type="text" class="form-control spinner" name="patient_name" id="patient_name"></div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Mobile Number</label>
                                                    <div class="col-md-8">
                                                       <input type="text" class="form-control spinner" name="mobile_number" id="mobile_number"></div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Care Of Name</label>
                                                    <div class="col-md-8">
                                                       <input type="text" class="form-control spinner" name="care_of" id="care_of"></div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Age</label>
                                                    <div class="col-md-3">
                                                    <input type="text" class="form-control spinner" name="age" id="age"></div>

                                                    <label class="col-md-2 control-label">Sex</label>
                                                    <div class="col-md-3">
                                                    <select class="form-control spinner" id="sex">
                                                      <option value="">Select Sex</option>
                                                      <option value="1">Male</option>
                                                      <option value="2">Female</option>
                                                    </select>
                                                  </div>
                                                </div>
                                                 <div class="form-group">
                                                    <label class="col-md-4 control-label">Address</label>
                                                    <div class="col-md-8">
                                                    <textarea class="form-control spinner" name="address" id="address"></textarea>
                                                    </div>
                                                    <input type="hidden" name="patient_id" id="patient_id" required="">
                                                    
                                                </div>
                                              </div>      
                                                  
                                </div>
                                <!-- END SAMPLE FORM PORTLET-->
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <!-- END DASHBOARD STATS 1-->
                    </div><!-- END PAGE CONTENT BODY -->


                     <div class="col-md-6">
                                 <!-- BEGIN SAMPLE FORM PORTLET-->
                                <div class="portlet box blue">
                                    <div class="portlet-title">
                                        <div class="caption">
                                        IPD INFORMATION
                                      </div>
                                    </div>
                                    <div class="portlet-body form">
                                          
                                            <div class="form-body">
                                                  <div class="form-group">
                                                    <label class="col-md-4 control-label">Admission Fee </label>
                                                    <div class="col-md-8">
                                                        <input type="number" class="form-control spinner" name="admission_fee_hidden" value="<?php echo $ipd_fee->admission_fee_amount ; ?>" readonly="">

                                                          <input type="hidden" class="form-control spinner" name="admission_fee_amount" value="<?php echo $ipd_fee->admission_fee_amount ; ?>" required="">
                                             
                                                </div>
                                              </div>
                                               <div class="form-group">
                                                    <label class="col-md-4 control-label">Room Type <span style="color:red; font-weight: bold">*</span> </label>
                                                    <div class="col-md-4">
                                                        CABIN<input type="radio" class="form-control spinner room_type" name="room_type"  value="1" required="">
                                                </div>
                                                   <div class="col-md-4">
                                                        WARD<input type="radio" class="form-control spinner room_type" name="room_type" value="2" required="">    
                                                </div>
                                              </div>

                                                 <div class="form-group select_cabin_type" style="display: none;">
                                                    <label class="col-md-4 control-label">Select Cabin Type<span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-8">
                                                        <select class="form-control spinner selectpicker" data-live-search="true" name="cabin_type" id="cabin_type">
                                                          <option value="">Select Cabin Type</option>
                                                          <?php foreach ($cabin_type as $cabin_value) {?>
                                                            <option value="<?php echo $cabin_value->id; ?>"><?php echo $cabin_value->cabin_type_name ; ?></option>
                                                          <?php }?>
                                                        </select>
                                                </div>
                                              </div>

                                               <div class="form-group select_cabin_type" style="display: none;">
                                                    <label class="col-md-4 control-label">Available Cabin Room<span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-8">
                                                        <select class="form-control spinner"  name="cabin_room" id="cabin_room"> 
                                                        </select>
                                                </div>
                                              </div>

                                                 <div class="form-group select_ward" style="display: none;">
                                                    <label class="col-md-4 control-label">Select Ward<span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-8">
                                                        <select class="form-control spinner selectpicker" data-live-search="true" name="ward_no" id="ward_no">
                                                          <option value="">Select Ward</option>
                                                          <?php foreach ($ward as $ward_value) { ?>
                                                           <option value="<?php echo $ward_value->id;?>"><?php echo $ward_value->building_name.' - '.$ward_value->floor_name.' - '.$ward_value->ward_number ; ?></option>
                                                      
                                                          <?php } ?>
                                                        </select>
                                                </div>
                                              </div>
                                                 <div class="form-group select_ward" style="display: none;">
                                                    <label class="col-md-4 control-label">Available Bed<span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-8">
                                                        <select class="form-control spinner"  name="ward_bed" id="ward_bed"> 
                                                        </select>
                                                </div>
                                              </div>
                                                  <div class="form-group" style="display: none;">
                                                    <label class="col-md-4 control-label">Charge Amount </label>
                                                    <div class="col-md-8">
                                                        <input type="number" class="form-control spinner" name="charge_amount" id="change_amount" readonly="">
                                             
                                                </div>
                                              </div>
                                                
                                                     <div class="form-group">
                                                    <label class="col-md-4 control-label">Remarks</label>
                                                    <div class="col-md-8">
                                                        <textarea type="text" class="form-control spinner" name="remarks" placeholder="Remarks"></textarea>
                                                        </div>
                                                </div>
                                                <div class="form-group">
                                                   <label class="col-md-4 control-label">Admit Date<span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-3">
                                                        <input class="form-control form-control-inline input-medium date-picker" data-date-format="dd-mm-yyyy"  type="text" id="bill_date" name="bill_date" required="">
                                                    </div>
                                                </div>
                                                     <div class="form-group">
                                                   <label class="col-md-4 control-label">Admit Time<span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-3">
                                                       <div class="input-group bootstrap-timepicker timepicker">
                                                        <input id="timepicker1" type="text" class="form-control input-small" name="admit_time" required="">
                                                        <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                                                    </div>
                                                    </div>
                                                </div>

                                                    <div class="form-group">
                                                    <label class="col-md-4 control-label">Paid Amount<span style="color:red; font-weight: bold">*</span> </label>
                                                    <div class="col-md-8">
                                                        <input type="number" class="form-control spinner" name="paid_amount" id="paid_amount" required="" min=0 max=<?php echo $ipd_fee->admission_fee_amount ; ?> oninput="validity.valid||(value='');">
                                             
                                                </div>
                                              </div>
                                                  <div class="form-group">
                                                    <label class="col-md-4 control-label">Confirm Paid Amount <span style="color:red; font-weight: bold">*</span> </label>
                                                    <div class="col-md-8">
                                                        <input type="number" class="form-control spinner" name="confirm_paid_amount" id="confirm_paid_amount" required="" min=0 max=<?php echo $ipd_fee->admission_fee_amount ; ?> oninput="validity.valid||(value='');">
                                             
                                                </div>
                                              </div>
                                                  
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label"></label>
                                                    <div class="col-md-8">
                                                        <button type="submit" class="btn green">IPD Admission</button>
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
@section('js')
<script>
     $('#old_patient').change(function(e){
       e.preventDefault();
      var old_patient = $(this).val();
      $('#barcode_scan_status').attr("style", "display: none;");
      if(old_patient == '0'){
        $('#for_new_patient').removeAttr( 'style' );
        $('#patient_id').val('0');
      }else{
        $("#for_new_patient").attr("style", "display: none;");
        $('#patient_id').val(old_patient);
      }
    });

      $('#barcode_scan_status').change(function(e){
       e.preventDefault();
      $('#old_patient_status').attr("style", "display: none;");
    });
      // get opd category of doctor id
      $('.room_type').change(function(e){
       e.preventDefault();
       var room_type         = $('input[name=room_type]:checked').val();

       if (room_type == "1") {
          $('.select_cabin_type').removeAttr('style');
          $('.select_ward').attr('style','display:none');
       }else if(room_type == "2"){
         $('.select_ward').removeAttr('style');
         $('.select_cabin_type').attr('style','display:none');
       }else{
        $('.select_ward').attr('style','display:none');
        $('.select_cabin_type').attr('style','display:none');
       }
       });

      $("#cabin_type").change(function(e){
       e.preventDefault();
       var cabin_type         = $(this).val();
       $.ajaxSetup({
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
        });
       $.ajax({
        'url':"{{ url('/getAvailableRoomForIpdAdmission') }}",
        'type':'post',
        'dataType':'text',
        data:{  
        cabin_type:cabin_type
        },
         success:function(data)
         {
           $('#cabin_room').html(data);
         
        }
        });
       });

      $("#ward_no").change(function(e){
       e.preventDefault();
       var ward_no         = $(this).val();
       $.ajaxSetup({
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
        });
       $.ajax({
        'url':"{{ url('/getAvailableWardForIpdAdmission') }}",
        'type':'post',
        'dataType':'text',
        data:{  
        ward_no:ward_no
        },
         success:function(data)
         {
          $('#ward_bed').html(data);
        }
        });
       });

      

</script>
@endsection
