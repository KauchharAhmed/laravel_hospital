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
                                          PATHOLOGY BILL LIST BETWEEN FROM <?php echo date('d M Y',strtotime($from_date)) ;?> TO <?php echo date('d M Y',strtotime($to_date)) ; ?>
                                            </span>
                                              </div> 
                                    </div>
                                    <div class="portlet-body">
                                         <div class="header">
                                
                            </div>
                                        <div class="table-responsive">
                                             <table class="table table-striped table-hover table-bordered" id="sample_editable_1">
                                                <thead>
                                                <tr>
                                                    <th>Sl No</th>
                                                    <th>Bill Date</th>
                                                    <th>Bill No</th>
                                                    <th>Patient Id</th>
                                                    <th>Patient Name</th>
                                                    <th>Status</th>
                                                    <th>Bill Amt</th>
                                                    <th>Discount Amt</th>
                                                    <th>Rebate Amt</th>
                                                    <th>Payment Amt</th>
                                                    <th>Print</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $i = 1 ;
                                                foreach ($result as $value) { 
                                                 ?>
                                                <tr>
                                                    <td><?php echo $i++ ;?></td>
                                                    <td><?php echo date('d-M-Y',strtotime($value->bill_date)) ; ?></td>
                                                    <td><?php echo "PAT- ".$value->invoice ; ?></td>
                                                    <td><?php echo $value->patient_number ; ?></td>
                                                    <td><?php echo $value->patient_name ; ?></td>
                                                    <td><?php if($value->due_status == '1'): ?>
                                                      <span style="color:green;">PAID</span>
                                                    <?php elseif($value->due_status == '2'):?>
                                                      <span style="color:red;">DUE</span>
                                                    <?php endif;?>
                                                    </td>
                                                    <?php
                                                    // get tr info
                                                $pathology_tr_query = DB::table('pathology_bill_transaction')->where('branch_id',$branch_id)->where('invoice_number',$value->invoice)->get(); 
                                                $total_payable  = 0 ;
                                                $total_discount = 0 ;
                                                $total_rebate = 0 ;
                                                $total_payment = 0 ;
                                                    foreach ($pathology_tr_query as $pathology_tr_value) {
                                                      $total_payable = $total_payable + $pathology_tr_value->total_payable ;
                                                      $total_discount = $total_discount + $pathology_tr_value->total_discount ;
                                                      $total_rebate    = $total_rebate + $pathology_tr_value->total_rebate ;
                                                      $total_payment    = $total_payment + $pathology_tr_value->total_payment ;
                                                     
                                                    }
                                                    // return amount
                                                    $pathology_return_amount = DB::table('pathology_bill_transaction')->where('branch_id',$branch_id)->where('invoice_number',$value->invoice)->where('status',2)->get(); 
                                                    $total_return_amount = 0 ;
                                                    foreach ($pathology_return_amount as $return_value) {
                                                      $total_return_amount = $total_return_amount + $return_value->total_discount ;
                                                    }

                                                    ?>
                                                    <td><?php echo number_format($total_payable,2) ;  ?></td>
                                                    <td><?php echo number_format($total_discount,2) ;  ?></td>
                                                    <td><?php echo number_format($total_rebate,2) ;  ?></td>
                                                    <td><?php echo number_format($total_payment - $total_return_amount,2)  ;  ?></td> 
                                                     <td>
                                                      <a target="_blank" href="{{URL::to('printA4PathologyBill/'.$value->invoice.'/'.$value->cashbook_id)}}"><button type="button" class="btn btn-info btn-sm">PRINT INVOICE</button></a>  
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
             