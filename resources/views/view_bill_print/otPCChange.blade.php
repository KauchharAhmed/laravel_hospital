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
                                    CHAGE PC IN OT CLEARENCE BILL
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

{!! Form::open(['url' => 'changeOTPcAmountInfo','role' => 'form','class'=>'form-horizontal']) !!}
 <div class="row">

 <div class="col-md-6">
                                 <!-- BEGIN SAMPLE FORM PORTLET-->
                                <div class="portlet box blue">
                                    <div class="portlet-title">
                                        <div class="caption">
                                          Change To PC In OT Clearence Bill
                                       </div>
                                    </div>
                                    <div class="portlet-body form">
                                             
                                            <div class="form-body"> 
                                                  <div class="form-group">
                                                    <label class="col-md-4 control-label">Change PC<span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-8">
                                                          <select class="form-control spinner selectpicker" data-live-search="true" name="pc_id" id="pc_id" required="">
                                                        <?php
                                                       if($ot_query->pc_id == '0')
                                                       {
                                                        $pc_is = 'HOSPITAL';
                                                        $pc_id_is = '0';
                                                       }else{
                                                       $pc_info_query = DB::table('tbl_pc')->where('id',$ot_query->pc_id)->first();
                                                       $pc_is   =  $pc_info_query->name.' - '.$pc_info_query->mobile ;
                                                       $pc_id_is = $pc_info_query->id ;
                                                       }
                                                      ?>

                                                          <option value="<?php echo $pc_id_is ;?> "><?php echo $pc_is ; ?></option>
                                                          <option value="0" style="color:blue;font-weight: bold;">HOSPITAL</option>
                                                          <?php foreach ($pc as $pcs) { ?>
                                                           <option value="<?php echo $pcs->id ;?>"><?php echo $pcs->name.' - > '.$pcs->mobile ;?></option>
                                                           <?php } ?>
                                                      </select>
                                                      </div>
                                                </div>  

                                                  <div class="form-group">
                                                   <label class="col-md-4 control-label">Change Date<span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-8">
                                                        <input class="form-control form-control-inline input-medium date-picker" data-date-format="dd-mm-yyyy"  type="text" name="change_date" required="">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Change PC Amount<span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-8">
                                                        <input type="number" class="form-control spinner" name="pc_amount" id="pc_amount" required="" value="<?php echo $pc_amount;?>">
                                                    </div>
                                                </div> 
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Confirm Change PC Amount<span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-8">
                                                        <input type="number" class="form-control spinner" name="confirm_pc_amount" id="confirm_pc_amount" required="" value="<?php echo $pc_amount;?>">
                                                    </div>
                                                </div>
                                                 <div class="form-group">
                                                    <label class="col-md-4 control-label">Remarks</label>
                                                    <div class="col-md-8">
                                                        <textarea class="form-control spinner" name="remarks" cols="6" rows="6"></textarea>
                                                    </div>
                                                </div>
                                                <input type="hidden" name="invoice" value="<?php echo $invoice;?>" required="">
                                                <input type="hidden" name="year_invoice" value="<?php echo $year_invoice;?>" required="">
                                                <input type="hidden" name="daily_invoice" value="<?php echo $daily_invoice;?>" required="">
                                                <input type="hidden" name="cashbook_id" value="<?php echo $casbook_id;?>" required="">
                                                <input type="hidden" name="bill_date" value="<?php echo $ot_query->bill_date;?>" required="">
                                                <input type="hidden" name="total_bill_amt" value="<?php echo $total_payable;?>" required="">
                                                <input type="hidden" name="patient_id" value="<?php echo $ot_query->patient_id;?>" required="">
                                                <input type="hidden" name="previous_pc_id" value="<?php echo $ot_query->pc_id;?>" required="">
                                                <input type="hidden" name="previous_pc_amount" value="<?php echo $pc_amount;?>" required="">
                                                <input type="hidden" name="ot_booking_id" value="<?php echo $ot_booking_id;?>" required="">
                                               
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label"></label>
                                                    <div class="col-md-8">
                                                        <button type="submit" class="btn green">Change PC Info 
                                                        </button>
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
                                          OT Clearence Bill Info
                                       </div>
                                    </div>
                                    <div class="portlet-body form"> 
                                            <div class="form-body"> 
                                                  <div class="form-group">
                                                    <label class="col-md-4 control-label">Bill Date & Time</label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control spinner" readonly="" style="border: none;background: white;" value="<?php echo date('d M Y',strtotime($ot_query->bill_date));?> - <?php echo date('h:i:s a',strtotime($ot_query->bill_time));?>"></div>
                                                </div>  
                                                 <div class="form-group">
                                                    <label class="col-md-4 control-label"> <strong>Bill No </strong></label>
                                                    <div class="col-md-8">
                                                         <strong> <input type="text" class="form-control spinner" readonly="" style="border: none;background: white;" value="<?php echo "OTC - ".$ot_query->invoice;?>"> </strong></div>
                                                </div>

                                                 <div class="form-group">
                                                    <label class="col-md-4 control-label">Patient  Info</label>
                                                    <div class="col-md-8">
                                                        <textarea class="form-control spinner" rows="3" cols="3" style="border: none;background: white;"><?php echo $ot_query->patient_name.' - '.$ot_query->patient_number.' - '.$ot_query->patient_mobile.' - '.$ot_query->patient_address ?></textarea>
                                                    </div>
                                                </div>
                                                  <div class="form-group">
                                                    <label class="col-md-4 control-label"> Bill Amt </label>
                                                    <div class="col-md-8">
                                                    <input type="text" class="form-control spinner" readonly="" style="border: none;background: white;" value="<?php echo number_format($total_payable,2);?>"></div>
                                                </div>
                                                  <div class="form-group">
                                                    <label class="col-md-4 control-label"> Discount + Rebate </label>
                                                    <div class="col-md-8">
                                                       <input type="text" class="form-control spinner" readonly="" style="border: none;background: white;" value="<?php echo number_format($total_discount + $total_rebate,2);?>"> </div>
                                                </div>
                                                   <div class="form-group">
                                                    <label class="col-md-4 control-label"> <strong>PC</strong> </label>
                                                    <div class="col-md-8">
                                                      <?php
                                                       if($ot_query->pc_id == '0')
                                                       {
                                                        $pc_is = 'HOSPITAL';
                                                       }else{
                                                       $pc_info_query = DB::table('tbl_pc')->where('id',$ot_query->pc_id)->first();
                                                      $pc_is =  $pc_info_query->name.' - '.$pc_info_query->mobile.'-'.$pc_info_query->address ;
                                                       }
                                                      ?>
                                                    <strong><input type="text" class="form-control spinner" readonly="" style="border: none;background: white;" value="<?php echo $pc_is ;?>"></strong>
                                                    </div>
                                                </div>
                                                 <div class="form-group">
                                                    <label class="col-md-4 control-label"> <strong>PC Amount</strong> </label>
                                                    <div class="col-md-8">
                                                       <strong><input type="text" class="form-control spinner" readonly="" style="border: none;background: white;" value="<?php echo number_format($pc_amount,2);?>"></strong> </div>
                                                </div>
                                                 
                        </div>
                          <!-- END SAMPLE FORM PORTLET-->
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <!-- END DASHBOARD STATS 1-->
                    </div><!-- END PAGE CONTENT BODY --> 

                </div><!-- END PAGE CONTENT --> 

                {!! Form::close() !!}

                </div><!-- END PAGE CONTENT -->             
            </div><!-- END CONTAINER -->
@endsection
