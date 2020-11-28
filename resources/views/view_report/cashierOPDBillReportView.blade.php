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
                                         OPD BILL REPORT BETWEEN FROM <?php echo date('d M Y',strtotime($from_date)) ;?> TO <?php echo date('d M Y',strtotime($to_date)) ; ?>
                                         <?php
                                         if($doctor != ''){
                                          $doctor_info_query = DB::table('admin')->where('id',$doctor)->first();
                                          echo "For Doctor ".$doctor_info_query->name ;
                                         }
                                         ?>
                                            </span>
                                            </div> 
                                        {!! Form::open(['url' =>'printCashierOPDBillReport','method' => 'post','role' => 'form','class'=>'form-horizontal','target'=>'_blank']) !!}
                                        <input type="hidden" name="from_date" value="<?php echo $from_date;?>">
                                        <input type="hidden" name="to_date" value="<?php echo $to_date;?>">
                                        <input type="hidden" name="doctor" value="<?php echo $doctor;?>">
                                       <button target="_blank" type="submit" style="float:right;margin-right:6px;" class="btn btn-success">Print</button> 
                                      {!! Form::close() !!} 
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
                                                    <th>Doctor</th>
                                                    <th>Patient Id</th>
                                                    <th>Patient Name</th>
                                                    <th>Bill Amt</th>
                                                    <th>Discount Amt</th>
                                                    <th>Rebate Amt</th>
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
                                                    <td><?php echo date('d-M-Y',strtotime($value->bill_date)) ; ?></td>
                                                    <td><?php echo "OPD- ".$value->invoice ; ?></td>
                                                    <td><?php $all_doctor_query = DB::table('admin')->where('id',$value->doctor_id)->first();
                                                      echo $all_doctor_query->name ;
                                                      ?>
                                                      
                                                    </td>
                                                    <td><?php echo $value->patient_number ; ?></td>
                                                    <td><?php echo $value->patient_name ; ?></td>
                                                    <?php
                                                    // get tr info
                                                    $pathology_tr_query = DB::table('opd_bill_transaction')->where('branch_id',$branch_id)->where('cashbook_id',$value->cashbook_id)->where('invoice_number',$value->invoice)->where('invoice_tr_id',1)->where('status',0)->first(); 
                                                    ?>
                                                    <td><?php echo $pathology_tr_query->total_payable ;  ?></td>
                                                    <td><?php echo $pathology_tr_query->total_discount ;  ?></td>
                                                    <td><?php echo $pathology_tr_query->total_rebate ;  ?></td>
                                                    <td><?php echo $pathology_tr_query->total_payment ;  ?></td> 
                                                      <?php if($setting_status == '2'){?>
                                                    <td><a target="_blank" href="{{URL::to('cashierDeleteOPDBill/'.$value->invoice.'/'.$value->year_invoice.'/'.$value->daily_invoice.'/'.$value->cashbook_id)}}"><button type="button" class="btn btn-danger btn-sm" onclick="return confirm('Are You Sure You Want To Delete It ?')">DELETE</button></a></td> 
                                                    <?php } ?>  
                                                </tr>
                                                <?php } ?>

                                                <tr>
                                                    <td colspan="6"><strong>TOTAL</strong></td>
                                                     <td><strong><?php echo number_format($total_payable_amt,2);?></strong></td>
                                                      <td><strong><?php echo number_format($total_discount_amt,2);?></strong></td>
                                                       <td><strong><?php echo number_format($total_rebate_amt,2);?></strong></td>
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
             