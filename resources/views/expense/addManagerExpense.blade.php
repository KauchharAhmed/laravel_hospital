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
                                   EXPENSE 
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
                                          Add Expense
                                      </div>
                                    </div>
                                    <div class="portlet-body form">
                                        	 {!! Form::open(['url' =>'addManagerExpenseInfo','method' => 'post','role' => 'form','class'=>'form-horizontal']) !!}
                                            <div class="form-body">
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Expense Category Name <span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-8">
                                                      <select name="expense_category"  class="form-control spinner selectpicker" data-live-search="true" required="">
                                                        <option value="">Select Expense Category</option>
                                                      <?php foreach ($result as $value) { ?>
                                                        <option value="<?php echo $value->id;?>"><?php echo $value->expense_name ; ?></option>
                                                        <?php } ?>
                                                      </select>
                                                      </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Amount<span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-8">
                                                        <input type="number" class="form-control spinner" name="amount" placeholder="Amount" required="">
                                                      </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Confirm Amount<span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-8">
                                                        <input type="number" class="form-control spinner" name="confirm_amount" placeholder="Confirm Amount" required="">
                                                      </div>
                                                </div>
                                                <div class="form-group">
                                                   <label class="col-md-4 control-label">Expense Date<span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-3">
                                                        <input class="form-control form-control-inline input-medium date-picker" data-date-format="dd-mm-yyyy"  type="text"  name="tr_date" required="">
                                                    </div>
                                                </div>

                                                  <div class="form-group">
                                                    <label class="col-md-4 control-label">Service Provider (if it)</label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control spinner" name="provider" placeholder="Service Provider">
                                                      </div>
                                                </div>
                                                 <div class="form-group">
                                                    <label class="col-md-4 control-label">Service Provider Memo (if it)</label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control spinner" name="provider_memo" placeholder="Service Provider Memo Number"></div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Remarks (if it)</label>
                                                    <div class="col-md-8">
                                                        <textarea type="text" class="form-control spinner" name="remarks" placeholder="Remarks"></textarea>
                                                        </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label"></label>
                                                    <div class="col-md-8">
                                                        <button type="submit" class="btn green">Submit</button>
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