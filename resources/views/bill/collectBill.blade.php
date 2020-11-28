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
                                    <a href="index.html">Bill Create</a>
                                    <i class="fa fa-circle"></i>
                                </li>
                            </ul>
                        </div>
                        <br/><br/><br/>
                        <!-- END PAGE BAR -->
                        <!-- BEGIN PAGE TITLE-->
                        <!-- END PAGE TITLE-->
                        <!-- END PAGE HEADER-->
                        <!-- BEGIN DASHBOARD STATS 1-->
                        <div class="row widget-row">
                            <div class="col-md-3">
                            <a href="{{URL::to('pathologyBillCreate')}}"><button type="button" class="btn btn-primary" style="height: 100px;width: 200px">
                             PATHOLOGY
                             </button>
                         </div>
                              <div class="col-md-3">
                            <a href="{{URL::to('opdBillCreate')}}">
                            <button type="button" class="btn btn-success" style="height: 100px;width: 200px;background: yellowgreen">
                             OPD
                             </button>
                           </a>
                         </div>
                           
                        </div>
                        <br/>
                            <div class="row widget-row">
                                   <div class="col-md-3">
                            <a href="{{URL::to('ipdAdmission')}}">
                            <button type="button" class="btn btn-success" style="height: 100px;width: 200px;">
                             IPD ADMISSION
                             </button>
                             </a>
                         </div>
                            <div class="col-md-3">
                            <a href="{{URL::to('ipdPathologyBillCreate')}}"><button type="button" class="btn btn-primary" style="height: 100px;width: 200px;background: darkorange;">
                            IPD PATHOLOGY
                             </button>
                         </div>

                            <div class="col-md-3">
                            <a href="{{URL::to('ipdServiceBill')}}"><button type="button" class="btn btn-primary" style="height: 100px;width: 200px;background: #524d09;">
                            IPD SERVICE
                             </button>
                         </div>
                            <div class="col-md-3">
                            <a href="{{URL::to('ipdBillClearence')}}"><button type="button" class="btn btn-primary" style="height: 100px;width: 200px;background: #827171;">
                           CLEARENCE IPD
                             </button>
                         </div>
                        </div>
                            <br/>
                            <div class="row widget-row">
                            <div class="col-md-3">
                            <a href="{{URL::to('OTBooking')}}"><button type="button" class="btn btn-primary" style="height: 100px;width: 200px;background: #536d21;">
                            OT BOOKING
                             </button>
                         </div>
                        <div class="col-md-3">
                            <a href="{{URL::to('OTSurgeryClinicalPosting')}}"><button type="button" class="btn btn-primary" style="height: 100px;width: 200px;background: #1f2513;">
                            OT SURJEON <br/> AND <br/> STAFFS BILL POSTING
                             </button>
                         </div>
                          <div class="col-md-3">
                            <a href="{{URL::to('OTserviceBill')}}"><button type="button" class="btn btn-primary" style="height: 100px;width: 200px;background: #771933;">
                            OT SERVICE
                             </button>
                         </div>
                          <div class="col-md-3">
                            <a href="{{URL::to('OTBillClearence')}}"><button type="button" class="btn btn-primary" style="height: 100px;width: 200px;background: #052377;">
                            CLEARENCE OT
                             </button>
                         </div

                        </div>

                  <div class="clearfix"></div>
                        <!-- END DASHBOARD STATS 1-->
                    </div><!-- END PAGE CONTENT BODY -->
                </div><!-- END PAGE CONTENT -->             
            </div><!-- END CONTAINER -->
@endsection