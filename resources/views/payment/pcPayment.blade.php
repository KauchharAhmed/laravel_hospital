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
                                    PC PAYMENT
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
 <div class="col-md-10">
                                 <!-- BEGIN SAMPLE FORM PORTLET-->
                                <div class="portlet box blue">
                                    <div class="portlet-title">
                                        <div class="caption">
                                          Payment To PC
                                       </div>
                                    </div>
                                    <div class="portlet-body form">
                                             {!! Form::open(['url' => 'pcPaymentAmt','role' => 'form','class'=>'form-horizontal']) !!}
                                            <div class="form-body">
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Select PC<span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-8">
                                                             <select class="form-control spinner selectpicker" data-live-search="true" name="pc_id" id="pc_id" data="pc_id" required="">
                                                             <option value="">Select PC</option>
                                                             <?php
                                                             foreach ($pc as $pc_value) { ?>
                                                             <option value="<?php echo $pc_value->id;?>"><?php echo $pc_value->name.' -> '.$pc_value->mobile;?></option>
                                                             <?php } ?> 
                                                      </select>
                                                    </div>
                                                </div> 
                                                  <div class="form-group">
                                                    <label class="col-md-4 control-label">Due<span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-8">
                                                        <input type="number" class="form-control spinner" name="due" id="due" required="" readonly=""></div>
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
                                                   <label class="col-md-4 control-label">Date<span style="color:red; font-weight: bold">*</span></label>
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
                                     
                        {!! Form::close() !!}
                        </div>
                          <!-- END SAMPLE FORM PORTLET-->
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <!-- END DASHBOARD STATS 1-->
                    </div><!-- END PAGE CONTENT BODY -->

                    
                </div><!-- END PAGE CONTENT --> 
                </div><!-- END PAGE CONTENT -->             
            </div><!-- END CONTAINER -->
@endsection
@section('js')
<script>
    $('#pc_id').change(function(e){
       e.preventDefault();
       var pc_id         = $(this).val();
       $.ajaxSetup({
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
        });
       $.ajax({
        'url':"{{ url('/getPcDueAmount') }}",
        'type':'post',
        'dataType':'text',
        data:{  
        pc_id:pc_id
        },
         success:function(data)
         {
         $('#due').val(data);
        }
        });
       });

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