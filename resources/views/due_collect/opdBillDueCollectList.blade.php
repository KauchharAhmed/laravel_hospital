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
                                   OPD BILL DUE COLLECT LIST
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
 
                            <div class="col-md-12">
                                <!-- BEGIN EXAMPLE TABLE PORTLET-->
                                <div class="portlet light bordered">
                                    <div class="portlet-title">
                                        <div class="caption font-dark">
                                            <i class="icon-settings font-dark"></i>
                                            <span class="caption-subject bold uppercase"> OPD Bill Due Collect List</span>
                                        </div>
                                        <div class="tools"> </div>
                                    </div>
                                    <div class="portlet-body">
                                        <table class="table table-striped table-hover table-bordered" id="sample_editable_1">
                                            <thead>
                                                <tr>
                                                    <th>Sl</th>
                                                    <th>Added</th>
                                                    <th>Bill Date</th>
                                                    <th>Tr. Date</th>
                                                    <th>Time</th>
                                                    <th>Bill No</th>
                                                    <th>Tr.No</th>
                                                    <th>Ref. Dr</th>
                                                    <th>P.No</th>
                                                    <th>P.Name</th>
                                                    <th>P.Mobile</th>
                                                    <th>P.Address</th>
                                                    <th>Total Collect Amt</th>
                                                    <th>Total Rebate</th>
                                                    <!--<th>Edit</th>
                                                    <th>Delete</th>-->
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $i = 1 ;
                                                foreach ($result as $value) { ?>
                                                <tr>
                                                    <td><?php echo $i++ ;?></td>
                                                    <td><?php 
                                                    $admin_name = DB::table('admin')->where('id',$value->added_id)->first();
                                                    echo $admin_name->name ; ?>
                                                    </td>
                                                    <?php
                                                    $invoice_info_query = DB::table('opd_bill')->where('branch_id',$value->branch_id)->where('invoice',$value->invoice_number)->first();

                                                    ?>
                                                    <td><?php echo date('d-M-Y',strtotime($invoice_info_query->bill_date));   ?></td>
                                                    <td><?php echo date('d-M-Y',strtotime($value->tr_date)); ?></td>
                                                    <td><?php echo date('h:i:s a',strtotime($value->created_time)); ?></td>
                                                    <td><?php echo $value->invoice_number ;  ?></td>
                                                    <td><?php echo $value->invoice_tr_id ;  ?></td>
                                                    <td><?php
                                                    $doctor_query = DB::table('admin')->where('id',$invoice_info_query->doctor_id)->first(); 
                                                    echo $doctor_query->name ;  ?></td>
                                                    <?php
                                                    $patient_query = DB::table('tbl_patient')->where('branch_id',$value->branch_id)->where('id',$invoice_info_query->patient_id)->first();
                                                    ?>
                                                    <td><?php echo $patient_query->patient_number ; ?></td>
                                                    <td><?php echo $patient_query->patient_name ; ?></td>
                                                    <td><?php echo $patient_query->patient_mobile ; ?></td>
                                                    <td><?php echo $patient_query->address ; ?></td>
                                                     <td><?php echo $value->total_payment;?></td>
                                                    <td><?php echo $value->total_rebate;?></td>
                                                    </td>
                                                    <!--<td>
                                                        <button type="button" class="btn btn-info btn-sm">EDIT</button>
                                                    </td>
                                                       <td>
                                                        <button type="button" class="btn btn-danger btn-sm">DELETE</button>
                                                    </td>-->
                                                </tr>
                                                <?php } ?>
                                            
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!-- END EXAMPLE TABLE PORTLET-->
                            </div>
                        <div class="clearfix"></div>
                        <!-- END DASHBOARD STATS 1-->
                    </div><!-- END PAGE CONTENT BODY -->
                </div><!-- END PAGE CONTENT -->             
            </div><!-- END CONTAINER -->
@endsection