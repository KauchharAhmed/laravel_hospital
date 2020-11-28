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
                                   SALARY
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
                                         Monthly Salary Payment
                                          </div>
                                    </div>
                                    <div class="portlet-body form">
                                        	 {!! Form::open(['url' =>'paymentSalary','role' => 'form','class'=>'form-horizontal']) !!}
                                            <div class="form-body">
                                                  <div class="form-group">
                                                    <label class="col-md-4 control-label">Select Staff <span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-8">
                                                      <select name="staff" id="staff" class="form-control spinner selectpicker" data-live-search="true" required="" >
                                                        <option value="">Select Staff</option>
                                                        <?php foreach ($result as $value) { ?>
                                                           <option value="<?php echo $value->id ;?>"><?php echo $value->name ;?></option>
                                                        <?php } ?>
                                                      </select>
                                                    </div>
                                                </div>
                                                  <div class="form-group">
                                                    <label class="col-md-4 control-label">Year <span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-8">
                                                      <select name="year" id="year" class="form-control spinner" required="" >
                                                        <option value="">Select Year</option>
                                                         <option value="<?php echo date('Y')?>"><?php echo date('Y'); ?></option>
                                                        <?php foreach ($year as $years) { ?>
                                                           <option value="<?php echo $years->year ;?>"><?php echo $years->year ;?></option>
                                                        <?php } ?>
                                                      </select>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Month <span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-8">
                                                      <select name="month" id="month" class="form-control spinner" required="" >
                                                        <option value="">Select Month</option>
                                                           <option value="01">January</option>
                                                             <option value="02">February</option>
                                                               <option value="03">March</option>
                                                                 <option value="04">April</option>
                                                                   <option value="05">May</option>
                                                                     <option value="06">June</option>
                                                                       <option value="07">July</option>
                                                                         <option value="08">August</option>
                                                                           <option value="09">September</option>
                                                                             <option value="10">October</option>
                                                                               <option value="11">November</option>
                                                                                 <option value="12">December</option>
                                                    
                                                      </select>
                                                    </div>
                                                </div>

                                                 <div class="form-group">
                                                    <label class="col-md-4 control-label">Salary Type <span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-8">
                                                      <select class="form-control spinner" name="salary_type" id="salary_type" required="">
                                                      <option value="">Select Salary Type</option>
                                                      <option value="1">Regular</option>
                                                      <option value="2">Bonus</option>
                                                      </select>
                                                    </div>
                                                </div>
                                                 <div class="form-group">
                                                    <label class="col-md-4 control-label">Total Payable </label>
                                                    <div class="col-md-8">
                                                      <input type="number" name="total_payable" id="total_payble" class="form-control spinner" readonly="">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Total Payment</label>
                                                    <div class="col-md-8">
                                                      <input type="number" name="total_payment" id="total_payment" class="form-control spinner" readonly="">
                                                    </div>
                                                </div>
                                                 <div class="form-group">
                                                    <label class="col-md-4 control-label">Total Due</label>
                                                    <div class="col-md-8">
                                                      <input type="number" name="total_due" id="total_due" class="form-control spinner" readonly="">
                                                    </div>
                                                </div>
                                                   <div class="form-group">
                                                    <label class="col-md-4 control-label">Payment <span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-8">
                                                      <input type="number" name="payment_amount" class="form-control spinner" required="" >
                                                    </div>
                                                </div>

                                                 <div class="form-group">
                                                    <label class="col-md-4 control-label">Confirm Payment <span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-8">
                                                      <input type="number" name="confirm_payment_amount" class="form-control spinner" required="" >
                                                    </div>
                                                </div>
                                                 <div class="form-group">
                                                    <label class="col-md-4 control-label">Fine Amount</label>
                                                    <div class="col-md-8">
                                                      <input type="number" name="fine_amount" class="form-control spinner" value="0">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                   <label class="col-md-4 control-label">Payment Date<span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-3">
                                                        <input class="form-control form-control-inline input-medium date-picker" data-date-format="dd-mm-yyyy"  type="text" name="tr_date" required="">
                                                    </div>
                                                </div>
  
                                                    <div class="form-group">
                                                    <label class="col-md-4 control-label">Remarks</label>
                                                    <div class="col-md-8">
                                                      <textarea name="remarks" class="form-control spinner"></textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                  <label class="col-md-4 control-label"></label>
                                                   <div class="col-md-8">
                                                  <button class="btn btn-success">Submit</button>
                            
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
              </div>
            </div>
@endsection
@section('js')
<script>
$('#salary_type').change(function(e){
       e.preventDefault();
       var type         = $(this).val();
       var staff        = $('#staff').val();
       var year         = $('#year').val();
       var month        = $('#month').val();
       if(type != '1')
       {
          $('#total_payble').val('');
          $('#total_payment').val('');
          $('#total_due').val('');
          return false ;
       }
       if(staff == ''){
        $('#salary_type').val('');
        alert('Please Select The Staff');
        return false;
       }
       if(year == ''){
        $('#salary_type').val('');
        alert('Please Select The Year');
        return false;
       }
       if(month == ''){
        $('#salary_type').val('');
        alert('Please Select The Month');
        return false;
       }
       $.ajaxSetup({
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
        });
       $.ajax({
        'url':"{{ url('/getSalaryCalculation') }}",
        'type':'post',
        'dataType':'text',
        data:{  
        staff:staff,
        year:year,
        month:month
        },
         success:function(data)
         {
          var array = data.split('/');
          var total_payable = array[0];
          var total_paid    = array[1];
          var total_due     = array[2];
          $('#total_payble').val(total_payable);
          $('#total_payment').val(total_paid);
          $('#total_due').val(total_due);
        }
        });
       });
</script>
@endsection
