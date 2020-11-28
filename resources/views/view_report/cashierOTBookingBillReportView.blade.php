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
                                          OT BOOKING REPORT BETWEEN FROM <?php echo date('d M Y',strtotime($from_date)) ;?> TO <?php echo date('d M Y',strtotime($to_date)) ; ?>
                                          <?php if($ot_type != ''){
                                           $ot_type_indi_query = DB::table('tbl_ot_type')->where('branch_id',$branch_id)->where('id',$ot_type)->first();
                                                    echo "FOR ".$ot_type_indi_query->ot_type ;
                                              }
                                                     ?>
                                            </span>
                                              </div> 
                                             {!! Form::open(['url' =>'printCashierOTBookingBillReport','method' => 'post','role' => 'form','class'=>'form-horizontal','target'=>'_blank']) !!}
                                        <input type="hidden" name="from_date" value="<?php echo $from_date;?>">
                                        <input type="hidden" name="to_date" value="<?php echo $to_date;?>">
                                        <input type="hidden" name="ot_type" value="<?php echo $ot_type;?>">
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
                                                    <th>OT Type</th>
                                                    <th>Patient Id</th>
                                                    <th>Patient Name</th>
                                                    <th>Advance Payment Amt</th>
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
                                                    <td><?php echo date('d-M-Y',strtotime($value->booking_date)) ; ?></td>
                                                    <td><?php echo "OTB- ".$value->invoice ; ?></td>
                                                    <td><?php $ot_type_query = DB::table('tbl_ot_type')->where('branch_id',$branch_id)->where('id',$value->ot_type)->first();
                                                    echo $ot_type_query->ot_type ;
                                                     ?></td>
                                                    <td><?php echo $value->patient_number ; ?></td>
                                                    <td><?php echo $value->patient_name ; ?></td>
                                                    <?php
                                                    // get tr info
                                                     $ot_booking_tr_transaction = DB::table('tbl_ot_ledger')->where('branch_id',$branch_id)->where('ot_booking_id',$value->id)->where('service_invoice',$value->invoice)->where('service_id',$value->id)->where('service_type',1)->first(); 
                                                    ?>
                                                    <td><?php echo $ot_booking_tr_transaction->payment_amount ;  ?></td>  
                                                    <?php if($setting_status == '2'){?>
                                                    <td><a href="{{URL::to('cashierDeleteOTBooking/'.$value->id.'/'.$value->invoice.'/'.$value->year_invoice.'/'.$value->daily_invoice.'/'.$value->cashbook_id)}}"><button type="button" class="btn btn-danger btn-sm" onclick="return confirm('Are You Sure You Want To Delete It ?')">DELETE</button></a></td>  
                                                    <?php } ?>
                                                </tr>
                                                <?php } ?>
                                                <tr>
                                                    <td colspan="6"><strong>TOTAL</strong></td>
                                                    <td><strong><?php echo number_format($total_payment_amt,2);?></strong></td>      
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
             