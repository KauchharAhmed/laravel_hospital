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
                                          CASH RECEIVE REPORT BETWEEN FROM <?php echo date('d M Y',strtotime($from_date)) ;?> TO <?php echo date('d M Y',strtotime($to_date)) ; ?>
                                          <?php 
                                          echo "FOR ";
                                          if($transfer_type == '0'){
                                            echo "PENDING";
                                          }elseif($transfer_type == '1'){
                                            echo "APPROVED";
                                          }elseif($transfer_type == '2'){
                                            echo "REJECTED";
                                          }
                                          ?>

                                            </span>
                                              </div> 
                                        {!! Form::open(['url' =>'printManagerCashReceiveAmtReport','method' => 'post','role' => 'form','class'=>'form-horizontal','target'=>'_blank']) !!}
                                        <input type="hidden" name="from_date" value="<?php echo $from_date;?>">
                                        <input type="hidden" name="to_date" value="<?php echo $to_date;?>">
                                        <input type="hidden" name="transfer_type" value="<?php echo $transfer_type;?>">
                                       <button type="submit" style="float:right;margin-right:6px;" class="btn btn-success">Print</button> 
                                      {!! Form::close() !!} 
                                        
                                    </div>
                                    <div class="portlet-body">
                                         <div class="header">
                                
                            </div>
                                        <div class="table-responsive">
                                             <table class="table table-striped table-hover table-bordered" id="sample_editable_1">
                                                <thead>
                                                <tr>
                                                    <th>Sl No</th>
                                                    <th>Transfer Date</th>
                                                    <th>Transfer Type</th>
                                                    <th>Remarks</th>
                                                    <th>Receive Amt</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $i = 1 ;
                                                $total_transfer_amt = 0 ;
                                                foreach ($result as $value) { $total_transfer_amt = $total_transfer_amt + $value->transfer_amount ;
                                                 ?>
                                                <tr>
                                                    <td><?php echo $i++ ;?></td>
                                                    <td><?php echo date('d-M-Y',strtotime($value->transfer_date)) ; ?></td>
                                                    <td><?php
                                                    if($value->status == '0'):?>
                                                    <span style="color:green">PENDING</span>
                                                  <?php elseif($value->status == '1'):?>
                                                    <span style="color:blue">APPROVED</span>
                                                    <?php elseif($value->status == '2'):?>
                                                    <span style="color:red">REJECTED</span>
                                                     <?php endif;?></td>
                                                    <td><?php echo $value->remarks ; ?></td>
                                                    <td><?php echo $value->transfer_amount ; ?></td>
                                                </tr>
                                                <?php } ?>
                                                <tr>
                                                    <td colspan="4"><strong>TOTAL</strong></td>
                                                    <td><strong><?php echo number_format($total_transfer_amt,2);?></strong></td>
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
             