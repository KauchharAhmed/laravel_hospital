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
                                          OT BOOKING LIST BETWEEN FROM <?php echo date('d M Y',strtotime($from_date)) ;?> TO <?php echo date('d M Y',strtotime($to_date)) ; ?>
                                          
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
                                                    <th>Booking Date</th>
                                                    <th>Booking No</th>
                                                    <th>OT Type</th>
                                                    <th>Patient Id</th>
                                                    <th>Patient Name</th>
                                                    <th>Advance Payment Amt</th>
                                                    <th>Print</th>
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
                                                    <td><a target="_blank" href="{{URL::to('printOTBookingInvoice/'.$value->id.'/'.$value->invoice.'/'.$value->cashbook_id)}}"><button type="button" class="btn btn-info btn-sm">PRINT INVOICE</button></a></td> 
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
             