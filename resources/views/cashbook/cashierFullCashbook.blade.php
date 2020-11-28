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
                                    CASHBOOK
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
                                            <span class="caption-subject bold uppercase">CASHBOOK</span>
                                        </div>
                                        <div class="tools"> </div>
                                    </div>
                              

                    <div class="row">
                    <div class="col-md-12">
                        <div class="block-web">
                            <div class="header">
                                <h3 class="content-header"> Full Cashbook </h3>    
                            </div>
                            <div class="porlets-content">
                                <table class="nila" width="100%" border="1">
                                        <thead>
                                            <tr role="row" class="nila">
                                                <td class="nila" style="padding: 7px"><strong>Sl.</strong></td>
                                                <td class="nila" style="padding: 7px"><strong>Date</td>
                                                <td class="nila" style="padding: 7px"><strong>Time</td>
                                                <td class="nila" style="padding: 7px"><strong>Purpose</td>
                                                <td class="nila" style="padding: 7px"><strong>Cash In</td>
                                                <td class="nila" style="padding: 7px"><strong>Cash Out</td>
                                                <td class="nila" style="padding: 7px"><strong>C2B</td>
                                                <td class="nila" style="padding: 7px"><strong>B2C</td>
                                                <td class="nila" style="padding: 7px"><strong>Send Cash</td>
                                                <td class="nila" style="padding: 7px"><strong>Receive Cash</td>
                                                <td class="nila" style="padding: 7px"><strong>Clossing Balance</td>
                                            </tr>
                                        </thead>
                                        <tbody role="alert" aria-live="polite" aria-relevant="all">
                                            <?php
                                            $i = 1 ; 
                                            foreach ($result as $key=>$value) { ?>
                                            <tr class="nila">
                                                <td class="nila" style="padding: 5px"><?php echo  $i++ ; ?></td>
                                                <td class="nila" style="padding: 5px"><?php echo date('d M Y',strtotime($value->created_at)) ;?></td>
                                         
                                                   <td class="nila" style="padding: 5px"><?php  echo date('h:i:s a',strtotime($value->created_time,2)) ;?></td>
                                                <td class="nila" style="padding: 5px"><?php echo $value->purpose; ?></td>                                              
                                                <td class="nila" style="padding: 5px"><?php echo $value->earn; ?></td>
                                                <td class="nila" style="padding: 5px"><?php 
                                                echo $value->cost;?></td>
                                                <td class="nila" style="padding: 5px"><?php echo $value->c2b ;?></td>
                                                <td class="nila" style="padding: 5px">
                                                    <?php
                                                    echo $value->b2c ; 
                                                    ?>
                                                    <td class="nila" style="padding: 5px"><?php  if($value->balance_transfer_type == '1'){echo $value->balance_transfer;}?></td>

                                                      <td class="nila" style="padding: 5px"><?php if($value->balance_transfer_type == '2'){echo $value->balance_transfer;}?></td>
                                                </td>
                                                <td class="nila" style="padding: 5px">
                                               <?php 
                                               $previous_colum_balance = DB::table('cashbook')->where('branch_id', $branch_id)->where('admin_type',3)->whereNotIn('tr_status',[2])->where('created_at','<=',$value->created_at)->orderby('created_at','asc')->limit($key)->get();

                                                      $previous_earn_amount = 0 ;
                                                      $previous_cost_amount = 0 ;
                                                      $previous_c2b         = 0 ;
                                                      $previous_b2c         = 0 ;
                                                      $balance_transfer     = 0 ;
                                                      $previous_send_transfer = 0 ;
                                                      $previous_receive_transfer = 0 ;
                                                      $previous_non_cash = 0 ;
                                                      $previous_m2c      = 0 ;

                                                     foreach ($previous_colum_balance as $previous_colum_balances) {
                                                       $previous_earn_amount = $previous_earn_amount + $previous_colum_balances->earn ;

                                                       $previous_cost_amount = $previous_cost_amount + $previous_colum_balances->cost ;
                                                        $previous_non_cash = $previous_non_cash + $previous_colum_balances->get_non_cash_payment ;

                                                       $previous_c2b      = $previous_c2b + $previous_colum_balances->c2b ;

                                                       $previous_b2c      = $previous_b2c + $previous_colum_balances->b2c ;
                                                       $previous_m2c      = $previous_m2c + $previous_colum_balances->m2c ;

                                                       if($previous_colum_balances->balance_transfer_type == '1' OR $previous_colum_balances->balance_transfer_type == '0'){
                                                        $previous_send_transfer = $previous_send_transfer + $previous_colum_balances->balance_transfer ;
                                                       }else if($previous_colum_balances->balance_transfer_type == '2' OR $previous_colum_balances->balance_transfer_type == '0'){
                                                        $previous_receive_transfer = $previous_receive_transfer + $previous_colum_balances->balance_transfer ;
                                                       }

                                                     }
                                                     $previous_balance = $previous_earn_amount - $previous_cost_amount - $previous_c2b + $previous_b2c - $previous_send_transfer +  $previous_receive_transfer - $previous_non_cash + $previous_m2c ;


                                                    if($value->balance_transfer_type == '1'){
                                                        $send_balance_transfer = $value->balance_transfer ;
                                                    }else{
                                                      $send_balance_transfer = 0 ;
                                                    }

                                                     if($value->balance_transfer_type == '2'){
                                                        $receive_balance_transfer = $value->balance_transfer ;
                                                    }else{
                                                      $receive_balance_transfer = 0 ;
                                                    }

                                                    $current_balance = $value->earn - $value->cost - $value->c2b + $value->b2c - $send_balance_transfer + $receive_balance_transfer ;
                                                     $amount = $previous_balance + $current_balance - $value->get_non_cash_payment + $value->m2c ;
                                                     echo number_format($amount,2) ;
                                                ?> 
                                                </td>
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