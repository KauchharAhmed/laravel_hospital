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
                                DOCTOR DISCOUNT IN OPD BILL
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
                                         Doctor Discount In OPD Bill
                                      </div>
                                    </div>
                                    <div class="portlet-body form">
                                             {!! Form::open(['url' =>'opdDoctorDiscountInfo','method' => 'post','role' => 'form','class'=>'form-horizontal']) !!}
                                            <div class="form-body">
                                                 <div class="form-group">
                                                    <label class="col-md-4 control-label">Select Bill<span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-8">
                                                        <select class="form-control spinner selectpicker" data-live-search="true" name="invoice" id="invoice" required="">
                                                          <option value="">Select Bill</option>
                                                          <?php foreach ($paid_invoice as $paid_invoice_value) {?>
                                                            <option value="<?php echo $paid_invoice_value->invoice ?>">OPD - # <?php echo $paid_invoice_value->invoice;?></option>
                                                          <?php } ?>
                                                        </select>
                                                </div>
                                              </div>
                                                    <div class="form-group">
                                                    <label class="col-md-4 control-label">Patient Info</label>
                                                    <div class="col-md-8">
                                                        <textarea type="text" class="form-control spinner" name="patient_info" id="patient_info" readonly="" style="color:green;" required=""></textarea>
                                                        </div>
                                                </div>
                                                 <div class="form-group">
                                                    <label class="col-md-4 control-label">Total Bill Amt</label>
                                                    <div class="col-md-8">
                                                        <input type="text" name="total_amount" id="total_amount" class="form-control spinner" readonly="" required="">
                                                        </div>
                                                </div>
                                                 <div class="form-group">
                                                    <label class="col-md-4 control-label">Discount + Rebate</label>
                                                    <div class="col-md-8">
                                                        <input type="text" name="previous_discount" id="previous_discount" class="form-control spinner" readonly="" required="">
                                                        </div>
                                                </div>
                                                   <div class="form-group">
                                                    <label class="col-md-4 control-label">Total Payment</label>
                                                    <div class="col-md-8">
                                                        <input type="text" name="total_payment" id="total_payment" class="form-control spinner" readonly="" required="">
                                                        </div>
                                                </div>
                                                   <div class="form-group">
                                                   <label class="col-md-4 control-label">Discount Date<span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-3">
                                                        <input class="form-control form-control-inline input-medium date-picker" data-date-format="dd-mm-yyyy"  type="text" name="tr_date" required="">
                                                    </div>
                                                </div>
                                                 <div class="form-group">
                                                    <label class="col-md-4 control-label">Doctor Discount Amt<span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-8">
                                                        <input type="number" name="doctor_discount" class="form-control spinner" required="">
                                                        </div>
                                                </div>
                                                  <div class="form-group">
                                                    <label class="col-md-4 control-label">Confirm Doctor Discount Amt<span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-8">
                                                        <input type="number" name="confirm_doctor_discount" class="form-control spinner" required="">
                                                        </div>
                                                </div>
                                                 
                                                  <div class="form-group">
                                                    <label class="col-md-4 control-label">Remarks</label>
                                                    <div class="col-md-8">
                                                        <textarea type="text" class="form-control spinner" name="remarks"></textarea>
                                                        </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label"></label>
                                                    <div class="col-md-8">
                                                    <button type="submit" class="btn green"> OPD Doctor Discount</button>
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
    $('#invoice').change(function(e){
       e.preventDefault();
       var invoice         = $(this).val();
       $.ajaxSetup({
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
        });
       $.ajax({
        'url':"{{ url('/getOPDInvoiceInfo') }}",
        'type':'post',
        'dataType':'text',
        data:{  
        invoice:invoice
        },
         success:function(data)
         {
          $('#patient_info').text(data);
        }
        });
       });

      $("[name=invoice]").change(function(e){
       e.preventDefault();
       var invoice         = $(this).val();
       $.ajaxSetup({
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
        });
       $.ajax({
        'url':"{{ url('/getInvoiceCalculationForOPDDiscount') }}",
        'type':'post',
        'dataType':'text',
        data:{  
        invoice:invoice
        },
         success:function(data)
         {
          var arr = data.split('/');
          $('#total_amount').val(arr[0]);
          $('#previous_discount').val(arr[1]);
          $('#total_payment').val(arr[2]);
          
        }
        });
       });

  </script>
  @endsection
