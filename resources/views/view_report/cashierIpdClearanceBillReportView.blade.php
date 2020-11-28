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
                                          IPD CLEARANCE BILL REPORT BETWEEN FROM <?php echo date('d M Y',strtotime($from_date)) ;?> TO <?php echo date('d M Y',strtotime($to_date)) ; ?>
                                            </span>
                                              </div> 
                                             {!! Form::open(['url' =>'printCashierIpdClearanceBillReport','method' => 'post','role' => 'form','class'=>'form-horizontal','target'=>'_blank']) !!}
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
                                                    <th>Bill Date</th>
                                                    <th>Admission No</th>
                                                    <th>Bill No</th>
                                                    <th>Patient Id</th>
                                                    <th>Patient Name</th>
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
                                                    <td><?php 
                                                    $ipd_admit_query = DB::table('tbl_ipd_admission')->where('id',$value->ipd_admission_id)->first();
                                                    echo "IPDA- ".$ipd_admit_query->invoice ;
                                                     ?></td>
                                                    <td><?php echo "IPDC- ".$value->invoice ; ?></td>
                                                    <td><?php echo $value->patient_number ; ?></td>
                                                    <td><?php echo $value->patient_name ; ?></td>
                                                    <?php
                                                    // get tr info
                                                     $ipd_admission_tr_transaction = DB::table('tbl_ipd_ledger')->where('branch_id',$branch_id)->where('ipd_admission_id',$value->ipd_admission_id)->where('service_invoice',$value->invoice)->where('service_id',$value->id)->where('service_type',6)->first(); 
                                                    ?>
                                                    <td><?php echo $ipd_admission_tr_transaction->discount ;  ?></td>
                                                    <td><?php echo $ipd_admission_tr_transaction->rebate ;  ?></td>
                                                    <td><?php echo $ipd_admission_tr_transaction->payment_amount ;  ?></td>
                                                    <?php if($setting_status == '2'){?>
                                                     <td>
                                                    <a href="{{URL::to('cashierDeleteIPDClearence/'.$value->id.'/'.$value->ipd_admission_id.'/'.$value->invoice.'/'.$value->year_invoice.'/'.$value->daily_invoice.'/'.$value->cashbook_id)}}"><button type="button" class="btn btn-danger btn-sm" onclick="return confirm('Are You Sure You Want To Delete It ?')">DELETE</button></a>
                                                    </td> 
                                                    <?php } ?>   
                                                </tr>
                                                <?php } ?>

                                                <tr>
                                                    <td colspan="6"><strong>TOTAL</strong></td>
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
             