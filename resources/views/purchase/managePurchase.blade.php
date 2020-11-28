          @extends('admin.masterAdmin')
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
                                     PURCHASE
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
                                            <span class="caption-subject bold uppercase">PURCHASE BILL List</span>
                                        </div>
                                        <div class="tools"> </div>
                                    </div>
                                    <div class="portlet-body">
                                        <table class="table table-striped table-hover table-bordered" id="sample_editable_1">
                                            <thead>
                                                <tr>
                                                    <th>Sl No</th>
                                                    <th>Created</th>
                                                    <th>P.Date</th>
                                                    <th>Invoice</th>
                                                    <th>Memo</th>
                                                    <th>Branch</th>
                                                    <th>Supplier</th>
                                                    <th>Total Quantity</th>
                                                    <th>Total Price</th>
                                                    <th>Total Payment</th>
                                                    <th>Details</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $i = 1 ;
                                                foreach ($result as $value) { ?>
                                                <tr>
                                                    <td><?php echo $i++ ;?></td>
                                                    <td><?php echo $value->created_at ; ?></td>
                                                    <td><?php echo $value->purchase_date ; ?></td>
                                                    <td><?php echo $value->invoice ; ?></td>
                                                    <td><?php echo $value->memo_no ; ?></td>
                                                    <td><?php echo $value->name ; ?></td>
                                                    <td><?php echo $value->supplier_name ; ?></td>
                                                    <td><?php echo $value->total_quantity ; ?></td>
                                                    <td><?php echo $value->total_price ; ?></td>
                                                     <td><?php echo $value->total_payment ; ?></td>
                                                      <td>
                                                         <a href="{{URL::to('printPurchaseBill/'.$value->invoice)}}">
                                                        <button type="button" class="btn btn-info btn-sm">DETAILS</button>
                                                    </a>
                                                    </td>
                                                       <td>
                                                        <a href="{{URL::to('deletePurchase/'.$value->invoice.'/'.$value->branch_id)}}"><button type="button" class="btn btn-danger btn-sm" onclick="return confirm('Are You Sure You Want To Delete It ?')">DELETE</button></a>
                                                    </td>
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