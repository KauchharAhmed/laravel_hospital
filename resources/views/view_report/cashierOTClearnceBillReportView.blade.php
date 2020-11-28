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
                                          OT CLEARANCE REPORT BETWEEN FROM <?php echo date('d M Y',strtotime($from_date)) ;?> TO <?php echo date('d M Y',strtotime($to_date)) ; ?>
                                            </span>
                                              </div> 
                                             {!! Form::open(['url' =>'printCashierOTClearanceBillReport','method' => 'post','role' => 'form','class'=>'form-horizontal','target'=>'_blank']) !!}
                                        <input type="hidden" name="from_date" value="<?php echo $from_date;?>">
                                        <input type="hidden" name="to_date" value="<?php echo $to_date;?>">
                                       <button type="submit" style="float:right;margin-right:6px;" class="btn btn-success">Print</button> 
                                      {!! Form::close() !!} 
                                        
                                    </div>
                                    <div class="portlet-body">
                                         <div class="header">
                                          <?php
                                          // get manager delete setting
                                          $count         = DB::table('tbl_manager_delete_setting')->where('branch_id',$branch_id)->count();
                                          $delete_setting = DB::table('tbl_manager_delete_setting')->where('branch_id',$branch_id)->first();
                                          if($count == '0'){
                                            $setting_status = $count ;
                                          }else{
                                            $setting_status = $delete_setting->current_status  ;
                                          }
                                          ?>
                                
                            </div>
                                        <div class="table-responsive">
                                             <table class="table table-striped table-hover table-bordered" id="sample_editable_1">
                                                <thead>
                                                <tr>
                                                    <th>Sl No</th>
                                                    <th>Booking Date</th>
                                                    <th>Booking No</th>
                                                    <th>Bill Date</th>
                                                    <th>Bill No</th>
                                                    <th>Patient Id</th>
                                                    <th>Patient Name</th>
                                                    <th>Bill Amt</th>
                                                    <th>Discount Amt</th>
                                                    <th>Rebate Amt</th>
                                                    <th>Advance Payment Amt</th>
                                                    <th>Payment Amt</th>
                                                     <?php if($setting_status == '2'){?>
                                                    <th>Delete</th>
                                                    <?php } ?>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                    <?php $i = 1 ;
                                                foreach ($result as $value) { 
                                                 ?>
                                                <tr>
                                                    <td><?php echo $i++ ;?></td>
                                                    <?php $ot_booking_query = DB::table('tbl_ot_booking')->where('branch_id',$branch_id)->where('id',$value->ot_booking_id)->first();  ?>
                                                    <td><?php echo date('d-M-Y',strtotime($ot_booking_query->booking_date)) ; ?></td>
                                                    <td><?php echo "OTB- ".$ot_booking_query->invoice ; ?></td>
                                                    <td><?php echo date('d-M-Y',strtotime($value->bill_date)) ; ?></td>
                                                    <td><?php echo "OTC- ".$value->invoice ; ?></td>
                                                    <td><?php echo $value->patient_number ; ?></td>
                                                    <td><?php echo $value->patient_name ; ?></td>
                                                    <?php
                                                    // get tr info
                                                     $ot_tr_transaction = DB::table('tbl_ot_ledger')->where('branch_id',$branch_id)->where('ot_booking_id',$value->ot_booking_id)->get();
                                                      $advance_ot_tr_transaction = DB::table('tbl_ot_ledger')->where('branch_id',$branch_id)->where('ot_booking_id',$value->ot_booking_id)->where('service_type',1)->first();
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
                                                    <td><?php echo number_format($advance_ot_tr_transaction->payment_amount,2) ;  ?></td>
                                                    <td><?php echo number_format($ot_payment_amt-$advance_ot_tr_transaction->payment_amount,2) ;  ?></td> 
                                                    <?php if($setting_status == '2'){?>
                                                    <td><a href="{{URL::to('cashierDeleteOTClearence/'.$value->id.'/'.$value->ot_booking_id.'/'.$value->invoice.'/'.$value->year_invoice.'/'.$value->daily_invoice.'/'.$value->cashbook_id)}}"><button type="button" class="btn btn-danger btn-sm" onclick="return confirm('Are You Sure You Want To Delete It ?')">DELETE</button></a></td>  
                                                    <?php } ?>  
                                                </tr>
                                                <?php } ?>
                                                <tr>
                                                    <td colspan="7"><strong>TOTAL</strong></td>
                                                    <td><strong><?php echo number_format($total_payable_amt,2);?></strong></td>
                                                    <td><strong><?php echo number_format($total_discount_amt,2);?></strong></td>
                                                    <td><strong><?php echo number_format($total_rebate_amt,2);?></strong></td>
                                                    <td><strong><?php echo number_format($total_advance_payment,2);?></strong></td>
                                                    <td><strong><?php echo number_format($total_payment_amt - $total_advance_payment,2);?></strong></td>      
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
             