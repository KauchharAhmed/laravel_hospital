  <div class="row">
 
                            <div class="col-md-12">
                                 
                                <!-- BEGIN EXAMPLE TABLE PORTLET-->
                                <div class="portlet light bordered">
                                    <div class="portlet-title">
                                        <div class="caption font-dark">
                                            <span class="caption-subject bold uppercase">
                                             <?php foreach ($result as $value1) {
                                                
                                             }
                                             ?>
                                           CASHBOOK BETWEEN FROM <?php echo date ('d M Y',strtotime($from)) ;?> TO <?php echo date ('d M  Y',strtotime($to)) ; ?> 
                                            </span>
                                        </div>
                                       <a href="" style="float:right;margin-right:6px;" class="btn btn-success">Print</a>   
                                    </div>
                                    <div class="portlet-body">
                                         <div class="header">
                                
                                     </div>
                                        <div class="table-responsive">
                                        <table class="nila" width="100%" border="1">
                                        <thead>
                                            <tr role="row" class="nila">
                                                <td class="nila" style="padding: 7px"><strong>Sl.</strong></td>
                                                <td class="nila" style="padding: 7px"><strong>Date</strong></td>
                                                <td class="nila" style="padding: 7px"><strong>Time</strong></td>
                                                <td class="nila" style="padding: 7px"><strong>Purpose</strong></td>
                                                <td class="nila" style="padding: 7px"><strong>Cash In</strong></td>
                                                <td class="nila" style="padding: 7px"><strong>Cash Out</strong></td>
                                                <td class="nila" style="padding: 7px"><strong>C2B</strong></td>
                                                <td class="nila" style="padding: 7px"><strong>B2C</strong></td>
                                                <td class="nila" style="padding: 7px"><strong>Send Cash</strong></td>
                                                <td class="nila" style="padding: 7px"><strong>Receive Cash</strong></td>
                                                <td class="nila" style="padding: 7px"><strong>Clossing Balance</strong></td>
                                            </tr>
                                        </thead>
                                        <tbody role="alert" aria-live="polite" aria-relevant="all">

                                           <tr class="nila">
                                                <td class="nila" style="padding: 5px"><?php echo  1; ?></td>
                                                <td class="nila" style="padding: 5px"><?php echo date('d M Y',strtotime($from)) ;?></td>
                                                <td class="nila" style="padding: 5px"></td>
                                                <td class="nila" style="padding: 5px">From Date Opening Balance</td>                                              
                                                <td class="nila" style="padding: 5px"><?php echo number_format($previous_balance,2) ;?></td>
                                                <td class="nila" style="padding: 5px"></td>
                                                <td class="nila" style="padding: 5px"></td>
                                                <td class="nila" style="padding: 5px"></td>
                                                <td class="nila" style="padding: 5px"></td>   
                                                <td class="nila" style="padding: 5px"> </td>
                                                <td class="nila" style="padding: 5px"><?php echo number_format($previous_balance,2) ;?></td>
                                            <?php
                                            $i = 2 ; 
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
                                                  </td>
                                                     <td class="nila" style="padding: 5px"><?php  if($value->balance_transfer_type == '1'){echo $value->balance_transfer;}?></td>

                                                      <td class="nila" style="padding: 5px"><?php if($value->balance_transfer_type == '2'){echo $value->balance_transfer;}?></td>
                                                </td>
                                                <td class="nila" style="padding: 5px">

                                            <?php 
                                              $previous_colum_balance = DB::table('cashbook')->where('branch_id',$branch_id)->where('admin_type',2)->whereNotIn('tr_status',[2])->where('created_at','<=',$value->created_at)->whereBetween('created_at', [$next_form, $to])->orderby('created_at','asc')->limit($key)->get();

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
                                                     $previous_balancee = $previous_earn_amount - $previous_cost_amount - $previous_c2b + $previous_b2c - $previous_send_transfer +  $previous_receive_transfer - $previous_non_cash + $previous_m2c ;


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
                                                     $current_balance = $value->earn - $value->cost - $value->c2b + $value->b2c - $send_balance_transfer + $receive_balance_transfer - $value->get_non_cash_payment + $value->m2c;
                                                     $amount = $previous_balancee + $current_balance ;
                                                     echo number_format($amount + $previous_balance,2) ;
                                                ?>  
                                                </td>
                                            </tr>
                                            <?php } ?>
                                                                   
                                        </tbody>
                                    </table>
                                    </div>
                                    </div>
                                </div>
                                <!-- END EXAMPLE TABLE PORTLET-->
                            </div>
                       
                        <!-- END DASHBOARD STATS 1-->
                    </div><!-- END PAGE CONTENT BODY -->
             