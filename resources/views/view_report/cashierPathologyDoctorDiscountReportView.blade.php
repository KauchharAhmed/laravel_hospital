  <div class="row">
 
                            <div class="col-md-12">
                                 
                                <!-- BEGIN EXAMPLE TABLE PORTLET-->
                                <div class="portlet light bordered">
                                    <div class="portlet-title">
                                        <div class="caption font-dark">
                                            <span class="caption-subject bold uppercase">
                                           <?php foreach ($pathology_tr_transaction as $value1) {

                                          }
                                          ?>
                                          PATHOLOGY DOCTOR DISCOUNT REPORT BETWEEN FROM <?php echo date('d M Y',strtotime($from_date)) ;?> TO <?php echo date('d M Y',strtotime($to_date)) ; ?>
                                            </span>
                                              </div> 
                                             {!! Form::open(['url' =>'printCashierPathologyDoctorDiscountReport','method' => 'post','role' => 'form','class'=>'form-horizontal','target'=>'_blank']) !!}
                                        <input type="hidden" name="from_date" value="<?php echo $from_date;?>">
                                        <input type="hidden" name="to_date" value="<?php echo $to_date;?>">
                                       <button target="_blank" type="submit" style="float:right;margin-right:6px;" class="btn btn-success">Print</button> 
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
                                                    <th>Bill Date</th>
                                                    <th>Discount Date</th>
                                                    <th>Bill No</th>
                                                    <th>Tr No</th>
                                                    <th>Dr</th>
                                                    <th>Patient Id</th>
                                                    <th>Patient Name</th>
                                                    <th>Discount Amt</th>
                                                    <?php if($setting_status == '2'){?>
                                                    <th>Delete</th>
                                                    <?php } ?>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $i = 1 ;
                                                foreach ($pathology_tr_transaction as $value) { 
                                                  // pathology bill info
                                                  $pathology_bill_info = DB::table('pathology_bill')->where('branch_id',$branch_id)->where('invoice',$value->invoice_number)->where('year_invoice',$value->year_invoice_number)->where('daily_invoice',$value->daily_invoice_number)->first();
                                                  // get patient info
                                                  $patient_info = DB::table('tbl_patient')->where('id',$pathology_bill_info->patient_id)->first();
                                                 ?>
                                                <tr>
                                                    <td><?php echo $i++ ;?></td>
                                                    <td><?php echo date('d-M-Y',strtotime($pathology_bill_info->bill_date)) ; ?></td>
                                                    <td><?php echo date('d-M-Y',strtotime($value->tr_date)) ; ?></td>
                                                    <td><?php echo "PAT- ".$pathology_bill_info->invoice ; ?></td>
                                                    <td><?php echo $value->invoice_tr_id ; ?></td>
                                                    <td><?php $dr_query = DB::table('admin')->where('id',$pathology_bill_info->doctor_id)->first() ; echo $dr_query->name ;  ?></td>

                                                    <td><?php echo $patient_info->patient_number ; ?></td>
                                                    <td><?php echo $patient_info->patient_name ; ?></td>
                                                    <td><?php echo $value->total_discount ;  ?></td>
                                                    <?php if($setting_status == '2'){?>
                                                    <td><a target="_blank" href="{{URL::to('cashierDeletePathologyDoctorDiscount/'.$value->invoice_number.'/'.$value->year_invoice_number.'/'.$value->daily_invoice_number.'/'.$value->cashbook_id.'/'.$value->invoice_tr_id.'/'.'2')}}"><button type="button" class="btn btn-danger btn-sm" onclick="return confirm('Are You Sure You Want To Delete It ?')">DELETE</button></a></td> 
                                                    <?php } ?> 
                                                </tr>
                                                <?php } ?>
                                                <tr>
                                                    <td colspan="8"><strong>TOTAL</strong></td>
                                                       <td><strong><?php echo number_format($total_discount_amt,2);?></strong></td>
                                                            
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
             