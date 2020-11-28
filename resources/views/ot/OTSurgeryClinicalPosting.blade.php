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
                                OT SURJEON AND STAFFS BILL POSTING
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
    {!! Form::open(['url' =>'patientOTSuergeryStaffBill','method' => 'post','role' => 'form','class'=>'form-horizontal','files' => true]) !!}
<div class="row">
 <div class="col-md-6">
                                 <!-- BEGIN SAMPLE FORM PORTLET-->
                                <div class="portlet box blue">
                                    <div class="portlet-title">
                                        <div class="caption">
                                         PATIENT OT INFORMATION
                                      </div>
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
                                        OT INFORMATION
                                      </div>
                                    </div>
                                    <div class="portlet-body form">
                                            <div class="form-body">

                                               <div class="form-group">
                                                    <label class="col-md-4 control-label">Select OT Room <span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-8">
                                                        <select class="form-control spinner selectpicker" data-live-search="true" name="ot_room" id="ot_room" required="">
                                                          <option value="">Select OT Room</option>
                                                          <?php foreach ($ot_room as $ot_room) { ?>
                                                           <option value="<?php echo $ot_room->id ;?>"><?php echo $ot_room->ot_room ;?></option>
                                                           <?php } ?>
                                                      </select>

                                                     </div>
                                                </div>
                                                <div class="form-group">
                                                   <label class="col-md-4 control-label">OT Date<span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-3">
                                                        <input class="form-control form-control-inline input-medium date-picker" data-date-format="dd-mm-yyyy"  type="text" id="bill_date" name="bill_date" required="">
                                                    </div>
                                                </div>
                                                   <div class="form-group">
                                                   <label class="col-md-4 control-label">OT Time<span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-3">
                                                       <div class="input-group bootstrap-timepicker timepicker">
                                                        <input id="timepicker1" type="text" class="form-control input-small" name="ot_time" required="">
                                                        <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                                                    </div>
                                                    </div>
                                                </div>

                                               <div class="form-group">
                                                    <label class="col-md-4 control-label">Remarks</label>
                                                    <div class="col-md-8">
                                                        <textarea type="text" class="form-control spinner" name="remarks" placeholder="Remarks"></textarea>
                                                        </div>
                                                </div>               
                                </div>
                                <!-- END SAMPLE FORM PORTLET-->
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <!-- END DASHBOARD STATS 1-->
                    </div><!-- END PAGE CONTENT BODY -->
                </div><!-- END PAGE CONTENT -->  
                <div class="row">
 <div class="col-md-6">
                                 <!-- BEGIN SAMPLE FORM PORTLET-->
                                <div class="portlet box blue">
                                    <div class="portlet-title">
                                        <div class="caption">
                                         DOCTOR / SURGEON INFO
                                      </div>
                                    </div>
                                    <div class="portlet-body form">
                                            <div class="form-body">
                                                   <div class="form-group">
                                                   
                                                    <div class="col-md-4">
                                                      <strong>MAIN SURGEON </strong>
                                                     </div>
                                                     <div class="col-md-4">
                                                      <strong>TOTAL CHARGE AMT </strong>
                                                     </div>
                                                       <div class="col-md-4">
                                                      <strong><input class="form-control" type="text" name="main_surjon_fee" id="main_surjon_fee" required="" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"> </strong>
                                                     </div>
                                                </div>

                                                   <div class="form-group">
                                                   
                                                    <div class="col-md-12">
                      <div class="table-responsive">                     
                      <table class="table table-bordered">
                    <thead>   
                    <tr>  
                    <th>CHOSE</th>       
                    <th>NAME</th>
                    <th>SPEIALIST</th>
                    </tr>
                    </thead>
                     <tbody>
                      <?php foreach ($main_surjon as $main_surjon_value) { ?>
                     
                     <tr>
                    <td><input class="form-control" type="checkbox" name="main_surjon[]" id="main_sujon" value="<?php echo $main_surjon_value->id;?>"></td>
                    <td><?php echo $main_surjon_value->name ; ?></td>
                    <td><?php echo $main_surjon_value->speialist ; ?></td>
                    </tr>
                    <?php } ?>
                    </tbody>      
                    </table>
                  </div>
                                                </div>
                                                </div>

                                                  <div class="form-group">
                                                   
                                                    <div class="col-md-4">
                                                      <strong>ANESTHESIA </strong>
                                                     </div>
                                                      <div class="col-md-4">
                                                      <strong>TOTAL CHARGE AMT </strong>
                                                     </div>
                                                       <div class="col-md-4">
                                                      <strong><input class="form-control" type="text" name="anes_fee" id="anes_fee" required="" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"> </strong>
                                                     </div>
                                                </div>
                                                   <div class="form-group">

                                                    <div class="col-md-12">

                                                         <div class="table-responsive">                     
                      <table class="table table-bordered">
                    <thead>   
                    <tr>  
                    <th>CHOSE</th>       
                    <th>NAME</th>
                    <th>SPEIALIST</th>
                    </tr>
                    </thead>
                     <tbody>
                      <?php foreach ($anes_surjon as $anes_surjon_value) { ?>
                     
                     <tr>
                    <td><input class="form-control" type="checkbox" name="anes_surjon[]" id="anes_surjon" value="<?php echo $anes_surjon_value->id;?>"></td>
                    <td><?php echo $anes_surjon_value->name ; ?></td>
                    <td><?php echo $anes_surjon_value->speialist ; ?></td>
                    </tr>
                    <?php } ?>
                    </tbody>      
                    </table>
                  </div>
                                                </div>
                                                </div>
                                                   <div class="form-group">
                                                    
                                                    <div class="col-md-4">
                                                      <strong>Assistant Surgeon </strong>
                                                     </div>
                                                       <div class="col-md-4">
                                                      <strong>TOTAL CHARGE AMT </strong>
                                                     </div>
                                                     <div class="col-md-4">
                                                      <strong><input class="form-control" type="text" name="assistant_surjon_fee" id="assistant_surjon_fee" required="" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"> </strong>
                                                     </div>
                                                </div>
                                                   <div class="form-group">
                                                    <div class="col-md-12">
                                                         <div class="table-responsive">                     
                    <table class="table table-bordered">
                    <thead>   
                    <tr>  
                    <th>CHOSE</th>       
                    <th>NAME</th>
                    <th>SPEIALIST</th>
                    </tr>
                    </thead>
                     <tbody>
                       <tr>
                        <td>
                        <input class="form-control" type="checkbox" name="assistant_surjon[]" id="assistant_surjon" value="0" >
                      </td>
                    <td>N/A </td>
                    <td>N/A</td>
                      <tr>
                      <?php foreach ($assistant_surjon as $assistant_surjon_value) { ?>
                    <tr>
                    <td><input class="form-control" type="checkbox" name="assistant_surjon[]" id="assistant_surjon" value="<?php echo $assistant_surjon_value->id;?>"></td>
                    <td><?php echo $assistant_surjon_value->name ; ?></td>
                    <td><?php echo $assistant_surjon_value->speialist ; ?></td>
                    </tr>
                    <?php } ?>
                    </tbody>      
                    </table>
                  </div>
                                                      


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
                                        OTHER STAFF INFO
                                      </div>
                                    </div>
                                    <div class="portlet-body form">
                                            <div class="form-body">
                                                 <div class="form-group">
                                                    <div class="col-md-4">
                                                      <strong>OT ASSISTANT </strong>
                                                     </div>
                                                       <div class="col-md-4">
                                                      <strong>TOTAL CHARGE AMT </strong>
                                                     </div>
                                                     <div class="col-md-4">
                                                      <strong><input class="form-control" type="text" name="ot_assistant_fee" id="ot_assistant_fee" required="" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"> </strong>
                                                     </div>
                                                </div>

                    <div class="table-responsive">                     
                    <table class="table table-bordered">
                    <thead>   
                    <tr>  
                    <th>CHOSE</th>       
                    <th>NAME</th>
                    <th>MOBILE</th>
                    </tr>
                    </thead>
                     <tbody>
                       <tr>
                        <td>
                        <input class="form-control" type="checkbox" name="ot_assistant[]" id="ot_assistant" value="0">
                      </td>
                    <td>N/A </td>
                    <td>N/A</td>
                      <tr>
                      <?php foreach ($ot_assistant as $ot_assistant) { ?>
                    <tr>
                    <td><input class="form-control" type="checkbox" name="ot_assistant[]" id="ot_assistant" value="<?php echo $ot_assistant->id;?>" ></td>
                    <td><?php echo $ot_assistant->name ; ?></td>
                    <td><?php echo $ot_assistant->mobile ; ?></td>
                    </tr>
                    <?php } ?>
                    </tbody>      
                    </table>
                  </div>


                                  <div class="form-group">
                                                    
                                                    <div class="col-md-4">
                                                      <strong>OTHER INFORMATION </strong>
                                                     </div>

                                                </div>
                                                   <div class="form-group">
                                                    <div class="col-md-12">
                                                      <textarea class="form-control" name="other_ot_info" rows="10"></textarea>

                                                     </div>
                                                </div>
               
                                </div>
                                <!-- END SAMPLE FORM PORTLET-->
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <!-- END DASHBOARD STATS 1-->
                    </div><!-- END PAGE CONTENT BODY -->
                </div><!-- END PAGE CONTENT -->  

                <div class="row"> 
                <div class="col-md-2"></div>
                <div class="col-md-2"><STRONG>GRAND TOTAL CHARGE  AMOUNT</STRONG></div>
                 <div class="col-md-4"><input class="form-control" type="text" name="grand_total_charge_amount" id="grand_total_charge_amount" readonly="" required=""></div>
                </div>
                <br/>
                <div class="row"> 
                <div class="col-md-2"></div>
                <div class="col-md-2"><STRONG>DISCOUNT  AMOUNT</STRONG></div>
                 <div class="col-md-4"><input class="form-control" type="text" name="grand_total_discount_amount" id="grand_total_discount_amount" required="" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"></div>
                </div>
                 <br/>

                <div class="row"> 
                <div class="col-md-2"></div>
                <div class="col-md-2"><STRONG>SUB TOTAL CHARGE AMOUNT</STRONG></div>
                 <div class="col-md-4"><input class="form-control" type="text" name="now_payable" id="now_payable" readonly="" required=""></div>
                </div>

                <div class="col-md-4"></div>

                  <div class="form-group">
                    <label class="col-md-4 control-label"></label>
                    <div class="col-md-8">
                    <button type="submit" id="bill_create" class="form-control spinner btn btn-primary"> OT SURJEON  AND  STAFFS BILL</button>
                    </div>
                   </div>
                {!! Form::close() !!}

            </div><!-- END CONTAINER -->
