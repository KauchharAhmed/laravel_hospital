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
                                          BANK STATEMENT BETWEEN FROM <?php echo date('d M Y',strtotime($from_date)) ;?> TO <?php echo date('d M Y',strtotime($to_date)) ; ?>
                                          <?php if($bank !=''){
                                            $bank_query = DB::table('bank')->where('id',$bank)->first();
                                            echo "FOR ".$bank_query->bank_name." , ".$bank_query->branch." Branch , "." A/C No ".$bank_query->account_no;
                                          }
                                          ?>
                                            </span>
                                              </div> 
                                        {!! Form::open(['url' =>'printManagerBankStatementReport','method' => 'post','role' => 'form','class'=>'form-horizontal','target'=>'_blank']) !!}
                                        <input type="hidden" name="from_date" value="<?php echo $from_date;?>">
                                        <input type="hidden" name="to_date" value="<?php echo $to_date;?>">
                                        <input type="hidden" name="bank" value="<?php echo $bank;?>">
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
                                                    <th>Date</th>
                                                    <th>Bank</th>
                                                    <th>Branch</th>
                                                    <th>AC / Name</th>
                                                    <th>AC / No</th>
                                                    <th>Type</th>
                                                    <th>Tr No</th>
                                                    <th>Deposit</th>
                                                    <th>Withdraw</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $i = 1 ;
                                                $total_receive_amount = 0 ;
                                                $total_send_amount    = 0 ;
                                                foreach ($result as $value) {
                                                 $total_receive_amount = $total_receive_amount + $value->receive_amount ;
                                                 $total_send_amount = $total_send_amount + $value->send_amount ;
                                                  ?>
                                                <tr>
                                                    <td><?php echo $i++ ;?></td>
                                                    <td><?php echo date('d-M-Y',strtotime($value->transaction_date)) ; ?></td>
                                                    <td><?php echo $value->bank_name ; ?></td>
                                                    <td><?php echo $value->bank_branch ; ?></td>
                                                    <td><?php echo $value->account_name ; ?></td>
                                                    <td><?php echo $value->account_no ; ?></td>
                                                    <td><?php if($value->status == '1'){
                                                      echo "Opening Balance";
                                                    }elseif($value->status == '2'){
                                                     echo "Supplier Payment";
                                                    }elseif($value->status == '3'){
                                                     echo "PC Payment";
                                                    }elseif($value->status == '4'){
                                                     echo "OT Staff Payment";
                                                    }elseif($value->status == '5'){
                                                     echo "Balance Transfer From Cash To Bank";
                                                    }elseif($value->status == '6'){
                                                     echo "Balance Transfer From Bank To Cash";
                                                    }   ?></td>
                                                    <td><?php echo $value->info_paper ;  ?></td>   
                                                    <td><?php echo $value->receive_amount ;  ?></td>   
                                                    <td><?php echo $value->send_amount ;  ?></td>   
                                                </tr>
                                                <?php } ?>
                                                <tr>
                                                    <td colspan="8"><strong>TOTAL</strong></td>
                                                     <td><strong><?php echo number_format($total_receive_amount,2);?></strong></td>
                                                     <td><strong><?php echo number_format($total_send_amount,2);?></strong></td>          
                                                </tr>
                                                  <tr>
                                                    <td colspan="8"><strong>BALANCE</strong></td>
                                                     <td colspan="2"><strong><?php echo number_format($total_receive_amount - $total_send_amount,2);?></strong></td>          
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
             