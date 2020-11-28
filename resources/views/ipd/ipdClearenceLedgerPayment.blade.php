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
 <div class="col-md-6">
                                 <!-- BEGIN SAMPLE FORM PORTLET-->
                                <div class="portlet box blue">
                                    <div class="portlet-title">
                                        <div class="caption">
                                         Patient Details </div>
                                    </div>
                                    <div class="portlet-body form">
                                         <form role ="form" class="form-horizontal">
                                            <div class="form-body">
                                                   <div class="form-group">
                                                    <label class="col-md-4 control-label">Patient ID</label>

                                                    <div class="col-md-6">
                                                      <strong>
                                                        <input type="text" class="form-control spinner" readonly="" style="background: white;border:none;color:black" value="<?php echo $patient_info->patient_number; ?>" >
                                                      </strong>
                                                      </div>
                                                </div>
                                                   <div class="form-group">
                                                    <label class="col-md-4 control-label">Patient Name</label>
                                                    
                                                    <div class="col-md-6">
                                                      <strong>
                                                        <input type="text" class="form-control spinner" readonly="" style="background: white;border:none;color:black" value="<?php echo $patient_info->patient_name; ?>" >
                                                      </strong>
                                                      </div>
                                                </div>
                                                    <div class="form-group">
                                                    <label class="col-md-4 control-label">C / O</label>
                                                    
                                                    <div class="col-md-6">
                                                      <strong>
                                                        <input type="text" class="form-control spinner" readonly="" style="background: white;border:none;color:black" value="<?php echo $patient_info->c_o_name; ?>" >
                                                      </strong>
                                                      </div>
                                                </div>
                                                  <div class="form-group">
                                                    <label class="col-md-4 control-label">Mobile</label>
                                                    
                                                    <div class="col-md-6">
                                                      <strong>
                                                        <input type="text" class="form-control spinner" readonly="" style="background: white;border:none;color:black" value="<?php echo $patient_info->patient_mobile; ?>" >
                                                      </strong>
                                                      </div>
                                                </div>
                                                     <div class="form-group">
                                                    <label class="col-md-4 control-label">Age</label>
                                                    
                                                    <div class="col-md-6">
                                                      <strong>
                                                        <input type="text" class="form-control spinner" readonly="" style="background: white;border:none;color:black" value="<?php echo $patient_info->patient_age; ?>" >
                                                      </strong>
                                                      </div>
                                                </div>
                                                      <div class="form-group">
                                                    <label class="col-md-4 control-label">Sex</label>
                                                    
                                                    <div class="col-md-6">
                                                      <strong>
                                                        <input type="text" class="form-control spinner" readonly="" style="background: white;border:none;color:black" value="<?php echo $patient_info->patient_sex; ?>" >
                                                      </strong>
                                                      </div>
                                                </div>
                                                      <div class="form-group">
                                                    <label class="col-md-4 control-label">Address</label>
                                                    
                                                    <div class="col-md-6">
                                                      <strong>
                                                        <input type="text" class="form-control spinner" readonly="" style="background: white;border:none;color:black" value="<?php echo $patient_info->address; ?>" >
                                                      </strong>
                                                      </div>
                                                </div>
                                
                           </form>
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
                                        IPD Patient Info </div>
                                    </div>
                                    <div class="portlet-body form">
                                         <form role ="form" class="form-horizontal">
                                            <div class="form-body">

                                                 <div class="form-group">
                                                    <label class="col-md-4 control-label"> Admit No</label>

                                                    <div class="col-md-8">
                                                      <strong>
                                                        <input type="text" class="form-control spinner" readonly="" style="background: white;border:none;color:black" value="<?php echo $ipd_admit_query->invoice; ?> " >
                                                      </strong>
                                                      </div>
                                                </div>
                                           

                                                    <div class="form-group">
                                                    <label class="col-md-4 control-label"> Admit Date / Time</label>

                                                    <div class="col-md-8">
                                                      <strong>
                                                        <input type="text" class="form-control spinner" readonly="" style="background: white;border:none;color:black" value="<?php echo date('d M Y',strtotime($ipd_admit_query->admit_date)); ?> <?php echo date('h:i:s a',strtotime($ipd_admit_query->admit_time)); ?>" >
                                                      </strong>
                                                      </div>
                                                </div>

                                                <?php if($room_type == '1'):?>
                                                     <div class="form-group">
                                                    <label class="col-md-4 control-label"> Building</label>

                                                    <div class="col-md-8">
                                                      <strong>
                                                        <input type="text" class="form-control spinner" readonly="" style="background: white;border:none;color:black" value="<?php echo $cabin_room_details->building_name; ?>">
                                                      </strong>
                                                      </div>
                                                </div>
                                                   <div class="form-group">
                                                    <label class="col-md-4 control-label"> Floor</label>

                                                    <div class="col-md-8">
                                                      <strong>
                                                        <input type="text" class="form-control spinner" readonly="" style="background: white;border:none;color:black" value="<?php echo $cabin_room_details->floor_name; ?>">
                                                      </strong>
                                                      </div>
                                                </div>
                                                  <div class="form-group">
                                                    <label class="col-md-4 control-label"> Cabin Type</label>

                                                    <div class="col-md-8">
                                                      <strong>
                                                        <input type="text" class="form-control spinner" readonly="" style="background: white;border:none;color:black" value="<?php echo $cabin_room_details->cabin_type_name; ?>">
                                                      </strong>
                                                      </div>
                                                </div>
                                                  <div class="form-group">
                                                    <label class="col-md-4 control-label"> Room No </label>

                                                    <div class="col-md-8">
                                                      <strong>
                                                        <input type="text" class="form-control spinner" readonly="" style="background: white;border:none;color:black" value="<?php echo $cabin_room_details->room_no; ?>">
                                                      </strong>
                                                      </div>
                                                </div>


                                                <?php elseif($room_type = '2'): ?>
                                                      <div class="form-group">
                                                    <label class="col-md-4 control-label"> Building</label>

                                                    <div class="col-md-8">
                                                      <strong>
                                                        <input type="text" class="form-control spinner" readonly="" style="background: white;border:none;color:black" value="<?php echo $ward_no_details->building_name; ?>">
                                                      </strong>
                                                      </div>
                                                </div>
                                                   <div class="form-group">
                                                    <label class="col-md-4 control-label"> Floor</label>

                                                    <div class="col-md-8">
                                                      <strong>
                                                        <input type="text" class="form-control spinner" readonly="" style="background: white;border:none;color:black" value="<?php echo $ward_no_details->floor_name; ?>">
                                                      </strong>
                                                      </div>
                                                </div>
                                                 <div class="form-group">
                                                    <label class="col-md-4 control-label"> Ward</label>

                                                    <div class="col-md-8">
                                                      <strong>
                                                        <input type="text" class="form-control spinner" readonly="" style="background: white;border:none;color:black" value="<?php echo $ward_no_details->ward_number; ?>">
                                                      </strong>
                                                      </div>
                                                </div>

                                                  <div class="form-group">
                                                    <label class="col-md-4 control-label"> Bed No</label>

                                                    <div class="col-md-8">
                                                      <strong>
                                                        <input type="text" class="form-control spinner" readonly="" style="background: white;border:none;color:black" value="<?php echo $ward_bed_details->bed_no; ?>">
                                                      </strong>
                                                      </div>
                                                </div>
                                                 <?php endif;?>

                                                     
                                                  <div class="form-group">
                                                    <label class="col-md-4 control-label">End Date / Time</label>

                                                    <div class="col-md-8">
                                                      <strong>
                                                        <input type="text" class="form-control spinner" readonly="" style="background: white;border:none;color:black" value="<?php echo date('d M Y',strtotime($ipdEndDate)); ?> <?php echo date('h:i:s a',strtotime($ipd_end_time)); ?>">
                                                      </strong>
                                                      </div>
                                                </div>

                   
                        </form>
                        </div>
                          <!-- END SAMPLE FORM PORTLET-->
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <!-- END DASHBOARD STATS 1-->
                    </div><!-- END PAGE CONTENT BODY -->

                </div><!-- END PAGE CONTENT -->  

          <div class="row">
          <div class="portlet box blue">
                                    <div class="portlet-title">
                                        <div class="caption">
                                      IPD PATIENT LEDGER
                                    </div>
                                        <div class="tools">
                                            <a href="javascript:;" class="collapse" data-original-title="" title=""> </a>
                                            <a href="#portlet-config" data-toggle="modal" class="config" data-original-title="" title=""> </a>
                                            <a href="javascript:;" class="reload" data-original-title="" title=""> </a>
                                            <a href="javascript:;" class="remove" data-original-title="" title=""> </a>
                                        </div>
                                    </div>
                      <div class="portlet-body">
                      <div class="table-responsive">                     
                      <table class="table table-bordered" id="pur_item_table">
                    <thead>   
                    <tr>         
                    <th style="display:none;"></th>
                    <th class="text-center">SL NO.</th>
                    <th>DATE / TIME</th>
                    <th>TYPE</th>
                    <th>DESCRIPTION</th>
                    <th>PAYABLE AMT</th>
                    <th>DISCOUNT</th>
                    <th>PAYMENT AMT</th>
                    </tr>
                    </thead>
                     <tbody>
                    <?php 
                    $i = 1 ;
                    $total_payable_amount = 0 ;
                    $total_discount_amount = 0 ;
                    $total_payment_amount = 0 ;
                    foreach ($ipd_ledger_info as $ipd_ledger_value) { 
                      $total_payable_amount   = $total_payable_amount + $ipd_ledger_value->payable_amount ;
                      $total_discount_amount  = $total_discount_amount + $ipd_ledger_value->discount ; 
                      $total_payment_amount   = $total_payment_amount + $ipd_ledger_value->payment_amount ; 
                      ?>
                     <tr>
                    <td><?php echo $i++;?></td>
                    <td><?php echo date('d M Y',strtotime($ipd_ledger_value->service_created_at));?> / <?php echo date('h:i:s a',strtotime($ipd_ledger_value->created_time));?></td>
                    <td>
                      <?php if($ipd_ledger_value->service_type == '1'){
                        echo "Admission Fee";

                      }elseif($ipd_ledger_value->service_type == '2'){
                        echo "Pathology Bill";

                      }elseif($ipd_ledger_value->service_type == '3'){
                        echo "Service Bill";

                      }elseif($ipd_ledger_value->service_type == '4'){
                         echo "Collection";

                      }
                      ?>
                      </td>
                    <td><?php echo $ipd_ledger_value->purpose;?></td>
                    <td><?php echo $ipd_ledger_value->payable_amount;?></td>
                    <td><?php echo $ipd_ledger_value->discount;?></td>
                    <td><?php echo $ipd_ledger_value->payment_amount;?></td>
                    </tr>
                    <?php } ?>
                       <tr>
                    <td><?php echo $i++;?></td>
                    <td></td>
                    <td>
                   Room / Bed Rent
                     
                      </td>
                    <td>  <?php echo  $cabin_or_bed_rent_days.' Days * '.$charge_amount_is.' Amount (Per Day )' ;?></td>
                    <td><?php echo number_format($total_room_bed_charge_amount,2);?></td>
                    <td>0.00</td>
                    <td>0.00</td>

                       </tr>
                    </tbody>      
                    </table>
                  </div>
                  </div>
                  </div>
  <?php
  // calculation
  $grand_total_payable_with_bed_rent = $total_payable_amount + $total_room_bed_charge_amount ;
  $grand_total_payable_without_discount = $grand_total_payable_with_bed_rent - $total_discount_amount ;
  $grand_total_ipd_payable_amt = $grand_total_payable_without_discount - $total_payment_amount ;
  ?>
