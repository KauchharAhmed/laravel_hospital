          @extends('admin.masterManager')
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
                                 OT Amount Distribution
                                    <i class="fa fa-circle"></i>
                                </li>
                            </ul>
                        </div>
                        <!-- END PAGE BAR -->
                        <!-- BEGIN PAGE TITLE-->
                        <h1 class="page-title"></h1>
                        <!-- END PAGE TITLE-->
                        <!-- END PAGE HEADER-->
                        <!-- BEGIN DASHBOARD STATS 1--> 
     <?php if(Session::get('succes') != null) { ?>
   <div class="alert alert-info alert-dismissible" role="alert">
  <a href="#" class="fa fa-times" data-dismiss="alert" aria-label="close"></a>
  <strong><?php echo Session::get('succes') ;  ?></strong>
  <?php Session::put('succes',null) ;  ?>
</div>
<?php } ?>
<?php
if(Session::get('failed') != null) { ?>
 <div class="alert alert-danger alert-dismissible" role="alert">
  <a href="#" class="fa fa-times" data-dismiss="alert" aria-label="close"></a>
 <strong><?php echo Session::get('failed') ; ?></strong>
 <?php echo Session::put('failed',null) ; ?>
</div>
<?php } ?>

  @if (count($errors) > 0)
    @foreach ($errors->all() as $error)      
 <div class="alert alert-danger alert-dismissible" role="alert">
   <a href="#" class="fa fa-times" data-dismiss="alert" aria-label="close"></a>
  <strong>{{ $error }}</strong>
</div>
@endforeach
@endif
<div class="row">
 
                            <div class="col-md-12">
                                <!-- BEGIN EXAMPLE TABLE PORTLET-->
                                <div class="portlet light bordered">
                                    <div class="portlet-title">
                                        <div class="caption font-dark">
                                            <i class="icon-settings font-dark"></i>
                                            <span class="caption-subject bold uppercase">OT Amount Distribution</span>
                                        </div>
                                        <div class="tools"> </div>
                                    </div>
                                    <div class="portlet-body">
                                        <table class="table table-striped table-hover table-bordered" id="sample_editable_1">
                                            <thead>
                                                <tr>
                                                    <th>Sl</th>
                                                    <th>OT Booking Date</th>
                                                    <th>OT Clear Date</th>
                                                    <th>OT Booking No</th>
                                                    <th>OT Type</th>
                                                    <th>Patient Info</th>
                                                    <th>Amount Distribution</th>
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
                                                    $ot_booking_query = DB::table('tbl_ot_booking')->where('id',$value->ot_booking_id)->first();
                                                    echo date('d M Y',strtotime($ot_booking_query->booking_date)) ;
                                                    ?>  
                                                    </td>
                                                    <td><?php echo date('d M Y',strtotime($value->bill_date)); ?></td>
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
                                                       <a href="{{URL::to('otStaffAmountDistribution/'.$value->ot_booking_id.'/'.$patient_info->id)}}"> <button type="button" class="btn btn-info btn-sm">AMOUNT DISTRIBUTION</button></a>
                                                    </td>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!-- END EXAMPLE TABLE PORTLET-->
                            </div>
                        <div class="clearfix"></div>
                        <!-- END DASHBOARD STATS 1-->
                    </div><!-- END PAGE CONTENT BODY -->
                </div><!-- END PAGE CONTENT -->             
            </div><!-- END CONTAINER -->
@endsection