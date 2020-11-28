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
                                   OT STAFF PAYMENT
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
   {!! Form::open(['url' => 'otPaymentAmt','role' => 'form','class'=>'form-horizontal']) !!}
 <div class="col-md-6">
                                 <!-- BEGIN SAMPLE FORM PORTLET-->
                                <div class="portlet box blue">
                                    <div class="portlet-title">
                                        <div class="caption">
                                          OT Information
                                       </div>
                                    </div>
                                    <div class="portlet-body form">
                                             
                                            <div class="form-body">
                                             <?php
                                            $ot_booking_query = DB::table('tbl_ot_booking')->where('id',$ot_booking_id)->first(); 
                                            $ot_type_query = DB::table('tbl_ot_type')->where('id',$ot_booking_query->ot_type)->first();
                                            $ot_clear_query = DB::table('tbl_ot_clear_bill')->where('ot_booking_id',$ot_booking_id)->first();             
                                             ?>

                                                   <div class="form-group">
                                                    <label class="col-md-4 control-label">OT Booking No <span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control spinner" value="<?php echo $ot_booking_query->invoice ;  ?>" readonly=""></div>
                                                </div>
                                                      <div class="form-group">
                                                    <label class="col-md-4 control-label">OT Booking Date <span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control spinner" value="<?php echo date('d M Y',strtotime($ot_booking_query->booking_date)) ;?>" readonly=""></div>
                                                </div>
                                                      <div class="form-group">
                                                    <label class="col-md-4 control-label">OT Clearence Date <span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control spinner" value="<?php echo date('d M Y',strtotime($ot_clear_query->bill_date)) ;?>" readonly=""></div>
                                                </div>
                                                    <div class="form-group">
                                                    <label class="col-md-4 control-label">OT Type <span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control spinner" value="<?php  echo $ot_type_query->ot_type ;?>" readonly=""></div>
                                                </div>
                                                     <div class="form-group">
                                                    <label class="col-md-4 control-label">Patient Info</label>
                                                    <div class="col-md-8">
                                                        <textarea class="form-control spinner" cols="10" rows="5" readonly=""><?php $patient_info = DB::table('tbl_patient')->where('id',$patient_id)->first();echo $patient_info->patient_name ;?>&#13;&#10;<?php echo $patient_info->patient_number ; ?>&#13;&#10;<?php echo $patient_info->patient_mobile ; ?>&#13;&#10;<?php echo $patient_info->address ; ?> 
                                                     </textarea>
                                                    </div>
                                                </div>
                                                     <div class="form-group">
                                                    <label class="col-md-4 control-label">Staff Type <span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control spinner" value=" <?php 
                                                      if($staff_type == '2'){
                                                        echo "Main Surgeon";

                                                      }elseif($staff_type == '3'){
                                                        echo "Assistant Surgeon";
                                                      }elseif($staff_type == '4'){
                                                        echo "Anesthesia";
                                                      }elseif($staff_type == '5'){
                                                        echo "OT Assistant";

                                                      }?>" readonly="" ></div>
                                                </div>

                                                  <div class="form-group">
                                                    <label class="col-md-4 control-label">Staff Name<span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control spinner" value="<?php echo $result->name ; ?>" readonly=""></div>
                                                </div>
                                                      <div class="form-group">
                                                    <label class="col-md-4 control-label">Mobile<span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control spinner" value="<?php echo $result->mobile ; ?>" readonly=""></div>
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
                                          Payment To OT Staff
                                       </div>
                                    </div>
                                    <div class="portlet-body form">
                                            
                                            <div class="form-body">

                                                  <div class="form-group">
                                                    <label class="col-md-4 control-label">Payable Amount<span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-8">
                                                        <input type="number" class="form-control spinner" name="payable_amount" id="payable_amount" value=<?php echo $result->amount ; ?> required="" readonly=""></div>
                                                </div>
                                                      <div class="form-group">
                                                    <label class="col-md-4 control-label">Previous Payment<span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-8">
                                                        <input type="number" class="form-control spinner" name="previous_payment_amount" id="previous_payment_amount" value="<?php echo $total_payment;?>" required="" readonly=""></div>
                                                </div>
                                                <?php
                                                $due = $result->amount - $total_payment ;
                                                ?>
                                                  <div class="form-group">
                                                    <label class="col-md-4 control-label">Due<span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-8">
                                                        <input type="number" class="form-control spinner" name="due" id="due" value="<?php echo $due ;  ?>" required="" readonly=""></div>
                                                </div>  
                                                   <div class="form-group">
                                                    <label class="col-md-4 control-label">Payment Method<span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-8">
                                                        <select class="form-control spinner" name="payment_method" id="payment_method" required="">
                                                            <option value="">Select Payment Method</option>
                                                            <option value="1">Cash</option>
                                                            <!--<option value="2">Mobile Banking</option>-->
                                                            <option value="3">Bank</option>
                                                        </select> 
                                                </div> 
                                                </div>
                                                  <div class="form-group" id="bank" style="display: none;">
                                                  <label class="col-md-4 control-label">Select Bank<span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-8">
                                                        <select class="form-control spinner selectpicker" data-live-search="true" name="bank_account_number" id="bank_account_number">
                                                            <option value="">Select Bank And Account Number</option>
                                                        <?php foreach ($bank as $banks) { ?>
                                                              <option value="<?php echo $banks->id ;?>"><?php echo $banks->bank_name.' -> '.$banks->account_no ; ?></option>
                                                            <?php } ?>
                                                         </select> 
                                                  </div> 
                                                </div>
                                                <div class="form-group" id="t_t_number" style="display: none;">
                                                    <label class="col-md-4 control-label">T.T No<span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-8">
                                                          <input type="text" class="form-control spinner" name="bank_paper">
                                                        
                                                </div> 
                                                </div>
                                                  <div class="form-group">
                                                   <label class="col-md-4 control-label">Payment Date<span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-3">
                                                        <input class="form-control form-control-inline input-medium date-picker" data-date-format="dd-mm-yyyy"  type="text" name="payment_date" required="">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Payment Amount<span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-8">
                                                        <input type="number" class="form-control spinner" name="payment_amount" id="payment_amount" required="">
                                                    </div>
                                                </div> 
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Confirm Payment Amount<span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-8">
                                                        <input type="number" class="form-control spinner" name="confirm_payment_amount" id="confirm_payment_amount" required="">
                                                    </div>
                                                </div>
                                                 <div class="form-group">
                                                    <label class="col-md-4 control-label">Remarks</label>
                                                    <div class="col-md-8">
                                                        <textarea class="form-control spinner" name="remarks"></textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label"></label>
                                                    <div class="col-md-8">
                                                        <button type="submit" class="btn green">Payment 
                                                        </button>
                                                    </div>
                                                </div> 
                                                <input type="hidden" name="ot_booking_id" value="<?php echo $ot_booking_id ; ?>" required="">
                                                <input type="hidden" name="staff_type" value="<?php echo $staff_type ; ?>" required="">
                                                <input type="hidden" name="staff_id" value="<?php echo $staff ; ?>" required="">
                                                <input type="hidden" name="patient_id" value="<?php echo $patient_id ; ?>" required="">

                        </div>
                          <!-- END SAMPLE FORM PORTLET-->
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <!-- END DASHBOARD STATS 1-->
                    </div><!-- END PAGE CONTENT BODY -->
                    {!! Form::close() !!} 
                </div><!-- END PAGE CONTENT --> 
                </div><!-- END PAGE CONTENT -->             
            </div><!-- END CONTAINER -->
@endsection
@section('js')
<script>
      $('#payment_method').change(function(e){
       e.preventDefault();
      var method_type = $(this).val();
      if(method_type == '2'){
        $('#mobile').removeAttr( 'style' );
        $('#transaction_number').removeAttr( 'style' );
      }else if(method_type == '3'){
        $('#bank').removeAttr( 'style' );
        $('#t_t_number').removeAttr( 'style' );
        
        $("#mobile").attr("style", "display: none;");
        $("#transaction_number").attr("style", "display: none;");

      }else{
        $("#mobile").attr("style", "display: none;");
        $("#transaction_number").attr("style", "display: none;");

        $("#bank").attr("style", "display: none;");
        $("#t_t_number").attr("style", "display: none;");
      }
    });
   </script>
  @endsection