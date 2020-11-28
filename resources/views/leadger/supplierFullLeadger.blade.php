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
                                     LEDGER
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
                                            <span class="caption-subject bold uppercase">Supplier Ledger</span>
                                        </div>
                                        <div class="tools"> </div>
                                    </div>
                                    <div class="row">
                    <div class="col-md-12">
                        <div class="block-web">
                            <div class="porlets-content">
                                <table style="font-size:14px;">
                                    <tr>
                                        <td><strong>Supplier</strong></td>
                                        <td>:&nbsp;&nbsp;</td>
                                        <td><?php echo $supplier->supplier_name ; ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Address</strong></td>
                                        <td>:&nbsp;&nbsp;</td>
                                        <td><?php echo $supplier->address ; ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Mobile</strong></td>
                                        <td>:&nbsp;&nbsp;</td>
                                        <td><?php echo $supplier->mobile ; ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>E-mail</strong></td>
                                        <td>:&nbsp;&nbsp;</td>
                                        <td><?php echo $supplier->email ; ?></td>
                                    </tr>
                                </table>
                            
                            </div><!--/porlets-content-->
                        </div><!--/block-web--> 
                    </div><!--/col-md-9-->
                </div>  
                <?php foreach ($result as $value1) {
               
                }
                ?>
                    <div class="row">
                    <div class="col-md-12">
                        <div class="block-web">
                            <div class="header">
                                <h3 class="content-header">Ledger details <a href="{{URL::to('printSupllierLedger/'.$value1->supplier_id)}}" style="float:right;margin-right:6px;" class="btn btn-success">Print</a></h3>    
                            </div>
                            <div class="porlets-content">
                                <form action="post_url/storeadd" id="storeadd" method="post" class="form-horizontal row-border" enctype="multipart/form-data">

                                    <table class="nila" width="100%">
                                        <thead>
                                            <tr role="row" class="nila">
                                                <td class="nila">SL.</td>
                                                <td class="nila">Date</td>
                                                <td class="nila">Invoice</td>
                                                <td class="nila">Memo No</td>
                                                <td class="nila">Purpose</td>
                                                <td class="nila">Quantity</td>
                                                <td class="nila">Total Price</td>
                                                <td class="nila">Paid</td>
                                                <td class="nila">Total Due</td>
                                            </tr>
                                        </thead>
                                        <tbody role="alert" aria-live="polite" aria-relevant="all">
                                            <?php
                                            $i = 1 ; 
                                            foreach ($result as $key=>$value) { ?>
                                            <tr class="nila">
                                                <td class="nila"><?php echo  $i++ ; ?></td>
                                                <td class="nila"><?php echo date('d M Y',strtotime($value->created_at)); ?></td>
                                                <td class="nila"><?php echo $value->invoice;?></td>
                                                <td class="nila"><?php echo $value->money_receipt;?></td>
                                                <td class="nila"><?php echo $value->purpose?></td>    
                                                <td class="nila">
                                                <?php if($value->invoice > 0){
                                                  $quantity_query = DB::table('purchase')->where('branch_id',$branch_id)->where('invoice',$value->invoice)->first();
                                                  echo $quantity_query->total_quantity ;
                                                } 
                                                ?>
                                                 </td>                              
                                                <td class="nila"><?php 
                                                if($value->status != '0'){
                                                echo $value->payable_amount;
                                            }?>
                                                    
                                                </td>
                                                <td class="nila"><?php echo $value->payment_amount?></td>
                                                <td class="nila"><?php 
                                                   $previous_colum_duee = DB::table('payment_ledger')->where('branch_id',$branch_id)->where('supplier_id',$value->supplier_id)->where('created_at','<=',$value->created_at)->orderby('created_at','asc')->limit($key)->get();

                                                      $previous_payable_amount = 0 ;
                                                      $previous_paid_amount    = 0 ;
                                                
                                                     foreach ($previous_colum_duee as $previous_colum_due) {
                                                       $previous_payable_amount = $previous_payable_amount + $previous_colum_due->payable_amount ;
                                                       $previous_paid_amount = $previous_paid_amount + $previous_colum_due->payment_amount ;
                                                      
                                                     }
                                                     $previous_due_amount = $previous_payable_amount - $previous_paid_amount ;
                                                    $current_due = $value->payable_amount - $value->payment_amount ;
                                                     $due_amount = $previous_due_amount + $current_due ;
                                                     echo number_format($due_amount,2) ;

                                                ?></td>
                                               
                                            </tr>
                                            <?php } ?>
                                                                   
                                        </tbody>
                                    </table>
                                    <br/>                                    
                                </form>
                            </div><!--/porlets-content-->
                        </div><!--/block-web--> 
                    </div><!--/col-md-9-->
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