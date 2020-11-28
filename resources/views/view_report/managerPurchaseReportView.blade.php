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
                                          PRODUCT PURCHASE REPORT BETWEEN FROM <?php echo date('d M Y',strtotime($from_date)) ;?> TO <?php echo date('d M Y',strtotime($to_date)) ; ?>
                                          <?php if($supplier !=''){
                                            $supplier_query = DB::table('supplier')->where('id',$supplier)->first();
                                            echo "FOR ".$supplier_query->supplier_name ;
                                          }
                                          ?>
                                            </span>
                                              </div> 
                                        {!! Form::open(['url' =>'printManagerPurchaseReport','method' => 'post','role' => 'form','class'=>'form-horizontal','target'=>'_blank']) !!}
                                        <input type="hidden" name="from_date" value="<?php echo $from_date;?>">
                                        <input type="hidden" name="to_date" value="<?php echo $to_date;?>">
                                        <input type="hidden" name="supplier" value="<?php echo $supplier;?>">
                                       <button type="submit" style="float:right;margin-right:6px;" class="btn btn-success">Print</button> 
                                      {!! Form::close() !!}   
                                    </div>
                                    <div class="portlet-body">
                                         <div class="header">
                                          <?php
                                          // get manager delete setting
                                          $count         = DB::table('tbl_admin_delete_setting')->where('branch_id',$branch_id)->count();
                                          $delete_setting = DB::table('tbl_admin_delete_setting')->where('branch_id',$branch_id)->first();
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
                                                    <th>Purchase Date</th>
                                                    <th>Invoice No</th>
                                                    <th>Supplier Name</th>
                                                    <th>Supplier Mobile</th>
                                                    <th>Total Quantity</th>
                                                    <th>Purchase Amt</th>
                                                    <th>Payment Amt</th>
                                                    <?php if($setting_status == '2'){?>
                                                    <th>Delete</th>
                                                    <?php } ?>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $i = 1 ;
                                                $grand_total_qty     = 0 ;
                                                $grand_total_price   = 0 ;
                                                $grand_total_payment = 0 ;
                                                foreach ($result as $value) {
                                                 $grand_total_qty     = $grand_total_qty + $value->total_quantity ;
                                                 $grand_total_price   = $grand_total_price + $value->total_price ;
                                                 $grand_total_payment = $grand_total_payment + $value->total_payment ;
                                                  ?>
                                                <tr>
                                                    <td><?php echo $i++ ;?></td>
                                                    <td><?php echo date('d-M-Y',strtotime($value->purchase_date)) ; ?></td>
                                                    <td><?php echo $value->invoice ; ?></td>
                                                    <td><?php echo $value->supplier_name ; ?></td>
                                                    <td><?php echo $value->supplier_mobile ; ?></td>
                                                    <td><?php echo $value->total_quantity ; ?></td>
                                                    <td><?php echo $value->total_price ;  ?></td>
                                                    <td><?php echo $value->total_payment ;  ?></td>
                                                      <?php if($setting_status == '2'){?>
                                                    <td><a href="{{URL::to('managerPurchaseDelete/'.$value->invoice.'/'.$value->cashbook_id)}}"><button type="button" class="btn btn-danger btn-sm" onclick="return confirm('Are You Sure You Want To Delete It ?')">DELETE</button></a></td> 
                                                    <?php } ?>   
                                                </tr>
                                                <?php } ?>
                                                <tr>
                                                    <td colspan="5"><strong>TOTAL</strong></td>
                                                     <td><strong><?php echo number_format($grand_total_qty,2);?></strong></td>
                                                      <td><strong><?php echo number_format($grand_total_price,2);?></strong></td>
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
             