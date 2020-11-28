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
                                DUE COLLECT
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
                                         Pathology Bill Due Collect
                                      </div>
                                    </div>
                                    <div class="portlet-body form">
                                             {!! Form::open(['url' =>'pathologyDueCollectInfo','method' => 'post','role' => 'form','class'=>'form-horizontal']) !!}
                                            <div class="form-body">
                                                 <div class="form-group">
                                                    <label class="col-md-4 control-label">Select Due Bill<span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-8">
                                                        <select class="form-control spinner selectpicker" data-live-search="true" name="invoice" id="invoice" required="">
                                                          <option value="">Select Bill</option>
                                                          <?php foreach ($due_invoice as $due_invoice_value) {?>
                                                            <option value="<?php echo $due_invoice_value->invoice ?>">PAT - # <?php echo $due_invoice_value->invoice;?></option>
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
                                                    <label class="col-md-4 control-label">Total Due</label>
                                                    <div class="col-md-8">
                                                        <input type="text" name="total_due" id="total_due" class="form-control spinner" readonly="" required="">
                                                        </div>
                                                </div>
                                                   <div class="form-group">
                                                   <label class="col-md-4 control-label">Collect Date<span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-3">
                                                        <input class="form-control form-control-inline input-medium date-picker" data-date-format="dd-mm-yyyy"  type="text" name="tr_date" required="">
                                                    </div>
                                                </div>
                                                 <div class="form-group">
                                                    <label class="col-md-4 control-label"> Due Collect Amt<span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-8">
                                                        <input type="number" name="due_payment" class="form-control spinner" required="">
                                                        </div>
                                                </div>
                                                  <div class="form-group">
                                                    <label class="col-md-4 control-label">Confirm Due Collect Amt<span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-8">
                                                        <input type="number" name="confirm_due_payment" class="form-control spinner" required="">
                                                        </div>
                                                </div>
                                                   <div class="form-group">
                                                    <label class="col-md-4 control-label">Rebate<span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-8">
                                                        <input type="number" name="rebate" class="form-control spinner" required="" value="0">
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
                                                        <button type="submit" class="btn green">Pathology Due Collect</button>
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
        'url':"{{ url('/getInvoiceInfo') }}",
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
        'url':"{{ url('/getInvoiceCalculation') }}",
        'type':'post',
        'dataType':'text',
        data:{  
        invoice:invoice
        },
         success:function(data)
         {
          var arr = data.split('/');
          $('#total_amount').val(arr[0]);
          $('#total_due').val(arr[1]);
        }
        });
       });

  </script>
  @endsection
