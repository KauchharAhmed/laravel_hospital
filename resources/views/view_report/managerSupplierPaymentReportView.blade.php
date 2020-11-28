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
                                          SUPPLIER PAYMENT REPORT BETWEEN FROM <?php echo date('d M Y',strtotime($from_date)) ;?> TO <?php echo date('d M Y',strtotime($to_date)) ; ?>
                                          <?php if($supplier !=''){
                                            $supplier_query = DB::table('supplier')->where('id',$supplier)->first();
                                            echo "FOR ".$supplier_query->supplier_name ;
                                          }
                                          ?>
                                            </span>
                                              </div> 
                                        {!! Form::open(['url' =>'printManagerSupplierPaymentReport','method' => 'post','role' => 'form','class'=>'form-horizontal','target'=>'_blank']) !!}
                                        <input type="hidden" name="from_date" value="<?php echo $from_date;?>">
                                        <input type="hidden" name="to_date" value="<?php echo $to_date;?>">
                                        <input type="hidden" name="supplier" value="<?php echo $supplier;?>">
                                       <button type="submit" style="float:right;margin-right:6px;" class="btn btn-success">Print</button> 
                                      {!! Form::close() !!}   
                                    </div>
                                    <div class="portlet-body">
                                         <div class="header">
                                
                            </div>
                                        <div class="table-responsive">
                                             <table class="table table-striped table-hover table-bordered" id="sample_editable_1">
                                                <thead>
                                                <tr>
                                                    <th>Sl No</th>
                                                    <th>Payment Date</th>
                                                    <th>Memo No</th>
                                                    <th>Supplier Name</th>
                                                    <th>Supplier Mobile</th>
                                                    <th>Payment Method</th>
                                                    <th>Payment Amt</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $i = 1 ;
                                                $grand_total_payment = 0 ;
                                                foreach ($result as $value) {
                                                 $grand_total_payment = $grand_total_payment + $value->payment_amount ;
                                                  ?>
                                                <tr>
                                                    <td><?php echo $i++ ;?></td>
                                                    <td><?php echo date('d-M-Y',strtotime($value->created_at)) ; ?></td>
                                                    <td><?php echo $value->memo_no ; ?></td>
                                                    <td><?php echo $value->supplier_name ; ?></td>
                                                    <td><?php echo $value->supplier_mobile ; ?></td>
                                                    <td><?php if($value->payment_method == '1'){
                                                      echo "CASH";
                                                    }elseif($value->payment_method == '3'){
                                                     echo "BANK";
                                                    }  ?></td>
                                                    <td><?php echo $value->payment_amount ;  ?></td>   
                                                </tr>
                                                <?php } ?>
                                                <tr>
                                                    <td colspan="6"><strong>TOTAL</strong></td>
                                                     <td><strong><?php echo number_format($grand_total_payment,2);?></strong></td>          
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
             