@endsection
@section('js')
<script>
       // main surjon
       $('#main_surjon_fee').keyup(function(){
        var main_surjon_fee                 = parseFloat($(this).val());
        var anes_fee                        = parseFloat($('#anes_fee').val());
        var assistant_surjon_fee            = parseFloat($('#assistant_surjon_fee').val());
        var ot_assistant_fee                = parseFloat($('#ot_assistant_fee').val());
        $('#grand_total_discount_amount').val('');
        $('#now_payable').val('');

        if(isNaN(anes_fee)){
          anes_fee_is = 0 ;

        }else{
          anes_fee_is = anes_fee ;
        }
         if(isNaN(assistant_surjon_fee)){
          assistant_surjon_fee_is = 0 ;

        }else{
          assistant_surjon_fee_is = assistant_surjon_fee ;
        }
        // ot assistant
        if(isNaN(ot_assistant_fee)){
          ot_assistant_fee_is = 0 ;

        }else{
          ot_assistant_fee_is = ot_assistant_fee ;
        }

         var sub_total                       = main_surjon_fee + anes_fee_is + assistant_surjon_fee_is + ot_assistant_fee_is ;

         $("#grand_total_charge_amount").val(sub_total.toFixed(2));
      });

        // anestihisia
        $('#anes_fee').keyup(function(){
        var main_surjon_fee                 = parseFloat($('#main_surjon_fee').val());
        var anes_fee_is                     = parseFloat($(this).val());
        var assistant_surjon_fee           = parseFloat($('#assistant_surjon_fee').val());
        var ot_assistant_fee                = parseFloat($('#ot_assistant_fee').val());
           $('#grand_total_discount_amount').val('');
        $('#now_payable').val('');
        if(isNaN(main_surjon_fee)){
          main_surjon_fee_is = 0 ;

        }else{
          main_surjon_fee_is = main_surjon_fee ;
        }
           if(isNaN(assistant_surjon_fee)){
          assistant_surjon_fee_is = 0 ;

        }else{
          assistant_surjon_fee_is = assistant_surjon_fee ;
        }
     
        // ot assistant
        if(isNaN(ot_assistant_fee)){
          ot_assistant_fee_is = 0 ;

        }else{
          ot_assistant_fee_is = ot_assistant_fee ;
        }

         var sub_total                       = main_surjon_fee_is + anes_fee_is + assistant_surjon_fee_is + ot_assistant_fee_is ;
          $("#grand_total_charge_amount").empty();
         $("#grand_total_charge_amount").val(sub_total.toFixed(2));
      });

      // assistat surjon
        $('#assistant_surjon_fee').keyup(function(){
        var main_surjon_fee                 = parseFloat($('#main_surjon_fee').val());
        var anes_fee                        = parseFloat($('#anes_fee').val());
        var assistant_surjon_fee_is         = parseFloat($(this).val());
        var ot_assistant_fee                = parseFloat($('#ot_assistant_fee').val());
           $('#grand_total_discount_amount').val('');
        $('#now_payable').val('');

        if(isNaN(main_surjon_fee)){
          main_surjon_fee_is = 0 ;

        }else{
          main_surjon_fee_is = main_surjon_fee ;
        }
          if(isNaN(anes_fee)){
          anes_fee_is = 0 ;

        }else{
          anes_fee_is = anes_fee ;
        }
       
        // ot assistant
        if(isNaN(ot_assistant_fee)){
          ot_assistant_fee_is = 0 ;

        }else{
          ot_assistant_fee_is = ot_assistant_fee ;
        }

         var sub_total                       = main_surjon_fee_is + anes_fee_is + assistant_surjon_fee_is + ot_assistant_fee_is ;
          $("#grand_total_charge_amount").empty();
         $("#grand_total_charge_amount").val(sub_total.toFixed(2));
      });

      // ot assisstant
        $('#ot_assistant_fee').keyup(function(){
        var main_surjon_fee                 = parseFloat($('#main_surjon_fee').val());
        var anes_fee                        = parseFloat($('#anes_fee').val());
        var assistant_surjon_fee            = parseFloat($('#assistant_surjon_fee').val());
        var ot_assistant_fee_is             = parseFloat($(this).val());
           $('#grand_total_discount_amount').val('');
        $('#now_payable').val('');

        if(isNaN(main_surjon_fee)){
          main_surjon_fee_is = 0 ;

        }else{
          main_surjon_fee_is = main_surjon_fee ;
        }
        if(isNaN(anes_fee)){
          anes_fee_is = 0 ;

        }else{
          anes_fee_is = anes_fee ;
        }
         if(isNaN(assistant_surjon_fee)){
          assistant_surjon_fee_is = 0 ;

        }else{
          assistant_surjon_fee_is = assistant_surjon_fee ;
        }
         var sub_total                       = main_surjon_fee_is + anes_fee_is + assistant_surjon_fee_is + ot_assistant_fee_is ;
         $("#grand_total_charge_amount").val(sub_total.toFixed(2));
      });
      //discount
      $('#grand_total_discount_amount').keyup(function(){
        var total_discount        = parseFloat($(this).val());
        var total_payable         = parseFloat($("#grand_total_charge_amount").val());
        var now_payable           = total_payable - total_discount ;
        if(now_payable < 0){
          $("#grand_total_discount_amount").val('') ;
          $("#now_payable").val('') ;
          alert('Subtotal Amount Will Not Be Mininus Figure');
          return false;
        }else{
        $("#now_payable").val(now_payable.toFixed(2));
      }
      });
          
</script>
@endsection
