@extends('admin.masterCashier')
@section('content')
 <!-- BEGIN CONTENT -->
                <div class="page-content-wrapper">
                    <!-- BEGIN CONTENT BODY -->
                    <div class="page-content">
                        <!-- BEGIN PAGE HEADER-->
                        <!-- BEGIN PAGE BAR -->
                        <div class="page-bar">
                            <ul class="page-breadcrumb">
                                <li>
                                    <a href="index.html">Dashboard</a>
                                    <i class="fa fa-circle"></i>
                                </li>
                            </ul>
                        </div>
                        <!-- END PAGE BAR -->
                        <!-- BEGIN PAGE TITLE-->
                       
                        <!-- END PAGE TITLE-->
                        <!-- END PAGE HEADER-->
                        <!-- BEGIN DASHBOARD STATS 1-->

                          <?php
                            $total_pathology_bill_amt     = 0 ;
                            $total_discount_pathology_amt = 0 ;
                            $total_rebate_pathology_amt   = 0 ;
                            $total_payment_pathology_amt  = 0 ;

                             foreach ($patology_bill_info as $pathology_value) {
                               $total_pathology_bill_amt     = $total_pathology_bill_amt + $pathology_value->total_payable ;
                               $total_discount_pathology_amt = $total_discount_pathology_amt + $pathology_value->total_discount ;
                               $total_rebate_pathology_amt   = $total_rebate_pathology_amt + $pathology_value->total_rebate ;
                               $total_payment_pathology_amt  = $total_payment_pathology_amt + $pathology_value->total_payment_amt ;
                             }
                             $pathlogy_total_return_amt = 0 ;
                             foreach ($pathology_return_amt as $pathology_value_return_amt) {
                                 $pathlogy_total_return_amt = $pathlogy_total_return_amt + $pathology_value_return_amt->total_discount ;
                             }
                            ?>


                        <div class="row widget-row">
                            <div class="col-md-3">
                                <!-- BEGIN WIDGET THUMB -->
                                <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
                                    <h4 class="widget-thumb-heading">Petty Cash Balance</h4>
                                    <div class="widget-thumb-wrap">
                                        <i class="widget-thumb-icon bg-green icon-bulb"></i>
                                        <div class="widget-thumb-body">
                                            <span class="widget-thumb-subtitle">TK</span>
                                            <span class="widget-thumb-body-stat" style="font-size: 16px;"><?php echo number_format($petty_cash,2) ; ?></span>
                                        </div>
                                    </div>
                                </div>
                                <!-- END WIDGET THUMB -->
                            </div>
                          
                            <div class="col-md-3">
                                <!-- BEGIN WIDGET THUMB -->
                                <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
                                    <h4 class="widget-thumb-heading">Today Pathology Bill Create</h4>
                                    <div class="widget-thumb-wrap">
                                        <i class="widget-thumb-icon bg-purple icon-screen-desktop"></i>
                                        <div class="widget-thumb-body">
                                            <span class="widget-thumb-subtitle">TOTAL BILL </span>
                                            <span class="widget-thumb-body-stat"><?php echo $count_patology_bill ; ?></span>
                                    
                                        </div>
                                    </div>
                                </div>
                                <!-- END WIDGET THUMB -->
                            </div>
                            <div class="col-md-3">
                                <!-- BEGIN WIDGET THUMB -->
                                <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
                                    <h4 class="widget-thumb-heading">Today OPD Bill Create</h4>
                                    <div class="widget-thumb-wrap">
                                        <i class="widget-thumb-icon bg-blue icon-bar-chart"></i>
                                        <div class="widget-thumb-body">
                                            <span class="widget-thumb-subtitle">TOTAL BILL</span>
                                            <span class="widget-thumb-body-stat" style="font-size: 16px;"><?php echo $count_opd_bill; ?></span>
                                        </div>
                                    </div>
                                </div>
                                <!-- END WIDGET THUMB -->
                            </div>
                                <div class="col-md-3">
                                <!-- BEGIN WIDGET THUMB -->
                                <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
                                    <h4 class="widget-thumb-heading">Today IPD Admit</h4>
                                    <div class="widget-thumb-wrap">
                                        <i class="widget-thumb-icon bg-red icon-layers"></i>
                                        <div class="widget-thumb-body">
                                            <span class="widget-thumb-subtitle">TOTAL ADMIT</span>
                                            <span class="widget-thumb-body-stat" style="font-size: 16px;"><?php echo $count_ipd_admission; ?></span>
                                        </div>
                                    </div>
                                </div>
                                <!-- END WIDGET THUMB -->
                            </div>
                        </div>
                        <div class="row widget-row">
                            <div class="col-md-3">
                                <!-- BEGIN WIDGET THUMB -->
                                <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
                                    <h4 class="widget-thumb-heading">Today IPD Pathology Bill Create</h4>
                                    <div class="widget-thumb-wrap">
                                        <i class="widget-thumb-icon bg-green icon-bulb"></i>
                                        <div class="widget-thumb-body">
                                            <span class="widget-thumb-subtitle">TOTAL BILL</span>
                                            <span class="widget-thumb-body-stat" style="font-size: 16px;"><?php echo $count_ipd_patology_bill ; ?></span>
                                        </div>
                                    </div>
                                </div>
                                <!-- END WIDGET THUMB -->
                            </div>
                          
                            <div class="col-md-3">
                                <!-- BEGIN WIDGET THUMB -->
                                <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
                                    <h4 class="widget-thumb-heading">Today IPD Service Bill Create</h4>
                                    <div class="widget-thumb-wrap">
                                        <i class="widget-thumb-icon bg-purple icon-screen-desktop"></i>
                                        <div class="widget-thumb-body">
                                            <span class="widget-thumb-subtitle">TOTAL BILL </span>
                                            <span class="widget-thumb-body-stat"><?php echo $count_ipd_service_bill ; ?></span>
                                    
                                        </div>
                                    </div>
                                </div>
                                <!-- END WIDGET THUMB -->
                            </div>
                            <div class="col-md-3">
                                <!-- BEGIN WIDGET THUMB -->
                                <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
                                    <h4 class="widget-thumb-heading">Today IPD CLEARENCE Bill Create</h4>
                                    <div class="widget-thumb-wrap">
                                        <i class="widget-thumb-icon bg-blue icon-bar-chart"></i>
                                        <div class="widget-thumb-body">
                                            <span class="widget-thumb-subtitle">TOTAL BILL</span>
                                            <span class="widget-thumb-body-stat" style="font-size: 16px;"><?php echo $count_ipd_clearence_bill; ?></span>
                                        </div>
                                    </div>
                                </div>
                                <!-- END WIDGET THUMB -->
                            </div>
                                <div class="col-md-3">
                                <!-- BEGIN WIDGET THUMB -->
                                <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
                                    <h4 class="widget-thumb-heading">Today OT Booking</h4>
                                    <div class="widget-thumb-wrap">
                                        <i class="widget-thumb-icon bg-red icon-layers"></i>
                                        <div class="widget-thumb-body">
                                            <span class="widget-thumb-subtitle">TOTAL BOOKING</span>
                                            <span class="widget-thumb-body-stat" style="font-size: 16px;"><?php echo $count_ot_booking; ?></span>
                                        </div>
                                    </div>
                                </div>
                                <!-- END WIDGET THUMB -->
                            </div>
                        </div>
                        <div class="row widget-row">
                            <div class="col-md-3">
                                <!-- BEGIN WIDGET THUMB -->
                                <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
                                    <h4 class="widget-thumb-heading">Today OT CLEARENCE Bill Create</h4>
                                    <div class="widget-thumb-wrap">
                                        <i class="widget-thumb-icon bg-green icon-bulb"></i>
                                        <div class="widget-thumb-body">
                                            <span class="widget-thumb-subtitle">TOTAL BILL</span>
                                            <span class="widget-thumb-body-stat" style="font-size: 16px;"><?php echo $count_ot_clear ; ?></span>
                                        </div>
                                    </div>
                                </div>
                                <!-- END WIDGET THUMB -->
                            </div>
                          
                            <div class="col-md-3" style="display: none;">
                                <!-- BEGIN WIDGET THUMB -->
                                <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
                                    <h4 class="widget-thumb-heading">Today IPD Service Bill Create</h4>
                                    <div class="widget-thumb-wrap">
                                        <i class="widget-thumb-icon bg-purple icon-screen-desktop"></i>
                                        <div class="widget-thumb-body">
                                            <span class="widget-thumb-subtitle">TOTAL BILL </span>
                                            <span class="widget-thumb-body-stat" style="font-size: 16px;"><?php echo $count_ipd_service_bill ; ?></span>
                                    
                                        </div>
                                    </div>
                                </div>
                                <!-- END WIDGET THUMB -->
                            </div>
                            <div class="col-md-3" style="display: none;">
                                <!-- BEGIN WIDGET THUMB -->
                                <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
                                    <h4 class="widget-thumb-heading">Today IPD CLEARENCE Bill Create</h4>
                                    <div class="widget-thumb-wrap">
                                        <i class="widget-thumb-icon bg-blue icon-bar-chart"></i>
                                        <div class="widget-thumb-body">
                                            <span class="widget-thumb-subtitle">TOTAL BILL</span>
                                            <span class="widget-thumb-body-stat" style="font-size: 16px;"><?php echo $count_ipd_clearence_bill; ?></span>
                                        </div>
                                    </div>
                                </div>
                                <!-- END WIDGET THUMB -->
                            </div>
                                <div class="col-md-3" style="display: none;">
                                <!-- BEGIN WIDGET THUMB -->
                                <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
                                    <h4 class="widget-thumb-heading">Today OT Booking</h4>
                                    <div class="widget-thumb-wrap">
                                        <i class="widget-thumb-icon bg-red icon-layers"></i>
                                        <div class="widget-thumb-body">
                                            <span class="widget-thumb-subtitle">TOTAL BOOKING</span>
                                            <span class="widget-thumb-body-stat" style="font-size: 16px;"><?php echo $count_ot_booking; ?></span>
                                        </div>
                                    </div>
                                </div>
                                <!-- END WIDGET THUMB -->
                            </div>
                        </div>



                  <div class="clearfix"></div>
                        <!-- END DASHBOARD STATS 1-->
                    </div><!-- END PAGE CONTENT BODY -->
                </div><!-- END PAGE CONTENT -->             
            </div><!-- END CONTAINER -->
@endsection