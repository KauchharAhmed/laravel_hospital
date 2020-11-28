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
                                          OT CLEARANCE BILL LIST BETWEEN FROM <?php echo date('d M Y',strtotime($from_date)) ;?> TO <?php echo date('d M Y',strtotime($to_date)) ; ?> TO CHANGE PC
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
                                                    <th>PC Amt</th>
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
                                                    <td><?php echo "OTC- ".$value->invoice ; ?></td>
                                                    <td><?php echo $value->patient_number ; ?></td>
                                                    <td><?php echo $value->patient_name ; ?></td>

                                                    <td><?php if($value->pc_id == '0'): ; ?>
                                                      <span style="color:green;">HOSPITAL</span>
                                                    <?php else:?>
                                                      <?php $pc_info_query = DB::table('tbl_pc')->where('id',$value->pc_id)->first();
                                                      echo $pc_info_query->name.' - '.$pc_info_query->mobile.'-'.$pc_info_query->address ;
                                                       ?>
                                                    <?php endif;?> ;</td>
                                                    <td><?php 
                                                     if($value->pc_id == '0'){
                                                      echo '0.00' ;
                                                     }else{
                                                      $pc_amt_query = DB::table('pc_ledger')->where('branch_id',$branch_id)->where('invoice',$value->invoice)->where('year_invoice',$value->year_invoice)->where('daily_invoice_number',$value->daily_invoice)->where('cashbook_id',$value->cashbook_id)->where('invoice_type',3)->where('pc_id',$value->pc_id)->first();
                                                      echo $pc_amt_query->payable_amount ;
                                                     }
                                                     ?></td>

                                                    <?php
                                                    // get tr info
                                                     $ot_tr_transaction = DB::table('tbl_ot_ledger')->where('branch_id',$branch_id)->where('ot_booking_id',$value->ot_booking_id)->get();
                                                      $ot_payable_amt = 0 ;
                                                     $ot_discount    = 0 ;
                                                     $ot_rebate      = 0 ;
                                                     $ot_payment_amt = 0 ;
                                                     foreach ($ot_tr_transaction as $ot_tr_value) {
                                                        $ot_payable_amt = $ot_payable_amt + $ot_tr_value->payable_amount ;
                                                        $ot_discount    = $ot_discount + $ot_tr_value->discount ;
                                                        $ot_rebate      = $ot_rebate + $ot_tr_value->rebate ;
                                                        $ot_payment_amt = $ot_payment_amt + $ot_tr_value->payment_amount ;
                                                      } 
                                                    ?>
                                                    <td><?php echo number_format($ot_payable_amt,2) ;  ?></td>
                                                    <td><?php echo number_format($ot_discount,2) ;  ?></td>
                                                    <td><?php echo number_format($ot_rebate,2) ;  ?></td>
                                                    <td><?php echo number_format($ot_payment_amt,2) ;  ?></td>  
                                                    <td><a target="_blank" href="{{URL::to('otPCChange/'.$value->invoice.'/'.$value->year_invoice.'/'.$value->daily_invoice.'/'.$value->cashbook_id.'/'.$value->ot_booking_id)}}"><button type="button" class="btn btn-info btn-sm">CHANGE PC</button></a></td> 
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
             