</div>
    <div class="row">
 <div class="col-md-6">
                              
                                   
                                    <div class="portlet-body form">
                                            <div class="form-body">
                                               <form role ="form" class="form-horizontal">
                                            <input type="hidden" name="ipd_admission_id" id="ipd_admission_id" value="<?php echo $ipd_admit_query->id ?>" required="">
                                               <input type="hidden" name="ipd_admission_invoice" id="ipd_admission_invoice" value="<?php echo $ipd_admit_query->id ?>" required="">
                                                <input type="hidden" name="patient_id_is" value="<?php echo $patient_info->id ?>" id="patient_id_is" required="">

                                                <input type="hidden" name="ipd_end_date" id="ipd_end_date" value="<?php echo $ipdEndDate ; ?>" required="">

                                                   <input type="hidden" name="ipd_end_time" id="ipd_end_time" value="<?php echo $ipd_end_time ; ?>" required="">

                                                     <input type="hidden" name="total_bed_rent" id="total_bed_rent" value="<?php echo $total_room_bed_charge_amount ; ?>" required="">
                                              <div class="form-group">
                                                   <label class="col-md-4 control-label">Bill Date<span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-3">
                                                        <input class="form-control form-control-inline input-medium date-picker" data-date-format="dd-mm-yyyy"  type="text" id="bill_date" name="bill_date" required="">
                                                    </div>
                                                </div>

                                                   <div class="form-group" style="display: none">
                                                    <label class="col-md-4 control-label">Memo No</label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control spinner" name="memo_no" id="memo_no"></div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Total Payable</label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control spinner" name="grand_total_payable" id="grand_total_payable" value="<?php echo $grand_total_payable_with_bed_rent;?>" readonly="" readonly=""></div>
                                                </div>
                                                   <div class="form-group">
                                                    <label class="col-md-4 control-label">Total Discount</label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control spinner" name="grand_total_discount" id="grand_total_discount" value="<?php echo $total_discount_amount;?>" readonly="" readonly=""></div>
                                                </div>
                                                   <div class="form-group">
                                                    <label class="col-md-4 control-label">Total Payment</label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control spinner" name="previous_total_payment" id="previous_total_payment" value="<?php echo $total_payment_amount;?>" readonly="" readonly=""></div>
                                                </div>
                                                   <div class="form-group">
                                                    <label class="col-md-4 control-label">Due</label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control spinner" name="total_payable" id="total_payable" value="<?php echo $grand_total_ipd_payable_amt;?>" readonly="" readonly=""></div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Discount<span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-8">
                                                        <input type="number" class="form-control spinner" name="total_discount" id="total_discount" required=""></div>
                                                </div>
                                                  <div class="form-group">
                                                    <label class="col-md-4 control-label">Now Payable</label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control spinner" name="now_payable" id="now_payable" readonly="" readonly=""></div>
                                                </div>
                                                   <div class="form-group">
                                                    <label class="col-md-4 control-label">Select PC<span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-8">
                                                      <select class="form-control spinner selectpicker" data-live-search="true" name="pc_id" id="pc_id" required="">
                                                          <option value="">Select PC</option>
                                                          <option value="0" style="color:blue;font-weight: bold;">HOSPITAL</option>
                                                          <?php foreach ($pc as $pcs) { ?>
                                                           <option value="<?php echo $pcs->id ;?>"><?php echo $pcs->name.' - > '.$pcs->mobile ;?></option>
                                                           <?php } ?>
                                                      </select>
                                                    </div>
                                                </div>
                                                
                                                    <div class="form-group" id="pc_status_is" style="display: none;">
                                                    <label class="col-md-4 control-label">PC Amount<span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control spinner" name="pc_amount" id="pc_amount"></div>
                                                </div>
                                                  <div class="form-group">
                                                    <label class="col-md-4 control-label"></label>
                                                    <div class="col-md-8">
                                                       <button type="button" id="bill_create" class="form-control spinner btn btn-primary">CREATE IPD CLEARENCE BILL</button>
                                                      </div>
                                                </div>
                                                  </form>
                                                  

                        </div>
                          <!-- END SAMPLE FORM PORTLET-->
                            </div>
               
                        <div class="clearfix"></div>
                        <!-- END DASHBOARD STATS 1-->
                    </div><!-- END PAGE CONTENT BODY -->

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
       $('#cabin_room').val('');
       $('#cabin_type').val('');
       $('#ward_no').val('');
       $('#ward_bed').val('');
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
        $('#patient_info').html(data); 
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

        $('#total_discount').keyup(function(){
        var total_discount        = parseFloat($(this).val());
        var total_payable         = parseFloat($("#total_payable").val());
        var total_paid            = parseFloat($("#total_paid").val());
        var now_payable           = total_payable - total_discount ;
        var due                   = now_payable - total_paid ;
        $("#now_payable").val(now_payable.toFixed(2));
        $("#total_due").val(due.toFixed(2));
      });


        // calculation the due
      //   $('#total_paid').keyup(function(){
      //   var total_paid            = parseFloat($(this).val());
      //   var total_discount        = parseFloat($("#total_discount").val());
      //   var total_payable         = parseFloat($("#total_payable").val());
      //   var now_payable           = total_payable - total_discount ;
      //   var due                   = now_payable - total_paid ;
      //   $("#total_due").val(due.toFixed(2));
      // });

      $('#pc_id').change(function(e){
       e.preventDefault();
       var pc_id      = $(this).val();
       if(pc_id == '0'){
        $('#pc_status_is').attr("style", "display: none;");
       }else{
        $('#pc_status_is').removeAttr( 'style' );
       } 
    });
        // calculation the due
        $('#pc_amount').keyup(function(){
        var pc_amount             = parseFloat($(this).val());
        var total_due             = $("#now_payable").val();
        var pc_id                 = $("#pc_id").val();
        if(pc_id != '0'){
        if(total_due == ''){
          alert('First Select Above Field');
          $('#pc_amount').val('');
          return false;
        }else if(isNaN(total_due)){
          $('#pc_amount').val('');
           alert('First Select Above Field');
          return false;
        }else{
          var now_payable = $("#now_payable").val();
          if(pc_amount > now_payable){
            alert('Sorry PC Amount Big Than Now Payable Amount');
            $('#pc_amount').val('');
            return false;
          }
        }
      }

      });

        // submit bill into database 
      $('#bill_create').click(function(){
            var ipd_admission_id           = $('#ipd_admission_id').val();
            var ipd_admission_invoice      = $('#ipd_admission_invoice').val();
            var patient_id_is              = $('#patient_id_is').val();
            var ipd_end_date               = $('#ipd_end_date').val();
            var pc_id                      = $('#pc_id').val();
            var bill_date                  = $('#bill_date').val();
            var total_payable              = parseFloat($('#total_payable').val());
            var total_discount             = parseFloat($('#total_discount').val());
            var now_payable                = parseFloat($('#now_payable').val());
            var pc_amount                  = parseFloat($('#pc_amount').val());
            var total_discountt            = $('#total_discount').val();
            var pc_amountt                 = $('#pc_amount').val();
            var memo_no                    = $('#memo_no').val();
            var ipd_end_time               = $('#ipd_end_time').val();
            var total_bed_rent             = parseFloat($('#total_bed_rent').val());

            if(ipd_admission_id == ''){
              alert('Please IPD Admission ID');
                return false ;
            }
            if(ipd_admission_invoice == ''){
              alert('Please IPD Admission Invoice');
                return false ;
            }
            if(patient_id_is == ''){
              alert('Please Patient ID');
                return false ;
            }


            if(bill_date == ''){
              alert('Please Select Bill Date');
                return false ;
            }

              if(pc_id == ''){
              alert('Please Select PC');
                return false ;
            }

            if(total_discountt == ''){
                alert('Discount Will Not Be Empty');
                return false ;
            }

           if(now_payable < 0){
            alert('Now Payable Amount Is Not Minus Figure');
            return  false;
           }
           if(pc_id !='0'){
            if(pc_amountt == ''){
            alert('Given PC Amount');
            return  false;
            }
           }
            
        $('#bill_create').attr('disabled',true); 
        $.ajaxSetup({
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
        });
           $.ajax({
           type:'post',
           url:"{{ url('/createIpdClearenceBill') }}",
           async: false,
           dataType:'text',
           cache: false,
           data:{
            'ipd_admission_id':ipd_admission_id,
            'ipd_admission_invoice':ipd_admission_invoice,
            'patient_id_is':patient_id_is,
            'ipd_end_date':ipd_end_date,
            'pc_id':pc_id,
            'bill_date':bill_date,
            'total_payable':total_payable,
            'total_discount':total_discount,
            'pc_amount':pc_amount,
            'memo_no'  :memo_no,
            'ipd_end_time'  :ipd_end_time,
            'total_bed_rent'  :total_bed_rent 
           },
            success:function(data){
             if(data == 'd1'){
                $("#bill_create").attr("disabled",false);
                alert('Sorry ! Bill Date Will Not Be Small Than Patient IPD End Date');
                return false;
              }else if(data == 'd2'){
                $("#bill_create").attr("disabled",false);
                alert('Sorry ! Enter Wrong Bill Date');
                return false;
              }else if(data == 'i1'){
                $("#bill_create").attr("disabled",false);
                alert('Sorry ! Please IPD Admission ID');
                return false;
              }else if(data == 'i2'){
                $("#bill_create").attr("disabled",false);
                alert('Sorry ! Please IPD Admission Invoice');
                return false;
              }else if(data == 'i3'){
                $("#bill_create").attr("disabled",false);
                alert('Sorry ! Please Patient ID');
                return false;
              }else if(data == 'i4'){
                $("#bill_create").attr("disabled",false);
                alert('Now Payable Amount Is Not Minus Figure');
                return false;
              }else if(data == 'i5'){
                $("#bill_create").attr("disabled",false);
                alert('Sorry , PC Amount Not Big Than Now Payable Amount');
                return false;
              }else if(data == 'dup1'){
                $("#bill_create").attr("disabled",false);
                alert('Sorry , IPD Clearence Bill Already Created Of This Patient');
                return false;
              }else if(data == 'dup2'){
                $("#bill_create").attr("disabled",false);
                alert('Sorry , IPD Clearence Bill Already Created Of This Patient');
                return false;
              }else{
              location.href = "{{url('/collectBill')}}";
              window.open("{{url('/printIpdClearBill') }}/"+data);
            }
            },
            error:function(){
                alert('Traying After The Page Refress');
            }
        });
         })



</script>
@endsection