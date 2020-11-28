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
                                   PATHOLOGY BILL LIST
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
                                            <span class="caption-subject bold uppercase"> Pathology Bill List</span>
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
                                                    <th>Time</th>
                                                    <th>Invoice</th>
                                                    <th>Ref. Dr</th>
                                                    <th>P.No</th>
                                                    <th>P.Name</th>
                                                    <th>P.Mobile</th>
                                                    <th>P.Address</th>
                                                    <th>Status</th>
                                                    <th>Total Payable</th>
                                                    <th>Total Discount</th>
                                                    <th>Total Rebate</th>
                                                    <th>Total Payment</th>
                                                    <th>Due</th>
                                                    <th>Print Invoice</th>
                                                    <!--<th>Edit</th>
                                                    <th>Delete</th>-->
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $i = 1 ;
                                                foreach ($result as $value) { ?>
                                                <tr style="<?php if($value->due_status =='2'){
                                                echo "background: #e8bcac";
                                                }else{
                                                }
                                                ?>
                                                ">
                                                    <td><?php echo $i++ ;?></td>
                                                    <td><?php echo $value->admin_name ; ?></td>

                                                    <td><?php echo date('d-M-Y',strtotime($value->bill_date));   ?></td>
                                                    <td><?php echo date('h:i:s a',strtotime($value->bill_time));   ?></td>
                                                    <td><?php echo $value->invoice ;  ?></td>
                                                    <td><?php
                                                    $doctor_query = DB::table('admin')->where('id',$value->doctor_id)->first(); 
                                                    echo $doctor_query->name ;  ?></td>
                                                    <td><?php echo $value->patient_number ; ?></td>
                                                    <td><?php echo $value->patient_name ; ?></td>
                                                    <td><?php echo $value->patient_mobile ; ?></td>
                                                    <td><?php echo $value->patient_address ; ?></td>
                                                     <td><?php if($value->due_status == '1'):?>
                                                         <span style="color: green;font-weight: bold">PAID</span>
                                                     <?php else:?>
                                                    <span style="color: red;font-weight: bold">DUE</span>
                                                <?php endif;?>

                                                     </td>
                                                   <?php
                                                   // payable amount info
                                                    $bill_tr_info = DB::table('pathology_bill_transaction')->where('branch_id',$branch_id)->where('invoice_number',$value->invoice)->get();
                                                   $total_payable  = 0 ;
                                                   $total_payment  = 0 ;
                                                   $total_discount = 0 ;
                                                   $total_rebate   = 0 ;
                                                    foreach ($bill_tr_info as $bill_tr_info_value) {
                                                        $total_payable  = $total_payable + $bill_tr_info_value->total_payable ;
                                                        $total_payment  = $total_payment + $bill_tr_info_value->total_payment ;
                                                        $total_discount = $total_discount + $bill_tr_info_value->total_discount ;
                                                        $total_rebate   = $total_rebate + $bill_tr_info_value->total_rebate ;   
                                                    }
                                                   ?> 
                                                      <td><?php echo number_format($total_payable,2); ?></td>
                                                      <td><?php echo number_format($total_discount,2); ?></td>
                                                       <td><?php echo number_format($total_rebate,2); ?></td>
                                                        <td><?php echo number_format($total_payment,2); ?></td>
                                                          <td><?php 
                                                          $net_payable_is_without_offer = $total_discount + $total_rebate ;
                                                          $net_payable_is = $total_payable - $net_payable_is_without_offer ;
                                                          $due_is = $net_payable_is - $total_payment ;
                                                          echo number_format($due_is,2);

                                                         ?></td>
                                                         <td>
                                                    <a target="_blank" href="{{URL::to('printPathologyBill/'.$value->invoice.'/'.$value->cashbook_id)}}"><button type="button" class="btn btn-info btn-sm">PRINT INVOICE</button></a>
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