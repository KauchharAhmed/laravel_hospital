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
                                    IPD CLEARENCE BILL CREATE
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
   {!! Form::open(['url' =>'ipdClearenceLedgerPayment','method' => 'post','role' => 'form','class'=>'form-horizontal','files' => true]) !!}
                    <div class="col-md-6">
                                 <!-- BEGIN SAMPLE FORM PORTLET-->
                                <div class="portlet box blue">
                                    <div class="portlet-title">
                                        <div class="caption">
                                         Given IPD Patient Info </div>
                                    </div>
                                    <div class="portlet-body form">
                                            <div class="form-body">
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
                                                        <select class="form-control spinner" name="cabin_type" id="cabin_type">
                                                          <option value="">Select Cabin Type</option>
                                                          <?php foreach ($cabin_type as $cabin_value) {?>
                                                            <option value="<?php echo $cabin_value->id; ?>"><?php echo $cabin_value->cabin_type_name ; ?></option>
                                                          <?php }?>
                                                        </select>
                                                </div>
                                              </div>

                                               <div class="form-group select_cabin_type" style="display: none;">
                                                    <label class="col-md-4 control-label">Select Cabin Room<span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-8">
                                                        <select class="form-control spinner"  name="cabin_room" id="cabin_room" data="cabin_room"> 
                                                        </select>
                                                </div>
                                              </div>

                                                 <div class="form-group select_ward" style="display: none;">
                                                    <label class="col-md-4 control-label">Select Ward<span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-8">
                                                        <select class="form-control spinner" name="ward_no" id="ward_no">
                                                          <option value="">Select Ward</option>
                                                          <?php foreach ($ward as $ward_value) { ?>
                                                           <option value="<?php echo $ward_value->id;?>"><?php echo $ward_value->building_name.' - '.$ward_value->floor_name.' - '.$ward_value->ward_number ; ?></option>
                                                      
                                                          <?php } ?>
                                                        </select>
                                                </div>
                                              </div>
                                                 <div class="form-group select_ward" style="display: none;">
                                                    <label class="col-md-4 control-label">Select Bed<span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-8">
                                                        <select class="form-control spinner"  name="ward_bed" id="ward_bed" data="cabin_room"> 
                                                        </select>
                                                </div>
                                              </div>
                                                   <div class="form-group">
                                                    <label class="col-md-4 control-label">Info</label>
                                                    <div class="col-md-8">
                                                        <textarea type="text" rows="5" class="form-control spinner" name="patient_info" id="patient_info" readonly="" style="color:green;"></textarea>
                                                        </div>
                                                </div>

                                                    <div class="form-group">
                                                   <label class="col-md-4 control-label">Room / Bed Clearence End Date<span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-3">
                                                        <input class="form-control form-control-inline input-medium date-picker" data-date-format="dd-mm-yyyy"  type="text" id="ipd_end_date" name="ipd_end_date" required="">
                                                    </div>
                                                </div>
                                                   <div class="form-group">
                                                   <label class="col-md-4 control-label">Room / Bed Clearence End Time<span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-3">
                                                       <div class="input-group bootstrap-timepicker timepicker">
                                                        <input id="timepicker1" type="text" class="form-control input-small" name="end_time" required="">
                                                        <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                                                    </div>
                                                    </div>
                                                </div>


                                                    <div class="form-group">
                                                    <label class="col-md-4 control-label"></label>
                                                    <div class="col-md-8">
                                                        <button type="submit" class="btn green">Ok And Next To Payment</button>
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
@section('js')
<script>
  // building floor allogation
  $('.room_type').change(function(e){
       e.preventDefault();
       var room_type         = $('input[name=room_type]:checked').val();
       $('#patient_info').html('');
       var subtotal_price = parseFloat($('#pur_total_price').text()); 
       $('#cabin_room').val('');
       $('#cabin_type').val('');
       $('#ward_no').val('');
       $('#ward_bed').val('');
       $('#previous_fee').val('');
       $('#total_payable').val(subtotal_price); 

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
        'url':"{{ url('/getAllBokkingCabinRoom') }}",
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
        'url':"{{ url('/getAllBokkingBedRoom') }}",
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

      // patient info by cabin room
      $("#cabin_room").change(function(e){
       e.preventDefault();
       var cabin_room         = $(this).val();
       $.ajaxSetup({
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
        });
       $.ajax({
        'url':"{{ url('/getCabinRoomPatientInfoForAddIpdPathology') }}",
        'type':'post',
        'dataType':'text',
        data:{  
        cabin_room:cabin_room
        },
         success:function(data)
         {
         var arr = data.split('//');
         var total_payable       = parseFloat($('#total_payable').val());

        if(isNaN(total_payable)){
          var total_payable_is = 0;
        }else{
          total_payable_is = total_payable;
        }
        $('#previous_fee').val(arr[1]);

         var previus_amount =  $('#previous_fee').val();
         var grand_total_payable = parseFloat(total_payable_is) + parseFloat(previus_amount);
        $('#patient_info').html(arr[0]); 
        $('#total_payable').val(grand_total_payable); 
        
        }
        });
       });
      // patient info by cabin room
      $("#ward_bed").change(function(e){
       e.preventDefault();
       var ward_bed         = $(this).val();
       $.ajaxSetup({
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
        });
       $.ajax({
        'url':"{{ url('/getWardBedPatientInfoForAddIpdPathology') }}",
        'type':'post',
        'dataType':'text',
        data:{  
        ward_bed:ward_bed
        },
         success:function(data)
         {
          $('#patient_info').html(data); 
        }
        });
       });
      // end date calculation
</script>
@endsection