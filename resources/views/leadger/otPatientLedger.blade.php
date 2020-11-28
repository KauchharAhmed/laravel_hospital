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
                                  OT PATIENT LEDGER
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
                                        OT Info </div>
                                    </div>
                                    <div class="portlet-body form">
                                         <form role ="form" class="form-horizontal">
                                            <div class="form-body">

                                                 <div class="form-group">
                                                    <label class="col-md-4 control-label"> OT Booking No</label>

                                                    <div class="col-md-8">
                                                      <strong>
                                                        <input type="text" class="form-control spinner" readonly="" style="background: white;border:none;color:black" value="<?php echo $ot_booking_info_query->invoice; ?> " >
                                                      </strong>
                                                      </div>
                                                </div>
                                           

                                                    <div class="form-group">
                                                    <label class="col-md-4 control-label"> Booking Date / Time</label>

                                                    <div class="col-md-8">
                                                      <strong>
                                                        <input type="text" class="form-control spinner" readonly="" style="background: white;border:none;color:black" value="<?php echo date('d M Y',strtotime($ot_booking_info_query->booking_date)); ?> <?php echo date('h:i:s a',strtotime($ot_booking_info_query->created_time)); ?>" >
                                                      </strong>
                                                      </div>
                                                </div>
   
                                                  <div class="form-group">
                                                    <label class="col-md-4 control-label">OT Type</label>
                                                    <?php
                                                    $ot_type_query = DB::table('tbl_ot_type')->where('id',$ot_booking_info_query->ot_type)->limit(1)->first();

                                                    ?>
                                                    <div class="col-md-8">
                                                      <strong>
                                                        <input type="text" class="form-control spinner" readonly="" style="background: white;border:none;color:black" value="<?php echo $ot_type_query->ot_type ;  ?>">
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
                                      OT PATIENT LEDGER
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
                    foreach ($ot_ledger as $ipd_ledger_value) { 
                      $total_payable_amount   = $total_payable_amount + $ipd_ledger_value->payable_amount ;
                      $total_discount_amount  = $total_discount_amount + $ipd_ledger_value->discount ; 
                      $total_payment_amount   = $total_payment_amount + $ipd_ledger_value->payment_amount ; 
                      ?>
                     <tr>
                    <td><?php echo $i++;?></td>
                    <td><?php echo date('d M Y',strtotime($ipd_ledger_value->service_created_at));?> / <?php echo date('h:i:s a',strtotime($ipd_ledger_value->created_time));?></td>
                    <td>
                      <?php if($ipd_ledger_value->service_type == '1'){
                        echo "OT Booking";

                      }elseif($ipd_ledger_value->service_type == '2'){
                        echo "OT Staff Charge";

                      }elseif($ipd_ledger_value->service_type == '3'){
                        echo "OT Service Bill";

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
                    <th colspan="4">TOTAL</th>
                    <th><?php echo number_format($total_payable_amount,2);?></th>
                    <th><?php echo number_format($total_discount_amount,2);?></th>
                    <th><?php echo number_format($total_payment_amount,2);?></th>
                    </tr>
                  
                    </tbody>      
                    </table>
                  </div>
                  </div>
                  </div>

</div>
 

</div>
<!-- END CONTAINER -->
@endsection
