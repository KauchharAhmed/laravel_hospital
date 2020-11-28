  <div class="row">
 
                            <div class="col-md-12">
                                 
                                <!-- BEGIN EXAMPLE TABLE PORTLET-->
                                <div class="portlet light bordered">
                                    <div class="portlet-title">
                                        <div class="caption font-dark">
                                            <span class="caption-subject bold uppercase">
                                                <div class="row">
                                                    <?php
                                                     foreach ($result as $value1) {
                                                        
                                                     }
                                                  
                                                    ?>
                                                <div class="col-md-4">
                                                    NAME
                                                    
                                                </div>
                                                <div class="col-md-8">
                                                    :<?php echo $value1->name;?>
                                                    
                                                </div>
                                                <div class="col-md-4">
                                                    MOBILE
                                                    
                                                </div>
                                                <div class="col-md-8">
                                                    :<?php echo $value1->mobile;?>
                                                    
                                                </div>
                                              <div class="col-md-4">
                                                    TYPE
                                                    
                                                </div>
                                                <div class="col-md-8">
                                                    :<?php 
                                                      if($value1->staff_type == '2'){
                                                        echo "Main Surgeon";

                                                      }elseif($value1->staff_type == '3'){
                                                        echo "Assistant Surgeon";
                                                      }elseif($value1->staff_type == '4'){
                                                        echo "Anesthesia";
                                                      }elseif($value1->staff_type == '5'){
                                                        echo "OT Assistant";

                                                      }

                                                    ?>
                                                    
                                                </div>

                                                
                                                </div>
                                            
                                            </span>
                                        </div>
                                       
                                    </div>
                                    <div class="portlet-body">
                                         <div class="header">
                                
                                     </div>
                                        <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                     <tr>
                                                    <th>Sl</th>
                                                    <th>OT <br/> Booking<br/> Date</th>
                                                    <th>OT <br/>Clear <br/>Date</th>
                                                    <th>OT <br/>Booking <br/>No</th>
                                                    <th>OT Type</th>
                                                    <th>Patient Info</th>
                                                    <th>Payable Amt</th>
                                                    <th>Payment</th>
                                                </tr>
                                                </tr>
                                            </thead>
                                            <tbody>
                                               <?php $i = 1 ;
                                                foreach ($result as $value) { ?>
                                                <tr>
                                                    <td><?php echo $i++ ;?></td>
                                                    <td>
                                                    <?php
                                                    // booking date 
                                                    $ot_booking_query = DB::table('tbl_ot_booking')->where('id',$value->ot_booked_id)->first();
                                                    echo date('d M Y',strtotime($ot_booking_query->booking_date)) ;
                                                    ?>  
                                                    </td>
                                                    <td><?php 
                                                    $ot_clear_query = DB::table('tbl_ot_clear_bill')->where('ot_booking_id',$value->ot_booked_id)->first();
                                                    echo date('d M Y',strtotime($ot_clear_query->bill_date)); ?></td>
                                                    <td><?php echo $ot_booking_query->invoice; ?></td>
                                                    <td>
                                                    <?php
                                                    // ot type
                                                    $ot_type_query = DB::table('tbl_ot_type')->where('id',$ot_booking_query->ot_type)->first();
                                                    echo $ot_type_query->ot_type ;
                                                    ?>  
                                                    </td>
                                                     <td>
                                                    <?php
                                                    // patient info
                                                    $patient_info = DB::table('tbl_patient')->where('id',$value->patient_id)->first();
                                                    echo $patient_info->patient_name ;
                                                    ?>
                                                    <br/>
                                                    <?php echo $patient_info->patient_number ; ?>
                                                    <br/>
                                                    <?php echo $patient_info->patient_mobile ; ?>
                                                    <br/>
                                                    <?php echo $patient_info->address ; ?>  
                                                     </td>
                                                    <td>
                                                        Payable Amt = 
                                                    <?php
                                                    // calculation
                                                       $payment_ot = DB::table('ot_staff_payment')->where('branch_id',$branch_id)->where('ot_booking_id',$value->ot_booked_id)->where('staff_type',$ot_staff_type)->where('staff_id',$staff)->where('patient_id',$patient_info->id)->get();
    $total_payment = 0 ;
    foreach ($payment_ot as $payment_value) {
      $total_payment = $total_payment + $payment_value->payment_amount ;
    }


                                                    echo $value->amount ;
                                                    ?> 
                                                    <br/>
                                                    Payment Amt  = <?php echo number_format($total_payment , 2);?>
                                                    <br/>
                                                    Due Amt <span style="padding-left:30px;">=</span>  <?php echo number_format($value->amount - $total_payment , 2);?>
                                                    </td>
                                                    <td>
                                                    <a href="{{URL::to('otStaffPayment/'.$value->ot_booked_id.'/'.$ot_staff_type.'/'.$staff.'/'.$value->patient_id)}}"> <button type="button" class="btn btn-info btn-sm">PAYMENT</button></a>
                                                       
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
             