  <div class="row">
 
                            <div class="col-md-12">
                                 
                                <!-- BEGIN EXAMPLE TABLE PORTLET-->
                                <div class="portlet light bordered">
                                    <div class="portlet-title">
                                        <div class="caption font-dark">
                                            <span class="caption-subject bold uppercase">
                                           INCOME STATEMENT REPORT BETWEEN FROM <?php echo date('d M Y',strtotime($from_date)) ;?> TO <?php echo date('d M Y',strtotime($to_date)) ; ?>
                                            </span>
                                        </div>
                                         {!! Form::open(['url' =>'printManagerIncomeStatement','method' => 'post','role' => 'form','class'=>'form-horizontal','target'=>'_blank']) !!}
                                         <input type="hidden" name="from_date" value="<?php echo $from_date;?>">
                                         <input type="hidden" name="to_date" value="<?php echo $to_date;?>">
                                       <button type="submit" style="float:right;margin-right:6px;" class="btn btn-success">Print</button> 
                                      {!! Form::close() !!}  
                                    </div>
                                    <div class="portlet-body">
                                         <div class="header">
                                
                            </div>
                                        <div class="table-responsive">
                                        <table class="table table-bordered">
                                             <thead>
                                            <tr>
                                                <th>Sl No</th>
                                                <th>Date</th>
                                                <th>Purpose</th>
                                                <th>Earn</th>
                                                <th>Cost</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $i = 1 ; 
                                            $total_earn = 0 ;
                                            $total_cost = 0 ;
                                            foreach ($result as $value) { 
                                              $total_earn = $total_earn + $value->profit_earn ;
                                              $total_cost = $total_cost + $value->profit_cost ;
                                              ?>
                                              <?php if($value->profit_earn == 0 AND $value->profit_cost == 0):?>
                                              <?php else:?>
                                            <tr>
                                                <td><?php echo  $i++ ; ?></td>
                                                <td><?php echo date('d-M-Y',strtotime($value->created_at)) ; ?></td>
                                                <td><?php echo $value->purpose; ?></td>                                              
                                                <td><?php echo $value->profit_earn; ?></td>
                                                <td><?php 
                                                echo $value->profit_cost;?></td>
                                            </tr>
                                          <?php endif; ?>
                                            <?php } ?>
                                                                   
                                        </tbody>
                                           
                                                <tr>
                                                   <td colspan="3"><strong>TOTAL</strong></td>
                                                   <td>
                                                    <strong>
                                                       <?php
                                                       echo number_format($total_earn,2);
                                                       ?>
                                                   </strong>

                                                   </td>
                                                   <td>
                                                     <strong>
                                                       <?php
                                                       echo number_format($total_cost,2);
                                                       ?>
                                                           </strong>
                                                       
                                                   </td> 

                                                </tr>
                                                <tr>
                                                   <td colspan="3"><strong>NET PROFIT</strong></td>
                                                   <td colspan="2">
                                                    <?php
                                                    $total_profit = $total_earn - $total_cost ;
                                                    ?> 
                                                    <strong>
                                                    <?php if($total_profit > 0):?>
                                                        
                                                       <?php
                                                       echo number_format($total_earn - $total_cost,2);
                                                       ?>
                                                   
                                                 <?php else:?>
                                                  <span style="color:red;">
                                                   <?php
                                                       echo number_format($total_earn - $total_cost,2);
                                                       ?>
                                                     </span>
                                                 <?php endif;?>
                                                 </strong>
                                                   </td>
                                                </tr>
                                            
                                            </tbody>
                                        </table>
                                    </div>
                                    </div>
                                </div>
                                <!-- END EXAMPLE TABLE PORTLET-->
                            </div>
                       
                        <!-- END DASHBOARD STATS 1-->
                    </div><!-- END PAGE CONTENT BODY -->
             