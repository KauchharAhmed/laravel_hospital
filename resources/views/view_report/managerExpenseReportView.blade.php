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
                                        EXPENSE REPORT BETWEEN FROM <?php echo date('d M Y',strtotime($from_date)) ;?> TO <?php echo date('d M Y',strtotime($to_date)) ; ?>
                                          <?php if($expense !=''){
                                            $expense_query = DB::table('expense_category')->where('id',$expense)->first();
                                            echo "FOR ".$expense_query->expense_name ;
                                          }
                                          ?>
                                            </span>
                                              </div> 
                                        {!! Form::open(['url' =>'printManagerExpenseReport','method' => 'post','role' => 'form','class'=>'form-horizontal','target'=>'_blank']) !!}
                                        <input type="hidden" name="from_date" value="<?php echo $from_date;?>">
                                        <input type="hidden" name="to_date" value="<?php echo $to_date;?>">
                                        <input type="hidden" name="expense" value="<?php echo $expense;?>">
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
                                                    <th>Payment Date</th>
                                                    <th>Memo No</th>
                                                    <th>Category</th>
                                                    <th>Service Provider</th>
                                                    <th>Provider Memo</th>
                                                    <th>Expense Amt</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $i = 1 ;
                                                $grand_total_payment = 0 ;
                                                foreach ($result as $value) {
                                                 $grand_total_payment = $grand_total_payment + $value->amount ;
                                                  ?>
                                                <tr>
                                                    <td><?php echo $i++ ;?></td>
                                                    <td><?php echo date('d-M-Y',strtotime($value->created_at)) ; ?></td>
                                                    <td><?php echo $value->memo_no ; ?></td>
                                                    <td><?php echo $value->expense_name ; ?></td>
                                                    <td><?php echo $value->service_provider ; ?></td>
                                                    <td><?php echo $value->service_provider_memo_no ; ?></td>
                                                    <td><?php echo $value->amount ;  ?></td>   
                                                </tr>
                                                <?php } ?>
                                                <tr>
                                                    <td colspan="6"><strong>TOTAL</strong></td>
                                                     <td><strong><?php echo number_format($grand_total_payment,2);?></strong></td>          
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
             