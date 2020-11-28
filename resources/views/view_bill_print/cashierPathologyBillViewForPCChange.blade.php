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
                                          PATHOLOGY BILL LIST BETWEEN FROM <?php echo date('d M Y',strtotime($from_date)) ;?> TO <?php echo date('d M Y',strtotime($to_date)) ; ?> TO CHANGE PC
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
                                                    <th>PC</th>
                                                    <th>Bill Amt</th>
                                                    <th>Discount Amt</th>
                                                    <th>PC Amt</th>
                                                    <th>Change PC</th>
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
                                                    <td><?php if($value->pc_id == '0'): ; ?>
                                                      <span style="color:green;">HOSPITAL</span>
                                                    <?php else:?>
                                                      <?php $pc_info_query = DB::table('tbl_pc')->where('id',$value->pc_id)->first();
                                                      echo $pc_info_query->name.' - '.$pc_info_query->mobile.'-'.$pc_info_query->address ;
                                                       ?>
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
                                                    <td><?php 
                                                     if($value->pc_id == '0'){
                                                      echo '0.00' ;
                                                     }else{
                                                      $pc_amt_query = DB::table('pc_ledger')->where('branch_id',$branch_id)->where('invoice',$value->invoice)->where('year_invoice',$value->year_invoice)->where('daily_invoice_number',$value->daily_invoice)->where('cashbook_id',$value->cashbook_id)->where('invoice_type',1)->where('pc_id',$value->pc_id)->first();
                                                      echo $pc_amt_query->payable_amount ;
                                                     }
                                                     ?></td>
                                                    
                                                     <td>
                                                      <a target="_blank" href="{{URL::to('pathologyPCChange/'.$value->invoice.'/'.$value->year_invoice.'/'.$value->daily_invoice.'/'.$value->cashbook_id)}}"><button type="button" class="btn btn-info btn-sm">CHANGE PC</button></a>  
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
             