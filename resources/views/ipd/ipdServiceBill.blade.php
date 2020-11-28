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
                                    IPD SERVICE BILL CREATE
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
                                         Create IPD Service Bill </div>
                                    </div>
                                    <div class="portlet-body form">
                                        	 {!! Form::open(['id' => 'pur_item_add_form','role' => 'form','class'=>'form-horizontal']) !!}
                                            <div class="form-body">
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Select IPD Service <span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-8">
                                                      <select class="form-control spinner selectpicker" data-live-search="true" name="product" id="product" required="" data="product_id">
                                                          <option value="">Select Service</option>
                                                          <?php foreach ($result as $value) { ?>
                                                           <option value="<?php echo $value->id ;?>"><?php echo $value->service_name.' -> '.$value->service_code ;?></option>
                                                           <?php } ?>
                                                      </select>
                                                    </div>
                                                </div>
                                                <input class="form-control m-input" type="hidden" name="item" id ="item">
                                                    <div class="form-group">
                                                    <label class="col-md-4 control-label">Selected Service</label>
                                                    <div class="col-md-8">
                                                      <input type="text" name="item_name" id="item_name" class="form-control m-input" readonly="">
                                                      </div>     
                                                </div>
                                                 <div class="form-group" style="display: none;">
                                                    <label class="col-md-4 control-label">Select Size <span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-8">
                                                      <select class="form-control spinner" name="pc" id="pc" required="">
                                                        <option value="1">No Size</option>
                                                      </select>
                                                    </div>
                                                </div>
                                                     <div class="form-group" style="display: none;">
                                                    <label class="col-md-4 control-label">Product Serial</label>
                                                    <div class="col-md-8">
                                                        <textarea type="text" class="form-control spinner" name="serial" id="serial" placeholder="Serial"></textarea>
                                                        </div>
                                                </div>
                                                <input type="hidden" id="discount" name="bonus_quantity" >
                                                    <div class="form-group">
                                                    <label class="col-md-4 control-label"> Price<span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-8">
                                                        <input type="number" class="form-control spinner" name="pur_price" id="pur_item_price" required="" step="any"></div>
                                                       
                                                </div>
                                                  <div class="form-group">
                                                    <label class="col-md-4 control-label">Quantity<span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-8">
                                                        <input type="number" class="form-control spinner" name="pur_quantity" id="pur_quantity" step="any" required=""></div>
                                                </div>
                                                   <div class="form-group">
                                                    <label class="col-md-4 control-label">Sub Total</label>
                                                    <div class="col-md-8">
                                                        <input type="number" class="form-control spinner" name="pur_subtotal_price" id="pur_subtotal_price" required="" readonly=""></div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label"></label>
                                                   <div class="col-8">
                            <input type="button" style="display:none;" name="pur_item_update" value="UPDATE " class="btn btn-primary">
                            <input type="button" style="display:none;" name="pur_item_cancel" value="CANCEL" class="btn btn-danger">
                            <input type="submit" name="pur_item_add" id="pur_item_add" value="ADD" class="btn btn-success" style="margin-left:12px;">
                            <input type="hidden" name="pur_tr_index" value="">
                            <input type="hidden" name="pur_item_id" value="">  
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


                    <div class="col-md-6">
                                 <!-- BEGIN SAMPLE FORM PORTLET-->
                                <div class="portlet box blue">
                                    <div class="portlet-title">
                                        <div class="caption">
                                         Given IPD Patient Info </div>
                                    </div>
                                    <div class="portlet-body form">
                                         <form role ="form" class="form-horizontal">
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
                                      Added Service List
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
                    <th>TEST</th>
                    <th>DESCRIPTION</th>
                    <th>QUANTITY</th>
                    <th style="display:none;">SIZE</th>
                    <th>PRICE</th>
                    <th>SUBTOTAL</th>
                    <th style="display:none;">PC</th>
                    <th style="display:none;">SERIAL</th>
                    <th>ACTION</th>
                    </tr>
                    </thead>
                     <tbody>
                     <tr>
                    <td style="display:none;"><strong></strong></td>
                    <td><strong></strong></td>
                    <td><strong></strong></td>
                    <td><strong></strong></td>
                    <td><strong></strong></td>
                    <td><strong></strong></td>
                    <td>Total</td>
                    <td><strong><span id="pur_total_price">0.00</span></strong></td>
                    <td style="display:none;"><strong></strong></td>
                    </tr>
                    </tbody>      
                    </table>
                  </div>
                  </div>
                  </div>
          </div>
    <div class="row">
 <div class="col-md-6">
                              
                                   
                                    <div class="portlet-body form">
                                            <div class="form-body">
                                              <form role ="form" class="form-horizontal">
                                              <div class="form-group">
                                                   <label class="col-md-4 control-label">Bill Date<span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-3">
                                                        <input class="form-control form-control-inline input-medium date-picker" data-date-format="dd-mm-yyyy"  type="text" id="bill_date" name="bill_date">
                                                    </div>
                                                </div>
                                              <div class="form-group" style="display: none;">
                                                   <label class="col-md-4 control-label">Report Date<span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-3">
                                                        <input class="form-control form-control-inline input-medium date-picker" data-date-format="dd-mm-yyyy"  type="text" id="report_date_and_time" name="report_date_and_time">
                                                    </div>
                                                </div>


                                                   <div class="form-group" style="display: none;">
                                                    <label class="col-md-4 control-label">Memo No</label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control spinner" name="memo_no" id="memo_no"></div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Total Payable</label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control spinner" name="total_payable" id="total_payable" disabled=""></div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Total Discount<span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-8">
                                                        <input type="number" class="form-control spinner" name="total_discount" id="total_discount"></div>
                                                </div>
                                                  <div class="form-group">
                                                    <label class="col-md-4 control-label">Now Payable</label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control spinner" name="now_payable" id="now_payable" disabled=""></div>
                                                </div>
                                                    <div class="form-group">
                                                    <label class="col-md-4 control-label">Payment <span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-8">
                                                        <input type="number" class="form-control spinner" name="total_paid" id="total_paid"></div>
                                                </div>
                                                    <div class="form-group">
                                                    <label class="col-md-4 control-label">Due</label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control spinner" name="total_due" id="total_due"  disabled=""></div>
                                                </div>
                                                    <div class="form-group" id="pc_status_is" style="display: none;">
                                                    <label class="col-md-4 control-label">PC Amount<span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control spinner" name="pc_amount" id="pc_amount"></div>
                                                </div>
                                                  <div class="form-group">
                                                    <label class="col-md-4 control-label"></label>
                                                    <div class="col-md-8">
                                                       <button type="button" id="bill_create" class="form-control spinner btn btn-primary">CREATE IPD SERVICE BILL</button>
                                                      </div>
                                                </div>
                                              </form>

                        </div>
                          <!-- END SAMPLE FORM PORTLET-->
                            </div>
               
                        <div class="clearfix"></div>
                        <!-- END DASHBOARD STATS 1-->
                    </div><!-- END PAGE CONTENT BODY -->

                     <div class="col-md-6">
                                    <div class="portlet-body form">
                                            <div class="form-body">
                                              <form role ="form" class="form-horizontal">
                                                   <div class="form-group" style="display: none;">
                                                    <label class="col-md-4 control-label">Select Doctor<span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-8">
                                                      <select class="form-control spinner selectpicker" data-live-search="true" name="doctor_id" id="doctor_id">
                                                          <option value="">Select Doctor</option>
                                                          <option value="0" style="color:blue;font-weight: bold;">None</option>
                                                          <?php foreach ($doctor as $doctors) { ?>
                                                           <option value="<?php echo $doctors->id ;?>"><?php echo $doctors->name ;?></option>
                                                           <?php } ?>
                                                      </select>
                                                    </div>
                                                </div>
                                                 <div class="form-group" style="display: none">
                                                    <label class="col-md-4 control-label">Select PC<span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-8">
                                                      <select class="form-control spinner selectpicker" data-live-search="true" name="pc_id" id="pc_id">
                                                          <option value="">Select PC</option>
                                                          <option value="0" style="color:blue;font-weight: bold;">HOSPITAL</option>
                                                          <?php foreach ($pc as $pcs) { ?>
                                                           <option value="<?php echo $pcs->id ;?>"><?php echo $pcs->name.' - > '.$pcs->mobile ;?></option>
                                                           <?php } ?>
                                                      </select>
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

   // end building floor allogation

  // end check purchase amount and confirm purchas amount
    $('#product').change(function(e){
       e.preventDefault();
       var product         = $(this).val();
       var product_name = $('#product :selected').text();
      $('#item').val(product);
      $('#item_name').val(product_name);
      $("#pur_quantity").val('');
      $("#pur_subtotal_price").val('');
       });

        $("[data=product_id]").change(function(e){
       e.preventDefault();
       var service_id         = $(this).val();
       $.ajaxSetup({
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
        });
       $.ajax({
        'url':"{{ url('/getIPDServicePrice') }}",
        'type':'post',
        'dataType':'text',
        data:{  
        service_id:service_id
        },
         success:function(data)
         {
          $('#pur_item_price').val(data);
          //$("#pur_subtotal_price").val(data);
        }
        });
       });
      // color text
     
      // incress the quantity but price fixed
      $('#pur_quantity').keyup(function(){
        var quantity      = parseFloat($(this).val());
        var price         = parseFloat($("#pur_item_price").val());
        var sub_total     = quantity * price ;
        $("#pur_subtotal_price").val(sub_total.toFixed(2));
      });

      // incress the price but quantity fixed
      $('#pur_item_price').keyup(function(){
        var price        = parseFloat($(this).val());
        var quantity     = parseFloat($("#pur_quantity").val());
        var sub_total    = quantity * price ;
        $("#pur_subtotal_price").val(sub_total.toFixed(2));
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
        $('#total_paid').keyup(function(){
        var total_paid            = parseFloat($(this).val());
        var total_discount        = parseFloat($("#total_discount").val());
        var total_payable         = parseFloat($("#total_payable").val());
        var now_payable           = total_payable - total_discount ;
        var due                   = now_payable - total_paid ;
        $("#total_due").val(due.toFixed(2));
      });

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
        var total_due             = $("#total_due").val();
        var pc_id                 = $("#pc_id").val();
        if(pc_id != '0'){
        if(total_due == ''){
          alert('First Select Above Field');
          $('#pc_amount').val('');
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


        // add to cart process
        $("#pur_item_add_form").submit(function(e){
        e.preventDefault();
            var item = $('[name="item"]').val();
            var item_name = $('[name="item_name"]').val();
            var pur_price = $('[name="pur_price"]').val();
            var pur_quantity = $('[name="pur_quantity"]').val();
            var bonus_quantity = $('[name="bonus_quantity"]').val();
            var pur_subtotal_price = $('[name="pur_subtotal_price"]').val();
            var pc = $('[name="pc"]').val();
            if(item == ""){
                alert('You must select an Product !!');
                return;
            }

             var total_row = $('#pur_item_table tr').length;
            /*for item chk */
              for(var i = 1; i< total_row-1; i++){
                var table_item   =  parseInt($('#pur_item_table tr:eq('+i+') td:eq(0)').html());
                 var table_color =  parseInt($('#pur_item_table tr:eq('+i+') td:eq(8)').html());

                if(table_item == item){
                    alert('Product already exists.');
                    return;
                }
            }
           
            var last_row = total_row - 2;
            var sl = last_row + 1;
            var recent_row = total_row - 1;
            
            $('<tr>'+
              '<td style="display:none;">'+item+'</td>'+
              '<td class="text-center"><strong>'+sl+'</strong></td>'+
              '<td>'+item_name+'</td>'+
              '<td>'+item_name+'</td>'+
              '<td>'+pur_quantity+'</td>'+
              '<td style="display:none;">'+bonus_quantity+'</td>'+
              '<td>'+pur_price+'</td>'+
              '<td>'+pur_subtotal_price+'</td>'+
              '<td style="display:none;">'+pc+'</td>'+
              '<td style="display:none;">'+serial+'</td>'+
              '<td><a style="line-height:15px!important;background:#f00;" onclick="pur_table_remove($(this).parent().parent().index());" class="btn btn-danger">Remove</a></td>'+
             
              '</tr>'
              ).insertAfter($('#pur_item_table tr:eq('+last_row+')'));
            
               
            var ttl_price = 0.00;
            for(var i = 1; i< total_row - 1; i++){
                var ttrprc = $('#pur_item_table tr:eq('+i+') td:eq(7)').text().trim();
                ttl_price += parseFloat(ttrprc);    
            }
            
            var total_price = parseFloat(ttl_price) + parseFloat(pur_subtotal_price);
            
            $('#pur_total_price').text(total_price);
            $('[name="pur_total_price"]').val(total_price);
            $('[name="pur_amt_due"]').val(total_price);
            $('#sub_total_with_discount').val(total_price);
            $('#total_discount').val('');
            $('#total_payable').val(total_price);
            pur_form_clear();
         

            //purchase.pur_inv_control_dis();       
        
        });
       /*pur item update*/
        $('[name="pur_item_update"]').click(function(){
            
            var ind = $('[name="pur_tr_index"]').val();
            if(ind == ""){
                alert('Select Item for update');
                return;
            }
            $('#pur_item_table tr:eq('+ind+')').remove();
            var item = $('[name="pur_item_id"]').val();
            var item_name = $('#pur_item_name').text().trim();
            //var item_name = $('#pur_item_show option:selected').text();
            var pur_price = $('[name="pur_price"]');
            var pur_quantity = $('[name="pur_quantity"]');
            var bonus_quantity = $('[name="bonus_quantity"]');
            var pur_subtotal_price = $('[name="pur_subtotal_price"]');
            var pc = $('[name="pc"]'); 
            var serial = $('[name="serial"]');

            //alert(item_name);
            pur_item_add(item, item_name, pur_price.val(), pur_quantity.val(), bonus_quantity.val(), pur_subtotal_price.val(),pc.val(),serial.val());
            $('[name="pur_item_update"]').css('display', 'none');
            $('[name="pur_item_cancel"]').css('display', 'none');
            $('[name="pur_item_add"]').css('display', '');
            pur_form_clear();
            
            var total_row = $('#pur_item_table tr').length;

            for(var i = 1; i< total_row-1; i++){
                $('#pur_item_table tr:eq('+i+') td:eq(1)').text(i);
            }
            
        });

        /*update end*/      
      $('[name="pur_item_cancel"]').click(function(){
            $('[name="pur_item_update"]').css('display', 'none');
            $('[name="pur_item_cancel"]').css('display', 'none');
            $('[name="pur_item_add"]').css('display', '');
            //pur_form_clear();
        });         
    function pur_table_remove(ind){
        //alert('Hello Bro');
        //return false;
        var rev_r =   $('#pur_item_table tr:eq('+ind+') td:eq(7)').html().trim();
        //var rev_r =   $('#pur_item_table tr:eq('+ind+') td:eq(6)').html().trim();
        var rev_total_price = $('#pur_total_price').text();
        var sub_total_price = rev_total_price - rev_r;

        $('#pur_total_price').text(sub_total_price);
        $('[name="pur_total_price"]').val(sub_total_price);
        $('#sub_total_with_discount').val(sub_total_price);
        $('#total_payable').val(sub_total_price);
        $('#total_paid').val('');
        $('#due').val('');
        $('#total_discount').val('');


        //alert(rev_total_price);
        // return;
        $('#pur_item_table tr:eq('+ind+')').remove();       
    }
    
    function pur_table_edit(ind){
        
        var item_id = $('#pur_item_table tr:eq('+ind+') td:eq(0)').html().trim();
        var item_name = $('#pur_item_table tr:eq('+ind+') td:eq(2)').html().trim();
        var item_price = $('#pur_item_table tr:eq('+ind+') td:eq(6)').html().trim();
        var quantity = $('#pur_item_table tr:eq('+ind+') td:eq(4)').html().trim();
        var bonus_quantity = $('#pur_item_table tr:eq('+ind+') td:eq(5)').html().trim();
        var total_price = $('#pur_item_table tr:eq('+ind+') td:eq(7)').html().trim();
    var pc = $('#pur_item_table tr:eq('+ind+') td:eq(8)').html().trim();
    var serial = $('#pur_item_table tr:eq('+ind+') td:eq(9)').html().trim();

        $('[name="pur_tr_index"]').val(ind);
        $('[name="item"]').val(item_id);
        $('[name="item_name"]').val(item_name);
        $('[name="pur_price"]').val(item_price);
        $('[name="pur_quantity"]').val(quantity);
        $('[name="bonus_quantity"]').val(bonus_quantity);
        $('[name="pur_subtotal_price"]').val(total_price);
        $('[name="pc"]').val(pc);
        $('[name="serial"]').val(serial);
           
        //item_select(item_id);

        $('[name="pur_item_update"]').css('display', '');
        $('[name="pur_item_cancel"]').css('display', '');
        $('[name="pur_item_add"]').css('display', 'none');      
    }   
    
    function pur_item_add(item, item_name, pur_price, pur_quantity, bonus_quantity, pur_subtotal_price,pc,serial){
    
        var item = $('[name="item"]').val();
        var item_name = $('[name="item_name"]').val();  
    
        if(item == ""){
            alert('You must select an item !!');
            return;
        }
        
        var total_row = $('#pur_item_table tr').length;
        
        /*for item chk */
        for(var i = 1; i< total_row-1; i++){
            var table_item =  parseInt($('#pur_item_table tr:eq('+i+') td:eq(0)').html());
            if(table_item == item){
                alert('Item already exists.');
                return;
            }
        }
       
        var last_row = total_row - 2;
        var sl = last_row + 1;
        var recent_row = total_row - 1;
        
        $('<tr>'+
          '<td style="display:none;">'+item+'</td>'+
          '<td class="text-center"><strong>'+sl+'</strong></td>'+
          '<td>'+item_name+'</td>'+
          '<td>'+item_name+'</td>'+
          '<td>'+pur_quantity+'</td>'+
          '<td style="display:none;">'+bonus_quantity+'</td>'+
          '<td>'+pur_price+'</td>'+
          '<td>'+pur_subtotal_price+'</td>'+
       '<td style="display:none;">'+pc+'</td>'+
      '<td style="display:none;">'+serial+'</td>'+
          '<td><a style="line-height:15px!important;background:#f00;" onclick="pur_table_remove($(this).parent().parent().index());" class="btn btn-danger">Remove</a></td>'+
         
          '</tr>'
          ).insertAfter($('#pur_item_table tr:eq('+last_row+')'));
        
           
        var ttl_price = 0.00;
        for(var i = 1; i< total_row - 1; i++){
            var ttrprc = $('#pur_item_table tr:eq('+i+') td:eq(7)').text().trim();
            ttl_price += parseFloat(ttrprc);    
        }
        
        var total_price = parseFloat(ttl_price) + parseFloat(pur_subtotal_price);
        
        $('#pur_total_price').text(total_price);
        $('[name="pur_total_price"]').val(total_price);
        $('[name="pur_amt_due"]').val(total_price);
        $('#sub_total_with_discount').val(total_price); 
        $('#total_payable').val(total_price); 
        $('#total_paid').val('');
        $('#due').val('');
        $('#total_discount').val('');
    }

    function pur_form_clear(){
        $('[name="product"]').val('');
        $('[name="item"]').val('');
        $('[name="item_name"]').val('');
        $('[name="pur_price"]').val('');
        $('[name="pur_quantity"]').val('');
        $('[name="bonus_quantity"]').val('');
        $('[name="pur_subtotal_price"]').val('');   
        //$('[name="pc"]').val(''); 
        $('[name="serial"]').val('');
        $('#confirm_item_price').val(''); 
        
    }
      // submit bill into database 
      $('#bill_create').click(function(){
            var room_type         = $('input[name=room_type]:checked').val();
            var cabin_type        = $('#cabin_type').val();
            var cabin_room        = $('#cabin_room').val();
            var ward_no           = $('#ward_no').val();
            var ward_bed          = $('#ward_bed').val();
            var bill_date         = $('#bill_date').val();

            var total_amount    = parseFloat($('#pur_total_price').text());
            var total_discount  = parseFloat($('#total_discount').val());
            var total_paid      = parseFloat($('#total_paid').val());
            var pc_amount       = parseFloat($('#pc_amount').val());
            var total_discountt = $('#total_discount').val();
            var total_paidd     = $('#total_paid').val(); 
            var pc_amountt      = $('#pc_amount').val();
            var memo_no         = $('#memo_no').val();

            if(total_amount == '0.00'){
                alert('Invoice Is Empty. Add At Least One IPD Service');
                return false ;
            }

            if(typeof(room_type)  === "undefined"){
             alert('Please Select Room Type');
             return false ;
            }


            if(room_type == '1'){
              if(cabin_type == ''){
                alert('Please Select Cabin Type');
                return false;
              }
              if(cabin_room == ''){
                alert('Please Select Cabin Room');
                return false;
              }

            }

            if(room_type == '2'){
              if(ward_no == ''){
                alert('Please Select Ward No');
                return false;
              }
              if(ward_bed == ''){
                alert('Please Select Ward Bed');
                return false;
              }

            }

            if(bill_date == ''){
              alert('Please Select Bill Date');
                return false ;
            }
         

              if(total_discountt == ''){
                alert('Discount Will Not Be Empty');
                return false ;
            }
            if(total_paidd == ''){
                alert('Payment Will Not Be Empty');
                return false ;
            }
 
         if(isNaN(total_paid))
         {
             alert('Payment Will Not Be Empty');
             return false ;
         }
            if(!$.isNumeric(total_paid)){
              alert ('Amount Will Be Numeric');
              return false ;
            }
            if(total_paid < 0){
              alert ('Amount Will Be Positive');
              return false ;
            }
            // payment amount calculation
           var payableAmtWithoutDiscount =  parseFloat(total_amount - total_discount);
           if(total_paid > payableAmtWithoutDiscount){
            alert('Due Amount Is Not Minus Figure');
            return  false;
           }
           // if(pc_id !='0'){
           //  if(pc_amountt == ''){
           //  alert('Given PC Amount');
           //  return  false;
           //  }
           // }
            
        $('#bill_create').attr('disabled',true); 
        var total_row  = $('#pur_item_table tr').length;
        var arr = new Array();
        for(var i = 1; i<total_row-1; i++){
            var val_arr = new Array();
            val_arr.push($('#pur_item_table tr:eq('+i+') td:eq(0)').text().trim());
            val_arr.push($('#pur_item_table tr:eq('+i+') td:eq(4)').text().trim());
            val_arr.push($('#pur_item_table tr:eq('+i+') td:eq(5)').text().trim());
            val_arr.push($('#pur_item_table tr:eq('+i+') td:eq(6)').text().trim());
            val_arr.push($('#pur_item_table tr:eq('+i+') td:eq(7)').text().trim());
            val_arr.push($('#pur_item_table tr:eq('+i+') td:eq(8)').text().trim());
            val_arr.push($('#pur_item_table tr:eq('+i+') td:eq(9)').text().trim());
            arr.push(val_arr);
        }
        var json           = JSON.stringify(arr);
        //$("#bill_create").attr("disabled",true);
        $.ajaxSetup({
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
        });
           $.ajax({
           type:'post',
           url:"{{ url('/createIPDServiceBill') }}",
           async: false,
           dataType:'text',
           cache: false,
           data:{
            'arr':json, 
            'room_type':room_type,
            'cabin_type':cabin_type, 
            'cabin_room':cabin_room,
            'ward_no':ward_no,
            'ward_bed':ward_bed, 
            'bill_date':bill_date,
            'total_amount':total_amount,
            'total_discount':total_discount,
            'total_paid':total_paid,
            'memo_no'  :memo_no,
           },
            success:function(data){
              if(data == 'r1'){
                $("#bill_create").attr("disabled",false);
                alert('Sorry , Please Select Room Type');
                return false; 
              }else if(data =='r2'){
                $("#bill_create").attr("disabled",false);
                alert('Sorry , Please Select Cabin Type');
                return false; 
              }else if(data =='r3'){
                $("#bill_create").attr("disabled",false);
                alert('Sorry , Please Select Cabin Room');
                return false; 
              }else if(data =='r4'){
                $("#bill_create").attr("disabled",false);
                alert('Sorry , Please Select Ward');
                return false; 
              }else if(data =='r4'){
                $("#bill_create").attr("disabled",false);
                alert('Sorry , Please Select Ward Bed');
                return false; 
              }else if(data == 'd1'){
                $("#bill_create").attr("disabled",false);
                alert('Sorry , Enter Invalid Date . Please Enter Valid Date');
                return false;
              }else if(data == 'p1'){
                $("#bill_create").attr("disabled",false);
                alert('Sorry , Payment Amount Big Than Total Payable Amount');
                return false;
              }else{
              location.href = "{{url('/ipdServiceBill')}}";
              window.open("{{url('/printIpdServiceBill') }}/"+data);
            }
            },
            error:function(){
                alert('Traying After The Page Refress');
            }
        });
         })

</script>
@endsection